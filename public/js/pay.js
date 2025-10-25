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


            // === Sự kiện thanh toán ===
            document.getElementById("payBtn").addEventListener("click", () => {
                const name = document.getElementById("fname");
                const phone = document.getElementById("phone");
                const email = document.getElementById("email");
                const address = document.getElementById("address");

                // Xoá lỗi cũ
                [name, phone, email, address].forEach(i => i.classList.remove("error"));

                // Regex kiểm tra
                const phoneRegex = /^(0|\+84)[0-9]{9}$/;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!name.value.trim() || !phone.value.trim() || !email.value.trim() || !address.value.trim()) {
                    alert("⚠️ Vui lòng nhập đầy đủ thông tin bắt buộc!");
                    [name, phone, email, address].forEach(i => { if (!i.value.trim()) i.classList.add("error"); });
                    return;
                }

                if (!phoneRegex.test(phone.value)) {
                    alert("⚠️ Số điện thoại không hợp lệ!");
                    phone.classList.add("error");
                    return;
                }

                if (!emailRegex.test(email.value)) {
                    alert("⚠️ Email không hợp lệ!");
                    email.classList.add("error");
                    return;
                }
            });