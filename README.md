# 💻 Electronic Devices Store

A modern online store for selling and managing various electronic devices such as laptops, smartphones, monitors, and accessories.  
Customers can easily browse and purchase products, while administrators can manage inventory, orders, and product categories.

---

## ✨ Features

### 🛒 For Customers
- Browse and search for electronic devices by name, brand, or category  
- Filter products by price, brand, or availability  
- View detailed product information (images, specs, stock status)  
- Add items to the shopping cart  
- Checkout using **VNPay** or **MoMoPay**  
- Track order status after purchase  
- Create and manage personal accounts  

### ⚙️ For Administrators
- Manage product list (add, edit, delete)  
- Manage categories, brands, and stock levels  
- Manage orders and update order statuses  
- Dashboard overview of sales, revenue, and inventory  
- Basic role-based access control (optional)

---

## 🧰 Technologies Used

- **Frontend:** HTML, CSS, JavaScript (and optionally TypeScript)  
- **Backend:** Laravel (PHP Framework)  
- **Database:** MySQL  
- **Payment Gateway:** VNPay, MoMoPay  
- **Version Control:** Git & GitHub  
- **Optional:** Bootstrap or TailwindCSS for responsive UI  

---

## 🚀 Installation & Setup

1. Clone the repository  
   ```bash
   git clone https://github.com/yourusername/electronic-store.git
2. Install dependencies

composer install
npm install


3. Copy environment file and configure

cp .env.example .env


4. Update database and payment gateway info in .env

5. Run migrations and seed initial data

php artisan migrate --seed


6. Start the development server

php artisan serve
