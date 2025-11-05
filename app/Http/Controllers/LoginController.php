<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Password;

class LoginController extends Controller
{
    //
    public function showLoginForm()
    {
        return view('login.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        $credentials = [
            'email' => strtolower($request->email),
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            // Đăng nhập thành công
            return redirect()->route('index')->with('success', 'Đăng nhập thành công.');
        }

        // Đăng nhập thất bại
        return back()->withErrors(['login' => 'Sai email hoặc mật khẩu.'])->withInput();
    }

    // Đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect()->route('index')->with('success', 'Đăng xuất thành công.');
    }

    public function showResetForm()
    {
        return view('login.Reset');
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'old_password' => 'required|min:6',
            'new_password' => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required|min:6',
        ], [
            'email.required' => 'Vui lòng nhập email của bạn.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'old_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'old_password.min' => 'Mật khẩu hiện tại phải có ít nhất 6 ký tự.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu mới không khớp.',
            'new_password_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới.',
            'new_password_confirmation.min' => 'Xác nhận mật khẩu mới phải có ít nhất 6 ký tự.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Mật khẩu cũ không chính xác hoặc không tìm thấy người dùng.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công.');
    }

    public function showForgotForm()
    {
        return view('login.ForgotPassword');
    }

    public function forgot(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn.');
        } else {
            return back()->withErrors(['email' => 'Không tìm thấy email hoặc có lỗi khi gửi liên kết đặt lại mật khẩu.']);
        }
    }

    public function showRegisterForm()
    {
        return view('login.create');
    }

    public function postUser(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric|digits_between:9,15|unique:users,phone',
            'address' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ], [
            'full_name.required' => 'Vui lòng nhập họ và tên của bạn.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.numeric' => 'Số điện thoại chỉ được chứa các chữ số.',
            'phone.digits_between' => 'Số điện thoại phải có từ 9 đến 15 chữ số.',
            'phone.unique' => 'Số điện thoại này đã được sử dụng.',
            'address.required' => 'Vui lòng nhập địa chỉ của bạn.',
            'address.string' => 'Địa chỉ phải là một chuỗi hợp lệ.',
            'dob.required' => 'Vui lòng nhập ngày sinh.',
            'dob.date' => 'Ngày sinh phải là một ngày hợp lệ.',
            'dob.before' => 'Ngày sinh phải là ngày trong quá khứ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password_confirmation.required' => 'Vui lòng nhập mật khẩu.',
            'password_confirmation.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        $user = new User();
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->birth = $request->dob;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect("login")->with('success', 'Đăng ký thành công. Bạn có thể đăng nhập ngay bây giờ.');
    }
    public function apiLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !\Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email hoặc mật khẩu không chính xác'], 401);
        }

        // Tạo Sanctum token
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'token' => $token,
            'user' => $user->only(['id', 'full_name', 'email', 'role']),
        ]);
    }

    public function apiLogout(Request $request)
    {
        // Xóa token hiện tại (token đang dùng để gọi API)
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công',
        ]);
    }
}
