<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Requests\VoucherRequest;
use Exception;

class VoucherController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function list(Request $request)
    {
        $page = $request->query('page', 1);
        if (!ctype_digit((string) $page) || $page < 1) {
            return redirect()->route('voucher.list');
        }
        //
        $query = Voucher::query();

        // Tìm kiếm
        if (request()->filled('search')) {
            $query = Voucher::search(request('search'));
        }

        // Lọc theo status
        if (request()->filled('status_filter')) {
            $query->where('status', request('status_filter'));
        }

        // Lọc theo discount type
        if (request()->filled('discount_type_filter')) {
            $query->where('discount_type', request('discount_type_filter'));
        }

        // Lọc theo Start Date
        if (request()->filled('start_date_filter')) {
            $query->whereDate('start_date', '>=', request('start_date_filter'));
        }

        // Lọc theo End Date
        if (request()->filled('end_date_filter')) {
            $query->whereDate('end_date', '<=', request('end_date_filter'));
        }

        // Lấy danh sách giá trị duy nhất để hiển thị
        $allStatus = Voucher::select('status')->distinct()->pluck('status');
        $allDiscountType = Voucher::select('discount_type')->distinct()->pluck('discount_type');

        $vouchers = $query->paginate(5);

        return view('crud_voucher.list', compact('vouchers', 'allStatus', 'allDiscountType'));
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
            'message' => 'Lấy danh sách voucher thành công.',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VoucherRequest $request)
    {
        $validated = $request->validated();
        $voucher = Voucher::createVoucher($validated);

        return response()->json([
            'success' => true,
            'data' => $voucher,
            'message' => 'Thêm mới voucher thành công.',
        ], 201);
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
            'message' => 'Cập nhật voucher thành công.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        try {
            Voucher::deleteVoucher($id);
            return response()->json([
                'success' => true,
                'message' => 'Xóa voucher thành công.',
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa voucher: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Kiểm tra mã voucher
    public function checkVoucher(Request $request)
    {
        $code = $request->input('voucher');
        $voucher = Voucher::where('code', $code)->first();
        if ($voucher && $voucher->status === 'active') {
            if ($code == $voucher->code) {
                if ($voucher->discount_type == 'percent') {
                    $discount = $voucher->discount_value; // Lấy giá trị phần trăm giảm giá
                } elseif ($voucher->discount_type == 'amount') {
                    $discount = $voucher->discount_value; // Lấy giá trị số tiền giảm giá
                } else {
                    $discount = 0; // Trường hợp không xác định loại giảm giá
                }
            }
            return response()->json([
                'valid' => true,
                'voucher_id' => $voucher->voucher_id,
                'discount_type' => $voucher->discount_type,
                'discount_value' => $discount,
                'message' => 'Voucher hợp lệ!'
            ]);
        } else {
            return response()->json([
                'valid' => false,
                'message' => 'Voucher không hợp lệ hoặc đã hết hạn.'
            ]);
        }
    }

    public function vouchers(Request $request)
    {
        $perPage = $request->input('per_page', 3);

        // Phân trang dữ liệu
        $vouchers = Voucher::getListActive($perPage);

        return response()->json([
            'status' => 'success',
            'current_page' => $vouchers->currentPage(),
            'last_page' => $vouchers->lastPage(),
            'next_page_url' => $vouchers->nextPageUrl(),
            'prev_page_url' => $vouchers->previousPageUrl(),
            'data' => $vouchers->items(),
        ]);
    }
}
