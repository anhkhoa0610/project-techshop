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
        <div class="container-center">
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
                                    <input type="radio" name="pay">
                                    <div>
                                        <div class="label">COD</div>
                                        <div class="meta">Thanh toán khi nhận hàng</div>
                                    </div>
                                </label>
                                <label class="radio">
                                    <input type="radio" name="pay" checked>
                                    <div>
                                        <div class="label">VNPay</div>
                                        <div class="meta">Cổng thanh toán nhanh</div>
                                    </div>
                                </label>
                                <label class="radio">
                                    <input type="radio" name="pay">
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
                <aside class="summary">
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
                    <div class="divider"></div>
                    <div class="totals">
                        <div class="row total">
                            <div>Tổng phải trả</div>
                            <div>580.000₫</div>
                        </div>
                        <button class="pay-btn" type="button" id="payBtn">Thanh toán & Đặt hàng</button>
                    </div>
                </aside>
            </div>
        </div>

        <script>
            // === Load API địa chỉ Việt Nam ===
            const host = "https://provinces.open-api.vn/api/";
            const citySelect = document.getElementById("city");
            const districtSelect = document.getElementById("district");
            const wardSelect = document.getElementById("ward");

            async function loadCities() {
                const res = await fetch(host + "?depth=1");
                const data = await res.json();
                citySelect.innerHTML = '<option value="">Chọn tỉnh/thành</option>';
                data.forEach(city => {
                    citySelect.innerHTML += `<option value="${city.code}">${city.name}</option>`;
                });
            }

            async function loadDistricts(cityCode) {
                const res = await fetch(host + "p/" + cityCode + "?depth=2");
                const data = await res.json();
                districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                data.districts.forEach(d => {
                    districtSelect.innerHTML += `<option value="${d.code}">${d.name}</option>`;
                });
            }

            async function loadWards(districtCode) {
                const res = await fetch(host + "d/" + districtCode + "?depth=2");
                const data = await res.json();
                wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                data.wards.forEach(w => {
                    wardSelect.innerHTML += `<option value="${w.code}">${w.name}</option>`;
                });
            }

            citySelect.addEventListener("change", () => {
                const cityCode = citySelect.value;
                if (cityCode) loadDistricts(cityCode);
            });
            districtSelect.addEventListener("change", () => {
                const districtCode = districtSelect.value;
                if (districtCode) loadWards(districtCode);
            });

            loadCities();


            // === Sự kiện thanh toán ===
            document.getElementById("payBtn").addEventListener("click", () => {
                const name = document.getElementById("fname");
                const phone = document.getElementById("phone");
                const email = document.getElementById("email");
                const address = document.getElementById("address");

                // Xoá lỗi cũ
                [name, phone, email, address].forEach(i => i.classList.remove("error"));

                // Regex kiểm tra
                const phoneRegex = /^(0|\+84)[0-9]{9}$/;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!name.value.trim() || !phone.value.trim() || !email.value.trim() || !address.value.trim()) {
                    alert("⚠️ Vui lòng nhập đầy đủ thông tin bắt buộc!");
                    [name, phone, email, address].forEach(i => { if (!i.value.trim()) i.classList.add("error"); });
                    return;
                }

                if (!phoneRegex.test(phone.value)) {
                    alert("⚠️ Số điện thoại không hợp lệ!");
                    phone.classList.add("error");
                    return;
                }

                if (!emailRegex.test(email.value)) {
                    alert("⚠️ Email không hợp lệ!");
                    email.classList.add("error");
                    return;
                }

                const payment = document.querySelector('input[name="pay"]:checked').nextElementSibling.querySelector(".label").textContent;
                const isTDC = document.getElementById("tdc-check").checked;

                if (isTDC) {
                    alert(`✅ Bạn là sinh viên TDC — được giảm 10% khi thanh toán qua ${payment}!`);
                } else {
                    alert(`🧾 Thanh toán qua ${payment} thành công!`);
                }
            });
        </script>
    </body>



    {{-- Footer --}}
    @include('partials.footer')


</body>

</html>