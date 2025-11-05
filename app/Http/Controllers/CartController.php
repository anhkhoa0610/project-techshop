<?php

namespace App\Http\Controllers;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Giả lập user_id = 1 tạm thời
        if (!Auth::check()) {
            return redirect()->route('login');
        } else {
            $user_Id = auth()->id(); 
        }

        $cartItems = CartItem::with('product')
            ->where('user_id', $user_Id)
            ->get();

        return view('ui-giohang.cart', [
            'cartItems' => $cartItems
        ]);
    }


    public function destroy($cart_id)
    {
        $cart = CartItem::findOrFail($cart_id);
        $cart->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa danh mục thành công!'
        ]);
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addToCart(Request $request)
    {
        $user_id = Auth::id() ?? 1; // fallback nếu chưa đăng nhập
        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        // Kiểm tra sản phẩm đã có trong giỏ chưa
        $cartItem = CartItem::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'quantity' => $quantity,
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Thêm vào giỏ hàng thành công!'
            ]);
        } else {
            return redirect()->back()->with('success', 'Thêm vào giỏ hàng thành công!');
        }
    }
}
