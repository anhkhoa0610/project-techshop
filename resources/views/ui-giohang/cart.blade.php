<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TechStore - Cửa hàng công nghệ hàng đầu Việt Nam')</title>
    <meta name="description"
        content="@yield('description', 'TechStore - Chuyên bán điện thoại, laptop, tai nghe chính hãng với giá tốt nhất. Bảo hành uy tín, giao hàng nhanh toàn quốc.')">

    <link rel="stylesheet" href="{{ asset('css/index-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">


    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
</head>

<body>
    {{-- Header --}}
    @include('partials.header')

    <div class="background-layout">
        <div class="cartp-container">
            <div class="cartp-header">🛍️ Giỏ hàng của bạn</div>

            <div class="cartp-items">
                @forelse($cartItems as $item)
                    {{-- Đã sửa: Dùng $item->cart_id thay vì $item->id --}}
                    <div class="cartp-item" data-id="{{ $item->cart_id }}">
                        <input type="checkbox" class="cartp-select">
                        {{-- FIX LỖI 500: Dùng Toán tử Nullsafe (?->) để kiểm tra $item->product --}}
                        <img src="/uploads/{{ $item->product->cover_image}}">
                     
                        <div>
                            {{-- FIX LỖI 500: Dùng Toán tử Nullsafe (?->) --}}
                            <h3>{{ $item->product?->product_name ?? 'Sản phẩm không tìm thấy' }}</h3>
                        </div>
                        <div class="cartp-quantity">
                            <input type="number" value="{{ $item->quantity }}" min="1"
                                max="{{ $item->product->stock_quantity }}" class="cartp-qty-input">
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
                        <button class="cartp-checkout">💳 Thanh toán ngay</button>
                    </div>
                </div>
            @endif
        </div>
    </div>




    {{-- Footer --}}
    @include('partials.footer')
</body>

</html>
    <script src="{{ asset('js/cart.js') }}"></script>