document.addEventListener('DOMContentLoaded', function () {
    function updateCurrentFilterValues() {
        if (!filterForm) return;

        currentFilterValues = {
            min_price: document.querySelector('[name="price_min"]').value.trim(),
            max_price: document.querySelector('[name="price_max"]').value.trim(),
            category_id: document.querySelector('[name="category_filter"]').value,
            supplier_id: document.querySelector('[name="supplier_filter"]').value,
            stock: document.querySelector('[name="stock_filter"]').value,
            rating: document.querySelector('[name="rating_filter"]').value,
            release_date: document.querySelector('[name="release_filter"]').value,
        };
    }

    const loader = document.getElementById('loading-overlay');
    const productContainer = document.querySelector('.show-by-category');
    const loadMoreContainer = document.getElementById('load-more-container');

    function showLoader() {
        if (loader) loader.style.display = 'flex';
    }
    function hideLoader() {
        if (loader) loader.style.display = 'none';
    }

    let currentPage = 1;
    let isLoading = false;
    let hasMorePages = true;
    let currentFilterValues = {};

    function renderProducts(data, isAppend = false) {
        let html = '';

        if (!productContainer) return;

        // Hiển thị thông báo nếu không có sản phẩm
        if (!data.data || data.data.length === 0 && !isAppend) {
            productContainer.innerHTML = '<p style="color: white; text-align: center;">Không tìm thấy sản phẩm nào phù hợp.</p>';
        } else {
            // Tạo HTML cho sản phẩm
            data.data.forEach(product => {
                const avgRating = product.reviews_avg_rating ? parseFloat(product.reviews_avg_rating).toFixed(1) : '0.0';
                const reviewCount = product.reviews_count || 0;
                let starsHtml = `
                    <span class="stars" style="color: #ffc107;">⭐</span>
                    <span class="rating-score">${avgRating}</span>
                    <span class="reviews">(${reviewCount} reviews)</span>
                `;

                html += `
                <div class="product-card">
                    <div class="product-image">
                        <img src="${product.cover_image ? '/uploads/' + product.cover_image : '/images/place-holder.jpg'}" alt="${product.product_name}">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">${product.product_name}</h3>
                        <div class="product-rating">${starsHtml}</div>
                        <div class="product-price">
                            <span class="current-price">${Number(product.price).toLocaleString('vi-VN')}₫</span>
                        </div>
                        <div class="product-meta">
                            <div class="volume-sold">📅 <strong>Đã bán: </strong> ${product.volume_sold || 0} sản phẩm</div>
                            <div class="release-date">📅 <strong>Phát hành:</strong> ${product.release_date ? new Date(product.release_date).toLocaleDateString('vi-VN') : 'Chưa rõ'}</div>
                            <div class="stock-info">📦 <strong>Tồn kho:</strong> ${product.stock_quantity > 0 ? product.stock_quantity + ' sản phẩm' : '<span style="color:red;">Hết hàng</span>'}</div>
                        </div>
                    </div>
                    <button class="btn-add-cart btn btn-primary full-width" data-product-id="${product.product_id}" data-quantity="1">Thêm vào giỏ 🛒 </button>
                </div>
                `;
            });

            // Nối hoặc thay thế HTML
            if (isAppend) {
                productContainer.innerHTML += html; // Nối thêm
            } else {
                productContainer.innerHTML = html; // Thay thế
                if (loadMoreContainer) loadMoreContainer.innerHTML = '';
            }
        }
    }

    function updateLoadMoreButton(data) {
        if (!loadMoreContainer) return;

        // Kiểm tra xem các key cần thiết có tồn tại không
        const hasAllData = data.to !== undefined && data.per_page !== undefined && data.total !== undefined;

        if (data.current_page < data.last_page && hasAllData) {
            // Tính toán số liệu cho nút
            const remaining = data.total - data.to;
            const nextBatch = Math.min(data.per_page, remaining);

            // Tạo nút
            loadMoreContainer.innerHTML = `
                <button id="btn-load-more" class="btn btn-outline-light btn-lg">
                    Xem thêm ${nextBatch} / ${remaining} sản phẩm
                </button>
            `;
        } else {
            loadMoreContainer.innerHTML = '';
        }
    }

    function handleLoadMoreClick() {
        if (!isLoading && hasMorePages) {
            loadProductsByFilter(currentPage + 1, true, true);
        }
    }

    if (loadMoreContainer) {
        loadMoreContainer.addEventListener('click', function (event) {
            if (event.target && event.target.id === 'btn-load-more') {
                handleLoadMoreClick();
            }
        });
    }

    function loadProductsByFilter(page = 1, isAppend = false, showOverlay = false) {
        if (isLoading) return;
        isLoading = true;

        const btn = document.getElementById('btn-load-more');
        if (btn) btn.disabled = true;

        if (showOverlay) showLoader();

        const params = new URLSearchParams(currentFilterValues);
        params.append('page', page);

        fetch(`/api/index/filter?${params.toString()}`)
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not ok');
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    renderProducts(data, isAppend);
                    currentPage = data.current_page;
                    hasMorePages = data.current_page < data.last_page;

                    updateLoadMoreButton(data);
                } else {
                    if (!isAppend) {
                        productContainer.innerHTML = '<p style="color: white; text-align: center;">Không tìm thấy sản phẩm nào phù hợp.</p>';
                    }
                    if (loadMoreContainer) loadMoreContainer.innerHTML = '';
                }
            })
            .catch(error => {
                console.error('Lỗi khi tải sản phẩm:', error);
                if (loadMoreContainer) loadMoreContainer.innerHTML = '<p style="color:red; font-weight: bold;">Lỗi tải dữ liệu. Vui lòng thử lại.</p>';
            })
            .finally(() => {
                isLoading = false;
                if (showOverlay) hideLoader();
            });
    }

    /**
     * Trình nghe sự kiện submit form
     */
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // Lấy tất cả giá trị từ form
            currentFilterValues = {
                min_price: document.querySelector('[name="price_min"]').value.trim(),
                max_price: document.querySelector('[name="price_max"]').value.trim(),
                category_id: document.querySelector('[name="category_filter"]').value,
                supplier_id: document.querySelector('[name="supplier_filter"]').value,
                stock: document.querySelector('[name="stock_filter"]').value,
                rating: document.querySelector('[name="rating_filter"]').value,
                release_date: document.querySelector('[name="release_filter"]').value
            };

            // Reset trạng thái và tải lại từ đầu
            currentPage = 1;
            hasMorePages = true;
            loadProductsByFilter(currentPage, false, true); 
        });
    }

    const priceSlider = document.getElementById('price-slider');

    if (priceSlider) {

        const minPriceDisplay = document.getElementById('min-price-display');
        const maxPriceDisplay = document.getElementById('max-price-display');

        const minPriceHidden = document.querySelector('[name="price_min"]');
        const maxPriceHidden = document.querySelector('[name="price_max"]');

        const moneyFormat = wNumb({
            decimals: 0,
            thousand: '.',
            suffix: 'đ'
        });

        const defaultMin = minPriceHidden.value || 0;
        const defaultMax = maxPriceHidden.value || 50000000;

        noUiSlider.create(priceSlider, {
            start: [defaultMin, defaultMax],
            connect: true,
            step: 100000,
            range: {
                'min': 0,
                'max': 50000000
            }
        });

        priceSlider.noUiSlider.on('update', function (values, handle) {

            let minVal = parseFloat(values[0]);
            let maxVal = parseFloat(values[1]);

            minPriceDisplay.value = moneyFormat.to(minVal);
            maxPriceDisplay.value = moneyFormat.to(maxVal);

            minPriceHidden.value = minVal;
            maxPriceHidden.value = maxVal;
        });
    }

    const resetButton = document.querySelector('.btn-filter-reset');

    if (resetButton && filterForm) {
        resetButton.addEventListener('click', () => {
            filterForm.querySelectorAll('select').forEach(select => {
                select.value = '';
            });
            if (priceSlider) {
                priceSlider.noUiSlider.set([0, 50000000]);
            }
        });
    }

    updateCurrentFilterValues();
}); // <- Đóng thẻ DOMContentLoaded