<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Momo;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\CartItem;

class MoMoController extends Controller
{

    public function momo_payment(Request $request)
    {
        $data = $request->all();


        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $serectkey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = "Thanh toán qua MoMo";
        $amount = $data['total']; // Default amount if not provided
        $orderId = time() . ""; // Use provided order_id or generate one
        $redirectUrl = "http://127.0.0.1:8000/index";
        $ipnUrl = "http://127.0.0.1:8000/index";
        $extraData = "";

        $requestId = time() . "";
        $requestType = "payWithATM";
        // $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
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
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result =
            $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);  // decode json

        $shippingAddress = $request->input('shipping_address'); // hoặc $data['shipping_address']

        $order = Order::create([
            'user_id' => 1,
            'order_date' => now(),
            'status' => 'pending',
            'shipping_address' => $shippingAddress ?? 'chưa có địa chỉ',
            'payment_method' => 'momo',
            'voucher_id' => null,
            'total_price' => $amount,
        ]);
        $orderId = $order->order_id; // Lấy id đơn hàng vừa tạo

        // Lấy giỏ hàng của user
        $userId = 1; // hoặc Auth::id()
        $cartItems = CartItem::where('user_id', $userId)->get();

        foreach ($cartItems as $item) {
            $orderDetail = OrderDetail::where('order_id', $order->order_id)
                ->where('product_id', $item->product_id)
                ->first();

            $newQuantity = $item->quantity;
            $newUnitPrice = $item->product->price * $newQuantity;

            if ($orderDetail) {
                // Nếu đã có, cộng dồn số lượng và unit_price
                $orderDetail->quantity += $newQuantity;
                $orderDetail->unit_price += $newUnitPrice;
                $orderDetail->save();
            } else {
                // Nếu chưa có, tạo mới
                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'product_id' => $item->product_id,
                    'quantity' => $newQuantity,
                    'unit_price' => $newUnitPrice,
                ]);
            }
        }

        // (Tuỳ chọn) Xoá giỏ hàng sau khi đặt hàng
        CartItem::where('user_id', $userId)->delete();

        if (isset($jsonResult['payUrl'])) {
            return redirect()->to($jsonResult['payUrl']);
        } else {
            return back()->with('error', 'Không thể tạo liên kết thanh toán từ MoMo.');
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
