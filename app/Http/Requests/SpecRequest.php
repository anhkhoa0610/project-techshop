<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Đặt là true để cho phép mọi request đi qua.
        // Bạn có thể thêm logic phân quyền (ví dụ: chỉ admin) ở đây nếu cần.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Lấy từ logic "store" của bạn:
            'product_id' => 'required|exists:products', // Đảm bảo product_id tồn tại trong bảng products
            'name'       => 'required|string|max:255',      // Ví dụ: "RAM", "Màn hình"
            'value'      => 'required|string|max:255',      // Ví dụ: "8GB", "OLED 6.5 inch"
        ];
    }

    /**
     * Get the custom error messages for validator.
     *
     * @return array
     */
    public function messages()
    {
        return [
            // Lấy từ logic "store" của bạn:
            'product_id.required' => 'Vui lòng chọn sản phẩm.',
            'product_id.exists'   => 'Sản phẩm được chọn không hợp lệ hoặc không tồn tại.',
            'name.required'       => 'Tên thông số không được để trống.',
            'name.max'            => 'Tên thông số quá dài (tối đa 255 ký tự).',
            'value.required'      => 'Giá trị thông số không được để trống.',
            'value.max'           => 'Giá trị thông số quá dài (tối đa 255 ký tự).',
        ];
    }
}