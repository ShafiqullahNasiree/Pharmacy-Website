<?php
require_once __DIR__ . '/../config.php';

// Auth class for handling user authentication
class Auth {
    private $pdo;
    
    // Constructor that takes a PDO connection
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Register a new user
    public function register($email, $password, $role = 'user') {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->execute([$email, $hashed_password, $role]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Login a user
    public function login($email, $password) {
        try {
            $stmt = $this->pdo->prepare("SELECT id, password, role FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
    }
    
    // Check if user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    // Check if user is admin
    public function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
    
    public function logout() {
        session_destroy();
    }
}

// Initialize Auth class with $pdo
$auth = new Auth($pdo);
?>
