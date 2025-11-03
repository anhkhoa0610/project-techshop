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
                @if (Auth::check())
                    <div class="user-dropdown">
                        <button type="button" class="user-toggle" aria-haspopup="true" aria-expanded="false">
                            <i class="fa-solid fa-user me-2"></i>
                            <strong>{{ explode(' ', Auth::user()->full_name)[count(explode(' ', Auth::user()->full_name)) - 1] }}</strong>
                            <i class="fa-solid fa-caret-down ms-2 small caret-icon"></i>
                        </button>

                        <div class="user-menu" role="menu" aria-hidden="true">
                            <a href="" class="dropdown-item">
                                <i class="fa-solid fa-id-card me-2"></i> Tài khoản của tôi
                            </a>
                            @if (Auth::user()->role === "Admin")
                                <a href="{{ route('dashboard') }}" class="dropdown-item">
                                    <i class="fa-solid fa-building me-2"></i> Trang quản trị
                                </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST" class="dropdown-form" role="none">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i> Đăng xuất
                                </button>
                            </form>

                        </div>
                    </div>

                @else
                    <a href="{{ route('register') }}" style="text-decoration: none;">
                        <button id="BtnLogin" class="login-btn">Đăng ký</button>
                    </a>
                    <a href="{{ route('login') }}" style="text-decoration: none;">
                        <button id="BtnLogin" class="login-btn desktop-only">Đăng nhập</button>
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>

<script>
    const dropdown = document.querySelector('.user-dropdown');

    dropdown.addEventListener('mouseenter', () => {
        dropdown.classList.add('open');
    });

    dropdown.addEventListener('mouseleave', () => {
        dropdown.classList.remove('open');
    });

</script>
