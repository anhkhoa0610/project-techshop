<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    /**
     * Hiển thị danh sách tất cả đánh giá
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);
        $search = $request->input('search');

       if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($q) use ($search) {
                    $q->where('full_name', 'LIKE', "%{$search}%");
                })
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('product_name', 'LIKE', "%{$search}%");
                    });
            });
        }

        $reviews = $query->orderBy('review_id', 'desc')->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('crud_review.index', compact('reviews'))->render()
            ]);
        }

        return view('crud_review.index', compact('reviews'));
    }

    /**
     * Hiển thị form tạo đánh giá mới
     */
    public function create()
    {
        $users = User::all();
        $products = Product::all();

        if ($products->isEmpty()) {
            return redirect()->back()->with('error', 'Không có sản phẩm nào để đánh giá.');
        }

        if ($users->isEmpty()) {
            return redirect()->back()->with('error', 'Không có người dùng nào trong hệ thống.');
        }

        return view('crud_review.create', compact('users', 'products'));
    }

    /**
     * Lưu đánh giá mới vào database
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'user_id' => 'required|exists:users,user_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'review_date' => 'required|date|before_or_equal:today',
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'user_id.required' => 'Vui lòng chọn người dùng.',
            'user_id.exists' => 'Người dùng không tồn tại.',
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.integer' => 'Số sao phải là số nguyên.',
            'rating.min' => 'Số sao tối thiểu là 1.',
            'rating.max' => 'Số sao tối đa là 5.',
            'comment.max' => 'Bình luận không được vượt quá 1000 ký tự.',
            'review_date.required' => 'Vui lòng chọn ngày đánh giá.',
            'review_date.date' => 'Ngày đánh giá không hợp lệ.',
            'review_date.before_or_equal' => 'Ngày đánh giá không được sau ngày hôm nay.',
        ]);

        // Kiểm tra user đã đánh giá sản phẩm này chưa
        $existingReview = Review::where('product_id', $request->product_id)
            ->where('user_id', $request->user_id)
            ->first();

        if ($existingReview) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Người dùng này đã đánh giá sản phẩm này rồi.');
        }

        Review::create([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'review_date' => $request->review_date,
        ]);

        return redirect()
            ->route('reviews.index')
            ->with('success', 'Đánh giá đã được thêm thành công.');
    }

    /**
     * Hiển thị form chỉnh sửa đánh giá
     */
    public function edit($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $users = User::all();
        $products = Product::all();

        return view('crud_review.edit', compact('review', 'users', 'products'));
    }

    /**
     * Cập nhật đánh giá trong database
     */
    public function update(Request $request, $reviewId)
    {
        $review = Review::findOrFail($reviewId);

        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'user_id' => 'required|exists:users,user_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'review_date' => 'required|date|before_or_equal:today',
        ], [
            'product_id.required' => 'Vui lòng chọn sản phẩm.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'user_id.required' => 'Vui lòng chọn người dùng.',
            'user_id.exists' => 'Người dùng không tồn tại.',
            'rating.required' => 'Vui lòng chọn số sao đánh giá.',
            'rating.integer' => 'Số sao phải là số nguyên.',
            'rating.min' => 'Số sao tối thiểu là 1.',
            'rating.max' => 'Số sao tối đa là 5.',
            'comment.max' => 'Bình luận không được vượt quá 1000 ký tự.',
            'review_date.required' => 'Vui lòng chọn ngày đánh giá.',
            'review_date.date' => 'Ngày đánh giá không hợp lệ.',
            'review_date.before_or_equal' => 'Ngày đánh giá không được sau ngày hôm nay.',
        ]);

        // Kiểm tra xem user và product có thay đổi không, nếu có thì kiểm tra trùng
        if ($review->product_id != $request->product_id || $review->user_id != $request->user_id) {
            $existingReview = Review::where('product_id', $request->product_id)
                ->where('user_id', $request->user_id)
                ->where('review_id', '!=', $reviewId)
                ->first();

            if ($existingReview) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Người dùng này đã đánh giá sản phẩm này rồi.');
            }
        }

        $review->update([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'review_date' => $request->review_date,
        ]);

        return redirect()
            ->route('reviews.index')
            ->with('success', 'Đánh giá đã được cập nhật thành công.');
    }

    /**
     * Xóa đánh giá từ database
     */
    public function destroy($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đánh giá đã được xóa thành công.'
            ]);
        }

        return redirect()
            ->route('crud_review.index')
            ->with('success', 'Đánh giá đã được xóa thành công.');
    }
}