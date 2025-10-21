@extends('layouts.dashboard')

@section('content')
    <!-- Main Content -->
    <div class="content">
        <div class="container-xl">
            <div class="table-responsive text-center">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-4">
                                <a href="{{ route('users.create') }}" class="btn btn-info add-new">
                                    <i class="fas fa-plus"></i> Thêm Người Dùng
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <h2 class="text-center"><b>Quản Lý Người Dùng</b></h2>
                            </div>
                            <div class="col-sm-4">
                                <div class="search-box">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="material-icons">&#xE8B6;</i></span>
                                        <input type="text" class="form-control" id="searchInput"
                                            placeholder="Tìm kiếm người dùng...">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Họ Tên</th>
                                <th>Email</th>
                                <th>SĐT</th>
                                <th>Vai trò</th>
                                <th>Ngày sinh</th>
                                <th>SV TDC</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr data-user-id="{{ $user->user_id }}" data-full_name="{{ $user->full_name }}"
                                    data-email="{{ $user->email }}" data-phone="{{ $user->phone }}"
                                    data-address="{{ $user->address }}" data-role="{{ $user->role }}"
                                    data-birth="{{ $user->birth->format('Y-m-d') }}"
                                    data-is_tdc_student="{{ $user->is_tdc_student }}">
                                    <td>{{ $user->user_id }}</td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? '—' }}</td>
                                    <td>
                                        <span class="badge {{ $user->role === 'Admin' ? 'badge-success' : 'badge-primary' }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td>{{ $user->birth->format('d/m/Y') }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $user->is_tdc_student === 'true' ? 'badge-success' : 'badge-secondary' }}">
                                            {{ $user->is_tdc_student === 'true' ? 'Có' : 'Không' }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="#" class="view" title="Xem" data-toggle="modal" data-target="#viewUserModal">
                                            <i class="material-icons text-info">&#xE417;</i>
                                        </a>
                                        <a href="#" class="edit" title="Sửa" data-toggle="modal" data-target="#editUserModal">
                                            <i class="material-icons text-warning">&#xE254;</i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user->user_id) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline" title="Xóa">
                                                <i class="material-icons text-danger">&#xE872;</i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                   <div class="clearfix">
                        <div class="clearfix mt-3">
                            <nav>
                                {{ $users->withQueryString()->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->

@endsection

@push('scripts')
    <script>
        // Trong sự kiện click nút chỉnh sửa
$(document).on('click', '.edit', function() {
    const row = $(this).closest('tr');
    const userId = row.data('user-id');
    const form = $('#editUserForm');
    const email = row.data('email');
    
    // Kiểm tra đuôi email
    const isTDCEmail = email.endsWith('@mail.tdc.edu.vn');
    
    form.attr('action', '/users/' + userId);
    form.find('#edit_full_name').val(row.data('full_name'));
    form.find('#edit_email').val(email);
    form.find('#edit_phone').val(row.data('phone') || '');
    form.find('#edit_address').val(row.data('address') || '');
    form.find('#edit_role').val(row.data('role') || 'User');
    form.find('#edit_birth').val(row.data('birth') || '');
    
     form.find('#edit_is_tdc_student').prop('checked', isTDCEmail || row.data('is_tdc_student') === 'true');
});

// Thêm sự kiện thay đổi email để tự động cập nhật toggle
$(document).on('change', '#edit_email', function() {
    const email = $(this).val();
    const isTDCEmail = email.endsWith('@mail.tdc.edu.vn');
    if (isTDCEmail) {
        $('#edit_is_tdc_student').prop('checked', true);
    }
});
// End Edit User
        $(document).ready(function () {
            // View user details
            $(document).on('click', '.view', function () {
                const row = $(this).closest('tr');
                $('#view_full_name').text(row.data('full_name') || '—');
                $('#view_email').text(row.data('email') || '—');
                $('#view_phone').text(row.data('phone') || '—');
                $('#view_address').text(row.data('address') || '—');
                $('#view_birth').text(row.data('birth') ? new Date(row.data('birth')).toLocaleDateString('vi-VN') : '—');

                const role = row.data('role');
                const roleBadge = role === 'Admin' ? 'badge-success' : 'badge-primary';
                $('#view_role').text(role).removeClass().addClass('badge ' + roleBadge);

                const isTDC = row.data('is_tdc_student') === 'true';
                const tdcBadge = isTDC ? 'badge-success' : 'badge-secondary';
                $('#view_tdc').text(isTDC ? 'Có' : 'Không').removeClass().addClass('badge ' + tdcBadge);
            });

            // Edit user - populate form
            $(document).on('click', '.edit', function () {
                const row = $(this).closest('tr');
                const userId = row.data('user-id');
                const form = $('#editUserForm');

                form.attr('action', '/users/' + userId);
                form.find('#edit_full_name').val(row.data('full_name'));
                form.find('#edit_email').val(row.data('email'));
                form.find('#edit_phone').val(row.data('phone') || '');
                form.find('#edit_address').val(row.data('address') || '');
                form.find('#edit_role').val(row.data('role') || 'User');
                form.find('#edit_birth').val(row.data('birth') || '');
                form.find('#edit_is_tdc_student').prop('checked', row.data('is_tdc_student') === 'true');
            });

            // Search functionality with AJAX
            $('#searchInput').on('keyup', function () {
                const searchValue = $(this).val();

                if (searchValue.length >= 3 || searchValue.length === 0) {
                    $.ajax({
                        url: '{{ route("users.index") }}',
                        type: 'GET',
                        data: {
                            search: searchValue
                        },
                        success: function (data) {
                            $('#user-table-container').html(data);
                        },
                        error: function (xhr) {
                            console.log('Error:', xhr);
                        }
                    });
                }
            });

            // Delete user with AJAX
            $(document).on('submit', '.delete-form', function (e) {
                e.preventDefault();

                if (confirm('Bạn có chắc chắn muốn xóa người dùng này?')) {
                    const form = $(this);
                    const url = form.attr('action');

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.success) {
                                // Reload the user table
                                $('#searchInput').trigger('keyup');
                                alert(response.message);
                            }
                        },
                        error: function (xhr) {
                            alert('Có lỗi xảy ra khi xóa người dùng!');
                        }
                    });
                }
            });
        });
    </script>
@endpush