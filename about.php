<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Nasiree Pharmacy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #2ecc71;
            --text-color: #333;
        }

        body {
            background-color: white;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('assets/images/about-us2.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            text-align: center;
            padding: 150px 0;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 0;
        }

        .hero-section .container {
            position: relative;
            z-index: 1;
        }

        .hero-section h1 {
            font-size: 4.5rem;
            font-weight: 800;
            margin-bottom: 2rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            color: var(--primary-color);
        }

        .hero-section h1:hover {
            color: var(--secondary-color);
        }

        .hero-section p {
            font-size: 1.5rem;
            margin-bottom: 3rem;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
            color: white;
        }

        /* About Content */
        .about-section {
            padding: 4rem 0;
            background: white;
        }

        .about-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 3rem;
            transition: color 0.3s ease;
        }

        .about-section h2:hover {
            color: var(--secondary-color);
        }

        .about-content {
            font-size: 1.1rem;
            color: var(--text-color);
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        /* Our Story */
        .our-story {
            padding: 4rem 0;
        }

        .our-story h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 2rem;
            text-align: center;
        }

        .our-story p {
            font-size: 1.1rem;
            font-weight: 500;
            line-height: 1.8;
            color: var(--text-color);
            text-align: justify;
            opacity: 0.9;
        }

        .our-story p::first-letter {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--secondary-color);
            float: left;
            margin-right: 10px;
            margin-top: -5px;
        }

        .our-story p:hover {
            opacity: 1;
        }

        /* Mission Section */
        .mission-section {
            padding: 4rem 0;
            background: linear-gradient(rgba(0, 0, 0, 0.05), rgba(0, 0, 0, 0.05)), url('assets/images/mission-bg.jpg');
            background-size: cover;
            background-position: center;
        }

        .mission-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 3rem;
            transition: color 0.3s ease;
        }

        .mission-section h2:hover {
            color: var(--secondary-color);
        }

        /* Mission Cards */
        .mission-card {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .mission-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .mission-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            transition: color 0.3s ease;
        }

        .mission-card:hover i {
            color: var(--secondary-color);
        }

        .mission-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 1rem;
        }

        .mission-card p {
            font-size: 1.1rem;
            color: var(--text-color);
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .mission-card:hover p {
            opacity: 1;
        }

        /* Contact Info */
        .contact-info {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
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

        /* Social Links */
        .social-links {
            margin-top: 2rem;
        }

        .social-links a {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin-right: 1rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: var(--secondary-color);
            transform: translateY(-5px);
        }

        /* Footer */
        .footer {
            background: #f8f9fa;
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
            <h1>About Us</h1>
            <p>Your trusted healthcare partner since 2025</p>
        </div>
    </section>

    <!-- About Content -->
    <section class="about-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="our-story">
                        <h2>Our Story</h2>
                        <p>Nasiree Pharmacy was founded with a vision to provide quality healthcare products and services to our community. We believe in the power of healthcare and its ability to transform lives.</p>
                        <p>Our team of dedicated professionals is committed to excellence and customer satisfaction. We strive to maintain the highest standards in everything we do.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <img src="assets/images/about-us.jpg" alt="About Us" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="mission-section">
        <div class="container">
            <h2>Our Mission</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="mission-card">
                        <i class="fas fa-heart"></i>
                        <h3>Patient Care</h3>
                        <p>We prioritize patient care and well-being in everything we do. Our team is dedicated to providing compassionate and professional service.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mission-card">
                        <i class="fas fa-capsules"></i>
                        <h3>Quality Products</h3>
                        <p>We source only the highest quality medicines and healthcare products from trusted suppliers. Your health is our priority.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mission-card">
                        <i class="fas fa-hand-holding-heart"></i>
                        <h3>Community Service</h3>
                        <p>We are committed to giving back to our community. Through various health initiatives and programs, we aim to make healthcare accessible to all.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Info -->
    <section class="contact-section" id="contact">
        <div class="container">
            <h2 class="text-center mb-5">Contact Us</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="contact-info">
                        <i class="fas fa-envelope"></i>
                        <h4>Email</h4>
                        <p>contact@nasireepharmacy.com</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-info">
                        <i class="fas fa-phone"></i>
                        <h4>Phone</h4>
                        <p>+1 (555) 123-4567</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <h4>Location</h4>
                        <p>123 Pharmacy Street, Medical City</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4 social-links">
                <a href="https://facebook.com/" class="text-primary me-3" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com/" class="text-primary me-3" target="_blank"><i class="fab fa-twitter"></i></a>
                <a href="https://www.instagram.com/" class="text-primary me-3" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://linkedin.com/" class="text-primary" target="_blank"><i class="fab fa-linkedin"></i></a>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for contact link (About page only)
        function smoothScrollToContact() {
            var contactSection = document.getElementById('contact');
            if (contactSection) {
                contactSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
            return false;
        }
    </script>
</body>
</html>
