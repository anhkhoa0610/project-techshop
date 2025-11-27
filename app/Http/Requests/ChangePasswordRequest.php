<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Vui lòng nhập email của bạn.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'old_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'old_password.min' => 'Mật khẩu hiện tại phải có ít nhất 6 ký tự.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
            'new_password_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới.',
            'new_password_confirmation.min' => 'Xác nhận mật khẩu mới phải có ít nhất 6 ký tự.',
        ];
    }
}