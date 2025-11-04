
// Add to cart functionality

window.csrfToken = "{{ csrf_token() }}";
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
    if (!csrfToken) return alert('Không tìm thấy CSRF Token!');

    const itemName = elementToDelete?.querySelector('h3')?.textContent || "sản phẩm này";
    if (!confirm(`🗑️ Bạn có chắc muốn xóa "${itemName}" khỏi giỏ hàng không?`)) return;

    try {
        const res = await fetch(`/cart/${cartId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken }
        });

        if (res.ok) {
            elementToDelete.remove();
            cartpUpdateTotal?.();
            alert('✅ Xóa thành công!');
            setTimeout(() => location.reload(), 500);

        } else {
            const data = await res.json().catch(() => ({}));
            alert(`❌ Lỗi xóa: ${data.message || 'Không rõ nguyên nhân'}`);
        }
    } catch (err) {
        console.error('Lỗi khi xóa:', err);
        alert('⚠️ Có lỗi khi kết nối máy chủ!');
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

    // bắt dữ liệu input
    document.querySelectorAll('.cartp-qty-input').forEach(input => {
        input.addEventListener('input', function () {
            const value = parseInt(this.value);
            const max = parseInt(this.max);
            const min = parseInt(this.min);
            if (value > max) {
                this.value = max;

            }
            if (isNaN(value) || value < min) {
                this.value = min;
            }

        });
    });


    // Thanh toán
    document.querySelector('.cartp-checkout')?.addEventListener('click', () => {
        // Tạo mảng các đối tượng chứa ID và Số lượng
        const selectedItems = Array.from(document.querySelectorAll('.cartp-select:checked'))
            .map(cb => {
                const itemElement = cb.closest('.cartp-item');
                return {
                    id: itemElement.dataset.id,
                    qty: itemElement.querySelector('.cartp-qty-input').value
                };
            });

        if (selectedItems.length === 0) {
            alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán!');
            return;
        }

        // Chuyển mảng đối tượng thành chuỗi JSON
        const itemsJson = JSON.stringify(selectedItems);

        const form = document.createElement('form');
        form.method = 'POST';
        // Route Controller::checkout() nên xử lý việc nhận mảng ID này
        form.action = '/pay';
        form.innerHTML = `
                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                    <input type="hidden" name="items" value='${itemsJson}'>
                     `;
        document.body.appendChild(form);
        form.submit();
    });


    // Tính toán tổng tiền khi trang vừa load
    cartpUpdateTotal();
});
