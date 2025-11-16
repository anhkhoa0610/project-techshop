<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use App\Models\Momo;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\CartItem;

class MoMoController extends Controller
{

    // public function momo_payment(Request $request)
    // {
    //     $data = $request->all();
    //     $userId = auth()->id();
    //     $cartItems = CartItem::where('user_id', $userId)->get();
    //     $shoppingAddress = $request->input('shipping_address', 'chưa có địa chỉ');
    //     $voucher = $request->input('voucher_id', null);
    //     $amount = $request->input('total', $cartItems->sum(fn($i) => $i->product->price * $i->quantity));
    //     $order = Order::create([
    //         'user_id' => auth()->id(),
    //         'order_date' => now(),
    //         'status' => 'pending',
    //         'shipping_address' => $shoppingAddress,
    //         'payment_method' => 'momo',
    //         'voucher_id' => $voucher,
    //         'total_price' => $amount,
    //     ]);

    //     $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    //     $partnerCode = 'MOMOBKUN20180529';
    //     $accessKey = 'klm05TvNBzhg7h7j';
    //     $serectkey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
    //     $orderInfo = "Thanh toán qua MoMo";
    //     $amount = $data['total']; // Default amount if not provided
    //     $orderId = time(); // Use provided order_id or generate one
    //     $redirectUrl = route('momo.return');
    //     $ipnUrl = route('momo.ipn');
    //     $extraData = base64_encode(json_encode([
    //         'user_id' => $userId,
    //         'order_id' => $order->order_id,
    //     ]));

    //     $requestId = time() . "";
    //     $requestType = "payWithATM";
    //    // $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
    //     //before sign HMAC SHA256 signature
    //     $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
    //     $signature = hash_hmac("sha256", $rawHash, $serectkey);
    //     $data = array(
    //         'partnerCode' => $partnerCode,
    //         'partnerName' => "Test",
    //         "storeId" => "MomoTestStore",
    //         'requestId' => $requestId,
    //         'amount' => $amount,
    //         'orderId' => $orderId,
    //         'orderInfo' => $orderInfo,
    //         'redirectUrl' => route('momo.return'),
    //         'ipnUrl' => $ipnUrl,
    //         'lang' => 'vi',
    //         'extraData' => $extraData,
    //         'requestType' => $requestType,
    //         'signature' => $signature
    //     );
    //     $result =
    //         $this->execPostRequest($endpoint, json_encode($data));
    //     $jsonResult = json_decode($result, true); 
    //     if (isset($jsonResult['payUrl'])) {
    //         return redirect()->to($jsonResult['payUrl']);
    //     } else {
    //        return redirect()->route('cart.index')->with('error', 'Không thể tạo liên kết thanh toán từ MoMo. Vui lòng thử lại.');
    //     }


    // }

   
   public function momo_payment(Request $request)
    {
        $data = $request->all();
        $userId = auth()->id();
        $cartItems = CartItem::where('user_id', $userId)->get();
        
        // Lấy thông tin cần lưu
        $shoppingAddress = $request->input('shipping_address', 'chưa có địa chỉ');
        $voucher = $request->input('voucher_id', null);
        
        // Tính tổng tiền nếu thiếu, nhưng nên dựa vào input từ form checkout
        $amount = $request->input('total', $cartItems->sum(fn($i) => $i->product->price * $i->quantity));

        // ❌ BỎ TẠO ĐƠN HÀNG TẠM THỜI TẠI ĐÂY
        // $order = Order::create([...]); 
        
        // 🟢 SỬ DỤNG $orderId LÀ MỘT CHUỖI DUY NHẤT ĐỂ MOẠNG MOOMO TRUY VẤN
        $orderId = time(); // MoMo sẽ sử dụng orderId này
        
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $serectkey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán cho đơn hàng #{$orderId}"; 
        // Lấy amount từ biến $amount đã tính/nhận
        
        $redirectUrl = route('momo.return');
        $ipnUrl = route('momo.ipn');
        
        // 🟢 LƯU TẤT CẢ THÔNG TIN CẦN THIẾT VÀO extraData
        // Để tái tạo Order và OrderDetails khi quay lại từ MoMo
        $extraData = base64_encode(json_encode([
            'user_id' => $userId,
            'temporary_order_id' => $orderId, // Sử dụng ID tạm thời này để tham chiếu
            'shipping_address' => $shoppingAddress,
            'voucher_id' => $voucher,
            'total_price' => $amount,
            // KHÔNG LƯU cartItems vào extraData. Sẽ truy vấn lại trong momo_return.
        ]));

        $requestId = time() . "";
        $requestType = "payWithATM";
        
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $serectkey);
        
        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl, // Đã sửa
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        
        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true); 
        
        if (isset($jsonResult['payUrl'])) {
            return redirect()->to($jsonResult['payUrl']);
        } else {
            return redirect()->route('cart.index')->with('error', 'Không thể tạo liên kết thanh toán từ MoMo. Vui lòng thử lại.');
        }
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


    // public function momo_return(Request $request)
    // {
    //     $data = $request->all();

    //     // Giải mã extraData
    //     $extraData = json_decode(base64_decode($data['extraData'] ?? ''), true);
    //     $orderId = $extraData['order_id'] ?? null;

    //     // Tìm đơn hàng theo order_id
    //     $order = Order::where('order_id', $orderId)->first();

    //     if (!$order) {
    //         return redirect()->route('cart.index')->with('error', 'Không tìm thấy đơn hàng!');
    //     }


    //     // 🟢 Nếu thanh toán thành công
    //     if (($data['resultCode'] ?? 1) == 0) {
    //         $order->update(['status' => 'processing']);

    //         $userId = auth()->id();
    //         $cartItems = CartItem::where('user_id', $userId)->get();

    //         foreach ($cartItems as $item) {
    //             OrderDetail::create([
    //                 'order_id' => $order->order_id,
    //                 'product_id' => $item->product_id,
    //                 'quantity' => $item->quantity,
    //                 'unit_price' => $item->product->price * $item->quantity,
    //             ]);
    //         }

    //         // Xoá giỏ hàng sau khi thanh toán thành công
    //         CartItem::where('user_id', $userId)->delete();

    //         return redirect()->route('index')->with('success', 'Thanh toán MoMo thành công!');
    //     }
    //     // 🔴 Nếu thất bại


    //     return redirect()->route('cart.index')->with('error', 'Thanh toán MoMo thất bại!');

    // }


    public function momo_return(Request $request)
    {
        $data = $request->all();

        // 🟢 Giải mã extraData để lấy thông tin đơn hàng
        $extraData = json_decode(base64_decode($data['extraData'] ?? ''), true);
        
        $userId = $extraData['user_id'] ?? auth()->id(); // Lấy userId từ extraData
        $orderIdMoMo = $extraData['temporary_order_id'] ?? null;
        
        // 🔴 KIỂM TRA MỘT LẦN NỮA XEM ĐƠN HÀNG ĐÃ ĐƯỢC LƯU CHƯA (Tránh trùng lặp do user f5)
        $order = Order::where('order_id', $orderIdMoMo)->first();
        if ($order) {
            return redirect()->route('index')->with('info', 'Đơn hàng đã được xử lý trước đó.');
        }
        
        // 🟢 Nếu thanh toán thành công
        if (($data['resultCode'] ?? 1) == 0) {
            
            // 1. TẠO ĐƠN HÀNG MỚI
            $order = Order::create([
                'user_id' => $userId,
                'order_date' => now(),
                'status' => 'pending', // Bắt đầu ở trạng thái processing
                'shipping_address' => $extraData['shipping_address'] ?? 'chưa có địa chỉ',
                'payment_method' => 'momo',
                'voucher_id' => $extraData['voucher_id'] ?? null,
                'total_price' => $extraData['total_price'] ?? 0,
                'order_momo_id' => $orderIdMoMo, // Thêm cột này để lưu ID MoMo tham chiếu
            ]);
            
            // 2. LẤY CART ITEMS VÀ TẠO ORDER DETAILS
            $cartItems = CartItem::where('user_id', $userId)->get();
            
            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->order_id, // Sử dụng ID đơn hàng vừa tạo
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->product->price * $item->quantity,
                ]);
            }

            // 3. XÓA GIỎ HÀNG
            CartItem::where('user_id', $userId)->delete();

            return redirect()->route('index')->with('success', 'Thanh toán MoMo thành công và đơn hàng đã được tạo!');
        }
        
        // 🔴 Nếu thất bại hoặc hủy bỏ
        return redirect()->route('cart.index')->with('error', 'Thanh toán MoMo thất bại hoặc bị hủy!');
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
