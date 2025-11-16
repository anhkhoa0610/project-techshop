window.csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

// --- 1. TÍNH TỔNG TIỀN VÀ HIỂN THỊ ĐƠN GIÁ TẠM THỜI ---
function cartpUpdateTotal() {
    const items = document.querySelectorAll('.cartp-item');
    let total = 0;

    items.forEach(item => {
        const cb = item.querySelector('.cartp-select');
        const price = parseInt(item.querySelector('.cartp-price').dataset.price || 0);
        const qty = parseInt(item.querySelector('.cartp-qty-input').value || 1);
        const itemTotal = price * qty;

        // Cập nhật và hiển thị TỔNG MỤC (item total)
        const itemPriceEl = item.querySelector('.cartp-price');
        // Chỉ hiển thị tổng cộng nếu checkbox được check, hoặc có thể giữ nguyên đơn giá
        itemPriceEl.innerHTML = `<strong>${itemTotal.toLocaleString('vi-VN')}đ</strong>`;

        if (cb && cb.checked) total += itemTotal; // Chỉ tính tổng những mục đã chọn
    });

    // Cập nhật tổng cuối cùng
    const totalEl = document.getElementById('cartp-total');
    if (totalEl) totalEl.textContent = total.toLocaleString('vi-VN') + 'đ';
}

// --- 2. XÓA SẢN PHẨM (AJAX) ---
async function deleteCartItem(cartId, elementToDelete) {
    if (!window.csrfToken) return alert('Không tìm thấy CSRF Token!');
    const name = elementToDelete?.querySelector('h3')?.textContent || "sản phẩm này";

    const result = await Swal.fire({
        title: `🗑️ Xác nhận xóa "${name}"?`,
        text: "Bạn sẽ không thể hoàn tác thao tác này!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Đồng ý, xóa!"
    });

    if (!result.isConfirmed) return;

    try {
        const res = await fetch(`/cart/remove/${cartId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': window.csrfToken }
        });

        if (res.ok) {
            elementToDelete.remove();
            cartpUpdateTotal();
            Swal.fire({ icon: "success", title: "Đã xóa sản phẩm!", timer: 1500, showConfirmButton: false });
        } else {
            const data = await res.json();
            Swal.fire({ icon: "error", title: "Lỗi khi xóa!", text: data.message || "Vui lòng thử lại." });
        }
    } catch (err) {
        Swal.fire({ icon: "error", title: "Không thể kết nối máy chủ!", text: err.message });
    }
}

// --- 3. CẬP NHẬT SỐ LƯỢNG (AJAX) ---
async function updateCartQuantity(cartId, newQuantity) {
    if (!window.csrfToken) return;

    try {
        const res = await fetch(`/cart/update/${cartId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken
            },
            body: JSON.stringify({ quantity: newQuantity })
        });

        if (!res.ok) {
            const data = await res.json();
            console.error('Lỗi cập nhật số lượng:', data.message);
            // Tùy chọn: Hoàn lại giá trị input nếu cập nhật thất bại
        }
    } catch (err) {
        console.error('Lỗi khi cập nhật số lượng:', err);
    }
}


// --- 4. XỬ LÝ SỰ KIỆN CHÍNH ---
document.addEventListener('DOMContentLoaded', () => {

    // Nút xóa
    document.querySelectorAll('.cartp-remove').forEach(btn =>
        btn.addEventListener('click', () => {
            const item = btn.closest('.cartp-item');
            deleteCartItem(btn.dataset.cartId, item);
        })
    );

    // Xử lý sự kiện thay đổi số lượng và checkbox
    document.querySelectorAll('.cartp-qty-input, .cartp-select').forEach(el =>
        el.addEventListener('input', (e) => {
            // Luôn cập nhật tổng tiền
            cartpUpdateTotal();

            // Nếu là input số lượng, gửi AJAX cập nhật CSDL
            if (e.target.classList.contains('cartp-qty-input')) {
                const cartId = e.target.dataset.cartId;
                const newQty = parseInt(e.target.value);

                if (cartId && newQty > 0) {
                    updateCartQuantity(cartId, newQty);
                }
            }
        })
    );
    // bắt dữ liệu input
    document.querySelectorAll('.cartp-qty-input').forEach(input => {
        input.addEventListener('input', function (e) { // Dùng 'input' để kiểm tra tức thời
            const value = parseInt(this.value);
            const max = parseInt(this.max);
            const min = parseInt(this.min) || 1; // Đảm bảo min có giá trị mặc định là 1

            // 1. Kiểm tra MIN (Bắt buộc)
            if (isNaN(value) || value < min) {
                // Nếu không hợp lệ hoặc nhỏ hơn min, đặt lại về min
                this.value = min;
            }

            // 2. Kiểm tra MAX (Chỉ giới hạn nếu lớn hơn max)
            // Nếu người dùng nhập 7, 7 < 8 (max) -> KHÔNG LÀM GÌ, 7 được giữ nguyên.
            // Nếu người dùng nhập 9, 9 > 8 (max) -> đặt lại về 8.
            else if (value > max) {
                this.value = max;

                // Tùy chọn: Hiện cảnh báo (nên dùng)
                Swal.fire({
                    icon: "warning",
                    title: "Số lượng tối đa!",
                    text: `Số lượng đặt hàng không thể vượt quá ${max}.`,
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            // --- Sau khi giá trị input đã được kiểm tra và hợp lệ ---

            // Luôn cập nhật tổng tiền trên giao diện
            cartpUpdateTotal();

            // Gửi AJAX cập nhật CSDL (Chỉ gửi nếu giá trị hợp lệ sau kiểm tra min/max)
            const finalValue = parseInt(this.value);
            const cartId = this.dataset.cartId;
            if (cartId && finalValue >= min) {
                // Gửi finalValue đã được kiểm tra (ví dụ: 7, 1, hoặc 8 nếu nhập 9)
                updateCartQuantity(cartId, finalValue);
            }
        });
    });
    // Sự kiện bấm "Thanh toán"
    const checkoutForm = document.querySelector('.cartp-footer form');
    checkoutForm?.addEventListener('submit', e => {
        e.preventDefault();

        const selectedItems = Array.from(document.querySelectorAll('.cartp-select:checked')).map(cb => {
            const item = cb.closest('.cartp-item');
            return {
                id: item.dataset.id, // CartItem ID (cart_id)
                qty: item.querySelector('.cartp-qty-input').value
            };
        });

        if (selectedItems.length === 0) {
            Swal.fire({
                icon: "warning",
                title: "Chưa chọn sản phẩm!",
                text: "Vui lòng chọn ít nhất một sản phẩm để thanh toán."
            });
            return;
        }

        // Cập nhật trường ẩn 'items' với dữ liệu JSON
        const hiddenItems = document.getElementById('selected-cart-items-data');
        hiddenItems.value = JSON.stringify(selectedItems);

        // Gửi form
        checkoutForm.submit();
    });

    // Tính tổng lần đầu khi load trang
    cartpUpdateTotal();
});