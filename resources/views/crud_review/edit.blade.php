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
                                    <label class="form-label">Đánh giá</label>
                                <div class="rating">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                            {{ old('rating', $review->rating) == $i ? 'checked' : '' }}/>
                                        <label for="star{{ $i }}"></label>
                                    @endfor
                                </div>
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
  <style>
        .rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }
        .rating input {
            display: none;
        }
        .rating label {
            cursor: pointer;
            width: 30px;
            height: 30px;
            margin: 0 5px;
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z" fill="white" stroke="black" stroke-width="1"/></svg>');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
        }
        .rating label:hover,
        .rating label:hover ~ label,
        .rating input:checked ~ label {
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z" fill="gold"/></svg>');
        }
    </style>