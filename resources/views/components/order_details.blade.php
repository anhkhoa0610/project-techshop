@php
    // PowerGrid tự động truyền $id (primary key của row)
    $order = \App\Models\Order::with('orderDetails')->find($row->order_id);
    $orderDetails = $order ? $order->orderDetails : collect([]);
@endphp

<div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm text-gray-800 text-left">
    <h3 class="text-xl font-bold mb-4">Order Details - Order #{{ $id }}</h3>

    @if($orderDetails->count() > 0)
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-6 p-4 bg-gray-50 rounded-lg">
                @foreach($orderDetails as $detail)

                    <div class="space-y-3">
                        <div class="flex items-start">
                            <span class="font-semibold text-gray-700 w-2/5 shrink-0">Product:</span>
                            <span class="text-gray-900 flex-grow">{{ $detail->product->product_name }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="font-semibold text-gray-700 w-2/5 shrink-0">Quantity:</span>
                            <span class="text-gray-900 flex-grow">{{ $detail->quantity }}</span>
                        </div>
                        <div class="flex items-start">
                            <span class="font-semibold text-gray-700 w-2/5 shrink-0">Price:</span>
                            <span class="text-gray-900 flex-grow">{{ number_format($detail->unit_price) }} VNĐ</span>
                        </div>
                        <div class="flex items-start">
                            <span class="font-semibold text-gray-700 w-2/5 shrink-0">Total:</span>
                            <span class="text-gray-900 flex-grow">
                                {{ number_format($detail->quantity * $detail->unit_price) }} VNĐ
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6 pt-4 border-t">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold">Grand Total:</span>
                <span class="text-xl font-bold text-green-600">
                    {{ number_format($orderDetails->sum(fn($d) => $d->quantity * $d->unit_price)) }} VNĐ
                </span>
            </div>
        </div>
    @else
        <p class="text-gray-500 italic">No order details found.</p>
    @endif
</div>