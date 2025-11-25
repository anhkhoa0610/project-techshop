<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TechStore - Cửa hàng công nghệ hàng đầu Việt Nam')</title>
    <meta name="description"
        content="@yield('description', 'TechStore - Chuyên bán điện thoại, laptop, tai nghe chính hãng với giá tốt nhất. Bảo hành uy tín, giao hàng nhanh toàn quốc.')">

    <link rel="stylesheet" href="{{ asset('css/index-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/create-blade.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
</head>

<body>
    @include('partials.header')

    <div class="create-page">
        <div class="create-wrapper">
            <div class="create-form">
                <div class="create-inner">
                    <form action="{{ route('register.postUser') }}" method="post">
                        @csrf
                        <h2>Tạo Tài Khoản</h2>

                        {{-- CHIA 2 CỘT BẰNG BOOTSTRAP --}}
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="fullname"><b>Tên chủ tài khoản</b></label>
                                <input type="text" class="form-control" placeholder="Nhập tên" id="full_name" name="full_name" autofocus>
                                @error('full_name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="email"><b>Email</b></label>
                                <input type="email" class="form-control" placeholder="Nhập Email" id="email_address" name="email">
                                @error('email')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="phone"><b>Điện thoại</b></label>
                                <input type="tel" class="form-control" placeholder="Nhập số điện thoại" name="phone">
                                @error('phone')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>                         

                            <div class="col-md-6 mb-2">
                                <label for="birth"><b>Ngày sinh</b></label>
                                <input type="date" class="form-control" name="dob">
                                @error('dob')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Field địa chỉ chiếm toàn bộ 2 cột --}}
                            <div class="col-12 mb-2">
                                <label for="address"><b>Địa chỉ</b></label>
                                <input type="text" class="form-control" placeholder="Nhập địa chỉ" name="address">
                                @error('address')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="psw"><b>Mật khẩu</b></label>
                                <input type="password" class="form-control" placeholder="Nhập mật khẩu" id="password" name="password">
                                @error('password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-2">
                                <label for="psw-repeat"><b>Nhập lại mật khẩu</b></label>
                                <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" id="password_confirmation"
                                    name="password_confirmation">
                                @error('password_confirmation')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <button type="submit" class="btn btn-primary w-100 mt-2">Tạo tài khoản</button>

                        <div class="mt-3 text-center">
                            Bạn đã có tài khoản?
                            <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none hover-underline">Đăng nhập</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('js/index-script.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>