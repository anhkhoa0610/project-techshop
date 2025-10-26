document.addEventListener('DOMContentLoaded', function () {

    // ==================== Edit Voucher ====================
    const editForm = document.getElementById('editVoucherForm');
    if (editForm) {
        // submit edit
        editForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            const url = `/api/vouchers/${id}`;
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('code', document.getElementById('edit_code').value);
            formData.append('discount_type', document.getElementById('edit_discount_type').value);
            formData.append('discount_value', document.getElementById('edit_discount_value').value);
            formData.append('start_date', document.getElementById('edit_start_date').value);
            formData.append('end_date', document.getElementById('edit_end_date').value);
            formData.append('status', document.getElementById('edit_status').value);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.csrfToken || '' },
                    body: formData
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    Swal.fire('Thành công!', 'Cập nhật Voucher thành công.', 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Lỗi', data.message || 'Cập nhật thất bại.', 'error');
                }
            } catch (err) {
                Swal.fire('Lỗi', 'Không thể kết nối server.', 'error');
            }
        });

    }

    // ==================== Add Voucher ====================
    const addForm = document.getElementById('addVoucherForm');
    const closeBtn = document.getElementById('close');

    if (addForm) {
        addForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const url = '/api/vouchers';
            const formData = new FormData(this);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.csrfToken || '' },
                    body: formData
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    Swal.fire('Thành công!', 'Thêm Voucher thành công.', 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Lỗi', data.message || 'Thêm thất bại.', 'error');
                }
            } catch (err) {
                Swal.fire('Lỗi', 'Không thể kết nối server.', 'error');
            }
        });
    }

    // reset add form
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            if (addForm) addForm.reset();
        });
    }

    // ==================== View Voucher ====================
    document.querySelectorAll('.view').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const row = btn.closest('tr');
            if (!row) return;

            document.getElementById('view_logo').src = row.getAttribute('data-logo')
                ? '/uploads/' + row.getAttribute('data-logo')
                : '/uploads/voucher.jpg';
            document.getElementById('view_code').textContent = row.getAttribute('data-Code') || '';
            document.getElementById('view_discount_type').textContent = row.getAttribute('data-discount_type') || '';
            document.getElementById('view_discount_value').textContent = row.getAttribute('data-discount_value') || '';
            document.getElementById('view_start_date').textContent = row.getAttribute('data-start_date') || '';
            document.getElementById('view_end_date').textContent = row.getAttribute('data-end_date') || '';
            document.getElementById('view_created_at').textContent = row.getAttribute('data-created_at') || '';
            document.getElementById('view_updated_at').textContent = row.getAttribute('data-updated_at') || '';
            // Xử lý trạng thái
            const status = row.getAttribute('data-status');
            const $status = $('#view_status');
            if (status === 'active') {
                $status.text('Active')
                    .removeClass()
                    .addClass('badge badge-success');
            } else {
                $status.text('Inactive')
                    .removeClass()
                    .addClass('badge badge-secondary');
            }

            $('#viewVoucherModal').modal('show');
        });
    });

    // ==================== Edit button ====================
    document.querySelectorAll('.edit').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const row = btn.closest('tr');
            if (!row) return;

            document.getElementById('edit_code').value = row.getAttribute('data-Code') || '';
            document.getElementById('edit_discount_type').value = row.getAttribute('data-discount_type') || '';
            document.getElementById('edit_discount_value').value = row.getAttribute('data-discount_value') || '';
            document.getElementById('edit_start_date').value = row.getAttribute('data-start_date') || '';
            document.getElementById('edit_end_date').value = row.getAttribute('data-end_date') || '';
            document.getElementById('edit_status').value = row.getAttribute('data-status') || '';

            editForm?.setAttribute('data-id', row.getAttribute('data-Voucher-id'));
            $('#editVoucherModal').modal('show');
        });
    });


    // ==================== Tooltip ====================
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
    tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

});

    // ==================== Delete Voucher ====================
    function confirmDelete(id) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc chắn muốn xóa Voucher này không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/api/vouchers/${id}`, {
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
                            Swal.fire('Lỗi', 'Không thể xóa Voucher.', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Lỗi', 'Không thể kết nối đến server.', 'error'));
            }
        });
    }