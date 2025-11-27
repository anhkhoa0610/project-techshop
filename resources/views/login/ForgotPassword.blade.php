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
    <link rel="stylesheet" href="{{ asset('css/forgot-blade.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
</head>

<body>
    {{-- Header --}}
    @include('partials.header')
    <div class="forgot-page">
        <div class="forgot-wrapper">
            <div class="login-image">
                <img src="{{ asset('images/forgot-panner.png') }}" alt="TechStore">
            </div>
            <div class="forgot-form">
                <div class="forgot-inner">
                    <form action="{{ route('forgot') }}" method="post">
                        @csrf
                        <div class="container">
                            <h2>Quên Mật Khẩu</h2>
                            <div class="mb-3">
                                <label for="email"><b>Nhập Email</b></label>
                                <input type="email" placeholder="Nhập email của bạn" name="email">
                                @error('email')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                @if (session('status'))
                                    <div class="text-success mt-1">{{ session('status') }}</div>
                                @endif
                            </div>

                            <button type="submit">Gửi đường dẫn khôi phục mật khẩu</button>
                            <div class="psw">
                                <p><a href="{{ route('login') }}">Quay lại trang đăng nhập</a></p>
                            </div>
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