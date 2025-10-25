@extends('layouts.layouts')

@section('title', 'TechStore - Trang chủ')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-filter.css') }}">

    <!-- Sidebar -->


    <!-- Nút mở sidebar -->
    <!-- <button id="openSidebar" class="sidebar-toggle">
                <span> <i class="bi bi-funnel me-1"></i> Lọc</span>
            </button> -->


    <!-- Hero Section -->

    <section class="hero">
        <!-- moved hero-image ra trước container để video có thể phủ toàn section -->
        <div class="hero-image">
            <video class="hero-video" autoplay muted loop playsinline preload="metadata"
                poster="{{ asset('images/place-holder.jpg') }}">
                <source src="{{ asset('videos/banner.mp4') }}" type="video/mp4">
                <img src="{{ asset('images/place-holder.jpg') }}" alt="Banner">
            </video>
        </div>

        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <span class="hero-badge">🔥 Khuyến mãi đặc biệt</span>
                    <h1 class="hero-title">
                        Sony Xperia
                        <span class="hero-subtitle">Pro Series</span>
                    </h1>
                    <p class="hero-description">
                        Trải nghiệm công nghệ đỉnh cao với camera chuyên nghiệp và hiệu suất vượt trội.
                        Giảm giá lên đến 30% cho đơn hàng đầu tiên.
                    </p>
                    <div class="hero-buttons">
                        <button class="btn btn-primary">Mua ngay</button>
                        <button class="btn btn-outline">Xem chi tiết</button>
                    </div>
                    <div class="hero-specs">
                        <div class="spec-item">
                            <div class="spec-value">24MP</div>
                            <div class="spec-label">Camera chính</div>
                        </div>
                        <div class="spec-item">
                            <div class="spec-value">256GB</div>
                            <div class="spec-label">Bộ nhớ</div>
                        </div>
                        <div class="spec-item">
                            <div class="spec-value">5G</div>
                            <div class="spec-label">Kết nối</div>
                        </div>
                    </div>
                </div>
                <!-- hero-image removed from here -->
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <div class="background-overlay">

        <section class="categories">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Danh mục nổi bật</h2>
                    <p class="section-subtitle">Khám phá các sản phẩm công nghệ hàng đầu</p>
                </div>
                <div class="categories-grid">
                    <div class="category-card">
                        <div class="category-icon primary">📱</div>
                        <h3 class="category-title">The Best Smartphone</h3>
                        <p class="category-subtitle">Điện thoại cao cấp</p>
                    </div>
                    <div class="category-card">
                        <div class="category-icon accent">💻</div>
                        <h3 class="category-title">Gaming Laptop</h3>
                        <p class="category-subtitle">Laptop chuyên game</p>
                    </div>
                    <div class="category-card">
                        <div class="category-icon primary">🎧</div>
                        <h3 class="category-title">Premium Headphone</h3>
                        <p class="category-subtitle">Tai nghe chất lượng cao</p>
                    </div>
                    <div class="category-card">
                        <div class="category-icon accent">📱</div>
                        <h3 class="category-title">Tablet & iPad</h3>
                        <p class="category-subtitle">Máy tính bảng</p>
                    </div>
                    <div class="category-card">
                        <div class="category-icon primary">⌚</div>
                        <h3 class="category-title">Smart Watch</h3>
                        <p class="category-subtitle">Đồng hồ thông minh</p>
                    </div>
                    <div class="category-card">
                        <div class="category-icon accent">📷</div>
                        <h3 class="category-title">Camera & Photo</h3>
                        <p class="category-subtitle">Máy ảnh chuyên nghiệp</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="products sale-products">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Sản phẩm nổi bật</h2>
                    <p class="section-subtitle">Những sản phẩm được yêu thích nhất</p>
                </div>
                <div class="products-grid">
                    <?php foreach ($topProducts as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="{{ $product->cover_image ? asset('uploads/' . $product->cover_image) : asset('images/place-holder.jpg') }}"
                                alt="{{ $product->product_name }}">
                            <div class="product-badge">Bán chạy</div>
                            <div class="product-discount">-13%</div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?= $product->product_name; ?></h3>
                            <div class="product-rating">
                                <span class="stars">⭐ 4.9</span>
                                <span class="reviews">(156 đánh giá)</span>
                            </div>
                            <div class="product-price">
                                <span class="current-price"><?= number_format($product->price, 0, ',', '.'); ?>₫</span>
                                <span
                                    class="original-price"><?= number_format($product->original_price, 0, ',', '.'); ?>₫</span>
                            </div>
                            <button class="btn btn-primary full-width">🛒 Thêm vào giỏ</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>


        <!-- Featured Products -->
        <section class="products new-products">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Sản phẩm mới nhất</h2>
                    <p class="section-subtitle">Những sản phẩm mới nhất</p>
                </div>
                <div class="products-grid">
                    <?php foreach ($newProducts as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="{{ $product->cover_image ? asset('uploads/' . $product->cover_image) : asset('images/place-holder.jpg') }}"
                                alt="{{ $product->product_name }}">
                            <div class="product-badge">Bán chạy</div>
                            <div class="product-discount">-13%</div>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?= $product->product_name; ?></h3>
                            <div class="product-rating">
                                <span class="stars">⭐ 4.9</span>
                                <span class="reviews">(156 đánh giá)</span>
                            </div>
                            <div class="product-price">
                                <span class="current-price"><?= number_format($product->price, 0, ',', '.'); ?>₫</span>
                            </div>
                            <button class="btn btn-primary full-width">🛒 Thêm vào giỏ</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="products categories-products" style="display: none">
            <div class="container-fluid">
                <div class="section-header">
                    <h2 class="section-title">Sản phẩm theo danh mục</h2>
                    <p class="section-subtitle">Tất cả sản phẩm</p>
                </div>
                <div class="row">
                    <div class="col-md-3" style="color: white">
                        <div class="sidebar">
                            <div class="sidebar-header">
                                <span class="sidebar-title">Lọc sản phẩm</span>
                            </div>
                            <form id="filterForm" class="mt-4">
                                <!-- Giá tiền -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Giá tiền (VNĐ)</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="number" class="form-control number-input" name="price_min"
                                            placeholder="" min="0" step="1000" style="max-width: 16rem;">
                                        <span class="fw-bold">–</span>
                                        <input type="number" class="form-control number-input" name="price_max"
                                            placeholder="" min="0" step="1000" style="max-width: 16rem;">
                                    </div>
                                </div>


                                <!-- Danh mục -->
                                <div class="mb-4">
                                    <label for="category" class="form-label fw-semibold">Danh mục</label>
                                    <select class="form-select" id="category" name="category_filter">
                                        <option value="0">Tất cả</option>
                                        <option value="1">Laptop</option>
                                        <option value="2">Điện thoại</option>
                                        <option value="3">Phụ kiện</option>
                                        <option value="4">Máy tính bảng</option>
                                    </select>
                                </div>

                                <!-- Nhà phân phối -->
                                <div class="mb-4">
                                    <label for="supplier" class="form-label fw-semibold">Nhà phân phối</label>
                                    <select class="form-select" id="supplier" name="supplier_filter">
                                        <option value="0">Tất cả</option>
                                        <option value="1">Apple</option>
                                        <option value="2">Samsung</option>
                                        <option value="3">ASUS</option>
                                        <option value="4">Dell</option>
                                    </select>
                                </div>

                                <!-- Rating -->

                                <div class="mb-4">
                                    <label for="rating" class="form-label fw-semibold">Đánh giá</label>
                                    <select class="form-select" id="rating" name="rating">
                                        <option value="all">Tất cả</option>
                                        <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                                        <option value="4">⭐️⭐️⭐️⭐️</option>
                                        <option value="3">⭐️⭐️⭐️</option>
                                        <option value="2">⭐️⭐️</option>
                                        <option value="1">⭐️</option>
                                    </select>
                                </div>

                                <!-- Tình trạng hàng -->
                                <div class="mb-4">
                                    <label for="stock_status" class="form-label fw-semibold">Tình trạng hàng</label>
                                    <select class="form-select" id="stock_status" name="stock_status">
                                        <option value="all">Tất cả</option>
                                        <option value="in_stock">Còn hàng</option>
                                        <option value="out_of_stock">Hết hàng</option>
                                    </select>
                                </div>

                                <!-- Thời gian ra mắt -->
                                <div class="mb-4">
                                    <label for="release_date" class="form-label fw-semibold">Thời gian ra mắt</label>
                                    <select class="form-select" id="release_date" name="release_date">
                                        <option value="all">Tất cả</option>
                                        <option value="last_30_days">30 ngày qua</option>
                                        <option value="last_90_days">90 ngày qua</option>
                                        <option value="last_6_months">6 tháng qua</option>
                                        <option value="last_1_year">1 năm qua</option>
                                    </select>
                                </div>

                                <!-- Đang giảm giá -->
                                <div class="mb-4 form-check">
                                    <input type="checkbox" class="form-check-input" id="on_sale" name="on_sale">
                                    <label class="form-check-label fw-semibold" for="on_sale">Chỉ hiển thị sản phẩm đang
                                        giảm giá</label>
                                </div>

                                <!-- Nút áp dụng -->
                                <button type="submit" class="btn btn-primary w-100">Áp dụng bộ lọc</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="products-grid show-by-category">

                        </div>
                    </div>
                </div>

                <div class="pagination mt-5">
                    <!-- ... -->
                </div>
            </div>
        </section>
    </div>

    <!-- Deal of the Day -->
    <section class="deal-section">
        <div class="container">
            <div class="deal-header">
                <h2 class="deal-title">⚡ Deal of the Day</h2>
                <p class="deal-subtitle">Ưu đãi có thời hạn - Nhanh tay kẻo lỡ!</p>
            </div>
            <div class="deal-card">
                <div class="deal-image">
                    <img src="https://www.apple.com/v/iphone-17-pro/a/images/overview/contrast/iphone_17_pro__dwccrdina7qu_large.jpg"
                        alt="Xiaomi Deal">
                    <div class="flash-badge">FLASH SALE</div>
                </div>
                <div class="deal-content">
                    <h3 class="deal-product-title">Xiaomi 13 Ultra 5G</h3>
                    <div class="deal-rating">
                        <span class="stars">⭐ 4.8</span>
                        <span class="reviews">(234 đánh giá)</span>
                    </div>
                    <p class="deal-description">
                        Camera Leica 50MP, chip Snapdragon 8 Gen 2, RAM 12GB,
                        bộ nhớ 256GB. Trải nghiệm nhiếp ảnh chuyên nghiệp.
                    </p>
                    <div class="deal-pricing">
                        <span class="deal-price">12,990,000₫</span>
                        <span class="deal-original">18,990,000₫</span>
                        <div class="savings">Tiết kiệm 6,000,000₫ (32% OFF)</div>
                    </div>
                    <div class="countdown">
                        <div class="countdown-label">⏰ Thời gian còn lại:</div>
                        <div class="countdown-timer">
                            <div class="time-unit">
                                <span id="hours">12</span>
                                <label>Giờ</label>
                            </div>
                            <div class="time-unit">
                                <span id="minutes">34</span>
                                <label>Phút</label>
                            </div>
                            <div class="time-unit">
                                <span id="seconds">56</span>
                                <label>Giây</label>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-deal">🛒 Mua ngay - Flash Sale</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Chatbot Bubble -->
    <div class="chatbot-container">
        <div id="chatbot-button">💬</div>

        <div id="chatbot-window">
            <div class="chatbot-header">
                <span>Chatbot hỗ trợ</span>
                <button id="chatbot-close">&times;</button>
            </div>
            <div class="chatbot-body">
                <div class="bot-message">Xin chào 👋! Tôi có thể giúp gì cho bạn?</div>
            </div>
            <div class="chatbot-footer">
                <input type="text" id="chatbot-input" placeholder="Nhập tin nhắn..." />
                <button id="chatbot-send">Gửi</button>
            </div>
        </div>
    </div>




    <script src="{{ asset('js/index-chatbot.js') }}"></script>
    <script src="{{ asset('js/index-filter.js') }}"></script>
    <script src="{{ asset('js/index.js') }}"></script>
@endsection