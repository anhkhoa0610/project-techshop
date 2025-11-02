function renderProductsAndPaginationFilter(data, min_price, max_price, category_id, supplier_id, stock, rating, release_date) {
    let html = '';
    data.data.forEach(product => {
        const avgRating = product.reviews_avg_rating ? parseFloat(product.reviews_avg_rating).toFixed(1) : '0.0';
        const reviewCount = product.reviews_count || 0;

        // ⭐ tạo hiển thị sao
        let stars = '';
        for (let i = 1; i <= 5; i++) {
            stars += i <= Math.round(product.reviews_avg_rating || 0) ? '⭐' : '☆';
        }

        html += `
        <div class="product-card">
            <div class="product-image">
                <img src="${product.cover_image ? '/uploads/' + product.cover_image : '/images/place-holder.jpg'}" alt="${product.product_name}">
            </div>
            <div class="product-info">
                <h3 class="product-name">${product.product_name}</h3>
                <div class="product-rating">
                    <span class="stars">${stars}</span>
                    <span class="rating-score">${avgRating}</span>
                    <span class="reviews">(${reviewCount})</span>
                </div>
                <div class="product-price">
                    <span class="current-price">${Number(product.price).toLocaleString('vi-VN')}₫</span>
                </div>

                <div class="product-meta">
                    <div class="release-date">
                        📅 <strong>Phát hành:</strong> ${product.release_date ? new Date(product.release_date).toLocaleDateString('vi-VN') : 'Chưa rõ'}
                    </div>
                    <div class="stock-info">
                        📦 <strong>Tồn kho:</strong> ${product.stock_quantity > 0 ? product.stock_quantity + ' sản phẩm' : '<span style="color:red;">Hết hàng</span>'}
                    </div>
                </div>
            </div>
            <button class="btn-add-cart btn btn-primary full-width" data-product-id="${product.product_id}" data-quantity="1">Thêm vào giỏ 🛒 </button>
        </div>
        `;
    });

    document.querySelector('.show-by-category').innerHTML = html;
    document.querySelector('.categories-products').style.display = 'block';
    document.querySelector('.new-products').style.display = 'none';
    document.querySelector('.sale-products').style.display = 'none';
    let filter_pagination = '';
    if (data.last_page && data.last_page > 1) {
        // Sử dụng flex để căn giữa các nút và số trang
        filter_pagination += `<nav class="filter-pagination flex items-center justify-center space-x-4">`;

        // 1. Nút "Trước" (Prev)
        if (data.current_page > 1) {
            // Nút hoạt động
            filter_pagination += `<button class="mx-3 mb-2 filter-btn btn btn-outline-dark" data-page="${data.current_page - 1}">Prev</button>`;
        } else {
            // Nút vô hiệu hóa (disabled)
            filter_pagination += `<button class="mx-3 mb-2 filter-btn btn btn-outline-dark opacity-50 cursor-not-allowed" disabled>Prev</button>`;
        }

        // 2. Hiển thị Trang Hiện Tại / Tổng Số Trang (dưới dạng ô input)
        filter_pagination += `
            <span class="page-indicator">
            <span style="color: white">
                Pages:
                </span>               
                <input 
                    type="number" 
                    id="filter-page-input"
                    value="${data.current_page}" 
                    min="1" 
                    max="${data.last_page}" 
                    aria-label="Nhập số trang để chuyển đến"
                /> 
                <span style="color: white">/ ${data.last_page}</span>
            </span>
        `;

        // 3. Nút "Tiếp" (Next)
        if (data.current_page < data.last_page) {
            // Nút hoạt động
            filter_pagination += `<button class="mx-3 mb-2 filter-btn btn btn-outline-dark" data-page="${data.current_page + 1}">Next</button>`;
        } else {
            // Nút vô hiệu hóa (disabled)
            filter_pagination += `<button class="mx-3 mb-2 filter-btn btn btn-outline-dark opacity-50 cursor-not-allowed" disabled>Next</button>`;
        }

        filter_pagination += `</nav>`;
    }
    document.querySelector('.pagination').innerHTML = filter_pagination;

    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.onclick = function () {
            if (!this.disabled) {
                loadProductsByFilter(min_price, max_price, category_id, supplier_id, stock, rating, release_date, parseInt(this.dataset.page));
            }
        };
    });

    const pageInput = document.getElementById('filter-page-input');
    if (pageInput) {
        pageInput.addEventListener('keyup', function (event) {
            if (event.key === 'Enter') {
                let page = parseInt(this.value);
                const lastPage = data.last_page;
                if (isNaN(page) || page < 1) page = 1;
                else if (page > lastPage) page = lastPage;
                this.value = page;
                if (page !== data.current_page) {
                    loadProductsByFilter(min_price, max_price, category_id, supplier_id, stock, rating, release_date, page);
                }
            }
        });
    }
}



function loadProductsByFilter(min_price, max_price, category_id, supplier_id, stock, rating, release_date, page = 1) {
    const params = new URLSearchParams({
        min_price,
        max_price,
        category_id,
        supplier_id,
        stock,
        rating,
        release_date,
        page
    });

    fetch(`/api/index/filter?${params.toString()}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderProductsAndPaginationFilter(data, min_price, max_price, category_id, supplier_id, stock, rating, release_date);
            }
        });
}

document.getElementById('filterForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const minPrice = document.querySelector('[name="price_min"]').value.trim();
    const maxPrice = document.querySelector('[name="price_max"]').value.trim();
    const categoryId = document.querySelector('[name="category_filter"]').value;
    const supplierId = document.querySelector('[name="supplier_filter"]').value;
    const stock = document.querySelector('[name="stock_filter"]').value;
    const rating = document.querySelector('[name="rating_filter"]').value;
    const release_date = document.querySelector('[name="release_filter"]').value;

    loadProductsByFilter(minPrice, maxPrice, categoryId, supplierId, stock, rating, release_date, 1);
});

