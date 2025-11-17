<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
        return [
            'user_id' => 'required|integer|exists:users,user_id',
            'shipping_address' => 'required|string|max:255|min:10',
            'payment_method' => 'required|in:cash,card,transfer,momo,vnpay',
            'voucher_id' => 'nullable|integer|exists:vouchers,voucher_id',
        ];
    }
     public function messages(): array
    {
        return [
            'user_id.required' => 'vui lòng chọn người dùng.',
            'user_id.exists' => 'Người dùng không tồn tại.',
            'shipping_address.required' => 'Vui lòng nhập địa chỉ giao hàng.',
            'shipping_address.max' => 'Địa chỉ giao hàng không được vượt quá 255 ký tự.',
            'shipping_address.min' => 'Địa chỉ giao hàng phải có ít nhất 10 ký tự.',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ.',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán.',
            'voucher_id.exists' => 'Voucher không tồn tại.',

        ];
    }

}
