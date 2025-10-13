<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;


Route::apiResource('categories', CategoryController::class);
use App\Http\Controllers\ProductController;

Route::apiResource('products', ProductController::class)->only(['show', 'store', 'update', 'index', 'destroy']);
// supplier
Route::apiResource('suppliers', SupplierController::class)->only(['update', 'destroy']);
