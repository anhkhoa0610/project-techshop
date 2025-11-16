document.addEventListener('DOMContentLoaded', function () {
    $(document).on('click', '#edit-supplier-btn', function () {
        const row = this;
        const logoUrl = row.getAttribute('data-logo-url');
        const placeholderUrl = row.getAttribute('data-placeholder-url');
        $('#edit_name').val(row.getAttribute('data-name') || '');
        $('#edit_description').val(row.getAttribute('data-description') || '');
        $('#edit_email').val(row.getAttribute('data-email') || '');
        $('#edit_phone').val(row.getAttribute('data-phone') || '');
        $('#edit_address').val(row.getAttribute('data-address') || '');
        $('#edit_logo').val('');
        $('#edit_logo_preview').attr('src', logoUrl ? logoUrl : placeholderUrl);

        document.getElementById('editSupplierForm').dataset.id = row.getAttribute('data-supplier-id');
        console.log(logoUrl);

        $('#editSupplierModal').modal('show');
    });

    // ==================== Edit Supplier ====================
    const editForm = document.getElementById('editSupplierForm');
    const editLogo = document.getElementById('edit_logo');

    if (editForm) {

        // submit edit
        editForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            const url = `/api/suppliers/${id}`;
            const formData = new FormData();
            formData.append('_method', 'PUT');
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    Swal.fire('Thành công!', 'Cập nhật Nhà Cung Cấp thành công.', 'success')
                        .then(() => location.reload());
                }
                else if (data.errors) {
                    // Hiển thị lỗi dưới mỗi input
                    for (const [key, messages] of Object.entries(data.errors)) {
                        const errorElement = document.getElementById(`error_edit_${key}`);
                        if (errorElement) {
                            errorElement.textContent = messages[0];
                        }
                    }
                }
                else {
                    Swal.fire('Lỗi', data.message || 'Cập nhật thất bại.', 'error');
                }
            } catch (err) {
                Swal.fire('Lỗi', 'Không thể kết nối server.', 'error');
            }
        });

        // preview logo edit
        if (editLogo) {
            editLogo.addEventListener('change', function () {
                const [file] = this.files;
                if (file) document.getElementById('edit_logo_preview').src = URL.createObjectURL(file);
            });
        }
        // reset edit form
        document.getElementById('closeEdit')?.addEventListener('click', function () {
            editForm.reset();
            document.getElementById('edit_logo_preview').src = '/uploads/place-holder.jpg';
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        });
    }

    document.querySelector('#btn-add-supplier').addEventListener('click', function () {
        // Reset form
        $('#addSupplierModal').modal('show');
    });
    // ==================== Add Supplier ====================
    const addForm = document.getElementById('addSupplierForm');
    const addLogo = document.getElementById('add_logo');
    const addLogoPreview = document.getElementById('add_logo_preview');
    const closeBtn = document.getElementById('closeAddSupplier');
    const placeholder = '/uploads/place-holder.jpg';

    // Xử lý submit thêm nhà cung cấp
    if (addForm) {
        addForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const url = '/api/suppliers';
            const formData = new FormData(this);

            // Xóa lỗi cũ trước khi submit
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    Swal.fire('Thành công!', 'Thêm nhà cung cấp thành công.', 'success')
                        .then(() => location.reload());
                } else if (data.errors) {
                    // Hiển thị lỗi chi tiết
                    for (const [key, messages] of Object.entries(data.errors)) {
                        const errorElement = document.getElementById(`error_add_${key}`);
                        if (errorElement) {
                            errorElement.textContent = messages[0];
                        }
                    }
                } else {
                    Swal.fire('Lỗi', data.message || 'Thêm thất bại.', 'error');
                }
            } catch (err) {
                Swal.fire('Lỗi', 'Không thể kết nối đến server.', 'error');
            }
        });
    }

    // reset add form
    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            addForm?.reset();
            if (addLogoPreview) addLogoPreview.src = placeholder;
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        });
    }

    // Hiển thị preview ảnh khi chọn
    if (addLogo) {
        addLogo.addEventListener('change', function () {
            const [file] = this.files;
            if (file && addLogoPreview) addLogoPreview.src = URL.createObjectURL(file);
        });
    }

    // Khi bấm nút mở modal thêm mới
    document.getElementById('addNewSupplierBtn')?.addEventListener('click', function () {
        addForm?.reset();
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
                        Swal.fire('Lỗi', 'Không thể xóa nhà phân phối.', 'error');
                    }
                })
                .catch(() => Swal.fire('Lỗi', 'Không thể kết nối đến server.', 'error'));
        }
    });
}