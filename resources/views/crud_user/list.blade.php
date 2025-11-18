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
                                <th>Ảnh đại diện</th>
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
                                    <td>
                                        <div class="avatar-container" style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; margin: 0 auto;">
                                            @if($user->profile && $user->profile->avatar)
                                                <img src="{{ asset('storage/' . $user->profile->avatar) }}" alt="{{ $user->full_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <div style="width: 100%; height: 100%; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user" style="font-size: 24px; color: #888;"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
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
                                        <a href="{{ route('users.show', $user->user_id)}}" class="view" title="Xem" data-target="#viewUserModal" data-url="{{ route('users.show', $user->user_id)}}">
                                            <i class="material-icons text-info">&#xE417;</i>
                                        </a>
                                        <a href="{{ route('users.edit', $user->user_id) }}" class="edit" title="Sửa">
                                            <i class="material-icons text-warning">&#xE254;</i>
                                        </a>
                                        
                                        <a href="#deleteUserModal" class="delete" title="Xóa" data-toggle="modal"
                                            data-target="#deleteUserModal"
                                            data-url="{{ route('users.destroy', $user->user_id) }}"
                                            data-name="Họ và tên {{ $user->full_name ?? 'người dùng' }}">
                                            <i class="material-icons text-danger">&#xE872;</i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Không có dữ liệu</td>
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

<!-- Modal Xóa -->
    <div id="deleteUserModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-confirm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <i class="material-icons modal-icon">priority_high</i>
                    <h4 class="modal-title w-100">Xác nhận xóa</h4>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa người dùng này không?</p>
                    <p id="modalEntityName" style="font-weight: bold; color: #555;"></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Khởi tạo biến lưu trữ URL xóa và dòng cần xóa
        let deleteUrl = "";
        let rowToDelete = null;

        // Khi mở modal xác nhận xóa
        $(document).on('show.bs.modal', '#deleteUserModal', function(event) {
            // Đảm bảo chỉ có một modal backdrop
            if ($('.modal-backdrop').length > 1) {
                $('.modal-backdrop').not(':first').remove();
            }
            const button = $(event.relatedTarget);
            deleteUrl = button.data('url');
            rowToDelete = button.closest('tr');
            const entityName = button.data('name') || 'đánh giá';
            $('#modalEntityName').text(entityName);
            
            console.log('Delete URL:', deleteUrl);
        });

        // Hàm đóng modal đúng cách
        function closeModal() {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            $('#deleteUserModal').modal('hide').remove();
        }

        // Khi click nút xác nhận xóa
        $(document).on('click', '#confirmDeleteBtn', function() {
            const $btn = $(this);
            
            if (!deleteUrl) {
                alert('Không tìm thấy URL để xóa!');
                return;
            }

            // Vô hiệu hóa nút và hiển thị trạng thái đang xử lý
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xóa...').prop('disabled', true);

            // Gửi yêu cầu xóa
            $.ajax({
                url: deleteUrl,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Delete success:', response);
                    
                    if (response.success) {
                        // Đóng modal
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        $('#deleteUserModal').modal('hide');
                        
                        // Hiển thị thông báo thành công
                        showAlert('success', response.message || 'Đã xóa đánh giá thành công!');
                        
                        // Tự động tải lại trang sau 1 giây
                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    } else {
                        showAlert('danger', response.message || 'Không thể xóa đánh giá.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Delete error:', status, error);
                    console.error('Response:', xhr.responseText);
                    
                    let errorMessage = 'Đã xảy ra lỗi khi xóa đánh giá.';
                    
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response && response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {
                        console.error('Error parsing error response:', e);
                    }
                    
                    showAlert('danger', errorMessage);
                },
                complete: function() {
                    // Khôi phục trạng thái nút
                    $btn.html('Xóa').prop('disabled', false);
                    
                    // Đảm bảo modal được đóng đúng cách nếu có lỗi
                    if ($('#deleteUserModal').length) {
                        closeModal();
                    }
                }
            });
        });
        
        // Hàm hiển thị thông báo
        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>`;
            
            // Thêm thông báo vào đầu container
            $('.container-xl').prepend(alertHtml);
            
            // Tự động ẩn thông báo sau 5 giây
            setTimeout(() => {
                $('.alert').fadeOut(400, function() {
                    $(this).remove();
                });
            }, 5000);
        }
    });
</script>
@endsection