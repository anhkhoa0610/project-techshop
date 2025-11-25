@extends('layouts.layouts')
@section('title', 'Hồ sơ cá nhân - TechStore')

@section('content')
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <div class="glass-wrapper">
        <div class="animated-bg"></div>
        
        <div class="profile-container mt-5">
            <div class="glass-sidebar">
                <div class="text-center mb-4 user-summary">
                    <div class="avatar-container position-relative d-inline-block">
                     
                        @php
                        $user = auth()->user();
                        $defaultAvatar = asset('images/avatars/user-icon.png');
                        
                        // SỬA LẠI ĐOẠN NÀY: Dùng asset('images/...') thay vì asset('storage/...')
                        $avatarUrl = $user->profile && $user->profile->avatar
                            ? asset('images/' . $user->profile->avatar)
                            : $defaultAvatar;
                    @endphp

                        <img src="{{ $avatarUrl }}" onerror="this.src='{{ $defaultAvatar }}'" alt="Avatar"
                            class="rounded-circle user-avatar glow-effect">

                        {{-- Form upload avatar --}}
                        <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data"
                            class="avatar-upload-form">
                            @csrf
                            <label for="avatar-upload" class="avatar-btn upload-btn">
                                <i class="bi bi-camera-fill"></i>
                                <input type="file" id="avatar-upload" name="avatar" class="d-none" accept="image/*">
                            </label>
                        </form>

                        {{-- Nút xóa avatar --}}
                        @if($user->profile && $user->profile->avatar)
                            <form action="{{ route('profile.avatar.remove') }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="avatar-btn remove-btn"
                                    onclick="return confirm('Xóa ảnh đại diện thật hả bro?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>

                    <h4 class="mt-3 text-neon">{{ $user->full_name }}</h4>
                    @if($user->profile?->bio)
                        <p class="text-glass-muted">{{ $user->profile->bio }}</p>
                    @endif
                </div>

                <div class="sidebar-menu">
                    <ul class="profile-tabs">
                        <li class="glass-tab-item active" onclick="openGlassTab(event, 'infoPanel')">
                            <i class="bi bi-person-badge"></i> Thông tin cá nhân
                        </li>
                        <li class="glass-tab-item" onclick="openGlassTab(event, 'editPanel')">
                            <i class="bi bi-pencil-square"></i> Chỉnh sửa hồ sơ
                        </li>
                        <li class="glass-tab-item" onclick="openGlassTab(event, 'passwordPanel')">
                            <i class="bi bi-shield-lock"></i> Đổi mật khẩu
                        </li>
                        <li class="glass-tab-item" onclick="openGlassTab(event, 'verifyPanel')">
                            <i class="bi bi-patch-check"></i> Xác minh SV TDC
                        </li>
                        <li class="glass-tab-item text-danger mt-3" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                            <i class="bi bi-exclamation-triangle"></i> Xóa tài khoản
                        </li>
                    </ul>
                </div>
            </div>

            <div class="glass-content">
                
                <div id="infoPanel" class="glass-pane active-pane">
                    <h3 class="profile-title">Thông tin cá nhân</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Họ và tên</label>
                            <p>{{ auth()->user()->full_name }}</p>
                        </div>
                        <div class="info-item">
                            <label>Email</label>
                            <p>{{ auth()->user()->email }}</p>
                        </div>
                        <div class="info-item">
                            <label>Ngày sinh</label>
                            <p>{{ auth()->user()->birth ? auth()->user()->birth->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="info-item">
                            <label>Số điện thoại</label>
                            <p>{{ auth()->user()->phone ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="info-item full-width">
                            <label>Địa chỉ</label>
                            <p>{{ auth()->user()->address ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="info-item">
                            <label>Trạng thái sinh viên</label>
                            <span class="badge-status {{ auth()->user()->is_tdc_student === 'true' ? 'success' : 'pending' }}">
                                {{ auth()->user()->is_tdc_student === 'true' ? 'Đã xác minh' : 'Chưa xác minh' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div id="editPanel" class="glass-pane">
                    <h3 class="profile-title">✏️ Chỉnh sửa thông tin</h3>
                    <form action="{{ route('user.updateProfile') }}" method="POST" class="neon-form">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Họ và tên</label>
                                <input type="text" name="full_name" class="glass-input"
                                    value="{{ old('full_name', auth()->user()->full_name) }}" required>
                                @error('full_name') <small class="text-neon-red">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Ngày sinh</label>
                                <input type="date" name="birth" class="glass-input"
                                    value="{{ old('birth', auth()->user()->birth ? auth()->user()->birth->format('Y-m-d') : '') }}" required>
                                @error('birth') <small class="text-neon-red">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 form-group">
                                <label>Số điện thoại</label>
                                <input type="text" name="phone" class="glass-input"
                                    value="{{ old('phone', auth()->user()->phone) }}">
                                @error('phone') <small class="text-neon-red">{{ $message }}</small> @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="glass-input"
                                    value="{{ old('email', auth()->user()->email) }}" required>
                                @error('email') <small class="text-neon-red">{{ $message }}</small> @enderror
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label>Địa chỉ</label>
                            <textarea name="address" class="glass-input" rows="3">{{ old('address', auth()->user()->address) }}</textarea>
                            @error('address') <small class="text-neon-red">{{ $message }}</small> @enderror
                        </div>

                        <div class="mt-4 text-center">
                            <button type="submit" class="btn-neon">💾 Lưu thay đổi</button>
                        </div>
                    </form>
                </div>

                <div id="passwordPanel" class="glass-pane">
                    <h3 class="profile-title">🔐 Thay đổi mật khẩu</h3>
                    <form action="{{ route('user.updatePassword') }}" method="POST" class="neon-form">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label>Mật khẩu hiện tại</label>
                            <input type="password" name="current_password" class="glass-input" required placeholder="******">
                            @error('current_password') <small class="text-neon-red">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Mật khẩu mới</label>
                            <input type="password" name="new_password" class="glass-input" required placeholder="Ít nhất 8 ký tự...">
                            @error('new_password') <small class="text-neon-red">{{ $message }}</small> @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label>Xác nhận mật khẩu mới</label>
                            <input type="password" name="new_password_confirmation" class="glass-input" required placeholder="Nhập lại mật khẩu mới">
                            @error('new_password_confirmation') <small class="text-neon-red">{{ $message }}</small> @enderror
                        </div>

                        <div class="mt-4 text-center">
                            <button type="submit" class="btn-neon btn-green">Cập nhật mật khẩu</button>
                        </div>
                    </form>
                </div>

                <div id="verifyPanel" class="glass-pane">
                    <h3 class="profile-title">🎓 Xác minh Sinh viên TDC</h3>
                    <div class="verify-box">
                        @if (auth()->user()->is_tdc_student !== 'true' && str_ends_with(auth()->user()->email, '@mail.tdc.edu.vn'))
                            
                            @if(session('verification_sent'))
                                <div class="alert glass-alert">
                                    <i class="bi bi-info-circle"></i> Mã xác nhận đã gửi đến <strong>{{ auth()->user()->email }}</strong>.
                                </div>
                            @endif

                            <div class="text-center mb-4">
                                <p>Bạn đang sử dụng email TDC. Vui lòng xác thực để nhận ưu đãi.</p>
                                <form action="{{ route('user.verifyTdc.send') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-neon btn-sm">🚀 Gửi mã xác nhận</button>
                                </form>
                            </div>

                            <hr class="glass-divider">

                            <form action="{{ route('user.verifyTdc.confirm') }}" method="POST" class="mt-4">
                                @csrf
                                <div class="form-group">
                                    <label>Nhập mã xác nhận (6 ký tự)</label>
                                    <div class="d-flex gap-2">
                                        <input type="text" name="verification_code" class="glass-input text-center" 
                                            maxlength="6" required autocomplete="off" style="letter-spacing: 5px; font-weight: bold; font-size: 1.2rem;">
                                        <button type="submit" class="btn-neon btn-green" style="min-width: 100px;">Gửi</button>
                                    </div>
                                    @error('verification_code')
                                        <small class="text-neon-red">{{ $message }}</small>
                                    @enderror
                                </div>
                            </form>

                        @elseif(str_ends_with(auth()->user()->email, '@mail.tdc.edu.vn'))
                            <div class="text-center verified-success">
                                <i class="bi bi-patch-check-fill" style="font-size: 3rem; color: #0f0;"></i>
                                <h4 class="mt-3 text-success-neon">Đã xác thực Sinh viên TDC</h4>
                                <p>Bạn đã có thể hưởng các quyền lợi dành riêng cho sinh viên.</p>
                            </div>
                        @else
                            <div class="text-center verified-error">
                                <i class="bi bi-x-circle" style="font-size: 3rem; color: #ff3333;"></i>
                                <h4 class="mt-3 text-danger-neon">Email không hợp lệ</h4>
                                <p>Chỉ chấp nhận email có đuôi <strong>@mail.tdc.edu.vn</strong> để xác thực.</p>
                                <p>Vui lòng cập nhật lại Email trong phần "Chỉnh sửa hồ sơ".</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div id="deleteUserModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered glass-modal" role="document">
            <div class="modal-content glass-modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title text-danger-neon">
                        ⚠️ CẢNH BÁO XÓA TÀI KHOẢN
                    </h5>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc muốn xóa tài khoản <strong id="userNameToDelete" class="text-white"></strong>?</p>
                    <p class="text-neon-red small">
                        <i class="bi bi-exclamation-triangle-fill"></i> Hành động này không thể hoàn tác! Mọi dữ liệu sẽ biến mất vĩnh viễn.
                    </p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn-neon btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn-neon btn-danger" id="confirmDeleteBtn"
                        data-url="{{ route('user.delete') }}">
                        <span id="deleteBtnText">Xác nhận xóa</span>
                        <span id="deleteBtnSpinner" class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Base & Background */
        .glass-wrapper {
            position: relative;
            min-height: 100vh;
            background-color: #0f1015;
            color: #fff;
            overflow-x: hidden;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .animated-bg {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 10% 20%, rgba(46, 213, 115, 0.1) 0%, transparent 40%),
                        radial-gradient(circle at 90% 80%, rgba(41, 128, 185, 0.15) 0%, transparent 40%);
            animation: moveBg 15s ease-in-out infinite alternate;
            z-index: 0;
        }

        @keyframes moveBg {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }

        .profile-container {
            position: relative;
            z-index: 1;
            display: flex;
            gap: 30px;
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Glass Sidebar */
        .glass-sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 30px 20px;
            height: fit-content;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
        }

        /* Glass Content */
        .glass-content {
            flex: 1;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5);
            min-height: 500px;
        }

        /* Typography & Colors */
        .text-neon { color: #fff; text-shadow: 0 0 10px rgba(255,255,255,0.3); }
        .text-glass-muted { color: rgba(255,255,255,0.6); }
        .text-neon-red { color: #ff4d4d; text-shadow: 0 0 5px rgba(255, 77, 77, 0.5); }
        .text-success-neon { color: #2ed573; text-shadow: 0 0 8px rgba(46, 213, 115, 0.5); }
        .text-danger-neon { color: #ff4757; text-shadow: 0 0 8px rgba(255, 71, 87, 0.5); }

        /* Avatar */
        .user-avatar {
            width: 140px; height: 140px;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.2);
            box-shadow: 0 0 15px rgba(0, 123, 255, 0.3);
        }
        .avatar-btn {
            position: absolute;
            bottom: 5px;
            width: 35px; height: 35px;
            border-radius: 50%;
            border: none;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: #fff;
            transition: all 0.3s;
        }
        .upload-btn { right: 5px; background: #007bff; }
        .remove-btn { left: 5px; background: #dc3545; }
        .avatar-btn:hover { transform: scale(1.1); }

        /* Navigation Tabs */
        .profile-tabs { list-style: none; padding: 0; margin-top: 20px; }
        .glass-tab-item {
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            color: rgba(255,255,255,0.7);
            font-weight: 500;
        }
        .glass-tab-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            padding-left: 20px;
        }
        .glass-tab-item.active {
            background: linear-gradient(90deg, rgba(0, 123, 255, 0.2), transparent);
            border-left: 3px solid #007bff;
            color: #fff;
            text-shadow: 0 0 8px rgba(0, 123, 255, 0.6);
        }
        .glass-tab-item i { margin-right: 10px; }

        /* Tab Content Control */
        /* Đổi tên class từ tab-content -> glass-pane để tránh xung đột Bootstrap */
        .glass-pane { display: none; animation: fadeIn 0.5s ease; }
        .glass-pane.active-pane { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .profile-title {
            font-size: 1.8rem;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: #fff;
        }

        /* Inputs & Forms */
        .glass-input {
            width: 100%;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 12px;
            color: #fff;
            outline: none;
            transition: 0.3s;
        }
        .glass-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
            background: rgba(0, 0, 0, 0.4);
        }
        label { margin-bottom: 8px; font-weight: 500; color: rgba(255,255,255,0.8); }

        /* Buttons */
        .btn-neon {
            background: linear-gradient(45deg, #007bff, #00d2ff);
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.4);
            transition: 0.3s;
        }
        .btn-neon:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.6);
            color: #fff;
        }
        .btn-green { background: linear-gradient(45deg, #2ed573, #7bed9f); box-shadow: 0 4px 15px rgba(46, 213, 115, 0.4); }
        .btn-danger { background: linear-gradient(45deg, #ff4757, #ff6b81); box-shadow: 0 4px 15px rgba(255, 71, 87, 0.4); }
        .btn-secondary { background: rgba(255,255,255,0.1); box-shadow: none; }

        /* View Info Grid */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .info-item { background: rgba(255,255,255,0.03); padding: 15px; border-radius: 10px; }
        .info-item label { display: block; font-size: 0.9rem; color: #aaa; margin-bottom: 5px; }
        .info-item p { margin: 0; font-size: 1.1rem; font-weight: 600; }
        .full-width { grid-column: span 2; }

        .badge-status { padding: 5px 12px; border-radius: 20px; font-size: 0.9rem; font-weight: bold; }
        .badge-status.success { background: rgba(46, 213, 115, 0.2); color: #2ed573; border: 1px solid #2ed573; }
        .badge-status.pending { background: rgba(255, 71, 87, 0.2); color: #ff4757; border: 1px solid #ff4757; }

        /* Modal Customization */
        .glass-modal-content {
            background: #1e2029;
            border: 1px solid rgba(255,255,255,0.1);
            color: #fff;
            box-shadow: 0 0 30px rgba(0,0,0,0.8);
        }
        .glass-divider { border-color: rgba(255,255,255,0.1); }

        /* Responsive */
        @media (max-width: 768px) {
            .profile-container { flex-direction: column; padding: 20px; }
            .glass-sidebar { width: 100%; }
            .info-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- Xử lý Logic PHP ở đây để tránh lỗi ParseError --}}
    @php
        $hasEditErrors = $errors->hasAny(['full_name', 'birth', 'phone', 'email', 'address']);
        $hasPassErrors = $errors->hasAny(['current_password', 'new_password', 'new_password_confirmation']);
        $hasVerifyErrors = $errors->hasAny(['verification_code']) || session('verification_sent');
    @endphp

    <script>
        // Hàm chuyển Tab (Đổi tên để tránh xung đột)
        function openGlassTab(evt, tabId) {
            var i, panes, tabs;
            
            // Ẩn tất cả các pane
            panes = document.getElementsByClassName("glass-pane");
            for (i = 0; i < panes.length; i++) {
                panes[i].style.display = "none";
                panes[i].classList.remove('active-pane');
            }
            
            // Xóa active ở menu sidebar
            tabs = document.getElementsByClassName("glass-tab-item");
            for (i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove("active");
            }
            
            // Hiện pane được chọn
            document.getElementById(tabId).style.display = "block";
            document.getElementById(tabId).classList.add('active-pane');
            
            // Active nút vừa click
            if(evt) {
                evt.currentTarget.classList.add("active");
                localStorage.setItem('activeProfileGlassTab', tabId);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Truyền biến PHP vào JS an toàn
            let hasEditErrors = @json($hasEditErrors);
            let hasPassErrors = @json($hasPassErrors);
            let hasVerifyErrors = @json($hasVerifyErrors);

            let defaultTab = 'infoPanel';

            // Ưu tiên mở tab có lỗi
            if (hasEditErrors) defaultTab = 'editPanel';
            else if (hasPassErrors) defaultTab = 'passwordPanel';
            else if (hasVerifyErrors) defaultTab = 'verifyPanel';
            else {
                // Nếu không lỗi thì lấy từ localStorage
                const storedTab = localStorage.getItem('activeProfileGlassTab');
                if(storedTab) defaultTab = storedTab;
            }

            // Tìm nút menu tương ứng để active
            const tabToActivate = document.querySelector(`.glass-tab-item[onclick*="'${defaultTab}'"]`);
            
            // Reset trạng thái ban đầu
            var panes = document.getElementsByClassName("glass-pane");
            for (var i = 0; i < panes.length; i++) panes[i].style.display = "none";
            
            var tabs = document.getElementsByClassName("glass-tab-item");
            for (var i = 0; i < tabs.length; i++) tabs[i].classList.remove("active");

            // Kích hoạt tab và pane
            const targetPane = document.getElementById(defaultTab);
            if (targetPane) {
                targetPane.style.display = "block";
                targetPane.classList.add("active-pane");
                if (tabToActivate) tabToActivate.classList.add("active");
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const avatarForm = document.querySelector('.avatar-upload-form');
            const fileInput = document.getElementById('avatar-upload');

            if (fileInput) {
                fileInput.addEventListener('change', function () {
                    if (!this.files || !this.files[0]) return;
                    const file = this.files[0];

                    if (!file.type.startsWith("image/")) {
                        Swal.fire({ icon: 'error', title: 'Lỗi file!', text: 'Chỉ chọn ảnh.', background: '#1e2029', color: '#fff' });
                        return;
                    }

                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({ icon: 'error', title: 'Quá lớn!', text: 'Tối đa 2MB.', background: '#1e2029', color: '#fff' });
                        return;
                    }

                    const formData = new FormData(avatarForm);
                    const avatarImg = document.querySelector('.user-avatar');
                    avatarImg.style.opacity = '0.5';

                    fetch(avatarForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) throw new Error(data.message || "Upload thất bại");
                        avatarImg.src = data.avatar_url + "?v=" + Date.now();
                        avatarImg.style.opacity = '1';
                        Swal.fire({ icon: 'success', title: 'Đã cập nhật!', timer: 1500, showConfirmButton: false, background: '#1e2029', color: '#fff' });
                    })
                    .catch(err => {
                        Swal.fire({ icon: 'error', title: 'Lỗi!', text: err.message, background: '#1e2029', color: '#fff' });
                        avatarImg.style.opacity = '1';
                    });
                });
            }
        });
    </script>

    <script>
        $('#deleteUserModal').on('click', '.btn-secondary', function () {
            $('#deleteUserModal').modal('hide');
        });
          $('#deleteUserModal').on('hidden.bs.modal', function () {
            $('body').removeClass('modal-open');
            $('.modal-backdrop').remove();
        });
        $('#deleteUserModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var userName = "{{ auth()->user()->full_name }}"; 
            var modal = $(this);
            modal.find('#userNameToDelete').text(userName);
        });

        $(document).on('click', '#confirmDeleteBtn', function () {
            const deleteUrl = $(this).data('url');
            const btn = $(this);
            const btnText = $('#deleteBtnText');
            const spinner = $('#deleteBtnSpinner');

            btn.prop('disabled', true);
            btnText.addClass('d-none');
            spinner.removeClass('d-none');

            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                dataType: 'json',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (response) {
                    if (response.success) {
                        $('#deleteUserModal').modal('hide');
                        Swal.fire({
                            icon: 'success', title: 'Đã xóa!', text: response.message,
                            background: '#1e2029', color: '#fff'
                        }).then(() => { window.location.href = response.redirect || "{{ route('index') }}"; });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Lỗi!', text: response.message, background: '#1e2029', color: '#fff' });
                    }
                },
                error: function (xhr) {
                    Swal.fire({ icon: 'error', title: 'Lỗi!', text: 'Không thể xóa tài khoản.', background: '#1e2029', color: '#fff' });
                },
                complete: function () {
                    btn.prop('disabled', false);
                    btnText.removeClass('d-none');
                    spinner.addClass('d-none');
                }
            });
        });
    </script>
@endsection