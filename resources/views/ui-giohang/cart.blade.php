<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    {{-- Đảm bảo CSRF token được đặt trong thẻ meta --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TechStore - Cửa hàng công nghệ')</title>

    <link rel="stylesheet" href="{{ asset('css/index-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
       .header {
        color: black !important;
       }
    </style>
</head>

<body>
    {{-- Header --}}
    @include('partials.header')

    {{-- Nội dung trang --}}
    <div class="cartp-container">
        <div class="cartp-header">🛍️ Giỏ hàng của bạn</div>

        <div class="cartp-items">
            @forelse($cartItems as $item)
                {{-- Đã sửa: Dùng $item->cart_id thay vì $item->id --}}
                <div class="cartp-item" data-id="{{ $item->cart_id }}">
                    <input type="checkbox" class="cartp-select">
                    {{-- FIX LỖI 500: Dùng Toán tử Nullsafe (?->) để kiểm tra $item->product --}}
                    <img src="{{ $item->product->image ?? 'https://via.placeholder.com/80' }}">
                    <div>
                        {{-- FIX LỖI 500: Dùng Toán tử Nullsafe (?->) --}}
                        <h3>{{ $item->product?->product_name ?? 'Sản phẩm không tìm thấy' }}</h3>
                    </div>
                    <div class="cartp-quantity">
                        <input type="number" value="{{ $item->quantity }}" min="1" class="cartp-qty-input">
                    </div>
                    <div class="cartp-price" data-price="{{ $item->product?->price ?? 0 }}">
                        {{-- FIX LỖI 500: Dùng Toán tử Nullsafe (?->) --}}
                        {{ number_format($item->product?->price ?? 0, 0, ',', '.') }}đ
                    </div>
                    {{-- Gắn ID của CartItem vào nút xóa --}}
                    <button type="button" class="cartp-remove" data-cart-id="{{ $item->cart_id }}">&times;</button>
                </div>
            @empty
                <p>🛒 Giỏ hàng của bạn đang trống.</p>
            @endforelse
        </div>

        @if(isset($cartItems) && $cartItems->count() > 0)
            <div class="cartp-footer">
                <div class="cartp-total">
                    Tổng cộng: <span {{-- FIX LỖI 500: Cần kiểm tra Nullsafe trong hàm sum --}}
                        id="cartp-total">{{ number_format($cartItems->sum(fn($i) => ($i->product?->price ?? 0) * $i->quantity), 0, ',', '.') }}đ</span>
                </div>
                <div class="cartp-footer-buttons">
                    {{-- Nút này cần Controller::destroyMany và Route::delete('/cart-items') --}}

                    <button class="cartp-checkout">💳 Thanh toán ngay</button>
                </div>
            </div>
        @endif
    </div>

    {{-- Footer --}}
    @include('partials.footer')
    <script src="{{ asset('js/cart.js') }}"></script>
    
</body>

</html>