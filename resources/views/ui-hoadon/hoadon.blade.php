<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TechStore - Cửa hàng công nghệ hàng đầu Việt Nam')</title>
    <meta name="description"
        content="@yield('description', 'TechStore - Chuyên bán điện thoại, laptop, tai nghe chính hãng với giá tốt nhất. Bảo hành uy tín, giao hàng nhanh toàn quốc.')">

    <link rel="stylesheet" href="{{ asset('css/index-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hoadon.css') }}">


    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
</head>

<body>

    {{-- Header --}}
    @include('partials.header')

    {{-- Nội dung từng trang --}}

    <body>
        <div class="container-center">


            <div class="invoice-container">
                <div class="invoice-header">
                    <h1>Tech Shop - Hóa đơn thanh toán</h1>
                    <div class="invoice-status">✅ Đã thanh toán thành công</div>
                </div>

                <div class="invoice-info">
                    <div class="info-section">
                        <h3>Thông tin người mua</h3>
                        <p><strong>Họ tên:</strong> Nguyễn Văn A</p>
                        <p><strong>Email:</strong> nguyenvana@example.com</p>
                        <p><strong>Số điện thoại:</strong> 0987654321</p>
                        <p><strong>Địa chỉ:</strong> 123 Lê Lợi, Quận 1, TP. Hồ Chí Minh</p>
                    </div>

                    <div class="info-section">
                        <h3>Thông tin đơn hàng</h3>
                        <p><strong>Mã đơn hàng:</strong> #INV20251018</p>
                        <p><strong>Ngày thanh toán:</strong> 18/10/2025</p>
                        <p><strong>Phương thức:</strong> VNPay</p>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><img src="https://cdn2.cellphones.com.vn/insecure/rs:fill:358:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/t/a/tai-nghe-chup-tai-sony-wh-1000xm6-_9_.png"
                                    alt="Tai nghe Bluetooth"></td>
                            <td>Tai nghe Bluetooth</td>
                            <td>1</td>
                            <td>500.000₫</td>
                            <td>500.000₫</td>
                        </tr>
                        <tr>
                            <td><img src="https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/a/i/airpods-4-2.png"
                                    alt="Chuột không dây"></td>
                            <td>Chuột không dây</td>
                            <td>2</td>
                            <td>250.000₫</td>
                            <td>500.000₫</td>
                        </tr>
                    </tbody>
                </table>

                <div class="total">Tổng cộng: 1.000.000₫</div>

                <div class="actions">
                    <button onclick="window.print()">🖨️ In hóa đơn</button>
                    <button onclick="downloadPDF()">📄 Tải hóa đơn (PDF)</button>
                    <button onclick="window.location.href='index.html'">🏠 Quay lại trang chủ</button>
                </div>
            </div>
        </div>

        <script>
            function downloadPDF() {
                alert("Chức năng tải PDF đang được phát triển!");
            }
        </script>

    </body>



    {{-- Footer --}}
    @include('partials.footer')


</body>

</html>