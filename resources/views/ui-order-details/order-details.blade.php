@extends('layouts.layouts')

@section('title', 'TechStore - Trang chủ')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-filter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-chatbot.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('css/order_details.css') }}">
    <div class="background-layout">
        <div class="order-container glass3d">
            @if (count($formattedOrders) > 0)
                @foreach ($formattedOrders as $order)
                    <div class="container" style="margin-bottom: 60px;">
                        <header class="header-details">
                            <div>
                                <h1>Theo dõi đơn hàng</h1>
                                <div class="small">
                                    Mã đơn: <strong>#{{ $order['id'] }}</strong> • {{ $order['created_at'] }}
                                </div>
                            </div>
                            <div>
                                <span class="badge">{{ ucfirst($order['status']) }}</span>
                            </div>
                        </header>

                        <div class="layout">
                            {{-- Cột trái: thông tin người nhận, tiến trình --}}
                            <div class="card order-info">
                                <div class="row">
                                    <div>
                                        <div class="label">Người nhận</div>
                                        <div><strong>{{ auth()->user()->full_name ?? 'Người dùng' }}</strong></div>
                                        <div class="muted-small">{{ auth()->user()->phone ?? '—' }}</div>
                                        <div class="muted-small">{{ $order['shipping_address'] }}</div>
                                    </div>
                                    <div style="text-align:right">
                                        <div class="label">Tổng</div>
                                        <div class="price">{{ number_format($order['total'], 0, ',', '.') }}₫</div>
                                        <div class="muted-small">Phương thức: {{ $order['payment_method'] }}</div>
                                    </div>
                                </div>

                                {{-- Tiến trình đơn hàng --}}
                                <div class="label" style="margin-top:12px">Tiến trình đơn hàng</div>
                                <div class="timeline">
                                    <div class="steps">

                                        {{-- 1. Bước: Đang chờ (Pending) --}}
                                        <div class="step">
                                            <?php 
                                                // Bước này hoàn thành nếu trạng thái là processing, shipped, completed
                                                $is_step_done = in_array($order['status'], ['processing', 'shipped', 'completed']);
                                                // Bước này đang hoạt động nếu trạng thái là pending
                                                $is_step_active = $order['status'] == 'pending';
                                            ?>
                                            <div class="dot 
                                                {{ $is_step_done ? 'done' : ($is_step_active ? 'active' : 'pending') }}">
                                                {{ $is_step_done ? '✓' : ($is_step_active ? '●' : '') }}
                                            </div>
                                            <div class="time muted-small">Đang chờ</div>
                                        </div>

                                        {{-- 2. Bước: Đang xử lý (Processing) --}}
                                        <div class="step">
                                            <?php 
                                                // Bước này hoàn thành nếu trạng thái là shipped, completed
                                                $is_step_done = in_array($order['status'], ['shipped', 'completed']);
                                                // Bước này đang hoạt động nếu trạng thái là processing
                                                $is_step_active = $order['status'] == 'processing';
                                            ?>
                                            <div class="dot 
                                                {{ $is_step_done ? 'done' : ($is_step_active ? 'active' : 'pending') }}">
                                                {{ $is_step_done ? '✓' : ($is_step_active ? '●' : '') }}
                                            </div>
                                            <div class="time muted-small">Đang xử lý</div>
                                        </div>

                                        {{-- 3. Bước: Đã giao (Completed) --}}
                                        <div class="step">
                                            <?php 
                                                // Bước này hoàn thành nếu trạng thái là completed
                                                $is_step_done = $order['status'] == 'completed';
                                            ?>
                                            <div class="dot 
                                                {{ $is_step_done ? 'done' : 'pending' }}">
                                                {{ $is_step_done ? '✓' : '' }}
                                            </div>
                                            <div class="time muted-small">Đã giao</div>
                                        </div>

                                    </div>
                                </div>

                                {{-- Lịch sử vận chuyển --}}
                                <div class="label" style="margin-top:12px">Lịch sử vận chuyển</div>
                                <div class="events">
                                    <div class="event">
                                        <div class="left">
                                            <div style="width:8px;height:8px;background:#cbd5e1;border-radius:50%"></div>
                                        </div>
                                        <div class="right">
                                            <div style="font-weight:700">Đơn hàng đã được tạo</div>
                                            <div class="muted-small">{{ $order['created_at'] }}</div>
                                        </div>
                                    </div>
                                    <div class="event">
                                        <div class="left">
                                            <div style="width:8px;height:8px;background:#cbd5e1;border-radius:50%"></div>
                                        </div>
                                        <div class="right">
                                            <div style="font-weight:700">Đơn hàng đang được xử lý</div>
                                            <div class="muted-small">{{ $order['created_at'] }}</div>
                                        </div>
                                    </div>
                                </div>

                                <footer>

                                    <div class="actions">

                                        <button class="btn-primary contact-support-btn-dynamic" data-phone="02838966825">Liên hệ hỗ
                                            trợ</button>
                                        <button class="btn-ghost download-invoice-btn-dynamic"
                                            data-order-id="{{ $order['id'] }}">Tải hóa đơn</button>
                                    </div>

                                </footer>
                            </div>

                            {{-- Cột phải: bản đồ + sản phẩm --}}
                            <div>
                                <div class="card">
                                    <div class="label">Bản đồ & trạng thái hiện tại</div>
                                    <?php
                                        $origin_address = "53 Đ. Võ Văn Ngân, Phường, Thủ Đức, Thành phố Hồ Chí Minh"; // Điểm A: Địa chỉ xuất phát
                                                                                                ?>
                                    <div class="map glass-map">
                                        <iframe width="100%" height="100%" frameborder="0" style="border:0;border-radius:12px;"
                                            src="https://maps.google.com/maps?q=<?php echo urlencode($origin_address); ?>&output=embed"
                                            allowfullscreen>
                                        </iframe>
                                    </div>

                                    <div class="label" style="margin-top:12px">Sản phẩm</div>
                                    <div class="items">
                                        @foreach ($order['items'] as $item)
                                            <div class="item">
                                                <img src="{{ $item['img'] }}" alt="{{ $item['title'] }}">
                                                <div class="meta">
                                                    <div style="font-weight:700">{{ $item['title'] }}</div>
                                                    <div class="muted-small">Số lượng: {{ $item['quantity'] }}</div>
                                                </div>
                                                <div style="text-align:right">
                                                    <div class="price">{{ number_format($item['unit_price'], 0, ',', '.') }}₫</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div>Dự kiến giao hàng</div>
                                    <?php
                                        $format = 'd/m/Y H:i';
                                        $deliveryDate = \Carbon\Carbon::createFromFormat($format, $order['created_at'])->addDays(5);
                                        $formattedDeliveryDate = $deliveryDate->format('d/m/Y');
                                        $dayOfWeek = $deliveryDate->locale('vi')->dayName;
                                                                        ?>
                                    <div class="muted-small">
                                        {{ $dayOfWeek }}, {{ $formattedDeliveryDate }} • Trong Ngày
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-order">🛍️ Bạn chưa có đơn hàng nào</div>
            @endif
        </div>
    </div>
    <script src="{{ asset('js/order_details.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection