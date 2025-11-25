@extends('layouts.layouts')

@section('title', 'TechStore - Trang chủ')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-filter.css') }}">
    <link rel="stylesheet" href="{{ asset('css/index-chatbot.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('css/pay.css') }}">
    <div class="container-center background-layout ">
        <div class="wrap ">
            <!-- LEFT -->
            <div class="glass3d " style="border-radius: 20px;">
                <section class="panel ">
                    <h1>Thanh toán</h1>
                    <p class="lead">Điền thông tin giao hàng và chọn phương thức thanh toán</p>
                    <form id="checkoutForm">
                        <div class="section">
                            <div class="subtitle">
                                <label>Thông tin người nhận</label>
                                <small class="muted">Thông tin bắt buộc</small>
                            </div>

                            <div class="two-col">
                                <div>
                                    <label for="fname">Họ và tên</label>
                                    <input id="fname" name="fullname" type="text"
                                        value="{{ old('fullname', $user->full_name ?? '') }}" placeholder="Nguyễn Văn A"
                                        required>
                                </div>

                                <div>
                                    <label for="phone">Số điện thoại</label>
                                    <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone ?? '') }}"
                                        placeholder="09x xxx xxxx" required>
                                </div>
                            </div>

                            <div style="margin-top:12px">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email ?? '') }}"
                                    placeholder="you@example.com" required>
                            </div>
                        </div>

                        <div class="section">

                            <div style="margin-bottom:12px">
                                <label for="address">Địa chỉ</label>
                                <input id="address" type="text" placeholder="Số nhà, tên đường" required>
                            </div>
                            <div class="two-col">
                                <div>
                                    <label for="city">Tỉnh / Thành phố</label>
                                    <select id="city" required>
                                        <option value="">Chọn tỉnh/thành</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="district">Quận / Huyện</label>
                                    <select id="district" required>
                                        <option value="">Chọn quận/huyện</option>
                                    </select>
                                </div>
                            </div>
                            <div style="margin-top:12px">
                                <label for="ward">Phường / Xã</label>
                                <select id="ward" required>
                                    <option value="">Chọn phường/xã</option>
                                </select>
                            </div>
                        </div>
                        <div class="section">
                            <div class="two-col">
                                <div class="input-voucher">
                                    <label for="voucher">Voucher</label>
                                    <input id="voucher" name="voucher" type="text" value="" placeholder="Nhập Voucher"
                                        required>
                                </div>
                                <div class="apply-btn">
                                    <button type="button" class="apply" id="apply-btn">Áp Dụng</button>
                                </div>
                            </div>
                        </div>

                        <!-- PHƯƠNG THỨC THANH TOÁN -->
                        <div class="section">
                            <div class="subtitle"><label>Phương thức thanh toán</label><small class="muted">Chọn
                                    một</small>
                            </div>
                            <div class="radio-group">

                                <label class="radio">
                                    <input type="radio" name="pay" value="vnpay" checked>
                                    <div>
                                        <div class="label">VNPay</div>
                                        <div class="meta">Thanh Toán VNPAY</div>
                                    </div>
                                </label>

                                <label class="radio">
                                    <input type="radio" name="pay" value="momo">
                                    <div>
                                        <div class="label">MoMo</div>
                                        <div class="meta">Ví điện tử MoMo</div>
                                    </div>
                                </label>

                            </div>
                        </div>


                    </form>
                </section>
            </div>


            <div class="glass3d" style="border-radius: 20px;">
                <aside class="summary ">
                    <h3>Đơn hàng của bạn</h3>

                    @php
                        // Đảm bảo biến luôn là một Collection (hoặc mảng) để tránh lỗi
                        $selectedCartItems = $selectedCartItems ?? collect();
                        $subtotal = 0;
                    @endphp
                    <div class="items-list">
                        @forelse($cartItems as $item)
                            <div class="item">
                                <div class="thumb"><img src="/uploads/{{ $item->product->cover_image}}"></div>
                                <div>
                                    <div class="title">{{ $item->product->product_name }}</div>
                                    <div class="meta">Số lượng: {{ $item->quantity }}</div>
                                </div>
                                <div class="price">
                                    {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}₫
                                </div>
                            </div>
                        @empty
                            <div class="item">
                                <p>Không có sản phẩm nào được chọn. Vui lòng quay lại <a href="/cart">Giỏ hàng</a>.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="item-text" id="voucher-discount" style="display: none;">
                        <div style="font-weight: 600; color: white;">Giảm giá voucher</div>
                        <div id="voucher-amount" style="font-weight: 600; color: white;">-0₫</div>

                    </div>
                    @php
                        $total = 0;
                        foreach ($cartItems as $item) {
                            $total += ($item->product->price ?? 0) * $item->quantity;
                        }
                    @endphp
                    <div class="item-text total">
                        <div style="color: white;">Tổng phải trả</div>
                        <div id="total-price" style="color: white;">
                            {{ number_format($total, 0, ',', '.') }}₫
                        </div>
                    </div>
                    <button class="pay-btn" type="button" id="payBtn">Thanh toán & Đặt hàng</button>
                </aside>
            </div>
        </div>
    </div>
    <script>
        const csrfToken = "{{ csrf_token() }}";
        const totalAmount = {{ $total ?? 0 }};
        const cartItems = @json($cartItems->map(fn($i) => ['id' => $i->cart_id, 'qty' => $i->quantity]));

        const momoUrl = "{{ route('momo.payment') }}";
        const vnpayUrl = "{{ route('vnpay.payment') }}";
    </script>
    <script src="{{ asset('js/pay.js') }}"></script>
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
