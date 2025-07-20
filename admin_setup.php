<?php
require_once 'includes/config.php';

echo "<h2>Admin Setup</h2>";

// Create admin user
$email = 'admin@pharmacy.com';
$password = 'password123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Check if admin user already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existing_user = $stmt->fetch();
    
    if ($existing_user) {
        // Update existing admin user
        $stmt = $pdo->prepare("UPDATE users SET password = ?, role = 'admin' WHERE email = ?");
        $stmt->execute([$hashed_password, $email]);
        echo "<p>Admin user updated successfully.</p>";
    } else {
        // Create new admin user
        $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'admin')");
        $stmt->execute([$email, $hashed_password]);
        echo "<p>Admin user created successfully.</p>";
    }
    
    echo "<p><strong>Admin Login Details:</strong></p>";
    echo "<p>Email: admin@pharmacy.com</p>";
    echo "<p>Password: password123</p>";
    echo "<p><a href='admin/login.php'>Go to Admin Login</a></p>";
    
} catch(PDOException $e) {
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?> 