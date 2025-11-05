@extends('layouts.dashboard')

@section('title', 'Chi Tiết Đánh Giá')

@section('content')
    <div class="content">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h4 class="mb-0">
                                <i class="fas fa-info-circle"></i> Thông Tin Chi Tiết Đánh Giá
                            </h4>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th class="w-25">Mã đánh giá</th>
                                    <td>{{ $review->review_id }}</td>
                                </tr>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <td>{{ $review->product->product_name ?? 'Không xác định' }}</td>
                                </tr>
                                <tr>
                                    <th>Người đánh giá</th>
                                    <td>{{ $review->user->full_name ?? 'Không xác định' }}</td>
                                </tr>
                                <tr>
                                    <th>Số sao</th>
                                    <td>
                                        <div class="rating-display text-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <span class="text-warning" style="font-size: 22px;">★</span>
                                                @else
                                                    <span class="text-muted" style="font-size: 22px;">☆</span>
                                                @endif
                                            @endfor
                                            <span class="ml-2">({{ $review->rating }}/5)</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Bình luận</th>
<td style="white-space: pre-wrap;">{!! $review->comment ?? 'Không có bình luận' !!}</td>                                </tr>
                                <tr>
                                    <th>Ngày đánh giá</th>
                                    <td>{{ $review->review_date ? $review->review_date->format('d/m/Y') : '—' }}</td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td>{{ $review->created_at->format('H:i d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Cập nhật lần cuối</th>
                                    <td>{{ $review->updated_at->format('H:i d/m/Y') }}</td>
                                </tr>
                            </table>

                            <div class="text-center mt-4">
                                <a href="{{ route('reviews.index') }}" class="btn btn-secondary">
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

<style>
    .table th {
        background-color: #f8f9fa;
        color: #333;
        font-weight: bold;
    }

    .rating-display {
        display: flex;
        align-items: center;
    }
</style>