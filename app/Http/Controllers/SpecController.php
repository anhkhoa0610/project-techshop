<?php

namespace App\Http\Controllers;

use App\Models\Spec;
use App\Models\Product;
use App\Http\Requests\SpecRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpecController extends Controller
{
    /**
     * Display a listing of the resource (View cho Web).
     */
    public function list()
    {
        $specs = Spec::with('product')->get();
        return view('crud_spec.list', compact('specs'));
    }

    /**
     * Return JSON for API calls.
     */
    public function index()
    {
        $specs = Spec::with('product')->get();
        return response()->json($specs);
    }

    // **** SỬA LỖI POSTMAN TRẢ VỀ "1" NẰM Ở ĐÂY ****
    /**
     * Trả về danh sách sản phẩm cho dropdown.
     */
    public function getProducts()
    {
        // Phải dùng ->get() để lấy danh sách
        $products = Product::select('product_id', 'product_name')->get();

        // Trả về một mảng JSON
        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SpecRequest $request)
    {
        $validated = $request->validated();
        $spec = Spec::createSpec($validated);

        return response()->json([
            'success' => true,
            'data' => $spec,
            'message' => 'Thêm mới voucher thành công.',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $spec = Spec::with('product')->findOrFail($id);

        if (request()->wantsJson()) {
            return response()->json($spec);
        }
        return view('crud_spec.show', compact('spec'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SpecRequest $request, string $id)
    {
        $validated = $request->validated();
        $spec = Spec::updateSpec($id, $validated);
        return response()->json([
            'success' => true,
            'data' => $spec,
            'message' => 'Cập nhật voucher thành công.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Spec::deleteSpec($id);
            return response()->json([
                'success' => true,
                'message' => 'Xóa spec thành công.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa spec: ' . $e->getMessage(),
            ], 500);
        }
    }
}