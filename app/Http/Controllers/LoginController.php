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
            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email format.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Đăng nhập thành công
            return redirect()->route('index')->with('success', 'Logged in successfully.');
        }

        // Đăng nhập thất bại
        return back()->withErrors(['login' => 'Invalid email or password.'])->withInput();
    }

    // Đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect()->route('index')->with('success', 'Logged out successfully.');
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
            'email.required' => 'Please enter your email.',
            'email.email' => 'The email format is invalid.',
            'old_password.required' => 'Please enter your current password.',
            'old_password.min' => 'The current password must be at least 6 characters.',
            'new_password.required' => 'Please enter a new password.',
            'new_password.min' => 'The new password must be at least 6 characters.',
            'new_password.confirmed' => 'The new password confirmation does not match.',
            'new_password_confirmation.required' => 'Please confirm your new password.',
            'new_password_confirmation.min' => 'The new password confirmation must be at least 6 characters.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Old password is incorrect or user not found.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('login')->with('success', 'Password reset successfully.');
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
            return back()->with('success', 'Reset link sent to your email.');
        } else {
            return back()->withErrors(['email' => 'Email not found or error sending reset link.']);
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
        ], [
            'full_name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'The email format is invalid.',
            'email.unique' => 'This email is already in use.',
            'phone.required' => 'Please enter your phone number.',
            'phone.numeric' => 'Phone number must contain only numbers.',
            'phone.digits_between' => 'Phone number must be between 9 and 15 digits.',
            'phone.unique' => 'This phone number is already in use.',
            'address.required' => 'Please enter your address.',
            'address.string' => 'Address must be a valid string.',
            'dob.required' => 'Please enter your date of birth.',
            'dob.date' => 'Date of birth must be a valid date.',
            'dob.before' => 'Date of birth must be in the past.',
            'password.required' => 'Please enter a password.',
            'password.min' => 'Password must be at least 6 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = new User();
        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->dob = $request->dob;
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect("login")->with('success', 'Registration successful. you can now login.');
    }
}
