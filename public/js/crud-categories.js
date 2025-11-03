// Xử lý khi click nút Edit
document.querySelectorAll('.edit').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
        // Xóa lỗi cũ
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        e.preventDefault();
        var row = btn.closest('tr');

        document.getElementById('category_name').value = row.getAttribute('data-category-name') || '';
        document.getElementById('description').value = row.getAttribute('data-category-description') || '';
        document.getElementById('editCategoryForm').dataset.id = row.getAttribute('data-category-id');

        $('#editCategoryModal').modal('show');
    });
});



// Xử lý submit formn edit
document.getElementById('editCategoryForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const id = this.dataset.id;
    const url = `/api/categories/${id}`;

    const formData = new FormData();

    formData.append('_method', 'PUT');
    formData.append('category_name', document.getElementById('category_name').value);
    formData.append('description', document.getElementById('description').value);

    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN':window.csrfToken
        },
        body: formData
    });


    if (response.ok) {
        Swal.fire({
            icon: 'success',
            title: 'Cập nhật danh mục thành công!',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        }).then(() => location.reload());
        $('#editCategoryModal').modal('hide');
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
                title: 'Cập nhật danh mục thất bại',
                text: 'Đã xảy ra lỗi không xác định',
                confirmButtonText: 'Đóng',
                confirmButtonColor: '#d33'
            });
        }
    }
});

// Hiển thị modal khi nhấn nút "Thêm Mới danh mục"
document.querySelector('.add-new').addEventListener('click', function () {
    // Reset form
    // Xóa lỗi cũ
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    $('#addCategoryModal').modal('show');
});


// Xử lý submit form thêm mới danh mục
document.getElementById('addCategoryForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const url = '/api/categories';
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
            title: 'Thêm danh mục thành công!',
            confirmButtonText: 'OK',
            confirmButtonColor: '#3085d6'
        }).then(() => {
            location.reload();
        });
        $('#addCategoryModal').modal('hide');
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
                title: 'Thêm danh mục thất bại',
                text: 'Lỗi không xác định',
                confirmButtonText: 'Đóng',
                confirmButtonColor: '#d33'
            });
        }
    }
});

// Xử lý xóa danh mục
function confirmDelete(id) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc chắn muốn xóa danh mục này không?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/categories/${id}`, {
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
                        Swal.fire('Lỗi', 'Không thể xóa danh mục.', 'error');
                    }
                })
                .catch(() => Swal.fire('Lỗi', 'Không thể kết nối đến server.', 'error'));
        }
    });
}

// Hiển thị modal khi nhấn nút "Xem" chi tiết đơn hàng
document.querySelectorAll('.view').forEach(btn => {
    btn.addEventListener('click', e => {
        e.preventDefault();

        const row = btn.closest('tr');
        document.getElementById('view_category_id').textContent = row.getAttribute('data-category-id') || '';
        document.getElementById('view_category_name').textContent = row.getAttribute('data-category-name') || '';
        document.getElementById('view_description').textContent = row.getAttribute('data-category-description') || '';
        // Hiển thị modal
        $('#viewCategoryModal').modal('show');
    });
});
function formatCurrency(value) {
    const number = parseFloat(value);
    if (isNaN(number)) return '0 ₫';
    return number.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
}