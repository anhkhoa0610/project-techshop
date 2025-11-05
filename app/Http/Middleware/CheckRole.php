<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next, $role)
    {
        $user = $request->user(); // Sanctum tự hiểu nếu có Bearer token hoặc session

        if (!$user || $user->role !== $role) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Bạn không có quyền truy cập!'], 403);
            }

            return redirect()->route('index')->with('error', 'Bạn không có quyền truy cập!');
        }

        return $next($request);
    }
}
