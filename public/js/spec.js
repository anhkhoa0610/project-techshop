/**
 * Tải danh sách sản phẩm vào các dropdown
 * Giả định bạn có một API route tại /api/products
 */
async function loadProducts() {
    try {
        const response = await fetch('/api/products-spec');
        if (!response.ok) {
            console.error('Không thể tải danh sách sản phẩm.');
            return;
        }

        const result = await response.json();

        // FIX QUAN TRỌNG: Bắt cả 2 kiểu JSON
        const products = Array.isArray(result)
            ? result
            : (result.data || []);

        const addSelect = document.getElementById('add_product_id');
        const editSelect = document.getElementById('edit_product_id');

        if (!addSelect || !editSelect) return;

        addSelect.innerHTML = '<option value="">-- Chọn sản phẩm --</option>';
        editSelect.innerHTML = '<option value="">-- Chọn sản phẩm --</option>';

        products.forEach(product => {
            const option = `<option value="${product.product_id}">${product.product_name}</option>`;
            addSelect.insertAdjacentHTML('beforeend', option);
            editSelect.insertAdjacentHTML('beforeend', option);
        });

    } catch (err) {
        console.error('Lỗi khi tải sản phẩm:', err);
    }
}


document.addEventListener('DOMContentLoaded', function () {

    // Tải sản phẩm ngay khi trang được load
    loadProducts();

    // ==================== Mở Modal Thêm Spec ====================
    // SỬA LỖI LIVEWIRE: Dùng event delegation cho nút bên trong Livewire
    $(document).on('click', '#btn-add-spec', function () {
        const addForm = document.getElementById('addSpecForm');
        if (addForm) {
            addForm.reset();
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        }
        $('#addSpecModal').modal('show');
    });

    // ==================== Xử lý Form Thêm Spec ====================
    const addForm = document.getElementById('addSpecForm');

    // Hàm reset nút (đặt ở ngoài hoặc trong scope đều được, nhưng nên để ngoài để dùng chung)
    function resetButton(btn, originalText) {
        if (btn) {
            btn.disabled = false;
            btn.textContent = originalText;
        }
    }

    if (addForm) {
        addForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            // 1. Lấy nút submit và lưu trạng thái cũ
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent; // Lưu chữ "Thêm mới"

            // 2. Disable và hiện loading
            submitBtn.disabled = true;
            submitBtn.textContent = 'Đang xử lý...';

            const url = '/api/specs';
            const formData = new FormData(this);

            // Xóa lỗi cũ
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
                    // Không cần reset nút vì trang sẽ reload ngay sau đó
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: 'Thêm Spec thành công.',
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
                    // Có lỗi thì phải mở lại nút để sửa
                    resetButton(submitBtn, originalText);
                } else {
                    // Lỗi logic khác
                    Swal.fire('Lỗi', data.message || 'Thêm thất bại.', 'error');
                    resetButton(submitBtn, originalText);
                }
            } catch (err) {
                console.error(err);
                Swal.fire('Lỗi', 'Không thể kết nối server.', 'error');
                resetButton(submitBtn, originalText);
            }
        });
    }

    // SỬA LỖI RESET FORM: Lắng nghe sự kiện "hidden" của Bootstrap modal
    $('#addSpecModal').on('hidden.bs.modal', function () {
        if (addForm) addForm.reset();
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    });


    // ==================== Mở Modal Sửa Spec ====================
    // Event delegation này đã đúng, vì .edit-spec-btn cũng do Livewire render
    $(document).on('click', '#edit-spec-btn', async function () {
        const specId = this.getAttribute('data-spec-id');
        const productId = this.getAttribute('data-product-id');
        const name = this.getAttribute('data-name');
        const value = this.getAttribute('data-value');

        // Chờ load dropdown xong rồi mới set value
        await loadProducts();

        $('#edit_product_id').val(productId || '');
        $('#edit_name').val(name || '');
        $('#edit_value').val(value || '');

        const editForm = document.getElementById('editSpecForm');
        if (editForm) {
            editForm.dataset.id = specId;
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
        }

        $('#editSpecModal').modal('show');
    });


    // ==================== Xử lý Form Sửa Spec ====================
    const editForm = document.getElementById('editSpecForm');

    if (editForm) {
        editForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            if (!id) {
                Swal.fire('Lỗi', 'Không tìm thấy ID của Spec.', 'error');
                return;
            }

            const url = `/api/specs/${id}`;
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('product_id', document.getElementById('edit_product_id').value);
            formData.append('name', document.getElementById('edit_name').value);
            formData.append('value', document.getElementById('edit_value').value);

            // Xóa lỗi cũ
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
                    Swal.fire('Thành công!', 'Cập nhật Spec thành công.', 'success')
                        .then(() => location.reload()); // Tải lại trang (hoặc dispatch Livewire event)
                } else if (data.errors) {
                    // Hiển thị lỗi chi tiết
                    for (const [key, messages] of Object.entries(data.errors)) {
                        const errorElement = document.getElementById(`error_edit_${key}`);
                        if (errorElement) {
                            errorElement.textContent = messages[0];
                        }
                    }
                } else {
                    Swal.fire('Lỗi', data.message || 'Cập nhật thất bại.', 'error');
                }
            } catch (err) {
                Swal.fire('Lỗi', 'Không thể kết nối server.', 'error');
            }
        });
    }

    // SỬA LỖI RESET FORM: Lắng nghe sự kiện "hidden" của Bootstrap modal
    $('#editSpecModal').on('hidden.bs.modal', function () {
        if (editForm) editForm.reset();
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    });

    // ==================== Tooltip (Giữ nguyên) ====================
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
    tooltipTriggerList.map(el => new bootstrap.Tooltip(el));

});

// ==================== Xử lý Nút Xóa Spec ====================
// (Giữ nguyên, hàm này đã đúng)
function confirmDelete(id) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc chắn muốn xóa Spec này không?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/specs/${id}`, {
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
                        Swal.fire('Lỗi', data.message || 'Không thể xóa Spec.', 'error');
                    }
                })
                .catch(() => Swal.fire('Lỗi', 'Không thể kết nối đến server.', 'error'));
        }
    });
}