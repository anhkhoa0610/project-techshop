document.addEventListener('DOMContentLoaded', function () {
    // ==================== 1. Mở Modal Edit ====================
    $(document).on('click', '.edit-supplier-btn', function () {
        const btn = this; // Dùng biến btn cho rõ nghĩa
        const supplierId = btn.getAttribute('data-supplier-id');
        const logoUrl = btn.getAttribute('data-logo-url');
        const placeholderUrl = btn.getAttribute('data-placeholder-url');
        $('#edit_original_updated_at').val(btn.getAttribute('data-updated-at') || '');

        $('#edit_name').val(btn.getAttribute('data-name') || '');
        $('#edit_description').val(btn.getAttribute('data-description') || '');
        $('#edit_email').val(btn.getAttribute('data-email') || '');
        $('#edit_phone').val(btn.getAttribute('data-phone') || '');
        $('#edit_address').val(btn.getAttribute('data-address') || '');

        // Reset file input và ảnh preview
        $('#edit_logo').val('');
        $('#edit_logo_preview').attr('src', logoUrl ? logoUrl : placeholderUrl);

        // Gán ID vào form để dùng khi submit
        document.getElementById('editSupplierForm').dataset.id = supplierId;

        $('#editSupplierModal').modal('show');
    });

    // ==================== 2. Submit Edit Form ====================
    const editForm = document.getElementById('editSupplierForm');
    const editLogo = document.getElementById('edit_logo');

    if (editForm) {
        editForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            // UX: Khóa nút submit để tránh double click
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang lưu...';

            const id = this.dataset.id;
            const url = `/api/suppliers/${id}`;
            const formData = new FormData();

            // Xóa thông báo lỗi cũ
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

            formData.append('_method', 'PUT');

            // QUAN TRỌNG: Gửi thời gian cập nhật gốc lên server
            const originalUpdatedAt = document.getElementById('edit_original_updated_at').value;
            formData.append('original_updated_at', originalUpdatedAt);

            formData.append('name', document.getElementById('edit_name').value);
            formData.append('description', document.getElementById('edit_description').value);
            formData.append('email', document.getElementById('edit_email').value);
            formData.append('phone', document.getElementById('edit_phone').value);
            formData.append('address', document.getElementById('edit_address').value);

            if (editLogo && editLogo.files.length > 0) {
                formData.append('logo', editLogo.files[0]);
            }

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    },
                    body: formData
                });

                const data = await response.json();

                // === XỬ LÝ CONFLICT (409) ===
                if (response.status === 409) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Dữ liệu không đồng bộ!',
                        text: data.message || 'Dữ liệu đã thay đổi bởi người khác.',
                        confirmButtonText: 'Tải lại trang',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) location.reload();
                    });
                    return; // Dừng lại tại đây
                }

                // === XỬ LÝ THÀNH CÔNG ===
                if (response.ok && data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'Cập nhật thành công.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                }
                // === XỬ LÝ LỖI VALIDATION ===
                else if (data.errors) {
                    for (const [key, messages] of Object.entries(data.errors)) {
                        const errorElement = document.getElementById(`error_edit_${key}`);
                        if (errorElement) errorElement.textContent = messages[0];
                    }
                    resetBtn(submitBtn, originalText);
                }
                // === LỖI KHÁC ===
                else {
                    Swal.fire('Lỗi', data.message || 'Cập nhật thất bại.', 'error');
                    resetBtn(submitBtn, originalText);
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Lỗi', 'Lỗi kết nối server.', 'error');
                resetBtn(submitBtn, originalText);
            }
        });

        // Preview logo khi chọn file mới
        if (editLogo) {
            editLogo.addEventListener('change', function () {
                const [file] = this.files;
                if (file) document.getElementById('edit_logo_preview').src = URL.createObjectURL(file);
            });
        }

        // Reset form khi đóng modal (bắt sự kiện của Bootstrap Modal)
        $('#editSupplierModal').on('hidden.bs.modal', function () {
            editForm.reset();
            document.getElementById('edit_logo_preview').src = '/uploads/place-holder.jpg';
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

            // Reset nút submit nếu bị kẹt
            const btn = editForm.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled = false;
                btn.textContent = 'Lưu thay đổi';
            }
        });
    }

    // Helper function
    function resetBtn(btn, text) {
        btn.disabled = false;
        btn.textContent = text;
    }
    // ==================== Add Supplier ====================
    const addForm = document.getElementById('addSupplierForm');
    const addLogo = document.getElementById('add_logo');
    const addLogoPreview = document.getElementById('add_logo_preview');
    const placeholder = '/uploads/place-holder.jpg';

    // Hàm tiện ích để mở lại nút (Clean Code)
    function resetSubmitButton(btn, originalText) {
        if (btn) {
            btn.disabled = false;
            btn.textContent = originalText;
        }
    }

    // Xử lý submit thêm nhà cung cấp
    if (addForm) {
        addForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            // 1. Lấy nút submit và khóa lại
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang xử lý...';

            const url = '/api/suppliers';
            const formData = new FormData(this);

            // 2. Xóa thông báo lỗi cũ
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || window.csrfToken || ''
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Thành công: Hiện thông báo và reload
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'Thêm nhà cung cấp thành công.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else if (data.errors) {
                    // Lỗi Validation (422)
                    for (const [key, messages] of Object.entries(data.errors)) {
                        const errorElement = document.getElementById(`error_add_${key}`);
                        if (errorElement) {
                            errorElement.textContent = messages[0];
                        }
                    }
                    // Mở lại nút để sửa lỗi
                    resetSubmitButton(submitBtn, originalText);
                } else {
                    // Lỗi logic khác
                    Swal.fire('Lỗi', data.message || 'Thêm thất bại.', 'error');
                    resetSubmitButton(submitBtn, originalText);
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Lỗi', 'Không thể kết nối đến server.', 'error');
                resetSubmitButton(submitBtn, originalText);
            }
        });
    }

    // Hiển thị preview ảnh khi chọn file (Add Modal)
    if (addLogo) {
        addLogo.addEventListener('change', function () {
            const [file] = this.files;
            if (file && addLogoPreview) {
                addLogoPreview.src = URL.createObjectURL(file);
            }
        });
    }

    // Reset form hoàn toàn khi đóng Modal (Sử dụng sự kiện chuẩn của Bootstrap)
    $('#addSupplierModal').on('hidden.bs.modal', function () {
        if (addForm) addForm.reset();
        if (addLogoPreview) addLogoPreview.src = placeholder;
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    });

    // ==================== View Supplier ====================
    document.querySelectorAll('.view').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const row = btn.closest('tr');
            if (!row) return;

            document.getElementById('view_logo').src = row.getAttribute('data-logo')
                ? '/uploads/' + row.getAttribute('data-logo')
                : '/uploads/place-holder.jpg';
            document.getElementById('view_name').textContent = row.getAttribute('data-name') || '';
            document.getElementById('view_email').textContent = row.getAttribute('data-email') || '';
            document.getElementById('view_phone').textContent = row.getAttribute('data-phone') || '';
            document.getElementById('view_address').textContent = row.getAttribute('data-address') || '';
            document.getElementById('view_description').textContent = row.getAttribute('data-description') || '';

            $('#viewSupplierModal').modal('show');
        });
    });

    // ==================== Tooltip ====================
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
    tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

});
// ==================== Delete Supplier ====================
function confirmDelete(id) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc chắn muốn xóa nhà cung cấp này không?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/suppliers/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Đã xóa!', data.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Lỗi', 'Không thể xóa nhà cung cấp.', 'error');
                    }
                })
                .catch(() => Swal.fire('Lỗi', 'Không thể kết nối đến server.', 'error'));
        }
    });
}