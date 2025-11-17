@extends('layouts.dashboard')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Main Content -->
    <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @livewire('spec-table')
        </div>
    </main>

    <!-- Modal Edit Spec -->
    <div class="modal fade" id="editSpecModal" tabindex="-1" role="dialog" aria-labelledby="editSpecModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            {{-- Form này sẽ cần được cập nhật action bằng JavaScript --}}
            <form id="editSpecForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Bắt buộc phải có khi dùng method PUT/PATCH để update --}}

                {{-- Thêm một trường ẩn để giữ ID của spec --}}
                <input type="hidden" id="edit_spec_id" name="spec_id">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSpecModalLabel">Chỉnh sửa thông số</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">

                                {{-- Trường 1: Chọn Sản phẩm --}}
                                <div class="form-group">
                                    <label for="edit_product_id">Sản phẩm</label>
                                    <select class="form-control" id="edit_product_id" name="product_id">
                                        <option value="">-- Chọn sản phẩm --</option>

                                    </select>
                                    <div class="text-danger error-message" id="error_edit_product_id"></div>
                                </div>

                                {{-- Trường 2: Tên thông số (ví dụ: CPU, RAM) --}}
                                <div class="form-group">
                                    <label for="edit_name">Tên thông số</label>
                                    <input type="text" class="form-control" id="edit_name" name="name"
                                        placeholder="Ví dụ: CPU, RAM, Màn hình...">
                                    <div class="text-danger error-message" id="error_edit_name"></div>
                                </div>

                                {{-- Trường 3: Giá trị thông số (ví dụ: Core i7, 16GB) --}}
                                <div class="form-group">
                                    <label for="edit_value">Giá trị</label>
                                    <input type="text" class="form-control" id="edit_value" name="value"
                                        placeholder="Ví dụ: Core i7, 16GB, 6.5 inch...">
                                    <div class="text-danger error-message" id="error_edit_value"></div>
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

    <!-- Modal Thêm Mới Spec -->
    <div class="modal fade" id="addSpecModal" tabindex="-1" role="dialog" aria-labelledby="addSpecModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            {{-- Form này sẽ trỏ đến route store của SpecController --}}
            <form id="addSpecForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSpecModalLabel">Thêm thông số mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="add_product_id">Sản phẩm</label>
                                    <select class="form-control" id="add_product_id" name="product_id">

                                    </select>
                                    <div class="text-danger error-message" id="error_add_product_id"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_name">Tên thông số</label>
                                    <input type="text" class="form-control" id="add_name" name="name"
                                        placeholder="Ví dụ: CPU, RAM, Màn hình...">
                                    <div class="text-danger error-message" id="error_add_name"></div>
                                </div>

                                <div class="form-group">
                                    <label for="add_value">Giá trị</label>
                                    <input type="text" class="form-control" id="add_value" name="value"
                                        placeholder="Ví dụ: Core i7, 16GB, 6.5 inch...">
                                    <div class="text-danger error-message" id="error_add_value"></div>
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

@endsection

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@push('scripts')
    <script src="js/spec.js">
    </script>
@endpush