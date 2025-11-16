document.addEventListener("DOMContentLoaded", () => {
    // Khởi tạo tất cả toast có trên trang
    const toastElList = [].slice.call(document.querySelectorAll('.toast'))
    toastElList.map(function (toastEl) {
        const toast = new bootstrap.Toast(toastEl, { delay: 2500 }) // tự ẩn sau 2 giây
        toast.show()
    })

    // Dropdown hover (giữ nguyên như trước)
    const dropdown = document.querySelector('.user-dropdown');
    if (dropdown) {
        dropdown.addEventListener('mouseenter', () => dropdown.classList.add('open'));
        dropdown.addEventListener('mouseleave', () => dropdown.classList.remove('open'));
    }
});

document.addEventListener("DOMContentLoaded", function () {
    fetch('/api/suppliers')
        .then(response => response.json())
        .then(result => {
            const menu = document.getElementById("supplierMenu");
            menu.innerHTML = "";

            // Lấy mảng suppliers từ result.data
            result.data.forEach(supplier => {
                const item = document.createElement("a");
                item.href = `/supplier-ui/${supplier.supplier_id}`;
                item.className = "dropdown-item";
                item.textContent = supplier.name;
                menu.appendChild(item);
            });
        })
        .catch(error => {
            console.error("Lỗi lấy suppliers:", error);
        });

});