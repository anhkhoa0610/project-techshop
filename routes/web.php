<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


Route::get('/', function () {
    return view('layouts.dashboard');
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'list'])->name('products.list');
    // Sau này bạn có thể thêm các route khác:
    // Route::get('/create', [ProductController::class, 'create'])->name('product.create');
    // Route::post('/store', [ProductController::class, 'store'])->name('product.store');
});