@extends('layouts.layouts')

@section('title', 'TechStore - Trang chủ')

@section('content')

<link rel="stylesheet" href="{{ asset('css/index.css') }}">
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
            <div class="hero-text" style="margin-top: 15vh; font-family: 'Doris'">
                <span class="hero-badge">🔥 Hàng mới</span>
                <h1 class="hero-title">
                    iPhone 17
                    <span class="hero-subtitle">Pro Series</span>
                </h1>
                <p class="hero-description">
                    Trải nghiệm sự đột phá vượt mọi giới hạn với chip A18 Bionic mạnh mẽ nhất, hệ thống camera ProRAW
                    50MP đỉnh cao và màn hình ProMotion XDR siêu mượt.
                </p>
                <div class="hero-specs">
                    <div class="spec-item">
                        <div class="spec-value">50MP</div>
                        <div class="spec-label">Camera chính</div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-value">1TB</div>
                        <div class="spec-label">Bộ nhớ</div>
                    </div>
                    <div class="spec-item">
                        <div class="spec-value">Wi-Fi 7</div>
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
                @foreach ($categories as $category)
                <a class="category-card" href="{{ route('index.categories', $category->category_id) }}">
                    <div class="category-image" style="background-image: url('/uploads/{{ $category->cover_image }}');">
                    </div>
                    <h3 class="category-title">{{ $category->category_name }}</h3>
                    <p class="category-subtitle">{{ $category->description }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="sale-products" style="margin-top: 100px;">
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
                                        @php
                                        $activeDiscount = $product->discounts->first(function($discount) {
                                        $now = now();
                                        $startValid = is_null($discount->start_date) || $discount->start_date <= $now;
                                            $endValid=is_null($discount->end_date) || $discount->end_date >= $now;
                                            return $startValid && $endValid;
                                            });
                                            @endphp
                                            @if($activeDiscount)
                                            <div class="product-discount sale-badge">SALE -{{ number_format($activeDiscount->discount_percent, 0) }}%</div>
                                            @else
                                            <div class="product-discount">Trả góp 0%</div>
                                            @endif
                                    </div>
                                    <a class="product-info" href="{{ route('product.details', $product->product_id) }}">
                                        <h3 class="product-name"><?= $product->product_name; ?></h3>

                                        @php
                                        $specsMap = $product->specs->pluck('value', 'name');
                                        $coreSpecsData = [
                                        'CPU' => $specsMap->first(fn($v, $k) => Str::contains(strtolower($k), ['cpu', 'chip', 'vi xử lý'])),
                                        'RAM' => $specsMap->first(fn($v, $k) => Str::contains(strtolower($k), 'ram')),
                                        'GPU' => $specsMap->first(fn($v, $k) => Str::contains(strtolower($k), ['gpu', 'đồ họa', 'vga'])),
                                        'Storage' => $specsMap->first(fn($v, $k) => Str::contains(strtolower($k), ['dung lượng', 'storage', 'ssd', 'hdd'])),
                                        ];
                                        $specIconFiles = [
                                        'CPU' => asset('images/icons/cpu.svg'),
                                        'RAM' => asset('images/icons/ram.svg'),
                                        'GPU' => asset('images/icons/gpu.svg'),
                                        'Storage' => asset('images/icons/storage.svg'),
                                        ];
                                        @endphp

                                        <div class="specs-grid-container">
                                            @foreach ($coreSpecsData as $name => $value)

                                            @if ($value)
                                            <div class="spec-grid-item">
                                                <img src="{{ $specIconFiles[$name] }}" alt="{{ $name }} icon"
                                                    class="spec-grid-icon">

                                                <div class="spec-grid-text">
                                                    <span class="spec-grid-name">{{ $name }}</span>
                                                    <strong class="spec-grid-value">{{ $value }}</strong>
                                                </div>
                                            </div>
                                            @endif

                                            @endforeach
                                        </div>

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
                                            @if($activeDiscount)
                                            <span class="sale-price"><?= number_format($activeDiscount->sale_price, 0, ',', '.'); ?>₫</span>
                                            <span class="original-price"><?= number_format($product->price, 0, ',', '.'); ?>₫</span>
                                            @else
                                            <span class="current-price"><?= number_format($product->price, 0, ',', '.'); ?>₫</span>
                                            @endif
                                        </div>

                                        <div class="product-meta">
                                            <div class="volume-sold">
                                                📅 <strong>Đã bán: </strong>{{ $product->volume_sold }} sản phẩm
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
                                    </a>
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
    <section class="new-products" style="margin-top: 100px;">
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
                                        <div class="product-badge">Hàng mới</div>
                                        @php
                                        $activeDiscount = $product->discounts->first(function($discount) {
                                        $now = now();
                                        $startValid = is_null($discount->start_date) || $discount->start_date <= $now;
                                            $endValid=is_null($discount->end_date) || $discount->end_date >= $now;
                                            return $startValid && $endValid;
                                            });
                                            @endphp
                                            @if($activeDiscount)
                                            <div class="product-discount sale-badge">SALE -{{ number_format($activeDiscount->discount_percent, 0) }}%</div>
                                            @else
                                            <div class="product-discount">Trả góp 0%</div>
                                            @endif
                                    </div>
                                    <a class="product-info" href="{{ route('product.details', $product->product_id) }}">
                                        <h3 class="product-name"><?= $product->product_name; ?></h3>

                                        @php
                                        $specsMap = $product->specs->pluck('value', 'name');
                                        $coreSpecsData = [
                                        'CPU' => $specsMap->first(fn($v, $k) => Str::contains(strtolower($k), ['cpu', 'chip', 'vi xử lý'])),
                                        'RAM' => $specsMap->first(fn($v, $k) => Str::contains(strtolower($k), 'ram')),
                                        'GPU' => $specsMap->first(fn($v, $k) => Str::contains(strtolower($k), ['gpu', 'đồ họa', 'vga'])),
                                        'Storage' => $specsMap->first(fn($v, $k) => Str::contains(strtolower($k), ['dung lượng', 'storage', 'ssd', 'hdd'])),
                                        ];
                                        $specIconFiles = [
                                        'CPU' => asset('images/icons/cpu.svg'),
                                        'RAM' => asset('images/icons/ram.svg'),
                                        'GPU' => asset('images/icons/gpu.svg'),
                                        'Storage' => asset('images/icons/storage.svg'),
                                        ];
                                        @endphp

                                        <div class="specs-grid-container">
                                            @foreach ($coreSpecsData as $name => $value)

                                            @if ($value)
                                            <div class="spec-grid-item">
                                                <img src="{{ $specIconFiles[$name] }}" alt="{{ $name }} icon"
                                                    class="spec-grid-icon">

                                                <div class="spec-grid-text">
                                                    <span class="spec-grid-name">{{ $name }}</span>
                                                    <strong class="spec-grid-value">{{ $value }}</strong>
                                                </div>
                                            </div>
                                            @endif

                                            @endforeach
                                        </div>

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
                                            @if($activeDiscount)
                                            <span class="sale-price"><?= number_format($activeDiscount->sale_price, 0, ',', '.'); ?>₫</span>
                                            <span class="original-price"><?= number_format($product->price, 0, ',', '.'); ?>₫</span>
                                            @else
                                            <span class="current-price"><?= number_format($product->price, 0, ',', '.'); ?>₫</span>
                                            @endif
                                        </div>

                                        <div class="product-meta">
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
                                    </a>
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

    <section class="news" style="margin-top: 100px;">
        <div class="container-fluid">
            <div class="section-header">
                <h2 class="section-title">
                    <span>T</span>
                    <span>i</span>
                    <span>n&nbsp;</span>
                    <span>T</span>
                    <span>ứ</span>
                    <span>c</span>
                </h2>
                <p class="section-subtitle">Thông tin công nghệ mới nhất</p>
            </div>
            <div class="news-list glass3d">
                @foreach ($posts as $post)
                <article class="news-item">
                    <div class="news-content">
                        <h2>
                            <a href="{{ route('posts.show', $post->id) }}">
                                {{ $post->title }}
                            </a>
                        </h2>
                        <p>{{ $post->description }}</p>
                        <span class="arrow">
                            <svg fill="currentColor" viewBox="0 0 23 24" xmlns="http://www.w3.org/2000/svg" class="w-5">
                                <path clip-rule="evenodd"
                                    d="M0 3.2V0.5H18H20.5H23V3V5.5V23.5H20.3L20.5 5.5191H19.8881L1.96094 23.4462L0.0308727 21.5514L17.9578 3.62444V3.2H0Z"
                                    fill-rule="evenodd"></path>
                            </svg>
                        </span>
                        <small class="date">Cập nhật lúc: {{ $post->updated_at->format('d/m/Y H:i') }}</small>
                    </div>
                    @if ($post->cover_image)
                    <img src="{{ $post->cover_image }}" alt="{{ $post->title }}"
                        style="max-width: 300px; height: auto;">
                    @endif
                </article>
                @endforeach
                <div class="">

                </div>
                <div class="see-more-container">
                    <a href="{{ route('posts.index') }}" class="btn-see-more">
                        Xem tất cả tin tức >
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Review -->
    <section class="review-video" style="margin-top: 100px;">
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

    <section class="review-video" style="margin-top: 100px;">
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
                                                @if ($i <=$review->rating)
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
<script src='https://cdn.jotfor.ms/agent/embedjs/019abf67745377df8d515ddced74eb40a02a/embed.js'>
</script>


<script>
    const USER_ID = {{auth() -> id() ?? 'null'}};
    const cartCountFromController = {{$cartItemCount ?? 0}};

    function updateCartCount() {
        if (typeof cartCountFromController === 'number' && cartCountFromController >= 0) {
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = cartCountFromController;
            }
        }
    }

    document.addEventListener('DOMContentLoaded', updateCartCount);
</script>
<script src="{{ asset('js/index-chatbot.js') }}"></script>
<script src="{{ asset('js/index.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="{{ asset('js/swiper.js') }}"></script>
@endsection