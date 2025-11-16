document.addEventListener('DOMContentLoaded', function () {

    // 1. Xử lý nút "Liên hệ hỗ trợ" (Kích hoạt cuộc gọi điện thoại)
    document.querySelectorAll('.contact-support-btn-dynamic').forEach(button => {
        button.addEventListener('click', function () {
            const phoneNumber = this.dataset.phone || '02838966825';

            // ✅ SỬ DỤNG Swal.fire VỚI NÚT XÁC NHẬN
            Swal.fire({
                title: 'Xác nhận liên hệ hỗ trợ',
                html: `Bạn có muốn gọi đến số hỗ trợ khách hàng:<br><strong>${phoneNumber}</strong>?`,
                icon: 'question',
                showCancelButton: true, // Hiển thị nút Hủy
                confirmButtonText: 'Gọi ngay', // Tên nút xác nhận
                cancelButtonText: 'Để sau',   // Tên nút hủy
                reverseButtons: true,        // Đảo ngược vị trí nút (tùy chọn)
                customClass: {
                    confirmButton: 'btn btn-primary', // Thêm class CSS cho nút
                    cancelButton: 'btn btn-ghost'
                },
            }).then((result) => {
                // Kiểm tra xem người dùng đã bấm nút xác nhận hay không
                if (result.isConfirmed) {
                    const telLink = `tel:${phoneNumber.replace(/ /g, '')}`;
                    window.location.href = telLink;
                }
            });
        });
    });

    // 2. Xử lý nút "Tải hóa đơn" (Giữ nguyên logic xuất Excel)
    document.querySelectorAll('.download-invoice-btn-dynamic').forEach(button => {
        button.addEventListener('click', function () {
            const orderId = this.dataset.orderId;
            if (orderId) {
                // Chuyển hướng đến route xuất Excel cho orderId tương ứng
                const exportUrl = `/export/invoice/${orderId}/xlsx`;
                window.location.href = exportUrl;
            }
        });
    });
});