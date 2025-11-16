@extends('layouts.dashboard')

@section('content')
    <!-- Main Content -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @livewire('supplier-table')
        </div>
    </main>
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
                                    <img id="edit_logo_preview" src="" alt="Hình ảnh"
                                        style="max-height: 50px; margin-bottom: 2px;">
                                    <input type="file" class="form-control" id="edit_logo" name="logo" accept="image/*">
                                    <div class="text-danger error-message" id="error_edit_logo"></div>
                                </div>
                                <div class="form-group">
                                    <label for="name">Tên Nhà Phân Phối</label>
                                    <input type="text" class="form-control" id="edit_name" name="name" required>
                                    <div class="text-danger error-message" id="error_edit_name"></div>
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" id="edit_email" name="email">
                                    <div class="text-danger error-message" id="error_edit_email"></div>
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" class="form-control" id="edit_phone" name="phone">
                                    <div class="text-danger error-message" id="error_edit_phone"></div>
                                </div>
                                <div class="form-group">
                                    <label for="address">Địa chỉ</label>
                                    <input type="text" class="form-control" id="edit_address" name="address">
                                    <div class="text-danger error-message" id="error_edit_address"></div>
                                </div>
                                <div class="form-group">
                                    <label for="description">Mô tả</label>
                                    <textarea class="form-control" id="edit_description" name="description"></textarea>
                                    <div class="text-danger error-message" id="error_edit_description">
                                    </div>
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
    <!-- Modal Add Supplier -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="addSupplierForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm Nhà Cung Cấp</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <!-- Cột trái -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_logo">Hình ảnh</label><br>
                                    <img id="add_logo_preview" src="/uploads/place-holder.jpg" alt="Logo xem trước"
                                        style="max-height: 50px; margin-bottom: 2px;">
                                    <input type="file" class="form-control" id="add_logo" name="logo" accept="image/*">
                                    <div class="text-danger error-message" id="error_add_logo"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_name">Tên Nhà Phân Phối</label>
                                    <input type="text" class="form-control" id="add_name" name="name" required>
                                    <div class="text-danger error-message" id="error_add_name"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_email">Email</label>
                                    <input type="text" class="form-control" id="add_email" name="email">
                                    <div class="text-danger error-message" id="error_add_email"></div>
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="add_phone">Số điện thoại</label>
                                    <input type="text" class="form-control" id="add_phone" name="phone">
                                    <div class="text-danger error-message" id="error_add_phone"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_address">Địa chỉ</label>
                                    <input type="text" class="form-control" id="add_address" name="address">
                                    <div class="text-danger error-message" id="error_add_address"></div>
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
                        <button id="closeAddSupplier" type="button" class="btn btn-secondary"
                            data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal View Supplier -->
    <div class="modal fade" id="viewSupplierModal" tabindex="-1" role="dialog" aria-labelledby="viewSupplierModalLabel"
        aria-hidden="true">
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
                                <img id="view_logo" src="" alt="Logo hiện tại" class="img-thumbnail shadow"
                                    style="max-height: 120px; background: #fff;">
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
                                        <div class="col-4 font-weight-bold text-secondary">Số điện thoại:
                                        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- End Main Content -->
    @push('scripts')
        <script src="js/supplier.js"></script>
    @endpush
@endsection