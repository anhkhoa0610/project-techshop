// Xử lý thay đổi ảnh sản phẩm
const images = document.querySelectorAll('.swiper-slide-img');
const mainImage = document.getElementById('mainImage');
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


// xử lý hiển thị đánh giá và phân trang đánh giá bằng API
document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('.button-filter-star');
    const reviewContainer = document.querySelector('.comment-field');
    const paginationContainer = document.querySelector('.pagination');
    const apiBase = `/api/product/${productId}/reviews`;

    let currentUrl = apiBase;

    // Hàm tải danh sách review + render phân trang
    function loadReviews(url) {
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
                    reviewContainer.innerHTML = '<p>Chưa có đánh giá nào cho mức sao này.</p>';
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
                                         <p class="review-info">${stars}</p>
                                         <p class="review-info">${review.comment}</p>
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
                console.error('Lỗi khi tải review:', error);
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

    // xử lý submit form thêm đánh giá

    document.getElementById('form-post-review').addEventListener('submit', async function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const response = await fetch('/api/product/{{ $product->product_id }}/reviews', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
            },
            body: formData
        });

        if (response.ok) {
            Swal.fire('Thành công', 'đánh giá của bạn đã được lưu lại', 'success');

            // Lấy rating từ form (nếu input có name="rating")
            const rating = formData.get('rating');

            // xử lý tăng số lượng đánh giá hiển thị ở từng mức sao
            const span = document.querySelector(`.review-count[data-rating="${rating}"]`);
            if (span) {
                span.textContent = parseInt(span.textContent) + 1; // tăng lên 1
            }

            // xử lý tăng số lượng đánh giá hiển thị ở phần tất cả
            const allSpan = document.querySelector('.review-count[data-rating=""]');
            if (allSpan) {
                allSpan.textContent = parseInt(allSpan.textContent) + 1;
            }
            // xử lý hiển thị lại số sao trung bình
            const data = await response.json();
            const rating_left = document.querySelector('.rating-left');
            const rating_star_title = document.querySelector('.rating-star-title');

            let avg;
            if (data.data.avg) {
                avg = parseFloat(data.data.avg).toFixed(1);
            }
            if (rating_left) {
                rating_left.textContent = avg;
            }
            if (rating_star_title) {
                rating_star_title.textContent = avg;
            }


            // Cập nhật nút lọc sao đang active đúng với số sao mà user vừa đánh giá
            document.querySelectorAll('.button-filter-star').forEach(b => b.classList.remove('active'));
            const activeBtn = document.querySelector(`.button-filter-star[data-rating="${rating}"]`);
            if (activeBtn) activeBtn.classList.add('active');

            // Gọi callback hàm để load lại review 
            const apiBase = `/api/product/${productId}/reviews`;
            const url = rating ? `${apiBase}?rating=${rating}` : apiBase;
            loadReviews(url);

            // Reset form
            this.reset();
        } else {
            const errorData = await response.json();
            Swal.fire('Lỗi', 'Lỗi khi gửi đánh giá, vui lòng thử lại sau.', 'error');
        }
    });
});