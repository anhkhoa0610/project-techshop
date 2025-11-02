<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Http\Requests\CartRequest;

class IndexController extends Controller
{
    public function index()
    {
        $topProducts = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('volume_sold')
            ->limit(4)
            ->get();
        $newProducts = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('release_date')
            ->limit(4)
            ->get();

        $allProducts = Product::withAvg('reviews', 'rating')->get();

        $reviews = Review::with('product', 'user')->orderBy('rating', 'desc')->limit(8)->get();

        $videoProducts = Product::withVideo()
            ->inRandomOrder()
            ->limit(4)
            ->get();
        return view('index', compact('topProducts', 'newProducts', 'allProducts', 'videoProducts', 'reviews'));
    }

    public function getProductsByCategory($categoryId)
    {
        $products = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('category_id', $categoryId)
            ->paginate(8);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'total' => $products->total(),
            'per_page' => $products->perPage(),
        ]);
    }

    public function searchProductsAPI(Request $request)
    {
        $keyword = $request->input('keyword');
        $products = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->search($keyword)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $products
        ]);
    }

    public function addToCart(CartRequest $request)
    {
        // Lấy dữ liệu đã validate
        $data = $request->validated();

        // Kiểm tra xem sản phẩm đã có trong giỏ chưa
        $cartItem = CartItem::where('user_id', $data['user_id'])
            ->where('product_id', $data['product_id'])
            ->first();

        if ($cartItem) {
            return response()->json([
                'message' => 'Sản phẩm đã có trong giỏ hàng!'
            ], 400);
        } else {
            CartItem::create($data);
        }

        return response()->json([
            'message' => 'Added to cart successfully!'
        ]);
    }

}
