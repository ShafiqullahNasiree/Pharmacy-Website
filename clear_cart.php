<?php
session_start();
$_SESSION['cart'] = array();
// Remove all cart items from database for logged-in user
if (isset($_SESSION['user_id'])) {
    require_once 'includes/config.php';
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
}
header('Location: cart.php');
exit;
?> 