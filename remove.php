<?php
// Check if session is already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Remove item from cart (associative array: product_id => qty)
    if (isset($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    }
    // Remove from database cart table if user is logged in
    if (isset($_SESSION['user_id'])) {
        require_once 'includes/config.php';
        $user_id = $_SESSION['user_id'];
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$user_id, $id]);
    }
    // Redirect back to cart with success message
    header('Location: cart.php?removed=1');
    exit;
} else {
    // Redirect back to cart if no ID provided
    header('Location: cart.php');
    exit;
}
?>
