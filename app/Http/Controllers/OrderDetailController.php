<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderDetailRequest;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Product;


class OrderDetailController extends Controller
{

    public function list($order_id)
    {
        $query = OrderDetail::where('order_id', $order_id)
            ->with('product');
        $products = Product::all();

        // Nếu có tham số tìm kiếm
        if (request()->has('search') && request('search')) {
            $search = request('search');
            $query->whereRelation('product', 'product_name', 'like', '%' . $search . '%');
        }

        $orderDetails = $query->paginate(5);
        return view('crud-orderDetails.list', compact('orderDetails', 'order_id', 'products'));
    }
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
            $query->whereRelation('product', 'product_name', 'like', '%' . $search . '%');
        }

        $orderDetails = $query->paginate(5);
        return response()->json([
            'success' => true,
            'message' => 'Danh sách chi tiết đơn hàng',
            'data' => $orderDetails
        ], 200);
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
    public function store(OrderDetailRequest $request)
    {
        $detail = new OrderDetail();
        $detail->fill($request->all());

        $product = Product::find($request->product_id);
        $detail->unit_price = $product?->price ?? 0;

        $detail->save();

        if ($detail->order) {
            $detail->order->updateTotalPrice();
        }
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
    public function update(OrderDetailRequest $request, string $detail_id)
    {
        $detail = OrderDetail::findOrFail($detail_id);

        $detail->fill($request->all());

        $detail->save();

        if ($detail->order) {
            $detail->order->updateTotalPrice();
        }

        return response()->json([
            'success' => true,
            'data' => $detail,
            'message' => 'Cập nhật chi tiết đơn hàng thành công và đã cập nhật tổng tiền!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $detail_id)
    {
        $order = OrderDetail::findOrFail($detail_id);

        $order->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa chi tiết đơn hàng thành công!'
        ]);
    }


}
