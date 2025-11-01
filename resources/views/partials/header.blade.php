@include('components.login-modal')
<header class="header">
    <div class="header-container">
        <div class="header-content">
            <div class="header-left">
                <button class="menu-btn mobile-only">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <div class="logo">TechStore</div>
            </div>

            <nav class="nav desktop-only">
                <a href="{{ asset('/index') }}" class="nav-link">Trang chủ</a>
                <a href="#" class="nav-link">Điện thoại</a>
                <a href="#" class="nav-link">Laptop</a>
                <a href="#" class="nav-link">Phụ kiện</a>
                <a href="#" class="nav-link">Khuyến mãi</a>
            </nav>

            <div class="header-actions">
                <div class="search-box desktop-only">
                    <input type="search" id="header-search-input" placeholder="Tìm kiếm sản phẩm..." class="search-input">
                    <button class="search-btn" id="header-search-btn">🔍</button>
                    <div id="search-results" class="search-results"></div>

                </div>
                <button class="cart-btn" onclick="window.location.href='{{ route('cart.index') }}'">
                    🛒
                    <span class="cart-count">2</span>
                </button>
                <button class="user-btn">👤</button>
                @if (Auth::check())
                    <span class="me-3">
                        Xin chào, <strong>{{ Auth::user()->full_name }}</strong>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm ms-2">Đăng xuất</button>
                        </form>
                    </span>
                @else
                    <a href="{{ route('login') }}">
                        <button id="BtnLogin" class="login-btn desktop-only">Đăng nhập</button>
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>
