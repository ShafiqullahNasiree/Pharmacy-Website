<?php

session_start();
include 'includes/config.php';
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
require_once __DIR__ . '/includes/config.php';
include 'includes/header.php';

if (isset($_GET['checkout']) && $_GET['checkout'] === '1') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $error_checkout = '';
    $success_checkout = '';
    if (!isset($_SESSION['user_id'])) {
        $error_checkout = 'You must be logged in to checkout.';
    } elseif (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        $user_id = $_SESSION['user_id'];
        $total = 0;
        foreach ($_SESSION['cart'] as $product_id => $qty) {
            $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
            if (!$stmt->execute([$product_id])) {
                $error_checkout = 'Failed to fetch product price.';
                break;
            }
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($product) {
                $total += $product['price'] * $qty;
            } else {
                $error_checkout = 'Product not found.';
                break;
            }
        }
        if (!$error_checkout) {
            // Insert order
            $order_stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
            if (!$order_stmt->execute([$user_id, $total])) {
                $error_checkout = 'Order creation failed.';
            } else {
                $order_id = $pdo->lastInsertId();
                // Insert order items
                foreach ($_SESSION['cart'] as $product_id => $qty) {
                    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
                    $stmt->execute([$product_id]);
                    $product = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($product) {
                        $item_stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                        if (!$item_stmt->execute([$order_id, $product_id, $qty, $product['price']])) {
                            $error_checkout = 'Failed to insert order item.';
                            break;
                        }
                    } else {
                        $error_checkout = 'Product not found for order item.';
                        break;
                    }
                }
                if (!$error_checkout) {
                    // Clear cart session and cart table for this user after successful order
                    $_SESSION['cart'] = array();
                    if (isset($user_id)) {
                        $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);
                    }
                    $success_checkout = 'Order placed successfully!';
                }
            }
        }
    } else {
        $error_checkout = 'Your cart is empty.';
    }
    if ($error_checkout) {
        echo '<div class="alert alert-danger text-center">' . htmlspecialchars($error_checkout) . '</div>';
    }
    if ($success_checkout) {
        echo '<div class="alert alert-success text-center">' . htmlspecialchars($success_checkout) . '</div>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Nasiree Pharmacy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/cart.css">
    <style>
        .cart-section {
            padding: 4rem 0;
            margin-top: 100px;
        }

        .cart-section h1 {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 3rem;
            transition: color 0.3s ease;
        }

        .cart-section h1:hover {
            color: var(--secondary-color);
        }

        .cart-table {
            width: 100%;
            margin-bottom: 2rem;
        }

        .cart-item {
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .cart-empty {
            text-align: center;
            padding: 4rem;
        }

        .cart-actions {
            margin-top: 2rem;
        }

        .cart-total {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .checkout-btn {
            padding: 1rem 3rem;
            font-size: 1.2rem;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .cart-item-actions {
            display: flex;
            gap: 1rem;
        }

        .cart-item-quantity {
            width: 80px;
        }

        .remove-item {
            color: var(--text-color);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .remove-item:hover {
            color: var(--secondary-color);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <?php include 'includes/header.php'; ?>

    <!-- Cart Section -->
    <section class="cart-section">
        <div class="container">
            <h1 class="h1 fw-bold mb-4">Shopping Cart</h1>

            <?php
            // Only show cart if there are items and it's an array
            if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])): ?>
                <div class="container py-4">
                    <div class="row g-4">
                        <?php
                        $total = 0;
                        foreach ($_SESSION['cart'] as $product_id => $qty):
                            if ($qty < 1) continue;
                            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
                            $stmt->execute([$product_id]);
                            $product = $stmt->fetch(PDO::FETCH_ASSOC);
                            if ($product):
                                $subtotal = $product['price'] * $qty;
                                $total += $subtotal;
                                // Fix image path logic to match shop.php
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
                        ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm cart-product-card">
                                <img src="<?php echo htmlspecialchars($img_path); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height:220px;object-fit:cover;">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <h5 class="card-title mb-2"><?php echo htmlspecialchars($product['name']); ?></h5>
                                    <div class="mb-2"><span class="fw-bold">Price:</span> $<?php echo number_format($product['price'], 2); ?></div>
                                    <div class="mb-2"><span class="fw-bold">Quantity:</span> <?php echo $qty; ?></div>
                                    <div class="mb-2"><span class="fw-bold">Total:</span> $<?php echo number_format($subtotal, 2); ?></div>
                                    <form method="GET" action="remove.php" onsubmit="return confirm('Remove this item from cart?');" class="mt-auto">
                                        <input type="hidden" name="id" value="<?php echo $product_id; ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm w-100" title="Remove"><i class="fas fa-trash"></i> Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endif; endforeach; ?>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12 col-md-6 mx-auto">
                            <div class="card p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>$<?php echo number_format($total, 2); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>Free</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <h5>Total:</h5>
                                    <h5>$<?php echo number_format($total, 2); ?></h5>
                                </div>
                                <a href="?checkout=1" id="checkout-btn" class="btn btn-success btn-lg w-100 checkout-btn mt-2"><i class="fas fa-shopping-cart me-2"></i>Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="cart-empty text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x mb-3"></i>
                    <h2>Your cart is empty</h2>
                    <a href="shop.php" class="btn btn-primary mt-3">Continue Shopping</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Contact Us Section for Cart Page -->
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

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var contactLinks = document.querySelectorAll('a[href=\"#contact\"]');
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
</body>
</html>
