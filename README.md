# Nasiree Pharmacy Website

A simple web application for a local pharmacy, built with PHP and MySQL. Users can browse products, add items to their cart, and place orders. Admins can manage products and users through a secure dashboard.

## Features
- User registration and login
- Product catalog with images and categories
- Shopping cart functionality
- Admin panel for managing products, orders, and users
- Responsive design

## How to Run Locally
1. Import `database.sql` into your MySQL server (e.g., XAMPP or WAMP).
2. Place the project folder in your web server directory (e.g., `htdocs` for XAMPP).
3. Make sure the `assets/images/products/` folder is included for product images.
4. Open `http://localhost/Pharmacy-Website` in your browser.


## File Structure
- `index.php` – Homepage
- `shop.php` – Shop/products page
- `cart.php` – Shopping cart
- `admin/` – Admin dashboard and management
- `assets/` – Images, CSS, JS
- `auth/` – User authentication
- `includes/` – Shared PHP includes
- `database.sql` – Database schema
