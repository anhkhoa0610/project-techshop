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
                                    <form class="search-box" method="GET" action="{{ url()->current() }}">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="material-icons">&#xE8B6;</i></span>
                                            <input type="text" class="form-control" name="search" placeholder="Tìm kiếm..."
                                                value="{{ request('search') }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <form method="GET" action="{{ url()->current() }}" class="d-inline">
                                    <div class="input-group">
                                        <select name="role" class="form-control" onchange="this.form.submit()">
                                            <option value="">Tất cả vai trò</option>
                                            <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                            <option value="User" {{ request('role') == 'User' ? 'selected' : '' }}>User</option>

                                        </select>
                                        @if(request('role'))
                                            <div class="input-group-append">
                                                <a href="{{ url()->current() }}" class="btn btn-secondary">Xóa lọc</a>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Giữ lại tham số search khi lọc role -->
                                    @if(request('search'))
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Họ Tên</th>
                                <th>Email</th>
                                <th>Địa chỉ</th>
                                <th>SĐT</th>
                                <th>Vai trò</th>
                                <th>Ngày sinh</th>
                                <th>SV TDC</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr data-user_id="{{ $user->user_id }}"
                                    data-full_name="{{ $user->full_name }}"
                                    data-email="{{ $user->email }}"
                                    data-phone="{{ $user->phone }}"
                                    data-address="{{ $user->address }}"
                                    data-birth="{{ $user->birth }}"
                                    data-role="{{ $user->role }}"
                                    data-is_tdc_student="{{ $user->is_tdc_student }}">
                                    <td>{{ $user->user_id }}</td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->address }}</td>
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
                                        <a href="{{ route('users.edit', $user->user_id) }}" class="edit" title="Sửa">
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
                        <div class="clearfix mt-5">
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



            // Search functionality with AJAX
            $('#searchInput').on('keyup', function () {
                const searchValue = $(this).val();

                if (searchValue.length >= 3 || searchValue.length === 0) {
                    $.ajax({
                        url: '{{ route("users.index") }}',
                        type: 'GET',
                        data: {
                            search: searchValue,
                            role: $('select[name="role"]').val()
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