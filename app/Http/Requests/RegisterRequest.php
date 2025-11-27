<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric|digits_between:9,15|unique:users,phone',
            'address' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'Vui lòng nhập họ và tên của bạn.',
            'full_name.regex' => 'tên chỉ được chứa chữ cái.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.numeric' => 'Số điện thoại chỉ được chứa các chữ số.',
            'phone.digits_between' => 'Số điện thoại phải có từ 9 đến 15 chữ số.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'address.required' => 'Vui lòng nhập địa chỉ của bạn.',
            'address.string' => 'Địa chỉ phải là một chuỗi hợp lệ.',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự.',
            'dob.required' => 'Vui lòng nhập ngày sinh.',
            'dob.date' => 'Ngày sinh phải là một ngày hợp lệ.',
            'dob.before' => 'Ngày sinh phải là ngày trong quá khứ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password_confirmation.required' => 'Vui lòng nhập mật khẩu.',
            'password_confirmation.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ];
    }
}