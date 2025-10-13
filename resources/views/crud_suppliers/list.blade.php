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
                                <a href="{{ route('supplier.create') }}" class="btn btn-info add-new">Thêm Mới Nhà Cung Cấp</a>
                            </div>
                            <div class="col-sm-4">
                                <h2 class="text-center"><b>Quản Lý Nhà Phân Phối</b></h2>
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
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                                <tr data-supplier-id="{{ $supplier->supplier_id }}"
                                    data-name="{{ $supplier->name }}"
                                    data-description="{{ $supplier->description }}"
                                    data-email="{{ $supplier->email }}"
                                    data-phone="{{ $supplier->phone }}"
                                    data-address="{{ $supplier->address }}"
                                    data-logo="{{ $supplier->logo }}">
                                    <td>{{ $supplier->supplier_id }}</td>
                                    <td>
                                        @if ($supplier->logo)
                                            <img src="{{ asset('uploads/' . $supplier->logo) }}" alt=""
                                                class="img-fluid rounded shadow" style="max-height: 50px;">
                                        @else
                                            <img src="{{ asset('uploads/place-holder.jpg') }}" alt=""
                                                class="img-fluid rounded shadow" style="max-height: 50px;">
                                        @endif
                                    </td>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->email ?? '—' }}</td>
                                    <td>{{ $supplier->phone ?? '—' }}</td>
                                    <td>{{ $supplier->address ?? '—' }}</td>
                                    <td>{{ $supplier->description ?? '—' }}</td>
                                    <td>
                                    <a href="#" class="view" title="View" data-toggle="tooltip"><i
                                            class="material-icons">&#xE417;</i></a>
                                    <a href="#" class="edit" title="Edit" data-toggle="modal"
                                            data-target="#editSupplierModal">
                                            <i class="material-icons">&#xE254;</i>
                                        </a>
                                    <form id="delete-form-{{ $supplier->supplier_id }}"
                                        action="{{ url('/api/suppliers/' . $supplier->supplier_id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-link p-0 m-0 align-baseline delete"
                                                title="Delete" data-toggle="tooltip"
                                            onclick="confirmDelete({{ $supplier->supplier_id }})"><i class="material-icons text-danger">&#xE872;</i></button>
                                    </form>
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <!-- Modal Edit Product -->
                    <div class="modal fade" id="editSupplierModal" tabindex="-1" role="dialog" aria-labelledby="editSupplierModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form id="editSupplierForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editSupplierModalLabel">Chỉnh sửa Nhà Cung Cấp</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <!-- Cột trái -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="logo">Hình ảnh</label><br>
                                                    <img id="edit_logo_preview" src="" alt="Logo hiện tại" style="max-height: 50px; margin-bottom: 2px;">
                                                    <input type="file" class="form-control" id="edit_logo" name="logo"
                                                        accept="image/*">
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Tên Nhà Phân Phối</label>
                                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="text" class="form-control" id="edit_email" name="email">
                                                </div>
                                            </div>

                                            <!-- Cột phải -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="phone">Số điện thoại</label>
                                                    <input type="text" class="form-control" id="edit_phone" name="phone">
                                                </div>
                                                <div class="form-group">
                                                    <label for="address">Địa chỉ</label>
                                                    <input type="text" class="form-control" id="edit_address" name="address">
                                                </div>
                                                <div class="form-group">
                                                    <label for="description">Mô tả</label>
                                                    <textarea class="form-control" id="edit_description" name="description"></textarea>
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
    <script>
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

                 // Lưu ID nhà cung cấp vào thuộc tính data của form

                document.getElementById('editSupplierForm').dataset.id = row.getAttribute('data-supplier-id');

                $('#editSupplierModal').modal('show');
            });
        });
        document.getElementById('edit_logo').addEventListener('change', function (e) {
            const [file] = this.files;
            if (file) {
                document.getElementById('edit_logo_preview').src = URL.createObjectURL(file);
            }
        });
        // Xử lý submit form
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
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            if (response.ok) {
                alert('Cập nhật Nhà Phân Phối thành công!');
                $('#editSupplierModal').modal('hide');
                location.reload();
            } else {
                const err = await response.json();

                console.error(err);
                alert('Cập nhật thất bại: ' + (err.message || 'Lỗi không xác định'));
            }
        });

        function confirmDelete(supplierId) {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc chắn muốn xóa tác nhà cũng cấp này không?',
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
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection