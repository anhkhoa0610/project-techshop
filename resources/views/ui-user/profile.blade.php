@extends('layouts.layouts')

@section('title', 'Thông tin cá nhân - TechStore')

@section('content')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">

<div class="profile-container mt-5">
    <div class="sidebar mt-3">
        <h3>Categories</h3>
        <ul>
            <li><a href="#">Điện thoại</a></li>
            <li><a href="#">Laptop</a></li>
            <li><a href="#">Phụ kiện</a></li>
            <li><a href="#">Âm thanh</a></li>
            <li><a href="#">Máy tính bảng</a></li>
        </ul>
    </div>

    <div class="profile-content mt-5">
        <div class="profile-box">
            <h3 class="profile-title">Thông tin cá nhân</h3>
            <div class="profile-info">
                <div class="info-left">
                    <p><strong>Họ và tên:</strong> {{ auth()->user()->full_name }}</p>
                    <p><strong>Ngày sinh:</strong> {{ auth()->user()->birth ? auth()->user()->birth->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                    <p><strong>Phone:</strong> {{ auth()->user()->phone ?? 'Chưa cập nhật' }}</p>
                </div>
                <div class="info-right">
                    <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
                    <p><strong>Address:</strong> {{ auth()->user()->address ?? 'Chưa cập nhật' }}</p>
                    <p><strong>Sinh viên:</strong> {{ auth()->user()->is_tdc_student === 'true' ? 'Có' : 'Không' }}</p>
                </div>
            </div>

            <div class="profile-actions">
                <a href="#" class="btn btn-edit">Chỉnh sửa</a>
                <a href="{{ route('user.changePassword') }}" class="btn btn-password">Thay mật khẩu</a>
                <!-- <form action="#" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa tài khoản này không?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete">Xóa tài khoản</button>
                </form> -->
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
    box-shadow: 0px 4px 20px rgba(0,0,0,0.1);
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
    box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
}

.profile-info p {
    margin: 8px 0;
    color: #333;
}

.info-left, .info-right {
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

