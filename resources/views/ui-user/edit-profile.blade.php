@extends('layouts.layouts')

@section('title', 'Chỉnh sửa thông tin cá nhân - TechStore')

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

        <!-- Nội dung chính -->
        <div class="profile-content mt-5">
            <div class="profile-box">
                <h3 class="profile-title">✏️ Chỉnh sửa thông tin cá nhân</h3>

                <form action="{{ route('user.updateProfile') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="profile-info">
                        <div class="info-left">
                            <div class="form-group">
                                <label>Họ và tên:</label>
                                <input type="text" name="full_name" class="form-control"
                                    value="{{ old('full_name', auth()->user()->full_name) }}" required>
                                @error('full_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Ngày sinh:</label>
                                <input type="date" name="birth" class="form-control"
                                    value="{{ old('birth', auth()->user()->birth ? auth()->user()->birth->format('Y-m-d') : '') }}"
                                    required>
                                @error('birth')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Số điện thoại:</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', auth()->user()->phone) }}">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="info-right">
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Địa chỉ:</label>
                                <textarea name="address" class="form-control"
                                    rows="3">{{ old('address', auth()->user()->address) }}</textarea>
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Sinh viên TDC:</label>
                                <input type="text" class="form-control"
                                    value="{{ auth()->user()->is_tdc_student === 'true' ? 'Có' : 'Không' }}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="profile-actions">
                        <button type="submit" class="btn btn-edit">💾 Lưu thay đổi</button>
                        <a href="{{ route('user.profile') }}" class="btn btn-password">↩️ Quay lại</a>
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
            box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
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
            display: flex;
            /* justify-content: space-between; */
            gap: 120px;
            padding: 20px;
            border-radius: 15px;
            background: #fff;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 5px;
            display: inline-block;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
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
        
        .info-left, .info-right {
            width: 48%;
        }
    
     
    </style>
@endsection