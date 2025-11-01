<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Review;

class UIProductDetailsController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $avg = $product->reviews()->avg('rating');
        $reviews_count = $product->reviews()->count();
        $reviewSummary = $product->getReviewSummary();
        $reviews = $product->getReviews();


        // return response()->json([
        //     'success' => true,
        //     'message' => 'Danh sách danh mục',
        //     'data' => $reviewSummary
        // ], 200);


        return view('ui-product-details.product', compact('product', 'avg', 'reviews_count', 'reviewSummary', 'reviews'));
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
    public function store(ReviewRequest $request)
    {

        $validated = $request->validated();
        $review = Review::create([
            'product_id' => $validated['product_id'],
            'user_id' => $validated['user_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'review_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đánh giá đã được thêm thành công',
            'data' => $review
        ], 201);
    }
}
