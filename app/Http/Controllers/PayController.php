<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\User;



class PayController extends Controller
{
    public function index()
    {
        return view('ui-thanhtoan.pay');
    }
    public function pay(Request $request) // Đây là phương thức bị thiếu
    {
        // ... (Phần 1: Nhận và giải mã JSON)
        $selectedItemsData = json_decode($request->input('items'), true);
        $selectedCartItemIds = array_column($selectedItemsData, 'id'); // Vẫn là 'id' từ JSON gửi lên

        // 2. SỬA ĐỔI: Thay 'id' thành 'cart_id' (hoặc tên khóa chính chính xác của bảng)
        $selectedCartItems = CartItem::with('product')
            ->whereIn('cart_id', $selectedCartItemIds) // <<< ĐÃ SỬA THÀNH 'cart_id'
            ->get();

        // 3. Tinh chỉnh số lượng (sử dụng $item->cart_id nếu cần)
        $quantityMap = array_combine(array_column($selectedItemsData, 'id'), array_column($selectedItemsData, 'qty'));
        foreach ($selectedCartItems as $item) {
            // Sử dụng $item->{$item->getKeyName()} để lấy khóa chính
            $item->quantity = $quantityMap[$item->getKey()] ?? $item->quantity;
        }

        return view('ui-thanhtoan.pay', [
            'cartItems' => $selectedCartItems,
            'user' => User::find(auth()->id()),
        ]);

    }
}
