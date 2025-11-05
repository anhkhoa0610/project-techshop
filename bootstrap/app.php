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

        // Kích hoạt stateful API cho Sanctum (tự động thêm EnsureFrontendRequestsAreStateful vào group 'api')
        $middleware->statefulApi();

        // Group 'api' giờ đã có: EnsureFrontendRequestsAreStateful + SubstituteBindings + ThrottleRequests (mặc định)
        // Không cần thêm thủ công Authenticate, vì auth:sanctum sẽ xử lý
    
        // Đặt alias cho middleware tùy chỉnh (giữ nguyên)
        $middleware->alias([
            'checkrole' => \App\Http\Middleware\CheckRole::class,
            'api.token' => \App\Http\Middleware\CheckApiToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
