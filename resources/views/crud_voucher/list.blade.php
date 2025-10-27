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
                                            data-target="#addVoucherModal" class="btn btn-info add-new">Thêm mã khuyến mãi</a>
                                
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
                            <div class="col-sm-12 d-flex justify-content-end">
                                <a href="{{ url()->current() }}" 
                                    class="btn btn-outline-secondary d-flex align-items-center justify-content-center ms-1 mt-1" 
                                    style="height:36px; padding:0 10px; line-height:1; width:70px;">
                                    <i class="material-icons me-1" style="font-size:18px;">refresh</i>
                                    <span style="font-size:14px;">Reset</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Code</th>

                                {{-- Cột Discount Type --}}
                                <th>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="fw-bold mx-1">Discount Type</span>
                                        <form method="GET" action="{{ url()->current() }}" class="m-0" id="discountTypeFilterForm">
                                            {{-- Giữ lại các tham số khác --}}
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                            <input type="hidden" name="status_filter" value="{{ request('status_filter') }}">

                                            <select name="discount_type_filter"
                                                    class="form-select form-select-sm"
                                                    style="min-width: 50px; max-width: 100px;"
                                                    onchange="document.getElementById('discountTypeFilterForm').submit()">
                                                <option value="">All</option>
                                                @foreach($allDiscountType as $discount_type)
                                                    <option value="{{ $discount_type }}" {{ request('discount_type_filter') == $discount_type ? 'selected' : '' }}>
                                                        {{ $discount_type }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </div>
                                </th>

                                <th>Discount Value</th>
                                {{-- Start Date --}}
                                <th>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="fw-bold mx-1">Start</span>
                                        <form method="GET" action="{{ url()->current() }}" id="startDateFilterForm" class="m-0">
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                            <input type="hidden" name="discount_type_filter" value="{{ request('discount_type_filter') }}">
                                            <input type="hidden" name="status_filter" value="{{ request('status_filter') }}">
                                            <input type="hidden" name="end_date_filter" value="{{ request('end_date_filter') }}">
                                            <input type="date" name="start_date_filter"
                                                class="form-control form-control-sm text-center"
                                                style="width: 130px;"
                                                value="{{ request('start_date_filter') }}"
                                                onchange="document.getElementById('startDateFilterForm').submit()">
                                        </form>
                                    </div>
                                </th>
                                {{-- End Date --}}
                                <th>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="fw-bold mx-1">End</span>
                                        <form method="GET" action="{{ url()->current() }}" id="endDateFilterForm" class="m-0">
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                            <input type="hidden" name="discount_type_filter" value="{{ request('discount_type_filter') }}">
                                            <input type="hidden" name="status_filter" value="{{ request('status_filter') }}">
                                            <input type="hidden" name="start_date_filter" value="{{ request('start_date_filter') }}">
                                            <input type="date" name="end_date_filter"
                                                class="form-control form-control-sm text-center"
                                                style="width: 130px;"
                                                value="{{ request('end_date_filter') }}"
                                                onchange="document.getElementById('endDateFilterForm').submit()">
                                        </form>
                                    </div>
                                </th>

                                {{-- Cột Status --}}
                                <th>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <span class="fw-bold mx-1">Status</span>
                                        <form method="GET" action="{{ url()->current() }}" class="m-0" id="statusFilterForm">
                                            {{-- Giữ lại các tham số khác --}}
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                            <input type="hidden" name="discount_type_filter" value="{{ request('discount_type_filter') }}">

                                            <select name="status_filter"
                                                    class="form-select form-select-sm"
                                                    style="min-width: 50px; max-width: 100px;"
                                                    onchange="document.getElementById('statusFilterForm').submit()">
                                                <option value="">All</option>
                                                @foreach($allStatus as $status)
                                                    <option value="{{ $status }}" {{ request('status_filter') == $status ? 'selected' : '' }}>
                                                        {{ ucfirst($status) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </div>
                                </th>

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
                                    <td>{{ $voucher->code }}</td>
                                    <td>{{ $voucher->discount_type }}</td>
                                    <td>{{ $voucher->discount_value ?? '—' }}</td>
                                    <td>{{ $voucher->start_date ?? '—' }}</td>
                                    <td>{{ $voucher->end_date ?? '—' }}</td>
                                    <td>
                                        @php
                                            $statusClass = $voucher->status === 'active' ? 'badge-success' : 'badge-secondary';
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ $voucher->status }}</span>
                                    </td>
                                    <td>
                                        <a href="#" class="view" title="View" data-toggle="modal" data-target="#viewVoucherModal">
                                            <i class="material-icons">&#xE417;</i>
                                        </a>
                                        <a href="#" class="edit" title="Edit" data-toggle="modal" data-target="#editVoucherModal">
                                            <i class="material-icons">&#xE254;</i>
                                        </a>
                                        <form id="delete-form-{{ $voucher->voucher_id }}"
                                            action="{{ url('/api/vouchers/' . $voucher->voucher_id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-link p-0 m-0 align-baseline delete"
                                                    title="Delete" data-toggle="tooltip"
                                                    onclick="confirmDelete({{ $voucher->voucher_id }})">
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
                                                    <label for="edit_code">Code</label>
                                                    <input type="text" class="form-control" id="edit_code" name="code" required>
                                                    <div class="text-danger error-message" id="error_edit_code"></div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="edit_discount_type">Discount Type</label>
                                                    <select class="form-control" id="edit_discount_type" name="discount_type" required>
                                                        <option value="percent">percent</option>
                                                        <option value="amount">amount</option>
                                                    </select>
                                                    <div class="text-danger error-message" id="error_edit_discount_type"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit_discount_value">Discount Value</label>
                                                    <input type="number" step="0.01" min="0" class="form-control" id="edit_discount_value" name="discount_value" required>
                                                    <div class="text-danger error-message" id="error_edit_discount_value"></div>
                                                </div>
                                            </div>

                                            <!-- Cột phải -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="edit_start_date">Start Date</label>
                                                    <input type="date" class="form-control" id="edit_start_date" name="start_date" required>
                                                    <div class="text-danger error-message" id="error_edit_start_date"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit_end_date">End Date</label>
                                                    <input type="date" class="form-control" id="edit_end_date" name="end_date" required>
                                                    <div class="text-danger error-message" id="error_edit_end_date"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit_status">Status</label>
                                                    <select class="form-control" id="edit_status" name="status" required>
                                                        <option value="active">active</option>
                                                        <option value="inactive">inactive</option>
                                                    </select>
                                                    <div class="text-danger error-message" id="error_edit_status"></div>
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

                    <div class="modal fade" id="addVoucherModal" tabindex="-1" role="dialog" aria-labelledby="addVoucherModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form id="addVoucherForm" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addVoucherModalLabel">Thêm Voucher</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="row">
                                            <!-- Cột trái -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="add_code">Code</label>
                                                    <input type="text" class="form-control" id="add_code" name="code" required>
                                                    <div class="text-danger error-message" id="error_add_code"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="add_discount_type">Discount Type</label>
                                                    <select class="form-control" id="add_discount_type" name="discount_type" required>
                                                        <option value="percent">percent</option>
                                                        <option value="amount">amount</option>
                                                    </select>
                                                    <div class="text-danger error-message" id="error_add_discount_type"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="add_discount_value">Discount Value</label>
                                                    <input type="number" step="0.01" min="0" class="form-control" id="add_discount_value" name="discount_value" required>
                                                    <div class="text-danger error-message" id="error_add_discount_value"></div>
                                                </div>
                                            </div>

                                            <!-- Cột phải -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="add_start_date">Start Date</label>
                                                    <input type="date" class="form-control" id="add_start_date" name="start_date" required>
                                                    <div class="text-danger error-message" id="error_add_start_date"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="add_end_date">End Date</label>
                                                    <input type="date" class="form-control" id="add_end_date" name="end_date" required>
                                                    <div class="text-danger error-message" id="error_add_end_date"></div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="add_status">Status</label>
                                                    <select class="form-control" id="add_status" name="status" required>
                                                        <option value="active">active</option>
                                                        <option value="inactive">inactive</option>
                                                    </select>
                                                    <div class="text-danger error-message" id="error_add_status"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
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
                                                <span id="view_status" class="badge"></span>
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
                                                    <hr>
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
    <script src="{{ asset('js/voucher.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection