// === Biến toàn cục từ Blade (Giả định) ===
// CẦN ĐẢM BẢO CÁC BIẾN NÀY ĐƯỢC IN TRONG BLADE TRƯỚC KHI TẢI FILE JS NÀY
// const momoUrl = "{{ route('momo.payment') }}";
// const vnpayUrl = "{{ route('vnpay.payment') }}";
// const csrfToken = "{{ csrf_token() }}";
// const totalAmount = "{{ $finalSubtotal ?? 0 }}";

// --- Custom Notification System (Thay thế alert) ---
function showNotification(message) {
    const notificationContainer = document.getElementById('notification-container');
    if (!notificationContainer) {
        // Tạo container nếu chưa có
        const div = document.createElement('div');
        div.id = 'notification-container';
        // Định vị ở góc trên bên phải, z-index cao để luôn hiển thị
        div.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 1000;';
        document.body.appendChild(div);
    }
    
    const noti = document.createElement('div');
    noti.textContent = message;
    // Sử dụng CSS để thông báo nổi bật và thân thiện hơn
    noti.style.cssText = 'background-color: #ffe0b2; color: #e65100; padding: 10px 20px; margin-bottom: 10px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-family: Inter, sans-serif; opacity: 0; transition: opacity 0.5s ease-in-out; max-width: 300px; border: 1px solid #ffcc80;';
    
    document.getElementById('notification-container').appendChild(noti);
    
    // Fade in
    setTimeout(() => { noti.style.opacity = '1'; }, 10);

    // Fade out and remove
    setTimeout(() => {
        noti.style.opacity = '0';
        noti.addEventListener('transitionend', () => noti.remove());
    }, 5000);
}


// === 1. Load API địa chỉ Việt Nam ===
const host = "https://provinces.open-api.vn/api/";
const citySelect = document.getElementById("city");
const districtSelect = document.getElementById("district");
const wardSelect = document.getElementById("ward");
const payBtn = document.getElementById("payBtn");

async function loadCities() {
    try {
        const res = await fetch(host + "?depth=1");
        const data = await res.json();
        citySelect.innerHTML = '<option value="">Chọn tỉnh/thành</option>';
        data.forEach(city => {
            citySelect.innerHTML += `<option value="${city.code}">${city.name}</option>`;
        });
    } catch (e) {
        console.error("Lỗi khi tải Tỉnh/Thành phố:", e);
        showNotification("Không thể tải danh sách tỉnh/thành. Vui lòng kiểm tra kết nối.");
    }
}

async function loadDistricts(cityCode) {
    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
    if (!cityCode) return;

    try {
        const res = await fetch(host + "p/" + cityCode + "?depth=2");
        const data = await res.json();
        data.districts.forEach(d => {
            districtSelect.innerHTML += `<option value="${d.code}">${d.name}</option>`;
        });
    } catch (e) {
        console.error("Lỗi khi tải Quận/Huyện:", e);
    }
}

async function loadWards(districtCode) {
    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
    if (!districtCode) return;

    try {
        const res = await fetch(host + "d/" + districtCode + "?depth=2");
        const data = await res.json();
        data.wards.forEach(w => {
            wardSelect.innerHTML += `<option value="${w.code}">${w.name}</option>`;
        });
    } catch (e) {
        console.error("Lỗi khi tải Phường/Xã:", e);
    }
}

// Event Listeners cho các dropdown
citySelect.addEventListener("change", () => {
    const cityCode = citySelect.value;
    if (cityCode) loadDistricts(cityCode);
});
districtSelect.addEventListener("change", () => {
    const districtCode = districtSelect.value;
    if (districtCode) loadWards(districtCode);
});

// Chạy hàm tải thành phố khi script được tải
if (citySelect && districtSelect && wardSelect) {
    loadCities();
}


// === 2. Xử lý Thanh Toán và Gửi Dữ liệu AJAX ===
if (payBtn) {
    payBtn.addEventListener("click", async () => {
        const nameInput = document.getElementById("fname");
        const phoneInput = document.getElementById("phone");
        const emailInput = document.getElementById("email");
        const addressInput = document.getElementById("address");

        // Xóa lỗi cũ
        [nameInput, phoneInput, emailInput, addressInput, citySelect, districtSelect, wardSelect].forEach(i => i.classList.remove("error"));

        // Regex kiểm tra (chấp nhận 9 hoặc 10 số sau 0 hoặc +84)
        const phoneRegex = /^(0|\+84)[0-9]{9,10}$/; 
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let isValid = true;
        let errorTarget = null; // Dùng để focus vào trường lỗi đầu tiên

        // 1. Validation cơ bản & Địa chỉ
        const inputsToValidate = [nameInput, phoneInput, emailInput, addressInput, citySelect, districtSelect, wardSelect];
        
        for (const input of inputsToValidate) {
            if (!input || !input.value || !input.value.trim()) {
                if(input) input.classList.add("error");
                isValid = false;
                if (!errorTarget && input) errorTarget = input;
            }
        }

        // 2. Validation Regex
        if (isValid && phoneInput && phoneInput.value.trim() && !phoneRegex.test(phoneInput.value)) {
            phoneInput.classList.add("error");
            isValid = false;
            showNotification("⚠️ Số điện thoại không hợp lệ!");
            if (!errorTarget) errorTarget = phoneInput;
        }
        if (isValid && emailInput && emailInput.value.trim() && !emailRegex.test(emailInput.value)) {
            emailInput.classList.add("error");
            isValid = false;
            showNotification("⚠️ Email không hợp lệ!");
            if (!errorTarget) errorTarget = emailInput;
        }

        if (!isValid) {
            showNotification("⚠️ Vui lòng nhập đầy đủ và đúng định dạng các thông tin bắt buộc!");
            if (errorTarget) errorTarget.focus();
            return;
        }

        // 3. Lấy dữ liệu thanh toán và địa chỉ chi tiết
        const selectedMethod = document.querySelector('input[name="pay"]:checked');
        if (!selectedMethod) {
            showNotification("Vui lòng chọn phương thức thanh toán!");
            return;
        }

        // Lấy Tên của địa danh (Text content), không phải Mã (Value)
        const cityText = citySelect.options[citySelect.selectedIndex].textContent;
        const districtText = districtSelect.options[districtSelect.selectedIndex].textContent;
        const wardText = wardSelect.options[wardSelect.selectedIndex].textContent;

        // Chuỗi địa chỉ chi tiết để lưu vào cột shipping_address (Controller đã sẵn sàng nhận)
        const fullShippingAddress =
            ` ${addressInput.value.trim()}, ${wardText}, ${districtText}, ${cityText}`;


        const paymentMethod = selectedMethod.value; // 'momo' hoặc 'vnpay' (giả định)
        let actionUrl = '';

        if (paymentMethod === "momo") {
            if (typeof momoUrl === 'undefined') { showNotification('Lỗi cấu hình: momoUrl chưa được định nghĩa.'); return; }
            actionUrl = momoUrl;
        } else if (paymentMethod === "vnpay") {
            if (typeof vnpayUrl === 'undefined') { showNotification('Lỗi cấu hình: vnpayUrl chưa được định nghĩa.'); return; }
            actionUrl = vnpayUrl;
        } else {
            showNotification("Phương thức thanh toán không hợp lệ.");
            return;
        }

        // 4. Tạo Payload dữ liệu
        const payload = {
            _token: (typeof csrfToken !== 'undefined') ? csrfToken : '',
            total: (typeof totalAmount !== 'undefined') ? totalAmount : 0, 
            payment_method: paymentMethod, 
            shipping_address: fullShippingAddress, 
            // voucher_id: ... (nếu có)
        };
        
        // 5. Gửi request POST dưới dạng JSON (phù hợp với Controller đã sửa)
        payBtn.disabled = true;
        showNotification("Đang xử lý thanh toán, vui lòng chờ...");

        try {
            const response = await fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': payload._token, 
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();
            payBtn.disabled = false; // Bật lại nút

            if (response.ok && data.redirect_url) {
                // Chuyển hướng người dùng sang cổng thanh toán
                window.location.href = data.redirect_url; 
            } else {
                // Xử lý lỗi từ Server (MoMo API lỗi hoặc Database lỗi)
                const errorMsg = data.error || 'Đã xảy ra lỗi hệ thống (Server). Vui lòng thử lại.';
                console.error("Lỗi Server: ", data);
                showNotification(errorMsg); 
            }
        } catch (error) {
            payBtn.disabled = false; // Bật lại nút
            const detailedError = (error instanceof TypeError && error.message.includes('Failed to fetch')) 
                                ? 'Kiểm tra đường dẫn URL hoặc kết nối mạng.'
                                : `Lỗi không xác định: ${error.message}`;
            console.error('Lỗi Fetch/Mạng:', error);
            showNotification(`Đã xảy ra lỗi mạng. Chi tiết: ${detailedError}`);
        }
    });
}
