
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

