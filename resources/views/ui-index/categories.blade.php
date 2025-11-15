@extends('layouts.layouts')

@section('title', 'TechStore - Trang chủ')

@section('content')
    <?php
    $categoryId = $currentCategory->category_id ?? null;

    $specificVideoPath = 'videos/banner-' . $categoryId . '.mp4';
    $defaultVideoPath = 'videos/banner.mp4';

    if ($categoryId && File::exists(public_path($specificVideoPath))) {
        $videoSource = $specificVideoPath;
    } else {
        $videoSource = $defaultVideoPath;
    }
        ?>
    <div id="loading-overlay">
        <div class="logo"></div>
        <div class="spinner"></div>
    </div>
    <link rel="stylesheet" href="{{ asset('css/index-chatbot.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('css/swiper.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index-categories.css') }}">
    <section class="hero">
        <div class="hero-image">
            <video class="hero-video" autoplay muted loop playsinline preload="metadata"
                poster="{{ asset('images/place-holder.jpg') }}">
                <source src="{{ asset($videoSource) }}" type="video/mp4">
                <img src="{{ asset('images/place-holder.jpg') }}" alt="Banner">
            </video>
        </div>
        @if($currentCategory)
        <div class="container">
            <div class="hero-content">
                <div class="hero-text glass3d" style="margin-top: 15vh; font-family: 'Doris'">
                    <h1 class="hero-title">
                        Category
                        <span class="hero-subtitle">{{ $currentCategory->category_name }}</span>
                    </h1>
                    <p class="hero-description">
                        {{ $currentCategory->description }}
                    </p>
                </div>
            </div>
        </div>
        @endif
    </section>

    <!-- Categories Section -->
    <div class="background-overlay">
        <div class="breadcrumb-container" style="margin-left: 5vw">
            <x-breadcrumb :items="[
            ['title' => $currentCategory?->category_name ?? 'Toàn bộ sản phẩm']
        ]" />
        </div>
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
                                        <option value="{{ $category->category_id }}" {{ ($currentCategory && $currentCategory->category_id == $category->category_id) ? 'selected' : '' }}>
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
                                        </div>
                                        <a class="product-info" href="{{ route('product.details', $product->product_id) }}">
                                            <h3 class="product-name" title="{{ $product->product_name }}"><?= $product->product_name; ?>
                                            </h3>

                                            <?php
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
                                                                                                                            ?>

                                            <div class="specs-grid-container">
                                                @foreach ($coreSpecsData as $name => $value)

                                                    @if ($value)
                                                        <div class="spec-grid-item">
                                                            <img src="{{ $specIconFiles[$name] }}" alt="{{ $name }} icon" class="spec-grid-icon">

                                                            <div class="spec-grid-text">
                                                                <span class="spec-grid-name">{{ $name }}</span>
                                                                <strong class="spec-grid-value">{{ $value }}</strong>
                                                            </div>
                                                        </div>
                                                    @endif

                                                @endforeach
                                            </div>

                                            <div class="product-rating">
                                                <?php
                        $rating = round($product->reviews_avg_rating ?? 0, 1);
                        $count = $product->reviews_count ?? 0;
                                                                                                                                ?>
                                                <span class="stars" style="color: #ffc107;">⭐</span>
                                                <span class="rating-score">{{ $rating }}</span>
                                                <span class="reviews">({{ $count }} đánh giá)</span>
                                            </div>
                                            <div class="product-price">
                                                <span class="current-price"><?= number_format($product->price, 0, ',', '.'); ?>₫</span>
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

                    @endforeach
                </div>
                <div id="load-more-container" class="text-center my-4">
                    @if ($allProducts->hasMorePages())
                                    <?php
                        $remaining = $allProducts->total() - $allProducts->count();
                        $nextBatch = min($allProducts->perPage(), $remaining);
                                                                                                        ?>
                                    <button id="btn-load-more" class="btn btn-lg glass3d">
                                        Xem thêm {{ $nextBatch }} / {{ $remaining }} sản phẩm
                                    </button>
                    @endif
                </div>
            </div>
        </section>


        <section class="faq">
            <div class="container-fluid">
                <div class="faq-news-grid">
                    <div class="faq-column">
                        <h2 class="section-title">
                            <span>C</span>
                            <span>â</span>
                            <span>u&nbsp;</span>
                            <span>h</span>
                            <span>ỏ</span>
                            <span>i&nbsp;</span>
                            <span>t</span>
                            <span>h</span>
                            <span>ư</span>
                            <span>ờ</span>
                            <span>n</span>
                            <span>g&nbsp;</span>
                            <span>g</span>
                            <span>ặ</span>
                            <span>p</span>
                        </h2>
                        <div class="accordion glass3d">
                            <details class="accordion-item">
                                <summary class="accordion-header">
                                    Techshop có thu cũ đổi mới khi mua tablet không?
                                </summary>
                                <div class="accordion-content">
                                    <p>Tại Techshop có chương trình thu cũ đổi mới tablet với giá cực tốt - <strong>trợ
                                            giá
                                            lên đến 1.000.000 đồng</strong>. Như vậy khách hàng chỉ cần chi trả cho phần
                                        chênh
                                        lệch bù vào thay vì toàn bộ giá trị ban đầu của sản phẩm.</p>
                                </div>
                            </details>

                            <details class="accordion-item">
                                <summary class="accordion-header">
                                    Mua tablet tại Techshop có được trả góp 0% không?
                                </summary>
                                <div class="accordion-content">
                                    <p>CÓ! Cụ thể, khi mua tablet tại Techshop, quý khách hàng sẽ được hỗ trợ hướng dẫn
                                        <strong>trả góp 0% lãi suất</strong> nhanh chóng, đơn giản. Cùng với đó là thời gian
                                        trả
                                        góp linh động từ 4-6-8-10-12 tháng.
                                    </p>
                                </div>
                            </details>

                            <details class="accordion-item">
                                <summary class="accordion-header">
                                    Máy tính bảng mua tại Techshop được bảo hành như thế nào?
                                </summary>
                                <div class="accordion-content">
                                    <p>Sản phẩm máy tính bảng mua tại hệ thống Techshop sẽ được đảm bảo chính sách
                                        <strong>bảo
                                            hành chính hãng</strong>. Cụ thể:
                                    </p>
                                    <ul>
                                        <li>1 đổi 1 trong vòng 30 ngày nếu xuất hiện lỗi phần cứng do nhà sản xuất.</li>
                                        <li>Bảo hành chính hãng 12 tháng tại các trung tâm bảo hành.</li>
                                    </ul>
                                </div>
                            </details>

                            <details class="accordion-item">
                                <summary class="accordion-header">
                                    Tôi có thể thanh toán ở Techshop bằng những hình thức nào?
                                </summary>
                                <div class="accordion-content">
                                    <p>Tại Techshop, bạn có thể thanh toán bằng nhiều hình thức khác nhau như:
                                    <ul>
                                        <li>VNPAY</li>
                                        <li>Momo</li>
                                    </ul>
                                    </p>
                                </div>
                            </details>
                        </div>
                    </div>

                    <div class="news-column glass3d">
                        <div class="news-header">
                            <h2 class="section-title">
                                <span>T</span>
                                <span>i</span>
                                <span>n&nbsp;</span>
                                <span>t</span>
                                <span>ứ</span>
                                <span>c&nbsp;</span>
                                <span>c</span>
                                <span>ô</span>
                                <span>n</span>
                                <span>g&nbsp;</span>
                                <span>n</span>
                                <span>g</span>
                                <span>h</span>
                                <span>ệ</span>
                            </h2>
                            <a href="{{ route('posts.index') }}" class="see-all-link">Xem tất cả ></a>
                        </div>

                        <div class="news-list-sidebar">
                            @foreach($posts as $post)
                                <article class="news-item-sidebar">
                                    <a href="{{ route('posts.show', $post->id) }}">
                                        <img src="{{ $post->cover_image }}" alt="">
                                        <div>
                                            <h3>{{ $post->title }}</h3>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>




    <!-- Chatbot Bubble -->
    @include('ui-index.chatbot'); 

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