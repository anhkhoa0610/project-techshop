<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Http\Requests\CartRequest;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\Supplier;


class IndexController extends Controller
{
    public function index()
    {
        $topProducts = Product::with(['specs', 'discounts'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('volume_sold')
            ->limit(8)
            ->get();

        $newProducts = Product::with(['specs', 'category', 'supplier', 'discounts'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('release_date')
            ->limit(8)
            ->get();

        $categories = Category::all();

        $cartItemCount = 0;

        if (Auth::check()) {
            $cartItemCount = CartItem::where('user_id', Auth::id())->count('quantity');
        }

        $posts = Post::query()->inRandomOrder()->limit(4)->get();

        $reviews = Review::with('product', 'user')->orderBy('rating', 'desc')->limit(8)->get();

        $videoProducts = Product::withVideo()
            ->inRandomOrder()
            ->limit(4)
            ->get();
        return view('ui-index.index', compact('topProducts', 'newProducts', 'videoProducts', 'reviews', 'categories', 'posts', 'cartItemCount'));
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
        $data = $request->validated();

        $cartItemCount = 0;
        $product = Product::find($data['product_id']);

        if (!$product) {
            return response()->json([
                'message' => 'Sản phẩm không tồn tại!'
            ], 404);
        }

        $requestedQuantity = $data['quantity'];
        $availableStock = $product->stock_quantity;

        $cartItem = CartItem::where('user_id', $data['user_id'])
            ->where('product_id', $data['product_id'])
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $requestedQuantity;

            if ($newQuantity > $availableStock) {
                return response()->json([
                    'message' => 'Không thể thêm! Tổng số lượng trong giỏ hàng (' . $newQuantity . ') vượt quá số lượng stock hiện có (' . $availableStock . ') của sản phẩm.'
                ], 400);
            }

            $cartItem->update(['quantity' => $newQuantity]);

            return response()->json([
                'message' => 'Cập nhật số lượng sản phẩm trong giỏ hàng thành công!',
                'cart_item' => $cartItem
            ]);
        } else {
            if ($requestedQuantity > $availableStock) {
                return response()->json([
                    'message' => 'Số lượng yêu cầu vượt quá số lượng stock hiện có (' . $availableStock . ') của sản phẩm.'
                ], 400);
            }
            $cartItem = CartItem::create($data);

            $cartItemCount = CartItem::where('user_id', Auth::id())->count('quantity');
            return response()->json([
                'message' => 'Thêm sản phẩm vào giỏ hàng thành công!',
                'cart_item' => $cartItem,
                'cartItemCount' => $cartItemCount,
            ]);
        }
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

        $suppliers = Supplier::all();

        $productQuery = Product::with(['specs'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');
        $currentCategory = null;

        if ($category_id) {
            $currentCategory = Category::find($category_id);
            if ($currentCategory) {
                $productQuery->where('category_id', $category_id);
            } else {
                return redirect()->route('index');
            }
        }

        $cartItemCount = 0;

        if (Auth::check()) {
            $cartItemCount = CartItem::where('user_id', Auth::id())->count('quantity');
        }

        $allProducts = $productQuery->paginate(8);

        $posts = Post::query()->inRandomOrder()->limit(6)->get();


        return view('ui-index.categories', compact(
            'allProducts',
            'categories',
            'currentCategory',
            'posts',
            'cartItemCount',
            'suppliers'
        ));
    }
}
