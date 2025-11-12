<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>

    {{-- Bootstrap, fonts, icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

</head>

<body>
    <!-- Sidebar -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <div class="logo">
                    <img src="{{ asset('images/logo.jpg') }}" alt="Logo">
                </div>
                <ul style="margin-top: -50px;">
                    <li><a href="{{ route('users.index') }}"><i class="fa fa-users"></i> Quản Lý Khách Hàng</a></li>
                    <li><a href="{{ route('products.list') }}"><i class="fa fa-archive"></i> Quản Lý Sản Phẩm</a></li>
                    <li><a href="{{ route('categories.list') }}"><i class="fa fa-list"></i> Quản Lý Danh Mục</a></li>
                    <li><a href="{{ route('orders.list')}}"><i class="fa fa-shopping-bag"></i> Quản Lý Đơn Hàng</a></li>
                    <li><a href="{{ route('supplier.list') }}"><i class="fa fa-truck"></i> Quản Lý Nhà Phân Phối</a>
                    </li>
                    <li><a href="{{ route('voucher.list') }}"><i class="fa fa-ticket"></i> Quản Lý Voucher</a></li>
                    <li><a href="{{ route('reviews.index') }}"><i class="fa fa-star"></i> Quản Lý Review</a></li>
                    <li><a href="{{ route('index') }}"><i class="fa fa-home"></i> Trang chủ</a></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit">
                                <i class="fa fa-sign-out"></i> Đăng Xuất
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

            <div class="col-md-10 content">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    @stack('scripts')
</body>
</html>