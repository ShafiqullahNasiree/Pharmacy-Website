<?php
require_once '../includes/config.php';
require_once '../includes/auth_admin.php';

// Require admin access
requireAdmin();

$error = '';
$message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category = $_POST['category'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $image = $_FILES['image'] ?? null;

    try {
        $image_path = null;
        
        // Handle image upload
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            $upload_dir_rel = 'assets/images/products/';
            $upload_dir_abs = __DIR__ . '/../' . $upload_dir_rel;
            if (!is_dir($upload_dir_abs)) {
                mkdir($upload_dir_abs, 0777, true);
            }
            $file_extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (in_array($file_extension, $allowed_extensions)) {
                // Use exact filename for specific products
                $special_filenames = [
                    'Multivitamin' => 'multivitamin.jpg',
                    'Lisinopril' => 'product6.jpg'
                ];
                if (isset($special_filenames[$name])) {
                    $new_filename = $special_filenames[$name];
                } else {
                    $new_filename = uniqid() . '.' . $file_extension;
                }
                $upload_path_abs = $upload_dir_abs . $new_filename;
                $upload_path_rel = $upload_dir_rel . $new_filename;
                if (move_uploaded_file($image['tmp_name'], $upload_path_abs)) {
                    $image_path = $upload_path_rel;
                } else {
                    $error = 'Failed to upload image';
                }
            } else {
                $error = 'Invalid image format. Allowed: JPG, PNG, GIF, WEBP';
            }
        }

        if (!$error) {
            if ($action === 'add') {
                $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, stock, image) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$name, $description, $price, $category, $stock, $image_path]);
                $message = 'Product added successfully';
            } elseif ($action === 'edit' && $id) {
                if ($image_path) {
                    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, stock = ?, image = ? WHERE id = ?");
                    $stmt->execute([$name, $description, $price, $category, $stock, $image_path, $id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, stock = ? WHERE id = ?");
                    $stmt->execute([$name, $description, $price, $category, $stock, $id]);
                }
                $message = 'Product updated successfully';
            }
        }
    } catch(PDOException $e) {
        $error = 'Error saving product: ' . $e->getMessage();
    }
}

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    try {
        // Get image path before deleting
        $stmt = $pdo->prepare("SELECT image FROM products WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $product = $stmt->fetch();
        
        // Delete the product
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        
        // Delete image file if exists
        if ($product && $product['image']) {
            $image_path = '../' . $product['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        $message = 'Product deleted successfully';
    } catch(PDOException $e) {
        $error = 'Error deleting product';
    }
}

// Get product for editing
$edit_product = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $edit_product = $stmt->fetch();
    } catch(PDOException $e) {
        $error = 'Error loading product';
    }
}

// Get products for display
$products = [];
try {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY name");
    $products = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = 'Error loading products';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
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
        .product-image {
            max-width: 80px;
            max-height: 80px;
            object-fit: cover;
            border-radius: 5px;
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
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                            <a class="nav-link active" href="products.php">Manage Products</a>
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
                        <h2>Manage Products</h2>
                        <a href="?action=add" class="btn btn-primary">Add New Product</a>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if ($message): ?>
                        <div class="alert alert-success"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <!-- Add/Edit Product Form -->
                    <?php if (isset($_GET['action']) && ($_GET['action'] === 'add' || $_GET['action'] === 'edit')): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><?php echo $_GET['action'] === 'add' ? 'Add New Product' : 'Edit Product'; ?></h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="action" value="<?php echo $_GET['action']; ?>">
                                    <?php if ($edit_product): ?>
                                        <input type="hidden" name="id" value="<?php echo $edit_product['id']; ?>">
                                    <?php endif; ?>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Product Name</label>
                                                <input type="text" class="form-control" id="name" name="name" 
                                                       value="<?php echo $edit_product ? htmlspecialchars($edit_product['name']) : ''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="category" class="form-label">Category</label>
                                                <input type="text" class="form-control" id="category" name="category" 
                                                       value="<?php echo $edit_product ? htmlspecialchars($edit_product['category']) : ''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Price</label>
                                                <input type="number" step="0.01" class="form-control" id="price" name="price" 
                                                       value="<?php echo $edit_product ? $edit_product['price'] : ''; ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="stock" class="form-label">Stock</label>
                                                <input type="number" class="form-control" id="stock" name="stock" 
                                                       value="<?php echo $edit_product ? $edit_product['stock'] : ''; ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $edit_product ? htmlspecialchars($edit_product['description']) : ''; ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Product Image</label>
                                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                        <?php if ($edit_product && $edit_product['image']): ?>
                                            <small class="text-muted">Current image: <?php echo $edit_product['image']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">Save Product</button>
                                        <a href="products.php" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Products Table -->
                    <div class="card">
                        <div class="card-header">
                            <h5>All Products</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($products)): ?>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Category</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($products as $product): ?>
                                                <tr>
                                                    <td>
                                                        <?php if ($product['image']): ?>
                                                            <?php 
                                                                $img = $product['image'];
                                                                if (strpos($img, 'assets/') === 0) {
                                                                    $img_path = '../' . $img;
                                                                } elseif (strpos($img, 'images/') === 0) {
                                                                    $img_path = '../assets/' . $img;
                                                                } else {
                                                                    $img_path = '../assets/images/' . $img;
                                                                }
                                                                // Check if file exists, else fallback
                                                                $img_abs = __DIR__ . '/../' . ltrim($img_path, './');
                                                                if (!file_exists($img_abs)) {
                                                                    $img_path = '../assets/images/no-image.png';
                                                                }
                                                            ?>
                                                            <img src="<?php echo $img_path; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                                                        <?php else: ?>
                                                            <span class="text-muted">No image</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                                                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                                                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                                                    <td><?php echo $product['stock']; ?></td>
                                                    <td>
                                                        <a href="?action=edit&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                                        <a href="?action=delete&id=<?php echo $product['id']; ?>" class="btn btn-sm btn-outline-danger" 
                                                           onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                                    </td>
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
        </div>
    </div>
</body>
</html>
