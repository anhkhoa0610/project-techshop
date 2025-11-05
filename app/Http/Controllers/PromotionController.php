<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Voucher;

class PromotionController extends Controller
{
    public function index()
    {
        return view('ui-promotion.promotion');
    }

    public function apiIndex()
    {
        $promotions = Voucher::all();
        $categories = Category::all();
        $products = Product::all();

        return response()->json([
            'status' => 'success',
            'promotions' => $promotions,
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}
