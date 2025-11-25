<div class="flex justify-center gap-2">
    <a href="{{ route('users.edit', $user->user_id)}}" class="edit-btn p-2 rounded text-blue-600" title="Edit">

        <i class="fa-regular fa-pen-to-square text-blue-600"></i>
    </a>

    <form id="delete-form-{{ $user->user_id }}" action="{{ route('users.destroy', $user->user_id) }}"
        method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-link p-0 m-0 align-baseline delete" title="Delete" data-toggle="tooltip"
            onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này không?')">
            <i class="material-icons text-danger">&#xE872;</i>
        </button>
    </form>
</div>