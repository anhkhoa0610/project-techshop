 // Hiển thị modal khi nhấn nút "Chỉnh sửa" chi tiết đơn hàng
        document.querySelectorAll('.edit').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();

                // Xóa lỗi cũ
                document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

                // Lấy dòng chứa nút "edit" được bấm
                const row = btn.closest('tr');

                // Gán dữ liệu vào form trong modal
                document.getElementById('edit_product_id').value = row.getAttribute('data-product-id') || '';
                document.getElementById('edit_quantity').value = row.getAttribute('data-quantity') || '';
                document.getElementById('edit_order_id').value = row.getAttribute('data-order-id') || '';

                // Lưu ID của order_detail để gửi PUT khi submit
                document.getElementById('editOrderDetailForm').dataset.id = row.getAttribute('data-detail-id');

                // Hiển thị modal
                $('#editOrderDetailModal').modal('show');
            });
        });


        // xử lý submit form chỉnh sửa
        document.getElementById('editOrderDetailForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const detailId = this.dataset.id; // id gán khi mở modal
            const url = `/api/orderDetails/${detailId}`;

            const formData = new FormData(this);
            formData.append('_method', 'PUT'); // Laravel nhận update()

            try {
                const response = await fetch(url, {
                    method: 'POST', // vẫn là POST, nhưng Laravel đọc _method = PUT
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN':  window.csrfToken
                    },
                    body: formData
                });

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cập nhật chi tiết đơn hàng thành công!',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#3085d6'
                    }).then(() => location.reload());

                    $('#editOrderDetailModal').modal('hide');
                } else {
                    const err = await response.json();
                    if (err.errors) {
                        // Xóa lỗi cũ
                        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
                        // Hiển thị lỗi mới
                        Object.keys(err.errors).forEach(field => {
                            const errorDiv = document.getElementById(`error_edit_${field}`);
                            if (errorDiv) {
                                errorDiv.textContent = err.errors[field][0];
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cập nhật chi tiết đơn hàng thất bại',
                            text: 'Đã xảy ra lỗi không xác định',
                            confirmButtonText: 'Đóng',
                            confirmButtonColor: '#d33'
                        });
                    }
                }
            } catch (error) {
                console.error('Lỗi kết nối:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi kết nối máy chủ',
                    text: 'Không thể gửi yêu cầu cập nhật',
                    confirmButtonText: 'Đóng'
                });
            }
        });

        // Hiển thị modal khi nhấn nút "Thêm mới orderdetail"
        document.querySelector('.add-new').addEventListener('click', function () {
            // Reset form
            // Xóa lỗi cũ
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            $('#addOrderDetailModal').modal('show');
        });

        // Xử lý submit form thêm mới danh mục
        document.getElementById('addOrderDetailForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const url = '/api/orderDetails';
            const formData = new FormData(this);
            // Xóa lỗi cũ
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN':  window.csrfToken
                },
                body: formData
            });

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thêm chi tiết đơn hàng thành công!',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    location.reload();
                });
                $('#addOrderDetailModal').modal('hide');

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
                        title: 'Thêm chi tiết đơn hàng thất bại',
                        text: 'Lỗi không xác định',
                        confirmButtonText: 'Đóng',
                        confirmButtonColor: '#d33'
                    });
                }
            }
        });

        // Hiển thị modal khi nhấn nút "Xem" chi tiết đơn hàng
        document.querySelectorAll('.view').forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();

                const row = btn.closest('tr');
                document.getElementById('view_product_image').src = row.getAttribute('data-product-image') ? '/uploads/' + row.getAttribute('data-product-image') : '/uploads/place-holder.jpg';
                document.getElementById('view_stock_quantity').textContent = row.getAttribute('data-detail-id') || '';
                document.getElementById('view_product_name').textContent = row.getAttribute('data-product-name') || '';
                document.getElementById('view_quantity').textContent = row.getAttribute('data-quantity') || '';
                document.getElementById('view_unit_price').textContent = formatCurrency(row.getAttribute('data-unit-price')) || '';
                document.getElementById('view_total_price').textContent = formatCurrency(row.getAttribute('data-total-price')) || '';

                // Hiển thị modal
                $('#viewOrderDetailModal').modal('show');
            });
        });
        function formatCurrency(value) {
            const number = parseFloat(value);
            if (isNaN(number)) return '0 ₫';
            return number.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
        }

        // Xử lý xóa chi tiết đơn hàng
        function confirmDelete(id) {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa chi tiết đơn hàng này không?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/orderDetails/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN':  window.csrfToken
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Đã xóa!', data.message, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Lỗi', 'Không thể xóa chi tiết đơn hàng.'.data.message, 'error');
                            }
                        })
                        .catch(() => Swal.fire('Lỗi', 'Không thể kết nối đến server.', 'error'));
                }
            });
        }