<?php

namespace App\Http\Requests;

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

        // Rule cho số nguyên ASCII
        $asciiInteger = [
            'required',
            'regex:/^[0-9]+$/',     // chỉ nhận 0–9 ASCII
            'gte:0',                // >= 0
        ];

        // Rule cho số thập phân ASCII (price)
        $asciiDecimal = [
            'required',
            'regex:/^[0-9]+(\.[0-9]+)?$/',
            'gte:0',                // >= 0
        ];

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

            // Giá (decimal ASCII)
            'price' => $asciiDecimal,

            // Số lượng tồn kho
            'stock_quantity' => $asciiInteger,

            'description' => 'nullable|string|max:1000',

            // Volume sold
            'volume_sold' => $asciiInteger,

            // Image
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',

            // Warranty
            'warranty_period' => $asciiInteger,

            // Ngày - validate strict để từ chối ngày không hợp lệ như 30/2
            'release_date' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:today',
                function ($attribute, $value, $fail) {
                    // Kiểm tra ngày có hợp lệ không (tránh 30/2, 31/4, etc.)
                    $date = \DateTime::createFromFormat('Y-m-d', $value);
                    if (!$date || $date->format('Y-m-d') !== $value) {
                        $fail('Ngày phát hành không hợp lệ');
                    }
                },
            ],

            // YouTube URL
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
            'min' => ':attribute phải lớn hơn hoặc bằng :min',
            'image' => ':attribute phải là định dạng ảnh (jpeg, png, bmp, gif, svg, hoặc webp)',
            'mimes' => ':attribute phải là định dạng: :values',
            'max.file' => ':attribute không được vượt quá :max kilobytes',
            'url' => ':attribute phải là link youtube hợp lệ',
            'before_or_equal' => ':attribute không được lớn hơn ngày hiện tại',
            'date' => 'Sai định dạng :attribute',

            // Messages cho số nguyên ASCII (từ chối full-width)
            'stock_quantity.regex' => ':attribute chỉ chấp nhận số thông thường (0-9), không chấp nhận số full-width',
            'volume_sold.regex' => ':attribute chỉ chấp nhận số thông thường (0-9), không chấp nhận số full-width',
            'warranty_period.regex' => ':attribute chỉ chấp nhận số thông thường (0-9), không chấp nhận số full-width',

            // Message cho số thập phân ASCII (từ chối full-width)
            'price.regex' => ':attribute chỉ chấp nhận số thông thường (0-9), không chấp nhận số full-width',

            // Message cho YouTube URL
            'embed_url_review.regex' => ':attribute phải là link youtube hợp lệ',
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
