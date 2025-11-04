@extends('layouts.dashboard')

@section('content')
<div class="content">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-user"></i> Thông Tin Chi Tiết Người Dùng
                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th class="w-25">ID</th>
                                <td>{{ $user->user_id }}</td>
                            </tr>
                            <tr>
                                <th>Họ và tên</th>
                                <td>{{ $user->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Số điện thoại</th>
                                <td>{{ $user->phone ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Địa chỉ</th>
                                <td>{{ $user->address ?? '—' }}</td>
                            </tr>
                            <tr>
                                <th>Vai trò</th>
                                <td>
                                    <span class="badge {{ $user->role === 'Admin' ? 'badge-success' : 'badge-primary' }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày sinh</th>
                                <td>{{ \Carbon\Carbon::parse($user->birth)->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Là SV TDC?</th>
                                <td>
                                    <span class="badge {{ $user->is_tdc_student === 'true' ? 'badge-success' : 'badge-secondary' }}">
                                        {{ $user->is_tdc_student === 'true' ? 'Có' : 'Không' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày tạo</th>
                                <td>{{ $user->created_at->format('H:i d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Cập nhật lần cuối</th>
                                <td>{{ $user->updated_at->format('H:i d/m/Y') }}</td>
                            </tr>
                        </table>

                        <div class="text-center mt-4">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại danh sách
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection