<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm">
      <div class="modal-header">
        <h5 class="modal-title fw-bold text-primary" id="loginModalLabel">Đăng nhập</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>

      <form method="POST" action="{{ route('user.authUser') }}">
        @csrf
        <div class="modal-body">
          {{-- Email --}}
          <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="text" name="email" id="email" class="form-control" placeholder="Nhập email" required autofocus>
            @error('email')
                  <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          {{-- Password --}}
          <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Mật khẩu</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Nhập mật khẩu" required>
            @error('password')
                  <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          {{-- Login Error --}}
          @if ($errors->has('login'))
            <div class="alert alert-danger py-1">
              {{ $errors->first('login') }}
            </div>
          @endif

          <div class="d-flex justify-content-between align-items-center mt-3">
            <a href="{{ route('forgot.form') }}" class="small text-decoration-none text-primary">Quên mật khẩu?</a>
            <a href="{{ route('register') }}" class="small text-decoration-none text-primary">Tạo tài khoản</a>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
        </div>
      </form>
    </div>
  </div>
</div>