@extends('layouts.dashboard')

@section('title', 'Quản Lý Đánh Giá')

@section('content')
    <div class="content">
        <div class="container-xl">
            <div class="table-responsive text-center">
                <div class="table-wrapper">
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-4">
                                <a href="{{ route('reviews.create') }}" class="btn btn-info add-new">
                                    <i class="fas fa-plus"></i> Thêm Đánh Giá
                                </a>
                            </div>
                            <div class="col-sm-4">
                                <h2 class="text-center"><b>Quản Lý Đánh Giá</b></h2>
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

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Sản Phẩm</th>
                                <th>Người Đánh Giá</th>
                                <th>Đánh giá</th>
                                <th>Bình Luận</th>
                                <th>Ngày Đánh Giá</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                                <tr data-review-id="{{ $review->review_id }}"
                                    data-product-name="{{ $review->product->product_name ?? 'N/A' }}"
                                    data-user-name="{{ $review->user->full_name ?? 'N/A' }}" data-rating="{{ $review->rating }}"
                                    data-comment="{{ $review->comment }}" data-date="{{ $review->review_date }}">
                                    <td>{{ $review->review_id }}</td>
                                    <td>{{ $review->product->product_name ?? 'N/A' }}</td>
                                    <td>{{ $review->user->full_name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="rating-display">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $review->rating)
                                                    <span class="star filled text-warning fs-1">★</span>
                                                @else
                                                    <span class="star text-warning fs-1">☆</span>
                                                @endif
                                            @endfor
                                        </div>
                                    </td>
                                    <td>{{ $review->comment ? Str::limit($review->comment, 1000) : 'N/A' }}</td>
                                    <td>{{ $review->review_date ? $review->review_date->format('d/m/Y') : '—' }}</td>
                                    <td class="text-nowrap">
                                        <a href="#" class="view" title="Xem" data-toggle="modal" data-target="#viewReviewModal">
                                            <i class="material-icons text-info">&#xE417;</i>
                                        </a>
                                        <a href="{{ route('reviews.edit', $review->review_id) }}" class="edit" title="Sửa">
                                            <i class="material-icons text-warning">&#xE254;</i>
                                        </a>
                                        <form action="{{ route('reviews.destroy', $review->review_id) }}" method="POST"
                                            class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline" title="Xóa">
                                                <i class="material-icons text-danger">&#xE872;</i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="clearfix mt-5">
                        <nav>
                            {{ $reviews->withQueryString()->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Review Modal -->
    <div class="modal fade" id="viewReviewModal" tabindex="-1" role="dialog" aria-labelledby="viewReviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewReviewModalLabel">Chi Tiết Đánh Giá</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Sản phẩm:</strong> <span id="view_product"></span></p>
                    <p><strong>Người đánh giá:</strong> <span id="view_user"></span></p>
                    <p><strong>Đánh giá:</strong> <span id="view_rating"></span></p>
                    <p><strong>Bình luận:</strong> <span id="view_comment"></span></p>
                    <p><strong>Ngày đánh giá:</strong> <span id="view_date"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // View review details
            $(document).on('click', '.view', function () {
                const row = $(this).closest('tr');
                $('#view_product').text(row.data('product-name') || '—');
                $('#view_user').text(row.data('user-name') || '—');
                $('#view_rating').text(row.data('rating') || '—');
                $('#view_comment').text(row.data('comment') || '—');
                $('#view_date').text(row.data('date') ? new Date(row.data('date')).toLocaleDateString('vi-VN') : '—');
            });

            // Search functionality with AJAX
            $('#searchInput').on('keyup', function () {
                const searchValue = $(this).val();

                if (searchValue.length >= 3 || searchValue.length === 0) {
                    $.ajax({
                        url: '{{ route("reviews.index") }}',
                        type: 'GET',
                        data: { search: searchValue },
                        success: function (response) {
                            if (response.success) {
                                $('.table-responsive').html($(response.html).find('.table-responsive').html());
                            }
                        },
                        error: function (xhr) {
                            console.log('Error:', xhr);
                            alert('Có lỗi xảy ra khi tìm kiếm!');
                        }
                    });
                }
            });

            // Delete review with AJAX
            $(document).on('submit', '.delete-form', function (e) {
                e.preventDefault();

                if (confirm('Bạn có chắc chắn muốn xóa đánh giá này?')) {
                    const form = $(this);
                    const url = form.attr('action');

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            if (response.success) {
                                $('#searchInput').trigger('keyup');
                                alert(response.message);
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function (xhr) {
                            alert('Có lỗi xảy ra khi xóa đánh giá!');
                        }
                    });
                }
            });
        });
    </script>
@endpush
