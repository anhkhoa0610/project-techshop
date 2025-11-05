@extends('layouts.layouts')

@section('title', 'TechStore - Trang chủ')

@section('content')
    <div id="loading-overlay">
        <div class="spinner"></div>
    </div>

    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-filter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-chatbot.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('css/swiper.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index-categories.css') }}">


    <section class="hero">
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
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <div class="background-overlay">
        <section class="categories">
            <div class="container-fluid">
                <div class="section-header">
                    <h2 class="section-title">
                        <span>D</span>
                        <span>a</span>
                        <span>n</span>
                        <span>h&nbsp;</span>
                        <span>M</span>
                        <span>ụ</span>
                        <span>c&nbsp;</span>
                        <span>n</span>
                        <span>ổ</span>
                        <span>i&nbsp;</span>
                        <span>b</span>
                        <span>ậ</span>
                        <span>t</span>
                    </h2>
                    <p class="section-subtitle">Khám phá các sản phẩm hàng đầu</p>
                </div>
                <div class="categories-grid glass3d">
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
        <section id="section-all-products" class="products categories-products">
            <div class="container-fluid">
                <div class="section-header">
                    <h2 class="section-title">
                        <span>T</span>
                        <span>ấ</span>
                        <span>t&nbsp;</span>
                        <span>c</span>
                        <span>ả&nbsp;</span>
                        <span>s</span>
                        <span>ả</span>
                        <span>n&nbsp;</span>
                        <span>p</span>
                        <span>h</span>
                        <span>ẩ</span>
                        <span>m</span>
                    </h2>
                    <p class="section-subtitle">Khám phá sản phẩm theo lựa chọn của bạn</p>
                </div>

                <div class="sidebar glass3d" id="sidebar">
                    <div class="sidebar-header">
                        <span class="sidebar-title">Lọc sản phẩm</span>
                    </div>
                    <form id="filterForm" class="mt-4">
                        <div class="row">

                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Giá tiền (VNĐ)</label>

                                <div class="price-inputs d-flex align-items-center gap-2">
                                    <input type="text" class="form-control number-input" id="min-price-display" readonly
                                        placeholder="Từ">
                                    <span class="fw-bold" style="color: #ccc;">–</span>
                                    <input type="text" class="form-control number-input" id="max-price-display" readonly
                                        placeholder="Đến">
                                </div>

                                <div id="price-slider"></div>

                                <input type="hidden" name="price_min">
                                <input type="hidden" name="price_max">
                            </div>
                            <div class="col-md-3">
                                <label for="category" class="form-label fw-semibold">Danh mục</label>
                                <select class="form-select" id="category" name="category_filter">
                                    <option value="">Tất cả</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->category_id }}" {{ ($currentCategory && $currentCategory->id == $category->id) ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>

                                <label for="supplier" class="form-label fw-semibold mt-3">Nhà phân phối</label>
                                <select class="form-select" id="supplier" name="supplier_filter">
                                    <option value="">Tất cả</option>
                                    <option value="1">Apple</option>
                                    <option value="2">Samsung</option>
                                    <option value="3">ASUS</option>
                                    <option value="4">Dell</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="rating" class="form-label fw-semibold">Đánh giá</label>
                                <select class="form-select" id="rating" name="rating_filter">
                                    <option value="">Tất cả</option>
                                    <option value="5">⭐️⭐️⭐️⭐️⭐️</option>
                                    <option value="4">⭐️⭐️⭐️⭐️</option>
                                    <option value="3">⭐️⭐️⭐️</option>
                                    <option value="2">⭐️⭐️</option>
                                    <option value="1">⭐️</option>
                                </select>

                                <label for="stock_status" class="form-label fw-semibold mt-3">Tình trạng hàng</label>
                                <select class="form-select" id="stock_status" name="stock_filter">
                                    <option value="">Tất cả</option>
                                    <option value="1">Còn hàng</option>
                                    <option value="2">Hết hàng</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="release_date" class="form-label fw-semibold">Thời gian ra mắt</label>
                                <select class="form-select" id="release_date" name="release_filter">
                                    <option value="">Tất cả</option>
                                    <option value="30">30 ngày qua</option>
                                    <option value="90">90 ngày qua</option>
                                    <option value="180">6 tháng qua</option>
                                    <option value="365">1 năm qua</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Áp dụng bộ lọc</button>
                        <button type="button" class="btn-filter-reset btn btn-primary w-100 ms-2">Đặt lại bộ lọc</button>
                    </form>
                </div>
                <div class="products-grid show-by-category glass3d">
                    @foreach ($allProducts as $product)
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
                                    @php
                                        $rating = round($product->reviews_avg_rating ?? 0, 1);
                                        $count = $product->reviews_count ?? 0;
                                    @endphp
                                    <span class="stars" style="color: #ffc107;">⭐</span>
                                    <span class="rating-score">{{ $rating }}</span>
                                    <span class="reviews">({{ $count }} đánh giá)</span>
                                </div>
                                <div class="product-price">
                                    <span class="current-price"><?= number_format($product->price, 0, ',', '.'); ?>₫</span>
                                    <span
                                        class="original-price"><?= number_format($product->original_price, 0, ',', '.'); ?>₫</span>
                                </div>

                                <div class="product-meta">
                                    <div class="volume-sold">
                                        📅 <strong>Đã bán: </strong>{{ $product->volume_sold }} sản phẩm
                                    </div>
                                    <div class="release-date">
                                        📅 <strong>Phát hành: </strong>{{ $product->release_date }}
                                    </div>
                                    <div class="stock-info">
                                        📦 <strong>Còn lại:</strong>
                                        @if ($product->stock_quantity > 0)
                                            {{ $product->stock_quantity }} sản phẩm
                                        @else
                                            <span style="color:red;">Hết hàng</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button data-product-id="{{ $product->product_id }}" data-quantity="1"
                                class="btn-add-cart btn btn-primary full-width">Thêm vào giỏ 🛒 </button>
                        </div>

                    @endforeach
                </div>
                <div id="load-more-container" class="text-center my-4">
                    @if ($allProducts->hasMorePages())
                        @php
                            $remaining = $allProducts->total() - $allProducts->count();
                            $nextBatch = min($allProducts->perPage(), $remaining);
                        @endphp
                        <button id="btn-load-more" class="btn btn-outline-light btn-lg">
                            Xem thêm {{ $nextBatch }} / {{ $remaining }} sản phẩm
                        </button>
                    @endif
                </div>
            </div>
        </section>
        <!-- Video Review -->
    </div>




    <!-- Chatbot Bubble -->
    <div class="chatbot-container">
        <div id="chatbot-button">💬</div>

        <div id="chatbot-window">
            <div class="chatbot-header">
                <div class="chat-avatar">F</div>
                <div class="chat-info">
                    <strong>Chatbot hỗ trợ</strong>
                    <span>October 15, 2024</span>
                </div>
                <button class="chat-close" id="chatbot-close">&times;</button>
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

    <script>
        const USER_ID = {{ auth()->id() ?? 'null' }};
        console.log("User ID:", USER_ID);
    </script>
    <script src="{{ asset('js/index-chatbot.js') }}"></script>
    <script src="{{ asset('js/categories-filter.js') }}"></script>
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/swiper.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wnumb/1.2.0/wNumb.min.js"></script>
@endsection