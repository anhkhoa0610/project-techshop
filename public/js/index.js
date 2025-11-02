function renderProductsAndPagination(data, categoryId) {
    let html = '';
    data.data.forEach(product => {

        const rating = product.reviews_avg_rating ? parseFloat(product.reviews_avg_rating).toFixed(1) : 0;
        const reviewCount = product.reviews_count || 0;

        let stars = '';
        for (let i = 1; i <= 5; i++) {
            if (i <= Math.floor(rating)) {
                stars += '<i class="fa fa-star" style="color:#FFD700;"></i>';
            } else if (i - 0.5 <= rating) {
                stars += '<i class="fa fa-star-half-o" style="color:#FFD700;"></i>';
            } else {
                stars += '<i class="fa fa-star-o" style="color:#FFD700;"></i>';
            }
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
                <span class="rating-text">${rating}</span>
                <span class="reviews">(${reviewCount} đánh giá)</span>
            </div>
                <div class="product-price">
                    <span class="current-price">${Number(product.price).toLocaleString('vi-VN')}₫</span>
                </div>
            </div>
            <button class="btn-add-cart btn btn-primary full-width" data-product-id="${product.product_id}" data-quantity="1">🛒 Thêm vào giỏ</button>
        </div>
        `;
    });
    document.querySelector('.show-by-category').innerHTML = html;
    document.querySelector('.categories-products').style.display = 'block';
    document.querySelector('.new-products').style.display = 'none';
    document.querySelector('.sale-products').style.display = 'none';

    let pagination = '';
    if (data.last_page && data.last_page > 1) {
        pagination += `<nav class="category-pagination flex items-center justify-center space-x-4">`;

        if (data.current_page > 1) {
            pagination += `<button class="mx-3 mb-2 page-btn btn btn-outline-dark" data-page="${data.current_page - 1}">Prev</button>`;
        } else {
            pagination += `<button class="mx-3 mb-2 page-btn btn btn-outline-dark opacity-50 cursor-not-allowed" disabled>Prev</button>`;
        }

        pagination += `
            <span class="page-indicator">
            <span style="color: white">
                Pages:
                </span>               
                <input 
                    type="number" 
                    id="page-input"
                    value="${data.current_page}" 
                    min="1" 
                    max="${data.last_page}" 
                    aria-label="Nhập số trang để chuyển đến"
                /> 
                <span style="color: white">/ ${data.last_page}</span>
            </span>
        `;

        if (data.current_page < data.last_page) {
            pagination += `<button class="mx-3 mb-2 page-btn btn btn-outline-dark" data-page="${data.current_page + 1}">Next</button>`;
        } else {
            pagination += `<button class="mx-3 mb-2 page-btn btn btn-outline-dark opacity-50 cursor-not-allowed" disabled>Next</button>`;
        }

        pagination += `</nav>`;
    }
    document.querySelector('.pagination').innerHTML = pagination;

    document.querySelectorAll('.category-pagination .page-btn').forEach(btn => {
        btn.onclick = function () {
            if (!this.disabled) {
                loadProductsByCategory(categoryId, parseInt(this.dataset.page));
            }
        };
    });

    const pageInput = document.getElementById('page-input');
    if (pageInput) {
        pageInput.addEventListener('keyup', function (event) {
            if (event.key === 'Enter') {
                let page = parseInt(this.value);
                const lastPage = data.last_page;

                if (isNaN(page) || page < 1) {
                    page = 1;
                } else if (page > lastPage) {
                    page = lastPage;
                }

                this.value = page;

                if (page !== data.current_page) {
                    loadProductsByCategory(categoryId, page);
                }
            }
        });
    }
}



function loadProductsByCategory(categoryId, page = 1) {
    fetch(`/api/categories/${categoryId}/products?page=${page}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderProductsAndPagination(data, categoryId);
            }
        });
}

document.querySelectorAll('.category-card').forEach(function (card, idx) {
    card.addEventListener('click', function () {
        document.getElementById('filterForm').reset();
        const categoryIds = [1, 2, 3, 4, 5, 6]; // Sửa lại cho đúng với DB của bạn
        const categoryId = categoryIds[idx];
        loadProductsByCategory(categoryId, 1);
    });
});

function playVideo(container) {
    const iframe = container.querySelector('iframe');
    let src = iframe.getAttribute('src');

    // Nếu chưa có autoplay thì thêm vào
    if (!src.includes('autoplay=1')) {
        src += (src.includes('?') ? '&' : '?') + 'autoplay=1';
        iframe.setAttribute('src', src);
    }

    // Ẩn overlay sau khi phát
    const overlay = container.querySelector('.overlay');
    overlay.style.display = 'none';
}




const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        const title = entry.target;
        if (entry.isIntersecting) {
            // vào khung nhìn
            title.classList.add('animation-effect');
            title.querySelectorAll('span').forEach((s, i) => {
                s.style.animationDelay = `${i * 0.07}s`;
            });
        } else {
            // ra khung nhìn
            title.classList.remove('animation-effect');
        }
    });
});

document.querySelectorAll('.section-title').forEach(el => observer.observe(el));

