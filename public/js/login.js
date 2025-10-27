document.addEventListener('DOMContentLoaded', () => {
    const loginModalEl = document.getElementById('loginModal');

    // Khi có lỗi đăng nhập từ server (flag global từ backend)
    if (loginModalEl && window.loginHasErrors) {
        const loginModal = new bootstrap.Modal(loginModalEl);
        loginModal.show();
    }

    // Khi modal bị đóng, reset form & trạng thái lỗi
    if (loginModalEl) {
        loginModalEl.addEventListener('hidden.bs.modal', function () {
            // Reset toàn bộ form trong modal
            this.querySelectorAll('form').forEach(form => form.reset());

            // Xóa class lỗi và thông báo lỗi
            this.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            this.querySelectorAll('.text-danger').forEach(el => el.textContent = '');

            // Xóa alert lỗi đăng nhập (nếu có)
            this.querySelectorAll('.alert.alert-danger').forEach(alert => alert.remove());

            // Đặt lại icon hiển thị mật khẩu
            this.querySelectorAll('.toggle-password').forEach(icon => {
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            });
        });
    }
});

/**
 * Hàm toggle mật khẩu
 * @param {string} id - ID của input password
 * @param {Event} event - Sự kiện click
 */
function togglePassword(id, event) {
    const input = document.getElementById(id);
    const icon = event?.target;
    if (!input || !icon) return;

    const showing = input.type === 'text';
    input.type = showing ? 'password' : 'text';
    icon.classList.toggle('bi-eye', !showing);
    icon.classList.toggle('bi-eye-slash', showing);
}