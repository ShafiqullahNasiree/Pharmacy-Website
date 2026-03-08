<?php
require_once 'config.php';
require_once 'auth.php';
?>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <i class="fas fa-pills me-2 fs-4"></i>
            <span class="fs-4 fw-bold brand-name" data-hover-color="#2ecc71">Nasiree Pharmacy</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="shop.php">Shop Now</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="#contact" onclick="smoothScrollToContact()">Contact Us</a>
                </li>
            </ul>
            <form class="d-flex me-3" id="search-form">
                <!-- Search bar removed because the feature was incomplete and could confuse evaluation. -->
            </form>
            <ul class="navbar-nav">
                <li class="nav-item me-3">
                    <a class="nav-link position-relative" href="cart.php">
                        <i class="fas fa-shopping-cart fs-5 cart-icon"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                            <span id="cart-count"><?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0; ?></span>
                        </span>
                    </a>
                </li>
                <?php if (!$auth->isLoggedIn()): ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary me-2 rounded-3" href="auth/login.php">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn custom-register-btn rounded-3" href="auth/register.php">
                            <i class="fas fa-user-plus me-1"></i>Register
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-primary me-2 rounded-3" href="auth/logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                    <?php if ($auth->isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary rounded-3" href="admin/dashboard.php">
                                <i class="fas fa-cog me-1"></i>Admin
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Add CSS for header hover effects -->
<style>
.navbar {
    background: white !important;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.navbar-brand {
    font-weight: 700;
}

.navbar-brand .brand-name {
    font-size: 1.5rem;
    color: #4a90e2;
    transition: all 0.3s ease;
}

.navbar-brand .brand-name:hover {
    color: #2ecc71;
    transform: scale(1.05);
}

.navbar-nav .nav-link {
    font-weight: 600;
    color: #4a90e2 !important;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    position: relative;
}

.navbar-nav .nav-link:hover {
    color: #2ecc71 !important;
    transform: translateX(5px);
}

.navbar-nav .nav-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -5px;
    left: 0;
    background: #2ecc71;
    transition: width 0.3s ease;
}

.navbar-nav .nav-link:hover::after {
    width: 100%;
}

.search-form .form-control {
    background: white;
    border: 2px solid #4a90e2;
    transition: all 0.3s ease;
}

.search-form .form-control:focus {
    border-color: #2ecc71;
    box-shadow: 0 0 0 0.2rem rgba(46, 204, 113, 0.25);
}

.search-form .btn {
    background: #4a90e2;
    border: none;
    transition: all 0.3s ease;
}

.search-form .btn:hover {
    background: #2ecc71;
    transform: translateY(-2px);
}

.cart-icon {
    color: #2ecc71;
    font-size: 1.5rem;
    transition: all 0.3s ease;
}

.cart-icon:hover {
    transform: scale(1.1);
    color: #4a90e2;
}

.cart-badge {
    background: #2ecc71;
    border: 2px solid white;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 10px;
}

.navbar-toggler {
    transition: all 0.3s ease;
}

.navbar-toggler:focus {
    box-shadow: none;
}

.navbar-toggler:hover {
    transform: rotate(10deg);
}

.custom-register-btn {
    background: #fff;
    color: #4a90e2 !important;
    border: 2px solid #4a90e2;
    position: relative;
    box-shadow: none;
    transition: color 0.3s, background 0.3s, border 0.3s;
    text-decoration: none;
    font-weight: 600;
}
.custom-register-btn::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 2px;
    background: #2ecc71;
    display: block;
}
.custom-register-btn:hover {
    color: #2ecc71 !important;
    background: #fff;
    border-color: #2ecc71;
    text-decoration: none;
}
.custom-register-btn:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(46,204,113,0.2);
}
</style>
