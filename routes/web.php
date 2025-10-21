<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HoaDonController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\IndexController;

use App\Http\Controllers\UserController;


Route::get('/', function () {
    return view('layouts.dashboard');
});

Route::get('/index', [IndexController::class, 'index'])->name('index');

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'list'])->name('products.list');
    // Sau này bạn có thể thêm các route khác:
    // Route::get('/create', [ProductController::class, 'create'])->name('product.create');
    // Route::post('/store', [ProductController::class, 'store'])->name('product.store');
});
Route::prefix('supplier')->group(function () {
    Route::get('/', [SupplierController::class, 'list'])->name('supplier.list');

});
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'list'])->name('categories.list');

});

Route::prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'list'])->name('orders.list');

});

Route::prefix('orderDetails')->group(function () {
    Route::get('/{order_id}', [OrderDetailController::class, 'list'])->name('orderDetails.list');


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/pay', [PayController::class, 'index'])->name('pay.index');
Route::get('/hoadon', [HoaDonController::class, 'index'])->name('hoadon.index');


Route::get('/{order_id}', [OrderDetailController::class, 'index'])->name('orderDetail.index');


});







Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/search/autocomplete', [UserController::class, 'search'])->name('users.search');
});

