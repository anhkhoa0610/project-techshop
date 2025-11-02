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
    <link rel="stylesheet" href="{{ asset('css/login-blade.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
</head>

<body>
    {{-- Header --}}
    @include('partials.header')
    <div class="login-page">
        <div class="login-wrapper">
            <div class="login-image">
                <img src="{{ asset('images/login.png') }}" alt="TechStore">
            </div>

            <div class="login-form">
                <div class="login-inner">
                    <h2>Đăng nhập</h2>
                    <form action="{{ route('user.authUser') }}" method="post">
                        @csrf
                        @if ($errors->has('login'))
                            <div class="alert alert-danger d-flex align-items-center" role="alert" style="border-radius: 8px;">
                                <i class="fa fa-exclamation-circle me-2" style="font-size: 18px;"></i>
                                <div>{{ $errors->first('login') }}</div>
                            </div>
                        @endif

                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Nhập email">
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif

                        <label for="password">Mật khẩu</label>
                        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu">
                        @if ($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif

                        <a href="{{ route('forgot.form') }}" class="forgot">Quên mật khẩu?</a>

                        <button type="submit">Đăng nhập</button>

                        <div class="text-center mt-3">
                            <p class="small">
                                Chưa có tài khoản?
                                <a href="{{ route('register') }}" class="text-primary fw-semibold text-decoration-none hover-underline">Đăng ký</a>
                            </p>
                            <a href="{{ route('index') }}" class="text-secondary small text-decoration-none hover-underline">
                                <i class="fa fa-arrow-left me-1"></i> Quay lại trang chủ
                            </a>
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