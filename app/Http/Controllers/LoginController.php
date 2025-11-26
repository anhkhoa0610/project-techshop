<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        if (!Auth::check() && !$request->session()->has('url.intended')) {
            $request->session()->put('url.intended', url()->previous());
        }
        return view('login.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = [
            'email' => strtolower($request->email),
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            return redirect()->intended(route('index'))->with('success', 'Đăng nhập thành công.');
        }

        return back()->withErrors(['login' => 'Sai email hoặc mật khẩu.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        // Xóa session đăng nhập
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->back()->with('success', 'Đăng xuất thành công.');
    }

    public function showResetForm()
    {
        return view('login.Reset');
    }

    public function reset(ChangePasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Mật khẩu cũ không chính xác hoặc không tìm thấy người dùng.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('index')->with('success', 'Đặt lại mật khẩu thành công.');
    }

    public function showForgotForm()
    {
        return view('login.ForgotPassword');
    }

    public function forgot(ForgotPasswordRequest $request)
    {
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

    public function postUser(RegisterRequest $request)
    {
        $user = new User();
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->birth = $request->dob;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect("index")->with('success', 'Đăng ký thành công. Bạn có thể đăng nhập ngay bây giờ.');
    }

    public function showResetPasswordForm($token)
    {
        return view('login.reset-password', ['token' => $token]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('index')->with('success', 'Đặt lại mật khẩu thành công.')
            : back()->withErrors(['email' => [__($status)]]);
    }
    
    public function apiLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

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
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Đăng xuất thành công',
        ]);
    }

}
