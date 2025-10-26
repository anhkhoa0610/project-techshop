<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Requests\VoucherRequest;

class VoucherController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function list()
    {
        //
        $query = Voucher::query();

        // Nếu có tham số tìm kiếm
        if (request()->has('search') && request('search')) {
            $query = Voucher::search(request('search'));
        }

        $vouchers = $query->paginate(5);

        return view('crud_voucher.list', compact('vouchers'));
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $vouchers = Voucher::all();
        return response()->json([
            'success' => true,
            'data' => $vouchers,
            'message' => 'Suppliers retrieved successfully',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VoucherRequest $request)
    {
        //
        $validated = $request->validated();
        $voucher = Voucher::createVoucher($validated);

        return response()->json([
            'success' => true,
            'data' => $voucher,
            'message' => 'Supplier created successfully',
        ],201);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(VoucherRequest $request, $id)
    {
        //
        $validated = $request->validated();
        $voucher = Voucher::updateVoucher($id, $validated);
        return response()->json([
            'success' => true,
            'data' => $voucher,
            'message' => 'Voucher updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        //
    }
}
