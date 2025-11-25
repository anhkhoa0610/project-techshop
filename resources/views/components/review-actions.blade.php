<div class="flex justify-center gap-2">
    <a href="{{ route('reviews.edit', $review->review_id)}}" class="edit-btn p-2 rounded text-blue-600" title="Edit">

        <i class="fa-regular fa-pen-to-square"></i>
    </a>

    <form id="delete-form-{{ $review->review_id}}" action="{{ route('reviews.destroy', $review->review_id) }}"
        method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-link p-0 m-0 align-baseline delete" title="Delete" data-toggle="tooltip" 
        onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá này không?')">
            
            <i class="material-icons text-danger">&#xE872;</i>
        </button>
    </form>
    
</div>