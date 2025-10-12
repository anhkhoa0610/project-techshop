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
                                <button class="btn btn-info add-new">Thêm thông tin đơn hàng mới
                                </button>
                            </div>
                            <div class="col-sm-4">
                                <h2 class="text-center"><b>Quản Lý thông tin đơn hàng</b></h2>
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
                                <tr >
                                    <td>{{ $detail->order_detail_id }}</td>
                                    <td>{{ $detail->product->product_name }}</td>
                                    <td><img src="{{ asset('images/' . $detail->product->image) }}" alt="{{ $detail->product->product_name }}" width="50"></td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ number_format($detail->unit_price, 2) }}</td>
                                    <td>{{ number_format(($detail->unit_price)*($detail->quantity), 2) }}</td>

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
                  
                </div>
            </div>
        </div>
    </div>


    

    <script>
      
    </script>
@endsection