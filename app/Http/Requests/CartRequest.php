<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Cho phép tất cả (hoặc return auth()->check() nếu chỉ cho user đăng nhập)
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'    => 'required|integer|exists:users,user_id',
            'product_id' => 'required|integer|exists:products,product_id',
            'quantity'   => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'    => 'Thiếu user_id',
            'user_id.integer'     => 'Người dùng không tồn tại',
            'user_id.exists'      => 'Người dùng không tồn tại',

            'product_id.required' => 'Thiếu product_id',
            'product_id.integer'  => 'Sản phẩm không tồn tại',
            'product_id.exists'   => 'Sản phẩm không tồn tại',

            'quantity.required'   => 'Thiếu số lượng',
            'quantity.integer'    => 'Số lượng phải là số nguyên',
            'quantity.min'        => 'Số lượng tối thiểu là 1',
        ];
    }
}
