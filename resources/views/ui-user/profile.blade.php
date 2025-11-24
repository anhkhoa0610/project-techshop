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

                        $defaultAvatarUrl = asset('images/avatars/user-icon.png');
                        $avatarFile = $user->profile->avatar ?? null;
                        if ($avatarFile) {
                            $userAvatarPath = asset('storage/' . $avatarFile);
                        } else {
                            $userAvatarPath = $defaultAvatarUrl;
                        }
                    @endphp

                    <img src="{{ $userAvatarPath }}" onerror="this.src='{{ $defaultAvatarUrl }}'"
                        alt="{{ $user->full_name ?? 'User Avatar' }}" class="rounded-circle user-avatar"
                        style="width:150px;height:150px;object-fit:cover;border:3px solid #f0f0f0;">


                    <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data"
                        class="avatar-upload-form">
                        @csrf
                        <label for="avatar-upload"
                            class="btn btn-sm btn-primary position-absolute rounded-circle p-0 d-flex align-items-center justify-content-center"
                            style="bottom: 0px; right: 0px; width: 30px; height: 30px; cursor: pointer; border: 2px solid #fff;">
                            <i class="bi bi-camera" style="font-size: 14px;"></i>

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
            <div class="sidebar-menu" style="width:250px; background:#fafafa; padding:20px; border-radius:10px;">
                <h6 style="font-weight:bold;">Tài Khoản Của Tôi</h6>
                <ul class="profile-tabs" style="list-style:none; padding:0; margin-top:15px;">
                    <li class="tab-item active"><a href="{{ route('user.profile') }}">Thông tin cá nhân</a></li>
                    <li class="tab-item"> <a href="{{ route('user.editProfile') }}">Chỉnh sửa</a></li>
                    <!-- <li class="tab-item">Đơn mua</li> -->
                    <li class="tab-item"><a href="{{ route('user.changePassword') }}">Thay mật
                            khẩu</a> </li>
                    <li class="tab-item"> <button type="button" data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                            data-url="{{ route('user.delete') }}" class="btn btn-danger"
                            data-name="{{ auth()->user()->full_name }}">
                            Xóa tài khoản</button></li>
                </ul>
                <h6 style="margin-top:25px; font-weight:bold;">
                    <a href="#">Khuyến mãi</a>
                </h6>
                <h6 style="margin-top:25px; font-weight:bold;"> <a href="{{ route('user.verifyTdc.send') }}">Sinh viên TDC</a></h6>
            </div>
        
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
                    <!-- <a class="btn btn-edit">Chỉnh sửa</a> -->
                </div>
            </div>
        </div>
<!-- Nút GỬI MÃ XÁC NHẬN -->
@if (auth()->user()->is_tdc_student !== 'true' && str_ends_with(auth()->user()->email, '@mail.tdc.edu.vn'))

    @if(session('verification_sent'))
        <div class="alert alert-info">
            Mã xác nhận đã được gửi đến <strong>{{ auth()->user()->email }}</strong>. 
            Vui lòng kiểm tra email (kể cả mục Spam/Junk).
        </div>
    @endif

    <!-- Form gửi mã -->
    <form action="{{ route('user.verifyTdc.send') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-primary btn-sm">
            Gửi mã xác nhận đến email
        </button>
    </form>

    <hr>

    <!-- Form nhập mã xác nhận -->
    <form action="{{ route('user.verifyTdc.confirm') }}" method="POST">
        @csrf
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="verification_code" class="col-form-label">Nhập mã 6 ký tự:</label>
            </div>
            <div class="col-auto">
                <input type="text" name="verification_code" id="verification_code" 
                       class="form-control @error('verification_code') is-invalid @enderror" 
                       maxlength="6" required autocomplete="off">
                @error('verification_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success">Xác nhận</button>
            </div>
        </div>
    </form>

@elseif(str_ends_with(auth()->user()->email, '@mail.tdc.edu.vn'))
    <p class="text-success">
        Đã xác nhận là sinh viên TDC
    </p>
@else
    <p class="text-danger">
        Email không hợp lệ. Chỉ chấp nhận email có đuôi @mail.tdc.edu.vn
    </p>
@endif
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
        .tab-item {
            padding: 6px 0;
            cursor: pointer;
            color: #333;
            font-size: 15px;
        }

        .tab-item:hover {
            color: #ff5733;
        }

        .tab-item.active {
            font-weight: bold;
            color: #ff5733;
        }

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
        document.addEventListener('DOMContentLoaded', function () {
            const avatarForm = document.querySelector('.avatar-upload-form');
            const fileInput = document.getElementById('avatar-upload');

            if (fileInput) {
                fileInput.addEventListener('change', function () {

                    // (1) Kiểm tra nếu không có file
                    if (!this.files || !this.files[0]) return;

                    const file = this.files[0];

                    // (2) Kiểm tra định dạng file
                    if (!file.type.startsWith("image/")) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi file!',
                            text: 'Vui lòng chọn đúng định dạng hình ảnh.'
                        });
                        return;
                    }

                    // (3) Kiểm tra kích thước file (< 2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ảnh quá lớn!',
                            text: 'Kích thước ảnh tối đa là 2MB.'
                        });
                        return;
                    }

                    const formData = new FormData(avatarForm);
                    const avatarImg = document.querySelector('.user-avatar');

                    // Loading effect
                    if (avatarImg) avatarImg.style.opacity = '0.5';

                    fetch(avatarForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (!data.success) throw new Error(data.message || "Upload thất bại");

                            // Cập nhật ảnh với cache buster để hiển thị ảnh mới
                            avatarImg.src = data.avatar_url + "?v=" + Date.now();
                            avatarImg.style.opacity = '1';

                            Swal.fire({
                                icon: 'success',
                                title: 'Cập nhật thành công!',
                                text: 'Ảnh đại diện đã được thay đổi.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: err.message
                            });
                            if (avatarImg) avatarImg.style.opacity = '1';
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