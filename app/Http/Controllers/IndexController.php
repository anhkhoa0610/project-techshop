<?php

namespace App\Http\Controllers;
use App\Models\Category;
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
            ->limit(8)
            ->get();
            
        $newProducts = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('release_date')
            ->limit(8)
            ->get();

        $allProducts = Product::withAvg('reviews', 'rating')->get();

        $reviews = Review::with('product', 'user')->orderBy('rating', 'desc')->limit(8)->get();

        $videoProducts = Product::withVideo()
            ->inRandomOrder()
            ->limit(4)
            ->get();
        return view('ui-index.index', compact('topProducts', 'newProducts', 'allProducts', 'videoProducts', 'reviews'));
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
    $products = Product::withAvg('reviews', 'rating')
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
        ->paginate(8); // <-- Đã đúng, lấy 8 sản phẩm

    return response()->json([
        'success' => true,
        'data' => $products->items(),
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
        'total' => $products->total(),
        
        // --- THÊM 2 DÒNG NÀY ---
        'per_page' => $products->perPage(),
        'to' => $products->currentPage() * $products->perPage(), // Tính toán 'to'
        // Hoặc an toàn hơn:
        // 'to' => $products->firstItem() + $products->count() - 1,

    ]);
}

    public function categories(Request $request, $category_id = null)
    {
        // 1. Lấy TẤT CẢ danh mục để hiển thị trong combobox
        $categories = Category::all();

        // 2. Chuẩn bị query sản phẩm
        $productQuery = Product::withAvg('reviews', 'rating')
        ->withCount('reviews');
        $currentCategory = null;

        // 3. Lọc sản phẩm nếu có $category_id
        if ($category_id) {
            // Tìm category hiện tại để truyền ra view (để đánh dấu 'selected')
            $currentCategory = Category::find($category_id);
            
            // Nếu tìm thấy, lọc query
            if ($currentCategory) {
                $productQuery->where('category_id', $category_id);
            }
        }

        // 4. Lấy 8 sản phẩm (đã lọc hoặc chưa)
        $allProducts = $productQuery->paginate(8);

        // 5. Trả về view CHỈ VỚI 3 BIẾN cần thiết
        return view('ui-index.categories', compact(
            'allProducts',      // 8 sản phẩm đầu tiên (đã phân trang)
            'categories',       // TẤT CẢ categories (cho combobox)
            'currentCategory'   // Category đang chọn (hoặc null)
        ));
    }

}
