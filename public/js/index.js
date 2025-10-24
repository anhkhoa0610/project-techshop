function renderProductsAndPagination(data, categoryId) {
    // Hiển thị sản phẩm
    let html = '';
    data.data.forEach(product => {
        // Đoạn HTML hiển thị sản phẩm giữ nguyên
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
    document.querySelector('.new-products').style.display = 'none';
    document.querySelector('.sale-products').style.display = 'none';

    // --- Đã thay đổi: Hiển thị phân trang kiểu Trang Input / Y ---
    let pagination = '';
    if (data.last_page && data.last_page > 1) {
        // Sử dụng flex để căn giữa các nút và số trang
        pagination += `<nav class="category-pagination flex items-center justify-center space-x-4">`;

        // 1. Nút "Trước" (Prev)
        if (data.current_page > 1) {
            // Nút hoạt động
            pagination += `<button class="mx-3 mb-2 page-btn btn btn-outline-dark" data-page="${data.current_page - 1}">Prev</button>`;
        } else {
            // Nút vô hiệu hóa (disabled)
            pagination += `<button class="mx-3 mb-2 page-btn btn btn-outline-dark opacity-50 cursor-not-allowed" disabled>Prev</button>`;
        }

        // 2. Hiển thị Trang Hiện Tại / Tổng Số Trang (dưới dạng ô input)
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
                / ${data.last_page}
            </span>
        `;

        // 3. Nút "Tiếp" (Next)
        if (data.current_page < data.last_page) {
            // Nút hoạt động
            pagination += `<button class="mx-3 mb-2 page-btn btn btn-outline-dark" data-page="${data.current_page + 1}">Next</button>`;
        } else {
            // Nút vô hiệu hóa (disabled)
            pagination += `<button class="mx-3 mb-2 page-btn btn btn-outline-dark opacity-50 cursor-not-allowed" disabled>Next</button>`;
        }

        pagination += `</nav>`;
    }
    document.querySelector('.pagination').innerHTML = pagination;
    // --- Kết thúc thay đổi ---

    // Gán sự kiện cho nút phân trang (vẫn hoạt động với các nút .page-btn)
    document.querySelectorAll('.category-pagination .page-btn').forEach(btn => {
        btn.onclick = function () {
            // Đảm bảo chỉ gọi hàm khi nút không bị vô hiệu hóa
            if (!this.disabled) {
                loadProductsByCategory(categoryId, parseInt(this.dataset.page));
            }
        };
    });

    // Gán sự kiện cho trường input để nhảy trang khi nhấn Enter
    const pageInput = document.getElementById('page-input');
    if (pageInput) {
        pageInput.addEventListener('keyup', function (event) {
            // Kiểm tra nếu phím Enter được nhấn
            if (event.key === 'Enter') {
                let page = parseInt(this.value);
                const lastPage = data.last_page;

                // Kiểm tra tính hợp lệ của số trang nhập vào
                if (isNaN(page) || page < 1) {
                    page = 1;
                } else if (page > lastPage) {
                    page = lastPage;
                }

                // Cập nhật giá trị input sau khi kiểm tra (trường hợp người dùng nhập ngoài giới hạn)
                this.value = page;

                // Nếu số trang nhập vào khác trang hiện tại, thì tải trang mới
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
        const categoryIds = [1, 2, 3, 4, 5, 6]; // Sửa lại cho đúng với DB của bạn
        const categoryId = categoryIds[idx];
        loadProductsByCategory(categoryId, 1);
    });
});


