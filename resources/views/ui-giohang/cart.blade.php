@extends('layouts.layouts')

@section('title', 'TechStore - Trang chủ')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-filter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-chatbot.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('css/cart.css') }}">
    <div class="background-layout">
        <div class="cartp-container glass3d">
            <div class="cartp-header">🛍️ Giỏ hàng của bạn</div>

            <div class="cartp-items">
                @forelse($cartItems as $item)
                    {{-- Dùng cart_id làm ID cho JS, Controller sẽ dùng ID (giả sử là cart_id) --}}
                    <div class="cartp-item" data-id="{{ $item->cart_id }}">
                        <input type="checkbox" class="cartp-select" checked> {{-- Mặc định chọn --}}
                        <img src="/uploads/{{ $item->product?->cover_image }}">

                        <div>
                            <h3>{{ $item->product?->product_name ?? 'Sản phẩm không tìm thấy' }}</h3>
                        </div>
                        <div class="cartp-quantity">
                            <input type="number" value="{{ $item->quantity }}" min="1"
                                max="{{ $item->product?->stock_quantity ?? 99 }}" class="cartp-qty-input"
                                data-cart-id="{{ $item->cart_id }}">
                        </div>
                        {{-- data-price là đơn giá --}}
                        <div class="cartp-price" data-price="{{ $item->product?->price ?? 0 }}">
                            {{ number_format($item->product?->price ?? 0, 0, ',', '.') }}đ
                        </div>
                        {{-- Gắn ID của CartItem vào nút xóa --}}
                        <button type="button" class="cartp-remove" data-cart-id="{{ $item->cart_id }}">&times;</button>
                    </div>
                @empty
                    <p>🛒 Giỏ hàng của bạn đang trống.</p>
                @endforelse
            </div>

            @if($cartItems->count() > 0)
                <div class="cartp-footer">
                    <div class="cartp-total">
                        Tổng cộng: <span id="cartp-total">
                            {{ number_format($cartItems->sum(fn($i) => ($i->product?->price ?? 0) * $i->quantity), 0, ',', '.') }}đ
                        </span>
                    </div>

                    <form id="checkout-form" action="{{ route('checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="items" id="selected-cart-items-data">
                        <input type="hidden" name="total" id="checkout-total">
                        <input type="hidden" name="voucher_id" id="checkout-voucher">
                        <input type="hidden" name="shipping_address" id="checkout-shipping-address">
                        <button type="submit" class="cartp-checkout">💳 Thanh toán ngay</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
    <script src="{{ asset('js/cart.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
         const cartCountFromController = {{ $cartItemCount ?? 0 }};
        function updateCartCount() {
            if (typeof cartCountFromController === 'number' && cartCountFromController >= 0) {
                const cartCountElement = document.querySelector('.cart-count');
                if (cartCountElement) {
                    cartCountElement.textContent = cartCountFromController;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', updateCartCount);
    </script>
@endsection