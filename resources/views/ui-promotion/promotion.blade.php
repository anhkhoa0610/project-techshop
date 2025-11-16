@extends('layouts.layouts')

@section('title', 'Khuyến mãi - TechStore')



@section('content')
    <link rel="stylesheet" href="{{ asset('css/promotion.css') }}">
    <div class="promotion-wrapper">
        <div class="promotion-page mt-4">

            {{-- Banner khuyến mãi --}}
            <div id="promoCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-inner rounded-4 overflow-hidden shadow-sm">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/banner-5.png') }}" class="d-block w-100" alt="Banner 1">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/banner-6.png') }}" class="d-block w-100" alt="Banner 2">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/banner-3.png') }}" class="d-block w-100" alt="Banner 3">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>

            {{-- Các ưu đãi nổi bật --}}
            <div class="row text-center mb-5">
                <div class="col-md-4 mb-3">
                    <i class="bi bi-truck display-5 text-danger"></i>
                    <h6 class="mt-2 fw-bold">Miễn phí vận chuyển</h6>
                    <p class="text-muted small">Giao hàng miễn phí toàn quốc</p>
                </div>
                <div class="col-md-4 mb-3">
                    <i class="bi bi-shield-check display-5 text-primary"></i>
                    <h6 class="mt-2 fw-bold">Hàng chính hãng 100%</h6>
                    <p class="text-muted small">Đảm bảo hoàn tiền gấp đôi nếu hàng giả</p>
                </div>
                <div class="col-md-4 mb-3">
                    <i class="bi bi-arrow-counterclockwise display-5 text-success"></i>
                    <h6 class="mt-2 fw-bold">Đổi trả 15 ngày</h6>
                    <p class="text-muted small">Trả hàng miễn phí trong 15 ngày</p>
                </div>
            </div>

            <section id="voucher-list" class="my-5 mx-5">
                <p class="modern-big-title text-center">
                    Phiếu giảm giá có thời hạn – Mua sắm và tiết kiệm!
                </p>
                <div class="row g-4" id="voucher-container"></div>
                <div id="voucher-pagination" class="my-3 text-center"></div>
            </section>

            <div id="promoCarouselTwo" class="carousel slide mb-4" data-bs-ride="carousel">
                <div class="carousel-inner rounded-4 overflow-hidden shadow-sm">
                    <div class="carousel-item active">
                        <img src="{{ asset('images/banner-4.png') }}" class="d-block w-100" alt="Banner 1">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/banner-1.png') }}" class="d-block w-100" alt="Banner 2">
                    </div>
                    <div class="carousel-item">
                        <img src="{{ asset('images/banner-2.png') }}" class="d-block w-100" alt="Banner 3">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#promoCarouselTwo" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#promoCarouselTwo" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
            {{-- Flash Sale Section --}}
            <div class="mt-5">
                <div class="flash-sale-wrapper my-4">
                    <div class="flash-sale-banner d-flex align-items-center justify-content-center gap-3">
                        <i class="fa-solid fa-bolt-lightning fs-2 lightning-icon"></i>
                        <h4 class="fw-bold text-uppercase m-0 flash-sale-text">Flash Sale Hôm Nay</h4>
                        <i class="fa-solid fa-clock fs-3 clock-icon"></i>
                    </div>
                </div>
                <div id="promotion-container" class="row g-3">
                </div>
            </div>
        </div>
    </div>
    <a id="backToTopBtn" title="Quay lên đầu trang">
        <i class="fa fa-arrow-up"></i>
    </a>
    <script>
        // Lấy ID người dùng từ server (ví dụ cho Laravel)
        const USER_ID = {{ auth()->check() ? auth()->id() : 'null' }};

        // Lấy CSRF token (Hàm add to cart cũng cần cái này)
        const csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/promotion.js') }}"></script>
@endsection