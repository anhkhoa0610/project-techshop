// Countdown Timer for Deal of the Day
function updateCountdown() {
    const hoursElement = document.getElementById('hours');
    const minutesElement = document.getElementById('minutes');
    const secondsElement = document.getElementById('seconds');

    let hours = parseInt(hoursElement.textContent);
    let minutes = parseInt(minutesElement.textContent);
    let seconds = parseInt(secondsElement.textContent);

    // Decrease seconds
    if (seconds > 0) {
        seconds--;
    } else {
        seconds = 59;
        if (minutes > 0) {
            minutes--;
        } else {
            minutes = 59;
            if (hours > 0) {
                hours--;
            } else {
                // Reset timer when it reaches 0
                hours = 23;
                minutes = 59;
                seconds = 59;
            }
        }
    }

    // Update display with leading zeros
    hoursElement.textContent = hours.toString().padStart(2, '0');
    minutesElement.textContent = minutes.toString().padStart(2, '0');
    secondsElement.textContent = seconds.toString().padStart(2, '0');
}

// Start countdown when page loads
document.addEventListener('DOMContentLoaded', function () {
    // Update countdown every second
    setInterval(updateCountdown, 1000);

    // Mobile menu toggle (if needed)
    const menuBtn = document.querySelector('.menu-btn');
    const nav = document.querySelector('.nav');

    if (menuBtn && nav) {
        menuBtn.addEventListener('click', function () {
            nav.style.display = nav.style.display === 'flex' ? 'none' : 'flex';
        });
    }

    // Search functionality
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    alert('Tìm kiếm: ' + searchTerm);
                    // Here you would typically redirect to search results
                }
            }
        });
    }

    // Add to cart functionality
    const addToCartButtons = document.querySelectorAll('.btn-primary');
    addToCartButtons.forEach(button => {
        if (button.textContent.includes('Thêm vào giỏ') || button.textContent.includes('Mua ngay')) {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                // Simple animation
                const originalText = this.textContent;
                this.textContent = '✓ Đã thêm!';
                this.style.background = '#10b981';

                setTimeout(() => {
                    this.textContent = originalText;
                    this.style.background = '';
                }, 2000);

                // Update cart count
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    const currentCount = parseInt(cartCount.textContent);
                    cartCount.textContent = currentCount + 1;
                }
            });
        }
    });

    // Newsletter subscription
    const newsletterBtn = document.querySelector('.newsletter-btn');
    const newsletterInput = document.querySelector('.newsletter-input');

    if (newsletterBtn && newsletterInput) {
        newsletterBtn.addEventListener('click', function () {
            const email = newsletterInput.value.trim();
            if (email && email.includes('@')) {
                alert('Cảm ơn bạn đã đăng ký nhận tin! Email: ' + email);
                newsletterInput.value = '';
            } else {
                alert('Vui lòng nhập email hợp lệ');
            }
        });

        newsletterInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                newsletterBtn.click();
            }
        });
    }

    // Smooth scroll for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

    // Simple animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe elements for animation
    const animatedElements = document.querySelectorAll('.category-card, .product-card, .deal-card');
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'all 0.6s ease';
        observer.observe(el);
    });
});



// Format price function (if needed for dynamic content)
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
    }).format(price);
}

// Debounce function
function debounce(fn, delay) {
    let timer = null;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn.apply(this, args), delay);
    };
}

// Search
const searchInput = document.getElementById('header-search-input');
const searchResults = document.getElementById('search-results');

const handleSearch = function () {
    const query = this.value.trim();
    if (query.length < 2) {
        searchResults.style.display = 'none';
        searchResults.innerHTML = '';
        return;
    }
    fetch(`/api/index/search?keyword=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => {
            let html = '';
            if (data.status === 'success' && data.data.length) {
                data.data.forEach(product => {
                    html += `
                    <div class="result-item" onclick="window.location.href='/products/${product.product_id}'">
                        <div class="result-thumb">
                            <img src="${product.cover_image ? '/uploads/' + product.cover_image : '/images/place-holder.png'}" alt="${product.product_name}">
                        </div>
                        <div class="result-info">
                            <div class="result-title">${product.product_name}</div>
                            <div class="result-price">${Number(product.price).toLocaleString('vi-VN')}₫</div>
                        </div>
                    </div>
                    `;
                });
            } else {
                html = `<div class="no-result">Không tìm thấy sản phẩm phù hợp.</div>`;
            }
            searchResults.innerHTML = html;
            searchResults.style.display = 'block';
            searchResults.classList.add('active');
        });
};

// Sử dụng debounce cho sự kiện input
searchInput.addEventListener('input', debounce(handleSearch, 400));

// Ẩn kết quả khi blur
searchInput.addEventListener('blur', function () {
    setTimeout(() => { searchResults.style.display = 'none'; }, 200);
});

// Hiện lại khi focus nếu có kết quả
searchInput.addEventListener('focus', function () {
    if (searchResults.innerHTML.trim()) searchResults.style.display = 'block';
});


document.addEventListener('click', (e) => {
    if (!e.target.closest('.search-box')) {
        searchResults.classList.remove('active');
    }
});