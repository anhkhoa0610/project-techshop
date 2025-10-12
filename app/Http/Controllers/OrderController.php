<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with(['user', 'voucher'])
            ->paginate(5);
        $users =  User::all();
        $vouchers =  Voucher::all();

        return view('crud-orders.list', compact('orders', 'users', 'vouchers'));
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
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
