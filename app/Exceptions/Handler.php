<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\StaleModelException; // ← Thêm cái này cho Optimistic Locking
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->view('errors.404', [
                'message' => 'Không tìm thấy người dùng này! Có thể đã bị xóa hoặc ID sai rồi đó bro.'
            ], 404);
        }

        // Bắt ID kiểu abc, 99999999999999999999
        if ($exception instanceof \Illuminate\Database\QueryException && str_contains($exception->getMessage(), 'Data too long')) {
            return response()->view('errors.404', [
                'message' => 'ID không hợp lệ! Đừng nghịch ngợm nha.'
            ], 404);
        }

        return parent::render($request, $exception);
    }
}