<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\SupplierRequest;

class SupplierController extends Controller
{
    // Hiển thị danh sách nhà cung cấp
    public function index()
    {
        $suppliers = Supplier::all();
        return response()->json([
            'success' => true,
            'data' => $suppliers,
            'message' => 'Suppliers retrieved successfully',
        ]);
    }

    // Hiển thị form tạo mới nhà cung cấp
    // Hiển thị danh sách nhà cung cấp

    public function list()
    {
        $query = Supplier::query();

        // Nếu có tham số tìm kiếm
        if (request()->has('search') && request('search')) {
            $query = Supplier::searchByName(request('search'));
        }

        $suppliers = $query->paginate(5);

        return view('crud_suppliers.list', compact('suppliers'));
    }

    // Lưu nhà cung cấp mới
    public function store(SupplierRequest $request)
    {
        $validated = $request->validated();
        $supplier = Supplier::createSupplier($validated);

        return response()->json([
            'success' => true,
            'data' => $supplier,
            'message' => 'Supplier created successfully',
        ]);
    }

    public function update(SupplierRequest $request, $id)
    {
        $validated = $request->validated();
        $supplier = Supplier::updateSupplier($id, $validated);

        return response()->json([
            'success' => true,
            'data' => $supplier,
            'message' => 'Supplier updated successfully',
        ]);
    }

    public function destroy(string $id)
    {
        Supplier::deleteSupplier($id);

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully',
        ]);
    }
}