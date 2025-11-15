/*
 * File: public/js/supplier-page.js
 * (Đã cập nhật logic "Xem Thêm")
 */
document.addEventListener('DOMContentLoaded', function () {

    const supplierContainer = document.querySelector('.supplier-container');
    if (!supplierContainer) return;
    const supplierId = supplierContainer.dataset.supplierId;
    if (!supplierId) return;

    // ----- ĐÃ THÊM: Biến trạng thái để phân trang -----
    let currentPage = 1;
    let currentApiUrl = `/api/supplier/${supplierId}`; // URL cơ sở, sẽ cập nhật khi sort
    let isLoading = false; // Ngăn chặn nhiều yêu cầu cùng lúc
    const grid = document.getElementById('product-grid');

    // ----- ĐÃ THÊM: Tạo và chèn nút "Xem Thêm" vào DOM -----
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
    // Chèn nút "Xem thêm" vào ngay sau lưới sản phẩm
    grid.after(xemThemContainer);

    // Lấy tham chiếu đến các phần tử của nút
    const xemThemBtn = xemThemContainer.querySelector('.btn-load-more');
    const btnText = xemThemBtn.querySelector('.btn-text');
    const spinner = xemThemBtn.querySelector('.spinner-border');

    // Ẩn nút đi lúc đầu, chỉ hiện khi API xác nhận còn trang
    xemThemBtn.style.display = 'none';


    // ----- HÀM CẬP NHẬT HEADER (GIỮ NGUYÊN) -----
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

    // ----- HÀM HIỂN THỊ SẢN PHẨM (ĐÃ SỬA) -----
    // Thêm tham số 'append' để biết nên xóa hay nên nối
    function displayProducts(productItems, append = false) {

        if (!append) { // Nếu là sort hoặc load lần đầu, xóa sạch
            grid.innerHTML = '';
        }

        if (productItems.length === 0 && !append) { // Chỉ hiện "chưa có" khi load lần đầu
            grid.innerHTML = '<p>Nhà cung cấp này chưa có sản phẩm nào.</p>';
            return;
        }

        // Nối sản phẩm mới vào
        productItems.forEach(item => {
            const cardHtml = createProductCard(item);
            grid.insertAdjacentHTML('beforeend', cardHtml);
        });
    }

    // ----- HÀM TẠO CARD (GIỮ NGUYÊN) -----
    function createProductCard(item) {
        const product = item;
        const originalPrice = parseFloat(product.price);
        const salePrice = parseFloat(product.sale_price);
        const discountAmount = parseFloat(product.discount_amount);

        let priceOldHtml = '';
        let badgeHtml = '';

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
            <div class="product-card">
                ${badgeHtml}
                <div class="product-image-container">
                    <img src="${product.image}" alt="${product.name}">
                </div>
                <div class="product-info">
                    <span class="product-brand">${product.supplier_name || 'Không rõ'}</span>
                    <h4>${product.name}</h4>

                    <div class="price-area">
                        <div class="price-current">${salePrice.toLocaleString('vi-VN')}đ</div>
                        ${priceOldHtml}
                    </div>
                    <button class="btn-add-to-cart" data-product-id="${product.product_id}">Thêm vào giỏ</button>
                </div>
            </div>
        `;
    }

    // ----- LOAD SẢN PHẨM TỪ API (ĐÃ SỬA LẠI HOÀN TOÀN) -----
    function loadProducts(url, isSortOrInit = false) {
        if (isLoading) return; // Nếu đang tải thì không làm gì
        isLoading = true;

        let fetchUrl = '';

        if (isSortOrInit) {
            // Đây là lần load đầu tiên hoặc một hành động Sort
            currentPage = 1;
            currentApiUrl = url; // Lưu lại URL (để dùng cho "xem thêm")
            fetchUrl = `${currentApiUrl}?page=${currentPage}`;
            // (Tạm thời) ẩn nút "Xem thêm" khi sort
            xemThemBtn.style.display = 'none';
        } else {
            // Đây là hành động "Xem Thêm"
            currentPage++;
            fetchUrl = `${currentApiUrl}?page=${currentPage}`; // Dùng URL sort đã lưu

            // Hiển thị loading trên nút "Xem Thêm"
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
                    if (isSortOrInit && data.supplier) {
                        updateShopInfo(data.supplier);
                    }

                    // Quyết định 'append' hay không
                    displayProducts(data.products, !isSortOrInit);

                    // ----- ĐÃ THÊM: Xử lý hiển thị nút "Xem Thêm" -----
                    // **QUAN TRỌNG**: Giả định API của bạn trả về data.pagination.has_more_pages (boolean)
                    // (Hoặc data.pagination.current_page và data.pagination.last_page)
                    if (data.pagination && data.pagination.has_more_pages === true) {
                        xemThemBtn.style.display = 'block'; // Hiển thị nút
                    } else {
                        xemThemBtn.style.display = 'none'; // Không còn trang, ẩn nút đi
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
                // Reset nút "Xem Thêm" về trạng thái ban đầu
                btnText.textContent = 'Xem thêm';
                spinner.classList.add('d-none');
            });
    }

    // ----- XỬ LÝ NÚT SORT  -----
    // Thêm tham số 'true' để báo đây là hành động Sort/Init
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

    // -----  XỬ LÝ NÚT "XEM THÊM" -----
    xemThemBtn.addEventListener('click', function () {
        // Gọi loadProducts với 'false' để nó hiểu là "xem thêm"
        loadProducts(currentApiUrl, false);
    });


    // ----- HÀM ĐẶT NÚT ACTIVE  -----
    function setActiveButton(button) {
        document.querySelectorAll('.section-title button').forEach(btn => {
            btn.classList.remove('active');
        });
        button.classList.add('active');
    }

    // ----- LOAD MẶC ĐỊNH LẦN ĐẦU -----
    // Thêm 'true' để báo đây là lần load đầu tiên
    loadProducts(`/api/supplier/${supplierId}`, true);
});

// --------------------------------------------------
// PHẦN BACK TO TOP (GIỮ NGUYÊN)
// --------------------------------------------------

// Lấy nút
let myButton = document.getElementById("backToTopBtn");

// Khi người dùng cuộn trang, gọi hàm scrollFunction
window.onscroll = function () {
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
myButton.onclick = function (e) {
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