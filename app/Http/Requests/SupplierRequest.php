<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SupplierRequest extends FormRequest
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
        $id = $this->route()->supplier;
        $nameRule = [
            'required',
            'string',
            'max:255',
            Rule::unique('suppliers', 'name')->ignore($id, 'supplier_id'),
        ];

        return [
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'name' => $nameRule,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20|regex:/^[\d\s\+\-]+$/',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('suppliers', 'email')->ignore($id, 'supplier_id'),
            ],
            'description' => 'nullable|string|max:2000',
        ];
    }

    public function messages()
    {
        return [
            // Logo
            'logo.image' => 'Tệp tải lên phải là hình ảnh.',
            'logo.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg hoặc svg.',
            'logo.max' => 'Ảnh không được lớn hơn 2MB.',

            // Name
            'name.required' => 'Tên nhà cung cấp là bắt buộc.',
            'name.string' => 'Tên nhà cung cấp không hợp lệ.',
            'name.max' => 'Tên nhà cung cấp không được vượt quá 255 ký tự.',
            'name.unique' => 'Tên nhà cung cấp này đã tồn tại.',

            // Address
            'address.string' => 'Địa chỉ không hợp lệ.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',

            // Phone
            'phone.string' => 'Số điện thoại không hợp lệ.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'phone.regex' => 'Số điện thoại chỉ được chứa số, dấu (+), (-) và khoảng trắng.', // Thêm cho quy tắc regex (nếu bạn dùng)

            // Email
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.unique' => 'Email này đã được sử dụng.', // Thêm cho quy tắc unique (nếu bạn dùng)

            // Description
            'description.string' => 'Mô tả không hợp lệ.',
            'description.max' => 'Mô tả không được vượt quá 2000 ký tự.',
        ];
    }
}