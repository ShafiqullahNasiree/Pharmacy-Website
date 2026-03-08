<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
file_put_contents('cart_debug.log', date('c') . ' ' . print_r($_SESSION, true) . print_r($_POST, true) . print_r($_GET, true) . PHP_EOL, FILE_APPEND);
header('Content-Type: application/json');
require_once __DIR__ . '/../config.php';

// Initialize cart if it doesn't exist
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handle cart actions
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    switch($action) {
        case 'add':
            if (isset($_POST['product_id'])) {
                $product_id = $_POST['product_id'];
                if (!isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id] = 1;
                } else {
                    $_SESSION['cart'][$product_id]++;
                }
                // Save to database cart table if user is logged in
                if (isset($_SESSION['user_id'])) {
                    $user_id = $_SESSION['user_id'];
                    // Check if this product is already in the user's cart
                    $stmt = $pdo->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
                    $stmt->execute([$user_id, $product_id]);
                    $cart_row = $stmt->fetch();
                    if ($cart_row) {
                        // Update quantity
                        $update = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ?");
                        $update->execute([$cart_row['id']]);
                    } else {
                        // Insert new row
                        $insert = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
                        $insert->execute([$user_id, $product_id]);
                    }
                }
                ob_clean();
                echo json_encode(['status' => 'success', 'message' => 'Product added to cart']);
            }
            break;
            
        case 'remove':
            if (isset($_POST['id'])) {
                $product_id = $_POST['id'];
                if (isset($_SESSION['cart'][$product_id])) {
                    unset($_SESSION['cart'][$product_id]);
                    // Remove from database cart table if user is logged in
                    if (isset($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];
                        $delete = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
                        $delete->execute([$user_id, $product_id]);
                    }
                    ob_clean();
                    echo json_encode(['status' => 'success', 'message' => 'Product removed from cart']);
                }
            }
            break;
            
        case 'count':
            // Return total quantity of all products in cart
            $cart_count = 0;
            foreach ($_SESSION['cart'] as $qty) { $cart_count += $qty; }
            ob_clean();
            echo $cart_count;
            break;
            
        case 'get_cart':
            $cart_items = array();
            $total = 0;
            
            if (!empty($_SESSION['cart'])) {
                $product_ids = array_keys($_SESSION['cart']);
                $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN (" . implode(",", array_fill(0, count($product_ids), '?')) . ")");
                $stmt->execute($product_ids);
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($products as $product) {
                    $quantity = $_SESSION['cart'][$product['id']];
                    $subtotal = $product['price'] * $quantity;
                    $cart_items[] = array(
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => $quantity,
                        'subtotal' => $subtotal,
                        'image' => $product['image']
                    );
                    $total += $subtotal;
                }
            }
            
            ob_clean();
            echo json_encode([
                'items' => $cart_items,
                'total' => $total
            ]);
            break;
    }
    exit;
}

// Get cart items for display
function get_cart_items() {
    global $pdo;
    
    $cart_items = array();
    $total = 0;
    
    if (!empty($_SESSION['cart'])) {
        $product_ids = array_keys($_SESSION['cart']);
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN (" . implode(",", array_fill(0, count($product_ids), '?')) . ")");
        $stmt->execute($product_ids);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($products as $product) {
            $quantity = $_SESSION['cart'][$product['id']];
            $subtotal = $product['price'] * $quantity;
            $cart_items[] = array(
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'image' => $product['image']
            );
            $total += $subtotal;
        }
    }
    
    return array('items' => $cart_items, 'total' => $total);
}
?>
