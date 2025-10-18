@extends('layouts.layouts')

@section('title', 'TechStore - Trang chủ')

@section('content')
    <!-- Hero Section -->

    <section class="hero">
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
                <div class="hero-image">
                    <img src="https://www.apple.com/v/iphone-17-pro/a/images/overview/contrast/iphone_17_pro__dwccrdina7qu_large.jpg" alt="Sony Xperia Pro" class="phone-image">
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->

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
    <section class="products">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Sản phẩm nổi bật</h2>
                <p class="section-subtitle">Những sản phẩm được yêu thích nhất</p>
            </div>
            <div class="products-grid">
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://www.apple.com/v/iphone-17-pro/a/images/overview/contrast/iphone_17_pro__dwccrdina7qu_large.jpg" alt="iPhone 15 Pro">
                        <div class="product-badge">Bán chạy</div>
                        <div class="product-discount">-13%</div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">iPhone 15 Pro</h3>
                        <div class="product-rating">
                            <span class="stars">⭐ 4.9</span>
                            <span class="reviews">(156 đánh giá)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">25,990,000₫</span>
                            <span class="original-price">29,990,000₫</span>
                        </div>
                        <button class="btn btn-primary full-width">🛒 Thêm vào giỏ</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image">
                        <img src="https://www.apple.com/v/iphone-17-pro/a/images/overview/contrast/iphone_17_pro__dwccrdina7qu_large.jpg" alt="Xiaomi 13 Ultra">
                        <div class="product-badge new">Mới</div>
                        <div class="product-discount">-16%</div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">Xiaomi 13 Ultra</h3>
                        <div class="product-rating">
                            <span class="stars">⭐ 4.7</span>
                            <span class="reviews">(89 đánh giá)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">15,990,000₫</span>
                            <span class="original-price">18,990,000₫</span>
                        </div>
                        <button class="btn btn-primary full-width">🛒 Thêm vào giỏ</button>
                    </div>
                </div>

                <div class="product-card">
                    <div class="product-image">
                        <img src="https://www.apple.com/v/iphone-17-pro/a/images/overview/contrast/iphone_17_pro__dwccrdina7qu_large.jpg" alt="ROG Gaming Laptop">
                        <div class="product-badge sale">Giảm sốc</div>
                        <div class="product-discount">-13%</div>
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">ROG Gaming Laptop</h3>
                        <div class="product-rating">
                            <span class="stars">⭐ 4.8</span>
                            <span class="reviews">(67 đánh giá)</span>
                        </div>
                        <div class="product-price">
                            <span class="current-price">39,990,000₫</span>
                            <span class="original-price">45,990,000₫</span>
                        </div>
                        <button class="btn btn-primary full-width">🛒 Thêm vào giỏ</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Deal of the Day -->
    <section class="deal-section">
        <div class="container">
            <div class="deal-header">
                <h2 class="deal-title">⚡ Deal of the Day</h2>
                <p class="deal-subtitle">Ưu đãi có thời hạn - Nhanh tay kẻo lỡ!</p>
            </div>
            <div class="deal-card">
                <div class="deal-image">
                    <img src="https://www.apple.com/v/iphone-17-pro/a/images/overview/contrast/iphone_17_pro__dwccrdina7qu_large.jpg" alt="Xiaomi Deal">
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

@endsection
