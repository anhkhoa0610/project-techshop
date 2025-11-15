<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;
use App\Models\CartItem;
use App\Http\Requests\CartRequest;


class UIProductDetailsController extends Controller
{
    public function show($id)
    {
        $product = Product::with('specs', 'discounts')->findOrFail($id);

        $avg = $product->reviews()->avg('rating');
        $reviews_count = $product->reviews()->count();
        $reviewSummary = $product->getReviewSummary();
        $reviews = $product->getReviews();
        $cartItems_count = auth()->check() ? auth()->user()->cartItemsCount() : 0;
        $hasReviewed = auth()->check()
            ? $product->reviews()->where('user_id', auth()->id())->exists()
            : false;

        return view('ui-product-details.product', compact(
            'product',
            'avg',
            'reviews_count',
            'reviewSummary',
            'reviews',
            'cartItems_count',
            'hasReviewed'
        ));
    }

    public function index($id, Request $request)
    {
        $product = Product::findOrFail($id);
        $rating = $request->get('rating');
        $reviews = $product->getFilteredReviews($rating);
        return response()->json([
            'success' => true,
            'message' => 'Danh sách đánh giá đã lọc',
            'data' => $reviews
        ]);
    }
    public function storeReview(ReviewRequest $request)
    {

        $validated = $request->validated();
        $review = Review::create([
            'product_id' => $validated['product_id'],
            'user_id' => $validated['user_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'review_date' => now(),
        ]);
        $product = Product::findOrFail($validated['product_id']);
        $avg = $product->reviews()->avg('rating');

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá đã được thêm thành công',
            'data' => [
                'review' => $review,
                'avg' => $avg
            ]
        ], 201);
    }
    public function updateReview(ReviewRequest $request, $id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền sửa đánh giá này.'
            ], 403);
        }

        $validated = $request->validated();
        $product = Product::findOrFail($validated['product_id']);
        $avg = $product->reviews()->avg('rating');

        $review->update([
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? $review->comment,
            'review_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá đã được cập nhật thành công!',
            'data' => [
                'review' => $review,
                'avg' => $avg
            ]
        ]);
    }

    public function deleteReview($id)
    {
        $review = Review::findOrFail($id);

        if ($review->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xóa đánh giá này.'
            ], 403);
        }

        $productId = $review->product_id;
        $review->delete();
        $product = Product::findOrFail($productId);
        $avg = $product->reviews()->avg('rating');
        return response()->json([
            'success' => true,
            'message' => 'Đánh giá đã được xóa thành công!',
            'data' => [
                'avg' => $avg
            ]
        ]);
    }

    public function filterProducts(Request $request)
    {
        $categoryId = $request->input('category_id');
        $supplierId = $request->input('supplier_id');

        $products = (new Product)->getFilteredProducts($categoryId, $supplierId);

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    public function addToCart(CartRequest $request)
    {
        $userId = auth()->id();
        $productId = $request->product_id;
        $quantity = $request->quantity;

        $cartItem = CartItem::addOrUpdate($userId, $productId, $quantity);

        return response()->json([
            'success' => true,
            'message' => "Đã thêm ({$quantity}) sản phẩm vào giỏ hàng!",
            'item' => $cartItem,
        ]);
    }

}
