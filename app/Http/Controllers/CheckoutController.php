<?php

namespace App\Http\Controllers;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class CheckoutController extends Controller
{
    /**
     * [POST] Xử lý dữ liệu giỏ hàng được gửi đến từ trang Giỏ hàng.
     */
    public function handleCheckout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để thanh toán.');
        }

        // 1. Nhận và giải mã JSON từ trường ẩn
        // Chuỗi JSON có dạng: [{"id": 1, "qty": 2}, ...]
        $selectedItemsData = json_decode($request->input('items'), true);
        
        if (empty($selectedItemsData)) {
             return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán.');
        }

        // Lấy danh sách ID CartItem được chọn
        $selectedCartItemIds = array_column($selectedItemsData, 'id'); 
        
        // 2. Truy vấn CartItem đã chọn (Giả sử ID trong DB là 'id')
        // *Chú ý: Nếu khóa chính của CartItem là 'cart_id', bạn cần chỉnh lại Model hoặc truy vấn*
        $selectedCartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('cart_id', $selectedCartItemIds) // Dùng cart_id vì JS gửi lên là cart_id
            ->get();

        // 3. Tinh chỉnh số lượng (Cập nhật số lượng tạm thời/vĩnh viễn)
        $quantityMap = array_combine(array_column($selectedItemsData, 'id'), array_column($selectedItemsData, 'qty'));
        
        $finalCartItems = collect();
        $totalAmount = 0;

        foreach ($selectedCartItems as $item) {
            $itemId = $item->cart_id; 
            $newQuantity = (int)($quantityMap[$itemId] ?? $item->quantity);
            
            // Cập nhật số lượng tạm thời cho lần thanh toán này
            $item->quantity = $newQuantity;

            // Optional: Cập nhật số lượng vĩnh viễn trong CSDL
            if ($newQuantity != $item->getOriginal('quantity')) {
                 $item->update(['quantity' => $newQuantity]);
            }

            if ($item->product) {
                 $finalCartItems->push($item);
                 $totalAmount += ($item->product->price * $newQuantity);
            }
        }
        
        // Chuyển hướng đến trang xác nhận thanh toán/địa chỉ
        // Giả sử view là 'ui-thanhtoan.pay'
        return view('ui-thanhtoan.pay', [
            'cartItems' => $finalCartItems,
            'user' => Auth::user(),
            'totalAmount' => $totalAmount,
        ]);
    }






}
