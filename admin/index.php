<?php
// Admin index file - redirect to login or dashboard
session_start();

// Check if already logged in as admin
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header('Location: dashboard.php');
    exit;
} else {
    header('Location: login.php');
    exit;
}
?> 