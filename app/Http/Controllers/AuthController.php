<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class AuthController extends Controller
{
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

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email hoặc mật khẩu không đúng'], 401);
        }

        // Xóa token cũ (1 user 1 token)
        PersonalAccessToken::where('tokenable_id', $user->user_id)
            ->where('tokenable_type', $user::class)
            ->delete();

        // Tạo token mới
        $plainToken = Str::random(40);
        $hashedToken = hash('sha256', $plainToken);

        PersonalAccessToken::create([
            'tokenable_type' => $user::class,
            'tokenable_id' => $user->user_id,
            'name' => 'api-token',
            'token' => $hashedToken,
            'abilities' => ['*'],
            'expires_at' => now()->addDays(7),
        ]);

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'token' => $plainToken,
            'user' => $user->only(['user_id', 'full_name', 'email']),
            'expires_at' => now()->addDays(7)->toISOString(),
        ]);
    }

    public function me(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            $hashed = hash('sha256', $token);
            PersonalAccessToken::where('token', $hashed)->delete();
        }

        return response()->json(['message' => 'Đã đăng xuất']);
    }
}