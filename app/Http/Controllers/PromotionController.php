<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

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
        })->get();

        // Danh mục sản phẩm
        $categories = Category::all();

        // Lấy sản phẩm có khuyến mãi đang còn hiệu lực
        $products = Product::with([
            'discounts' => function ($query) {
                $query->active();
            }
        ])
            ->whereHas('discounts', function ($query) {
                $query->active();
            })
            ->paginate(8)
            ->withQueryString();

        $cartItemCount = 0;

        if (Auth::check()) {
            $cartItemCount = CartItem::where('user_id', Auth::id())->count('quantity');
        }
        // Biến đổi collection để trả về array sản phẩm kèm discount hiện tại và giá sau giảm
        $productItems = $products->getCollection()->map(function ($product) {
            // Lấy discount đầu tiên (nếu có nhiều, tuỳ logic bạn chọn)
            $discount = $product->discounts->first();

            // Thay 'price' bằng tên cột giá gốc trong bảng products của bạn nếu khác
            $originalPrice = $product->price ?? null;
            $finalPrice = $discount ? ($discount->sale_price ?? $originalPrice) : $originalPrice;

            return [
                'product' => $product,
                'discount' => $discount,
                'final_price' => $finalPrice,
            ];
        })->values()->all();

        // Chỉ gửi mảng dữ liệu sản phẩm, không gửi toàn bộ paginator
        return response()->json([
            'status' => 'success',
            'cartItemCount' => $cartItemCount,
            'data' => [
                'promotions' => $vouchers,
                'categories' => $categories,
                'products' => $productItems, // giờ đây là array sản phẩm kèm discount
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $products->perPage(),
                    'total' => $products->total(),
                ],
            ],
        ]);
    }

}
