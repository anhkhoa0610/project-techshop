<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UIProductDetailsController;


Route::apiResource('categories', CategoryController::class);
use App\Http\Controllers\ProductController;

Route::apiResource('products', ProductController::class)->only(['show', 'store', 'update', 'index', 'destroy']);

Route::apiResource('orders', OrderController::class);

Route::apiResource('orderDetails', OrderDetailController::class);


// supplier
Route::apiResource('suppliers', SupplierController::class);

Route::apiResource('vouchers', VoucherController::class);

// Lấy sản phẩm theo danh mục
Route::get('categories/{categoryId}/products', [IndexController::class, 'getProductsByCategory']);

//product filter cho trang index
Route::get('/index/filter', [ProductController::class, 'filter']);

// Tìm kiếm sản phẩm qua API
Route::get('/index/search', [IndexController::class, 'searchProductsAPI']);

// DeepSeek Chatbot API route
Route::post('/chat', [\App\Http\Controllers\DeepSeekChatController::class, 'chat']);

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('api.token')->get('/me', [AuthController::class, 'me']);
Route::middleware('api.token')->post('/logout', [AuthController::class, 'logout']);


Route::post('/index/add-to-cart', [IndexController::class, 'addToCart']);

Route::get('/product/{id}/reviews', [UIProductDetailsController::class, 'index']);
Route::post('/product/{id}/reviews', [UIProductDetailsController::class, 'store']);

