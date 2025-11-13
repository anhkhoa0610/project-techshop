@extends('layouts.layouts')

@section('title', 'Trang thông tin sản phẩm')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/product-details.css') }}">

    <div class="content-container w-100">
        <div class="header-back-ground w-100"></div>
        <div class="container-details container glass3d ">
            <div class="row">
                <!-- Cột trái: Hình ảnh sản phẩm -->
                <div class="col-md-6">
                    <div class="product-images text-center">

                        @php
                            $discount = $product->discounts->first(); 
                        @endphp

                        @if ($discount)
                            <div class="related-product-sale-icon main">
                                Giảm {{ $discount->discount_percent }}%
                            </div>
                        @endif

                        <img src="{{!empty($product->cover_image) ? asset('uploads/' . $product->cover_image) : asset('images/blank_product.png') }}"
                            class="prodcut-image" alt="Ảnh sản phẩm chính" id="mainImage">
                        <div class="swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <img src="{{!empty($product->cover_image) ? asset('uploads/' . $product->cover_image) : asset('images/blank_product.png') }}"
                                        alt="Ảnh chính" class="swiper-slide-img">
                                </div>
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
                        <span class="star filled text-warning fs-1">★</span>
                        <span class="rating-star-title">{{ number_format($avg, 1) ?? 0 }}</span> | <span
                            class="total-review">{{ $reviews_count ?? 0 }}</span>
                        đánh giá | Đã bán
                        {{ $product->volume_sold ?? 0 }}
                    </p>

                    <h3 class=" fw-bold">
                        <strong>Đơn giá: </strong>
                        @php
                            // Tải đối tượng giảm giá đầu tiên nếu tồn tại
                            $discount = $product->discounts->first(); 
                        @endphp

                        <span class="product-price">

                            @if ($discount)

                                <span class="current-price">
                                    {{ number_format($discount->sale_price, 0, ',', '.') }}₫
                                </span>

                                <span class="original-price price-strike-through">
                                    {{ number_format($discount->original_price, 0, ',', '.') }}₫
                                </span>

                            @else
                                <span class="current-price">
                                    {{ number_format($product->price, 0, ',', '.') }}₫
                                </span>
                            @endif

                        </span>


                    </h3>

                    <p class="mt-3"><strong>Nhà phân phối: </strong>
                        {{ isset($product->supplier->name) ? $product->supplier->name : "Không có nhà phân phối"}}</p>

                    <p class="mt-3"><strong>Bảo hành: </strong>
                        {{ isset($product->warranty_period) ? $product->warranty_period . ' tháng' : 'Không bảo hành' }}</p>
                    <p class="mt-3"><strong>Danh mục: </strong>
                        {{ isset($product->category) ? $product->category->category_name : 'Không có danh mục' }}</p>

                    <div class="mt-4 d-flex align-items-center">
                        <strong class="me-2">Số lượng:</strong>
                        <i class="bi bi-dash quantity-button minus"></i>
                        <input type="number" class="input-quantity form-control text-center"
                            max="{{ isset($product->stock_quantity) ? $product->stock_quantity : 0 }}"
                            value="{{ isset($product->stock_quantity) ? 1 : 0 }}">
                        <i class="bi bi-plus quantity-button plus"></i>
                        <p class="mt-3 ms-3">
                            {{ isset($product->stock_quantity) ? $product->stock_quantity . ' sản phẩm có sẵn' : 'Hết hàng' }}
                        </p>
                    </div>

                    @php
                        use Illuminate\Support\Str;

                        $specsMap = $product->specs?->pluck('value', 'name') ?? collect();

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

                    @if($product->specs && $product->specs->count() > 0)
                        <div class="mt-3">
                            <h5 class="fw-bold mb-3 text-uppercase">Thông số sản phẩm</h5>

                            <div class="specs-grid-container d-flex flex-wrap justify-content-start gap-3">
                                @foreach ($coreSpecsData as $name => $value)
                                    @if ($value)
                                        <div class="spec-grid-item text-center p-2 border rounded shadow-sm bg-white">
                                            <img src="{{ $specIconFiles[$name] }}" alt="{{ $name }} icon" class="spec-grid-icon mb-2"
                                                style="width: 40px; height: 40px;">
                                            <div class="spec-grid-text">
                                                <span class="spec-grid-name d-block fw-semibold">{{ $name }}</span>
                                                <strong class="spec-grid-value d-block">{{ $value }}</strong>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-4">
                        <button class="btn btn-danger me-2 btn-buy-now">Mua ngay</button>
                        <button class="btn btn-outline-danger btn-add-cart-main">Thêm vào giỏ hàng</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="desc-product container glass3d">
            <h2>Mô tả sản phẩm</h2>
            <p>{{ isset($product->description) ? $product->description : "Sản phẩm không có mô tả!!"}}</p>
        </div>

        <div class="review-product container glass3d ">
            <h2>Đánh giá sản phẩm</h2>
            <div class="review-title glass3d">
                <div class="col-md-3 star-rating  ">
                    <div class="rating">
                        <span class="rating-right"> trên 5 sao</span>
                        <span class="rating-left">{{ number_format($avg, 1) ?? 0 }} </span>
                    </div>
                    <div class="star-rating-display" data-avg="{{ $avg}}">
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
                        <p class="review-count">Bình luận: (<span class="review-count"
                                data-rating="all">{{ $reviewSummary['all'] ?? 0 }}</span>)
                        </p>
                    </div>
                    <div class="groub-button-filter">
                        <button class="button-filter-star" data-rating="1">1 sao</button>
                        <p class="review-count">Bình luận: (<span class="review-count"
                                data-rating="1">{{ $reviewSummary['1'] ?? 0 }}</span>)</p>
                    </div>
                    <div class="groub-button-filter">
                        <button class="button-filter-star " data-rating="2">2 sao</button>
                        <p class="review-count">Bình luận: (<span class="review-count"
                                data-rating="2">{{ $reviewSummary['2'] ?? 0 }}</span>)</p>
                    </div>
                    <div class="groub-button-filter">
                        <button class="button-filter-star " data-rating="3">3 sao</button>
                        <p class="review-count">Bình luận: (<span class="review-count"
                                data-rating="3">{{ $reviewSummary['3'] ?? 0 }}</span>)</p>
                    </div>
                    <div class="groub-button-filter">
                        <button class="button-filter-star " data-rating="4">4 sao</button>
                        <p class="review-count">Bình luận: (<span class="review-count"
                                data-rating="4">{{ $reviewSummary['4'] ?? 0 }}</span>)</p>
                    </div>
                    <div class="groub-button-filter">
                        <button class="button-filter-star " data-rating="5">5 sao</button>
                        <p class="review-count">Bình luận: (<span class="review-count"
                                data-rating="5">{{ $reviewSummary['5'] ?? 0 }}</span>)</p>
                    </div>

                </div>

            </div>
            <div class="post-review glass3d">
                <div class="title-post">
                    <h3>Thêm đánh giá sản phẩm</h3>
                </div>
                <div class="post-form">
                    <form id="form-post-review">
                        @csrf
                        <div class="mb-3">
                            <label for="reviewRating" class="form-label">Đánh giá của bạn:</label>
                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                            <select class="form-select" id="reviewRating" name="rating" required>
                                <option value="" selected disabled>Chọn số sao đánh giá</option>
                                <option class="text-warning" value="1">
                                    <span class="star filled text-warning fs-1">★</span>
                                </option>
                                <option class="text-warning" value="2">
                                    <span class="star filled text-warning fs-1">★★</span>
                                </option>
                                <option class="text-warning" value="3">
                                    <span class="star filled text-warning fs-1">★★★</span>
                                </option>
                                <option class="text-warning" value="4">
                                    <span class="star filled text-warning fs-1">★★★★</span>
                                </option>
                                <option class="text-warning" value="5">
                                    <span class="star filled text-warning fs-1">★★★★★</span>
                                </option>
                            </select>
                            
                        </div>
                        <div class="mb-3">
                            <label for="reviewComment" class="form-label">Bình luận của bạn:</label>
                            <textarea class="form-control" id="reviewComment" name="comment" rows="4"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                    </form>
                </div>
            </div>

            {{-- Vùng hiển thị các đánh giá của user--}}
            <div class="container comment-field glass3d ">

            </div>

            {{-- Hiển thị nút phân trang của đánh giá--}}
            <div class="pagination mt-3 text-center pagination-review "></div>

        </div>
        <div class="container glass3d related-product">
            <div class="title-button">
                <h2 class="related-title"> Sản phẩm liên quan:
                    (<span class="related-title-type"> Cùng danh mục</span> )
                </h2>
                <div class="related-button">
                    <button class="category-button  active" data-category_id="{{ $product->category_id }}">Cùng danh
                        mục</button>
                    <button class="supplier-button " data-supplier_id="{{ $product->supplier_id }}">Nhà phân
                        phối</button>
                </div>
            </div>
            <div class="related-display container "></div>
        </div>
    </div>
    <script>
        const check_user = @json(auth()->check());
        const user_id = @json(auth()->id());
        window.csrfToken = @json(csrf_token());
        const productId = @json($product->product_id);
        const cartItems_count = @json($cartItems_count);
    </script>
    <script src="{{ asset('js/ui-product.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection