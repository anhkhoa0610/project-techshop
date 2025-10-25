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
        $user_Id = 1;

        $cartItems = CartItem::with('product')
            ->where('user_id', $user_Id)
            ->get();

        return view('ui-giohang.cart', ['cartItems' => $cartItems 
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

}
