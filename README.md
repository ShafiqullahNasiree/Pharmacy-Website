<br/>
<p align="center">
  <h1 align="center">💊 Nasiree Pharmacy</h1>

  <p align="center">
    A comprehensive, secure, and fully functional E-Commerce Pharmacy Web Application built with PHP & MySQL.
    <br/>
    <br/>
    <a href="https://github.com/ShafiqullahNasiree/Pharmacy-Website/issues">Report Bug</a>
    .
    <a href="https://github.com/ShafiqullahNasiree/Pharmacy-Website/issues">Request Feature</a>
  </p>
</p>

![Downloads](https://img.shields.io/github/downloads/ShafiqullahNasiree/Pharmacy-Website/total) ![Contributors](https://img.shields.io/github/contributors/ShafiqullahNasiree/Pharmacy-Website?color=dark-green) ![Issues](https://img.shields.io/github/issues/ShafiqullahNasiree/Pharmacy-Website) ![License](https://img.shields.io/github/license/ShafiqullahNasiree/Pharmacy-Website) 

## 📝 About The Project

**Nasiree Pharmacy** is a robust web-based application designed to digitalize the operations of a local pharmacy. Built using raw PHP and MySQL, this project demonstrates a complete e-commerce workflow tailored for healthcare and medicinal products. 

It provides an intuitive interface for customers to browse categories, read about medications, add items to their shopping cart, and securely place orders. For store owners, it features a comprehensive Admin Dashboard to manage inventory, track user orders, and handle customer accounts.

### Built With

This project was built utilizing standard web technologies to ensure a lightweight and lightning-fast experience:

* ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white) 
* ![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
* ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
* ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
* ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

---

## ✨ Features

### 👤 Customer Facing
- **Secure Authentication:** User registration, login, and secure session management.
- **Product Catalog:** Browse a wide variety of medicines sorted by categories with high-quality images.
- **Shopping Cart System:** Add, remove, update quantities, and clear shopping cart dynamically.
- **Order Placement:** Seamless checkout experience to place orders.
- **Responsive UI:** Fully responsive design that works beautifully on desktops, tablets, and mobile devices.

### 🛡️ Admin Dashboard (Management)
- **Secure Admin Login:** Protected route `admin/` to prevent unauthorized access.
- **Inventory Management:** Add new medicinal products, update prices, manage stock, and upload product images.
- **Order Tracking:** View all customer orders, update order statuses, and track sales.
- **User Management:** View registered users and manage customer accounts.

---

## 🚀 Getting Started

Follow these instructions to get a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

You will need a local web server environment like **XAMPP**, **WAMP**, or **MAMP** installed on your system.
* [Download XAMPP](https://www.apachefriends.org/index.html)

### Installation

1. **Clone the repository**
   ```sh
   git clone https://github.com/ShafiqullahNasiree/Pharmacy-Website.git
   ```
2. **Move to Web Directory** 
   Move the `Pharmacy-Website` folder into your local server's root directory:
   - For XAMPP: default is `C:\xampp\htdocs\`
   - For WAMP: default is `C:\wamp\www\`
3. **Setup Database**
   - Open your browser and go to `http://localhost/phpmyadmin/`.
   - Create a new database named `pharmacy_db` (or check `includes/config.php` / `config.php` for the exact DB name).
   - Import the provided `database.sql` file into this new database.
4. **Configuration**
   - If your database password is not empty, update the database connection credentials inside the `includes/config.php` or `includes/db.php` files.
5. **Run the Application**
   - Open your web browser and navigate to:
   ```text
   http://localhost/Pharmacy-Website
   ```

---

## 📂 Project Structure

```text
Pharmacy-Website/
├── admin/                  # Admin dashboard and management tools
│   ├── dashboard.php       # Overview statistics
│   ├── products.php        # Manage inventory
│   ├── orders.php          # Manage customer orders
│   └── users.php           # Manage user accounts
├── assets/                 # Static assets
│   ├── css/                # Stylesheets (style.css, shop.css, cart.css)
│   ├── images/             # Product and UI images
│   └── js/                 # Client-side JavaScript
├── auth/                   # Authentication handling
│   ├── login.php
│   ├── register.php
│   └── logout.php
├── includes/               # Reusable PHP components
│   ├── header.php          # UI Header & Navigation
│   ├── footer.php          # UI Footer
│   ├── db.php              # Database connection
│   └── cart.php            # Cart logic
├── index.php               # Landing Page
├── shop.php                # Product Catalog
├── cart.php                # Shopping Cart UI
├── database.sql            # Database schema and seed data
└── README.md               # Project documentation
```

---

## 🤝 Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any contributions you make are **greatly appreciated**.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📫 Contact

Shafiqullah Nasiree - [GitHub Profile](https://github.com/ShafiqullahNasiree)

Project Link: [https://github.com/ShafiqullahNasiree/Pharmacy-Website](https://github.com/ShafiqullahNasiree/Pharmacy-Website)
