<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/login.css') }}">

<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm rounded-4 p-4">

      <div class="modal-header border-0 text-center position-relative pb-0 mb-2">
        <h5 class="modal-title w-100 fw-semibold" id="loginModalLabel">Đăng nhập</h5>
        <button type="button" class="btn-close position-absolute top-0 end-0 me-2 mt-1" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body px-3 pt-3 pb-0">
        <!-- Thông báo lỗi từ API -->
        <div id="loginError" class="alert alert-danger d-none py-2 mb-3 rounded-3 small text-center"></div>

        <form id="loginForm">
          <div class="mb-3">
            <input type="text" name="email" id="email" 
                   class="form-control login-input" 
                   placeholder="Nhập email của bạn">
            <div class="invalid-feedback" id="email-error"></div>
          </div>

          <div class="mb-3 position-relative">
            <input type="password" name="password" id="password"
                   class="form-control login-input"
                   placeholder="Nhập mật khẩu">
            <i class="toggle-password bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y pe-3" 
               style="cursor: pointer;" onclick="togglePassword()"></i>
               <div class="invalid-feedback" id="password-error"></div>
          </div>

          <div class="text-end mb-3">
            <a href="" class="small text-muted text-decoration-none">Quên mật khẩu?</a>
          </div>

          <button type="submit" id="loginBtn" class="btn btn-login w-100">
            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
            ĐĂNG NHẬP
          </button>

          <div class="text-center mt-3 small">
            Chưa có tài khoản?
            <a href="" class="text-primary text-decoration-none">Đăng ký</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="{{ asset('js/login.js') }}"></script>