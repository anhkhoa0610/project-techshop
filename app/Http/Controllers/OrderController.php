<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateOrderRequest;

class OrderController extends Controller
{
    public function list(Request $request)
    {
        session(['orders_list_url' => $request->fullUrl()]);

        $orders = Order::with(['user', 'voucher'])
            ->search($request->search)
            ->dateRange($request->start_date, $request->end_date)
            ->paginate(5);

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
        return response()->json([
            'success' => true,
            'message' => 'Danh sách đơn hàng',
            'data' => $query->get()
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
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Lưu lại trạng thái cũ để so sánh
        $oldStatus = $order->status;
        $newStatus = $request->input('status');

        // Cập nhật các thông tin khác (trừ user_id)
        $order->fill($request->except('user_id'));
        $order->status = $newStatus;
        $order->save();

        if ($oldStatus === 'pending' && $newStatus === 'processing'|| $newStatus === 'completed') {
            $order->decreaseStock();
        }

        if ($oldStatus === 'processing' || $oldStatus === 'completed' && $newStatus === 'cancelled') {
            $order->restoreStock();
        }

        $order->updateTotalPrice();

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Cập nhật đơn hàng thành công, trạng thái và tồn kho đã được xử lý!',
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
