# Nasiree Pharmacy Website

A modern, full-featured web application for local pharmacies, designed to streamline online product browsing, ordering, and inventory management. Built with PHP and MySQL, this project enables customers to shop for healthcare products online while providing pharmacy staff with a secure admin dashboard for efficient management.

## 🚀 Project Overview
Nasiree Pharmacy Website is a responsive, user-friendly platform that bridges the gap between pharmacies and their customers. It allows users to:
- Browse a rich catalog of medicines and healthcare products
- Register and securely log in
- Add products to a shopping cart and place orders
- Track their orders and manage their account

Pharmacy administrators can:
- Log in to a secure admin panel
- Manage products, inventory, and categories
- View and process customer orders
- Manage user accounts

## ✨ Features
- **User Registration & Login:** Secure authentication for customers and admins
- **Product Catalog:** Browse products by category, with images and details
- **Shopping Cart:** Add, update, and remove products; persistent cart for logged-in users
- **Order Placement:** Seamless checkout and order tracking
- **Admin Dashboard:** Product, order, and user management with real-time statistics
- **Responsive Design:** Mobile-friendly UI using Bootstrap 5
- **Role-Based Access:** Separate user and admin authentication
- **Secure Passwords:** Passwords are hashed and never stored in plain text

## 🗂️ Project Structure
- `index.php` – Homepage
- `about.php` – About the pharmacy, mission, and story
- `shop.php` – Product catalog and shopping interface
- `cart.php` – Shopping cart management
- `admin/` – Admin dashboard and management tools
- `auth/` – User authentication (login, register, logout)
- `assets/` – Static assets (CSS, JS, images)
- `includes/` – Shared PHP includes (header, footer, config, auth)
- `database.sql` – Database schema and sample data

## 🛠️ Technologies Used
- **Backend:** PHP 7+
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript
- **Icons:** FontAwesome

## ⚡ Installation & Setup
1. **Clone the repository:**
   ```
   git clone https://github.com/yourusername/Pharmacy-Website.git
   ```
2. **Import the database:**
   - Open your MySQL tool (e.g., phpMyAdmin, XAMPP, WAMP)
   - Import `database.sql` to create tables and sample data
3. **Configure the project:**
   - Place the project folder in your web server directory (e.g., `htdocs` for XAMPP)
   - Ensure the `assets/images/products/` folder is present for product images
   - Update database credentials in `config.php` and `includes/config.php` if needed
4. **Start your web server:**
   - Make sure Apache and MySQL are running
5. **Access the application:**
   - Open your browser and go to `http://localhost/Pharmacy-Website`

## 👤 Admin Access
- Visit `http://localhost/Pharmacy-Website/admin/login.php`
- Default admin credentials:
  - **Email:** `admin@pharmacy.com`
  - **Password:** `password123`
- (You can change these using `admin_setup.php`)

## 🚚 Deployment & Usage
- **Frontend and backend** are integrated; no separate build steps required.
- **Database**: Import `database.sql` before first use.
- **Admin panel**: For inventory and order management, use the `/admin/` section.

## 📄 License
This project is for educational and demonstration purposes. For production use, please review and enhance security, validation, and deployment practices.

## ✍️ Author
Shafiqullah Nasiree
