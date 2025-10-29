<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Momo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MoMoController extends Controller
{
    // Cập nhật hàm momo_payment để tạo Order ngay lập tức và sử dụng ID của Order
    public function momo_payment(Request $request)
    {
        $data = $request->all();

        // Lấy User ID: Ưu tiên người dùng đăng nhập, nếu không có, dùng ID 1.
        $userId = auth()->id() ?? 1;
        $amount = $data['total'] ?? 0;

        // FIX LỖI ROUTE KHÔNG TỒN TẠI (Gây lỗi trả về HTML)
        if (!\Route::has('momo.return') || !\Route::has('momo.ipn')) {
            return response()->json(['error' => 'Lỗi cấu hình Route: Vui lòng định nghĩa route "momo.return" và "momo.ipn".'], 500);
        }

        DB::beginTransaction();

        try {
            // Kiểm tra user_id mặc định
            // FIX LỖI 1054 đã được áp dụng trước đó (giả định khóa chính là 'user_id')
            if (!auth()->check() && !DB::table('users')->where('user_id', 1)->exists()) {
                DB::rollBack();
                return response()->json(['error' => 'Lỗi: User ID 1 (mặc định) không tồn tại. Vui lòng đăng nhập.'], 400);
            }

            // 💾 1.1. Tạo Order (Trạng thái ban đầu là pending)
            $order = Order::create([
                'user_id' => $userId,
                'order_date' => now(),
                'status' => 'pending',
                'shipping_address' => $data['shipping_address'] ?? 'Địa chỉ không cung cấp',
                'payment_method' => 'momo',
                'voucher_id' => $data['voucher_id'] ?? null,
                'total_price' => $amount,
            ]);

            // Dùng ID của Order làm mã định danh duy nhất cho MoMo
            $localOrderId = $order->id;

            // 🚨 BẮT LỖI GỐC: Nếu Order không tạo được (ID rỗng), dừng lại và báo lỗi chi tiết.
            if (empty($localOrderId)) {
                // Lỗi này xảy ra khi Order::create thất bại do lỗi NOT NULL hoặc Mass Assignment chưa được giải quyết
                throw new \Exception("Lỗi nghiêm trọng: Không thể tạo đơn hàng. Vui lòng kiểm tra Model Order.php (thiếu \$fillable) hoặc cấu trúc bảng 'orders' (thiếu giá trị cho cột NOT NULL).");
            }

            // 💾 1.2. Tạo bản ghi Momo tạm thời để log, sử dụng ID của Order
            Momo::create([
                'order_id' => $localOrderId, // Truyền giá trị số nguyên vào Eloquent
                'trans_id' => null,
                'result_code' => 999,
                'message' => 'Created pending order for MoMo payment.',
                'status' => 'pending',
                'amount' => $amount,
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('FATAL DATABASE ERROR (MoMo Payment): ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            // 🚨 Trả về lỗi chi tiết hơn nếu đang ở môi trường dev
            $errorMsg = app()->environment('local', 'staging')
                ? 'Lỗi DB: ' . $e->getMessage() . '. Kiểm tra logs!'
                : 'Lỗi server khi tạo đơn hàng. Vui lòng thử lại.';

            return response()->json(['error' => $errorMsg], 500);
        }

        // --- 2. Gửi API đến MoMo ---
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "Thanh toán cho đơn hàng: " . $localOrderId;
        $orderId = $localOrderId; // PHP sẽ tự động ép kiểu thành chuỗi khi cần thiết cho API
        $amount = $data['total'] ?? 0;
        $redirectUrl = route('momo.return');
        $ipnUrl = route('momo.ipn');
        $extraData = "";

        $requestId = time() . "";
        $requestType = "payWithATM";

        // 🔏 Chuỗi ký (Signature)
        // $localOrderId sẽ tự động ép kiểu thành chuỗi trong rawHash
        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        // 📦 Body gửi MoMo
        $body = [
            'partnerCode' => $partnerCode,
            'partnerName' => 'TechStore',
            'storeId' => 'TechStoreMomo',
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
        ];

        // 🌐 Gửi request đến MoMo
        $result = $this->execPostRequest($endpoint, json_encode($body));
        $jsonResult = json_decode($result, true);

        // 🔁 Trả về URL chuyển hướng (JSON response cho AJAX)
        if (!empty($jsonResult['payUrl'])) {
            return response()->json(['redirect_url' => $jsonResult['payUrl']]);
        }

        // Cập nhật trạng thái lỗi nếu MoMo không trả về payUrl (sau khi Order đã tạo thành công)
        // Dùng fresh() để đảm bảo Order model có trạng thái mới nhất sau commit
        $order->fresh()->update(['status' => 'failed', 'payment_method' => 'momo_failed']);
        return response()->json(['error' => $jsonResult['message'] ?? 'MoMo API lỗi: Không thể tạo URL thanh toán.'], 400);
    }

    private function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    // Giữ nguyên momo_return và momo_ipn
    public function momo_return(Request $request)
    {
        $data = $request->all();
        $orderId = $data['orderId'] ?? '';

        $order = Order::find($orderId);
        $momo = Momo::where('order_id', $orderId)->first();

        if ($momo) {
            $momo->update([
                'trans_id' => $data['transId'] ?? null,
                'result_code' => $data['resultCode'] ?? null,
                'message' => $data['message'] ?? null,
                'status' => $data['resultCode'] == 0 ? 'success' : 'failed',
            ]);
        }

        if ($order) {
            if (($data['resultCode'] ?? 1) == 0) {
                $order->update(['status' => 'paid']);
                return redirect()->route('index')->with('success', 'Thanh toán MoMo thành công! Đơn hàng đã được xác nhận.');
            } else {
                $order->update(['status' => 'failed']);
                return redirect()->route('cart.index')->with('error', 'Thanh toán MoMo thất bại. Vui lòng kiểm tra lại giỏ hàng.');
            }
        }

        return redirect()->route('cart.index')->with('error', 'Không tìm thấy đơn hàng tương ứng.');
    }

    public function momo_ipn(Request $request)
    {
        $data = $request->all();
        $orderId = $data['orderId'] ?? '';

        $order = Order::find($orderId);
        $momo = Momo::where('order_id', $orderId)->first();

        if ($momo) {
            $momo->update([
                'trans_id' => $data['transId'] ?? null,
                'result_code' => $data['resultCode'] ?? null,
                'message' => $data['message'] ?? null,
                'status' => $data['resultCode'] == 0 ? 'success' : 'failed',
            ]);
        }

        if ($order) {
            if (($data['resultCode'] ?? 1) == 0) {
                $order->update(['status' => 'paid']);
            } else {
                $order->update(['status' => 'failed']);
            }
        }

        return response()->json([
            'resultCode' => 0,
            'message' => 'Confirm success'
        ]);
    }
}
