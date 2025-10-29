<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TechStore - Cửa hàng công nghệ hàng đầu Việt Nam')</title>
    <meta name="description"
        content="@yield('description', 'TechStore - Chuyên bán điện thoại, laptop, tai nghe chính hãng với giá tốt nhất. Bảo hành uy tín, giao hàng nhanh toàn quốc.')">

    <link rel="stylesheet" href="{{ asset('css/index-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pay.css') }}">


    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
</head>

<body>

    {{-- Header --}}
    @include('partials.header')

    {{-- Nội dung từng trang --}}

    <body>
        <div class="container-center background-layout">
            <div class="wrap">
                <!-- LEFT -->
                <section class="panel">
                    <h1>Thanh toán</h1>
                    <p class="lead">Điền thông tin giao hàng và chọn phương thức thanh toán</p>
                    <form id="checkoutForm">
                        <div class="section">
                            <div class="subtitle"><label>Thông tin người nhận</label><small class="muted">Thông tin bắt
                                    buộc</small></div>
                            <div class="two-col">
                                <div><label for="fname">Họ và tên</label><input id="fname" type="text"
                                        placeholder="Nguyễn Văn A" required>
                                </div>
                                <div><label for="phone">Số điện thoại</label><input id="phone" type="tel"
                                        placeholder="09x xxx xxxx" required></div>
                            </div>
                            <div style="margin-top:12px"><label for="email">Email</label><input id="email" type="email"
                                    placeholder="you@example.com" required></div>
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

                <!-- RIGHT -->
                <!-- <aside class="summary">
                    <h3>Đơn hàng của bạn</h3>
                    <div class="item">
                        <div class="thumb"><img src="https://i.imgur.com/tGbaZCY.jpg"></div>
                        <div>
                            <div class="title">Quần Jeans Xanh</div>
                            <div class="meta">Size M • Số lượng: 1</div>
                        </div>
                        <div class="price">350.000₫</div>
                       
                    </div>
                    <div class="item">
                        <div class="thumb"><img src="https://i.imgur.com/6oHix35.jpg"></div>
                        <div>
                            <div class="title">Áo Thun Trắng</div>
                            <div class="meta">Size L • Số lượng: 1</div>
                        </div>
                        <div class="price">200.000₫</div>
                    </div>
                     <div>Sinh Viên TDC Giảm 10%</div>
                    <div class="divider"></div>
                    <div class="totals">
                        <div class="row total">
                            <div>Tổng phải trả</div>
                            <div>580.000₫</div>
                        </div>
                        <button class="pay-btn" type="button" id="payBtn">Thanh toán & Đặt hàng</button>
                    </div>
                </aside> -->
                <aside class="summary">
                    <h3>Đơn hàng của bạn</h3>

                    @php
                        // Đảm bảo biến luôn là một Collection (hoặc mảng) để tránh lỗi
                        $selectedCartItems = $selectedCartItems ?? collect();
                        $subtotal = 0;
                    @endphp

                    @forelse($cartItems as $item)
                        @php
                            // ... (Logic tính toán của bạn ở đây)
                            $product = $item->product ?? (object) ['product_name' => 'Không tìm thấy', 'image' => 'https://via.placeholder.com/64', 'price' => 0];
                            $quantity = $item->quantity ?? 1;
                            $price = $product->price ?? 0;
                            $itemTotal = $price * $quantity;
                            $subtotal += $itemTotal;
                            $discountAmount = $subtotal * 0.2; // Giảm giá 20%
                            $finalSubtotal = $subtotal - $discountAmount;
                        @endphp
                        <div class="item">
                            <div class="thumb"><img src="/uploads/{{ $item->product->cover_image}}"></div>
                            <div>
                                <div class="title">{{ $product->product_name }}</div>
                                <div class="meta">Số lượng: {{ $quantity }}</div>
                            </div>
                            <div class="price">{{ number_format($itemTotal, 0, ',', '.') }}₫</div>
                        </div>
                    @empty
                        <div class="item">
                            <p>Không có sản phẩm nào được chọn. Vui lòng quay lại <a href="/cart">Giỏ hàng</a>.</p>
                        </div>
                    @endforelse
                    <div class="row">
                        <div style="font-weight: 600; color: white;">Giảm giá TDC (20%)</div>
                        <div style="font-weight: 600; color: white;">-
                            {{ number_format($discountAmount, 0, ',', '.') }}₫
                        </div>
                    </div>
                    <div class="row total">
                        <div style="color: white;">Tổng phải trả</div>
                        <div style="color: white;">{{ number_format($finalSubtotal, 0, ',', '.') }}₫</div>
                    </div>
                    <button class="pay-btn" type="button" id="payBtn">Thanh toán & Đặt hàng</button>
                </aside>
            </div>
        </div>

    </body>



    {{-- Footer --}}
    @include('partials.footer')


</body>

</html>
<script>
    const momoUrl = "{{ route('momo.payment') }}";
    const csrfToken = "{{ csrf_token() }}";
    const totalAmount = "{{ $finalSubtotal ?? 0 }}";
</script>
<script src="{{ asset('js/pay.js') }}"></script>