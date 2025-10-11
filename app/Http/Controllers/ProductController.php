<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function list()
    {
        $products = Product::with(['category', 'supplier'])->get();
        $suppliers = Supplier::all();
        $categories = Category::all();

        return view('crud-product.list', compact('products', 'suppliers', 'categories'));
    }

    public function index()
    {
        $products = Product::with(['category', 'supplier'])->get();
        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Products retrieved successfully',
        ]);
    }

    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'data' => $product->load(['category', 'supplier']),
            'message' => 'Product details retrieved successfully',
        ]);
    }

    public function store(ProductRequest $request)
    {
        $product = new Product;
        $product->fill($request->all());
        $product->save();
        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product details retrieved successfully',
        ]);
    }

    public function update(ProductRequest $request, string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $data = $request->all();

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');

            $filename = $file->getClientOriginalName();

            $file->move(public_path('uploads'), $filename);

            if ($product->cover_image && file_exists(public_path('uploads/' . $product->cover_image))) {
                unlink(public_path('uploads/' . $product->cover_image));
            }

            $data['cover_image'] = $filename;
        }

        $product->update($data);

        return response()->json([
            'success' => true,
            'data' => $product,
            'message' => 'Product updated successfully',
        ]);
    }

    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found',
                ], 404);
            }
            return redirect()->back()->with('error', 'Product not found');
        }

        $product->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully',
            ]);
        }

        return redirect()->route('products.list')->with('success', 'Product deleted successfully');
    }
}
