@extends('layouts.layouts')

@section('title', 'Khuyến mãi - TechStore')



@section('content')
    <link rel="stylesheet" href="{{ asset('css/promotion.css') }}">
    <div class="promotion-wrapper">
        <div class="promotion-page container mt-4">

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

            {{-- Danh mục khuyến mãi --}}
            <div id="category-container" class="d-flex flex-wrap justify-content-center gap-3 mb-5 promo-categories">

            </div>

            {{-- Flash Sale Section --}}
            <div class="mt-5">
                <h4 class="fw-bold mb-3 text-danger">FLASH SALE HÔM NAY</h4>
                    <div id="promotion-container" class="row g-3">
                    </div>
            </div>
        </div>
    </div>
 <script>   
    document.addEventListener("DOMContentLoaded", async () => {
        try {
            const response = await fetch("{{ url('/api/promotions') }}");
            const result = await response.json();

            if (result.status === "success") {
                const categories = result.data.categories;
                const promotions = result.data.promotions;

                const categoryContainer = document.getElementById("category-container");
                const promoContainer = document.getElementById("promotion-container");

                // Render danh mục
                categoryContainer.innerHTML = categories.map(c => `
                    <a href="#" class="btn btn-outline-danger rounded-pill fw-semibold promo-btn">
                        ${c.category_name}
                    </a>
                `).join("");

                // Render khuyến mãi
                promoContainer.innerHTML = promotions.map(p => `
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="card h-100 border-0 shadow-sm hover-scale">
                            <img src="{{ asset('images/products/prod-') }}${Math.floor(Math.random() * 6) + 1}.jpg"
                                class="card-img-top" alt="Sản phẩm">
                            <div class="card-body text-center">
                                <p class="card-title small fw-semibold mb-1">${p.code ?? 'Sản phẩm giảm giá'}</p>
                                <span class="text-danger fw-bold">
                                    ₫${(p.discount_value ?? Math.random() * 999000).toLocaleString('vi-VN')}
                                </span>
                                <p class="text-muted small text-decoration-line-through mb-0">
                                    ₫${(Math.random() * 1999000 + 1000000).toLocaleString('vi-VN')}
                                </p>
                            </div>
                        </div>
                    </div>
                `).join("");
            }
        } catch (error) {
            console.error("Lỗi khi tải dữ liệu:", error);
        }
    });

</script>
@endsection

    
