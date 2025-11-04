<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Nhóm middleware cho API
        $middleware->group('api', [
            EnsureFrontendRequestsAreStateful::class, // Cho phép frontend gửi cookie / token hợp lệ
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Auth\Middleware\Authenticate::class, // <- Quan trọng để dùng auth:sanctum
        ]);

        // Đặt alias cho middleware tùy chỉnh
        $middleware->alias([
            'checkrole' => \App\Http\Middleware\CheckRole::class,
            'api.token' => \App\Http\Middleware\CheckApiToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
