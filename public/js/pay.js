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


// === THANH TOÁN ===
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
    totalInput.value = totalAmount;
    form.appendChild(totalInput);

    // Địa chỉ
    const addrInput = document.createElement('input');
    addrInput.type = 'hidden';
    addrInput.name = 'shipping_address';
    addrInput.value = fullShippingAddress;
    form.appendChild(addrInput);

    // Submit form
    document.body.appendChild(form);
    form.submit();
});
