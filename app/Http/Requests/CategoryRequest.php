<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cho phép tất cả request, có thể thêm kiểm tra quyền tại đây
    }

    public function rules(): array
    {
        return [
            'category_name' => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'category_name.required' => 'Tên danh mục là bắt buộc.',
            'category_name.string' => 'Tên danh mục phải là chuỗi.',
            'category_name.max' => 'Tên danh mục không quá 255 ký tự.',
            'description.required' => 'Mô tả là bắt buộc.',
        ];
    }

}
