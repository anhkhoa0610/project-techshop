<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;


Route::get('/', function () {
    return view('layouts.dashboard');
});

Route::prefix('product')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('product.index');
    // Sau này bạn có thể thêm các route khác:
    // Route::get('/create', [ProductController::class, 'create'])->name('product.create');
    // Route::post('/store', [ProductController::class, 'store'])->name('product.store');
});

Route::prefix('category')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('category.index');
   
});