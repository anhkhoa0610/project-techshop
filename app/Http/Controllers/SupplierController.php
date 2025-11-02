<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Requests\SupplierRequest;

class SupplierController extends Controller
{

    // Hiển thị danh sách nhà cung cấp

    public function list()
    {
        $query = Supplier::query();

        // Nếu có tham số tìm kiếm
        if (request()->has('search') && request('search')) {
            $query = Supplier::searchByName(request('search'));
        }

        // Lọc theo địa chỉ nếu có
        if (request()->has('address_filter') && request('address_filter')) {
            $query->where('address', request('address_filter'));
        }

        // Lấy danh sách địa chỉ duy nhất để hiển thị trong dropdown
        $allAddresses = Supplier::select('address')
            ->whereNotNull('address')
            ->distinct()
            ->pluck('address');

        $suppliers = $query->paginate(5);

        return view('crud_suppliers.list', compact('suppliers', 'allAddresses'));
    }

    public function index()
    {
        $suppliers = Supplier::all();
        return response()->json([
            'success' => true,
            'data' => $suppliers,
            'message' => 'Suppliers retrieved successfully',
        ]);
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

    public function destroy($id)
    {
        try {
            Supplier::deleteSupplier($id);
            return response()->json([
                'success' => true,
                'message' => 'Supplier deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting supplier: ' . $e->getMessage(),
            ], 500);
        }
    }
}