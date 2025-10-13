<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class OrderDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($order_id)
    {
        $query = OrderDetail::where('order_id', $order_id)
            ->with('product');

        // Nếu có tham số tìm kiếm
        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->whereRelation('product','product_name', 'like', '%' . $search . '%');
        }

        $orderDetails = $query->paginate(5);
        return view('crud-orderDetails.list', compact('orderDetails', 'order_id'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // cập nhật giá trị total_price
    public function updateTotalPrice($order_id)
    {
        $total = OrderDetail::where('order_id', $order_id)
            ->sum(DB::raw('quantity * unit_price'));

        Order::where('order_id', $order_id)
            ->update(['total_price' => $total]);
    }
}
