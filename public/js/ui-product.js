// Xử lý thay đổi ảnh sản phẩm
const images = document.querySelectorAll('.swiper-slide-img');
const mainImage = document.getElementById('mainImage');
const post_review = document.querySelector('.post-review');

let hoverTimeout;

images.forEach((img) => {
    img.addEventListener('mouseenter', () => {
        clearTimeout(hoverTimeout);
        hoverTimeout = setTimeout(() => {
            mainImage.src = img.src;
        }, 500);
    });

    img.addEventListener('mouseleave', () => {
        clearTimeout(hoverTimeout);
    });
});



const swiper_wrapper = document.querySelector('.swiper-wrapper');
const swiper_button_prev = document.querySelector('.swiper-button-prev');
const swiper_button_next = document.querySelector('.swiper-button-next');

// xử lý 2 nút điều hướng trong swiper
swiper_button_next.addEventListener('click', () => {
    swiper_wrapper.scrollBy({
        left: 300,
        behavior: 'instant'
    });
});
swiper_button_prev.addEventListener('click', () => {
    swiper_wrapper.scrollBy({
        left: -300,
        behavior: 'instant'
    });
});

// Xử lý giới hạn số lượng nhập để thêm vào giỏ hàng

const inputQuantity = document.querySelector('.input-quantity');

inputQuantity.addEventListener('input', () => {
    const min = 1;
    const max = parseInt(inputQuantity.max);
    let value = parseInt(inputQuantity.value);

    // Nếu không phải số, gán lại giá trị min
    if (isNaN(value)) {
        inputQuantity.value = '';
    }

    // Giới hạn trong khoảng [min, max]
    if (value < min) inputQuantity.value = min;
    if (value > max) inputQuantity.value = max;

});

// Xử lý nút tăng, giảm số lượng
const minusButton = document.querySelector('.quantity-button.minus');
const plusButton = document.querySelector('.quantity-button.plus');

minusButton.addEventListener('click', () => {
    let currentValue = parseInt(inputQuantity.value);
    const min = 1;
    if (currentValue > min) {
        inputQuantity.value = currentValue - 1;
    }
});

plusButton.addEventListener('click', () => {
    let currentValue = parseInt(inputQuantity.value);
    const max = parseInt(inputQuantity.max);
    if (currentValue < max) {
        inputQuantity.value = currentValue + 1;
    }
});

// hàm cập nhật số sao bình luận
const star_rating_display = document.querySelector('.star-rating-display');
function updateStarDisplay(avg) {

    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= avg) {
            starsHtml += `<span class="star filled text-warning fs-1">★</span>`;
        } else {
            starsHtml += `<span class="star text-warning fs-1">☆</span>`;
        }
    }
    star_rating_display.innerHTML = starsHtml;
}

// xử lý hiển thị đánh giá và phân trang đánh giá bằng API
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.button-filter-star');
    const reviewContainer = document.querySelector('.comment-field');
    const paginationContainer = document.querySelector('.pagination');
    const apiBase = `/api/product/${productId}/reviews`;
    updateCartCountDisplay(cartItems_count);

    let currentUrl = apiBase;

    // Hàm tải danh sách review + render phân trang
    function loadReviews(url) {

        if (hasReviewed) {
            post_review.style.display = 'none';
        }
        else {
            post_review.style.display = 'block';
        }
        fetch(url)
            .then(response => response.json())
            .then(result => {
                if (!result.success) {
                    reviewContainer.innerHTML = '<p>Không có dữ liệu!</p>';
                    paginationContainer.innerHTML = '';
                    return;
                }

                const pagination = result.data;
                const reviews = pagination.data;

                // Nếu không có review
                if (!reviews.length) {
                    reviewContainer.innerHTML = '<p>Chưa có đánh giá.</p>';
                    paginationContainer.innerHTML = '';
                    return;
                }

                // Render danh sách đánh giá
                reviewContainer.innerHTML = reviews.map(review => {

                    let stars = '';
                    for (let i = 0; i < 5; i++) {
                        if (i < review.rating) {
                            stars += '<span class="star filled text-warning fs-1">★</span>';
                        } else {
                            stars += '<span class="star text-warning fs-1">☆</span>';
                        }
                    }
                    const actions = (user_id === review.user.user_id)
                        ? `
                            <div class="review-actions ms-auto">
                                <button class="btn btn-sm btn-info edit-review-btn"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editReviewModal"
                                        data-id="${review.review_id}" 
                                        data-rating="${review.rating}"
                                        data-comment="${review.comment}">
                                    <span> <i class="bi bi-pencil"></i> </span>
                                    Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-review" data-id="${review.review_id}" data-rating="${review.rating}">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        `
                        : '';

                    const formattedDate = new Date(review.review_date)
                        .toLocaleString('vi-VN', {
                            day: '2-digit', month: '2-digit', year: 'numeric',
                            hour: '2-digit', minute: '2-digit'
                        });

                    return `
                     <div class="review-display border-bottom py-2">
                         <img class="user-avatar" src="/images/user-icon.jpg" alt="">
                         <div class="user-review">
                             <div class="d-flex">
                                 <strong class="review-info">${review.user.full_name}</strong>
                                 <p class="review-info ms-5">| ${formattedDate}</p>
                             </div>
                             <div class="gr-star-ed d-flex">
                                <p class="review-info-st">${stars}</p>
                                ${actions}
                             </div>
                             <p class="review-info">${review.comment ?? ""}</p>
                        </div>
                    </div>
                      `;
                }).join('');

                // Render thanh phân trang
                paginationContainer.innerHTML = pagination.links.map(link => {

                    const label = link.label;
                    const activeClass = link.active ? 'active' : '';
                    const disabled = link.url === null ? 'disabled' : '';

                    return `
                     <button
                         class="btn btn-sm btn-outline-secondary mx-1 ${activeClass}"
                         ${disabled ? 'disabled' : ''}
                         data-url="${link.url || '#'}"
                     >
                         ${label}
                     </button>
                         `;
                }).join('');

                // Gán sự kiện click cho từng nút
                paginationContainer.querySelectorAll('button[data-url]').forEach(btn => {
                    btn.addEventListener('click', () => {
                        let url = btn.getAttribute('data-url');

                        if (url && url !== '#' && typeof currentRating !== 'undefined' && currentRating !== null) {
                            const url_obj = new URL(url);
                            url_obj.searchParams.set('rating', currentRating);
                            url = url_obj.toString();
                        }

                        if (url && url !== '#') loadReviews(url);
                    });
                });

            })
            .catch(error => {
                reviewContainer.innerHTML = '<p>Đã xảy ra lỗi khi tải đánh giá!</p>';
                paginationContainer.innerHTML = '';
            });
    }

    // xử lý các nút lọc đánh giá sao
    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            buttons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const rating = btn.getAttribute('data-rating');
            const url = rating ? `${apiBase}?rating=${rating}` : apiBase;
            currentUrl = url;
            loadReviews(url);
        });
    });

    // Tải mặc định trang đầu tiên
    loadReviews(apiBase);

    function updateReviewUI(data, rating, productId, action) {
        let amountReview = -1;
        if (action == 'add') {
            amountReview = 1;
        }
        if (action == 'update') {
            amountReview = 0;
        }
        //  Cập nhật số lượng đánh giá tùy vào số sao 
        const span = document.querySelector(`.review-count[data-rating="${rating}"]`);
        if (span) span.textContent = parseInt(span.textContent) + amountReview;

        //  Cập nhật số lượng đánh giá "tất cả"
        const allSpan = document.querySelector('.review-count[data-rating="all"]');
        if (allSpan) allSpan.textContent = parseInt(allSpan.textContent) + amountReview;

        //  Cập nhật sao trung bình
        const avg = data?.data?.avg ? parseFloat(data.data.avg).toFixed(1) : '0.0';
        const rating_left = document.querySelector('.rating-left');
        const rating_star_title = document.querySelector('.rating-star-title');
        // cập nhật số sao thống kê
        if (rating_left) rating_left.textContent = avg;
        if (rating_star_title) rating_star_title.textContent = avg;

        const total_review = document.querySelector('.total-review');
        if (total_review)
            total_review.textContent = parseInt(total_review.textContent) + amountReview;

        //  Làm nổi bật nút lọc sao
        if (action === 'add') {
            document.querySelectorAll('.button-filter-star').forEach(b => b.classList.remove('active'));
            const activeBtn = document.querySelector(`.button-filter-star[data-rating="${rating}"]`);
            if (activeBtn) activeBtn.classList.add('active');
        }

        const apiBase = `/api/product/${productId}/reviews`;
        const url = rating ? `${apiBase}?rating=${rating}` : apiBase;

        loadReviews(url);

        updateStarDisplay(data.data.avg);
    }


    // xử lý thêm vào giỏ hàng và mua ngay
    const btnAddCart = document.querySelector('.btn-add-cart-main');
    const btnBuyNow = document.querySelector('.btn-buy-now');
    const inputQuantity = document.querySelector('.input-quantity');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    if (btnAddCart) {
        btnAddCart.addEventListener('click', async () => {

            const quantity = parseInt(inputQuantity?.value);
            // Kiểm tra đăng nhập trước khi xử lý
            if (!check_user) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Vui lòng đăng nhập',
                    text: 'Bạn cần đăng nhập để sử dụng chức năng này.',
                    showCancelButton: true,
                    confirmButtonText: 'Đăng nhập ngay',
                    cancelButtonText: 'Hủy bỏ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/login';
                    }
                });
                return;
            }
            else {
                try {
                    const response = await fetch('/api/product-details/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            user_id: user_id,
                            product_id: productId,
                            quantity: quantity
                        })
                    });

                    const text = await response.text();
                    let data;

                    try {
                        data = JSON.parse(text);
                    } catch {
                        throw new Error(`Lỗi máy chủ (${response.status})`);
                    }

                    if (!response.ok) {
                        let msg = 'Có lỗi xảy ra!';
                        if (response.status === 422 && data.errors) {
                            msg = Object.values(data.errors).flat().join('\n');
                        } else if (data.message) {
                            msg = data.message;
                        }
                        Swal.fire({ icon: 'error', text: msg });
                        return;
                    }

                    if (data.success) {
                        Swal.fire({ icon: 'success', text: data.message, timer: 1500, showConfirmButton: false });
                        updateCartCountDisplay(data.cartItems_count);
                    } else {
                        Swal.fire({ icon: 'warning', text: data.message || 'Không thể thêm vào giỏ hàng!' });
                    }

                } catch (err) {
                    Swal.fire({ icon: 'error', text: err.message || 'Lỗi kết nối!' });
                }
            }
        });
    }

    // =========== MUA NGAY ===========
    if (btnBuyNow) {
        btnBuyNow.addEventListener('click', async () => {


            const quantity = parseInt(inputQuantity?.value);
            // Kiểm tra đăng nhập trước khi xử lý
            if (!check_user) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Vui lòng đăng nhập',
                    text: 'Bạn cần đăng nhập để sử dụng chức năng này.',
                    showCancelButton: true,
                    confirmButtonText: 'Đăng nhập ngay',
                    cancelButtonText: 'Hủy bỏ'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/login';
                    }
                });
                return;
            }
            else {
                try {
                    const response = await fetch('/api/product-details/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            user_id: user_id,
                            product_id: productId,
                            quantity: quantity
                        })
                    });

                    const text = await response.text();
                    let data;

                    try {
                        data = JSON.parse(text);
                    } catch {
                        throw new Error(`Lỗi máy chủ (${response.status})`);
                    }

                    if (!response.ok) {
                        let msg = 'Có lỗi xảy ra!';
                        if (response.status === 422 && data.errors) {
                            msg = Object.values(data.errors).flat().join('\n');
                        } else if (data.message) {
                            msg = data.message;
                        }
                        Swal.fire({ icon: 'error', text: msg });
                        return;
                    }

                    if (data.success) {
                        // Chuyển sang trang giỏ hàng khi thêm thành công
                        window.location.href = '/cart';
                        updateCartCountDisplay(data.cartItems_count);
                    } else {
                        Swal.fire({ icon: 'warning', text: data.message || 'Không thể thêm vào giỏ hàng!' });
                    }

                } catch (err) {
                    Swal.fire({ icon: 'error', text: err.message || 'Lỗi kết nối!' });
                }
            }
        });
    }

    // Xử lý các nút addToCart ở phần related products
    document.addEventListener('click', async (e) => {
        const btn = e.target.closest('.btn-add-cart');
        if (!btn) return;

        const productId = btn.dataset.productId;
        const quantity = btn.dataset.quantity || 1;

        btn.disabled = true;
        const originalText = btn.textContent;
        btn.textContent = 'Đang thêm...';
        // Kiểm tra đăng nhập trước khi xử lý
        if (!check_user) {
            Swal.fire({
                icon: 'warning',
                title: 'Vui lòng đăng nhập',
                text: 'Bạn cần đăng nhập để sử dụng chức năng này.',
                showCancelButton: true,
                confirmButtonText: 'Đăng nhập ngay',
                cancelButtonText: 'Hủy bỏ'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login';
                }
            });
            return;
        }
        else {
            try {
                const response = await fetch('/api/product-details/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ user_id: user_id, product_id: productId, quantity })
                });

                const data = await response.json();

                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    updateCartCountDisplay(data.cartItems_count);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Thất bại',
                        text: data.message,
                        showConfirmButton: true,
                        confirmButtonText: "OK"
                    });
                }
            } catch (err) {
                console.error('Lỗi thêm giỏ hàng:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: err.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }
    });

    // xử lý submit form thêm đánh giá

    document.getElementById('form-post-review').addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        
        // Disable button to prevent multiple clicks
        submitButton.disabled = true;
        const originalText = submitButton.textContent;
        submitButton.textContent = 'Đang xử lý...';
        
        // Kiểm tra đăng nhập trước khi xử lý
        if (!check_user) {
            Swal.fire({
                icon: 'warning',
                title: 'Vui lòng đăng nhập',
                text: 'Bạn cần đăng nhập để sử dụng chức năng này.',
                showCancelButton: true,
                confirmButtonText: 'Đăng nhập ngay',
                cancelButtonText: 'Hủy bỏ'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/login';
                }
            });
            submitButton.disabled = false;
            submitButton.textContent = originalText;
            return;
        }
        else {
            const response = await fetch(`/api/product/${productId}/reviews`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken
                },
                body: formData
            });

            try {
                if (response.ok) {

                    Swal.fire('Thành công', 'Đánh giá của bạn đã được lưu lại', 'success');
                    hasReviewed = true;
                    const data = await response.json();
                    const rating = formData.get('rating');

                    updateReviewUI(data, rating, productId, 'add');
                } else {
                    const errorData = await response.json();
                    Swal.fire('Lỗi', 'Lỗi khi gửi đánh giá, vui lòng thử lại sau.', 'error');
                }
            } finally {
                // Re-enable button after response completes
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        }
    });

    // Xử lý nút xóa đánh giá
    document.addEventListener('click', function (e) {
        if (e.target.closest('.delete-review')) {
            e.preventDefault();
            const button = e.target.closest('.delete-review');
            const reviewId = button.getAttribute('data-id');
            const reviewRating = button.getAttribute('data-rating');
            // Hiển thị xác nhận xóa
            Swal.fire({
                title: 'Bạn có chắc muốn xóa?',
                text: 'Hành động này không thể hoàn tác!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/client-review/${reviewId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Thành công!',
                                    text: data.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                hasReviewed = false;
                                updateReviewUI(data, reviewRating, productId, 'delete');

                                // Xóa review khỏi giao diện
                                const reviewElement = button.closest('.review-display');
                                if (reviewElement) reviewElement.remove();
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi!',
                                    text: data.message || 'Không thể xóa đánh giá.',
                                });
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi hệ thống!',
                                text: 'Đã xảy ra lỗi, vui lòng thử lại.',
                            });
                        });
                }
            });
        }
    });

    // xử lý sửa đánh giá
    document.addEventListener("click", function (e) {
        const button = e.target.closest(".edit-review-btn");
        if (!button) return;

        const reviewId = button.dataset.id;
        const rating = button.dataset.rating;
        const comment = button.dataset.comment;

        // Set hidden ID
        document.getElementById('edit_review_id').value = reviewId;


        // Set comment
        document.getElementById('edit_comment').value =
            (comment === null || comment === "null" || comment === undefined || comment === "undefined")
                ? ""
                : comment;


        // Set rating
        document.querySelectorAll('#editReviewForm input[name="rating"]').forEach(input => {
            input.checked = input.value == rating;
        });
    });



    // XỬ LÝ SUBMIT FORM AJAX
    document.getElementById("editReviewForm").addEventListener("submit", async function (e) {
        e.preventDefault();

        const form = this;
        const reviewId = document.getElementById('edit_review_id').value;
        const formData = new FormData(form);
        const submitButton = this.querySelector('button[type="submit"]');
        
        // Disable button to prevent multiple clicks
        submitButton.disabled = true;
        const originalText = submitButton.textContent;
        submitButton.textContent = 'Đang xử lý...';

        try {
            const response = await fetch(`/api/client-review/${reviewId}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json",
                },
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    icon: "success",
                    title: "Cập nhật thành công!",
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Lỗi!",
                    text: result.message || "Cập nhật thất bại.",
                    confirmButtonText: "Đóng"
                });
            }
        } catch (error) {
            console.error("Lỗi AJAX:", error);
            Swal.fire({
                icon: "error",
                title: "Lỗi kết nối!",
                text: "Không kết nối được server.",
                confirmButtonText: "Đóng"
            });
        } finally {
            // Re-enable button after response completes
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }

    });

    const categoryBtn = document.querySelector('.category-button');
    const supplierBtn = document.querySelector('.supplier-button');
    const display = document.querySelector('.related-display');

    /**
     * Hiển thị hiệu ứng tải (Skeleton) có animation xuất hiện từng khối
     * @param {number} count
     */
    function showProductLoading(count = 4) {
        if (!display) return;

        let html = '';
        for (let i = 0; i < count; i++) {
            html += `
            <div class="mb-4 mt-5 d-inline-block related-container fade-in" style="animation-delay: ${i * 0.15}s">
                <div class="product-card skeleton-card">
                    <div class="skeleton-image shimmer"></div>
                    <div class="skeleton-line short shimmer"></div>
                    <div class="skeleton-line shimmer"></div>
                    <div class="skeleton-line shimmer"></div>
                    <div class="skeleton-line short shimmer"></div>
                </div>
            </div>
        `;
        }
        display.innerHTML = html;
    }

    /**
     * Hàm load sản phẩm qua API
     */
    function loadProducts(type, id) {
        let url = `${window.location.origin}/api/product-details/filter`;
        if (type === 'category') url += `?category_id=${id}`;
        else if (type === 'supplier') url += `?supplier_id=${id}`;

        // Hiển thị skeleton trước khi tải
        showProductLoading();

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    renderProducts(data.data);
                } else {
                    display.innerHTML = `<p class="text-center text-muted py-4">Không có sản phẩm phù hợp.</p>`;
                }
            })
            .catch(err => {
                display.innerHTML = `<p class="text-center text-danger py-4">Đã xảy ra lỗi khi tải sản phẩm.</p>`;
            });
    }

    /**
     * Render sản phẩm liên quan ra giao diện 
     */
    function renderProducts(products) {
        display.innerHTML = '';
        products.forEach((prod, i) => {
            const imageUrl = prod.cover_image
                ? `/uploads/${prod.cover_image}`
                : `/images/blank_product.png`;

            const productHtml = `
            <div class="mb-4 mt-5 d-inline-block related-container fade-in-up" style="animation-delay: ${i * 0.1}s">
                <div class="product-card">
                    <div class="product-image">
                        <img src="${imageUrl}" alt="${prod.product_name}">
                        ${prod.discounts?.length
                    ? `<div class="related-product-sale-icon">Giảm ${prod.discounts[0].discount_percent}%</div>`
                    : ''}
                        <div class="product-discount">Trả góp 0%</div>
                    </div>

                    <a class="product-info" href="/product-details/${prod.product_id}">
                        <h3 class="product-name">${prod.product_name}</h3>

                        <div class="specs-grid-container">
                            ${prod.specs
                    ? prod.specs.map(spec => {
                        const nameLower = spec.name?.toLowerCase() || "";
                        let iconFile = "cpu.svg";
                        if (nameLower.includes("ram")) iconFile = "ram.svg";
                        else if (nameLower.includes("gpu") || nameLower.includes("đồ họa") || nameLower.includes("vga")) iconFile = "gpu.svg";
                        else if (nameLower.includes("ssd") || nameLower.includes("hdd") || nameLower.includes("storage") || nameLower.includes("dung lượng"))
                            iconFile = "storage.svg";

                        return `
                            <div class="spec-grid-item">
                                <img src="/images/icons/${iconFile}" alt="${spec.name}" class="spec-grid-icon">
                                <div class="spec-grid-text">
                                    <span class="spec-grid-name">${spec.name}</span>
                                    <strong class="spec-grid-value">${spec.value}</strong>
                                </div>
                            </div>
                        `;
                    }).join('')
                    : ''}
                        </div>

                        <div class="product-rating">
                            <span class="stars" style="color:#ffc107;">⭐</span>
                            <span class="rating-score">${prod.reviews_avg_rating ? prod.reviews_avg_rating.toFixed(1) : '0.0'}</span>
                            <span class="reviews">(${prod.reviews_count || 0} đánh giá)</span>
                        </div>

                        <div class="product-price">
                            ${prod.discounts?.length
                    ?
                    `
                     <span class="current-price">
                         ${Number(prod.discounts[0].sale_price).toLocaleString('vi-VN')}₫
                     </span>
                     <span class="original-price price-strike-through">
                         ${Number(prod.discounts[0].original_price).toLocaleString('vi-VN')}₫
                     </span>
                     `
                    :
                    `
                     <span class="current-price">
                         ${prod.price ? Number(prod.price).toLocaleString('vi-VN') + '₫' : 'Liên hệ'}
                     </span>
                     `
                }
                        </div>

                        <div class="product-meta">
                            <div class="release-date">
                                📅 <strong>Phát hành:</strong> ${prod.release_date || 'Đang cập nhật'}
                            </div>
                            <div class="stock-info">
                                📦 <strong>Còn lại:</strong> 
                                ${prod.stock_quantity > 0
                    ? `${prod.stock_quantity} sản phẩm`
                    : '<span style="color:red;">Hết hàng</span>'}
                            </div>
                        </div>
                    </a>

                    <button data-product-id="${prod.product_id}" data-quantity="1"
                        class="btn-add-cart btn btn-primary full-width">Thêm vào giỏ 🛒</button>
                </div>
            </div>
        `;
            display.insertAdjacentHTML('beforeend', productHtml);
        });
    }

    /**
     * Gắn sự kiện click
     */
    categoryBtn.addEventListener('click', function () {
        document.querySelector('.related-title-type').textContent = "Cùng danh mục";
        this.classList.add('active');
        supplierBtn.classList.remove('active');
        loadProducts('category', this.dataset.category_id);
    });

    supplierBtn.addEventListener('click', function () {
        document.querySelector('.related-title-type').textContent = "Cùng nhà phân phối";
        this.classList.add('active');
        categoryBtn.classList.remove('active');
        loadProducts('supplier', this.dataset.supplier_id);
    });

    // Auto load loại đang active
    if (categoryBtn.classList.contains('active')) {
        loadProducts('category', categoryBtn.dataset.category_id);
    } else if (supplierBtn.classList.contains('active')) {
        loadProducts('supplier', supplierBtn.dataset.supplier_id);
    }

});