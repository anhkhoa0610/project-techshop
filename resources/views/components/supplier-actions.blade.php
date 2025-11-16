<div class="flex justify-center gap-2">
    <button id="edit-supplier-btn" class="edit-btn p-2 rounded text-blue-600 edit"
        data-supplier-id="{{ $supplier->supplier_id }}" data-name="{{ $supplier->name }}"
        data-description="{{ $supplier->description }}" data-email="{{ $supplier->email }}"
        data-phone="{{ $supplier->phone }}" data-address="{{ $supplier->address }}" data-logo="{{ $supplier->logo }}"
        title="Edit" data-logo-url="{{ $supplier->logo ? 'uploads/' . $supplier->logo : '' }}"
        data-placeholder-url="{{ 'uploads/place-holder.jpg' }}">

        <!-- svg edit -->
        <i class="fa-regular fa-pen-to-square"></i>
    </button>

    <button class="delete-btn p-2 rounded text-red-600" onclick="confirmDelete({{ $supplier->supplier_id }})"
        title="Delete">
        <!-- svg delete -->
        <i class="fa-solid fa-trash"></i>
    </button>
</div>