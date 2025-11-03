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
                                <button class="btn btn-info add-new">Thêm chi tiết đơn hàng mới
                                </button>

                            </div>
                            <div class="col-sm-4">
                                <h2 class="text-center"><b>Quản Lý chi tiết đơn hàng Order ID: <?php echo ($order_id) ?></b>
                                </h2>
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
                    <div class=" d-flex justify-content-start mb-1">
                        <a href="{{ session('orders_list_url', route('orders.list')) }}" class="btn btn-primary">
                            <i class="fa fa-arrow-left"></i>
                            <span>Back</span>
                        </a>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Order detai ID</th>
                                <th>Product name</th>
                                <th>Product image</th>
                                <th>Quantity</th>
                                <th>Unit price</th>
                                <th>Total price</th>
                                <th>Actions</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderDetails as $detail)
                                <tr data-detail-id="{{ $detail->order_detail_id }}" data-order-id="{{ $detail->order_id }}"
                                    data-product-id="{{ $detail->product_id }}"
                                    data-product-name="{{ $detail->product->product_name }}"
                                    data-product-image="{{ $detail->product->cover_image }}"
                                    data-quantity="{{ $detail->quantity }}" data-unit-price="{{ $detail->unit_price }}"
                                    data-total-price="{{ number_format(($detail->unit_price) * ($detail->quantity), 2) }}">
                                    <td>{{ $detail->order_detail_id }}</td>
                                    <td>{{ $detail->product->product_name }}</td>
                                    <td><img src="{{ asset('uploads/' . $detail->product->cover_image) }}"
                                            alt="{{ $detail->product->product_name }}" width="50"></td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ number_format($detail->unit_price, 0, ',', '.') }} ₫</td>
                                    <td>{{ number_format(($detail->unit_price) * ($detail->quantity), 0, ',', '.') }}₫</td>

                                    <td>
                                        <a href="#" class="view" title="View" data-toggle="tooltip"><i
                                                class="material-icons">&#xE417;</i></a>
                                        <a href="#" class="edit" title="Edit" data-toggle="tooltip"><i
                                                class="material-icons">&#xE254;</i></a>
                                        <form action="{{ url('/api/orderDetails/' . $detail->order_detail_id) }}" method="POST"
                                            style="display:inline;">
                                            <button type="button" class="btn btn-link p-0 m-0 align-baseline delete"
                                                title="Delete" data-toggle="tooltip"
                                                onclick="confirmDelete({{ $detail->order_detail_id}})">
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
                                {{ $orderDetails->withQueryString()->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm orderdetail -->
    <div class="modal fade" id="addOrderDetailModal" tabindex="-1" role="dialog" aria-labelledby="addOrderDetailModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="addOrderDetailForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addOrderDetailModal">Thêm chi tiết đơn hàng mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" id="add_order_id" name="order_id" value="{{ $order_id }}">
                                </div>
                                <div class="form-group">
                                    <label for="add_description">product name</label>
                                    <select class="form-control" id="add_product_id" name="product_id">
                                        <option value="{{ null }}">-- chọn sản phẩm --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->product_id }}">
                                                {{ $product->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message" id="error_add_product_id"></div>
                                </div>
                                <div class="form-group">
                                    <label for="add_quantity">quantity</label>
                                    <input type="number" class="form-control" id="add_quantity" name="quantity"
                                        placeholder="Nhập số lượng">
                                    <div class="text-danger error-message" id="error_add_quantity"></div>
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

    <!-- Modal sửa orderdetail -->
    <div class="modal fade" id="editOrderDetailModal" tabindex="-1" role="dialog" aria-labelledby="editOrderDetailModal"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editOrderDetailForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOrderDetailModal">Sửa chi tiết đơn hàng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" id="edit_order_id" name="order_id" value="{{ $order_id }}">
                                </div>
                                <div class="form-group">
                                    <label for="add_description">product name</label>
                                    <select class="form-control" id="edit_product_id" name="product_id">
                                        <option value="{{ null }}">-- chọn sản phẩm --</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->product_id }}">
                                                {{ $product->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message" id="error_edit_product_id"></div>
                                </div>
                                <div class="form-group">
                                    <label for="edit_quantity">quantity</label>
                                    <input type="number" class="form-control" id="edit_quantity" name="quantity"
                                        placeholder="Nhập số lượng">
                                    <div class="text-danger error-message" id="error_edit_quantity"></div>
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

    <!-- Modal View orderDetail -->
    <div class="modal fade" id="viewOrderDetailModal" tabindex="-1" role="dialog" aria-labelledby="viewOrderDetailLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content shadow-lg">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="viewSupplierModalLabel">
                        Thông tin chi tiết đơn hàng
                    </h5>
                </div>
                <div class="modal-body bg-light">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <img id="view_product_image" src="" alt="Hình ảnh sản phẩm" class="img-thumbnail shadow"
                                    style="max-height: 220px; background: #fff;">
                            </div>
                            <h4 id="view_name" class="font-weight-bold text-secondary mb-2"></h4>
                        </div>
                        <div class="col-md-8">
                            <div class="card border-0 bg-white shadow-sm">
                                <div class="card-body p-3">
                                    <div class="row mb-2">
                                        <div class="col-4 font-weight-bold text-secondary">order detail ID:</div>
                                        <div class="col-8" id="view_stock_quantity"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4 font-weight-bold text-secondary">Tên sản phẩm:</div>
                                        <div class="col-8" id="view_product_name"></div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-4 font-weight-bold text-secondary">Số lượng đã mua:</div>
                                        <div class="col-8" id="view_quantity"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 font-weight-bold text-secondary">Đơn giá :</div>
                                        <div class="col-8" id="view_unit_price"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 font-weight-bold text-secondary">Tổng giá:</div>
                                        <div class="col-8" id="view_total_price"></div>
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


    <script>
        window.csrfToken = "{{ csrf_token() }}";
    </script>
    <script src="{{ asset('js/crud-order-details.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection