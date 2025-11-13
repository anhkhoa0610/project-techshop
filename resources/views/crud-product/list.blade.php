@extends('layouts.dashboard')
@section('content')
    <!-- Main Content -->
    <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @livewire('product-table')
        </div>
    </main>

    <!-- Modal Thêm Mới Sản Phẩm -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="addProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Thêm mới sản phẩm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Cột trái -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_product_name">Tên sản phẩm</label>
                                    <input type="text" class="form-control" id="add_product_name" name="product_name">
                                    <div class="text-danger error-message" id="error_add_product_name"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_description">Mô tả</label>
                                    <textarea class="form-control" id="add_description" name="description"></textarea>
                                    <div class="text-danger error-message" id="error_add_description"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_stock_quantity">Số lượng tồn</label>
                                    <input type="number" class="form-control" id="add_stock_quantity" name="stock_quantity">
                                    <div class="text-danger error-message" id="error_add_stock_quantity"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_release_date">Ngày phát hành</label>
                                    <input type="date" class="form-control" id="add_release_date" name="release_date">
                                    <div class="text-danger error-message" id="error_add_release_date"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_supplier_id">Nhà cung cấp</label>
                                    <select class="form-control" id="add_supplier_id" name="supplier_id">
                                        <option value="">-- Chọn nhà cung cấp --</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->supplier_id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message" id="error_add_supplier_id"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_category_id">Danh mục</label>
                                    <select class="form-control" id="add_category_id" name="category_id">
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message" id="error_add_category_id"></div>
                                </div>

                            </div>
                            <!-- Cột phải -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_cover_image">Hình ảnh</label>
                                    <div class="mt-2 text-center">
                                        <img id="add_preview_image" src="{{ asset('images/place-holder.jpg') }}"
                                            alt="Ảnh sản phẩm" style="max-width: 120px; border-radius: 6px;">
                                    </div>
                                    <input type="file" class="form-control" id="add_cover_image" name="cover_image"
                                        accept="image/*">
                                    <div class="text-danger error-message" id="error_add_cover_image"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_price">Giá</label>
                                    <input type="number" step="0.01" class="form-control" id="add_price" name="price">
                                    <div class="text-danger error-message" id="error_add_price"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_volume_sold">Đã bán</label>
                                    <input type="number" class="form-control" id="add_volume_sold" name="volume_sold">
                                    <div class="text-danger error-message" id="error_add_volume_sold"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_warranty_period">Bảo hành</label>
                                    <input type="number" class="form-control" id="add_warranty_period"
                                        name="warranty_period">
                                    <div class="text-danger error-message" id="error_add_warranty_period"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_embed_url_review">Link review</label>
                                    <input type="text" class="form-control" id="add_embed_url_review"
                                        name="embed_url_review">
                                    <div class="text-danger error-message" id="error_add_embed_url_review"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-success">Thêm mới</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal Edit Product -->
    <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Chỉnh sửa sản phẩm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Cột trái -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_product_name">Tên sản phẩm</label>
                                    <input type="text" class="form-control" id="edit_product_name" name="product_name">
                                    <div class="text-danger error-message" id="error_edit_product_name"></div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_description">Mô tả</label>
                                    <textarea class="form-control" id="edit_description" name="description"></textarea>
                                    <div class="text-danger error-message" id="error_edit_product_description"></div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_stock_quantity">Số lượng tồn</label>
                                    <input type="number" class="form-control" id="edit_stock_quantity"
                                        name="stock_quantity">
                                    <div class="text-danger error-message" id="error_edit_stock_quantity"></div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_release_date">Ngày phát hành</label>
                                    <input type="date" class="form-control" id="edit_release_date" name="release_date">
                                    <div class="text-danger error-message" id="error_edit_release_date"></div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_supplier_id">Nhà cung cấp</label>
                                    <select class="form-control" id="edit_supplier_id" name="supplier_id">
                                        <option value="">-- Chọn nhà cung cấp --</option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->supplier_id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message" id="error_edit_supplier_id"></div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_category_id">Danh mục</label>
                                    <select class="form-control" id="edit_category_id" name="category_id">
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message" id="error_edit_category_id"></div>
                                </div>

                            </div>

                            <!-- Cột phải -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_cover_image">Hình ảnh</label>
                                    <div class="mt-2 text-center">
                                        <img id="preview_image" src="" alt="Ảnh sản phẩm"
                                            style="max-width: 120px; border-radius: 6px;">
                                    </div>
                                    <input type="file" class="form-control" id="edit_cover_image" name="cover_image"
                                        accept="image/*">
                                    <div class="text-danger error-message" id="error_edit_cover_image"></div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_price">Giá</label>
                                    <input type="number" step="0.01" class="form-control" id="edit_price" name="price">
                                    <div class="text-danger error-message" id="error_edit_price"></div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_volume_sold">Đã bán</label>
                                    <input type="number" class="form-control" id="edit_volume_sold" name="volume_sold">
                                    <div class="text-danger error-message" id="error_edit_volume_sold"></div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_warranty_period">Bảo hành</label>
                                    <input type="number" class="form-control" id="edit_warranty_period"
                                        name="warranty_period">
                                    <div class="text-danger error-message" id="error_edit_warranty_period"></div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_embed_url_review">Link review</label>
                                    <input type="text" class="form-control" id="edit_embed_url_review"
                                        name="embed_url_review">
                                    <div class="text-danger error-message" id="error_edit_embed_url_review"></div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
        <script>
            //decode chuỗi
            function decodeHtmlEntities(str) {
                const txt = document.createElement('textarea');
                txt.innerHTML = str;
                return txt.value;
            }

            //Mở modal Edit
            $(document).on('click', '#edit-product-btn', function () {
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

            //Submit edit form
            document.addEventListener('DOMContentLoaded', () => {
                const formEdit = document.getElementById('editProductForm');
                if (!formEdit) return;

                formEdit.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    clearFormErrors(formEdit);

                    const id = formEdit.dataset.id;
                    if (!id) return alert('Không xác định được sản phẩm cần sửa!');

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
                            Swal.fire('Cập nhật sản phẩm thành công !', data.message, 'success').then(() => location.reload());
                        } else if (data.errors) {
                            // Hiển thị lỗi validation
                            Object.entries(data.errors).forEach(([field, messages]) => {
                                const errorEl = document.getElementById(`error_edit_${field}`);
                                if (errorEl) errorEl.textContent = messages[0];
                            });
                        } else {
                            Swal.fire('Cập nhật sản phẩm thất bại!', data.message, 'error').then(() => location.reload());
                        }
                    } catch (err) {
                        Swal.fire('Lỗi không xác định !', data.message, 'err').then(() => location.reload());

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
                            Swal.fire('Thêm sản phẩm thành công !', data.message, 'success').then(() => location.reload());
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
                                    Swal.fire('Đã xóa!', data.message, 'success').then(() => location.reload());
                                } else {
                                    Swal.fire('Lỗi', 'Không thể xóa sản phẩm.', 'error');
                                }
                            })
                            .catch(() => Swal.fire('Lỗi', 'Không thể kết nối đến server.', 'error'));
                    }
                });
            }

        </script>
    @endpush
@endsection