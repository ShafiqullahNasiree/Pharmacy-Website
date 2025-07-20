<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nasiree Pharmacy - Your Trusted Healthcare Partner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #2ecc71;
            --text-color: #333;
            --background-color: #ffffff;
            --section-bg: #f8f9fc;
            --card-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            --hero-bg: #d0e4ff;
        }

        body {
            background-color: var(--background-color);
        }

        .hero-section {
            background: linear-gradient(rgba(208, 228, 255, 0.8), rgba(208, 228, 255, 0.8)), url('assets/images/hero-bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
            text-align: center;
            padding: 150px 0;
            margin-bottom: 50px;
        }

        .hero-section h1 {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            color: #333;
        }

        .hero-section h1:hover {
            color: var(--secondary-color);
        }

        .hero-section p {
            font-size: 1.8rem;
            margin-bottom: 3rem;
            opacity: 0.9;
            color: #333;
            font-weight: 500;
            letter-spacing: 0.5px;
            line-height: 1.5;
        }

        .shop-now-btn {
            padding: 1rem 3rem;
            font-size: 1.2rem;
            border-radius: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, var(--primary-color), var(--primary-color));
            border: none;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            margin-top: 2rem;
            text-decoration: none;
            display: inline-block;
            position: relative;
            z-index: 1;
        }

        .shop-now-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            text-decoration: none;
        }

        .shop-now-btn:active {
            transform: translateY(0);
            text-decoration: none;
        }

        .shop-now-btn:focus {
            outline: none;
            text-decoration: none;
        }

        .featured-products {
            padding: 4rem 0;
            background-color: var(--section-bg);
        }

        .featured-products h2 {
            color: var(--text-color);
            margin-bottom: 3rem;
        }

        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .product-card h3 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }

        .product-card:hover h3 {
            color: var(--secondary-color);
        }

        .product-card p {
            color: var(--text-color);
            opacity: 0.8;
            margin-bottom: 1rem;
            transition: opacity 0.3s ease;
        }

        .product-card:hover p {
            opacity: 1;
        }

        .product-price {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .product-card .btn {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
            border-radius: 30px;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
        }

        .product-card .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }

        .contact-section {
            padding: 4rem 0;
            background: linear-gradient(rgba(0, 0, 0, 0.05), rgba(0, 0, 0, 0.05)), url('assets/images/contact-bg.jpg');
            background-size: cover;
            background-position: center;
        }

        .contact-info {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .contact-info:hover {
            transform: translateX(5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .contact-info i {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
            transition: color 0.3s ease;
        }

        .contact-info:hover i {
            color: var(--secondary-color);
        }

        .contact-info h4 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .contact-info p {
            font-size: 1rem;
            color: var(--text-color);
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .contact-info:hover p {
            opacity: 1;
        }

        /* Footer */
        .footer {
            background: var(--background-color);
            padding: 4rem 0 2rem;
            margin-top: 4rem;
        }

        .footer-title {
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .footer-title:hover {
            color: var(--secondary-color);
            transform: translateX(5px);
        }

        .footer-text {
            color: var(--text-color);
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .footer-text:hover {
            opacity: 1;
        }

        .footer i {
            color: var(--secondary-color);
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }

        .footer i:hover {
            transform: scale(1.2);
        }

        .footer-social {
            margin-top: 2rem;
        }

        .footer-social a {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-right: 1rem;
            transition: all 0.3s ease;
        }

        .footer-social a:hover {
            color: var(--secondary-color);
            transform: translateY(-5px);
        }

        /* Smooth Scrolling */
        a[href="#contact"] {
            scroll-behavior: smooth;
        }

        /* Cart & Search Icons */
        .cart-icon, .search-icon {
            color: var(--secondary-color);
            transition: all 0.3s ease;
        }

        .cart-icon:hover, .search-icon:hover {
            transform: scale(1.1);
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1>Welcome to Nasiree Pharmacy</h1>
            <p>Your trusted healthcare partner since 2025</p>
            <a href="shop.php" class="shop-now-btn">
                <i class="fas fa-shopping-cart me-2"></i>Shop Now
            </a>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <h2 class="h1 fw-bold text-center mb-5">Contact Us</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="contact-info">
                        <i class="fas fa-envelope"></i>
                        <h4>Email Us</h4>
                        <p>contact@nasireepharmacy.com</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="contact-info">
                        <i class="fas fa-phone"></i>
                        <h4>Call Us</h4>
                        <p>+1 (555) 123-4567</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="contact-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Visit Us</h4>
                        <p>123 Pharmacy Street, Medical City</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-light mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h4 class="footer-title">Nasiree Pharmacy</h4>
                    <p class="footer-text">Your trusted healthcare partner since 2025</p>
                    <div class="footer-social">
                        <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.twitter.com/" target="_blank" rel="noopener noreferrer"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="footer-text">Home</a></li>
                        <li><a href="about.php" class="footer-text">About Us</a></li>
                        <li><a href="shop.php" class="footer-text">Shop Now</a></li>
                        <li><a href="#contact" class="footer-text">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h4 class="footer-title">Contact Info</h4>
                    <p class="footer-text">
                        <i class="fas fa-envelope"></i> contact@nasireepharmacy.com<br>
                        <i class="fas fa-phone"></i> +1 (555) 123-4567<br>
                        <i class="fas fa-map-marker-alt"></i> 123 Pharmacy Street, Medical City
                    </p>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; <?php echo date('Y'); ?> Nasiree Pharmacy. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Fix contact scroll delay
        document.addEventListener('DOMContentLoaded', function() {
            const contactLinks = document.querySelectorAll('a[href="#contact"]');
            contactLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.getElementById('contact');
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop - 70,
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });

        // Update cart count when page loads
        $(document).ready(function() {
            if ('<?php echo isset($_SESSION["cart"]) ? count($_SESSION["cart"]) : 0; ?>' > 0) {
                $('#cart-count').text('<?php echo isset($_SESSION["cart"]) ? count($_SESSION["cart"]) : 0; ?>');
            }
        });

        // Search functionality
        $('#search-form').submit(function(e) {
            e.preventDefault();
            const searchTerm = $('#search-term').val();
            if (searchTerm.trim() !== '') {
                window.location.href = 'shop.php?search=' + encodeURIComponent(searchTerm);
            }
        });
    </script>
</body>
</html>
