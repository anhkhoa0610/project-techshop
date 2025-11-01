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
                <span id="userInfo" class="me-3 d-none">
                    Xin chào, <strong id="userName"></strong>
                    <button class="btn btn-outline-danger btn-sm ms-2" onclick="logout()">Đăng xuất</button>
                </span>

                <button id="BtnLogin" class="login-btn desktop-only" data-bs-toggle="modal" data-bs-target="#loginModal">
                    Đăng nhập
                </button>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', () => {
    updateUserUI();
});

function updateUserUI() {
    const user = JSON.parse(localStorage.getItem('user'));
    const userInfo = document.getElementById('userInfo');
    const userName = document.getElementById('userName');
    const loginBtn = document.getElementById('BtnLogin');

    if (user) {
        userInfo.classList.remove('d-none');
        userName.textContent = user.full_name;
        loginBtn.classList.add('d-none');
    } else {
        userInfo.classList.add('d-none');
        loginBtn.classList.remove('d-none');
    }
}

async function logout() {
    const token = localStorage.getItem('api_token');
    if (token) {
        await fetch('/api/logout', {
            method: 'POST',
            headers: { 'Authorization': 'Bearer ' + token }
        });
    }

    localStorage.removeItem('api_token');
    localStorage.removeItem('user');
    updateUserUI(); // Cập nhật lại giao diện ngay
    showToast('Đã đăng xuất!', 'info');
}
</script>
