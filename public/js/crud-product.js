function decodeHtmlEntities(str) {
    const txt = document.createElement('textarea');
    txt.innerHTML = str;
    return txt.value;
}

// Utility: Phát event thông báo tất cả tab khác cần reload danh sách
function notifyProductsUpdated() {
    // Phát event qua localStorage để các tab khác biết
    localStorage.setItem('products_updated_at', new Date().getTime());
}

// Utility: Lắng nghe event từ các tab khác
window.addEventListener('storage', function (e) {
    if (e.key === 'products_updated_at') {
        // Nếu có tab khác update product, reload trang này
        location.reload();
    }
});

//Mở modal Edit
$(document).on('click', '#edit-product-btn', function () {
    const formReset = document.getElementById('editProductForm');
    formReset.reset();
    formReset.querySelectorAll('.text-danger, .error-message').forEach(e => e.innerHTML = "");
    const row = this;
    $('#edit_product_name').val(decodeHtmlEntities(row.getAttribute('data-product-name')) || '');
    $('#edit_description').val(decodeHtmlEntities(row.getAttribute('data-description')) || '');
    $('#edit_price').val(row.getAttribute('data-price') || '');
    $('#edit_stock_quantity').val(row.getAttribute('data-stock-quantity') || '');
    $('#edit_supplier_id').val(row.getAttribute('data-supplier-id') || '');
    $('#edit_category_id').val(row.getAttribute('data-category-id') || '');
    $('#edit_warranty_period').val(row.getAttribute('data-warranty-period') || '');
    $('#edit_volume_sold').val(row.getAttribute('data-volume-sold') || '');
    $('#edit_release_date').val(row.getAttribute('data-release-date') || '');
    $('#edit_embed_url_review').val(row.getAttribute('data-embed-url-review') || '');
    $('#edit_updated_at').val(row.getAttribute('data-updated-at') || '');

    const imageFile = row.getAttribute('data-cover-image');
    const preview = document.getElementById('preview_image');

    if (imageFile && imageFile.trim() !== '') {
        preview.src = `/uploads/${imageFile}`;
    } else {
        preview.src = `/images/place-holder.jpg`;
    }

    document.getElementById('edit_cover_image').value = '';

    document.getElementById('editProductForm').dataset.id = row.getAttribute('data-product-id');

    $('#editProductModal').modal('show');
});

// Hiển thị modal khi nhấn nút "Thêm Mới Sản Phẩm"
document.querySelector('#btn-register').addEventListener('click', function () {
    const formReset = document.getElementById('addProductForm');
    formReset.reset();
    formReset.querySelectorAll('.text-danger, .error-message').forEach(e => e.innerHTML = "");
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

//Thay ảnh
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

// Submit edit form
document.addEventListener('DOMContentLoaded', () => {
    const formEdit = document.getElementById('editProductForm');
    if (!formEdit) return;

    formEdit.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearFormErrors(formEdit);

        const id = formEdit.dataset.id;
        if (!id) return alert('Không xác định được sản phẩm cần sửa!');

        const submitButton = formEdit.querySelector('button[type="submit"]');
        const originalButtonHtml = submitButton.innerHTML;
        
        submitButton.disabled = true;
        submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang lưu...`;
        submitButton.classList.add('btn-loading');

        const url = `/api/products/${id}`;
        const formDataEdit = new FormData(formEdit);
        formDataEdit.append('_method', 'PUT');

        try {
            const response = await fetch(url, {
                method: 'POST', 
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formDataEdit
            });

            const data = await response.json();

            if (response.ok) {
                $('#editProductModal').modal('hide');
                Swal.fire('Cập nhật sản phẩm thành công !', data.message, 'success').then(() => {
                    notifyProductsUpdated();
                    location.reload();
                });
            } else if (data.errors) {
                Object.entries(data.errors).forEach(([field, messages]) => {
                    const errorEl = document.getElementById(`error_edit_${field}`);
                    if (errorEl) errorEl.textContent = messages[0];
                });
            } else {
                Swal.fire('Cập nhật sản phẩm thất bại!', data.message, 'error');
            }
        } catch (err) {
            Swal.fire('Lỗi không xác định !', 'Không thể kết nối đến máy chủ.', 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonHtml;
            submitButton.classList.remove('btn-loading');
        }
    });
});

//Submit thêm sản phẩm
document.addEventListener('DOMContentLoaded', () => {
    const formAdd = document.getElementById('addProductForm');
    if (!formAdd) return;

    formAdd.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearFormErrors(formAdd);

        const submitButton = formAdd.querySelector('button[type="submit"]');
        const originalButtonHtml = submitButton.innerHTML;

        submitButton.disabled = true;
        submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang thêm...`;
        submitButton.classList.add('btn-loading');

        const url = `/api/products/`;
        const formDataAdd = new FormData(formAdd);
        formDataAdd.append('_method', 'POST');

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formDataAdd
            });

            const data = await response.json();

            if (response.ok) {
                $('#addProductModal').modal('hide');
                Swal.fire('Thêm sản phẩm thành công !', data.message, 'success').then(() => {
                    notifyProductsUpdated();
                    location.reload();
                });
            } else if (data.errors) {
                // Hiển thị lỗi validation
                Object.entries(data.errors).forEach(([field, messages]) => {
                    const errorEl = document.getElementById(`error_add_${field}`);
                    if (errorEl) errorEl.textContent = messages[0];
                });
            } else {
                Swal.fire('Thêm sản phẩm thất bại!', data.message, 'error').then(() => location.reload());
            }
        } catch (err) {
            Swal.fire('Lỗi không xác định !', data.message, 'err').then(() => location.reload());

        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonHtml;
            submitButton.classList.remove('btn-loading');
        }
    });
});

//Clear lỗi
function clearFormErrors(form) {
    if (!form) return;
    form.querySelectorAll('.error-message').forEach(function (el) {
        el.textContent = '';
    });
}

//Xóa sản phẩm
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Đã xóa!', data.message, 'success').then(() => {
                            notifyProductsUpdated();
                            location.reload();
                        });
                    } else {
                        Swal.fire('Lỗi', data.message , 'error').then(() => location.reload());
                    }
                })
                .catch(() => Swal.fire('Lỗi', 'Không thể kết nối đến server.', 'error'));
        }
    });
}
