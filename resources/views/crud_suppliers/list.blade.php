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
                                <a href="{{ route('supplier.create') }}" class="btn btn-info add-new">Thêm Mới Nhà Cung Cấp</a>
                            </div>
                            <div class="col-sm-4">
                                <h2 class="text-center"><b>Quản Lý Nhà Phân Phối</b></h2>
                            </div>
                            <div class="col-sm-4">
                                <div class="search-box">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="material-icons">&#xE8B6;</i></span>
                                        <input type="text" class="form-control" placeholder="Search&hellip;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Logo</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suppliers as $supplier)
                                <tr>
                                    <td>{{ $supplier->supplier_id }}</td>
                                    <td>
                                    @if ($supplier->logo)
                                        <img src="{{ asset('images/' . $supplier->logo) }}" alt=""
                                            class="img-fluid rounded shadow" style="max-height: 50px;">
                                    @else
                                        <img src="{{ asset('images/placeholder.png') }}" alt=""
                                            class="img-fluid rounded shadow" style="max-height: 50px;">
                                    @endif
                                    </td>
                                    <td>{{ $supplier->name }}</td>
                                    <td>{{ $supplier->email ?? '—' }}</td>
                                    <td>{{ $supplier->phone ?? '—' }}</td>
                                    <td>{{ $supplier->address ?? '—' }}</td>
                                    <td>{{ $supplier->description ?? '—' }}</td>
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
                        <div class="hint-text">Showing <b>5</b> out of <b>25</b> entries</div>
                        <ul class="pagination">
                            <li class="page-item disabled"><a href="#">Previous</a></li>
                            <li class="page-item"><a href="#" class="page-link">1</a></li>
                            <li class="page-item"><a href="#" class="page-link">2</a></li>
                            <li class="page-item active"><a href="#" class="page-link">3</a></li>
                            <li class="page-item"><a href="#" class="page-link">4</a></li>
                            <li class="page-item"><a href="#" class="page-link">5</a></li>
                            <li class="page-item"><a href="#" class="page-link">Next</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection