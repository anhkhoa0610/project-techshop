<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Hiển thị danh sách sản phẩm
    public function list()
    {
        $search = request('search');

        $products = Product::with(['category', 'supplier'])
            ->search($search)
            ->paginate(5);

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

        $product->fill($data);
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

            // if ($product->cover_image && file_exists(public_path('uploads/' . $product->cover_image))) {
            //     unlink(public_path('uploads/' . $product->cover_image));
            // }

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
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không tồn tại',
            ], 404);
        }


        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sản phẩm đã xóa thành công!',
        ]);
    }

    public function filter(Request $request)
    {
        $min = $request->input('min_price');
        $max = $request->input('max_price');
        $category = $request->input('category_id', 0);
        $supplier = $request->input('supplier_id', 0);

        $products = Product::filter($min, $max, $category, $supplier)->paginate(8);

        return response()->json([
            'success' => true,
            'data' => $products->items(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'total' => $products->total(),
            'per_page' => $products->perPage(),
        ]);
    }
}
