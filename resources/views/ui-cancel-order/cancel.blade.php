@extends('layouts.layouts')

@section('title', 'TechStore - Trang chủ')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-filter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-chatbot.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('css/cancel.css') }}">
    <div class="background-layout">
        <div class="order-container glass3d">

            <div class="order-list" id="order-list">
                @if (count($formattedOrders) > 0)
                    <div class="order-header">📦 Danh sách đơn hàng của bạn</div>
                    @foreach ($formattedOrders as $order)
                        <div class="order-card" data-status="{{ $order['status'] }}" data-id="{{ $order['id'] }}">
                            <div class="order-left">
                                <div class="order-thumb">
                                    <img src="{{ $order['items'][0]['img'] }}" alt="">
                                </div>
                            </div>

                            <div class="order-info">
                                <h3>Đơn hàng #{{ $order['id'] }}</h3>
                                <div class="muted">Ngày đặt: {{ $order['date'] }}</div>
                                <div class="muted">Số lượng: {{ $order['quantity'] }}</div>
                                <div class="muted">
                                    Tổng tiền:
                                    <span class="price">{{ number_format($order['total'], 0, ',', '.') }}₫</span>
                                </div>
                            </div>

                            <div class="order-actions" style="display:none"></div>
                        </div>
                    @endforeach
                @else
                    <div class="no-order">
                        🛍️ Bạn chưa có đơn hàng nào
                    </div>
                @endif
            </div>

        </div>
    </div>
    <script src="{{ asset('js/cancel.js') }}"> </script>
@endsection