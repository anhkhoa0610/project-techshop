
document.querySelectorAll('.edit').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        var row = btn.closest('tr');

        document.getElementById('product_name').value = row.getAttribute('data-product-name') || '';
        document.getElementById('description').value = row.getAttribute('data-description') || '';
        document.getElementById('price').value = row.getAttribute('data-price') || '';
        document.getElementById('stock_quantity').value = row.getAttribute('data-stock-quantity') || '';
        document.getElementById('supplier_id').value = row.getAttribute('data-supplier-id') || '';
        document.getElementById('category_id').value = row.getAttribute('data-category-id') || '';
        document.getElementById('warranty_period').value = row.getAttribute('data-warranty-period') || '';
        document.getElementById('volume_sold').value = row.getAttribute('data-volume-sold') || '';
        document.getElementById('release_date').value = row.getAttribute('data-release-date') || '';

        const imageFile = row.getAttribute('data-cover-image');
        const preview = document.getElementById('preview_image');
        if (imageFile) {
            preview.src = `/uploads/${imageFile}`;
        } else {
            preview.src = `/images/place-holder.jpg`;
        }

        // Reset input file
        document.getElementById('cover_image').value = '';

        document.getElementById('editProductForm').dataset.id = row.getAttribute('data-product-id');

        $('#editProductModal').modal('show');
    });
});

document.getElementById('cover_image').addEventListener('change', function (e) {
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

// Xử lý submit form
document.getElementById('editProductForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    clearFormErrors(this);


    const id = this.dataset.id;
    const url = `/api/products/${id}`;

    const formData = new FormData();
    formData.append('_method', 'PUT');
    formData.append('product_name', document.getElementById('product_name').value);
    formData.append('description', document.getElementById('description').value);
    formData.append('stock_quantity', document.getElementById('stock_quantity').value);
    formData.append('price', document.getElementById('price').value);
    formData.append('volume_sold', document.getElementById('volume_sold').value);
    formData.append('category_id', document.getElementById('category_id').value);
    formData.append('supplier_id', document.getElementById('supplier_id').value);
    formData.append('warranty_period', document.getElementById('warranty_period').value);
    formData.append('release_date', document.getElementById('release_date').value);
    const fileInput = document.getElementById('cover_image');
    if (fileInput.files.length > 0) {
        formData.append('cover_image', fileInput.files[0]);
    }

    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: formData
    });

    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }

    if (response.ok) {
        alert('Cập nhật sản phẩm thành công!');
        $('#editProductModal').modal('hide');
        location.reload();
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
            alert('Sửa sản phẩm thất bại: ' + (err.message || 'Lỗi không xác định'));
        }
    }
});

// Hiển thị modal khi nhấn nút "Thêm Mới Sản Phẩm"
document.querySelector('.add-new').addEventListener('click', function () {
    // Reset form
    document.getElementById('addProductForm').reset();
    document.getElementById('add_preview_image').src = "/images/place-holder.jpg";
    $('#addProductModal').modal('show');
});

// Xem trước ảnh khi chọn file
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

// Xử lý submit form thêm mới
document.getElementById('addProductForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    clearFormErrors(this);


    const url = '/api/products';
    const formData = new FormData();
    formData.append('product_name', document.getElementById('add_product_name').value);
    formData.append('description', document.getElementById('add_description').value);
    formData.append('stock_quantity', document.getElementById('add_stock_quantity').value);
    formData.append('price', document.getElementById('add_price').value);
    formData.append('volume_sold', document.getElementById('add_volume_sold').value);
    formData.append('category_id', document.getElementById('add_category_id').value);
    formData.append('supplier_id', document.getElementById('add_supplier_id').value);
    formData.append('warranty_period', document.getElementById('add_warranty_period').value);
    formData.append('release_date', document.getElementById('add_release_date').value);
    const fileInput = document.getElementById('add_cover_image');
    if (fileInput.files.length > 0) {
        formData.append('cover_image', fileInput.files[0]);
    }

    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': window.csrfToken
        },
        body: formData
    });

    if (response.ok) {
        alert('Thêm sản phẩm thành công!');
        $('#addProductModal').modal('hide');
        location.reload();
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
            alert('Thêm sản phẩm thất bại: ' + (err.message || 'Lỗi không xác định'));
        }
    }
});

function confirmDelete(id) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc chắn muốn xóa sản phẩm này không?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/products/${id}`, {
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
                        Swal.fire('Lỗi', 'Không thể xóa sản phẩm.', 'error');
                    }
                })
                .catch(() => Swal.fire('Lỗi', 'Không thể kết nối đến server.', 'error'));
        }
    });
}


document.querySelectorAll('.view').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        var row = btn.closest('tr');
        document.getElementById('view_category').textContent = row.getAttribute('data-category-name') || '';
        document.getElementById('view_supplier').textContent = row.getAttribute('data-supplier-name') || '';
        document.getElementById('view_product_image').src = row.getAttribute('data-cover-image') ? '/uploads/' + row.getAttribute('data-cover-image') : '/images/place-holder.jpg';
        document.getElementById('view_product_name').textContent = row.getAttribute('data-product-name') || '';
        document.getElementById('view_price').textContent = row.getAttribute('data-price') || '';
        document.getElementById('view_stock_quantity').textContent = row.getAttribute('data-stock-quantity') || '';
        document.getElementById('view_volume_sold').textContent = row.getAttribute('data-volume-sold') || '';
        document.getElementById('view_warranty_period').textContent = row.getAttribute('data-warranty-period') || '';
        document.getElementById('view_release_date').textContent = row.getAttribute('data-release-date') || '';
        document.getElementById('view_description').innerHTML = row.getAttribute('data-description') || '';
        $('#viewProductModal').modal('show');
    });
});

function clearFormErrors(form) {
    if (!form) return;
    // clear elements with class 'error-message' inside the form
    form.querySelectorAll('.error-message').forEach(function (el) {
        el.textContent = '';
    });
}