<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,processing,completed,cancelled',
            'shipping_address' => 'sometimes|string|max:255|min:10',
            'payment_method' => 'sometimes|in:cash,card,transfer,momo,vnpay',
            'voucher_id' => 'nullable|integer|exists:vouchers,voucher_id',
            'updated_at' => 'required|date_format:Y-m-d H:i:s', // Version tracking for conflict detection
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Vui lòng chọn trạng thái đơn hàng.',
            'status.in' => 'Trạng thái đơn hàng không hợp lệ.',
            'shipping_address.max' => 'Địa chỉ giao hàng không được vượt quá 255 ký tự.',
            'shipping_address.min' => 'Địa chỉ giao hàng phải có ít nhất 10 ký tự.',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ.',
            'voucher_id.exists' => 'Voucher không tồn tại.',
            'updated_at.required' => 'Phiên bản dữ liệu không xác định.',
            'updated_at.date_format' => 'Định dạng phiên bản không hợp lệ.',
        ];
    }
}