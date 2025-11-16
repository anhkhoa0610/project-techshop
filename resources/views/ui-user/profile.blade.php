@extends('layouts.layouts')

@section('title', 'Thông tin cá nhân - TechStore')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">

    <div class="profile-container mt-5">
        <div class="sidebar mt-3">
            <div class="text-center mb-4">
                <div class="avatar-container position-relative d-inline-block">
                    @php
                        $user = auth()->user();

                        $avatarUrl = $user->profile ? $user->profile->avatar : '';
                    @endphp
                    @if($avatarUrl)
                        <img src="{{ asset('storage/' . $avatarUrl) }}" alt="{{ $user->full_name }}"
                            class="rounded-circle user-avatar"
                            style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #f0f0f0;">
                    @else
                        <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center bg-secondary text-white"
                            style="width: 150px; height: 150px; font-size: 60px; border: 3px solid #f0f0f0;">
                            {{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}
                        </div>
                    @endif

                    <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data"
                        class="avatar-upload-form">
                        @csrf
                        <label for="avatar-upload" class="btn btn-sm btn-primary position-absolute"
                            style="bottom: -85px; right: 55px; width: 25px; height: 25px; display: flex;">
                            <i class="bi bi-camera"></i>
                            <input type="file" id="avatar-upload" name="avatar" class="d-none" accept="image/*"
                                onchange="this.form.submit()">
                        </label>
                    </form>


                </div>
                <h4 class="mt-3">{{ auth()->user()->full_name }}</h4>
                @if(auth()->user()->profile && auth()->user()->profile->bio)
                    <p class="text-muted">{{ auth()->user()->profile->bio }}</p>
                @endif
            </div>
            <ul>
                <li><a href="{{ route('user.profile') }}">Tài Khoản</a></li>
                <li><a href="#">Địa chỉ</a></li>
                <li><a href="{{ route('promotion.index') }}">Khuyến mãi</a></li>
                <li><a href="#">Đơn mua</a></li>
            </ul>
        </div>

        <div class="profile-content mt-5">
            <div class="profile-box">
                <h3 class="profile-title">Thông tin cá nhân</h3>
                <div class="profile-info">
                    <div class="info-left">
                        <p><strong>Họ và tên:</strong> {{ auth()->user()->full_name }}</p>
                        <p><strong>Ngày sinh:</strong>
                            {{ auth()->user()->birth ? auth()->user()->birth->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                        <p><strong>Phone:</strong> {{ auth()->user()->phone ?? 'Chưa cập nhật' }}</p>
                    </div>
                    <div class="info-right">
                        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                        <p><strong>Address:</strong> {{ auth()->user()->address ?? 'Chưa cập nhật' }}</p>
                        <p>
                            <strong>Sinh viên:</strong> <span
                                style="background-color: {{ auth()->user()->is_tdc_student === 'true' ? 'lightgreen' : 'lightcoral' }}; padding: 5px; border-radius: 4px;">
                                {{ auth()->user()->is_tdc_student === 'true' ? 'Có' : 'Không' }}</span>
                        </p>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="{{ route('user.editProfile') }}" class="btn btn-edit">Chỉnh sửa</a>
                    <a href="{{ route('user.changePassword') }}" class="btn btn-password">Thay mật khẩu</a>
                    <button type="button" class="btn btn-delete" data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                        data-url="{{ route('user.delete') }} " data-name="{{ auth()->user()->full_name }}">
                        Xóa tài khoản
                    </button>

                </div>
            </div>
        </div>
    </div>

    <!-- MODAL XÁC NHẬN XÓA -->
    <div id="deleteUserModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title material-icons mr-5 font-size-35">
                        ⚠️ Xác nhận xóa tài khoản
                    </h5>

                </div>
                <div class="modal-body">
                    <p>Bạn có chắc muốn xóa tài khoản <strong id="userNameToDelete"></strong>?</p>
                    <p class="text-danger"><i class="material-icons" style="font-size:14px;"></i> Hành động này không thể
                        hoàn tác! Những thông tin về tài khoản và lịch sử mua hàng sẽ bị xóa vĩnh viễn.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn"
                        data-url="{{ route('user.delete') }}">
                        <span id="deleteBtnText">Xác nhận xóa</span>
                        <span id="deleteBtnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <style>
        .profile-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            padding: 40px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        }

        /* ===== Sidebar ===== */
        .sidebar {
            width: 20%;
            background-color: #f9f9f9;
            border-radius: 15px;
            padding: 25px;
            min-height: 320px;
        }

        .sidebar h3 {
            font-size: 25px;
            margin-bottom: 10px;
            text-align: center;
            color: #555;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 12px 0;
            text-align: center;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: 0.3s;
        }

        .sidebar ul li a:hover {
            color: #007bff;
        }

        /* ===== Nội dung chính ===== */
        .profile-content {
            flex: 1;
        }

        .profile-box {
            border: 2px solid #ddd;
            padding: 25px 30px;
            border-radius: 20px;
            background-color: #fafafa;
        }

        .profile-title {
            font-size: 25px;
            margin-bottom: 25px;
            font-weight: bold;
            color: #333;
        }

        .profile-info {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            border-radius: 15px;
            background: #fff;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.05);
        }

        .profile-info p {
            margin: 8px 0;
            color: #333;
        }

        .info-left,
        .info-right {
            width: 48%;
        }

        /* ===== Nút hành động ===== */
        .profile-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 25px;
        }

        .btn {
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            color: #fff;
            text-decoration: none;
            text-align: center;
            transition: 0.3s ease;
        }

        .btn-edit {
            background-color: #1798e8;
        }

        .btn-edit:hover {
            background-color: #0f78bd;
        }

        .btn-password {
            background-color: #5dbd27;
        }

        .btn-password:hover {
            background-color: #4ea31f;
        }

        .btn-delete {
            background-color: #d90f9b;
        }

        .btn-delete:hover {
            background-color: #b1097d;
        }

        /* Responsive */

        /* @media (max-width: 768px) {
                                                                                                        .profile-container {
                                                                                                            flex-direction: column;
                                                                                                        }
                                                                                                        .sidebar {
                                                                                                            width: 100%;
                                                                                                        }
                                                                                                        .profile-info {
                                                                                                            flex-direction: column;
                                                                                                        }
                                                                                                        .info-left, .info-right {
                                                                                                            width: 100%;
                                                                                                        }
                                                                                                    } */
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Add this script to handle avatar updates without page reload
        document.addEventListener('DOMContentLoaded', function () {
            const avatarForm = document.querySelector('.avatar-upload-form');
            if (avatarForm) {
                avatarForm.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const avatarImg = document.querySelector('.user-avatar');

                                if (avatarImg) {
                                    avatarImg.src = data.avatar_url + '?v=' + new Date().getTime();
                                } else {
                                    // Nếu chưa có avatar ảnh thì reload trang
                                    window.location.reload();
                                }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công!',
                                    text: 'Cập nhật ảnh đại diện thành công!',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi!',
                                    text: data.message || 'Không thể cập nhật ảnh!'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: 'Có lỗi xảy ra khi cập nhật ảnh đại diện!',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        });

                });
            }
        });
    </script>
    <script>
        $('#deleteUserModal').on('click', '.btn-secondary', function () {
            $('#deleteUserModal').modal('hide');
        });

        $('#deleteUserModal').on('hidden.bs.modal', function () {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });
        $(document).ready(function () {
            // Cập nhật tên người dùng trong modal khi modal được mở
            $('#deleteUserModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget); // Nút đã kích hoạt modal
                var userName = button.data('name'); // Lấy tên từ data-name
                var modal = $(this);
                modal.find('#userNameToDelete').text(userName); // Cập nhật tên trong modal
            });

            // Xử lý khi nhấn nút xác nhận xóa
            $(document).on('click', '#confirmDeleteBtn', function () {
                const deleteUrl = $(this).data('url');
                if (!deleteUrl) {
                    // Thay thế alert bằng SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Không tìm thấy URL để xóa.'
                    });
                    return;
                }

                const btn = $(this);
                const btnText = $('#deleteBtnText');
                const spinner = $('#deleteBtnSpinner');

                // Vô hiệu hóa nút và hiển thị spinner
                btn.prop('disabled', true);
                btnText.addClass('d-none');
                spinner.removeClass('d-none');

                // Gửi yêu cầu AJAX
                $.ajax({
                    url: deleteUrl,
                    type: 'DELETE',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#deleteUserModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: response.message,
                                confirmButtonText: 'OK',
                                allowOutsideClick: false
                            }).then(() => {
                                // Chuyển hướng sau khi xóa thành công
                                window.location.href = response.redirect ||
                                    "{{ route('index') }}";
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: response.message || 'Không thể xóa tài khoản!'
                            });
                        }
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: xhr.responseJSON?.message ??
                                'Đã xảy ra lỗi khi xóa tài khoản!'
                        });
                    },
                    complete: function () {
                        // Kích hoạt lại nút và ẩn spinner
                        btn.prop('disabled', false);
                        btnText.removeClass('d-none');
                        spinner.addClass('d-none');
                    }
                });
            });
        });
    </script>
@endsection