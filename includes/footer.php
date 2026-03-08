<?php
require_once 'config.php';
?>

<!-- Footer -->
<footer class="footer bg-light mt-5">
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h5 class="footer-title">Follow Us</h5>
                <div class="d-flex gap-2 justify-content-center">
                    <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" class="text-primary">
                        <i class="fab fa-facebook-f fs-4"></i>
                    </a>
                    <a href="https://www.twitter.com/" target="_blank" rel="noopener noreferrer" class="text-primary">
                        <i class="fab fa-twitter fs-4"></i>
                    </a>
                    <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" class="text-primary">
                        <i class="fab fa-instagram fs-4"></i>
                    </a>
                    <a href="https://www.linkedin.com/" target="_blank" rel="noopener noreferrer" class="text-primary">
                        <i class="fab fa-linkedin fs-4"></i>
                    </a>
                </div>
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-12 text-center footer-bottom">
                <p class="text-muted mb-0">&copy; <?php echo date('Y'); ?> Nasiree Pharmacy. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Add CSS for footer hover effects -->
<style>
.footer {
    background: #f8f9fa;
    padding: 4rem 0 2rem;
    margin-top: 4rem;
}

.footer-title {
    font-weight: 700;
    color: #333;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.footer-title:hover {
    color: #2ecc71;
    transform: translateX(5px);
}

.footer-text {
    color: #333;
    opacity: 0.8;
    transition: opacity 0.3s ease;
}

.footer-text:hover {
    opacity: 1;
}

.footer i {
    color: #2ecc71;
    margin-right: 0.5rem;
    transition: all 0.3s ease;
}

.footer i:hover {
    transform: scale(1.2);
}

.footer-social {
    margin-top: 2rem;
}

.footer-social a {
    color: #4a90e2;
    font-size: 1.5rem;
    margin-right: 1rem;
    transition: all 0.3s ease;
}

.footer-social a:hover {
    color: #2ecc71;
    transform: translateY(-5px);
}

.footer-bottom {
    background: white;
    padding: 1rem 0;
    margin-top: 3rem;
    text-align: center;
}

.footer-bottom a {
    color: #4a90e2;
    text-decoration: none;
    transition: all 0.3s ease;
}

.footer-bottom a:hover {
    color: #2ecc71;
}
</style>
