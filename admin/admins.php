<?php
require_once '../includes/config.php';
require_once '../includes/auth_admin.php';
requireAdmin();

$error = '';
$message = '';

// Handle admin registration
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    if (!$email || !$password || !$confirm_password) {
        $error = 'All fields are required.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'admin')");
            $stmt->execute([$email, $hashed_password]);
            $message = 'Admin registered successfully.';
        } catch (PDOException $e) {
            $error = 'Error registering admin.';
        }
    }
}

// Handle admin delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'admin'");
        $stmt->execute([$_GET['id']]);
        $message = 'Admin deleted.';
    } catch (PDOException $e) {
        $error = 'Error deleting admin.';
    }
}

// Handle admin update (email only, not password)
if (isset($_POST['action']) && $_POST['action'] === 'update_admin' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $email = $_POST['email'] ?? '';
    if (!$email) {
        $error = 'Email is required.';
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ? AND role = 'admin'");
            $stmt->execute([$email, $id]);
            $message = 'Admin updated.';
        } catch (PDOException $e) {
            $error = 'Error updating admin.';
        }
    }
}

// Handle profile update (password change for current admin)
if (isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $admin_id = $_SESSION['user_id'];
    if (!$old_password || !$new_password || !$confirm_password) {
        $error = 'All fields are required.';
    } elseif ($new_password !== $confirm_password) {
        $error = 'New passwords do not match.';
    } else {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ? AND role = 'admin'");
        $stmt->execute([$admin_id]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($old_password, $admin['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ?, password_changed = 1 WHERE id = ?");
            $stmt->execute([$hashed_password, $admin_id]);
            $message = 'Password updated successfully.';
        } else {
            $error = 'Old password is incorrect.';
        }
    }
}

// Fetch all admins
$admins = [];
try {
    $stmt = $pdo->query("SELECT id, email FROM users WHERE role = 'admin' ORDER BY id");
    $admins = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = 'Failed to load admins.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar { background-color: #343a40; min-height: 100vh; color: white; }
        .sidebar .nav-link { color: rgba(255,255,255,0.8); padding: 10px 15px; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: white; background-color: rgba(255,255,255,0.1); }
        .main-content { background-color: #f8f9fa; min-height: 100vh; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0">
            <div class="sidebar">
                <div class="p-3">
                    <h4 class="mb-4">Pharmacy Admin</h4>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                        <a class="nav-link" href="products.php">Manage Products</a>
                        <a class="nav-link" href="orders.php">Manage Orders</a>
                        <a class="nav-link" href="users.php">Registered Users</a>
                        <a class="nav-link active" href="admins.php">Manage Admins</a>
                        <hr>
                        <a class="nav-link" href="login.php">Login</a>
                        <a class="nav-link" href="logout.php">Logout</a>
                        <a class="nav-link" href="#profile-update">Update Admin Profile</a>
                        <a class="nav-link" href="#register-admin">Register New Admin</a>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="main-content p-4">
                <h2>Admin Management</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                <!-- Admins List -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>All Admins</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($admins)): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Email</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($admins as $admin): ?>
                                        <tr>
                                            <td><?php echo $admin['id']; ?></td>
                                            <td>
                                                <form method="POST" class="d-flex align-items-center">
                                                    <input type="hidden" name="action" value="update_admin">
                                                    <input type="hidden" name="id" value="<?php echo $admin['id']; ?>">
                                                    <input type="email" name="email" value="<?php echo htmlspecialchars($admin['email']); ?>" class="form-control form-control-sm me-2" required>
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="?action=delete&id=<?php echo $admin['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this admin?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No admins found.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Register New Admin -->
                <div class="card mb-4" id="register-admin">
                    <div class="card-header">
                        <h5>Register New Admin</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="register">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-success">Register New Admin</button>
                        </form>
                    </div>
                </div>
                <!-- Update Admin Profile -->
                <div class="card mb-4" id="profile-update">
                    <div class="card-header">
                        <h5>Update Admin Profile (Change Password)</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_profile">
                            <div class="mb-3">
                                <label for="old_password" class="form-label">Old Password</label>
                                <input type="password" class="form-control" id="old_password" name="old_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 