<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\SupplierController;


Route::apiResource('categories', CategoryController::class);
use App\Http\Controllers\ProductController;

Route::apiResource('products', ProductController::class)->only(['show', 'store', 'update', 'index', 'destroy']);

Route::apiResource('orders', OrderController::class);

Route::apiResource('orderDetails', OrderDetailController::class);


// supplier
Route::apiResource('suppliers', SupplierController::class);

// Lấy sản phẩm theo danh mục
Route::get('categories/{categoryId}/products', [IndexController::class, 'getProductsByCategory']);

//product filter cho trang index
Route::get('/index/filter', [ProductController::class, 'filter']);

