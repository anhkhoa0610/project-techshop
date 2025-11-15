@extends('layouts.dashboard')

@section('content')
    <!-- Main Content -->
    <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            @livewire('order-table')
        </div>
    </main>

    <!-- Modal Thêm Mới order -->
    <div class="modal fade" id="addOrderModal" tabindex="-1" role="dialog" aria-labelledby="addOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="addOrderForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addOrderModalLabel">Thêm mới đơn hàng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Cột trái -->
                            <div class="col-md-6">
                                {{-- Người dùng --}}
                                <div class="form-group">
                                    <label for="add_user_id">Người dùng</label>
                                    <select class="form-control" id="add_user_id" name="user_id">
                                        <option value="">-- Chọn người dùng --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->user_id }}">{{ $user->full_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message" id="error_add_user_id"></div>
                                </div>



                                {{-- Địa chỉ giao hàng --}}
                                <div class="form-group">
                                    <label for="shipping_address">Địa chỉ giao hàng</label>
                                    <textarea name="shipping_address" id="shipping_address"></textarea>
                                    <div class="text-danger error-message" id="error_add_shipping_address"></div>
                                </div>
                            </div>
                            <!-- Cột phải -->
                            <div class="col-md-6">


                                {{-- Phương thức thanh toán --}}
                                <div class="form-group">
                                    <label for="add_payment_method">Phương thức thanh toán</label>
                                    <select class="form-control" id="add_payment_method" name="payment_method">
                                        <option value="">-- Chọn phương thức thanh toán--</option>
                                        <option value="cash">cash</option>
                                        <option value="card">card</option>
                                        <option value="transfer">transfer</option>
                                    </select>
                                    <div class="text-danger error-message" id="error_add_payment_method"></div>
                                </div>

                                {{-- Voucher --}}
                                <div class="form-group">
                                    <label for="add_voucher_id">Mã giảm giá (Voucher)</label>
                                    <select class="form-control" id="add_voucher_id" name="voucher_id">
                                        <option value="{{ null }}">-- Không áp dụng --</option>
                                        @foreach ($vouchers as $voucher)
                                            <option value="{{ $voucher->voucher_id }}">{{ $voucher->code }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message" id="error_add_voucher_id"></div>
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

    <!-- Modal chỉnh sửa order -->
    <div class="modal fade" id="editOrderModal" tabindex="-1" role="dialog" aria-labelledby="editOrderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editOrderForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOrderModalLabel">Chỉnh sửa đơn hàng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Cột trái -->
                            <div class="col-md-6">


                                {{-- Trạng thái --}}
                                <div class="form-group">
                                    <label for="edit_status">Trạng thái</label>
                                    <select class="form-control" id="edit_status" name="status">
                                        <option value="">-- Chọn trạng thái --</option>
                                        <option value="pending">pending</option>
                                        <option value="processing">processing</option>
                                        <option value="completed">completed</option>
                                        <option value="cancelled">cancelled</option>
                                    </select>
                                    <div class="text-danger error-message" id="error_edit_status"></div>
                                </div>

                                {{-- Địa chỉ giao hàng --}}
                                <div class="form-group">
                                    <label for="edit_shipping_address">Địa chỉ giao hàng</label>
                                    <textarea name="shipping_address" id="edit_shipping_address"></textarea>
                                    <div class="text-danger error-message" id="error_edit_shipping_address"></div>
                                </div>
                            </div>
                            <!-- Cột phải -->
                            <div class="col-md-6">


                                {{-- Phương thức thanh toán --}}
                                <div class="form-group">
                                    <label for="edit_payment_method">Phương thức thanh toán</label>
                                    <select class="form-control" id="edit_payment_method" name="payment_method">
                                        <option value="">-- Chọn phương thức thanh toán--</option>
                                        <option value="cash">cash</option>
                                        <option value="card">card</option>
                                        <option value="transfer">transfer</option>
                                    </select>
                                    <div class="text-danger error-message" id="error_edit_payment_method"></div>
                                </div>

                                {{-- Voucher --}}
                                <div class="form-group">
                                    <label for="edit_voucher_id">Mã giảm giá (Voucher)</label>
                                    <select class="form-control" id="edit_voucher_id" name="voucher_id">
                                        <option value="{{ null }}">-- Không áp dụng --</option>
                                        @foreach ($vouchers as $voucher)
                                            <option value="{{ $voucher->voucher_id }}">{{ $voucher->code }}</option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message" id="error_edit_voucher_id"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script src="js/crud-orders.js">

    </script>
@endpush


@endsection