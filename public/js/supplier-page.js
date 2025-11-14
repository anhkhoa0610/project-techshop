/*
 * File: public/js/supplier-page.js
 * (ĐÃ CẬP NHẬT THEO LOGIC MỚI CỦA BẠN)
 */
document.addEventListener('DOMContentLoaded', function () {

    const supplierContainer = document.querySelector('.supplier-container');
    if (!supplierContainer) return;
    const supplierId = supplierContainer.dataset.supplierId;
    if (!supplierId) return;

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
    }

    // ----- HÀM HIỂN THỊ SẢN PHẨM -----
    function displayProducts(productItems, supplierData) {
        const grid = document.getElementById('product-grid');
        grid.innerHTML = '';

        if (productItems.length === 0) {
            grid.innerHTML = '<p>Nhà cung cấp này chưa có sản phẩm nào.</p>';
            return;
        }

        productItems.forEach(item => {
            const cardHtml = createProductCard(item, supplierData);
            grid.insertAdjacentHTML('beforeend', cardHtml);
        });
    }

    // ----- HÀM TẠO CARD -----
    function createProductCard(item, supplierData) {
        const product = item;
        const originalPrice = parseFloat(product.price);
        const salePrice = parseFloat(product.sale_price);
        const discountAmount = parseFloat(product.discount_amount);

        let priceOldHtml = '';
        let badgeHtml = '';

        // Nếu có discount (discount_amount > 0)
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

    // ----- LOAD SẢN PHẨM TỪ API -----
    function loadProducts(url) {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if (data.supplier) {
                        updateShopInfo(data.supplier);
                    }
                    displayProducts(data.products, data.supplier || {});
                } else {
                    document.getElementById('shop-name').textContent = data.message || 'Không tìm thấy';
                    document.getElementById('product-grid').innerHTML = '<p>Không thể tải dữ liệu.</p>';
                }
            })
            .catch(error => {
                console.error('Fetch lỗi:', error);
                document.getElementById('product-grid').innerHTML = '<p>Đã có lỗi xảy ra.</p>';
            });
    }

    // ----- XỬ LÝ NÚT SORT -----
    document.getElementById('sort-best-discount').addEventListener('click', function () {
        loadProducts(`/api/supplier/${supplierId}/sort-best-discount`);
        setActiveButton(this);
    });

    document.getElementById('sort-price-asc').addEventListener('click', function () {
        loadProducts(`/api/supplier/${supplierId}/sort-price-asc`);
        setActiveButton(this);
    });

    document.getElementById('sort-price-desc').addEventListener('click', function () {
        loadProducts(`/api/supplier/${supplierId}/sort-price-desc`);
        setActiveButton(this);
    });

    document.getElementById('sort-newest').addEventListener('click', function () {
        loadProducts(`/api/supplier/${supplierId}/sort-newest`);
        setActiveButton(this);
    });

    document.getElementById('sort-best-seller').addEventListener('click', function () {
        loadProducts(`/api/supplier/${supplierId}/sort-best-seller`);
        setActiveButton(this);
    });

    // ----- HÀM ĐẶT NÚT ACTIVE -----
    function setActiveButton(button) {
        document.querySelectorAll('.section-title button').forEach(btn => {
            btn.classList.remove('active');
        });
        button.classList.add('active');
    }

    // ----- LOAD MẶC ĐỊNH LẦN ĐẦU -----
    loadProducts(`/api/supplier/${supplierId}`);
});