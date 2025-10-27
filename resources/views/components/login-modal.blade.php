<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm rounded-4 p-4">

      <div class="modal-header border-0 text-center position-relative pb-0 mb-2">
        <h5 class="modal-title w-100 fw-semibold" id="loginModalLabel">Login to Your Account</h5>
        <button type="button" class="btn-close position-absolute top-0 end-0 me-2 mt-1" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body px-3 pt-3 pb-0">
        {{-- Hiển thị lỗi đăng nhập thất bại --}}
        @if ($errors->has('login'))
          <div class="alert alert-danger text-center py-2 mb-3 rounded-3 small">
            {{ $errors->first('login') }}
          </div>
        @endif
        
        <form method="POST" action="{{ route('user.authUser') }}">
          @csrf
          <div class="form-group">
            <input type="text" name="email" 
                   class="form-control login-input @error('email') is-invalid @enderror" 
                   placeholder="Please enter your Email"
                   value="{{ old('email') }}">
            @error('email')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <!-- Password -->
          <div class="form-group position-relative">
            <input type="password" name="password" id="password"
                   class="form-control login-input @error('password') is-invalid @enderror"
                   placeholder="Please enter your password">
            <i class="toggle-password bi bi-eye-slash" onclick="togglePassword('password', event)"></i>
            @error('password')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <!-- Forgot password -->
          <div class="text-end mb-3">
            <a href="{{ route('forgot.form') }}" class="small text-decoration-none text-muted">Forgot password?</a>
          </div>

          <!-- Login button -->
          <button type="submit" class="btn btn-login w-100">LOGIN</button>

          <!-- Signup -->
          <div class="text-center mt-3 small">
            Don’t have an account?
            <a href="{{ route('register') }}" class="text-primary text-decoration-none">Sign up</a>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>window.loginHasErrors = @json($errors->any());</script>
<script src="{{ asset('js/login.js') }}"></script>
