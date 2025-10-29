@extends('layouts.layouts')

@section('title', 'Trang thông tin sản phẩm')

@section('content')

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
                        ⭐ {{ $product->rating ?? 4.7 }} | {{ $product->review_count ?? '3k' }} đánh giá | Đã bán
                        {{ $product->sold_count ?? '10k+' }}
                    </p>

                    <h4 class="text-danger fw-bold">
                        {{ isset($product->price) ? number_format($product->price, 0, ',', '.') : 0}}đ
                    </h4>

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
                        <span class="rating-left">4.9 </span>
                        <span class="rating-right"> trên 5</span>
                    </div>
                    <div class="star-rating-display">⭐⭐⭐⭐⭐</div>
                </div>
                <div class="col-md-9 filter-by-star">
                    <button class="button-filter-star active">Tất cả</button>
                    <button class="button-filter-star">1 sao</button>
                    <button class="button-filter-star">2 sao</button>
                    <button class="button-filter-star">3 sao</button>
                    <button class="button-filter-star">4 sao</button>
                    <button class="button-filter-star">5 sao</button>

                </div>

            </div>
            <p>{{ isset($product->description) ? $product->description : "Sản phẩm không có mô tả!!"}}</p>
        </div>
    </div>


    <script>
        const images = document.querySelectorAll('.swiper-slide-img');
        const mainImage = document.getElementById('mainImage');
        let hoverTimeout; // dùng để lưu timeout hiện tại

        images.forEach((img) => {
            img.addEventListener('mouseenter', () => {
                // xóa delay trước (nếu có), để tránh bị lặp
                clearTimeout(hoverTimeout);

                // đặt delay 500ms (0.5 giây)
                hoverTimeout = setTimeout(() => {
                    mainImage.src = img.src;
                }, 500);
            });

            img.addEventListener('mouseleave', () => {
                // nếu rời chuột trước khi hết delay, hủy luôn
                clearTimeout(hoverTimeout);
            });
        });

        const swiper_wrapper = document.querySelector('.swiper-wrapper');
        const swiper_button_prev = document.querySelector('.swiper-button-prev');
        const swiper_button_next = document.querySelector('.swiper-button-next');

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

        // Xử lý giới hạn số lượng nhập

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

        // Xử lý nút tăng giảm số lượng
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

        // xử lý trạng thái nút lọc đánh giá sao
        document.querySelectorAll('.button-filter-star').forEach(btn => {
            btn.addEventListener('click', function () {
                // Xóa active ở tất cả các nút
                document.querySelectorAll('.button-filter-star').forEach(b => b.classList.remove('active'));
                // Gán active cho nút được click
                this.classList.add('active');
            });
        });
    </script>





@endsection