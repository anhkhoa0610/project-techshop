<div class="flex justify-center gap-2">
    <button id="edit-spec-btn" class="edit-btn p-2 rounded text-blue-600 edit"
        data-spec-id="{{ $spec->spec_id }}" data-product-id="{{ $spec->product_id }}"
        data-name="{{ $spec->name }}" data-value="{{ $spec->value }}"
        title="Edit">

        <!-- svg edit -->
        <i class="fa-regular fa-pen-to-square"></i>
    </button>

    <button class="delete-btn p-2 rounded text-red-600" onclick="confirmDelete({{ $spec->spec_id }})"
        title="Delete">
        <!-- svg delete -->
        <i class="fa-solid fa-trash"></i>
    </button>
</div>