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
document.addEventListener('DOMContentLoaded', function() {
    // Update countdown every second
    setInterval(updateCountdown, 1000);
    
    // Mobile menu toggle (if needed)
    const menuBtn = document.querySelector('.menu-btn');
    const nav = document.querySelector('.nav');
    
    if (menuBtn && nav) {
        menuBtn.addEventListener('click', function() {
            nav.style.display = nav.style.display === 'flex' ? 'none' : 'flex';
        });
    }
    
    // Search functionality
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
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
            button.addEventListener('click', function(e) {
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
        newsletterBtn.addEventListener('click', function() {
            const email = newsletterInput.value.trim();
            if (email && email.includes('@')) {
                alert('Cảm ơn bạn đã đăng ký nhận tin! Email: ' + email);
                newsletterInput.value = '';
            } else {
                alert('Vui lòng nhập email hợp lệ');
            }
        });
        
        newsletterInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                newsletterBtn.click();
            }
        });
    }
    
    // Smooth scroll for anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
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
    
    const observer = new IntersectionObserver(function(entries) {
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