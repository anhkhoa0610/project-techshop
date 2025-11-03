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
                                        {{-- Nút Xem (Mở Modal View) --}}
                                        <a href="#" class="view" title="Xem" data-toggle="modal" data-target="#viewReviewModal">
                                            <i class="material-icons text-info">&#xE417;</i>
                                        </a>
                                        {{-- Nút Sửa --}}
                                        <a href="{{ route('reviews.edit', $review->review_id) }}" class="edit" title="Sửa">
                                            <i class="material-icons text-warning">&#xE254;</i>
                                        </a>
                                        {{-- Nút Xóa (Mở Modal Delete) --}}
                                        <a href="#deleteReviewModal" class="delete" title="Xóa" data-toggle="modal"
                                            data-target="#deleteReviewModal"
                                            data-url="{{ route('reviews.destroy', $review->review_id) }}"
                                            data-name="Đánh giá của {{ $review->user->full_name ?? 'người dùng' }}">
                                            <i class="material-icons text-danger">&#xE872;</i>
                                        </a>
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

    <!-- Modal Xóa -->
    <div id="deleteReviewModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-confirm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <i class="material-icons modal-icon">priority_high</i>
                    <h4 class="modal-title w-100">Xác nhận xóa</h4>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa đánh giá này không?</p>
                    <p id="modalEntityName" style="font-weight: bold; color: #555;"></p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Xóa</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Khởi tạo biến lưu trữ URL xóa và dòng cần xóa
        let deleteUrl = "";
        let rowToDelete = null;

        // Khi mở modal xác nhận xóa
        $(document).on('show.bs.modal', '#deleteReviewModal', function(event) {
            // Đảm bảo chỉ có một modal backdrop
            if ($('.modal-backdrop').length > 1) {
                $('.modal-backdrop').not(':first').remove();
            }
            const button = $(event.relatedTarget);
            deleteUrl = button.data('url');
            rowToDelete = button.closest('tr');
            const entityName = button.data('name') || 'đánh giá';
            $('#modalEntityName').text(entityName);
            
            console.log('Delete URL:', deleteUrl);
        });

        // Hàm đóng modal đúng cách
        function closeModal() {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
            $('#deleteReviewModal').modal('hide').remove();
        }

        // Khi click nút xác nhận xóa
        $(document).on('click', '#confirmDeleteBtn', function() {
            const $btn = $(this);
            
            if (!deleteUrl) {
                alert('Không tìm thấy URL để xóa!');
                return;
            }

            // Vô hiệu hóa nút và hiển thị trạng thái đang xử lý
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xóa...').prop('disabled', true);

            // Gửi yêu cầu xóa
            $.ajax({
                url: deleteUrl,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Delete success:', response);
                    
                    if (response.success) {
                        // Đóng modal
                        $('body').removeClass('modal-open');
                        $('.modal-backdrop').remove();
                        $('#deleteReviewModal').modal('hide');
                        
                        // Hiển thị thông báo thành công
                        showAlert('success', response.message || 'Đã xóa đánh giá thành công!');
                        
                        // Tự động tải lại trang sau 1 giây
                        setTimeout(function() {
                            window.location.reload();
                        }, 500);
                    } else {
                        showAlert('danger', response.message || 'Không thể xóa đánh giá.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Delete error:', status, error);
                    console.error('Response:', xhr.responseText);
                    
                    let errorMessage = 'Đã xảy ra lỗi khi xóa đánh giá.';
                    
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response && response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {
                        console.error('Error parsing error response:', e);
                    }
                    
                    showAlert('danger', errorMessage);
                },
                complete: function() {
                    // Khôi phục trạng thái nút
                    $btn.html('Xóa').prop('disabled', false);
                    
                    // Đảm bảo modal được đóng đúng cách nếu có lỗi
                    if ($('#deleteReviewModal').length) {
                        closeModal();
                    }
                }
            });
        });
        
        // Hàm hiển thị thông báo
        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>`;
            
            // Thêm thông báo vào đầu container
            $('.container-xl').prepend(alertHtml);
            
            // Tự động ẩn thông báo sau 5 giây
            setTimeout(() => {
                $('.alert').fadeOut(400, function() {
                    $(this).remove();
                });
            }, 5000);
        }
    });
</script>
@endsection