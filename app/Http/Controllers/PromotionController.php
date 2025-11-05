<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDiscount;
use App\Models\Voucher;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    // Hiển thị trang khuyến mãi
    public function index()
    {
        return view('ui-promotion.promotion');
    }

    // API trả dữ liệu khuyến mãi
    public function apiIndex()
    {
        // Lấy danh sách voucher còn hạn
        $vouchers = Voucher::where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>', now());
            })
            ->get();

        // Danh mục sản phẩm
        $categories = Category::all();

        // Lấy sản phẩm có khuyến mãi đang còn hiệu lực
        $products = Product::with(['discounts' => function ($query) {
                $query->active();
            }])
            ->whereHas('discounts', function ($query) {
                $query->active();
            })
            ->take(12)
            ->get();

        // Trả dữ liệu JSON về frontend
        return response()->json([
            'status' => 'success',
            'data' => [
                'promotions' => $vouchers,
                'categories' => $categories,
                'products' => $products,
            ],
        ]);
    }
}
