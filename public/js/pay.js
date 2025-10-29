// === Load API địa chỉ Việt Nam ===
const host = "https://provinces.open-api.vn/api/";
const citySelect = document.getElementById("city");
const districtSelect = document.getElementById("district");
const wardSelect = document.getElementById("ward");

// --- Load danh sách tỉnh/thành ---
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

// === XỬ LÝ THANH TOÁN ===
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

    // ✅ Kiểm tra bắt buộc
    if (!nameInput.value.trim() || !phoneInput.value.trim() || !emailInput.value.trim() || !addressInput.value.trim()) {
        alert("⚠️ Vui lòng nhập đầy đủ thông tin!");
        [nameInput, phoneInput, emailInput, addressInput].forEach(i => {
            if (!i.value.trim()) i.classList.add("error");
        });
        return;
    }

    if (!phoneRegex.test(phoneInput.value)) {
        alert("⚠️ Số điện thoại không hợp lệ!");
        phoneInput.classList.add("error");
        return;
    }

    if (!emailRegex.test(emailInput.value)) {
        alert("⚠️ Email không hợp lệ!");
        emailInput.classList.add("error");
        return;
    }

    // ✅ Lấy vị trí (Tỉnh / Huyện / Xã)
    const cityText = citySelect.options[citySelect.selectedIndex]?.textContent || "";
    const districtText = districtSelect.options[districtSelect.selectedIndex]?.textContent || "";
    const wardText = wardSelect.options[wardSelect.selectedIndex]?.textContent || "";

    // ✅ Gộp thành địa chỉ chi tiết
    const fullShippingAddress =
        `${addressInput.value.trim()}, ${wardText}, ${districtText}, ${cityText}`;

    // ✅ Kiểm tra phương thức thanh toán
    const selectedMethod = document.querySelector('input[name="pay"]:checked');
    if (!selectedMethod) {
        alert("Vui lòng chọn phương thức thanh toán!");
        return;
    }

    const payMethod = selectedMethod.value; // ví dụ: momo / vnpay / cod

    // ✅ Chọn URL endpoint theo phương thức thanh toán
    let actionUrl;
    if (payMethod === "momo") {
        actionUrl = momoUrl;
    } else if (payMethod === "vnpay") {
        actionUrl = vnpayUrl;
    } else {
        actionUrl = codUrl; // thanh toán khi nhận hàng
    }

    // ✅ Gửi form POST động
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = actionUrl;

    // Thêm token CSRF
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = csrfToken;
    form.appendChild(csrf);

    // Thông tin người dùng
    const userFields = {
        name: nameInput.value.trim(),
        phone: phoneInput.value.trim(),
        email: emailInput.value.trim(),
        shipping_address: fullShippingAddress,
        payment_method: payMethod,
    };

    for (const [key, value] of Object.entries(userFields)) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    }

    // Tổng tiền
    const totalInput = document.createElement('input');
    totalInput.type = 'hidden';
    totalInput.name = 'total';
    totalInput.value = totalAmount;
    form.appendChild(totalInput);

    // Giỏ hàng (dạng JSON)
    const cartInput = document.createElement('input');
    cartInput.type = 'hidden';
    cartInput.name = 'cart';
    cartInput.value = JSON.stringify(cartItems);
    form.appendChild(cartInput);

    // Gửi form
    document.body.appendChild(form);
    form.submit();
});
