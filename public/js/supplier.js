document.addEventListener('DOMContentLoaded', function () {

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
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.csrfToken || '' },
                    body: formData
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    Swal.fire('Thành công!', 'Cập nhật Nhà Phân Phối thành công.', 'success')
                        .then(() => location.reload());
                } else {
                    Swal.fire('Lỗi', data.message || 'Cập nhật thất bại.', 'error');
                }
            } catch (err) {
                Swal.fire('Lỗi', 'Không thể kết nối server.', 'error');
            }
        });

        // preview logo edit
        if (editLogo) {
            editLogo.addEventListener('change', function (e) {
                const [file] = this.files;
                if (file) document.getElementById('edit_logo_preview').src = URL.createObjectURL(file);
            });
        }
    }

    // ==================== Add Supplier ====================
    const addForm = document.getElementById('addSupplierForm');
    const addLogo = document.getElementById('add_logo');
    const addLogoPreview = document.getElementById('add_logo_preview');
    const closeBtn = document.getElementById('close');

    if (addForm) {
        addForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const url = '/api/suppliers';
            const formData = new FormData(this);

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': window.csrfToken || '' },
                    body: formData
                });

                const data = await response.json();
                if (response.ok && data.success) {
                    Swal.fire('Thành công!', 'Thêm Nhà Phân Phối thành công.', 'success')
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
            if (addLogoPreview) addLogoPreview.src = '/uploads/place-holder.jpg';
        });
    }

    if (addLogo) {
        addLogo.addEventListener('change', function () {
            const [file] = this.files;
            if (file && addLogoPreview) addLogoPreview.src = URL.createObjectURL(file);
        });
    }

    document.getElementById('addNewSupplierBtn')?.addEventListener('click', function () {
        if (addLogoPreview) addLogoPreview.src = '/uploads/place-holder.jpg';
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

    // ==================== Edit button ====================
    document.querySelectorAll('.edit').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const row = btn.closest('tr');
            if (!row) return;

            document.getElementById('edit_logo').value = '';
            const logo = row.getAttribute('data-logo');
            document.getElementById('edit_logo_preview').src = logo ? '/uploads/' + logo : '/uploads/place-holder.jpg';

            document.getElementById('edit_name').value = row.getAttribute('data-name') || '';
            document.getElementById('edit_email').value = row.getAttribute('data-email') || '';
            document.getElementById('edit_phone').value = row.getAttribute('data-phone') || '';
            document.getElementById('edit_address').value = row.getAttribute('data-address') || '';
            document.getElementById('edit_description').value = row.getAttribute('data-description') || '';

            editForm?.setAttribute('data-id', row.getAttribute('data-supplier-id'));
            $('#editSupplierModal').modal('show');
        });
    });

    // ==================== Delete Supplier ====================
   

   

    // ==================== Tooltip ====================
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
    tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

});

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
                        'X-CSRF-TOKEN': window.csrfToken
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
