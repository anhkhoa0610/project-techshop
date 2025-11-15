@extends('layouts.layouts')

@section('title', 'Đang tải... - TechStore')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/supplier.css') }}">

    <div class="supplier-container" data-supplier-id="{{ $supplier_id }}">

        {{-- ================= HEADER (ĐÃ THÊM ID) ================ --}}
        <section class="shop-header">
            <div class="shop-avatar">
                {{-- Đổi id thành 'shop-logo' cho khớp với model --}}
                <img src="/placeholder-logo.png" alt="Avatar" id="shop-logo">
            </div>

            <div class="shop-info">
                <h2 id="shop-name">Đang tải...</h2>
                <h5 id="shop-desc"><strong>Mô tả:</strong>...</h5>
                {{-- Model không có online status, nên ta ẩn/xóa dòng này --}}
                {{-- <p id="shop-online-status">...</p> --}}
            </div>
            <div class="shop-information">

            </div>

            {{-- ================= SHOP STATS (RÚT GỌN) ================ --}}
            {{-- CHỈ GIỮ LẠI CÁC TRƯỜNG MÀ API CÓ THỂ CUNG CẤP --}}
            <div class="shop-stats">
                <p id="shop-email"><strong>Email:</strong>...</p>
                <p id="shop-phone"><strong>Số điện thoại:</strong>...</p>
                <p id="shop-address"><strong>Địa chỉ:</strong>...</p>
                <p id="shop-stat-join"><strong>Tham gia:</strong> ...</p>
                <p id="shop-stat-products"><strong>Sản phẩm:</strong> ...</p>
                <p id="shop-stat-orders"><strong>Đã bán:</strong> ...</p>
            </div>
        </section>

        {{-- ================= MENU (GIỮ NGUYÊN) ================ --}}
        <nav class="shop-menu ">
            <a class="active" href="#">Sản phẩm</a>
        </nav>

        {{-- ================= PRODUCT LIST (GIỮ NGUYÊN) ================ --}}
        <section class="product-section ">
            <div class="section-title">
                <h3>Sắp xếp theo</h3>
                <button id="sort-best-discount">Khuyến mãi tốt nhất</button>
                <button id="sort-price-asc">Giá tăng dần</button>
                <button id="sort-price-desc">Giá giảm dần</button>
                <button id="sort-newest">Sản phẩm mới nhất</button>
                <button id="sort-best-seller">Sản phẩm bán chạy nhất</button>
            </div>

            <div class="product-grid" id="product-grid">
                <p>Đang tải sản phẩm...</p>
            </div>

            <div id="pagination-links"></div>
        </section>

    </div> {{-- END supplier-container --}}
    <a id="backToTopBtn" title="Quay lên đầu trang">
        <i class="fa fa-arrow-up"></i>
    </a>
    {{-- Link tới JS vẫn giữ nguyên --}}
    <script src="{{ asset('js/supplier-page.js') }}"></script>
@endsection