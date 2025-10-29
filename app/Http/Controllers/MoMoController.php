<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Momo;
use App\Models\Order;

class MoMoController extends Controller
{

    public function momo_payment(Request $request)
    {
        $data = $request->all();
     

        // 🔐 Thông tin cấu hình MoMo test
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        // 🧾 Thông tin đơn hàng
        $orderInfo = "Thanh toán qua MoMo";
        $amount = $data['total'] ?? 0; // lấy tổng tiền, nếu không có thì 0
        $orderId = time(); // Mã đơn hàng tạm thời
        $redirectUrl = route('momo.return'); // route trả về sau khi thanh toán
        $ipnUrl = route('momo.ipn');    // route nhận callback từ MoMo
        $extraData = "";

        // 🪪 Các tham số yêu cầu
        $requestId = time() . "";
        $requestType = "payWithATM";

        // 🔏 Tạo chuỗi ký
        $rawHash = "accessKey=" . $accessKey .
            "&amount=" . $amount .
            "&extraData=" . $extraData .
            "&ipnUrl=" . $ipnUrl .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&partnerCode=" . $partnerCode .
            "&redirectUrl=" . $redirectUrl .
            "&requestId=" . $requestId .
            "&requestType=" . $requestType;

        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        // 📦 Dữ liệu gửi sang MoMo
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

        // 🌐 Gửi request sang MoMo
        $result = $this->execPostRequest($endpoint, json_encode($body));
        $jsonResult = json_decode($result, true);

        // 💾 Lưu vào database
        Order::create([
            'user_id' => 1,
            'order_date' => now(),
            'status' => 'pending',
            'shipping_address' => 'chưa có địa chỉ',
            'payment_method' => 'momo',
            'voucher_id' => null,
        ]);

        // 🔁 Chuyển hướng người dùng sang trang thanh toán
        if (!empty($jsonResult['payUrl'])) {
            return redirect()->away($jsonResult['payUrl']);
        }

        // ❌ Nếu lỗi
        return back()->with('error', 'Không thể tạo liên kết thanh toán từ MoMo.');
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
    public function momo_return(Request $request)
    {
        // MoMo redirect về sau khi thanh toán
        $data = $request->all();

        // Tìm giao dịch theo orderId
        $momo = Momo::where('order_id', $data['orderId'] ?? '')->first();

        if ($momo) {
            $momo->update([
                'trans_id' => $data['transId'] ?? null,
                'result_code' => $data['resultCode'] ?? null,
                'message' => $data['message'] ?? null,
                'status' => $data['resultCode'] == 0 ? 'success' : 'failed',
            ]);
        }

        // ✅ Thanh toán thành công
        if (($data['resultCode'] ?? 1) == 0) {
            return redirect()->route('index')
                ->with('success', 'Thanh toán MoMo thành công!');
        }

        // ❌ Thanh toán thất bại
        return redirect()->route('cart.index')->with('error', 'Thanh toán MoMo thất bại. Vui lòng thử lại.');
    }


    public function momo_ipn(Request $request)
    {
        // Callback từ MoMo gửi về server
        $data = $request->all();

        $momo = Momo::where('order_id', $data['orderId'] ?? '')->first();

        if ($momo) {
            $momo->update([
                'trans_id' => $data['transId'] ?? null,
                'result_code' => $data['resultCode'] ?? null,
                'message' => $data['message'] ?? null,
                'status' => $data['resultCode'] == 0 ? 'success' : 'failed',
            ]);
        }

        // Trả phản hồi cho MoMo
        return response()->json([
            'resultCode' => 0,
            'message' => 'Confirm success'
        ]);
    }


}
