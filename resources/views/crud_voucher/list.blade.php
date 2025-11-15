@extends('layouts.dashboard')

@section('content')
    <!-- Main Content -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @livewire('voucher-table')
        </div>
    </main>
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
                                    <input type="text" class="form-control" id="edit_code" name="code">
                                    <div class="text-danger error-message" id="error_edit_code"></div>
                                </div>
                                <div class="form-group">
                                    <label for="edit_discount_type">Discount Type</label>
                                    <select class="form-control" id="edit_discount_type" name="discount_type">
                                        <option value="">-- Chọn loại giảm giá --</option>
                                        <option value="percent">percent</option>
                                        <option value="amount">amount</option>
                                    </select>
                                    <div class="text-danger error-message" id="error_edit_discount_type"></div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_discount_value">Discount Value</label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="edit_discount_value"
                                        name="discount_value">
                                    <div class="text-danger error-message" id="error_edit_discount_value"></div>
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="edit_start_date">Start Date</label>
                                    <input type="date" class="form-control" id="edit_start_date" name="start_date">
                                    <div class="text-danger error-message" id="error_edit_start_date"></div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_end_date">End Date</label>
                                    <input type="date" class="form-control" id="edit_end_date" name="end_date">
                                    <div class="text-danger error-message" id="error_edit_end_date"></div>
                                </div>

                                <div class="form-group">
                                    <label for="edit_status">Status</label>
                                    <select class="form-control" id="edit_status" name="status">
                                        <option value="">-- Chọn trạng thái --</option>
                                        <option value="active">active</option>
                                        <option value="inactive">inactive</option>
                                    </select>
                                    <div class="text-danger error-message" id="error_edit_status"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="closeEdit">Đóng</button>
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
                                    <input type="text" class="form-control" id="add_code" name="code">
                                    <div class="text-danger error-message" id="error_add_code"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_discount_type">Discount Type</label>
                                    <select class="form-control" id="add_discount_type" name="discount_type">
                                        <option value="">-- Chọn loại giảm giá --</option>
                                        <option value="percent">percent</option>
                                        <option value="amount">amount</option>
                                    </select>
                                    <div class="text-danger error-message" id="error_add_discount_type"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_discount_value">Discount Value</label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="add_discount_value"
                                        name="discount_value">
                                    <div class="text-danger error-message" id="error_add_discount_value"></div>
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_start_date">Start Date</label>
                                    <input type="date" class="form-control" id="add_start_date" name="start_date">
                                    <div class="text-danger error-message" id="error_add_start_date"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_end_date">End Date</label>
                                    <input type="date" class="form-control" id="add_end_date" name="end_date">
                                    <div class="text-danger error-message" id="error_add_end_date"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_status">Status</label>
                                    <select class="form-control" id="add_status" name="status">
                                        <option value="">-- Chọn trạng thái --</option>
                                        <option value="active">active</option>
                                        <option value="inactive">inactive</option>
                                    </select>
                                    <div class="text-danger error-message" id="error_add_status"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button id="close" type="button" class="btn btn-secondary" data-dismiss="modal"
                            id="closeAdd">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal View Voucher -->
    <div class="modal fade" id="viewVoucherModal" tabindex="-1" role="dialog" aria-labelledby="viewVoucherModalLabel"
        aria-hidden="true">
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
                            <img id="view_logo" src="" alt="Voucher" class="img-fluid rounded shadow mb-3"
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- End Main Content -->
    @push('scripts')
        <script src="js/voucher.js"></script>
    @endpush
@endsection