document.addEventListener('DOMContentLoaded', function () {
    // Hiển thị modal sửa và đổ dữ liệu vào form
    document.querySelectorAll('.edit').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var row = btn.closest('tr');

            document.getElementById('edit_logo').value = '';
            const logo = row.getAttribute('data-logo');
            const logoPreview = document.getElementById('edit_logo_preview');
            if (logo) {
                logoPreview.src = '/uploads/' + logo;
            } else {
                logoPreview.src = '/uploads/place-holder.jpg';
            }

            document.getElementById('edit_name').value = row.getAttribute('data-name') || '';
            document.getElementById('edit_email').value = row.getAttribute('data-email') || '';
            document.getElementById('edit_phone').value = row.getAttribute('data-phone') || '';
            document.getElementById('edit_address').value = row.getAttribute('data-address') || '';
            document.getElementById('edit_description').value = row.getAttribute('data-description') || '';

            document.getElementById('editSupplierForm').dataset.id = row.getAttribute('data-supplier-id');

            $('#editSupplierModal').modal('show');
        });
    });

    // Xem trước ảnh khi chọn file sửa
    document.getElementById('edit_logo').addEventListener('change', function (e) {
        const [file] = this.files;
        if (file) {
            document.getElementById('edit_logo_preview').src = URL.createObjectURL(file);
        }
    });

    // Xem trước ảnh khi chọn file thêm mới
    document.getElementById('add_logo').addEventListener('change', function (e) {
        const [file] = this.files;
        if (file) {
            document.getElementById('add_logo_preview').src = URL.createObjectURL(file);
        }
    });

    // Xử lý submit form sửa
    document.getElementById('editSupplierForm').addEventListener('submit', async function (e) {
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
        const fileInput = document.getElementById('edit_logo');
        if (fileInput.files.length > 0) {
            formData.append('logo', fileInput.files[0]);
        }

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken || ''
            },
            body: formData
        });

        if (response.ok) {
            alert('Cập nhật Nhà Phân Phối thành công!');
            $('#editSupplierModal').modal('hide');
            location.reload();
        } else {
            const err = await response.json();
            alert('Cập nhật thất bại: ' + (err.message || 'Lỗi không xác định'));
        }
    });


    // Xử lý submit form thêm mới
    document.getElementById('close').addEventListener('click', function () {
        document.getElementById('addSupplierForm').reset();
        document.getElementById('add_logo_preview').src = '/uploads/place-holder.jpg';
    });

    document.getElementById('addNewSupplierBtn').addEventListener('click', function (e) {
        document.getElementById('add_logo_preview').src = '/uploads/place-holder.jpg';
    });

    document.getElementById('addSupplierForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const url = '/api/suppliers';
        const formData = new FormData(this);

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken || ''
            },
            body: formData
        });

        if (response.ok) {
            alert('Thêm nhà phân phối thành công!');
            $('#addSupplierModal').modal('hide');
            location.reload();
        } else {
            const err = await response.json();
            alert('Thêm thất bại: ' + (err.message || 'Lỗi không xác định'));
        }
    });

    // Xử lý xóa với SweetAlert2
    window.confirmDelete = function (supplierId) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc chắn muốn xóa nhà cung cấp này không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + supplierId).submit();
            }
        });
    }

    document.querySelectorAll('.view').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var row = btn.closest('tr');
            document.getElementById('view_logo').src = row.getAttribute('data-logo') ? '/uploads/' + row.getAttribute('data-logo') : '/uploads/place-holder.jpg';
            document.getElementById('view_name').textContent = row.getAttribute('data-name') || '';
            document.getElementById('view_email').textContent = row.getAttribute('data-email') || '';
            document.getElementById('view_phone').textContent = row.getAttribute('data-phone') || '';
            document.getElementById('view_address').textContent = row.getAttribute('data-address') || '';
            document.getElementById('view_description').textContent = row.getAttribute('data-description') || '';
            $('#viewSupplierModal').modal('show');
        });
    });
});