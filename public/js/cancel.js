// Lấy tất cả order-card
document.querySelectorAll('.order-card').forEach(card => {
    // Lấy trạng thái (status) và ID của đơn hàng từ data attributes
    const status = card.dataset.status;
    
    // Nếu trạng thái không phải 'processing' thì không cần thực hiện logic hiển thị nút Chi tiết/Hủy đơn ở đây
    // Lưu ý: Nếu muốn nút Chi tiết hiển thị cho TẤT CẢ trạng thái, bạn phải loại bỏ/di chuyển dòng if (status !== 'processing') return;
    

    // Lấy ID đơn hàng
    const id = card.dataset.id; 
    const actionsDiv = card.querySelector('.order-actions');

    // Hiển thị div actions
    actionsDiv.style.display = 'flex';
    actionsDiv.style.flexDirection = 'column';
    actionsDiv.style.gap = '6px';

    // Nút hiển thị status
    const statusBtn = document.createElement('button');
    statusBtn.textContent = status.charAt(0).toUpperCase() + status.slice(1);
    statusBtn.className = 'status';
    actionsDiv.appendChild(statusBtn);

    // Nút Chi tiết
    const detailBtn = document.createElement('button');
    detailBtn.textContent = 'Chi tiết';
    detailBtn.className = 'detail';
    
    // 🟢 SỬA LỖI CHUYỂN HƯỚNG: SỬ DỤNG TEMPLATE LITERALS VÀ BIẾN `id`
    detailBtn.onclick = () => {
        // Thay đổi URL để chuyển hướng người dùng
        // Giả sử route chi tiết là '/orders/ID'
        window.location.href = `/details/${id}`; // Đã sửa từ /details/{id}
    };
    actionsDiv.appendChild(detailBtn);

    // Nút Cancel / Rebuy
    if (status === 'processing' || status === 'pending') {
        const cancelBtn = document.createElement('button');
        cancelBtn.textContent = 'Hủy đơn';
        cancelBtn.className = 'cancel';
        cancelBtn.onclick = () => {
            Swal.fire({
                title: 'Bạn chắc chắn muốn hủy đơn này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hủy đơn',
                cancelButtonText: 'Quay lại',
                confirmButtonColor: '#dc3545',
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/orders/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Đã hủy đơn', data.message, 'success');
                                // Xóa phần hiển thị khỏi giao diện
                                card.remove();
                            } else {
                                Swal.fire('Lỗi', data.message, 'error');
                            }
                        })
                        .catch(() => Swal.fire('Lỗi', 'Không thể kết nối máy chủ', 'error'));
                }
            });
        };
        actionsDiv.appendChild(cancelBtn);
    } else {
        const rebuyBtn = document.createElement('button');
        rebuyBtn.textContent = 'Mua lại';
        rebuyBtn.className = 'rebuy';
        // Sử dụng biến 'id' trong nút Mua lại (nếu trạng thái khác 'processing' được xử lý ở đây)
        rebuyBtn.onclick = () => Swal.fire('Mua lại', `Bạn có thể mua lại đơn hàng #${id}`, 'success'); 
        actionsDiv.appendChild(rebuyBtn);
    }
});