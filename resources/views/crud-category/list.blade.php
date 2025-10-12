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
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="material-icons">&#xE8B6;</i></span>
                                        <input type="text" class="form-control" placeholder="Search&hellip;">
                                    </div>
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
                                <tr
                                data-category-id="{{ $category->category_id }}"
                                data-category-name="{{ $category->category_name }}"
                                data-category-description="{{ $category->description }}"
                                >
                                    <td>{{ $category->category_id }}</td>
                                    <td>{{ $category->category_name }}</td>
                                    <td>{{ $category->description }}</td>
                                   
                                    <td>
                                    <a href="#" class="view" title="View" data-toggle="tooltip"><i
                                            class="material-icons">&#xE417;</i></a>
                                    <a href="#" class="edit" title="Edit" data-toggle="tooltip"><i
                                            class="material-icons">&#xE254;</i></a>
                                    <a href="#" class="delete" title="Delete" data-toggle="tooltip"><i
                                            class="material-icons">&#xE872;</i></a>
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="clearfix">
                        <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
                        <ul class="pagination">
                            <li class="page-item disabled"><a href="#">Previous</a></li>
                            <li class="page-item"><a href="#" class="page-link">1</a></li>
                            <li class="page-item"><a href="#" class="page-link">2</a></li>
                            <li class="page-item active"><a href="#" class="page-link">3</a></li>
                            <li class="page-item"><a href="#" class="page-link">4</a></li>
                            <li class="page-item"><a href="#" class="page-link">5</a></li>
                            <li class="page-item"><a href="#" class="page-link">Next</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


      <!-- Modal Edit Product -->
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
                            <!-- Cột trái -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="category_name">Tên danh mục</label>
                                    <input type="text" class="form-control" id="category_name" name="category_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control" id="description" name="description"></textarea>
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

    <!-- Modal Thêm Mới Sản Phẩm -->
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
                            <!-- Cột trái -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="add_product_name">Tên danh mục</label>
                                    <input type="text" class="form-control" id="add_category_name" name="category_name"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="add_description">Mô tả</label>
                                    <textarea class="form-control" id="add_description" name="description"></textarea>
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

    <script>
          // Xử lý khi click nút Edit
        document.querySelectorAll('.edit').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
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

            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            if (response.ok) {
                alert('Cập nhật danh mục thành công!');
                $('#editCategoryModal').modal('hide');
                location.reload();
            } else {
                const err = await response.json();

                console.error(err);
                alert('Cập nhật thất bại: ' + (err.message || 'Lỗi không xác định'));
            }
        });

        // Hiển thị modal khi nhấn nút "Thêm Mới Sản Phẩm"
        document.querySelector('.add-new').addEventListener('click', function () {
            // Reset form
            $('#addCategoryModal').modal('show');
        });


        // Xử lý submit form thêm mới
        document.getElementById('addCategoryForm').addEventListener('submit', async function (e) {
            e.preventDefault();

            const url = '/api/categories';
            const formData = new FormData(this);

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            if (response.ok) {
                alert('Thêm danh mục thành công!');
                $('#addCategoeyModal').modal('hide');
                location.reload();
            } else {
                const err = await response.json();
                alert('Thêm thất bại: ' + (err.message || 'Lỗi không xác định'));
            }
        });
    </script>
@endsection