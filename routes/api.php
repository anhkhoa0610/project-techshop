<?php

use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderDetailController;


Route::apiResource('categories', CategoryController::class);
use App\Http\Controllers\ProductController;

Route::apiResource('products', ProductController::class)->only(['show', 'store', 'update', 'index', 'destroy']);

Route::apiResource('orders', OrderController::class);

Route::apiResource('orderDetails', OrderDetailController::class);


