@extends('layouts.dashboard')

@section('content')
    <!-- Flash Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @livewire('user-table')
        </div>
    </main>

    <!-- Modal Xóa (giữ nguyên, nhưng thêm JS bên dưới) -->
    <div id="deleteUserModal" class="modal fade" tabindex="-1" role="dialog">
        <!-- ... giữ nguyên code modal ... -->
    </div>
@endsection

@section('scripts')
    <script>
        // Handle modal show: Lưu URL delete vào button confirm
        $('#deleteUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Button delete ở table
            var deleteUrl = button.closest('form').attr('action'); // Lấy action của form
            var entityName = button.data('name') || 'người dùng này'; // Nếu có data-name ở button
            var modal = $(this);
            modal.find('#modalEntityName').text(entityName);
            modal.find('#confirmDeleteBtn').data('url', deleteUrl); // Lưu URL vào button confirm
        });

        // Handle confirm delete: Submit normal (không AJAX để dùng redirect flash)
        $('#confirmDeleteBtn').on('click', function () {
            var deleteUrl = $(this).data('url');
            if (deleteUrl) {
                // Tạo form tạm để submit DELETE (vì modal không có form)
                var form = $('<form>', {
                    'action': deleteUrl,
                    'method': 'POST'
                });
                form.append($('<input>', { 'name': '_token', 'value': '{{ csrf_token() }}' }));
                form.append($('<input>', { 'name': '_method', 'value': 'DELETE' }));
                $('body').append(form);
                form.submit(); // Submit normal → redirect với flash
            }
            $('#deleteUserModal').modal('hide');
        });
    </script>
@endsection