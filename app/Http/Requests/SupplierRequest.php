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
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string|max:2000',
        ];
    }

    public function messages()
    {
        return [
            'logo.image' => 'Tệp tải lên phải là hình ảnh.',
            'logo.mimes' => 'Ảnh phải có định dạng: jpeg, png, jpg hoặc svg.',
            'logo.max' => 'Ảnh không được lớn hơn 2MB.',
            'name.required' => 'Tên nhà cung cấp là bắt buộc.',
            'name.max' => 'Tên nhà cung cấp không được vượt quá 255 ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            'phone.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'email.email' => 'Email không hợp lệ.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'description.max' => 'Tiểu sử không được vượt quá 2000 ký tự.',
        ];
    }
}