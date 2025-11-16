<div class="flex justify-center gap-2">
    <button id="edit-voucher-btn" class="edit-btn p-2 rounded text-blue-600 edit" data-voucher-id="{{ $voucher->voucher_id }}"
        data-code="{{ $voucher->code }}" data-discount_type="{{ $voucher->discount_type }}"
        data-discount_value="{{ $voucher->discount_value }}" data-start_date="{{ $voucher->start_date }}"
        data-end_date="{{ $voucher->end_date }}" data-status="{{ $voucher->status }}"
        data-created_at="{{ $voucher->created_at }}" data-updated_at="{{ $voucher->updated_at }}" title="Edit">
        
        <!-- svg edit -->
        <i class="fa-regular fa-pen-to-square"></i>
    </button>

    <button class="delete-btn p-2 rounded text-red-600" onclick="confirmDelete({{ $voucher->voucher_id }})"
        title="Delete">
        <!-- svg delete -->
        <i class="fa-solid fa-trash"></i>
    </button>
</div>