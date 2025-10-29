
// Khởi tạo Swiper
var swiper = new Swiper(".mySwiper", {
    slidesPerView: 3,       // Hiển thị 4 slide cùng lúc
    spaceBetween: 30,       // Khoảng cách giữa các slide là 30px
    loop: false,             // Cho phép lặp lại vô tận

    slidesPerGroup: 2,

    autoplay: {
      delay: 4000, // 1000 milliseconds = 1 giây
      disableOnInteraction: false, // Giữ autoplay sau khi người dùng tương tác
      pauseOnMouseEnter: true, // Tạm dừng khi di chuột vào slider
    },

    // Cấu hình cho các nút điều hướng
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },

    // Cấu hình cho phân trang (dấu chấm)
    pagination: {
        el: ".swiper-pagination",
        clickable: true, // Cho phép click vào dấu chấm để chuyển slide
    },
});
