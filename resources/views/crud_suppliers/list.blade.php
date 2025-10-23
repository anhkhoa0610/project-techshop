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
                                <a href="#" title="Add" data-toggle="modal" id = "addNewSupplierBtn"
                                            data-target="#addSupplierModal" class="btn btn-info add-new">Thêm Nhà Cung Cấp</a>
                            </div>
                            <div class="col-sm-4">
                                <h2 class="text-center"><b>Quản Lý Nhà Phân Phối</b></h2>
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
                                            <img src="{{ asset('uploads/' . $supplier->logo) }}" alt="{{ $supplier->name }}"
                                                 style="max-width: 50px;">
                                        @else
                                            <img src="{{ asset('images/place-holder.jpg') }}" alt="place-holder"
                                                 style="max-width: 50px;">
                                        @endif
                                    </td>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->email ?? '—' }}</td>
                                    <td>{{ $supplier->phone ?? '—' }}</td>
                                    <td>{{ $supplier->address ?? '—' }}</td>
                                    <td>{{ $supplier->description ?? '—' }}</td>
                                    <td>
                                    <a href="#" class="view" title="View" data-toggle="modal" 
                                            data-target="#viewSupplierModal">
                                            <i class="material-icons">&#xE417;</i>
                                        </a>
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
                    <div class="clearfix">
                        <div class="clearfix">
                            <nav>
                                {{ $suppliers->withQueryString()->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                    <!-- Modal Edit Supplier -->
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
                    <!-- Modal Add Supplier -->
                    <div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form id="addSupplierForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editSupplierModalLabel">Thêm Nhà Cung Cấp</h5>
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
                                                    <img id="add_logo_preview" src="" alt="Logo hiện tại" style="max-height: 50px; margin-bottom: 2px;">
                                                    <input type="file" class="form-control" id="add_logo" name="logo"
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
                                        <button id = "close" type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Modal View Supplier -->
                    <div class="modal fade" id="viewSupplierModal" tabindex="-1" role="dialog" aria-labelledby="viewSupplierModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content shadow-lg">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title" id="viewSupplierModalLabel">                                    
                                        Thông tin Nhà Cung Cấp
                                    </h5>
                                </div>
                                <div class="modal-body bg-light">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <div class="mb-3">
                                                <img id="view_logo" src="" alt="Logo hiện tại" class="img-thumbnail shadow" style="max-height: 120px; background: #fff;">
                                            </div>
                                            <h4 id="view_name" class="font-weight-bold text-secondary mb-2"></h4>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card border-0 bg-white shadow-sm">
                                                <div class="card-body p-3">
                                                    <div class="row mb-2">
                                                        <div class="col-4 font-weight-bold text-secondary">Email:</div>
                                                        <div class="col-8" id="view_email"></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4 font-weight-bold text-secondary">Số điện thoại:</div>
                                                        <div class="col-8" id="view_phone"></div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-4 font-weight-bold text-secondary">Địa chỉ:</div>
                                                        <div class="col-8" id="view_address"></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-4 font-weight-bold text-secondary">Mô tả:</div>
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

                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->
     <script>
    window.csrfToken = '{{ csrf_token() }}';
    </script>
    <script src="{{ asset('js/supplier.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection