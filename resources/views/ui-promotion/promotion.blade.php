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
                const { promotions, categories, products } = result;

                const promoContainer = document.getElementById("promotion-container");

                promoContainer.innerHTML = products.map(p => `
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="card h-100 border-0 shadow-sm hover-scale">
                            <img src="/uploads/${p.cover_image ?? 'no-image.png'}" class="card-img-top" alt="${p.product_name ?? p.category_name ?? p.discount_type ?? 'Không rõ'}">
                            <div class="card-body text-center">
                                <p class="card-title small fw-semibold mb-1">
                                    ${p.product_name ?? 'Không rõ'}
                                </p>
                                <span class="text-danger fw-bold">
                                    ₫${(p.price * 90 / 100 ?? 00).toLocaleString('vi-VN')}
                                </span>
                                <p class="text-muted small text-decoration-line-through mb-0">
                                    ₫${(p.price ?? 00).toLocaleString('vi-VN')}
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

    
