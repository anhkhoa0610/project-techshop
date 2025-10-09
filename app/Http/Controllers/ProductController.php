<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function index()
    {
        $products = Product::with(['category', 'supplier'])->get();

        return view('crud-product.list', compact('products'));
    }

    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'data' => $product->load(['category', 'supplier']),
            'message' => 'Product details retrieved successfully',
        ]);
    }

    public function store(ProductRequest $request) {
        $product = new Product;
        $product->fill($request->all());
        $product->save();
        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product details retrieved successfully',
        ]);
    }
}
