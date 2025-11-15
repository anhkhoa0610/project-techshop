<div class="flex justify-center gap-2">
    <button class="edit-btn p-2 rounded text-blue-600 edit"
        data-status="{{ $order->status_value }}"
        data-shipping-address="{{ $order->shipping_address }}"
        data-payment-method="{{ $order->payment_method }}"
        data-voucher-id="{{ $order->voucher_id }}"
        data-order-id="{{ $order->order_id }}"
        >
        <!-- svg edit -->
        <i class="fa-regular fa-pen-to-square"></i>
    </button>

    <button class="delete-btn p-2 rounded text-red-600"
        onclick="confirmDelete({{ $order->order_id }})" title="Delete"  >
        <!-- svg delete -->
        <i class="fa-solid fa-trash"></i>
    </button>
</div>