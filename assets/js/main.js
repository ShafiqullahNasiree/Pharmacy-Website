// Initialize variables
let loadingSpinner = document.querySelector('.spinner');

// Show loading spinner
function showSpinner() {
    loadingSpinner.classList.add('show');
}

// Hide loading spinner
function hideSpinner() {
    loadingSpinner.classList.remove('show');
}

// Show alert message
function showAlert(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.querySelector('.alerts-container').appendChild(alertDiv);
    
    // Remove alert after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Handle product search
$(document).ready(function() {
    // Product search
    $('#search-form').on('submit', function(e) {
        e.preventDefault();
        const searchTerm = $('#search-term').val().trim();
        
        if (searchTerm === '') {
            showAlert('Please enter a search term', 'warning');
            return;
        }

        showSpinner();
        
        $.ajax({
            url: 'api/products.php',
            method: 'GET',
            data: { search: searchTerm },
            success: function(response) {
                hideSpinner();
                displayProducts(response);
                if (response.length === 0) {
                    showAlert('No products found', 'info');
                }
            },
            error: function() {
                hideSpinner();
                showAlert('Error loading products', 'danger');
            }
        });
    });

    // Product card interactions
    $('.product-card').on('mouseenter', function() {
        $(this).find('.card-img-top').addClass('hover');
    }).on('mouseleave', function() {
        $(this).find('.card-img-top').removeClass('hover');
    });

    // Add to cart
    $(document).on('click', '.add-to-cart', function() {
        const productId = $(this).data('id');
        const productCard = $(this).closest('.product-card');
        
        // Add animation
        const productImg = productCard.find('.card-img-top').clone();
        productImg.css({
            position: 'fixed',
            zIndex: 9999,
            width: '50px',
            height: '50px',
            left: productCard.offset().left,
            top: productCard.offset().top
        });
        
        $('body').append(productImg);
        
        // Animate to cart
        productImg.animate({
            left: $(window).width() - 100,
            top: 50,
            width: 0,
            height: 0
        }, 1000, function() {
            $(this).remove();
        });

        // Add to cart request
        $.ajax({
            url: 'api/cart.php',
            method: 'POST',
            data: { action: 'add', product_id: productId },
            success: function(response) {
                showAlert('Product added to cart', 'success');
            },
            error: function() {
                showAlert('Error adding product to cart', 'danger');
            }
        });
    });

    // Display products
    function displayProducts(products) {
        const container = $('.product-grid');
        container.empty();

        if (products.length === 0) {
            container.html('<div class="alert alert-info">No products found</div>');
            return;
        }

        products.forEach(product => {
            const productCard = `
                <div class="col-md-4 mb-4">
                    <div class="card product-card">
                        <img src="${product.image}" class="card-img-top" alt="${product.name}">
                        <div class="card-body">
                            <h5 class="card-title">${product.name}</h5>
                            <p class="card-text">${product.description}</p>
                            <p class="card-text"><strong>Price: $${product.price.toFixed(2)}</strong></p>
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary add-to-cart" data-id="${product.id}">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.append(productCard);
        });
    }

    // Auto-complete search
    $('#search-term').on('input', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('.product-card').each(function() {
            const product = $(this);
            const productName = product.find('.card-title').text().toLowerCase();
            if (productName.includes(searchTerm)) {
                product.show();
            } else {
                product.hide();
            }
        });
    });

    // Sort products
    $('#sort-select').on('change', function() {
        const sortBy = $(this).val();
        $.ajax({
            url: 'api/products.php',
            method: 'GET',
            data: { sort: sortBy },
            success: function(response) {
                displayProducts(response);
            }
        });
    });
});
