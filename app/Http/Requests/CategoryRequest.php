<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        $id = $this->route('category') ?? $this->route('id');
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        
        return [
            'category_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[A-Za-zÀ-ỹ0-9\s\-_]+$/u',
                Rule::unique('categories', 'category_name')->ignore($id, 'category_id'),

            ],
            'description' => [
                'required',
                'string',
                'min:5',
                'max:500',
            ],
            'cover_image' => [
                'nullable',       // Cho phép để trống
                'image',          // Phải là file ảnh
                'mimes:jpeg,png,jpg,gif', // Định dạng cho phép
                'max:5120'        // Kích thước tối đa 2MB (2048 KB)
            ],
            'updated_at' => $isUpdate ? 'required|date_format:Y-m-d H:i:s' : '', // Version tracking only for updates
        ];
    }

    public function messages(): array
    {
        return [
            'category_name.required' => 'Tên danh mục là bắt buộc.',
            'category_name.min' => 'Tên danh mục phải nhiều hơn 2 ký tự.',
            'category_name.max' => 'Tên danh mục không quá 255 ký tự.',
            'category_name.regex' => 'Tên danh mục chỉ cho phép chữ, số, khoảng trắng, gạch ngang, gạch dưới',
            'category_name.unique' => 'Tên danh mục đã tồn tại.',
            'description.required' => 'Mô tả là bắt buộc.',
            'description.min' => 'Mô tả phải có ít nhất 5 ký tự.',
            'description.max' => 'Mô tả không quá 500 ký tự.',
            'cover_image.image' => 'File tải lên phải là hình ảnh.',
            'cover_image.max' => 'Hình ảnh không được vượt quá 5MB.',
            'updated_at.required' => 'Phiên bản dữ liệu không xác định.',
            'updated_at.date_format' => 'Định dạng phiên bản không hợp lệ.',
        ];
    }

}
