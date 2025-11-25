<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateOrderRequest;
use App\Mail\OrderCancelledMail;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function list(Request $request)
    {
        session(['orders_list_url' => $request->fullUrl()]);

        // Sanitize and validate `page` query parameter
        $search = $request->search;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $page = intval($request->query('page', 1));
        $requestedPage = $request->query('page');
        if ($page < 1 || ($requestedPage && intval($requestedPage) != $requestedPage)) {
            // Redirect to a clean URL (preserve filters) when page param is invalid
            return redirect()->route('orders.list', array_filter([
                'search' => $search,
                'start_date' => $startDate,
                'end_date' => $endDate,
            ]));
        }

        $orders = Order::with(['user', 'voucher'])
            ->search($search)
            ->dateRange($startDate, $endDate)
            ->paginate(5, ['*'], 'page', $page);

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

        // Check for version conflict (optimistic locking)
        $requestedUpdatedAt = $request->input('updated_at');
        $currentUpdatedAt = $order->updated_at->format('Y-m-d H:i:s');
        
        if ($requestedUpdatedAt !== $currentUpdatedAt) {
            return response()->json([
                'success' => false,
                'conflict' => true,
                'message' => 'Dữ liệu đã bị thay đổi bởi người khác. Vui lòng tải lại trang và thử lại.',
                'current_updated_at' => $currentUpdatedAt,
            ], 409); // HTTP 409 Conflict
        }

        // Lưu lại trạng thái cũ để so sánh
        $oldStatus = $order->status;
        $newStatus = $request->input('status');

        // Cập nhật các thông tin khác (trừ user_id)
        $order->fill($request->except('user_id', 'updated_at'));
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

    // public function show()
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
    //         ->whereIn('status', ['pending', 'processing','completed','cancelled'])
    //         ->orderBy('order_date', 'desc')
    //         ->get();

    //     // Định dạng từng đơn hàng — giữ cả 'id' và 'order_id' để view không bị lỗi nếu dùng key khác
    //     $formattedOrders = $orders->map(fn($order) => $this->formatOrder($order))->toArray();

    //     // Truyền sang Blade (cách rõ ràng)
    //     return view('ui-cancel-order.cancel', ['formattedOrders' => $formattedOrders]);
    // }




    public function show()
    {
        // Lấy ID user hiện tại
        $userId = auth()->id();

        // Nếu chưa đăng nhập thì redirect (tuỳ app)
        if (!$userId) {
            return redirect()->route('login');
        }

        // 1. Lấy toàn bộ đơn hàng của user kèm chi tiết sản phẩm
        $orders = Order::with('orderDetails.product')
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'processing', 'completed', 'cancelled'])
            ->orderBy('order_date', 'desc')
            ->get();

        // 2. Lọc các đơn hàng không có chi tiết sản phẩm
        // 🟢 SỬA ĐỔI TẠI ĐÂY: Dùng filter để loại bỏ đơn hàng không có OrderDetails
        $validOrders = $orders->filter(function ($order) {
            // Kiểm tra đơn hàng có ít nhất một chi tiết sản phẩm hay không
            return $order->orderDetails->count() > 0;
        });


        // 3. Định dạng từng đơn hàng hợp lệ
        // Định dạng (map) chỉ các đơn hàng đã được lọc ($validOrders)
        $formattedOrders = $validOrders->map(fn($order) => $this->formatOrder($order))->toArray();

        // 4. Truyền sang Blade
        return view('ui-cancel-order.cancel', ['formattedOrders' => $formattedOrders]);
    }


    public function showOrderdetails($id) // 🟢 1. PHẢI NHẬN THAM SỐ ID
    {
        // Lấy ID user hiện tại
        $userId = auth()->id();

        // Nếu chưa đăng nhập thì redirect
        if (!$userId) {
            return redirect()->route('login');
        }

        // 🟢 2. CHỈ LẤY MỘT ĐƠN HÀNG DUY NHẤT VỚI ID ĐƯỢC CHỌN
        $order = Order::with('orderDetails.product')
            ->where('user_id', $userId) // Bảo mật: Đảm bảo đơn hàng thuộc về user hiện tại
            ->where('order_id', $id)    // Lọc chính xác theo ID đơn hàng
            // Bỏ điều kiện status 'processing' nếu bạn muốn xem chi tiết các đơn hàng đã hoàn thành
            //->where('status', 'pendding') 
            ->first(); // Chỉ lấy một kết quả

        // 🟢 3. KIỂM TRA TÌM KIẾM
        if (!$order) {
            // Chuyển hướng hoặc báo lỗi nếu đơn hàng không tồn tại
            return redirect()->route('order.index')->with('error', 'Đơn hàng không tồn tại hoặc không thuộc về bạn.');
        }

        // 🟢 4. ĐỊNH DẠNG ĐƠN HÀNG DUY NHẤT

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

        // Xử lý alias cho từng item (dùng tham chiếu)
        foreach ($orderArray['items'] as &$item) {
            // Alias 'img' từ product (với mặc định nếu thiếu)
            $item['img'] = asset('uploads/' . $item['product']['cover_image'] ?? null);

            // Alias 'title' từ product name
            $item['title'] = $item['product']['product_name'] ?? 'Sản phẩm không xác định';
        }
        // 🟢 GỠ BỎ THAM CHIẾU (Rất quan trọng)
        unset($item);

        // 🟢 5. TRUYỀN SANG BLADE
        // Truyền đơn hàng duy nhất vào trong một MẢNG để khớp với cấu trúc Blade đang sử dụng @foreach
        $formattedOrders = [$orderArray];

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
        if ($order->user->email) {
            Mail::to($order->user->email)->send(new OrderCancelledMail($order));
        }


        // Nếu bạn muốn xóa luôn chi tiết đơn
        $order->orderDetails()->delete();

        // Xóa đơn hàng
        $order->delete();

        return response()->json(['success' => true, 'message' => 'Đơn hàng đã được hủy thành công']);
    }


}