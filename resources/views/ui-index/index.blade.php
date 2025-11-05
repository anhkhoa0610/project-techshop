@extends('layouts.layouts')

@section('title', 'TechStore - Trang chủ')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-filter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-chatbot.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('css/swiper.css') }}">

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
        <section class="sale-products">
            <div class="container-fluid">
                <div class="section-header">
                    <h2 class="section-title">
                        <span>S</span>
                        <span>ả</span>
                        <span>n&nbsp;</span>
                        <span>p</span>
                        <span>h</span>
                        <span>ẩ</span>
                        <span>m&nbsp;</span>
                        <span>n</span>
                        <span>ổ</span>
                        <span>i&nbsp;</span>
                        <span>b</span>
                        <span>ậ</span>
                        <span>t</span>
                    </h2>
                    <p class="section-subtitle">Những sản phẩm được bán chạy nhất</p>
                </div>
                <div class="slider-container glass3d">
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($topProducts as $product): ?>
                            <div class="swiper-slide">
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
                                            <span
                                                class="current-price"><?= number_format($product->price, 0, ',', '.'); ?>₫</span>
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
                            </div>

                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Featured Products -->
        <section class="new-products">
            <div class="container-fluid">
                <div class="section-header">
                    <h2 class="section-title">
                        <span>S</span>
                        <span>ả</span>
                        <span>n&nbsp;</span>
                        <span>p</span>
                        <span>h</span>
                        <span>ẩ</span>
                        <span>m&nbsp;</span>
                        <span>m</span>
                        <span>ớ</span>
                        <span>i&nbsp;</span>
                        <span>n</span>
                        <span>h</span>
                        <span>ấ</span>
                        <span>t</span>
                    </h2>
                    <p class="section-subtitle">Những sản phẩm mới được phát hành</p>
                </div>
                <div class="slider-container glass3d">
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            <?php foreach ($newProducts as $product): ?>
                            <div class="swiper-slide">
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
                                            <span
                                                class="current-price"><?= number_format($product->price, 0, ',', '.'); ?>₫</span>
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
                            </div>

                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Video Review -->
        <section class="review-video">
            <div class="container-fluid">
                <div class="section-header">
                    <h2 class="section-title">
                        <span>C</span>
                        <span>l</span>
                        <span>i</span>
                        <span>p&nbsp;</span>
                        <span>R</span>
                        <span>e</span>
                        <span>v</span>
                        <span>i</span>
                        <span>e</span>
                        <span>w</span>
                    </h2>
                    <p class="section-subtitle">Review về sản phẩm</p>
                </div>
                <div class="slider-container glass3d">
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            @foreach ($videoProducts as $product)
                                <div class="swiper-slide">
                                    <div class="video-card">
                                        <div class="video-thumb" onclick="playVideo(this)">
                                            <iframe
                                                src="{{ $product->embed_url_review }}?mute=1&playsinline=1&rel=0&modestbranding=1"
                                                title="Video sản phẩm" frameborder="0"
                                                allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen>
                                            </iframe>
                                            <div class="overlay">
                                                <div class="channel-info">
                                                    <img src="{{ asset('/images/logo.jpg') }}" alt="Channel"
                                                        class="channel-logo">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="product-info">
                                            <img src="/uploads/{{ $product->cover_image }}" alt="Sản phẩm"
                                                class="product-thumb">
                                            <div class="product-name">{{ $product->product_name }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Review -->

        <section class="review-video">
            <div class="container-fluid">
                <div class="section-header">
                    <h2 class="section-title">
                        <span>C</span>
                        <span>o</span>
                        <span>m</span>
                        <span>m</span>
                        <span>e</span>
                        <span>n</span>
                        <span>t</span>
                        <span>s</span>
                    </h2>
                    <p class="section-subtitle">Bình luận về sản phẩm</p>
                </div>
                <div class="slider-container glass3d">
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            @foreach ($reviews as $review)
                                <div class="swiper-slide">
                                    <div class="testimonial-card">
                                        <div class="quote-icon">“</div>

                                        <p class="testimonial-text">
                                            {{ $review->comment }}
                                        </p>

                                        <div class="author-info">
                                            <img src="/images/messi.jpg" class="author-avatar">
                                            <div class="author-details">
                                                <div class="author-name">{{ $review->user->full_name }}</div>
                                                <span class="author-title">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        @if ($i <= $review->rating)
                                                            <i class="fa fa-star" style="color: #FFD700;"></i>
                                                        @elseif ($i - 0.5 <= $review->rating)
                                                            <i class="fa fa-star-half-o" style="color: #FFD700;"></i>
                                                        @else
                                                            <i class="fa fa-star-o" style="color: #FFD700;"></i>
                                                        @endif
                                                    @endfor
                                                    <span><br>cho sản phẩm
                                                        {{ $review->product->product_name }}</span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </section>
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
    <script src="{{ asset('js/index-filter.js') }}"></script>
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/swiper.js') }}"></script>
@endsection