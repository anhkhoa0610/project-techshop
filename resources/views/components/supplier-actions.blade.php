<div class="flex justify-center gap-2">
    <button type="button" 
        class="edit-btn p-2 rounded text-blue-600 edit edit-supplier-btn"
        data-supplier-id="{{ $supplier->supplier_id }}" 
        data-name="{{ $supplier->name }}"
        data-description="{{ $supplier->description }}" 
        data-email="{{ $supplier->email }}"
        data-phone="{{ $supplier->phone }}" 
        data-address="{{ $supplier->address }}" 
        data-updated-at="{{ $supplier->updated_at->format('Y-m-d H:i:s') }}"
        data-logo-url="{{ $supplier->logo ? asset('uploads/' . $supplier->logo) : '' }}"
        data-placeholder-url="{{ asset('uploads/place-holder.jpg') }}"
        title="Edit">
        <i class="fa-regular fa-pen-to-square"></i>
    </button>

    <button class="delete-btn p-2 rounded text-red-600" onclick="confirmDelete({{ $supplier->supplier_id }})"
        title="Delete">
        <i class="fa-solid fa-trash"></i>
    </button>
</div>