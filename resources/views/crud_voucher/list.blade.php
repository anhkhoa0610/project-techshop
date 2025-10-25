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
                                <a href="#" title="Add" data-toggle="modal" id = "addNewVoucherBtn"
                                            data-target="#addVoucherModal" class="btn btn-info add-new">Thêm Voucher</a>
                            </div>
                            <div class="col-sm-4">
                                <h2 class="text-center"><b>Quản Lý Voucher</b></h2>
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
                                <th>Code</th>
                                <th>Discount Type</th>
                                <th>Discount Value</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vouchers as $voucher)
                                <tr data-voucher-id="{{ $voucher->voucher_id }}"
                                    data-code="{{ $voucher->code }}"
                                    data-discount_type="{{ $voucher->discount_type }}"
                                    data-discount_value="{{ $voucher->discount_value }}"
                                    data-start_date="{{ $voucher->start_date }}"
                                    data-end_date="{{ $voucher->end_date }}"
                                    data-status="{{ $voucher->status }}"
                                    data-created_at="{{ $voucher->created_at }}"
                                    data-updated_at="{{ $voucher->updated_at }}">
                                    <td>{{ $voucher->voucher_id }}</td>
                                    <td> {{ $voucher->code }} </td>
                                    <td>{{ $voucher->discount_type }}</td>
                                    <td>{{ $voucher->discount_value ?? '—' }}</td>
                                    <td>{{ $voucher->start_date ?? '—' }}</td>
                                    <td>{{ $voucher->end_date ?? '—' }}</td>
                                    <td>{{ $voucher->status ?? '—' }}</td>
                                    <td>
                                    <a href="#" class="view" title="View" data-toggle="modal" 
                                            data-target="#viewVoucherModal">
                                            <i class="material-icons">&#xE417;</i>
                                        </a>
                                    <a href="#" class="edit" title="Edit" data-toggle="modal"
                                            data-target="#editVoucherModal">
                                            <i class="material-icons">&#xE254;</i>
                                        </a>
                                    <form id="delete-form-{{ $voucher->voucher_id }}"
                                        action="{{ url('/api/vouchers/' . $voucher->voucher_id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-link p-0 m-0 align-baseline delete"
                                                title="Delete" data-toggle="tooltip"
                                            onclick="confirmDelete({{ $voucher->voucher_id }})"><i class="material-icons text-danger">&#xE872;</i></button>
                                    </form>
                                </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="clearfix">
                        <div class="clearfix">
                            <nav>
                                {{ $vouchers->withQueryString()->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                    <!-- Modal Edit Voucher -->
                    <div class="modal fade" id="editVoucherModal" tabindex="-1" role="dialog" aria-labelledby="editVoucherModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form id="editVoucherForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editVoucherModalLabel">Chỉnh sửa Nhà Cung Cấp</h5>
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
                    <!-- Modal Add Voucher -->
                    <div class="modal fade" id="addVoucherModal" tabindex="-1" role="dialog" aria-labelledby="addVoucherModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form id="addVoucherForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editVoucherModalLabel">Thêm Voucher</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <!-- Cột trái -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="code">Code</label><br>
                                                    <input type="text" class="form-control" id="add_code" name="code" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="discount_type">Discount Type</label>
                                                    <select class="form-control" id="add_discount_type" name="discount_type" required>
                                                        <option value="percent">percent</option>
                                                        <option value="amount">amount</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="discount_value">Discount Value</label>
                                                    <input type="number" step="0.01" min="0" class="form-control" id="add_discount_value" name="discount_value" required>
                                                </div>
                                            </div>

                                            <!-- Cột phải -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="start_date">Start Date</label>
                                                    <input type="date" class="form-control" id="add_start_date" name="start_date">
                                                </div>
                                                <div class="form-group">
                                                    <label for="end_date">End Date</label>
                                                    <input type="date" class="form-control" id="add_end_date" name="end_date">
                                                </div>
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select class="form-control" id="add_status" name="status" required>
                                                        <option value="active">active</option>
                                                        <option value="amount">inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button id = "close" type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-primary">Lưu</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- Modal View Voucher -->
                    <div class="modal fade" id="viewVoucherModal" tabindex="-1" role="dialog" aria-labelledby="viewVoucherModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content shadow-lg">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title" id="viewVoucherModalLabel">                                    
                                        Thông tin Voucher
                                    </h5>
                                </div>
                                <div class="modal-body bg-light">
                                    <div class="row">
                                        <div class="col-md-4 text-center">
                                            <img id="view_logo"
                                                src=""
                                                alt="Voucher"
                                                class="img-fluid rounded shadow mb-3"
                                                style="max-height:160px; width:100%; object-fit:cover;">
                                            <h4 id="view_name" class="h5 font-weight-bold text-secondary mb-1"></h4>

                                            <div class="mb-2">
                                                @if ($voucher->status === 'active')
                                                    <span id="view_status" class="badge badge-success">Active</span>
                                                @else
                                                    <span id="view_status" class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </div>

                                            <div class="text-muted small" id="view_dates">
                                                <div>Start: <span id="view_start_date">—</span></div>
                                                <div>End: <span id="view_end_date">—</span></div>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <div class="card border-0">
                                                <div class="card-body p-3">
                                                    <dl class="row mb-2">
                                                        <dt class="col-sm-4 text-muted">Code</dt>
                                                        <dd class="col-sm-8 font-weight-bold" id="view_code"></dd>

                                                        <dt class="col-sm-4 text-muted">Discount Type</dt>
                                                        <dd class="col-sm-8" id="view_discount_type"></dd>

                                                        <dt class="col-sm-4 text-muted">Discount Value</dt>
                                                        <dd class="col-sm-8" id="view_discount_value"></dd>

                                                    </dl>

                                                    <h6 class="mb-1 text-muted">Created At</h6>
                                                    <p id="view_created_at" class="mb-0 text-secondary" style="white-space:pre-wrap;"></p>

                                                    <h6 class="mb-1 text-muted">Updated At</h6>
                                                    <p id="view_updated_at" class="mb-0 text-secondary" style="white-space:pre-wrap;"></p>
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
    <script src="{{ asset('js/Voucher.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection