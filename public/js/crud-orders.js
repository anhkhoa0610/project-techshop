// Utility: Phát event thông báo tất cả tab khác cần reload danh sách
function notifyOrdersUpdated() {
    // Phát event qua localStorage để các tab khác biết
    localStorage.setItem('orders_updated_at', new Date().getTime());
}

// Utility: Lắng nghe event từ các tab khác
window.addEventListener('storage', function (e) {
    if (e.key === 'orders_updated_at') {
        // Nếu có tab khác update order, reload trang này
        location.reload();
    }
});

// Hiển thị modal khi nhấn nút "Chỉnh Sửa"
//Mở modal Edit
$(document).on('click', '.edit', function () {
    const row = this;
    // xóa đi các lỗi cũ sau khi click
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    // Lấy dữ liệu từ data-attributes
    $('#edit_status').val(row.getAttribute('data-status') || '');
    $('#edit_shipping_address').val(row.getAttribute('data-shipping-address') || '');
    $('#edit_payment_method').val(row.getAttribute('data-payment-method') || '');
    $('#edit_voucher_id').val(row.getAttribute('data-voucher-id') || '');
    $('#edit_user_id').val(row.getAttribute('data-user-id') || '');
    $('#edit_total').val(row.getAttribute('data-total') || '');

    document.getElementById('editOrderForm').dataset.id = row.getAttribute('data-order-id');

    $('#editOrderModal').modal('show');
});



// xử lý submit form chỉnh sửa
document.getElementById('editOrderForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const orderId = this.dataset.id;
    const url = `/api/orders/${orderId}`;
    const submitButton = this.querySelector('button[type="submit"]');
    
    // Disable button to prevent multiple clicks
    submitButton.disabled = true;
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Đang xử lý...';
    
    const formData = new FormData(this);
    // Xóa lỗi cũ
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    formData.append('_method', 'PUT');
    formData.append('shipping_address', document.getElementById('edit_shipping_address').value);
    formData.append('order_id', this.dataset.id);
    formData.append('status', document.getElementById('edit_status').value);
    formData.append('payment_method', document.getElementById('edit_payment_method').value);
    formData.append('voucher_id', document.getElementById('edit_voucher_id').value);
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    });

    try {
        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Cập nhật đơn hàng thành công!',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                notifyOrdersUpdated();
                location.reload();
            });

            $('#editOrderModal').modal('hide');
        } else {
            const err = await response.json();
            if (err.errors) {
                Object.keys(err.errors).forEach(field => {
                    const errorDiv = document.getElementById(`error_edit_${field}`);
                    if (errorDiv) {
                        errorDiv.textContent = err.errors[field][0];
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Cập nhật đơn hàng thất bại',
                    text: 'không thể sửa đơn hàng này, vui lòng thử lại sau',
                    confirmButtonText: 'Đóng',
                    confirmButtonColor: '#d33'
                });
            }
        }
    } finally {
        // Re-enable button after response completes
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    }
});

// Hiển thị modal khi nhấn nút "Thêm mới đơn hàng"
document.querySelector('.add-new').addEventListener('click', function () {
    // Reset form
    // Xóa lỗi cũ
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    document.getElementById('addOrderForm').reset();
    $('#addOrderModal').modal('show');
});

// Xử lý submit form thêm mới
document.getElementById('addOrderForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const url = '/api/orders';
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    
    // Disable button to prevent multiple clicks
    submitButton.disabled = true;
    const originalText = submitButton.textContent;
    submitButton.textContent = 'Đang xử lý...';
    
    // Xóa lỗi cũ
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    });

    try {
        if (response.ok) {
            Swal.fire({
                icon: 'success',
                title: 'Thêm đơn hàng thành công!',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                notifyOrdersUpdated();
                location.reload();
            });

            $('#addOrderModal').modal('hide');
        } else {
            const err = await response.json();
            if (err.errors) {
                Object.keys(err.errors).forEach(field => {
                    const errorDiv = document.getElementById(`error_add_${field}`);
                    if (errorDiv) {
                        errorDiv.textContent = err.errors[field][0];
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Thêm đơn hàng thất bại',
                    text: 'Lỗi không xác định',
                    confirmButtonText: 'Đóng',
                    confirmButtonColor: '#d33'
                });
            }
        }
    } finally {
        // Re-enable button after response completes
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    }
});

// xử lý xóa đơn hàng
function confirmDelete(id) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc chắn muốn xóa đơn hàng này không?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/orders/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Đã xóa!', data.message, 'success').then(() => {
                            notifyOrdersUpdated();
                            location.reload();
                        });
                    } else {
                        Swal.fire('Lỗi', 'Không thể xóa đơn hàng này,vui lòng thử lại sau!', 'error');
                    }
                })
                .catch(() => Swal.fire('Lỗi', 'Không thể kết nối đến server.', 'error'));
        }
    });
}