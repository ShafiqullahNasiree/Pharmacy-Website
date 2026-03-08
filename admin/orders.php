<?php
require_once '../includes/config.php';
require_once '../includes/auth_admin.php';
requireAdmin();

$error = '';
$message = '';

// Handle order status update
if (isset($_POST['action']) && $_POST['action'] === 'update_status' && isset($_POST['order_id'], $_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];
    try {
        $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $order_id]);
        $message = 'Order status updated.';
    } catch (PDOException $e) {
        $error = 'Failed to update order status.';
    }
}

// Handle order delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $order_id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$order_id]);
        $message = 'Order deleted.';
    } catch (PDOException $e) {
        $error = 'Failed to delete order.';
    }
}

// Fetch all orders
$orders = [];
try {
    $stmt = $pdo->query("SELECT o.*, u.email AS user_email FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to load orders.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
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
                        <a class="nav-link active" href="orders.php">Manage Orders</a>
                        <a class="nav-link" href="users.php">Registered Users</a>
                        <hr>
                        <a class="nav-link" href="logout.php">Logout</a>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Main Content -->
        <div class="col-md-9 col-lg-10">
            <div class="main-content p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manage Orders</h2>
                </div>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                <div class="card">
                    <div class="card-header">
                        <h5>All Orders</h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($orders)): ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Status</th>
                                            <th>Total</th>
                                            <th>Created At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($orders as $order): ?>
                                        <tr>
                                            <td><?php echo $order['id']; ?></td>
                                            <td><?php echo htmlspecialchars($order['user_email'] ?? 'Guest'); ?></td>
                                            <td>
                                                <form method="POST" class="d-flex align-items-center">
                                                    <input type="hidden" name="action" value="update_status">
                                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                    <select name="status" class="form-select form-select-sm me-2" onchange="this.form.submit()">
                                                        <option value="pending" <?php if ($order['status'] === 'pending') echo 'selected'; ?>>Pending</option>
                                                        <option value="processing" <?php if ($order['status'] === 'processing') echo 'selected'; ?>>Processing</option>
                                                        <option value="completed" <?php if ($order['status'] === 'completed') echo 'selected'; ?>>Completed</option>
                                                        <option value="cancelled" <?php if ($order['status'] === 'cancelled') echo 'selected'; ?>>Cancelled</option>
                                                    </select>
                                                </form>
                                            </td>
                                            <td>$<?php echo number_format($order['total'], 2); ?></td>
                                            <td><?php echo $order['created_at']; ?></td>
                                            <td>
                                                <a href="?action=delete&id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this order?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No orders found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> 