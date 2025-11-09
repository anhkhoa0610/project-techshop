@extends('layouts.layouts')

@section('title', 'Thay đổi mật khẩu - TechStore')

@section('content')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">

<div class="profile-container mt-5">
    <!-- Sidebar -->
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

    <!-- Main content -->
    <div class="profile-content mt-5">
        <div class="profile-box">
            <h3 class="profile-title">🔐 Thay đổi mật khẩu</h3>

            <form action="{{ route('user.updatePassword') }}" method="POST" id="passwordForm">
                @csrf
                @method('PUT')

                <div class="profile-info">
                    <div class="info-left" style="width:100%">
                        <div class="form-group">
                            <label>Mật khẩu hiện tại:</label>
                            <input type="password" name="current_password" class="form-control" required minlength="6"
                                placeholder="Nhập mật khẩu hiện tại">
                            @error('current_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Mật khẩu mới:</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required minlength="8"
                                placeholder="Nhập mật khẩu mới (ít nhất 8 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt)">
                            <!-- <small class="text-muted">Mật khẩu phải có ít nhất 6 ký tự, gồm chữ hoa, chữ thường, số và ký tự đặc biệt.</small> -->
                            @error('new_password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Xác nhận mật khẩu mới:</label>
                            <input type="password" name="new_password_confirmation" class="form-control" required
                                placeholder="Nhập lại mật khẩu mới">
                            @error('new_password_confirmation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="profile-actions">
                    <button type="submit" class="btn btn-password">Lưu mật khẩu mới</button>
                    <a href="{{ route('user.profile') }}" class="btn btn-edit">Quay lại</a>
                </div>
            </form>
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
    padding: 20px;
    border-radius: 15px;
    background: #fff;
    box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
}
.form-group {
    margin-bottom: 18px;
}
.form-group label {
    font-weight: 600;
    display: block;
    margin-bottom: 8px;
}
.form-control {
    width: 100%;
    padding: 10px 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
}
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

/* Responsive */
@media (max-width: 768px) {
    .profile-container {
        flex-direction: column;
    }
    .sidebar {
        width: 100%;
    }
    .profile-info {
        flex-direction: column;
    }
}
</style>
@endsection