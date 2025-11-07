<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Http\Requests\CartRequest;
use App\Models\Post;

class IndexController extends Controller
{
    public function index()
    {
        $topProducts = Product::with(['specs'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('volume_sold')
            ->limit(8)
            ->get();

        $newProducts = Product::with(['specs', 'category', 'supplier'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('release_date')
            ->limit(8)
            ->get();

        $categories = Category::all();

        $posts = Post::query()->inRandomOrder()->limit(4)->get();

        $reviews = Review::with('product', 'user')->orderBy('rating', 'desc')->limit(8)->get();

        $videoProducts = Product::withVideo()
            ->inRandomOrder()
            ->limit(4)
            ->get();
        return view('ui-index.index', compact('topProducts', 'newProducts', 'videoProducts', 'reviews', 'categories', 'posts'));
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

    public function filter(Request $request)
    {
        $products = Product::with(['specs'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->filter(
                $request->min_price,
                $request->max_price,
                $request->category_id,
                $request->supplier_id,
                $request->rating,
                $request->stock,
                $request->release_date
            )
            ->paginate(8);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'total' => $products->total(),
            'per_page' => $products->perPage(),
            'to' => $products->currentPage() * $products->perPage(),
        ]);
    }

    public function categories(Request $request, $category_id = null)
    {
        $categories = Category::all();

        $productQuery = Product::with(['specs'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');
        $currentCategory = null;

        if ($category_id) {

            $currentCategory = Category::find($category_id);

            if ($currentCategory) {
                $productQuery->where('category_id', $category_id);
            }
        }

        $allProducts = $productQuery->paginate(8);

        $posts = Post::query()->inRandomOrder()->limit(6)->get();


        return view('ui-index.categories', compact(
            'allProducts',
            'categories',
            'currentCategory',
            'posts'
        ));
    }

}
