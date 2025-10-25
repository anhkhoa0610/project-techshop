<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VoucherRequest extends FormRequest
{
    /**
     * Xác định quyền thực hiện request (nếu cần).
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
            'code' => [
                'required',
                'string',
                'max:20',
                // duy nhất trong bảng vouchers, nhưng bỏ qua id hiện tại khi update
                Rule::unique('vouchers', 'code')->ignore($this->voucher)
            ],
            'discount_type' => 'required|in:percent,amount',
            'discount_value' => 'required|numeric|min:0.01',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Thông báo lỗi tùy chỉnh.
     */
    public function messages(): array
    {
        return [
            // code
            'code.required' => 'Mã voucher không được để trống',
            'code.max' => 'Số ký tự cho phép dưới 20 ký tự',
            'code.unique' => 'Mã voucher đã tồn tại',

            // discount_type
            'discount_type.required' => 'Loại giảm giá không được để trống',
            'discount_type.in' => 'Loại giảm giá chỉ được chọn percent hoặc amount',

            // discount_value
            'discount_value.required' => 'Giá trị giảm giá không được để trống',
            'discount_value.numeric' => 'Lượng giảm giá phải là số',
            'discount_value.min' => 'Giá trị giảm giá phải lớn hơn 0',

            // start_date
            'start_date.required' => 'Ngày bắt đầu không được để trống',
            'start_date.before_or_equal' => 'Ngày áp dụng không lớn hơn ngày hết hạn',

            // end_date
            'end_date.required' => 'Ngày hết hạn không được để trống',
            'end_date.after_or_equal' => 'Ngày hết hạn không bé hơn ngày bắt đầu',

            // status
            'status.required' => 'Trạng thái không được để trống',
            'status.in' => 'Trạng thái chỉ được chọn active hoặc inactive',
        ];
    }
}
