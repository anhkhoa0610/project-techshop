@extends('layouts.dashboard')
<style>

</style>

@section('content')
    <!-- Main Content -->
    <div class="content">
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
                                <tr data-order-id="{{ $order->order_id }}"
                                    data-user-name="{{ $order->full_name }}"
                                    data-order-date="{{ $order->order_date }}"
                                    data-status="{{ $order->status }}"
                                    data-shipping-address="{{ $order->shipping_address }}"
                                    data-payment-method="{{ $order->payment_method }}"
                                    data-voucher-code="{{ $order->voucher_code }}"
                                    data-total-price="{{ $order->total_price }}">
                                    <td>{{ $order->order_id }}</td>
                                    <td>{{ $order->user->full_name }}</td>
                                    <td>{{ $order->order_date }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>{{ $order->shipping_address }}</td>
                                    <td>{{ $order->payment_method }}</td>
                                    <td>{{ $order->voucher->code }}</td>
                                    <td>{{ number_format($order->total_price, 2) }}</td>
                                    <td>
                                        <a href="#" class="view" title="View" data-toggle="tooltip"><i
                                                class="material-icons">&#xE417;</i></a>
                                        <a href="#" class="edit" title="Edit" data-toggle="modal"
                                            data-target="#editProductModal">
                                            <i class="material-icons">&#xE254;</i>
                                        </a>
                                        <form action="{{ url('/api/orders/' . $order->order_id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline delete"
                                                title="Delete" data-toggle="tooltip"
                                                onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này không?')">
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

   
@endsection