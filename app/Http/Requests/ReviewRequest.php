<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,product_id',
            'user_id' => 'required|exists:users,user_id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Vui lòng chọn sản phẩm.',
            'product_id.exists' => 'Sản phẩm không tồn tại.',
            'user_id.required' => 'Vui lòng chọn người dùng.',
            'user_id.exists' => 'Người dùng không tồn tại.',
            'rating.required' => 'Vui lòng chọn điểm đánh giá.',
            'rating.integer' => 'Điểm đánh giá phải là số nguyên.',
            'rating.min' => 'Điểm tối thiểu là 1.',
            'rating.max' => 'Điểm tối đa là 5.',
            'comment.max' => 'Bình luận không được vượt quá 1000 ký tự.',
            'review_date.required' => 'Vui lòng chọn ngày đánh giá.',
            'review_date.before_or_equal' => 'Ngày đánh giá không hợp lệ (không được lớn hơn hôm nay).',
        ];
    }
}