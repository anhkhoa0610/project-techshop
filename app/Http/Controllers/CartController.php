<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Hiển thị trang giỏ hàng.
     */
    public function index()
    {
        // Đảm bảo chỉ người dùng đã đăng nhập mới xem được giỏ hàng
        if (!Auth::check()) {
            return redirect()->route('login');
        }
         $cartItemCount = 0;

        if (Auth::check()) {
            $cartItemCount = CartItem::where('user_id', Auth::id())->count('quantity');
        }

        // Lấy tất cả CartItem của người dùng hiện tại
        // Giả sử Model CartItem có quan hệ 'product'
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

        return view('ui-giohang.cart', compact('cartItems','cartItemCount'));
    }

    public function delete(string $cartId)
    {
        $cartItem = CartItem::where('cart_id', $cartId)->where('user_id', Auth::id())->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Mục giỏ hàng không tồn tại hoặc không thuộc về bạn.'], 404);
        }

        $cartItem->delete();
        return response()->json(['message' => 'Đã xóa sản phẩm khỏi giỏ hàng.'], 200);
    }

    /**
     * [POST] Cập nhật số lượng CartItem bằng AJAX.
     */
    public function updateQuantity(Request $request, string $cartId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        
        $cartItem = CartItem::where('cart_id', $cartId)->where('user_id', Auth::id())->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Mục giỏ hàng không tồn tại hoặc không thuộc về bạn.'], 404);
        }

        $cartItem->quantity = $request->input('quantity');
        $cartItem->save();

        return response()->json(['message' => 'Cập nhật số lượng thành công.'], 200);
    }
}