function renderProductsAndPagination(data, categoryId) {
    // Hiển thị sản phẩm
    let html = '';
    data.data.forEach(product => {
        html += `
        <div class="product-card">
            <div class="product-image">
                <img src="${product.cover_image ? '/uploads/' + product.cover_image : '/images/place-holder.jpg'}" alt="${product.product_name}">
            </div>
            <div class="product-info">
                <h3 class="product-name">${product.product_name}</h3>
                <div class="product-price">
                    <span class="current-price">${Number(product.price).toLocaleString('vi-VN')}₫</span>
                </div>
                <button class="btn btn-primary full-width">🛒 Thêm vào giỏ</button>
            </div>
        </div>
        `;
    });
    document.querySelector('.show-by-category').innerHTML = html;
    document.querySelector('.categories-products').style.display = 'block';

    // Hiển thị nút phân trang
    let pagination = '';
    if (data.last_page && data.last_page > 1) {
        pagination += `<nav class="category-pagination">`;
        if (data.current_page > 1) {
            pagination += `<button class="page-btn btn btn-outline-primary mx-4" data-page="${data.current_page - 1}">Prev</button>`;
        }
        for (let i = 1; i <= data.last_page; i++) {
            pagination += `<button class="page-btn btn btn${i === data.current_page ? ' btn-primary' : ' btn-outline-primary'} mx-4" data-page="${i}">${i}</button>`;
        }
        if (data.current_page < data.last_page) {
            pagination += `<button class="page-btn btn btn-outline-primary mx-4" data-page="${data.current_page + 1}">Next</button>`;
        }
        pagination += `</nav>`;
    }
    document.querySelector('.pagination').innerHTML = pagination;

    // Gán sự kiện cho nút phân trang
    document.querySelectorAll('.category-pagination .page-btn').forEach(btn => {
        btn.onclick = function () {
            loadProductsByCategory(categoryId, parseInt(this.dataset.page));
        };
    });

    window.scrollTo({ top: document.querySelector('.categories-products').offsetTop - 60, behavior: 'smooth' });
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
        const categoryIds = [1, 2, 3, 4, 5, 6]; // Sửa lại cho đúng với DB của bạn
        const categoryId = categoryIds[idx];
        loadProductsByCategory(categoryId, 1);
    });
});
