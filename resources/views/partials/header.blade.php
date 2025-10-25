<header class="header">
    <div class="container">
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
                    <input type="search" placeholder="Tìm kiếm sản phẩm..." class="search-input">
                    <button class="search-btn">🔍</button>
                </div>
                <button class="cart-btn" onclick="window.location.href='{{ route('cart.index') }}'">
                    🛒
                    <span class="cart-count">2</span>
                </button>
                <button class="user-btn">👤</button>
                <button class="login-btn desktop-only">Đăng nhập</button>
            </div>
        </div>
    </div>
</header>
