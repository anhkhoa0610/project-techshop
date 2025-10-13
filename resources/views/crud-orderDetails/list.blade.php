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
                                <h2 class="text-center"><b>Quản Lý chi tiết đơn hàng</b></h2>
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
                                <tr>
                                    <td>{{ $detail->order_detail_id }}</td>
                                    <td>{{ $detail->product->product_name }}</td>
                                    <td><img src="{{ asset('images/' . $detail->product->image) }}"
                                            alt="{{ $detail->product->product_name }}" width="50"></td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ number_format($detail->unit_price, 2) }}</td>
                                    <td>{{ number_format(($detail->unit_price) * ($detail->quantity), 2) }}</td>

                                    <td>
                                        <a href="#" class="view" title="View" data-toggle="tooltip"><i
                                                class="material-icons">&#xE417;</i></a>
                                        <a href="#" class="edit" title="Edit" data-toggle="tooltip"><i
                                                class="material-icons">&#xE254;</i></a>
                                        <a href="#" class="delete" title="Delete" data-toggle="tooltip"><i
                                                class="material-icons">&#xE872;</i></a>
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
                                    <input type="hidden" id="add_product_id" name="product_id" value="{{ $order_id }}">
                                </div>
                                <div class="form-group">
                                    <label for="add_description">product name</label>
                                    <select class="form-control" id="add_product_id" name="product_id">
                                        <option value="{{ null }}">-- chọn sản phẩm --</option>
                                        @foreach ($orderDetails as $detail)
                                            <option value="{{ $detail->product->product_id }}">
                                                {{ $detail->product->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger error-message" id="error_add_description"></div>
                                </div>
                                <div class="form-group">
                                    <label for="add_quantity">quantity</label>
                                    <input type="number" class="form-control" id="add_quantity" name="quantity"
                                        placeholder="Nhập số lượng">
                                    <div class="text-danger error-message" id="error_add_description"></div>
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

    <script>
        // Hiển thị modal khi nhấn nút "Thêm Mới danh mục"
        document.querySelector('.add-new').addEventListener('click', function () {
            // Reset form
            // Xóa lỗi cũ
            document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
            $('#addOrderDetailModal').modal('show');
        });

    </script>
@endsection