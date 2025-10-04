<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

include('includes/config.php');

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handle cart actions
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'add' && isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        // Get product details
        $sql = "SELECT * FROM tblproduct WHERE ID = '$product_id'";
        $result = mysqli_query($con, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);

            // Add to cart or update quantity
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = array(
                    'id' => $product['ID'],
                    'name' => $product['ProductName'],
                    'price' => $product['UnitPrice'],
                    'image' => $product['ProductImage'],
                    'quantity' => $quantity
                );
            }
        }
    } elseif ($action == 'update' && isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        $quantity = (int)$_POST['quantity'];

        if ($quantity <= 0) {
            unset($_SESSION['cart'][$product_id]);
        } else {
            $_SESSION['cart'][$product_id]['quantity'] = $quantity;
        }
    } elseif ($action == 'remove' && isset($_POST['product_id'])) {
        $product_id = $_POST['product_id'];
        unset($_SESSION['cart'][$product_id]);
    } elseif ($action == 'clear') {
        $_SESSION['cart'] = array();
    }
}

// Calculate cart totals
$subtotal = 0;
$tax_rate = 0.05; // 5% tax
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$tax = $subtotal * $tax_rate;
$total = $subtotal + $tax;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Digital Dairy Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header/Navigation Bar -->
    <header class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
            <div class="container">
                <!-- Logo and Brand -->
                <a class="navbar-brand d-flex align-items-center" href="home_page.php">
                    <div class="logo-container me-3">
                        <img src="uploads/logo.png" alt="Digital Dairy Logo" style="height: 3rem;">
                    </div>
                    <div class="brand-text">
                        <h5 class="mb-0 fw-bold text-primary">DDMS</h5>
                        <small class="text-muted">Modern Dairy Solutions</small>
                    </div>
                </a>

                <!-- Mobile Menu Toggle -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navigation Menu -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="home_page.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="home_page.php#about">About Us</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="home_page.php#categories" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Products
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="milk_product.php">Milk Products</a></li>
                                <li><a class="dropdown-item" href="curd_product.php">Curd & Sambaram</a></li>
                                <li><a class="dropdown-item" href="icecream_product.php">Ice Cream</a></li>
                                <li><a class="dropdown-item" href="ghee_product.php">Ghee & Butter</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="home_page.php#contact">Contact</a>
                        </li>
                    </ul>

                    <!-- User Menu -->
                    <div class="d-flex align-items-center">
                        <a href="cart.php" class="btn btn-outline-primary me-3 position-relative">
                            <i class="fas fa-shopping-cart me-2"></i>Cart
                            <?php if (count($_SESSION['cart']) > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo count($_SESSION['cart']); ?>
                                </span>
                            <?php endif; ?>
                        </a>

                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($_SESSION['customer_name'] ?? $_SESSION['name'] ?? 'User'); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php">
                                    <i class="fas fa-user-circle me-2"></i>My Profile
                                </a></li>
                                <li><a class="dropdown-item" href="orders.php">
                                    <i class="fas fa-shopping-bag me-2"></i>My Orders
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Spacer for fixed header -->
    <div style="height: 80px;"></div>

    <!-- Checkout Error Message -->
    <?php if (isset($_SESSION['checkout_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" style="margin: 20px; border-radius: 10px;">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?php echo htmlspecialchars($_SESSION['checkout_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['checkout_error']); ?>
    <?php endif; ?>

    <!-- Cart Hero Section -->
    <section class="hero" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%); padding: 80px 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <h1 class="display-4 fw-bold mb-4" style="background: linear-gradient(135deg, #2c5aa0, #4caf50); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            Shopping Cart
                        </h1>
                        <p class="lead mb-4" style="color: #2c3e50; opacity: 0.9;">
                            Review your selected items and proceed to checkout
                        </p>
                        <div class="hero-buttons d-flex gap-3 flex-wrap">
                            <a href="home_page.php" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="cart-summary-card" style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                        <h5 class="text-center mb-3" style="color: #2c5aa0;">Cart Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Items (<?php echo count($_SESSION['cart']); ?>):</span>
                            <span>₹<?php echo number_format($subtotal, 2); ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (5%):</span>
                            <span>₹<?php echo number_format($tax, 2); ?></span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold" style="color: #2c5aa0; font-size: 1.2rem;">
                            <span>Total:</span>
                            <span>₹<?php echo number_format($total, 2); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Cart Items Section -->
    <section style="padding: 60px 0; background: white;">
        <div class="container">
            <?php if (empty($_SESSION['cart'])): ?>
                <!-- Empty Cart -->
                <div class="text-center" style="padding: 80px 20px;">
                    <div style="font-size: 4rem; color: #ddd; margin-bottom: 30px;">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h2 style="color: #2c5aa0; margin-bottom: 20px;">Your cart is empty</h2>
                    <p style="color: #6c757d; margin-bottom: 30px;">Add some delicious dairy products to get started!</p>
                    <a href="home_page.php" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #2c5aa0, #4caf50); border: none;">
                        <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                    </a>
                </div>
            <?php else: ?>
                <!-- Cart Items -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="cart-items">
                            <h3 style="color: #2c5aa0; margin-bottom: 30px;">Cart Items</h3>

                            <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                                <div class="cart-item" style="background: white; border-radius: 15px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 20px;">
                                    <!-- Product Image -->
                                    <div class="item-image" style="width: 100px; height: 100px; border-radius: 10px; overflow: hidden; flex-shrink: 0;">
                                        <?php if (!empty($item['image']) && file_exists($item['image'])): ?>
                                            <img src="<?php echo htmlspecialchars($item['image']); ?>"
                                                 alt="<?php echo htmlspecialchars($item['name']); ?>"
                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <div style="width: 100%; height: 100%; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #ddd;">
                                                <i class="fas fa-image fa-2x"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="item-details" style="flex-grow: 1;">
                                        <h5 style="color: #2c5aa0; margin-bottom: 5px;"><?php echo htmlspecialchars($item['name']); ?></h5>
                                        <p style="color: #6c757d; margin-bottom: 10px;">₹<?php echo number_format($item['price'], 2); ?> each</p>

                                        <!-- Quantity Controls -->
                                        <div class="quantity-controls d-flex align-items-center gap-3">
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                                <input type="hidden" name="quantity" value="<?php echo max(1, $item['quantity'] - 1); ?>">
                                                <button type="submit" class="btn btn-outline-secondary btn-sm" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </form>

                                            <span style="font-weight: 600; min-width: 30px; text-align: center;"><?php echo $item['quantity']; ?></span>

                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                                <input type="hidden" name="quantity" value="<?php echo $item['quantity'] + 1; ?>">
                                                <button type="submit" class="btn btn-outline-secondary btn-sm" style="width: 30px; height: 30px; padding: 0; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Item Total and Remove -->
                                    <div class="item-actions" style="text-align: right; flex-shrink: 0;">
                                        <div style="font-weight: 600; color: #2c5aa0; margin-bottom: 10px;">
                                            ₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                        </div>
                                        <form method="POST" style="display: inline;">
                                            <input type="hidden" name="action" value="remove">
                                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-trash me-1"></i>Remove
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <!-- Clear Cart Button -->
                            <div class="text-center mt-4">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="clear">
                                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to clear your cart?');">
                                        <i class="fas fa-trash me-2"></i>Clear Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Sidebar -->
                    <div class="col-lg-4">
                        <div class="order-summary" style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); position: sticky; top: 100px;">
                            <h4 style="color: #2c5aa0; margin-bottom: 20px;">Order Summary</h4>

                            <div class="summary-item d-flex justify-content-between mb-3">
                                <span>Subtotal (<?php echo count($_SESSION['cart']); ?> items):</span>
                                <span>₹<?php echo number_format($subtotal, 2); ?></span>
                            </div>

                            <div class="summary-item d-flex justify-content-between mb-3">
                                <span>Tax (5%):</span>
                                <span>₹<?php echo number_format($tax, 2); ?></span>
                            </div>

                            <hr>

                            <div class="summary-item d-flex justify-content-between mb-4" style="font-weight: 600; color: #2c5aa0; font-size: 1.2rem;">
                                <span>Total:</span>
                                <span>₹<?php echo number_format($total, 2); ?></span>
                            </div>

                            <form method="POST" action="process_checkout.php" id="checkoutForm">
                                <input type="hidden" name="action" value="checkout">
                                <button type="submit" class="btn btn-primary w-100 mb-3" style="background: linear-gradient(135deg, #2c5aa0, #4caf50); border: none; padding: 15px; font-weight: 600;">
                                    <i class="fas fa-credit-card me-2"></i>Proceed to Checkout
                                </button>
                            </form>

                            <div class="text-center">
                                <small style="color: #6c757d;">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Secure checkout with SSL encryption
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background: #2c3e50; color: white; padding: 40px 0;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h5>Digital Dairy Management System</h5>
                    <p>Modern solutions for traditional dairy farming. Making dairy management smarter and more efficient.</p>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <p>© 2025 Digital Dairy Management System | Developed by Joyal (MACFAST)</p>
                </div>
            </div>
        </div>
    </footer>

    <style>
        :root {
            --primary-color: #2c5aa0;
            --secondary-color: #4caf50;
            --accent-color: #ff6b35;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --light-text: #6c757d;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--dark-text);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(44, 90, 160, 0.3);
        }

        .btn-outline-primary:hover {
            background: linear-gradient(135deg, #2c5aa0, #4caf50);
            border-color: #2c5aa0;
            color: white;
        }

        .cart-item {
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        @media (max-width: 768px) {
            .display-4 {
                font-size: 2.5rem;
            }

            .cart-item {
                flex-direction: column;
                text-align: center;
            }

            .item-actions {
                text-align: center;
                margin-top: 15px;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-update cart totals when quantity changes
            function updateCartTotals() {
                // This would typically make an AJAX call to update totals
                // For now, we'll just reload the page to show updated totals
                location.reload();
            }

            // Add loading states to buttons
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    const button = this.querySelector('button[type="submit"]');
                    if (button) {
                        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                        button.disabled = true;
                    }
                });
            });

            // Show success message for cart actions
            <?php if (isset($_POST['action'])): ?>
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success position-fixed';
                alertDiv.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';

                <?php if ($_POST['action'] == 'add'): ?>
                    alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>Item added to cart successfully!';
                <?php elseif ($_POST['action'] == 'update'): ?>
                    alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>Cart updated successfully!';
                <?php elseif ($_POST['action'] == 'remove'): ?>
                    alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>Item removed from cart!';
                <?php elseif ($_POST['action'] == 'clear'): ?>
                    alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>Cart cleared successfully!';
                <?php endif; ?>

                document.body.appendChild(alertDiv);

                setTimeout(() => {
                    alertDiv.remove();
                }, 3000);
            <?php endif; ?>
        });
    </script>
</body>
</html>
