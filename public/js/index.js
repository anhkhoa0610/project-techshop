document.querySelectorAll('.category-card').forEach(function (card, idx) {
    card.addEventListener('click', function () {
        // Ví dụ: giả sử bạn có mảng id danh mục tương ứng với từng card
        // Bạn nên thay thế mảng này bằng id thực tế từ backend nếu có
        const categoryIds = [1, 2, 3, 4, 5, 6]; // Sửa lại cho đúng với DB của bạn
        const categoryId = categoryIds[idx];

        fetch(`/api/categories/${categoryId}/products`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
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
                    window.scrollTo({ top: document.querySelector('.categories-products').offsetTop - 60, behavior: 'smooth' });
                }
            });
    });
});
