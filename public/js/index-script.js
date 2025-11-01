
// Debounce function
function debounce(fn, delay) {
    let timer = null;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), delay);
    };
}

// Search
const searchInput = document.getElementById('header-search-input');
const searchResults = document.getElementById('search-results');

const handleSearch = function () {
    const query = this.value.trim();
    if (query.length < 2) {
        searchResults.style.display = 'none';
        searchResults.innerHTML = '';
        return;
    }
    fetch(`/api/index/search?keyword=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => {
            let html = '';
            if (data.status === 'success' && data.data.length) {
                data.data.forEach(product => {
                    html += `
                    <div class="result-item" onclick="window.location.href='/products/${product.product_id}'">
                        <div class="result-thumb">
                            <img src="${product.cover_image ? '/uploads/' + product.cover_image : '/images/place-holder.png'}" alt="${product.product_name}">
                        </div>
                        <div class="result-info">
                            <div class="result-title">${product.product_name}</div>
                            <div class="result-price">${Number(product.price).toLocaleString('vi-VN')}₫</div>
                        </div>
                    </div>
                    `;
                });
            } else {
                html = `<div class="no-result">Không tìm thấy sản phẩm phù hợp.</div>`;
            }
            searchResults.innerHTML = html;
            searchResults.style.display = 'block';
            searchResults.classList.add('active');
        });
};

// Sử dụng debounce cho sự kiện input
searchInput.addEventListener('input', debounce(handleSearch, 400));

// Ẩn kết quả khi blur
searchInput.addEventListener('blur', function () {
    setTimeout(() => { searchResults.style.display = 'none'; }, 200);
});

// Hiện lại khi focus nếu có kết quả
searchInput.addEventListener('focus', function () {
    if (searchResults.innerHTML.trim()) searchResults.style.display = 'block';
});


document.addEventListener('click', (e) => {
    if (!e.target.closest('.search-box')) {
        searchResults.classList.remove('active');
    }
});

document.addEventListener("click", debounce((event) => {
    const button = event.target.closest(".btn-add-cart");
    if (button) handleAddToCart(button);
}, 500));

// Thêm vào giỏ hàng
const addCartButtons = document.querySelectorAll(".btn-add-cart");
async function handleAddToCart(button) {
    const productId = button.dataset.productId;
    const quantity = button.dataset.quantity || 1;
    const userId = USER_ID;

    if (!userId || userId === 'null') {
        Swal.fire({
            icon: "warning",
            title: "Bạn cần đăng nhập!",
            text: "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.",
        });
        return;
    }

    try {
        const response = await fetch("/api/index/add-to-cart", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json" // 🔹 Quan trọng
            },
            body: JSON.stringify({ user_id: userId, product_id: productId, quantity })
        });

        // Đọc text trước để tránh lỗi JSON parse
        const text = await response.text();
        console.log("Phản hồi từ server:", text);

        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error("Không parse được JSON:", e);
            Swal.fire({
                icon: "error",
                title: "Lỗi hệ thống!",
                text: "Phản hồi không hợp lệ từ server.",
            });
            return;
        }

        if (response.ok) {
            Swal.fire({
                icon: "success",
                title: "Thành công!",
                text: data.message || "Đã thêm sản phẩm vào giỏ hàng.",
                timer: 2000,
                showConfirmButton: false,
            });
        } else {
            // 🔸 Gộp lỗi validation
            let errorMessages = "";
            if (data.errors) {
                for (const key in data.errors) {
                    errorMessages += `${data.errors[key].join(", ")}\n`;
                }
            } else {
                errorMessages = data.message || "Đã xảy ra lỗi.";
            }

            Swal.fire({
                icon: "error",
                title: "Lỗi!",
                html: errorMessages.replace(/\n/g, "<br>")
            });
        }
    } catch (error) {
        console.error("Fetch error:", error);
        Swal.fire({
            icon: "error",
            title: "Lỗi hệ thống!",
            text: "Không thể kết nối đến máy chủ.",
        });
    }
}