<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'config.php';
require_once 'includes/auth.php';
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Nasiree Pharmacy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/shop.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #2ecc71;
            --text-color: #333;
            --hero-overlay: rgba(0, 0, 0, 0.3);
        }

        .hero-section {
            background: linear-gradient(var(--hero-overlay), var(--hero-overlay)), url('assets/images/shop-hero.jpg');
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
            background: var(--hero-overlay);
            z-index: 0;
        }

        .hero-section .container {
            position: relative;
            z-index: 1;
        }

        .hero-section h1 {
            font-size: 4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            color: white;
        }

        .hero-section h1:hover {
            color: var(--secondary-color);
        }

        .hero-section p {
            font-size: 1.8rem;
            margin-bottom: 3rem;
            opacity: 0.9;
            color: white;
            font-weight: 500;
            letter-spacing: 0.5px;
            line-height: 1.5;
        }

        .start-shopping-btn {
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

        .start-shopping-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            background: linear-gradient(135deg, var(--secondary-color), var(--secondary-color));
            text-decoration: none;
        }

        .start-shopping-btn:active {
            transform: translateY(0);
            text-decoration: none;
        }

        .start-shopping-btn:focus {
            outline: none;
            text-decoration: none;
        }

        .category-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .category-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .category-card h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            transition: color 0.3s ease;
        }

        .category-card:hover h3 {
            color: var(--secondary-color);
        }

        .category-card p {
            color: var(--text-color);
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .category-card:hover p {
            opacity: 1;
        }

        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .product-card .product-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-color);
            transition: color 0.3s ease;
        }

        .product-card:hover .product-title {
            color: var(--secondary-color);
        }

        .product-card .product-price {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .product-card .btn {
            transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            color: white;
        }

        .product-card .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }

        .sort-select {
            width: 200px;
        }

        .sort-select select {
            transition: all 0.3s ease;
            border: 1px solid var(--primary-color);
            border-radius: 5px;
            padding: 0.5rem;
        }

        .sort-select select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(46, 204, 113, 0.25);
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
            <p>Discover our wide range of healthcare products and medicines</p>
            <a href="#products" class="start-shopping-btn">
                <i class="fas fa-shopping-cart me-2"></i>Start Shopping
            </a>
        </div>
    </section>

    <!-- Product Categories -->
    <section class="categories-section py-5">
        <div class="container">
            <h2 class="h1 fw-bold text-center mb-5">Shop by Category</h2>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-pills fa-3x"></i>
                        </div>
                        <h3 class="h5 fw-bold">Medicines</h3>
                        <p class="text-muted">Prescription and over-the-counter drugs</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-vial fa-3x"></i>
                        </div>
                        <h3 class="h5 fw-bold">Healthcare Products</h3>
                        <p class="text-muted">Medical supplies and equipment</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-first-aid fa-3x"></i>
                        </div>
                        <h3 class="h5 fw-bold">First Aid</h3>
                        <p class="text-muted">Emergency medical supplies</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-capsules fa-3x"></i>
                        </div>
                        <h3 class="h5 fw-bold">Supplements</h3>
                        <p class="text-muted">Health and wellness supplements</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Grid -->
    <section id="products" class="products-section py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h2 class="h1 fw-bold mb-3">Featured Products</h2>
                </div>
            </div>
            <div class="products-grid">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php
                    try {
                        $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
                        while ($product = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $img = $product['image'];
                            if ($img) {
                                if (strpos($img, 'assets/') === 0) {
                                    $img_path = $img;
                                } elseif (strpos($img, 'images/') === 0) {
                                    $img_path = 'assets/' . $img;
                                } else {
                                    $img_path = 'assets/images/' . $img;
                                }
                                // Fallback if file does not exist or is not readable
                                if (!is_readable($img_path)) {
                                    $img_path = 'assets/images/no-image.png';
                                }
                            } else {
                                $img_path = 'assets/images/no-image.png';
                            }
                            echo '<div class="col">';
                            echo '<div class="product-card h-100">';
                            echo '<img src="' . $img_path . '" alt="' . htmlspecialchars($product['name']) . '" class="card-img-top product-image">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $product['name'] . '</h5>';
                            echo '<p class="card-text">' . substr($product['description'], 0, 100) . '...</p>';
                            echo '<div class="product-price mb-3">$' . number_format($product['price'], 2) . '</div>';
                            echo '<div class="product-actions">';
                            echo '<button type="button" class="btn btn-primary btn-sm add-to-cart" data-id="' . $product['id'] . '" data-name="' . htmlspecialchars($product['name']) . '"><i class="fas fa-shopping-cart me-2"></i>Add to Cart</button>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } catch(PDOException $e) {
                        echo '<div class="col-12">';
                        echo '<div class="alert alert-danger">Error loading products</div>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Add to Cart JavaScript -->
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const addCartBtns = document.querySelectorAll('.add-to-cart');
                    addCartBtns.forEach(function(btn) {
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            const productId = this.dataset.id;
                            const productName = this.dataset.name;
                            fetch('includes/cart.php?action=add', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                body: `product_id=${productId}`
                            })
                            .then(response => response.json())
                            .then(data => {
                                if(data.status === 'success') {
                                    alert('Added ' + productName + ' to cart!');
                                    updateCartCount();
                                } else {
                                    alert('Failed to add to cart.');
                                }
                            })
                            .catch(() => {
                                alert('An error occurred.');
                            });
                        });
                    });
                });
                function updateCartCount() {
                    fetch('includes/cart.php?action=count')
                        .then(response => response.text())
                        .then(count => {
                            const cartCountElement = document.getElementById('cart-count');
                            if (cartCountElement) {
                                cartCountElement.textContent = count;
                            }
                        });
                }
            </script>

            <!-- Update Cart Count Function -->
            <script>
                function updateCartCount() {
                    fetch('includes/cart.php?action=count')
                        .then(response => response.text())
                        .then(count => {
                            const cartCountElement = document.getElementById('cart-count');
                            if (cartCountElement) {
                                cartCountElement.textContent = count;
                            }
                        });
                }
            </script>
        </div>
    </div>
</div>

<!-- Contact Us Section for Shop Page -->
<section id="contact" class="contact-section bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">Contact Us</h2>
        <div class="row justify-content-center">
            <div class="col-md-4 text-center">
                <div class="contact-info mb-3">
                    <i class="fas fa-envelope fa-2x mb-2"></i>
                    <h4>Email</h4>
                    <p>contact@nasireepharmacy.com</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="contact-info mb-3">
                    <i class="fas fa-phone fa-2x mb-2"></i>
                    <h4>Phone</h4>
                    <p>+1 (555) 123-4567</p>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <div class="contact-info mb-3">
                    <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
                    <h4>Location</h4>
                    <p>123 Pharmacy Street, Medical City</p>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var contactLinks = document.querySelectorAll('a[href="#contact"]');
    contactLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var contactSection = document.getElementById('contact');
            if (contactSection) {
                contactSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });
});
</script>

<?php
include 'includes/footer.php';
?>
