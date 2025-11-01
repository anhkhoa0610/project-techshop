@extends('layouts.layouts')

@section('title', 'Trang thông tin sản phẩm')

@section('content')
    @php
        use Carbon\Carbon;
    @endphp

    <link rel="stylesheet" href="{{ asset('css/product-details.css') }}">


    <div class="content-container w-100">
        <div class="header-back-ground w-100"></div>
        <div class="container-details container">
            <div class="row">
                <!-- Cột trái: Hình ảnh sản phẩm -->
                <div class="col-md-6">
                    <div class="product-images text-center">
                        <img src="{{isset($product->cover_image) ? asset('uploads/' . $product->cover_image) : asset('images/blank_product.png') }}"
                            class="prodcut-image" alt="Ảnh sản phẩm chính" id="mainImage">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                @if(isset($product->images) && $product->images->count() > 0)
                                    @foreach($product->images as $image)
                                        <div class="swiper-slide">
                                            <img src="{{ asset('uploads/' . $image->image_name) }}" alt="Ảnh phụ"
                                                class="swiper-slide-img">
                                        </div>
                                    @endforeach

                                @endif

                            </div>

                            <!-- Nút điều hướng -->
                            <div class="swiper-button-prev"><img src="{{ asset('images/less.png') }}" alt=""></div>
                            <div class="swiper-button-next"><img src="{{ asset('images/greater.png') }}" alt=""></div>
                        </div>
                    </div>
                </div>

                <!-- Cột phải: Thông tin sản phẩm -->
                <div class="col-md-6">
                    <h3 class="fw-bold text-center">{{ $product->product_name ?? "Sản phẩm không tồn tại!!!"}}</h3>
                    <p class="text-warning mb-1 fs-3 text-center">
                        ⭐ {{ number_format($avg, 1) ?? 0 }} | {{ $reviews_count ?? 0 }} đánh giá | Đã bán
                        {{ $product->volume_sold ?? 0 }}
                    </p>

                    <h3 class=" fw-bold">
                        <strong>Đơn giá: </strong>
                        {{ isset($product->price) ? number_format($product->price, 0, ',', '.') : 0}}đ
                    </h3>

                    <p class="mt-3"><strong>Nhà phân phối: </strong>
                        {{ isset($product->supplier->name) ? $product->supplier->name : "Không có nhà phân phối"}}</p>

                    <p class="mt-3"><strong>Bảo hành: </strong>
                        {{ isset($product->warranty_period) ? $product->warranty_period . ' tháng' : 'Không bảo hành' }}</p>
                    <p class="mt-3"><strong>Danh mục: </strong>
                        {{ isset($product->category) ? $product->category->category_name : 'Không có danh mục' }}</p>


                    <div class="mt-4 d-flex align-items-center">
                        <strong class="me-2">Số lượng:</strong>
                        <img class="quantity-button minus me-1" src="{{ asset('images/minus.png') }}" alt="">
                        <input type="number" class="input-quantity form-control text-center"
                            max="{{ isset($product->stock_quantity) ? $product->stock_quantity : 0 }}"
                            value="{{ isset($product->stock_quantity) ? 1 : 0 }}">
                        <img class="quantity-button plus ms-1" src="{{ asset('images/plus.png') }}" alt="">
                        <p class="mt-3 ms-3">
                            {{ isset($product->stock_quantity) ? $product->stock_quantity . ' sản phẩm có sẵn' : 'Hết hàng' }}
                        </p>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-danger me-2">Mua ngay</button>
                        <button class="btn btn-outline-danger">Thêm vào giỏ hàng</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="desc-product container">
            <h2>Mô tả sản phẩm</h2>
            <p>{{ isset($product->description) ? $product->description : "Sản phẩm không có mô tả!!"}}</p>
        </div>

        <div class="review-product container">
            <h2>Đánh giá sản phẩm</h2>
            <div class="review-title">
                <div class="col-md-3 star-rating">
                    <div class="rating">
                        <span class="rating-left">{{ number_format($avg, 1) ?? 0 }} </span>
                        <span class="rating-right"> trên 5 sao</span>
                    </div>
                    <div class="star-rating-display">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $avg)
                                <span class="star filled text-warning fs-1">★</span>
                            @else
                                <span class="star text-warning fs-1">☆</span>
                            @endif
                        @endfor
                    </div>
                </div>
                <div class="col-md-9 filter-by-star">
                    <div class="groub-button-filter">
                        <button class="button-filter-star active" data-rating="">Tất cả</button>
                        <p>Bình luận: ({{ $reviewSummary['all'] ?? 0 }})</p>
                    </div>
                    <div class="groub-button-filter">
                        <button class="button-filter-star" data-rating="1">1 sao</button>
                        <p>Bình luận: ({{ $reviewSummary['1'] ?? 0 }})</p>
                    </div>
                    <div class="groub-button-filter">
                        <button class="button-filter-star " data-rating="2">2 sao</button>
                        <p>Bình luận: ({{ $reviewSummary['2'] ?? 0 }})</p>
                    </div>
                    <div class="groub-button-filter">
                        <button class="button-filter-star " data-rating="3">3 sao</button>
                        <p>Bình luận: ({{ $reviewSummary['3'] ?? 0 }})</p>
                    </div>
                    <div class="groub-button-filter">
                        <button class="button-filter-star " data-rating="4">4 sao</button>
                        <p>Bình luận: ({{ $reviewSummary['4'] ?? 0 }})</p>
                    </div>
                    <div class="groub-button-filter">
                        <button class="button-filter-star " data-rating="5">5 sao</button>
                        <p class="text-center">Bình luận: ({{ $reviewSummary['5'] ?? 0 }})</p>
                    </div>

                </div>

            </div>
            {{-- Vùng hiển thị các đánh giá của user--}}
            <div class="container comment-field">

            </div>

            {{-- Hiển thị nút phân trang --}}
            <div class="pagination mt-3 text-center pagination-review "></div>

        </div>
    </div>


    <script>
        // Xử lý thay đổi ảnh sản phẩm
        const images = document.querySelectorAll('.swiper-slide-img');
        const mainImage = document.getElementById('mainImage');
        let hoverTimeout;

        images.forEach((img) => {
            img.addEventListener('mouseenter', () => {
                clearTimeout(hoverTimeout);
                hoverTimeout = setTimeout(() => {
                    mainImage.src = img.src;
                }, 500);
            });

            img.addEventListener('mouseleave', () => {
                clearTimeout(hoverTimeout);
            });
        });

        const swiper_wrapper = document.querySelector('.swiper-wrapper');
        const swiper_button_prev = document.querySelector('.swiper-button-prev');
        const swiper_button_next = document.querySelector('.swiper-button-next');

        // xử lý 2 nút điều hướng trong swiper
        swiper_button_next.addEventListener('click', () => {
            swiper_wrapper.scrollBy({
                left: 300,
                behavior: 'instant'
            });
        });
        swiper_button_prev.addEventListener('click', () => {
            swiper_wrapper.scrollBy({
                left: -300,
                behavior: 'instant'
            });
        });

        // Xử lý giới hạn số lượng nhập để thêm vào giỏ hàng

        const inputQuantity = document.querySelector('.input-quantity');

        inputQuantity.addEventListener('input', () => {
            const min = 1;
            const max = parseInt(inputQuantity.max);
            let value = parseInt(inputQuantity.value);

            // Nếu không phải số, gán lại giá trị min
            if (isNaN(value)) {
                inputQuantity.value = '';
            }

            // Giới hạn trong khoảng [min, max]
            if (value < min) inputQuantity.value = min;
            if (value > max) inputQuantity.value = max;

        });

        // Xử lý nút tăng, giảm số lượng
        const minusButton = document.querySelector('.quantity-button.minus');
        const plusButton = document.querySelector('.quantity-button.plus');

        minusButton.addEventListener('click', () => {
            let currentValue = parseInt(inputQuantity.value);
            const min = 1;
            if (currentValue > min) {
                inputQuantity.value = currentValue - 1;
            }
        });

        plusButton.addEventListener('click', () => {
            let currentValue = parseInt(inputQuantity.value);
            const max = parseInt(inputQuantity.max);
            if (currentValue < max) {
                inputQuantity.value = currentValue + 1;
            }
        });


        // xử lý hiển thị đánh giá và phân trang đánh giá bằng API
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.button-filter-star');
            const reviewContainer = document.querySelector('.comment-field');
            const paginationContainer = document.querySelector('.pagination');
            const apiBase = `/api/product/{{ $product->product_id }}/reviews`;

            let currentUrl = apiBase;

            // Hàm tải danh sách review + render phân trang
            function loadReviews(url) {
                fetch(url)
                    .then(response => response.json())
                    .then(result => {
                        if (!result.success) {
                            reviewContainer.innerHTML = '<p>Không có dữ liệu!</p>';
                            paginationContainer.innerHTML = '';
                            return;
                        }

                        const pagination = result.data;
                        const reviews = pagination.data;

                        // Nếu không có review
                        if (!reviews.length) {
                            reviewContainer.innerHTML = '<p>Chưa có đánh giá nào cho mức sao này.</p>';
                            paginationContainer.innerHTML = '';
                            return;
                        }

                        // Render danh sách đánh giá
                        reviewContainer.innerHTML = reviews.map(review => {

                            let stars = '';
                            for (let i = 0; i < 5; i++) {
                                if (i < review.rating) {
                                    stars += '<span class="star filled text-warning fs-1">★</span>';
                                } else {
                                    stars += '<span class="star text-warning fs-1">☆</span>';
                                }
                            }

                            const formattedDate = new Date(review.review_date)
                                .toLocaleString('vi-VN', {
                                    day: '2-digit', month: '2-digit', year: 'numeric',
                                    hour: '2-digit', minute: '2-digit'
                                });

                            return `
                                    <div class="review-display border-bottom py-2">
                                        <img class="user-avatar" src="/images/user-icon.jpg" alt="">
                                        <div class="user-review">
                                            <div class="d-flex">
                                                <strong class="review-info">${review.user.full_name}</strong>
                                                <p class="review-info ms-5">| ${formattedDate}</p>
                                            </div>
                                            <p class="review-info">${stars}</p>
                                            <p class="review-info">${review.comment}</p>
                                        </div>
                                    </div>
                                `;
                        }).join('');

                        // Render thanh phân trang
                        paginationContainer.innerHTML = pagination.links.map(link => {

                            const label = link.label;
                            const activeClass = link.active ? 'active' : '';
                            const disabled = link.url === null ? 'disabled' : '';

                            return `
                                    <button 
                                        class="btn btn-sm btn-outline-secondary mx-1 ${activeClass}" 
                                        ${disabled ? 'disabled' : ''} 
                                        data-url="${link.url || '#'}"
                                    >
                                        ${label}
                                    </button>
                                `;
                        }).join('');

                        // Gán sự kiện click cho từng nút
                        paginationContainer.querySelectorAll('button[data-url]').forEach(btn => {
                            btn.addEventListener('click', () => {
                                const url = btn.getAttribute('data-url');
                                if (url && url !== '#') loadReviews(url);
                            });
                        });
                    })
                    .catch(error => {
                        console.error('Lỗi khi tải review:', error);
                        reviewContainer.innerHTML = '<p>Đã xảy ra lỗi khi tải đánh giá!</p>';
                        paginationContainer.innerHTML = '';
                    });
            }

            // xử lý các nút lọc đánh giá sao
            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    buttons.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    const rating = btn.getAttribute('data-rating');
                    const url = rating ? `${apiBase}?rating=${rating}` : apiBase;
                    currentUrl = url;
                    loadReviews(url);
                });
            });

            // Tải mặc định trang đầu tiên
            loadReviews(apiBase);
        });

    </script>





@endsection