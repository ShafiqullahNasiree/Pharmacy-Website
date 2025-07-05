<?php
session_start();
$_SESSION['cart'] = array();
header('Location: cart.php');
exit;
?> 