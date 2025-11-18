// Xử lý khi click nút Edit
//Mở modal Edit
$(document).on('click', '.edit', function () {
    const row = this;
    // xóa đi các lỗi cũ sau khi click
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

    $('#category_name').val(row.getAttribute('data-category-name') || '');
    $('#description').val(row.getAttribute('data-category-description') || '');

    const imageFile = row.getAttribute('data-cover-image');
    const preview = document.getElementById('preview_image');

    if (imageFile && imageFile.trim() !== '') {
        preview.src = `/uploads/${imageFile}`;
    } else {
        preview.src = `/images/place-holder.jpg`;
    }

    document.getElementById('edit_cover_image').value = '';

    document.getElementById('editCategoryForm').dataset.id = row.getAttribute('data-category-id');

    $('#editCategoryModal').modal('show');
});

//Xem ảnh khi thêm trong edit
document.getElementById('edit_cover_image').addEventListener('change', function (e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview_image');

    if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
            preview.src = event.target.result;
        };
        reader.readAsDataURL(file);
    }
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
    const imageInput = document.getElementById('edit_cover_image');
    
    // 1. Kiểm tra xem người dùng có chọn file mới hay không
    if (imageInput.files.length > 0) {
        // 2. Nếu có, thêm file đó vào formData
        formData.append('cover_image', imageInput.files[0]);
    }

    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
                text: 'Không thể sửa danh mục này, vui lòng thử lại sau',
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

// Xem ảnh sau khi thêm
document.getElementById('add_cover_image').addEventListener('change', function (e) {
    const file = e.target.files[0];
    const preview = document.getElementById('add_preview_image');
    if (file) {
        const reader = new FileReader();
        reader.onload = function (event) {
            preview.src = event.target.result;
        };
        reader.readAsDataURL(file);
    }
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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


function formatCurrency(value) {
    const number = parseFloat(value);
    if (isNaN(number)) return '0 ₫';
    return number.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
}