<div class="flex justify-center gap-2">
    <button id="edit-product-btn" class="edit-btn p-2 rounded text-blue-600"
        data-product-id="{{ $product->product_id }}" data-product-name="{{ $product->product_name }}"
        data-description="{{ $product->description }}" data-stock-quantity="{{ $product->stock_quantity }}"
        data-price="{{ $product->price }}" data-cover-image="{{ $product->cover_image_filename }}"
        data-volume-sold="{{ $product->volume_sold }}" data-category-id="{{ $product->category_id }}"
        data-supplier-id="{{ $product->supplier_id }}" data-warranty-period="{{ $product->warranty_period }}"
        data-release-date="{{ $product->release_date }}" data-embed-url-review="{{ $product->embed_url_review }}"
        data-updated-at="{{ $product->updated_at }}" title="Edit">
        <!-- svg edit -->
        <i class="fa-regular fa-pen-to-square"></i>
    </button>

    <button id="delete-product-btn" class="delete-btn p-2 rounded text-red-600"
        onclick="confirmDelete({{ $product->product_id }})" title="Delete">
        <!-- svg delete -->
        <i class="fa-solid fa-trash"></i>
    </button>
</div>