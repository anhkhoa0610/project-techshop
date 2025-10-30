<?php
namespace App\Http\Middleware;

use Closure;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;

class CheckApiToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Token không tồn tại'], 401);
        }

        $hashed = hash('sha256', $token);

        $accessToken = PersonalAccessToken::with('tokenable')
            ->where('token', $hashed)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            })
            ->first();

        if (!$accessToken || !$accessToken->tokenable) {
            return response()->json(['error' => 'Token không hợp lệ hoặc hết hạn'], 401);
        }

        // Cập nhật last_used_at
        $accessToken->forceFill(['last_used_at' => now()])->save();

        // Gắn user vào request
        $request->setUserResolver(fn() => $accessToken->tokenable);

        return $next($request);
    }
}