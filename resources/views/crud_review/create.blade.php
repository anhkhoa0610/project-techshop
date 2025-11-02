@extends('layouts.dashboard')

@section('title', 'Thêm Đánh Giá')

@section('content')
<div class="content">
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center"><b>Thêm Đánh Giá Mới</b></h3>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif

                        <form action="{{ route('reviews.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="product_id">Sản Phẩm <span class="text-danger">*</span></label>
                                <select class="form-control @error('product_id') is-invalid @enderror" 
                                        id="product_id" name="product_id" required>
                                    <option value="">-- Chọn sản phẩm --</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->product_id }}" 
                                            {{ old('product_id') == $product->product_id ? 'selected' : '' }}>
                                        {{ $product->product_name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="user_id">Người Đánh Giá <span class="text-danger">*</span></label>
                                <select class="form-control @error('user_id') is-invalid @enderror" 
                                        id="user_id" name="user_id" required>
                                    <option value="">-- Chọn người dùng --</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->user_id }}" 
                                            {{ old('user_id') == $user->user_id ? 'selected' : '' }}>
                                        {{ $user->full_name }} ({{ $user->email }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Đánh giá</label>
                                <div class="rating">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                            {{ old('rating', 5) == $i ? 'checked' : '' }}/>
                                        <label for="star{{ $i }}"></label>
                                    @endfor
                                </div>
                                @error('rating')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="comment">Bình Luận</label>
                                <textarea class="form-control @error('comment') is-invalid @enderror" 
                                          id="comment" name="comment" rows="4" 
                                          maxlength="1000" 
                                          placeholder="Nhập bình luận (tối đa 1000 ký tự)">{{ old('comment') }}</textarea>
                                <small class="form-text text-muted">
                                    <span id="charCount">0</span>/1000 ký tự
                                </small>
                                @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="review_date">Ngày Đánh Giá <span class="text-danger">*</span></label>
                                <input type="date" 
                                       class="form-control @error('review_date') is-invalid @enderror" 
                                       id="review_date" 
                                       name="review_date" 
                                       value="{{ old('review_date', date('Y-m-d')) }}" 
                                       max="{{ date('Y-m-d') }}"
                                       required>
                                @error('review_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Lưu
                                </button>
                                <a href="{{ route('reviews.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Hủy
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
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Character counter for comment
        $('#comment').on('input', function() {
            const length = $(this).val().length;
            $('#charCount').text(length);
        });
        
        // Trigger on page load to show initial count
        $('#comment').trigger('input');
    });
</script>
@endpush