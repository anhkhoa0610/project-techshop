// public/js/login.js

// Toggle mật khẩu
function togglePassword() {
    const password = document.getElementById('password');
    const icon = document.querySelector('.toggle-password');
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    } else {
        password.type = 'password';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('loginForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const btn = document.getElementById('loginBtn');
        const spinner = btn.querySelector('.spinner-border');
        const errorDiv = document.getElementById('loginError');

        btn.disabled = true;
        spinner.classList.remove('d-none');
        clearLoginErrors();

        try {
            const response = await axios.post('/api/login', { email, password });

            localStorage.setItem('api_token', response.data.token);
            localStorage.setItem('user', JSON.stringify(response.data.user));

            const modal = bootstrap.Modal.getInstance(document.getElementById('loginModal'));
            modal.hide();

            showToast('Đăng nhập thành công!', 'success');
            setTimeout(() => window.location.reload(), 800);

        } catch (error) {
            const errors = error.response?.data?.errors;
            if (errors) {
                if (errors.email) {
                    const emailInput = document.getElementById('email');
                    document.getElementById('email-error').textContent = errors.email[0];
                    emailInput.classList.add('is-invalid');
                }
                if (errors.password) {
                    const passwordInput = document.getElementById('password');
                    document.getElementById('password-error').textContent = errors.password[0];
                    passwordInput.classList.add('is-invalid');
                }
            } else {
                const message = error.response?.data?.message || 'Đăng nhập thất bại. Vui lòng thử lại.';
                errorDiv.textContent = message;
                errorDiv.classList.remove('d-none');
            }
        } finally {
            btn.disabled = false;
            spinner.classList.add('d-none');
        }
    });

    const loginModal = document.getElementById('loginModal');
    if (loginModal) {
        loginModal.addEventListener('hidden.bs.modal', clearLoginErrors);
    }
});

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.style.transition = 'opacity 0.5s';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 500);
    }, 2500);
}

function clearLoginErrors() {
    //xóa lỗi chung
    const errorDiv = document.getElementById('loginError');
    if (errorDiv) {
        errorDiv.textContent = '';
        errorDiv.classList.add('d-none');
    }
    //xóa lỗi từng trường
    document.querySelectorAll('#loginModal .invalid-feedback').forEach(e => e.textContent = '');
    document.querySelectorAll('#loginModal .is-invalid').forEach(e => e.classList.remove('is-invalid'));

    //Xóa luôn nội dung trong các input
    document.getElementById('password').value = '';
    document.getElementById('email').value = '';
}


// Thêm vào cuối file login.js hoặc file JS chung

axios.interceptors.request.use(config => {
    const token = localStorage.getItem('api_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Xử lý lỗi 401 → tự động logout
// axios.interceptors.response.use(
//     response => response,
//     error => {
//         if (error.response?.status === 401) {
//             localStorage.removeItem('api_token');
//             localStorage.removeItem('user');
//             showToast('Phiên đăng nhập hết hạn. Vui lòng đăng nhập lại.', 'danger');
//             setTimeout(() => {
//                 const modal = new bootstrap.Modal(document.getElementById('loginModal'));
//                 modal.show();
//             }, 1000);
//         }
//         return Promise.reject(error);
//     }
// );  