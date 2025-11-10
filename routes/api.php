<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UIProductDetailsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PromotionController;

Route::post('/login', [LoginController::class, 'apiLogin']);
Route::middleware('auth:sanctum')->post('/logout', [LoginController::class, 'apiLogout']);
Route::middleware(['auth:sanctum', 'checkrole:Admin'])->group(function () {
    Route::apiResource('categories', CategoryController::class);

    Route::apiResource('products', ProductController::class)->only(['show', 'store', 'update', 'index', 'destroy']);

    Route::apiResource('orders', OrderController::class);

    Route::apiResource('orderDetails', OrderDetailController::class);

    Route::apiResource('suppliers', SupplierController::class);

    Route::apiResource('vouchers', VoucherController::class);
});

Route::get('categories/{categoryId}/products', [IndexController::class, 'getProductsByCategory']);

//product filter cho trang index
Route::get('/index/filter', [IndexController::class, 'filter']);

// Tìm kiếm sản phẩm qua API
Route::get('/index/search', [IndexController::class, 'searchProductsAPI']);

// Chatbot API route
Route::post('/chat', [\App\Http\Controllers\ChatbotController::class, 'chat']);

Route::post('/index/add-to-cart', [IndexController::class, 'addToCart']);

Route::get('/product/{id}/reviews', [UIProductDetailsController::class, 'index']);
Route::post('/product/{id}/reviews', [UIProductDetailsController::class, 'storeReview']);


Route::get('/promotions', [PromotionController::class, 'apiIndex']);

Route::post('/voucher/check', [App\Http\Controllers\VoucherController::class, 'checkVoucher']);

Route::get('/posts', [PostController::class,'loadPostsApi']);
Route::get('/vouchers', [VoucherController::class, 'vouchers']);
Route::get('/product-details/filter', [UIProductDetailsController::class, 'filterProducts']);
Route::post('/product-details/cart/add', [UIProductDetailsController::class, 'addToCart']);
