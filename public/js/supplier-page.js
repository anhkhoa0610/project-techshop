/*
 * File: public/js/supplier-page.js
 * (Đã sửa HOÀN CHỈNH logic Add To Cart)
 */
document.addEventListener('DOMContentLoaded', function () {

    const supplierContainer = document.querySelector('.supplier-container');
    if (!supplierContainer) return;
    const supplierId = supplierContainer.dataset.supplierId;
    if (!supplierId) return;

    // ----- Biến trạng thái -----
    let currentPage = 1;
    let currentApiUrl = `/api/supplier/${supplierId}`;
    let isLoading = false;
    const grid = document.getElementById('product-grid');

    // ----- SỬA LẠI BỘ LẮNG NGHE SỰ KIỆN CLICK -----
    grid.addEventListener('click', function (e) {

        // 1. Kiểm tra xem có click vào nút "Thêm vào giỏ" không?
        const cartButton = e.target.closest('.btn-add-to-cart');
        if (cartButton) {
            // ----- ĐÂY LÀ PHẦN SỬA QUAN TRỌNG -----
            e.preventDefault(); // Ngăn hành vi mặc định của nút (nếu có)

            // GỌI HÀM XỬ LÝ GIỎ HÀNG
            handleAddToCart(cartButton);

            // Return để không chạy code chuyển trang bên dưới
            return;
            // ----------------------------------------
        }

        // 2. Nếu không phải nút giỏ hàng, thì kiểm tra click vào card
        const card = e.target.closest('.product-card');
        if (card) {
            const url = card.dataset.url;
            if (url) {
                window.location.href = url;
            }
        }
    });
    // ----- KẾT THÚC PHẦN SỬA -----

    // ----- Tạo và chèn nút "Xem Thêm" vào DOM -----
    const xemThemContainer = document.createElement('div');
    xemThemContainer.className = "text-center my-3";
    xemThemContainer.innerHTML = `
        <div class="container-btn">
            <button class="btn btn-lg rounded-pill px-4 btn-load-more modern-load-more">
                <i class="fa-solid fa-angles-down me-2"></i>
                <span class="btn-text">Xem thêm</span>
                <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
            </button>
        </div>
    `;
    grid.after(xemThemContainer);

    const xemThemBtn = xemThemContainer.querySelector('.btn-load-more');
    const btnText = xemThemBtn.querySelector('.btn-text');
    const spinner = xemThemBtn.querySelector('.spinner-border');
    xemThemBtn.style.display = 'none';

    // ----- HÀM CẬP NHẬT HEADER -----
    function updateShopInfo(supplier) {
        document.getElementById('shop-logo').src = supplier.logo_url;
        document.getElementById('shop-name').textContent = 'Nhà Phân Phối – ' + supplier.name;
        document.title = supplier.name + ' - TechStore';
        document.getElementById('shop-stat-products').innerHTML = `<strong>Sản phẩm:</strong> ${supplier.product_count}`;
        document.getElementById('shop-stat-join').innerHTML = `<strong>Tham gia:</strong> ${supplier.join_date}`;
        document.getElementById('shop-email').innerHTML = `<strong>Email:</strong> ${supplier.email}`;
        document.getElementById('shop-desc').innerHTML = `<strong>Mô tả:</strong> ${supplier.description}`;
        document.getElementById('shop-phone').innerHTML = `<strong>Số điện thoại:</strong> ${supplier.phone}`;
        document.getElementById('shop-address').innerHTML = `<strong>Địa chỉ:</strong> ${supplier.address}`;
        document.getElementById('shop-stat-orders').innerHTML = `<strong>Đã bán:</strong> ${supplier.total_products_sold}`;
    }

    // ----- HÀM HIỂN THỊ SẢN PHẨM -----
    function displayProducts(productItems, append = false) {
        if (!append) {
            grid.innerHTML = '';
        }
        if (productItems.length === 0 && !append) {
            grid.innerHTML = '<p>Nhà cung cấp này chưa có sản phẩm nào.</p>';
            return;
        }
        productItems.forEach(item => {
            const cardHtml = createProductCard(item);
            grid.insertAdjacentHTML('beforeend', cardHtml);
        });
    }

    // ----- HÀM TẠO CARD (Đã có data-url) -----
    function createProductCard(item) {
        const product = item;
        const detailUrl = `/product-details/${product.product_id}`;
        const originalPrice = parseFloat(product.price);
        const salePrice = parseFloat(product.sale_price);
        const discountAmount = parseFloat(product.discount_amount);

        let priceOldHtml = '';
        let badgeHtml = '';
        const stockHtml = product.stock_quantity
            ? `Số lượng: <span class="text-secondary fw-bold">${product.stock_quantity}</span>`
            : `<span class="text-secondary fw-bold">Hết hàng</span>`;
        if (product.discount && discountAmount > 0) {
            const discountPercent = product.discount.discount_percent || Math.round((discountAmount / originalPrice) * 100);
            badgeHtml = `<div class="product-badge badge-save">Tiết kiệm ${discountAmount.toLocaleString('vi-VN')} đ</div>`;
            priceOldHtml = `
                <div class="price-old">
                    <span class="original-price">${originalPrice.toLocaleString('vi-VN')}đ</span>
                    <span class="discount-percent">-${discountPercent}%</span>
                </div>`;
        }

        return `
            <div class="product-card" data-url="${detailUrl}">
                ${badgeHtml}
                <div class="product-image-container">
                    <img src="${product.image}" alt="${product.name}">
                </div>
                <div class="product-info">
                    <span class="product-brand">${product.supplier_name || 'Không rõ'}</span>
                    <h4>${product.name}</h4>
                    <div class="text-secondary small">${stockHtml}</div>
                    <div class="price-area">
                        <div class="price-current">${salePrice.toLocaleString('vi-VN')}đ</div>
                        ${priceOldHtml}
                    </div>
                    ${product.stock_quantity > 0 ?
                    `<button class="btn-add-cart btn-add-to-cart" data-product-id="${product.product_id}">Thêm vào giỏ</button>` :
                    '<div class="w-100 h-25 d-flex justify-content-center align-items-center"><span class="fw-bold d-block text-secondary">Liên hệ sau</span></div>'
                }
                </div>
            </div>
        `;
    }

    // ----- LOAD SẢN PHẨM TỪ API -----
    function loadProducts(url, isSortOrInit = false) {
        if (isLoading) return;
        isLoading = true;

        let fetchUrl = '';

        if (isSortOrInit) {
            currentPage = 1;
            currentApiUrl = url;
            fetchUrl = `${currentApiUrl}?page=${currentPage}`;
            xemThemBtn.style.display = 'none';
        } else {
            currentPage++;
            fetchUrl = `${currentApiUrl}?page=${currentPage}`;
            btnText.textContent = 'Đang tải...';
            spinner.classList.remove('d-none');
        }

        fetch(fetchUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    //load sô lượng giỏ hàng nếu có
                    if (isSortOrInit && data.cartItemCount !== undefined) {
                        updateCartCountDisplay(data.cartItemCount, false);
                    }

                    if (isSortOrInit && data.supplier) {
                        updateShopInfo(data.supplier);
                    }
                    displayProducts(data.products, !isSortOrInit);

                    if (data.pagination && data.pagination.has_more_pages === true) {
                        xemThemBtn.style.display = 'block';
                    } else {
                        xemThemBtn.style.display = 'none';
                    }
                } else {
                    document.getElementById('shop-name').textContent = data.message || 'Không tìm thấy';
                    document.getElementById('product-grid').innerHTML = '<p>Không thể tải dữ liệu.</p>';
                }
            })
            .catch(error => {
                console.error('Fetch lỗi:', error);
                document.getElementById('product-grid').innerHTML = '<p>Đã có lỗi xảy ra.</p>';
            })
            .finally(() => {
                isLoading = false;
                btnText.textContent = 'Xem thêm';
                spinner.classList.add('d-none');
            });
    }

    // ----- XỬ LÝ NÚT SORT -----
    document.getElementById('sort-best-discount').addEventListener('click', function () {
        loadProducts(`/api/supplier/${supplierId}/sort-best-discount`, true);
        setActiveButton(this);
    });

    document.getElementById('sort-price-asc').addEventListener('click', function () {
        loadProducts(`/api/supplier/${supplierId}/sort-price-asc`, true);
        setActiveButton(this);
    });

    document.getElementById('sort-price-desc').addEventListener('click', function () {
        loadProducts(`/api/supplier/${supplierId}/sort-price-desc`, true);
        setActiveButton(this);
    });

    document.getElementById('sort-newest').addEventListener('click', function () {
        loadProducts(`/api/supplier/${supplierId}/sort-newest`, true);
        setActiveButton(this);
    });

    document.getElementById('sort-best-seller').addEventListener('click', function () {
        loadProducts(`/api/supplier/${supplierId}/sort-best-seller`, true);
        setActiveButton(this);
    });

    // ----- XỬ LÝ NÚT "XEM THÊM" -----
    xemThemBtn.addEventListener('click', function () {
        loadProducts(currentApiUrl, false);
    });

    // ----- HÀM ĐẶT NÚT ACTIVE -----
    function setActiveButton(button) {
        document.querySelectorAll('.section-title button').forEach(btn => {
            btn.classList.remove('active');
        });
        button.classList.add('active');
    }

    // ----- LOAD MẶC ĐỊNH LẦN ĐẦU -----
    loadProducts(`/api/supplier/${supplierId}`, true);
});

// --------------------------------------------------
// PHẦN BACK TO TOP
// --------------------------------------------------
let myButton = document.getElementById("backToTopBtn");

window.onscroll = function () {
    scrollFunction();
};

function scrollFunction() {
    if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
        myButton.classList.add("show");
    } else {
        myButton.classList.remove("show");
    }
}

myButton.onclick = function (e) {
    e.preventDefault();
    scrollToTop();
};

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

// --------------------------------------------------
// PHẦN ADD TO CART
// --------------------------------------------------
async function handleAddToCart(button) {
    // Lưu HTML ban đầu của nút để khôi phục sau
    const originalButtonHtml = button.innerHTML;

    const productId = button.dataset.productId;
    const quantity = button.dataset.quantity || 1;
    const userId = USER_ID; // Giả định USER_ID và csrfToken là biến toàn cục

    if (!userId || userId === 'null') {
        Swal.fire({
            icon: "warning",
            title: "Bạn cần đăng nhập!",
            text: "Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng.",
        });
        return;
    }

    button.disabled = true; // Vô hiệu hóa nút
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
                "X-CSRF-TOKEN": csrfToken // Giả định csrfToken là biến toàn cục
            },
            body: JSON.stringify({ user_id: userId, product_id: productId, quantity })
        });

        const text = await response.text();
        console.log("Phản hồi từ server:", text);

        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            console.error("Không parse được JSON:", e);
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

        // Chỉ thêm class 'cart-flash' nếu useFlash là true
        if (useFlash) {
            cartCountElement.classList.add('cart-flash');
            setTimeout(() => {
                cartCountElement.classList.remove('cart-flash');
            }, 500);
        }
    }
}