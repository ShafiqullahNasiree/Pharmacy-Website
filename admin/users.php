<?php
require_once '../includes/config.php';
require_once '../includes/auth_admin.php';

requireAdmin();

$error = '';
$users = [];
try {
    $stmt = $pdo->query("SELECT id, email, role, created_at, password_changed FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll();
    // Filter: Only show one admin (the first found), and all users with role 'user'
    $filtered_users = [];
    $admin_shown = false;
    foreach ($users as $user) {
        if ($user['role'] === 'admin') {
            if (!$admin_shown) {
                $filtered_users[] = $user;
                $admin_shown = true;
            }
        } else {
            $filtered_users[] = $user;
        }
    }
    $users = $filtered_users;
} catch(PDOException $e) {
    $error = 'Error loading users.';
}

// Handle user delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    try {
        $user_id = $_GET['id'];
        // Delete cart items
        $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->execute([$user_id]);
        // Get all order ids for this user
        $order_ids_stmt = $pdo->prepare("SELECT id FROM orders WHERE user_id = ?");
        $order_ids_stmt->execute([$user_id]);
        $order_ids = $order_ids_stmt->fetchAll(PDO::FETCH_COLUMN);
        if ($order_ids) {
            // Delete order_items for these orders
            $in = str_repeat('?,', count($order_ids) - 1) . '?';
            $del_items = $pdo->prepare("DELETE FROM order_items WHERE order_id IN ($in)");
            $del_items->execute($order_ids);
        }
        // Delete orders
        $stmt = $pdo->prepare("DELETE FROM orders WHERE user_id = ?");
        $stmt->execute([$user_id]);
        // Delete user
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        header('Location: users.php');
        exit;
    } catch(PDOException $e) {
        $error = 'Error deleting user.';
    }
}

// Handle password update
if (isset($_POST['action']) && $_POST['action'] === 'change_password' && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $old_password = $_POST['old_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $change_error = '';
    $change_success = '';
    if (!$old_password || !$new_password || !$confirm_password) {
        $change_error = 'All fields are required.';
    } elseif ($new_password !== $confirm_password) {
        $change_error = 'New passwords do not match.';
    } else {
        $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();
        if ($user && password_verify($old_password, $user['password'])) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE users SET password = ?, password_changed = 1 WHERE id = ?');
            $stmt->execute([$hashed_password, $user_id]);
            $change_success = 'Password updated successfully.';
        } else {
            $change_error = 'Old password is incorrect.';
        }
    }
}

// Helper to get cart items for a user
function get_user_cart($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT p.name, c.quantity FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

// Helper to get purchased items for a user
function get_user_purchases($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT p.name, SUM(oi.quantity) as total_bought FROM orders o JOIN order_items oi ON o.id = oi.order_id JOIN products p ON oi.product_id = p.id WHERE o.user_id = ? GROUP BY p.id");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background-color: #343a40;
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 10px 15px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 px-0">
            <div class="sidebar">
                <div class="p-3">
                    <h4 class="mb-4">Pharmacy Admin</h4>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                        <a class="nav-link" href="products.php">Manage Products</a>
                        <a class="nav-link active" href="users.php">Registered Users</a>
                        <hr>
                        <a class="nav-link" href="logout.php">Logout</a>
                    </nav>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-lg-10">
            <div class="main-content p-4">
                <h2>Registered Users</h2>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <div class="card">
                    <div class="card-header">
                        <h5>All Registered Users</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($users)): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Registered At</th>
                                            <th>Password Changed?</th>
                                            <th>Cart Items</th>
                                            <th>Purchased Products</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $user): ?>
                                            <tr>
                                                <td><?php echo $user['id']; ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                                <td><?php echo $user['created_at']; ?></td>
                                                <td><?php echo $user['password_changed'] ? 'Yes' : 'No'; ?></td>
                                                <td>
                                                    <?php $cart_items = get_user_cart($pdo, $user['id']); ?>
                                                    <?php if ($cart_items): ?>
                                                        <ul style="margin-bottom:0;">
                                                        <?php foreach ($cart_items as $item): ?>
                                                            <li><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</li>
                                                        <?php endforeach; ?>
                                                        </ul>
                                                    <?php else: ?>
                                                        <span class="text-muted">None</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php $purchases = get_user_purchases($pdo, $user['id']); ?>
                                                    <?php if ($purchases): ?>
                                                        <ul style="margin-bottom:0;">
                                                        <?php foreach ($purchases as $item): ?>
                                                            <li><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['total_bought']; ?>)</li>
                                                        <?php endforeach; ?>
                                                        </ul>
                                                    <?php else: ?>
                                                        <span class="text-muted">None</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user?')">Delete</a>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal<?php echo $user['id']; ?>">Change Password</button>
                                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#userDetailsModal<?php echo $user['id']; ?>">View Details</button>
                                                    <!-- Change Password Modal -->
                                                    <div class="modal fade" id="changePasswordModal<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="changePasswordModalLabel<?php echo $user['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                        <div class="modal-content">
                                                          <form method="POST">
                                                            <input type="hidden" name="action" value="change_password">
                                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                            <div class="modal-header">
                                                              <h5 class="modal-title" id="changePasswordModalLabel<?php echo $user['id']; ?>">Change Password</h5>
                                                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                              <div class="mb-3">
                                                                <label class="form-label">Email</label>
                                                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                                              </div>
                                                              <div class="mb-3">
                                                                <label class="form-label">Old Password</label>
                                                                <input type="password" class="form-control" name="old_password" required>
                                                              </div>
                                                              <div class="mb-3">
                                                                <label class="form-label">New Password</label>
                                                                <input type="password" class="form-control" name="new_password" required>
                                                              </div>
                                                              <div class="mb-3">
                                                                <label class="form-label">Confirm New Password</label>
                                                                <input type="password" class="form-control" name="confirm_password" required>
                                                              </div>
                                                              <?php if (isset($change_error) && $change_error && isset($_POST['user_id']) && $_POST['user_id'] == $user['id']): ?>
                                                                <div class="alert alert-danger"><?php echo $change_error; ?></div>
                                                              <?php elseif (isset($change_success) && $change_success && isset($_POST['user_id']) && $_POST['user_id'] == $user['id']): ?>
                                                                <div class="alert alert-success"><?php echo $change_success; ?></div>
                                                              <?php endif; ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                              <button type="submit" class="btn btn-primary">Update Password</button>
                                                            </div>
                                                          </form>
                                                        </div>
                                                      </div>
                                                    </div>
                                                    <!-- User Details Modal -->
                                                    <div class="modal fade" id="userDetailsModal<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="userDetailsModalLabel<?php echo $user['id']; ?>" aria-hidden="true">
                                                      <div class="modal-dialog">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <h5 class="modal-title" id="userDetailsModalLabel<?php echo $user['id']; ?>">User Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                          </div>
                                                          <div class="modal-body">
                                                            <h6>Current Cart Items</h6>
                                                            <?php $cart_items = get_user_cart($pdo, $user['id']); ?>
                                                            <?php if ($cart_items): ?>
                                                                <ul>
                                                                <?php foreach ($cart_items as $item): ?>
                                                                    <li><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</li>
                                                                <?php endforeach; ?>
                                                                </ul>
                                                            <?php else: ?>
                                                                <p class="text-muted">No items in cart.</p>
                                                            <?php endif; ?>
                                                          </div>
                                                          <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No users found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Add Bootstrap JS for modal support -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 