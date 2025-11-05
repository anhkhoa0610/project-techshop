@extends('layouts.dashboard')
<style>

</style>

@section('content')
    <!-- Main Content -->
    <div class="content">

        <div class="row">
            <div class="col-sm-4">

            </div>
            <div class="col-sm-4">

            </div>
            <div class="col-sm-4">
                <form method="GET" action="{{ url()->current() }}" class="row g-3  align-items-end">
                    <div class="col-sm-4">
                        <label for="start_date" class="form-label">Từ ngày</label>
                        <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}"
                            class="form-control">
                    </div>

                    <div class="col-sm-4">
                        <label for="end_date" class="form-label">Đến ngày</label>
                        <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}"
                            class="form-control">
                    </div>

                    <div class="col-sm-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Lọc
                        </button>
                    </div>
                </form>

            </div>

        </div>
        <div class="container-xl">
            <div class="table-responsive text-center">
                <div class="table-wrapper">
                    <div class="table-title">

                        <div class="row">
                            <div class="col-sm-4">
                                <button class="btn btn-info add-new">Thêm Mới đơn hàng
                                </button>

                            </div>

                            <div class="col-sm-4">
                                <h2 class="text-center"><b>Quản Lý đơn hàng</b></h2>
                            </div>
                            <div class="col-sm-4">

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
                    <table class="table table-bordered">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User name</th>
                                <th>Order date</th>
                                <th>Status</th>
                                <th>Shipping address</th>
                                <th>Payment method</th>
                                <th>Voucher code</th>
                                <th>Total price</th>
                                <th>Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr data-order-id="{{ $order->order_id }}" data-user-id="{{ $order->user->user_id }}"
                                    data-order-date="{{ $order->order_date }}" data-status="{{ $order->status }}"
                                    data-shipping-address="{{ $order->shipping_address }}"
                                    data-payment-method="{{ $order->payment_method }}"
                                    data-voucher-id="{{ $order->voucher->voucher_id ?? ""}}"
                                    data-total-price="{{ $order->total_price }}">
                                    <td>{{ $order->order_id }}</td>
                                    <td>{{ $order->user->full_name }}</td>
                                    <td>{{ $order->order_date }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>{{ $order->shipping_address }}</td>
                                    <td>{{ $order->payment_method }}</td>
                                    <td>{{ $order->voucher->code ?? "không áp dụng"}}</td>
                                    <td>{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                                    <td>
                                        <a href="{{ route("orderDetails.list", [$order->order_id]) }}" class="view" title="View"
                                            data-toggle="tooltip"><i class="material-icons">&#xE417;</i></a>
                                        <a href="#" class="edit" title="Edit" data-toggle="modal" data-target="#editOrderModal">
                                            <i class="material-icons">&#xE254;</i>
                                        </a>
                                        <form action="{{ url('/api/orders/' . $order->order_id) }}" method="POST"
                                            style="display:inline;">
                                            <button type="button" class="btn btn-link p-0 m-0 align-baseline delete"
                                                title="Delete" data-toggle="tooltip"
                                                onclick="confirmDelete({{ $order->order_id }})">
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
                                {{ $orders->withQueryString()->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



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

    <script>
        window.csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('js/crud-orders.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endsection