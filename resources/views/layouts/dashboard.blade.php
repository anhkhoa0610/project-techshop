<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>

    {{-- Bootstrap, fonts, icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo">
        </div>
        <ul style="margin-top: -50px;">
            <li><a href="#"><i class="fa fa-users"></i> Quản Lý Khách Hàng</a></li>
            <li><a href="{{ route('product.index') }}"><i class="fa fa-archive"></i> Quản Lý Sản Phẩm</a></li>
            <li><a href="{{ route('category.index') }}"><i class="fa fa-list"></i> Quản Lý Danh Mục</a></li>
            <li><a href="#"><i class="fa fa-shopping-bag"></i> Quản Lý Đơn Hàng</a></li>
            <li><a href="#"><i class="fa fa-file-text"></i> Quản Lý Chi Tiết Đơn Hàng</a></li>
            <li><a href="#"><i class="fa fa-truck"></i> Quản Lý Nhà Phân Phối</a></li>
            <li><a href="#"><i class="fa fa-ticket"></i> Quản Lý Voucher</a></li>
            <li><a href="#"><i class="fa fa-star"></i> Quản Lý Review</a></li>
            <li><a href="#"><i class="fa fa-sign-out"></i> Đăng Xuất</a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="content">
        @yield('content')
    </div>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
            // Animate select box length
            var searchInput = $(".search-box input");
            var inputGroup = $(".search-box .input-group");
            var boxWidth = inputGroup.width();
            searchInput.focus(function () {
                inputGroup.animate({
                    width: "300"
                });
            }).blur(function () {
                inputGroup.animate({
                    width: boxWidth
                });
            });
        });
    </script>
</body>
</html>
