<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

    {{-- Nội dung từng trang --}}

    <body>
        <div class="cartp-container">
            <div class="cartp-header">🛍️ Giỏ hàng của bạn</div>

            <div class="cartp-items">
                <div class="cartp-item">
                    <img src="https://i.imgur.com/6oHix35.jpg" alt="Áo Thun">
                    <div>
                        <h3>Áo Thun Trắng</h3>
                        <p>Mã: AT001</p>
                    </div>
                    <div class="cartp-quantity">
                        <input type="number" value="1" min="1">
                    </div>
                    <div class="cartp-price" data-price="200000">200.000đ</div>
                    <button class="cartp-remove">&times;</button>
                </div>

                <div class="cartp-item">
                    <img src="https://i.imgur.com/tGbaZCY.jpg" alt="Quần Jeans">
                    <div>
                        <h3>Quần Jeans Xanh</h3>
                        <p>Mã: QJ002</p>
                    </div>
                    <div class="cartp-quantity">
                        <input type="number" value="1" min="1">
                    </div>
                    <div class="cartp-price" data-price="350000">350.000đ</div>
                    <button class="cartp-remove">&times;</button>
                </div>

                <div class="cartp-item">
                    <img src="https://i.imgur.com/3fWl1VY.jpg" alt="Giày Sneaker">
                    <div>
                        <h3>Giày Sneaker Trắng</h3>
                        <p>Mã: GS003</p>
                    </div>
                    <div class="cartp-quantity">
                        <input type="number" value="1" min="1">
                    </div>
                    <div class="cartp-price" data-price="600000">600.000đ</div>
                    <button class="cartp-remove">&times;</button>
                </div>
            </div>

            <div class="cartp-footer">
                <div class="cartp-total">Tổng cộng: <span id="cartp-total">0đ</span></div>
                <button class="cartp-checkout">Thanh toán ngay</button>
            </div>
        </div>

        <script>
            function cartpUpdateTotal() {
                const items = document.querySelectorAll('.cartp-item');
                let total = 0;

                items.forEach(item => {
                    const price = parseInt(item.querySelector('.cartp-price').dataset.price);
                    const quantity = parseInt(item.querySelector('input').value);
                    total += price * quantity;
                });

                document.getElementById('cartp-total').textContent = total.toLocaleString('vi-VN') + 'đ';
            }

            document.querySelectorAll('.cartp-quantity input').forEach(input => {
                input.addEventListener('input', cartpUpdateTotal);
            });

            document.querySelectorAll('.cartp-remove').forEach(btn => {
                btn.addEventListener('click', e => {
                    const item = e.target.closest('.cartp-item');
                    const productName = item.querySelector('h3').textContent;

                    const confirmDelete = confirm(`Bạn có chắc muốn xóa "${productName}" khỏi giỏ hàng không?`);
                    if (confirmDelete) {
                        item.remove();
                        cartpUpdateTotal();
                    }
                });
            });

            cartpUpdateTotal();
        </script>
    </body>



    {{-- Footer --}}
    @include('partials.footer')


</body>

</html>