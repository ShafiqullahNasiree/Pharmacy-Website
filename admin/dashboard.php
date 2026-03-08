<?php
require_once '../includes/config.php';
require_once '../includes/auth_admin.php';

// Require admin access
requireAdmin();

// Get statistics
$stats = [];
try {
    // Total products
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
    $stats['total_products'] = $stmt->fetch()['total'];
    
    // Total users
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
    $stats['total_users'] = $stmt->fetch()['total'];
    
    // Low stock products (less than 10)
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM products WHERE stock < 10");
    $stats['low_stock'] = $stmt->fetch()['total'];
    
    // Total value of inventory
    $stmt = $pdo->query("SELECT SUM(price * stock) as total FROM products");
    $stats['inventory_value'] = $stmt->fetch()['total'] ?? 0;
    
    // Recent products
    $stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC LIMIT 5");
    $recent_products = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = 'Error loading dashboard data';
}

// Show purchase report
try {
    $stmt = $pdo->query("SELECT o.id AS order_id, u.email, p.name AS product_name, oi.quantity FROM orders o JOIN users u ON o.user_id = u.id JOIN order_items oi ON o.id = oi.order_id JOIN products p ON oi.product_id = p.id ORDER BY o.id DESC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $orders = [];
    $purchase_report_error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
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
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar">
                    <div class="p-3">
                        <h4 class="mb-4">Pharmacy Admin</h4>
                        <nav class="nav flex-column">
                            <a class="nav-link active" href="dashboard.php">Dashboard</a>
                            <a class="nav-link" href="products.php">Manage Products</a>
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
                    <!-- Header -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Admin Dashboard</h2>
                        <div class="text-muted">
                            <?php echo date('F j, Y'); ?>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <h3><?php echo $stats['total_products']; ?></h3>
                                <p class="text-muted mb-0">Total Products</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <h3><?php echo $stats['total_users']; ?></h3>
                                <p class="text-muted mb-0">Registered Users</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <h3><?php echo $stats['low_stock']; ?></h3>
                                <p class="text-muted mb-0">Low Stock Items</p>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <h3>$<?php echo number_format($stats['inventory_value'], 2); ?></h3>
                                <p class="text-muted mb-0">Inventory Value</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <a href="products.php?action=add" class="btn btn-primary me-2">Add New Product</a>
                                    <a href="products.php" class="btn btn-info">View All Products</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Products -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Recent Products</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($recent_products)): ?>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Price</th>
                                                        <th>Stock</th>
                                                        <th>Category</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($recent_products as $product): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                                                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                                                            <td><?php echo $product['stock']; ?></td>
                                                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">No products found.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Purchase Report Section -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Purchase Report</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($orders)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Order ID</th>
                                                        <th>User Email</th>
                                                        <th>Product Name</th>
                                                        <th>Quantity</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($orders as $order): ?>
                                                        <tr>
                                                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                                                            <td><?php echo htmlspecialchars($order['email']); ?></td>
                                                            <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                                            <td><?php echo $order['quantity']; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php elseif (isset($purchase_report_error)): ?>
                                        <div class="alert alert-danger">Error loading purchase report: <?php echo htmlspecialchars($purchase_report_error); ?></div>
                                    <?php else: ?>
                                        <p class="text-muted">No purchase records found.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
