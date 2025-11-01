@extends('layouts.dashboard')

@section('content')
    <!-- Main Content -->
    <div class="content">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0"><i class="fas fa-edit"></i> Chỉnh Sửa Đánh Giá</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('reviews.update', $review->review_id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="user_id">Người Dùng</label>
                                    <input type="text" class="form-control" id="user_id" value="{{ $review->user->full_name }}" disabled>
                                    <input type="hidden" name="user_id" value="{{ $review->user_id }}">
                                </div>

                                <div class="form-group">
                                    <label for="product_id">Sản Phẩm</label>
                                    <input type="text" class="form-control" id="product_id" value="{{ $review->product->product_name }}" disabled>
                                     <input type="hidden" name="product_id" value="{{ $review->product_id }}">
                                </div>

                                <div class="form-group">
                                    <label for="rating">Đánh giá (sao) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('rating') is-invalid @enderror"
                                           id="rating" name="rating"
                                           value="{{ old('rating', $review->rating) }}" required min="1" max="5">
                                    @error('rating')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="comment">Bình luận</label>
                                    <textarea class="form-control @error('comment') is-invalid @enderror"
                                              id="comment" name="comment" rows="4">{{ old('comment', $review->comment) }}</textarea>
                                    @error('comment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="review_date">Ngày đánh giá <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('review_date') is-invalid @enderror"
                                           id="review_date" name="review_date"
                                           value="{{ old('review_date', \Carbon\Carbon::parse($review->review_date)->format('Y-m-d')) }}" required>
                                    @error('review_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group text-center mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save"></i> Cập Nhật Đánh Giá
                                    </button>
                                    <a href="{{ route('reviews.index') }}" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-arrow-left"></i> Quay Lại
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Main Content -->
@endsection
