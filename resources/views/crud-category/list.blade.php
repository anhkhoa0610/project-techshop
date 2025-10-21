@extends('layouts.dashboard')

@section('content')
    <!-- Main Content -->
    <div class="content">
        <div class="container-xl">
            <div class="table-responsive text-center">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-4">
                                <button class="btn btn-info add-new">Thêm danh mục mới
                                </button>
                            </div>
                            <div class="col-sm-4">
                                <h2 class="text-center"><b>Quản Lý danh mục</b></h2>
                            </div>
                            <div class="col-sm-4">
                                <div class="search-box">
                                    <form class="search-box" method="GET" action="{{ url()->current() }}">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="material-icons">&#xE8B6;</i></span>
                                            <input type="text" class="form-control" name="search" placeholder="Tìm kiếm..."
                                                value="{{ request('search') }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">Search</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr data-category-id="{{ $category->category_id }}"
                                    data-category-name="{{ $category->category_name }}"
                                    data-category-description="{{ $category->description }}">
                                    <td>{{ $category->category_id }}</td>
                                    <td>{{ $category->category_name }}</td>
                                    <td>{{ $category->description }}</td>

                                    <td>
                                        <a href="#" class="view" title="View" data-toggle="tooltip"><i
                                                class="material-icons">&#xE417;</i></a>
                                        <a href="#" class="edit" title="Edit" data-toggle="tooltip"><i
                                                class="material-icons">&#xE254;</i></a>
                                        <form action="{{ url('/api/categories/' . $category->category_id) }}" method="POST"
                                            style="display:inline;">
                                            <button type="button" class="btn btn-link p-0 m-0 align-baseline delete"
                                                title="Delete" data-toggle="tooltip"
                                                onclick="confirmDelete({{ $category->category_id}})">
                                                <i class="material-icons text-danger">&#xE872;</i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="clearfix">
                        <div class="clearfix">
                            <nav>
                                {{ $categories->withQueryString()->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Edit category -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editCategoryForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCategoryModalLabel">Chỉnh sửa danh mục</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="category_name">Tên danh mục</label>
                                    <input type="text" class="form-control" id="category_name" name="category_name">
                                    <div class="text-danger error-message" id="error_edit_category_name"></div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
                                    <div class="text-danger error-message" id="error_edit_description"></div>
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

    <!-- Modal Thêm Mới danh mục -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="addCategoryForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Thêm danh mục mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="add_product_name">Tên danh mục</label>
                                    <input type="text" class="form-control" id="add_category_name" name="category_name">
                                    <div class="text-danger error-message" id="error_add_category_name"></div>
                                </div>
                                <div class="form-group">
                                    <label for="add_description">Mô tả</label>
                                    <textarea class="form-control" id="add_description" name="description"></textarea>
                                    <div class="text-danger error-message" id="error_add_description"></div>
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

    <!-- Modal View category -->
    <div class="modal fade" id="viewCategoryModal" tabindex="-1" role="dialog" aria-labelledby="viewCategoryLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="viewSupplierModalLabel">
                        Thông tin danh mục
                    </h5>
                </div>
                <div class="modal-body bg-light">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card border-0 bg-white shadow-sm">
                                <div class="card-body p-3">
                                    <div class="row mb-2">
                                        <div class="col-4 font-weight-bold text-secondary">ID</div>
                                        <div class="col-8" id="view_category_id"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4 font-weight-bold text-secondary">Category name:</div>
                                        <div class="col-8" id="view_category_name"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4 font-weight-bold text-secondary">Description:</div>
                                        <div class="col-8" id="view_description"></div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-white">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="material-icons align-middle">close</i> Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
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
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
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
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
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

    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection