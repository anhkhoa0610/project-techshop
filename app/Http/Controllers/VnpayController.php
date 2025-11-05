<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\CartItem;



class VnpayController extends Controller
{
    // public function vnpay_payment(Request $request)
    // {
    //     $data = $request->all();
    //     $code_cart = rand(1, 20);

    //     // VNPAY payment configuration
    //     $vnp_BankCode = $request->input('vnp_BankCode', 'NCB'); // Mã ngân hàng
    //     $vnp_Bill_State = $request->input('vnp_Bill_State', '0'); // Trạng thái hóa đơn

    //     // VNPAY payment URL and parameters

    //     $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    //     $vnp_Returnurl = "http://127.0.0.1:8000/index";
    //     $vnp_TmnCode = "CLRNE00Z"; //Mã website tại VNPAY 
    //     $vnp_HashSecret = "M2270YUKB4B47IW310LY4GC48BL57PQA"; //Chuỗi bí mật

    //     $vnp_TxnRef = $code_cart; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
    //     $vnp_OrderInfo = 'Thanh toán đơn hàng test';
    //     $vnp_OrderType = 'billpayment';
    //     $vnp_Amount = $request->input('total', 5000) * 100; // Lấy tổng tiền từ request, mặc định 5000 nếu không có
    //     $vnp_Locale = 'vn';
    //     $vnp_BankCode = 'NCB';
    //     $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

    //     $inputData = array(
    //         "vnp_Version" => "2.1.0",
    //         "vnp_TmnCode" => $vnp_TmnCode,
    //         "vnp_Amount" => $vnp_Amount,
    //         "vnp_Command" => "pay",
    //         "vnp_CreateDate" => date('YmdHis'),
    //         "vnp_CurrCode" => "VND",
    //         "vnp_IpAddr" => $vnp_IpAddr,
    //         "vnp_Locale" => $vnp_Locale,
    //         "vnp_OrderInfo" => $vnp_OrderInfo,
    //         "vnp_OrderType" => $vnp_OrderType,
    //         "vnp_ReturnUrl" => $vnp_Returnurl,
    //         "vnp_TxnRef" => $vnp_TxnRef,

    //     );

    //     if (isset($vnp_BankCode) && $vnp_BankCode != "") {
    //         $inputData['vnp_BankCode'] = $vnp_BankCode;
    //     }
    //     if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
    //         $inputData['vnp_Bill_State'] = $vnp_Bill_State;
    //     }

    //     //var_dump($inputData);
    //     ksort($inputData);
    //     $query = "";
    //     $i = 0;
    //     $hashdata = "";
    //     foreach ($inputData as $key => $value) {
    //         if ($i == 1) {
    //             $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
    //         } else {
    //             $hashdata .= urlencode($key) . "=" . urlencode($value);
    //             $i = 1;
    //         }
    //         $query .= urlencode($key) . "=" . urlencode($value) . '&';
    //     }

    //     $vnp_Url = $vnp_Url . "?" . $query;
    //     if (isset($vnp_HashSecret)) {
    //         $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    //         $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    //     }

    //     $shippingAddress = $request->input('shipping_address');
    //     $amount = $request->input('total', 5000);
    //     // Save transaction to database
    //     $order = Order::create([
    //         'user_id' => 1,
    //         'order_date' => now(),
    //         'status' => 'pending',
    //         'shipping_address' => $shippingAddress ?? 'chưa có địa chỉ',
    //         'payment_method' => 'vnpay',
    //         'voucher_id' => null,
    //         'total_price' => $amount,
    //     ]);

    //     $returnData = array(
    //         'code' => '00',
    //         'message' => 'success',
    //         'data' => $vnp_Url
    //     );
    //     if (isset($_POST['redirect'])) {
    //         header('Location: ' . $vnp_Url);
    //         die();
    //     } else {
    //         echo json_encode($returnData);
    //     }
    // }

    public function vnpay_payment(Request $request)
    {
        $data = $request->all();
        $code_cart = rand(00, 9999);
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = route('vnpay.return');
        ;
        $vnp_TmnCode = "1VYBIYQP"; //Mã website tại VNPAY 
        $vnp_HashSecret = "NOH6MBGNLQL9O9OMMFMZ2AX8NIEP50W1"; //Chuỗi bí mật

        $vnp_TxnRef = $code_cart; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = 'Thanh toán đơn hàng test';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $request->input('total', 5000) * 100;
        $vnp_Locale = 'vn';
        // $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,

        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $shippingAddress = $request->input('shipping_address');
        $amount = $request->input('total', 5000);
        $userId = auth()->id(); // hoặc Auth::id()
        $voucher = $request->input('voucher_id');

        //Save transaction to database
        $order = Order::create([
            'user_id' => $userId,
            'order_date' => now(),
            'status' => 'pending',
            'shipping_address' => $shippingAddress ?? 'chưa có địa chỉ',
            'payment_method' => 'vnpay',
            'voucher_id' => $voucher,
            'total_price' => $amount,
        ]);
        // Lấy giỏ hàng của user

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

        $returnData = array(
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url
        );
        if (isset($_POST['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
    }

    public function vnpay_return(Request $request)
    {
        $data = $request->all();

        if (($data['vnp_ResponseCode'] ?? 1) == 0) {
            // ✅ Thanh toán thành công
            $order = Order::where('user_id', auth()->id())
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($order) {
                $order->update([
                    'status' => 'completed',
                ]);
            }

            return redirect()->route('index')->with('success', 'Thanh toán VNPAY thành công!');
        } else {
            $order = Order::where('user_id', auth()->id())
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($order) {
                $order->update([
                    'status' => 'cancelled',
                ]);
            }

            return redirect()->route('index')->with('error', 'Thanh toán VNPAY không thành công!');
        }
    }


}