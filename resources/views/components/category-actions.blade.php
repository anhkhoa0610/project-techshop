<div class="flex justify-center gap-2">
    <button class="edit-btn p-2 rounded text-blue-600 edit"
        data-category-name = "{{ $category->category_name }}"
        data-category-id="{{ $category->category_id }}"
        data-category-description="{{ $category->description }}"
        >
        <!-- svg edit -->
        <i class="fa-regular fa-pen-to-square"></i>
    </button>

    <button class="delete-btn p-2 rounded text-red-600"
        onclick="confirmDelete({{ $category->category_id }})" title="Delete"  >
        <!-- svg delete -->
        <i class="fa-solid fa-trash"></i>
    </button>
</div>