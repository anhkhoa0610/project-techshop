<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>

    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />

    {{-- Bootstrap, fonts, icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700&display=swap" rel="stylesheet">

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Sidebar -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar">
                <a class="logo" href="{{ route('index') }}">
                    <img src="{{ asset('images/icons/logo.png') }}" alt="Logo">
                </a>
                <ul>
                    <li class="category-title"><a href="{{ route('charts') }}"><i
                                class="fas fa-chart-line"></i>Statistics</a>
                    </li>
                    <li class="category-title"><a href="{{ route('products.list') }}"><i
                                class="fa fa-archive"></i>Products</a></li>
                    <li class="category-title"><a href="{{ route('specs.list') }}"><i class="fa fa-cogs"></i>Specs</a>
                    </li>
                    <li class="category-title"><a href="{{ route('categories.list') }}"><i
                                class="fa fa-list"></i>Categories</a></li>
                    <li class="category-title"><a href="{{ route('orders.list')}}"><i
                                class="fa fa-shopping-bag"></i>Orders</a></li>
                    <li class="category-title"><a href="{{ route('supplier.list') }}"><i
                                class="fa fa-truck"></i>Suppliers</a></li>
                    <li class="category-title"><a href="{{ route('voucher.list') }}"><i
                                class="fa fa-ticket"></i>Vouchers</a></li>
                    <li class="category-title"><a href="{{ route('reviews.index') }}"><i
                                class="fa fa-star"></i>Reviews</a></li>
                    <li class="category-title"><a href="{{ route('users.index') }}"><i class="fa fa-users"></i>Users</a>
                    </li>
                    <li class="category-title">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit">
                                <i class="fa fa-sign-out"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
                @php
                $user = Auth::user();
                $defaultAvatar = asset('images/avatars/user-icon.png');

                $avatarUrl = $user && $user->profile && $user->profile->avatar ? asset('images/avatars/' . $user->profile->avatar) : $defaultAvatar;
                @endphp
                <div class="sidebar-avatar">
                    <div class="user-avatar">
                        <img src="{{ $avatarUrl}}"
                            alt="User Avatar">
                    </div>
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->full_name ?? 'Admin User' }}</span>
                        <span class="user-role">Administrator</span>
                    </div>
                </div>
            </div>

            <div class="col-md-10 content-section">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- JS --}}
    <script>
        console.log(document.querySelector('meta[name="csrf-token"]').content);
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>