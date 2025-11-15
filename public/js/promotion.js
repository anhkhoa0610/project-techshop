/**
 * Tải danh sách voucher từ API và hiển thị
 * @param {number} page
 */
async function loadVouchers(page = 1) {
    const container = document.getElementById('voucher-container');
    const pagination = document.getElementById('voucher-pagination');
    if (!container) return;

    showVoucherLoading(3);

    try {
        const res = await fetch(`/api/vouchers?page=${page}`);
        const result = await res.json();

        // Nếu không có data
        if (!result.data || result.data.length === 0) {
            container.innerHTML = `
        <div class="col-12 text-center py-5">
          <p class="text-muted fs-5">Hiện chưa có voucher nào khả dụng.</p>
        </div>`;
            if (pagination) pagination.innerHTML = "";
            return;
        }

        // Render giao diện voucher
        let html = "";
        result.data.forEach(voucher => {
            const discountText =
                voucher.discount_type === "percent"
                    ? `${voucher.discount_value}% OFF`
                    : `Giảm ${Number(voucher.discount_value).toLocaleString()}₫`;

            const isActive = voucher.status === "active";

            html += `
                <div class="col-md-4 col-sm-6 mb-4" style="padding:10px;">
                <div class="voucher-card">
                    <div class="voucher-header">
                    <div class="discount">${discountText}</div>
                    <i class="fa-solid fa-ticket fa-lg"></i>
                    </div>
                    <div class="voucher-body">
                    <div class="code-box">
                        <span class="code-label"><i class="fa-solid fa-tags"></i> Mã:</span>
                        <span class="code">${voucher.code}</span>
                        <button class="copy-btn" data-code="${voucher.code}">
                        <i class="fa-regular fa-copy"></i> Copy
                        </button>
                    </div>
                    <div class="date">
                        <i class="far fa-calendar-alt"></i>
                        Hiệu lực: ${voucher.start_date} - ${voucher.end_date}
                    </div>
                    <span class="voucher-status ${isActive ? "active" : "inactive"}">
                        ${isActive ? "Có hiệu lực" : "không hiệu lực"}
                    </span>
                    </div>
                </div>
                </div>`;
        });

        container.innerHTML = html;

        // Copy event (gắn lại mỗi lần load)
        container.querySelectorAll(".copy-btn").forEach(btn =>
            btn.addEventListener("click", () => copyVoucherCode(btn.dataset.code))
        );

        // Render phân trang
        renderPagination(result, pagination);

    } catch (err) {
        container.innerHTML = `<div class="text-center text-danger mt-5">Lỗi tải dữ liệu voucher!</div>`;
        console.error(err);
    }
}

/**
 * Hiển thị hiệu ứng tải (Skeleton)
 * @param {number} count
 */
function showVoucherLoading(count = 3) {
    const container = document.getElementById('voucher-container');
    if (!container) return;
    let html = "";
    for (let i = 0; i < count; i++) {
        html += `<div class="col-md-4 col-sm-6 mb-4"><div class="voucher-skeleton"></div></div>`;
    }
    container.innerHTML = html;
}

/**
 * Copy code voucher vào clipboard
 * @param {string} code
 */
function copyVoucherCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        alert(`Đã sao chép mã: ${code}`);
    });
}

/**
 * Tạo phân trang theo kết quả API
 * @param {object} result
 * @param {HTMLElement} pagination
 */
function renderPagination(result, pagination) {
    if (!pagination) return;

    let pagHtml = `<ul class="pagination justify-content-center">`;

    // Nút Trước
    if (result.prev_page_url) {
        pagHtml += `
      <li class="page-item">
        <button class="page-link" onclick="loadVouchers(${result.current_page - 1})">
          &laquo; Trước
        </button>
      </li>`;
    } else {
        pagHtml += `<li class="page-item disabled"><span class="page-link">&laquo; Trước</span></li>`;
    }

    // Các số trang
    const start = Math.max(1, result.current_page - 2);
    const end = Math.min(result.last_page, result.current_page + 2);

    for (let i = start; i <= end; i++) {
        pagHtml +=
            i === result.current_page
                ? `<li class="page-item active"><span class="page-link">${i}</span></li>`
                : `<li class="page-item"><button class="page-link" onclick="loadVouchers(${i})">${i}</button></li>`;
    }

    // Nút Sau
    if (result.next_page_url) {
        pagHtml += `
      <li class="page-item">
        <button class="page-link" onclick="loadVouchers(${result.current_page + 1})">
          Sau &raquo;
        </button>
      </li>`;
    } else {
        pagHtml += `<li class="page-item disabled"><span class="page-link">Sau &raquo;</span></li>`;
    }

    pagHtml += `</ul>`;
    pagination.innerHTML = pagHtml;
}

// Gọi khi trang load
document.addEventListener('DOMContentLoaded', () => loadVouchers());

document.addEventListener("DOMContentLoaded", () => {
    const promoContainer = document.getElementById("promotion-container");
    let currentPage = 1;
    const perPage = 8;
    let lastPage = 1; // sẽ cập nhật từ API

    // Tạo nút Xem thêm
    const xemThemBtn = document.createElement('div');
    xemThemBtn.className = "text-center my-3";
    xemThemBtn.innerHTML = `
        <button class="btn btn-lg rounded-pill px-4 btn-load-more modern-load-more">
            <i class="fa-solid fa-angles-down me-2"></i>
            <span class="btn-text">Xem thêm sản phẩm</span>
            <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
        </button>
    `;
    promoContainer.parentNode.appendChild(xemThemBtn);

    // Hàm load sản phẩm
    async function loadProducts(page = 1) {
        try {
            const btn = xemThemBtn.querySelector(".btn-load-more");
            const spinner = btn.querySelector(".spinner-border");
            const btnText = btn.querySelector(".btn-text");

            // Hiển thị spinner
            btn.disabled = true;
            spinner.classList.remove("d-none");
            btnText.textContent = "Đang tải...";

            const response = await fetch(`/api/promotions?page=${page}&limit=${perPage}`);
            const result = await response.json();

            if (result.status !== "success") {
                console.error("Không thể tải dữ liệu khuyến mãi");
                return;
            }

            const items = result.data.products || [];
            lastPage = result.data.pagination?.last_page || 1;

            if (items.length === 0 && page === 1) {
                promoContainer.innerHTML = `
                    <div class="col-12 text-center text-muted py-4">
                        Hiện tại chưa có sản phẩm Flash Sale nào.
                    </div>
                `;
                xemThemBtn.style.display = "none";
                return;
            }

            let html = "";
            items.forEach(item => {
                // Controller trả về mỗi phần tử dưới dạng { product, discount, final_price }
                const product = item.product || item; // fallback nếu cấu trúc khác
                const discount = item.discount ?? (product.discounts ? product.discounts[0] : null);
                const finalPrice = item.final_price ?? discount?.sale_price ?? product.price ?? 0;

                const id = product.product_id ?? product.id ?? product.productId;
                const discountPercent = discount?.discount_percent || 0;
                const hasDiscount = discountPercent > 0;

                // Giá hiển thị
                const salePrice = finalPrice;
                const originalPrice = product.price ?? salePrice;

                const endDate = discount?.end_date ?? null;
                const imageUrl = product.cover_image ? `/uploads/${product.cover_image}` : '/images/no-image.png';
                const productName = product.product_name ?? product.name ?? '';

                html += `
                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                        <div class="card product-card shadow-sm border-0 rounded-4 h-100 d-flex flex-column">
                            
                            <a href="/product-details/${id}" class="text-decoration-none text-dark">
                                <div class="position-relative">
                                    <img src="${imageUrl}" class="card-img-top rounded-top-4" alt="${productName}">
                                    ${discountPercent > 0 ? `<span class="sale-badge position-absolute top-0 start-0 m-2">-${discountPercent}%</span>` : ""}
                                </div>
                                <div class="card-body text-start pb-0">
                                    <h6 class="fw-bold mb-2 product-name">${productName}</h6>
                                </div>
                            </a>
                            
                            <div class="card-body text-start pt-2 d-flex flex-column flex-grow-1">
                                <div class="price-wrapper mt-auto">
                                    <span class="text-danger fw-bold fs-6">${formatCurrency(salePrice)}</span>
                                    
                                    ${(hasDiscount && originalPrice !== salePrice) ? `
                                        <small class="text-muted text-decoration-line-through ms-2">${formatCurrency(originalPrice)}</small>
                                    ` : ""}
                                </div>
                                
                                ${endDate ? `<div class="countdown mt-2 text-secondary small" data-end="${endDate}">Đang tính...</div>` : ""}
                            </div>

                            <div class="card-footer bg-transparent border-0 text-center pt-0">
                                <button class="btn btn-buy rounded-pill px-3 add-to-cart-btn" data-product-id="${id}">Thêm vào giỏ hàng</button>
                            </div>
                        </div>
                    </div>
                `;
            });

            // Append sản phẩm mới
            if (page === 1) {
                promoContainer.innerHTML = html;
            } else {
                promoContainer.insertAdjacentHTML("beforeend", html);
            }

            startCountdown();

            // Ẩn nút Xem thêm nếu hết trang
            if (currentPage >= lastPage) {
                xemThemBtn.style.display = "none";
            } else {
                xemThemBtn.style.display = "block";
            }

            // Ẩn spinner, reset text
            btn.disabled = false;
            spinner.classList.add("d-none");
            btnText.textContent = "Xem thêm";

        } catch (error) {
            console.error("Lỗi khi tải Flash Sale:", error);
        }
    }

    // Click “Xem thêm”
    xemThemBtn.querySelector(".btn-load-more").addEventListener("click", () => {
        currentPage++;
        loadProducts(currentPage);
    });

    // Load trang đầu tiên
    loadProducts(currentPage);
});



function startCountdown() {
    const countdownElements = document.querySelectorAll('.countdown');
    countdownElements.forEach(el => {
        const rawDate = el.dataset.end;
        if (!rawDate) return;

        // Chuẩn hóa định dạng ISO để tránh lỗi parse
        const endTime = new Date(rawDate.replace(' ', 'T')).getTime();
        if (isNaN(endTime)) {
            el.innerHTML = '<span class="text-muted">Không có thời gian kết thúc</span>';
            return;
        }

        const interval = setInterval(() => {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance <= 0) {
                clearInterval(interval);
                el.innerHTML = '<span class="text-danger fw-bold">Đã hết hạn</span>';
                return;
            }

            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            el.innerHTML = `Còn lại: ${hours.toString().padStart(2, '0')}:${minutes
                .toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    });
}

const formatCurrency = (value) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(value);
};

// Lấy nút
let myButton = document.getElementById("backToTopBtn");

// Khi người dùng cuộn trang, gọi hàm scrollFunction
window.onscroll = function() {
    scrollFunction();
};

function scrollFunction() {
    // Hiển thị nút khi cuộn xuống 200px
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        myButton.classList.add("show");
    } else {
        myButton.classList.remove("show");
    }
}

// Khi người dùng nhấn vào nút, cuộn lên đầu trang
myButton.onclick = function(e) {
    e.preventDefault(); // Ngăn hành vi mặc định của thẻ <a>
    scrollToTop();
};

function scrollToTop() {
    // Dành cho các trình duyệt hiện đại
    if (window.scrollTo) {
        window.scrollTo({
            top: 0,
            behavior: 'smooth' /* Đây là chìa khóa cho hiệu ứng mượt mà */
        });
    } 
    // Dành cho các trình duyệt cũ hơn (IE)
    else {
        document.documentElement.scrollTop = 0;
        document.body.scrollTop = 0;
    }
}