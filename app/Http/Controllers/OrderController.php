<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Str;

class OrderController extends Controller
{
    public function list(Request $request)
    {
        session(['orders_list_url' => $request->fullUrl()]);

        $query = Order::with(['user', 'voucher']);

        //  Nếu có tham số tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('order_id', 'like', "%{$search}%")
                ->orWhere('status', 'like', "%{$search}%")
                ->orWhereRelation('user', 'full_name', 'like', "%{$search}%");
        }

        // Nếu có lọc theo khoảng ngày tháng
        if ($request->filled(['start_date', 'end_date'])) {
            $query->whereBetween('order_date', [$request->start_date, $request->end_date]);
        }

        $orders = $query->paginate(5);
        $users = User::all();
        $vouchers = Voucher::all();

        return view('crud-orders.list', compact('orders', 'users', 'vouchers'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Order::with(['user', 'voucher']);

        // Nếu có tham số tìm kiếm
        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->where('order_id', 'like', '%' . $search . '%')
                ->orWhereRelation('user', 'full_name', 'like', '%' . $search . '%');
        }
        // nếu có lọc theo khoảng ngày tháng

        if (request()->filled(['start_date', 'end_date'])) {
            $query->whereBetween('order_date', [request('start_date'), request('end_date')]);
        }

        $orders = $query->paginate(5);
        $users = User::all();
        $vouchers = Voucher::all();

        return response()->json([
            'success' => true,
            'message' => 'Danh sách đơn hàng',
            'data' => [
                'orders' => $orders,
                'users' => $users,
                'vouchers' => $vouchers
            ]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function store(OrderRequest $request)
    {
        $order = new Order();
        $order->fill($request->all());
        $order->save();
        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Thành công!'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, $order_id)
    {
        $order = Order::findOrFail($order_id);
        $order->fill($request->all());
        $order->save();
        $order->updateTotalPrice();
        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Cập nhật đơn hàng thành công và đã tính lại tổng tiền!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $order_id)
    {
        $order = Order::findOrFail($order_id);

        $order->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa đơn hàng thành công!'
        ]);
    }
}
