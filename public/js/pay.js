let totalPrice = totalAmount;
const firstPrice = totalAmount;
// === Load API địa chỉ Việt Nam ===
const host = "https://provinces.open-api.vn/api/";
const citySelect = document.getElementById("city");
const districtSelect = document.getElementById("district");
const wardSelect = document.getElementById("ward");

async function loadCities() {
    const res = await fetch(host + "?depth=1");
    const data = await res.json();
    citySelect.innerHTML = '<option value="">Chọn tỉnh/thành</option>';
    data.forEach(city => {
        citySelect.innerHTML += `<option value="${city.code}">${city.name}</option>`;
    });
}

async function loadDistricts(cityCode) {
    const res = await fetch(host + "p/" + cityCode + "?depth=2");
    const data = await res.json();
    districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
    data.districts.forEach(d => {
        districtSelect.innerHTML += `<option value="${d.code}">${d.name}</option>`;
    });
}

async function loadWards(districtCode) {
    const res = await fetch(host + "d/" + districtCode + "?depth=2");
    const data = await res.json();
    wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
    data.wards.forEach(w => {
        wardSelect.innerHTML += `<option value="${w.code}">${w.name}</option>`;
    });
}

citySelect.addEventListener("change", () => {
    const cityCode = citySelect.value;
    if (cityCode) loadDistricts(cityCode);
});
districtSelect.addEventListener("change", () => {
    const districtCode = districtSelect.value;
    if (districtCode) loadWards(districtCode);
});
loadCities();

let lastAppliedCode = null; // Mã đã áp dụng trước đó
let originalPrice = null;   // Giá gốc ban đầu

document.addEventListener('DOMContentLoaded', function () {
    const totalPriceEl = document.getElementById('total-price');
    // 🧾 Lưu giá gốc 1 lần duy nhất
    originalPrice = parseInt(totalPriceEl.textContent.replace(/\D/g, ''));
});

document.getElementById('apply-btn').addEventListener('click', function () {
    const code = document.getElementById('voucher').value.trim();
    if (!code) return;

    // 🚫 Nếu mã này đã được áp rồi thì không cho áp lại
    if (code === lastAppliedCode) {
        Swal.fire({
            icon: "info",
            title: "Mã đã áp dụng!",
            text: "Bạn đã áp dụng mã này rồi.",
            timer: 2000,
            showConfirmButton: false,
        });
        return;
    }

    const applyBtn = this;
    applyBtn.disabled = true; // khóa tạm trong lúc fetch
    applyBtn.textContent = "Đang kiểm tra...";

    fetch('/api/voucher/check', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ voucher: code })
    })
        .then(res => res.json())
        .then(data => {
            const totalPriceEl = document.getElementById('total-price');
            const discountEl = document.getElementById('voucher-discount');
            const discountAmountEl = document.getElementById('voucher-amount');
            let total = originalPrice; // 🔁 luôn dùng giá gốc để tính lại
            let discount = 0;

            if (data.valid) {
                const voucherId = data.voucher_id;
                window.appliedVoucherId = voucherId;

                if (data.discount_type === 'percent') {
                    discount = Math.round(total * data.discount_value / 100);
                } else if (data.discount_type === 'amount') {
                    discount = data.discount_value;
                }

                // 🪙 Format giảm giá
                discountAmountEl.textContent = '-' + discount.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
                discountEl.style.display = '';

                // 🪙 Tính và format lại tổng tiền sau giảm
                const finalPrice = Number(originalPrice - discount);
                totalPriceEl.textContent = finalPrice.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });

                totalPrice = finalPrice; // giữ biến cục bộ
                lastAppliedCode = code;  // ✅ Lưu mã đã áp dụng

                Swal.fire({
                    icon: "success",
                    title: "Thành công!",
                    text: data.message || "Áp dụng voucher thành công.",
                    timer: 2000,
                    showConfirmButton: false,
                });
                console.log(data)

            } else {
                // ❌ Voucher không hợp lệ → reset về giá gốc
                discountAmountEl.textContent = '-0 ₫';
                discountEl.style.display = 'none';
                totalPriceEl.textContent = originalPrice.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });

                lastAppliedCode = null;

                Swal.fire({
                    icon: "error",
                    title: "Thất bại!",
                    text: data.message || "Áp dụng voucher không thành công.",
                    timer: 2000,
                    showConfirmButton: false,
                });
            }
        })
        .catch(err => {
            console.error('Voucher check error:', err);
            Swal.fire({
                icon: "error",
                title: "Lỗi!",
                text: "Không thể kiểm tra voucher. Vui lòng thử lại.",
            });
        })
        .finally(() => {
            applyBtn.disabled = false;
            applyBtn.textContent = "Áp dụng";
        });
});

// 🔁 Khi người dùng nhập mã mới → reset tổng tiền về giá gốc
document.getElementById('voucher').addEventListener('input', function () {
    const newCode = this.value.trim();
    console.log(newCode);
    const totalPriceEl = document.getElementById('total-price');
    const discountEl = document.getElementById('voucher-discount');
    const discountAmountEl = document.getElementById('voucher-amount');

    if (newCode !== lastAppliedCode) {
        lastAppliedCode = null;

        // 🧾 Reset lại tổng tiền hiển thị về ban đầu
        totalPriceEl.textContent = originalPrice.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
        discountEl.style.display = 'none';
        discountAmountEl.textContent = '-0 ₫';
    }
});



document.getElementById("payBtn").addEventListener("click", () => {
    const nameInput = document.getElementById("fname");
    const phoneInput = document.getElementById("phone");
    const emailInput = document.getElementById("email");
    const addressInput = document.getElementById("address");

    // Xóa lỗi cũ
    [nameInput, phoneInput, emailInput, addressInput].forEach(i => i.classList.remove("error"));

    // Regex kiểm tra
    const phoneRegex = /^(0|\+84)[0-9]{9}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    // ✅ Kiểm tra nhập liệu
    if (!nameInput.value.trim() || !phoneInput.value.trim() || !emailInput.value.trim() || !addressInput.value.trim()) {
          Swal.fire({
                icon: "error",
                title: "Lỗi!",
                text: "Vui lòng nhập đầy đủ thông tin",
            });
        [nameInput, phoneInput, emailInput, addressInput].forEach(i => {
            if (!i.value.trim()) i.classList.add("error");
        });
        return;
    }
    if (!phoneRegex.test(phoneInput.value)) {
        Swal.fire({
                icon: "error",
                title: "Lỗi!",
                text: "Vui lòng nhập số điện thoại hợp lệ",
            });
        phoneInput.classList.add("error");
        return;
    }
    if (!emailRegex.test(emailInput.value)) {
        Swal.fire({
                icon: "error",
                title: "Lỗi!",
                text: "Vui lòng nhập email hợp lệ",
            });
        emailInput.classList.add("error");
        return;
    }

    // ✅ Lấy vị trí (Tỉnh / Huyện / Xã)
    const cityText = citySelect.options[citySelect.selectedIndex]?.textContent ;
    const districtText = districtSelect.options[districtSelect.selectedIndex]?.textContent ;
    const wardText = wardSelect.options[wardSelect.selectedIndex]?.textContent;

    if(cityText === "Chọn tỉnh/thành" || districtText === "Chọn quận/huyện" || wardText === "Chọn phường/xã") {
       Swal.fire({
                icon: "error",
                title: "Lỗi!",
                text: "⚠️ Vui lòng chọn đầy đủ Tỉnh/Thành, Quận/Huyện, Phường/Xã!",
            });
        return;
    }
    // ✅ Gộp địa chỉ đầy đủ
    const fullShippingAddress = `${addressInput.value.trim()}, ${wardText}, ${districtText}, ${cityText}`;

    // ✅ Phương thức thanh toán
    const selectedMethod = document.querySelector('input[name="pay"]:checked');
    if (!selectedMethod) {
        alert("Vui lòng chọn phương thức thanh toán!");
        return;
    }

    const payMethod = selectedMethod.value; // "momo" | "vnpay"

    // ✅ Gửi form POST sang controller
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = payMethod === "momo" ? momoUrl : vnpayUrl;

    // CSRF token
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = csrfToken;
    form.appendChild(csrf);

    // Tổng tiền
    const totalInput = document.createElement('input');
    totalInput.type = 'hidden';
    totalInput.name = 'total';
    totalInput.value = totalPrice;
    form.appendChild(totalInput);

    // Địa chỉ
    const addrInput = document.createElement('input');
    addrInput.type = 'hidden';
    addrInput.name = 'shipping_address';
    addrInput.value = fullShippingAddress;
    form.appendChild(addrInput);

    const redirectInput = document.createElement('input');
    redirectInput.type = 'hidden';
    redirectInput.name = 'redirect';
    redirectInput.method = 'post';
    redirectInput.value = '1';
    form.appendChild(redirectInput);

    if(window.appliedVoucherId) {
        const voucherInput = document.createElement('input');
        voucherInput.type = 'hidden';
        voucherInput.name = 'voucher_id';
        voucherInput.value = window.appliedVoucherId;
        form.appendChild(voucherInput);
    }



    // Submit form
    document.body.appendChild(form);
    form.submit();
});
