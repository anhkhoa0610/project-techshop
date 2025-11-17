/*
 * FILE: promotion-and-voucher-page.js
 * * ĐÃ TỔ CHỨC LẠI HOÀN CHỈNH:
 * 1. Gom 3 DOMContentLoaded thành 1.
 * 2. Tối ưu việc gọi API cart count ban đầu.
 * 3. Sửa lỗi HTML (</strong>) trong 'loadProducts'.
 * 4. Nâng cấp copyVoucherCode sang SweetAlert.
 * 5. Nâng cấp Pagination, bỏ inline 'onclick'.
 */

// ===================================================================
// CÁC HÀM ĐỊNH NGHĨA TOÀN CỤC
// ===================================================================

/**
 * Tải số lượng giỏ hàng ban đầu (chỉ gọi khi trang không có module promotion)
 */
async function loadInitialCartCount() {
    try {
        // Tận dụng API promotions đã có, chỉ tải 1 item để lấy count
        const response = await fetch(`/api/promotions?page=1&limit=1`);
        const result = await response.json();

        if (result.status === "success" && result.cartItemCount !== undefined) {
            updateCartCountDisplay(result.cartItemCount, false);
        }
    } catch (error) {
        console.error("Lỗi khi lấy số lượng giỏ hàng ban đầu:", error);
    }
}

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
                        Thời hạn: ${new Date(voucher.start_date).toLocaleDateString('vi-VN')} - ${new Date(voucher.end_date).toLocaleDateString('vi-VN')}
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
 * Copy code voucher vào clipboard (ĐÃ NÂNG CẤP LÊN SWEETALERT)
 * @param {string} code
 */
function copyVoucherCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Đã sao chép!',
            text: `Đã sao chép mã: ${code}`,
            timer: 1500,
            showConfirmButton: false
        });
    });
}

/**
 * Tạo phân trang theo kết quả API (ĐÃ NÂNG CẤP, BỎ ONCLICK)
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
            <button class="page-link" data-page="${result.current_page - 1}">
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
                : `<li class="page-item"><button class="page-link" data-page="${i}">${i}</button></li>`;
    }

    // Nút Sau
    if (result.next_page_url) {
        pagHtml += `
        <li class="page-item">
            <button class="page-link" data-page="${result.current_page + 1}">
            Sau &raquo;
            </button>
        </li>`;
    } else {
        pagHtml += `<li class="page-item disabled"><span class="page-link">Sau &raquo;</span></li>`;
    }

    pagHtml += `</ul>`;
    pagination.innerHTML = pagHtml;
}

/**
 * Hàm load sản phẩm khuyến mãi (ĐÃ SỬA LỖI HTML)
 * @param {number} page
 * @param {HTMLElement} promoContainer
 * @param {HTMLElement} xemThemBtn
 * @param {object} state - (Chứa currentPage, lastPage)
 */
async function loadProducts(page = 1, promoContainer, xemThemBtn, state) {
    try {
        const btn = xemThemBtn.querySelector(".btn-load-more");
        const spinner = btn.querySelector(".spinner-border");
        const btnText = btn.querySelector(".btn-text");

        // Hiển thị spinner
        btn.disabled = true;
        spinner.classList.remove("d-none");
        btnText.textContent = "Đang tải...";

        const response = await fetch(`/api/promotions?page=${page}&limit=${state.perPage}`);
        const result = await response.json();

        if (result.status !== "success") {
            console.error("Không thể tải dữ liệu khuyến mãi");
            return;
        }

        // Cập nhật cart count khi load trang 1
        if (page === 1 && result.cartItemCount !== undefined) {
            updateCartCountDisplay(result.cartItemCount, false);
        }

        const items = result.data.products || [];
        state.lastPage = result.data.pagination?.last_page || 1;

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
            const product = item.product || item;
            const discount = item.discount ?? (product.discounts ? product.discounts[0] : null);
            const finalPrice = item.final_price ?? discount?.sale_price ?? product.price ?? 0;

            const id = product.product_id ?? product.id ?? product.productId;
            const discountPercent = discount?.discount_percent || 0;
            const hasDiscount = discountPercent > 0;

            const salePrice = finalPrice;
            const originalPrice = product.price ?? salePrice;

            const endDate = discount?.end_date ?? null;
            const imageUrl = product.cover_image ? `/uploads/${product.cover_image}` : '/images/placeholder.png';
            const productName = product.product_name ?? product.name ?? '';

            // ----- SỬA LỖI HTML & CHÍNH TẢ -----
            const stockHtml = product.stock_quantity
                ? `Số lượng: ${product.stock_quantity}`
                : `<span class="text-secondary fw-bold">Hết hàng</span>`;

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
                            <div class="text-secondary small">${stockHtml}</div>
                            ${endDate ? `<div class="countdown mt-2 text-secondary small" data-end="${endDate}">Đang tính...</div>` : ""}
                        </div>

                        <div class="card-footer bg-transparent border-0 text-center pt-0">
                            ${product.stock_quantity > 0 ?
                    `<button class="btn btn-buy rounded-pill px-3 add-to-cart-btn btn-add-cart" data-product-id="${id}">Thêm vào giỏ hàng</button>`
                    :
                    '<span class="text-secondary fw-bold mb-2 h-25 d-block">Liên hệ sau</span>'
                }
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
        if (state.currentPage >= state.lastPage) {
            xemThemBtn.style.display = "none";
        } else {
            xemThemBtn.style.display = "block";
        }

    } catch (error) {
        console.error("Lỗi khi tải Flash Sale:", error);
    } finally {
        // Ẩn spinner, reset text
        const btn = xemThemBtn.querySelector(".btn-load-more");
        if (btn) {
            btn.disabled = false;
            btn.querySelector(".spinner-border").classList.add("d-none");
            btn.querySelector(".btn-text").textContent = "Xem thêm";
        }
    }
}

/**
 * Bắt đầu đếm ngược cho các sản phẩm
 */
function startCountdown() {
    const countdownElements = document.querySelectorAll('.countdown');
    countdownElements.forEach(el => {
        const rawDate = el.dataset.end;
        if (!rawDate) return;

        // Xóa interval cũ nếu có để tránh lặp
        if (el.dataset.intervalId) {
            clearInterval(el.dataset.intervalId);
        }

        const endTime = new Date(rawDate.replace(' ', 'T')).getTime();
        if (isNaN(endTime)) {
            el.innerHTML = '<span class="text-muted">Không có thời gian kết thúc</span>';
            return;
        }

        const updateTimer = () => {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance <= 0) {
                clearInterval(el.dataset.intervalId);
                el.innerHTML = '<span class="text-danger fw-bold">Đã hết hạn</span>';
                return;
            }

            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            el.innerHTML = `Còn lại: ${hours.toString().padStart(2, '0')}:${minutes
                .toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        };

        updateTimer(); // Chạy 1 lần ngay
        const intervalId = setInterval(updateTimer, 1000); // Lặp lại
        el.dataset.intervalId = intervalId;
    });
}

/**
 * Định dạng tiền tệ
 * @param {number} value
 */
const formatCurrency = (value) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(value);
};

// --- Back To Top Functions ---
function scrollFunction(myButton) {
    if (!myButton) return;
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        myButton.classList.add("show");
    } else {
        myButton.classList.remove("show");
    }
}

function scrollToTop() {
    if (window.scrollTo) {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    } else {
        document.documentElement.scrollTop = 0;
        document.body.scrollTop = 0;
    }
}

// --- Add To Cart Functions ---
async function handleAddToCart(button) {
    const originalButtonHtml = button.innerHTML;
    const productId = button.dataset.productId;
    const quantity = button.dataset.quantity || 1;
    const userId = USER_ID; // Giả định USER_ID là biến toàn cục

    if (!userId || userId === 'null') {
        Swal.fire({
            icon: "warning",
            title: "Bạn cần đăng nhập!",
            text: "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.",
        });
        return;
    }

    button.disabled = true;
    button.innerHTML = `
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Đang thêm...
    `;
    button.classList.add('btn-loading');

    try {
        const response = await fetch("/api/index/add-to-cart", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ user_id: userId, product_id: productId, quantity })
        });

        const text = await response.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error("Không parse được JSON:", text);
            throw new Error("Phản hồi không hợp lệ từ server.");
        }

        if (response.ok) {
            if (data.cartItemCount !== undefined) {
                updateCartCountDisplay(data.cartItemCount);
            }
            Swal.fire({
                icon: "success",
                title: "Thành công!",
                text: data.message || "Đã thêm sản phẩm vào giỏ hàng.",
                timer: 2000,
                showConfirmButton: false,
            });
        } else {
            let errorMessages = data.message || "Đã xảy ra lỗi.";
            if (data.errors) {
                errorMessages = Object.values(data.errors).map(e => e.join(", ")).join("\n");
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
            text: error.message || "Không thể kết nối đến máy chủ.",
        });
    } finally {
        button.disabled = false;
        button.innerHTML = originalButtonHtml;
        button.classList.remove('btn-loading');
    }
}

function updateCartCountDisplay(newCount, useFlash = true) {
    const cartCountElement = document.querySelector('.cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = newCount;
        if (useFlash) {
            cartCountElement.classList.add('cart-flash');
            setTimeout(() => {
                cartCountElement.classList.remove('cart-flash');
            }, 500);
        }
    }
}

// ===================================================================
// KHỐI KHỞI CHẠY CHÍNH (SINGLE DOMCONTENTLOADED)
// ===================================================================
document.addEventListener("DOMContentLoaded", () => {

    // --- MODULE 1: VOUCHERS ---
    const voucherContainer = document.getElementById('voucher-container');
    const voucherPagination = document.getElementById('voucher-pagination');

    if (voucherContainer) {
        // Tải voucher lần đầu
        loadVouchers(1);

        // Gắn listener cho phân trang VOUCHER (Event Delegation)
        if (voucherPagination) {
            voucherPagination.addEventListener('click', (e) => {
                // Chỉ chạy nếu click vào nút .page-link CÓ data-page
                const pageButton = e.target.closest('.page-link[data-page]');
                if (pageButton && !pageButton.closest('.disabled')) {
                    e.preventDefault();
                    const page = pageButton.dataset.page;
                    loadVouchers(page);
                }
            });
        }
    }

    // --- MODULE 2: PROMOTIONS (FLASH SALE) ---
    const promoContainer = document.getElementById("promotion-container");
    if (promoContainer) {

        // Biến trạng thái cục bộ cho module promotion
        const promoState = {
            currentPage: 1,
            perPage: 8,
            lastPage: 1
        };

        // Gắn listener "Add to cart" cho PROMOTION (Event Delegation)
        promoContainer.addEventListener('click', function (e) {
            const cartButton = e.target.closest('.add-to-cart-btn');
            if (cartButton) {
                e.preventDefault();
                handleAddToCart(cartButton);
            }
        });

        // Tạo và chèn nút "Xem Thêm" cho PROMOTION
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

        // Gắn listener cho nút "Xem thêm" PROMOTION
        xemThemBtn.querySelector(".btn-load-more").addEventListener("click", () => {
            promoState.currentPage++;
            loadProducts(promoState.currentPage, promoContainer, xemThemBtn, promoState);
        });

        // Tải sản phẩm promotion lần đầu
        loadProducts(promoState.currentPage, promoContainer, xemThemBtn, promoState);
    }

    // --- MODULE 3: TẢI SỐ LƯỢNG GIỎ HÀNG BAN ĐẦU ---
    // (TỐI ƯU: Chỉ gọi nếu trang này không có module promotion,
    // vì module promotion đã tự lấy cart count ở trên)
    if (!promoContainer) {
        loadInitialCartCount();
    }

    // --- MODULE 4: BACK TO TOP ---
    const myButton = document.getElementById("backToTopBtn");
    if (myButton) {
        // Gắn listener cuộn
        window.onscroll = () => scrollFunction(myButton);

        // Gắn listener click
        myButton.onclick = (e) => {
            e.preventDefault();
            scrollToTop();
        };
    }
});