<?php

namespace App\Http\Requests;

use Attribute;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route()->product;
        $nameRule = [
            'required',
            'string',
            'max:255',
            'min:5',
            Rule::unique('products', 'product_name')->ignore($id, 'product_id'),
        ];

        return [
            'product_name' => $nameRule,
            'category_id' => 'required|exists:categories,category_id',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'volume_sold' => 'required|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'warranty_period' => 'required|integer|min:0',
            'release_date' => 'required|date|before_or_equal:today',
            'embed_url_review' => [
                'nullable',
                'url',
                'regex:/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/i',
                'max:500',
            ],
        ];
    }

    public function messages()
    {
        return [
            'exists' => ':attribute không tồn tai',
            'required' => ':attribute không được để trống',
            'unique' => ':attribute đã tồn tại',
            'max' => ':attribute không được vượt quá :max ký tự',
            'numeric' => ':attribute phải là số',
            'integer' => ':attribute phải là số nguyên',
            'min' => ':attribute phải lớn hơn hoặc bằng :min',
            'image' => ':attribute phải là định dạng ảnh (jpeg, png, bmp, gif, svg, hoặc webp)',
            'mimes' => ':attribute phải là định dạng: :values',
            'max.file' => ':attribute không được vượt quá :max kilobytes',
            'url' => ':attribute phải là link youtube hợp lệ',
            'regex' => ':attribute phải là link youtube hợp lệ',
            'before_or_equal' => ':attribute không được lớn hơn ngày hiện tại',
            'date' => 'Sai định dạng :attribute',
        ];
    }

    public function attributes()
    {
        return [
            'product_name' => 'Tên',
            'category_id' => 'Danh mục',
            'supplier_id' => 'Nhà cung cấp',
            'price' => 'Giá',
            'stock_quantity' => 'Số lượng trong kho',
            'description' => 'Mô tả',
            'volume_sold' => 'Số lượng đã bán',
            'cover_image' => 'Ảnh bìa',
            'warranty_period' => 'Thời gian bảo hành',
            'release_date' => 'Ngày phát hành',
            'embed_url_review' => 'Link review sản phẩm',
        ];
    }
}
