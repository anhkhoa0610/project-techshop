 // Hiển thị modal khi nhấn nút "Chỉnh Sửa"
        document.querySelectorAll('.edit').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                // Reset form và xóa lỗi cũ
                document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

                var row = btn.closest('tr'); // Lấy dòng chứa nút edit được bấm

                // Gán dữ liệu vào form
                document.getElementById('edit_status').value = row.getAttribute('data-status') || '';
                document.getElementById('edit_shipping_address').value = row.getAttribute('data-shipping-address') || '';
                document.getElementById('edit_payment_method').value = row.getAttribute('data-payment-method') || '';
                document.getElementById('edit_voucher_id').value = row.getAttribute('data-voucher-id') || '';
                document.getElementById('editOrderForm').dataset.id = row.getAttribute('data-order-id');

                // Hiển thị modal
                $('#editOrderModal').modal('show');
            });
        });


        // xử lý submit form chỉnh sửa
        document.getElementById('editOrderForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const orderId = this.dataset.id;
            const url = `/api/orders/${orderId}`;
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
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: formData
            });

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Cập nhật đơn hàng thành công!',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
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
                        text: 'Lỗi không xác định',
                        confirmButtonText: 'Đóng',
                        confirmButtonColor: '#d33'
                    });
                }
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
            // Xóa lỗi cũ
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: formData
            });

            if (response.ok) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thêm đơn hàng thành công!',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
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
                            'X-CSRF-TOKEN': window.csrfToken
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Đã xóa!', data.message, 'success').then(() => location.reload());
                            } else {
                                Swal.fire('Lỗi', 'Không thể xóa đơn hàng.', 'error');
                            }
                        })
                        .catch(() => Swal.fire('Lỗi', 'Không thể kết nối đến server.', 'error'));
                }
            });
        }