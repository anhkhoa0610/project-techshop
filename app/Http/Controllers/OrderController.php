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

        if ($oldStatus === 'pending' && $newStatus === 'processing' || $newStatus === 'completed') {
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

    public function show()
    {
        // Lấy ID user hiện tại
        $userId = auth()->id();

        // Nếu chưa đăng nhập thì redirect (tuỳ app)
        if (!$userId) {
            return redirect()->route('login');
        }

        // Lấy toàn bộ đơn hàng của user này kèm chi tiết sản phẩm
        $orders = Order::with('orderDetails.product')
            ->where('user_id', $userId)
            ->where('status', 'processing')
            ->get();

        // Định dạng từng đơn hàng — giữ cả 'id' và 'order_id' để view không bị lỗi nếu dùng key khác
        $formattedOrders = $orders->map(fn($order) => $this->formatOrder($order))->toArray();

        // Truyền sang Blade (cách rõ ràng)
        return view('ui-cancel-order.cancel', ['formattedOrders' => $formattedOrders]);
    }
    //  public function showOrderdetails()
    // {
    //     // Lấy ID user hiện tại
    //     $userId = auth()->id();

    //     // Nếu chưa đăng nhập thì redirect (tuỳ app)
    //     if (!$userId) {
    //         return redirect()->route('login');
    //     }

    //     // Lấy toàn bộ đơn hàng của user này kèm chi tiết sản phẩm
    //     $orders = Order::with('orderDetails.product')
    //         ->where('user_id', $userId)
    //         ->where('status', 'processing')
    //         ->get();

    //     // Định dạng từng đơn hàng — giữ cả 'id' và 'order_id' để view không bị lỗi nếu dùng key khác
    //     $formattedOrders = $orders->map(fn($order) => $this->formatOrder($order))->toArray();

    //     // Truyền sang Blade (cách rõ ràng)
    //     return view('ui-order-details.order-details', ['formattedOrders' => $formattedOrders]);
    // }

    /**
     * Hàm định dạng order theo cấu trúc bảng thực tế
     */
 public function showOrderdetails()
{
    // Lấy ID user hiện tại
    $userId = auth()->id();

    // Nếu chưa đăng nhập thì redirect (tuỳ app)
    if (!$userId) {
        return redirect()->route('login');
    }

    // Lấy toàn bộ đơn hàng của user này kèm chi tiết sản phẩm
    $orders = Order::with('orderDetails.product')
        ->where('user_id', $userId)
        ->where('status', 'processing')
        ->get();

    // Định dạng từng đơn hàng — tích hợp logic format vào đây
    $formattedOrders = $orders->map(function ($order) {
        // Chuyển object thành mảng
        $orderArray = $order->toArray();
        
        // Thêm key 'id' alias cho 'order_id'
        $orderArray['id'] = $orderArray['order_id'];
        
        // Thêm key 'total' alias cho 'total_price'
        $orderArray['total'] = $orderArray['total_price'];
        
        // Thêm key 'items' alias cho 'order_details'
        $orderArray['items'] = $orderArray['order_details'];
        
        // Đảm bảo 'created_at' có và format
        if (!isset($orderArray['created_at'])) {
            $orderArray['created_at'] = now()->format('d/m/Y H:i');
        } else {
            $orderArray['created_at'] = \Carbon\Carbon::parse($orderArray['created_at'])->format('d/m/Y H:i');
        }
        
        // Tính tổng số lượng
        $totalQuantity = $order->orderDetails->sum('quantity');
        $orderArray['total_quantity'] = $totalQuantity;
        
        // Xử lý alias cho từng item (để view dùng $item['img'], $item['title'] trực tiếp)
        foreach ($orderArray['items'] as &$item) {
            // Alias 'img' từ product (với mặc định nếu thiếu)
            $item['img'] = asset('uploads/' . $item['product']['cover_image'] ?? null); // Thay bằng đường dẫn thực
            
            // Alias 'title' từ product name
            $item['title'] = $item['product']['name'] ?? 'Sản phẩm không xác định';
            
            // 'unit_price' và 'quantity' đã có từ order_details, giữ nguyên
        }
        
        return $orderArray;
    })->toArray();

    // Truyền sang Blade
    return view('ui-order-details.order-details', ['formattedOrders' => $formattedOrders]);
}


    private function formatOrder($order)
    {
        return [
            'id' => $order->order_id,            // giữ key 'id' (cũ)
            'order_id' => $order->order_id,      // thêm key 'order_id' để view dùng được
            'user_id' => $order->user_id ?? auth()->id(),
            'date' => optional($order->order_date)?->format('d/m/Y') ?? '',
            'status' => $order->status,
            'total' => $order->total_price ?? $order->total,
            'shipping_address' => $order->shipping_address ?? '',
            'payment_method' => $order->payment_method ?? '',
            'quantity' => $order->orderDetails->sum('quantity'),
            'items' => $order->orderDetails->map(function ($detail) {
                return [
                    'order_detail_id' => $detail->order_detail_id ?? $detail->id,
                    'product_id' => $detail->product_id,
                    'title' => $detail->product->product_name ?? 'Sản phẩm không tìm thấy',
                    'img' => asset('uploads/' . $detail->product->cover_image) ?? 'https://via.placeholder.com/200',
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->unit_price,
                ];
            })->toArray(),
        ];
    }

    public function deleteOrder($id)
    {
        $userId = auth()->id();

        // Lấy đơn hàng cần xóa
        $order = Order::where('order_id', $id)
            ->where('user_id', $userId)
            ->first();

        // Nếu không tìm thấy đơn
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy đơn hàng'], 404);
        }

        // Nếu bạn muốn xóa luôn chi tiết đơn
        $order->orderDetails()->delete();

        // Xóa đơn hàng
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Đơn hàng đã được hủy thành công']);
    }


}