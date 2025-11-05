// Chạy code khi tài liệu HTML đã được tải xong
document.addEventListener('DOMContentLoaded', function () {

    // --- Biến tham chiếu ---
    const loader = document.getElementById('loading-overlay');
    const productContainer = document.querySelector('.show-by-category');
    const loadMoreContainer = document.getElementById('load-more-container');

    // --- Hàm điều khiển Loader ---
    function showLoader() {
        if (loader) loader.style.display = 'flex';
    }
    function hideLoader() {
        if (loader) loader.style.display = 'none';
    }

    // ===== TRẠNG THÁI TOÀN CỤC =====
    let currentPage = 1;
    let isLoading = false;
    let hasMorePages = true;
    let currentFilterValues = {};
    // ================================

    /**
     * Hàm render sản phẩm
     */
    function renderProducts(data, isAppend = false) {
        let html = '';

        if (!productContainer) return; // Dừng nếu không có container

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
                    <span class="reviews">(${reviewCount})</span>
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

        // --- SỬA LỖI NULL REFERENCE ---
        // Ẩn/hiện các khu vực khác (một cách an toàn)
        const catProducts = document.querySelector('.categories-products');
        if (catProducts) catProducts.style.display = 'block';

        const newProducts = document.querySelector('.new-products');
        if (newProducts) newProducts.style.display = 'none';

        const saleProducts = document.querySelector('.sale-products');
        if (saleProducts) saleProducts.style.display = 'none';
        // --- KẾT THÚC SỬA LỖI ---
    }

    /**
     * Cập nhật hoặc tạo nút "Xem thêm"
     * (Sử dụng data.to và data.per_page từ Controller)
     */
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
            // Không gán listener ở đây để tránh lặp
        } else {
            // Không còn trang nào, hoặc thiếu dữ liệu -> xóa nút
            loadMoreContainer.innerHTML = '';
        }
    }

    /**
     * Xử lý khi nhấp vào nút "Xem thêm"
     */
    function handleLoadMoreClick() {
        if (!isLoading && hasMorePages) {
            // Tải trang tiếp theo, nối vào (isAppend = true)
            // Không bật overlay (showOverlay = false)
            loadProductsByFilter(currentPage + 1, true, true);
        }
    }

    // --- SỬA LỖI EVENT LISTENER LẶP LẠI ---
    // Gắn 1 trình nghe duy nhất cho container "Xem thêm"
    if (loadMoreContainer) {
        loadMoreContainer.addEventListener('click', function (event) {
            // Chỉ chạy nếu click đúng vào nút có ID "btn-load-more"
            if (event.target && event.target.id === 'btn-load-more') {
                handleLoadMoreClick();
            }
        });
    }
    // --- KẾT THÚC SỬA LỖI ---

    /**
     * Hàm tải sản phẩm (ĐÃ CHỈNH SỬA)
     */
    function loadProductsByFilter(page = 1, isAppend = false, showOverlay = false) {
        if (isLoading) return;
        isLoading = true;

        // Vô hiệu hóa nút "Xem thêm" (nếu có) để tránh click đúp
        const btn = document.getElementById('btn-load-more');
        if (btn) btn.disabled = true;

        if (showOverlay) showLoader();

        const params = new URLSearchParams(currentFilterValues);
        params.append('page', page);

        fetch(`/api/index/filter?${params.toString()}`)
            .then(res => {
                if (!res.ok) { // Kiểm tra nếu server trả về lỗi (404, 500...)
                    throw new Error('Network response was not ok');
                }
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    renderProducts(data, isAppend);
                    currentPage = data.current_page;
                    hasMorePages = data.current_page < data.last_page;

                    // Cập nhật nút sau khi render
                    updateLoadMoreButton(data);
                } else {
                    // Xử lý trường hợp data.success = false
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
                // Không cần kích hoạt lại nút, vì updateLoadMoreButton đã tạo nút mới
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
            loadProductsByFilter(currentPage, false, true); // (page 1, không append, hiện loader)
        });
    }

    // XÓA BỎ TRÌNH NGHE SỰ KIỆN CUỘN
    // window.addEventListener('scroll', ...)

}); // <- Đóng thẻ DOMContentLoaded