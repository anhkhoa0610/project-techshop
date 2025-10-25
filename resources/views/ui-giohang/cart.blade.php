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
                    <img src="{{ $item->product?->image ?? 'https://via.placeholder.com/80' }}">
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
                    Tổng cộng: <span
                        {{-- FIX LỖI 500: Cần kiểm tra Nullsafe trong hàm sum --}}
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

    <script>
        // --- 1. HÀM TÍNH TỔNG CỘNG ---
        function cartpUpdateTotal() {
            const items = document.querySelectorAll('.cartp-item');
            let total = 0;

            items.forEach(item => {
                const cb = item.querySelector('.cartp-select');
                const price = parseInt(item.querySelector('.cartp-price').dataset.price);
                const qty = parseInt(item.querySelector('.cartp-qty-input').value);
                const itemTotal = price * qty;
                
                const formattedItemTotal = itemTotal.toLocaleString('vi-VN', { maximumFractionDigits: 0 });
                item.querySelector('.cartp-price').textContent = formattedItemTotal + 'đ';

                if (cb.checked) total += itemTotal;
            });
            
            const formattedTotal = total.toLocaleString('vi-VN', { maximumFractionDigits: 0 });
            const totalElement = document.getElementById('cartp-total');
            if (totalElement) {
                totalElement.textContent = formattedTotal + 'đ';
            }
        }

        // --- 2. HÀM XÓA MỤC GIỎ HÀNG (FETCH API) ---
        /**
         * Gửi yêu cầu DELETE đến route /cart/{cartId} và cập nhật giao diện.
         * @param {string} cartId ID của mục giỏ hàng (CartItem ID).
         * @param {HTMLElement} elementToDelete Phần tử HTML cần xóa khỏi DOM.
         */
        async function deleteCartItem(cartId, elementToDelete) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            // 1. Kiểm tra điều kiện cần thiết
            if (!csrfToken) {
                console.error('Lỗi: Không tìm thấy CSRF Token.');
                return;
            }

            // >> SỬA LỖI: Sử dụng ký tự Unicode để kiểm tra lỗi Blade
            const isBladeSyntaxLeaked = cartId && (cartId.toString().includes('\u007B') || cartId.toString().includes('\u007D'));

           

            const url = `/cart/${cartId}`;
            console.log(`Đang gửi yêu cầu DELETE cho mục ID: ${cartId} tại URL: ${url}`);

            try {
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                });

                // Kiểm tra Content-Type và phân tích phản hồi
                const contentType = response.headers.get("content-type");
                let data = { success: false, message: 'Lỗi không xác định.' };
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    data = await response.json();
                } else if (response.status === 405) {
                    data.message = `Lỗi HTTP 405 (Method Not Allowed). Route DELETE /cart/{id} không được công nhận.`;
                } else if (response.status === 419) {
                    data.message = `Lỗi HTTP 419 (Page Expired). CSRF Token hết hạn. Vui lòng refresh trang.`;
                }

                if (response.ok) {
                    // Xóa thành công (Server trả về 200/204)
                    console.log('Xóa thành công:', data.message);
                    if (elementToDelete && elementToDelete.parentNode) {
                        elementToDelete.remove();
                        cartpUpdateTotal(); // Cập nhật lại tổng tiền
                        console.log('Đã cập nhật giao diện.');
                    }
                } else {
                    // Xóa thất bại (Lỗi 4xx, 5xx)
                    console.error(`--- LỖI XÓA MỤC GIỎ HÀNG (HTTP ${response.status}) ---`);
                    console.error('Lỗi từ Server:', data.message || 'Không có message cụ thể.');
                    
                    // Thêm phần tử lỗi tạm thời vào DOM để người dùng thấy
                    const errorMsg = document.createElement('div');
                    errorMsg.style.cssText = 'color: #D93025; background: #FDE2E1; padding: 8px; border: 1px solid #D93025; border-radius: 4px; margin-top: 5px; font-weight: 600;';
                    errorMsg.textContent = `LỖI XÓA (HTTP ${response.status}): ${data.message}`;
                    elementToDelete.before(errorMsg);
                    setTimeout(() => errorMsg.remove(), 7000); 

                }
            } catch (error) {
                console.error('Lỗi kết nối mạng hoặc xử lý:', error);
            }
        }


        // --- 3. KHỞI TẠO SỰ KIỆN ---
        document.addEventListener('DOMContentLoaded', () => {

            // Gán sự kiện thay đổi số lượng và chọn/bỏ chọn
            document.querySelectorAll('.cartp-qty-input').forEach(i => i.addEventListener('input', cartpUpdateTotal));
            document.querySelectorAll('.cartp-select').forEach(cb => cb.addEventListener('change', cartpUpdateTotal));
            
            // Gán sự kiện cho nút XÓA 1 MỤC
            document.querySelectorAll('.cartp-remove').forEach(btn => {
                btn.addEventListener('click', () => {
                    const item = btn.closest('.cartp-item');
                    // Lấy ID từ thuộc tính data-cart-id
                    const id = btn.getAttribute('data-cart-id'); 
                    
                    // Thử xóa
                    deleteCartItem(id, item);
                });
            });

            // XÓA NHIỀU MỤC - CẦN VIẾT HÀM RIÊNG SỬ DỤNG Route::delete('/cart-items')
            const deleteSelectedBtn = document.querySelector('.cartp-delete-selected');
            if (deleteSelectedBtn) {
                deleteSelectedBtn.addEventListener('click', () => {
                     console.warn('Chức năng "Xóa mục đã chọn" đã được định nghĩa route, nhưng cần phương thức JS riêng để gửi mảng IDs.');
                });
            }

            // Thanh toán
            document.querySelector('.cartp-checkout')?.addEventListener('click', () => {
                const selectedIds = Array.from(document.querySelectorAll('.cartp-select:checked'))
                    // Đã sửa: Dùng .dataset.id đã được gán bằng $item->cart_id
                    .map(cb => cb.closest('.cartp-item').dataset.id);

                if (selectedIds.length === 0) {
                    console.warn('Vui lòng chọn ít nhất một sản phẩm để thanh toán!');
                    return;
                }

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/pay';
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="ids" value="${selectedIds.join(',')}">
                `;
                document.body.appendChild(form);
                form.submit();
            });

            // Tính toán tổng tiền khi trang vừa load
            cartpUpdateTotal(); 
        });
    </script>
</body>

</html>
