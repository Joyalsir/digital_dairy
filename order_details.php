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

// Check if order ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = $_GET['id'];
$user_email = $_SESSION['email'];
$cart_count = count($_SESSION['cart']);

// Get order details
$order_query = "SELECT * FROM tblorders WHERE ID = '$order_id' AND Email = '$user_email'";
$order_result = mysqli_query($con, $order_query);

if (!$order_result || mysqli_num_rows($order_result) == 0) {
    header("Location: orders.php");
    exit;
}

$order = mysqli_fetch_assoc($order_result);

// Get order items
$order_items_query = "SELECT oi.*, p.ProductName, p.ProductImage, p.UnitPrice
                     FROM tblorderitems oi
                     JOIN tblproduct p ON oi.ProductID = p.ID
                     WHERE oi.OrderID = '$order_id'";
$order_items_result = mysqli_query($con, $order_items_query);

// Handle reorder action
if (isset($_POST['action']) && $_POST['action'] == 'reorder') {
    if ($order_items_result && mysqli_num_rows($order_items_result) > 0) {
        mysqli_data_seek($order_items_result, 0);
        while ($item = mysqli_fetch_assoc($order_items_result)) {
            $product_id = $item['ProductID'];
            $quantity = $item['Quantity'];

            // Add items to cart
            if (isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = array(
                    'id' => $item['ProductID'],
                    'name' => $item['ProductName'],
                    'price' => $item['UnitPrice'],
                    'image' => $item['ProductImage'],
                    'quantity' => $quantity
                );
            }
        }
        header("Location: cart.php");
        exit;
    }
}

// Calculate totals
$subtotal = 0;
$tax_rate = 0.05; // 5% tax
if ($order_items_result && mysqli_num_rows($order_items_result) > 0) {
    mysqli_data_seek($order_items_result, 0);
    while ($item = mysqli_fetch_assoc($order_items_result)) {
        $subtotal += $item['UnitPrice'] * $item['Quantity'];
    }
}
$tax = $subtotal * $tax_rate;
$total = $subtotal + $tax;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Digital Dairy Management System</title>

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
                            <?php if ($cart_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo $cart_count; ?>
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

    <!-- Order Details Hero Section -->
    <section class="hero" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%); padding: 80px 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <h1 class="display-4 fw-bold mb-4" style="background: linear-gradient(135deg, #2c5aa0, #4caf50); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            Order Details
                        </h1>
                        <p class="lead mb-4" style="color: #2c3e50; opacity: 0.9;">
                            Order #<?php echo $order['ID']; ?> - <?php echo date('M d, Y', strtotime($order['OrderDate'])); ?>
                        </p>
                        <div class="hero-buttons d-flex gap-3 flex-wrap">
                            <a href="orders.php" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Back to Orders
                            </a>
                            <a href="home_page.php" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="order-status-card" style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                        <h5 class="text-center mb-3" style="color: #2c5aa0;">Order Status</h5>
                        <div class="text-center">
                            <span class="badge fs-5 px-4 py-3 mb-3" style="background:
                                <?php
                                echo $order['Status'] == 'Delivered' ? '#4caf50' :
                                     ($order['Status'] == 'Pending' ? '#ff6b35' : '#2c5aa0');
                                ?>; color: white; display: block;">
                                <i class="fas fa-<?php
                                    echo $order['Status'] == 'Delivered' ? 'check-circle' :
                                         ($order['Status'] == 'Pending' ? 'clock' : 'truck');
                                ?> me-2"></i>
                                <?php echo $order['Status']; ?>
                            </span>
                            <p style="color: #6c757d; margin-bottom: 0; font-size: 0.9rem;">
                                <?php
                                if ($order['Status'] == 'Pending') {
                                    echo 'Your order is being processed';
                                } elseif ($order['Status'] == 'Delivered') {
                                    echo 'Order delivered successfully';
                                } else {
                                    echo 'Order is ' . strtolower($order['Status']);
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Order Details Section -->
    <section style="padding: 60px 0; background: white;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Order Items -->
                    <div class="order-items-card" style="background: white; border-radius: 15px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <h4 style="color: #2c5aa0; margin-bottom: 25px;">
                            <i class="fas fa-list me-2"></i>Order Items
                        </h4>

                        <?php if ($order_items_result && mysqli_num_rows($order_items_result) > 0): ?>
                            <?php
                            mysqli_data_seek($order_items_result, 0);
                            while ($item = mysqli_fetch_assoc($order_items_result)):
                                $item_total = $item['UnitPrice'] * $item['Quantity'];
                            ?>
                                <div class="order-item" style="display: flex; align-items: center; gap: 20px; padding: 20px; border-bottom: 1px solid #f0f0f0;">
                                    <!-- Product Image -->
                                    <div class="item-image" style="width: 80px; height: 80px; border-radius: 10px; overflow: hidden; flex-shrink: 0;">
                                        <?php if (!empty($item['ProductImage']) && file_exists($item['ProductImage'])): ?>
                                            <img src="<?php echo htmlspecialchars($item['ProductImage']); ?>"
                                                 alt="<?php echo htmlspecialchars($item['ProductName']); ?>"
                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                        <?php else: ?>
                                            <div style="width: 100%; height: 100%; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #ddd;">
                                                <i class="fas fa-image fa-2x"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Product Details -->
                                    <div style="flex-grow: 1;">
                                        <h6 style="color: #2c5aa0; margin-bottom: 5px;"><?php echo htmlspecialchars($item['ProductName']); ?></h6>
                                        <p style="color: #6c757d; margin-bottom: 5px;">₹<?php echo number_format($item['UnitPrice'], 2); ?> each</p>
                                        <small style="color: #999;">Quantity: <?php echo $item['Quantity']; ?></small>
                                    </div>

                                    <!-- Item Total -->
                                    <div style="text-align: right; flex-shrink: 0;">
                                        <div style="font-weight: 600; color: #2c5aa0; font-size: 1.1rem;">
                                            ₹<?php echo number_format($item_total, 2); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="col-lg-4">
                    <!-- Order Information -->
                    <div class="order-info-card" style="background: white; border-radius: 15px; padding: 30px; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <h5 style="color: #2c5aa0; margin-bottom: 20px;">
                            <i class="fas fa-info-circle me-2"></i>Order Information
                        </h5>

                        <div class="info-item mb-3">
                            <strong>Order ID:</strong><br>
                            <span style="color: #6c757d;">#<?php echo $order['ID']; ?></span>
                        </div>

                        <div class="info-item mb-3">
                            <strong>Order Date:</strong><br>
                            <span style="color: #6c757d;"><?php echo date('M d, Y h:i A', strtotime($order['OrderDate'])); ?></span>
                        </div>

                        <div class="info-item mb-3">
                            <strong>Customer Email:</strong><br>
                            <span style="color: #6c757d;"><?php echo htmlspecialchars($order['Email']); ?></span>
                        </div>

                        <?php if (!empty($order['CustomerName'])): ?>
                            <div class="info-item mb-3">
                                <strong>Customer Name:</strong><br>
                                <span style="color: #6c757d;"><?php echo htmlspecialchars($order['CustomerName']); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($order['Phone'])): ?>
                            <div class="info-item mb-3">
                                <strong>Phone:</strong><br>
                                <span style="color: #6c757d;"><?php echo htmlspecialchars($order['Phone']); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($order['Address'])): ?>
                            <div class="info-item mb-0">
                                <strong>Delivery Address:</strong><br>
                                <span style="color: #6c757d;"><?php echo htmlspecialchars($order['Address']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary-card" style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); position: sticky; top: 100px;">
                        <h5 style="color: #2c5aa0; margin-bottom: 20px;">
                            <i class="fas fa-calculator me-2"></i>Order Summary
                        </h5>

                        <div class="summary-item d-flex justify-content-between mb-3">
                            <span>Subtotal:</span>
                            <span>₹<?php echo number_format($subtotal, 2); ?></span>
                        </div>

                        <div class="summary-item d-flex justify-content-between mb-3">
                            <span>Tax (5%):</span>
                            <span>₹<?php echo number_format($tax, 2); ?></span>
                        </div>

                        <hr>

                        <div class="summary-item d-flex justify-content-between mb-4" style="font-weight: 600; color: #2c5aa0; font-size: 1.2rem;">
                            <span>Total Amount:</span>
                            <span>₹<?php echo number_format($total, 2); ?></span>
                        </div>

                        <?php if ($order['Status'] == 'Delivered'): ?>
                            <form method="POST" style="margin-bottom: 15px;">
                                <input type="hidden" name="action" value="reorder">
                                <button type="submit" class="btn btn-success w-100" style="background: linear-gradient(135deg, #4caf50, #2c5aa0); border: none; padding: 15px; font-weight: 600;">
                                    <i class="fas fa-redo me-2"></i>Reorder Same Items
                                </button>
                            </form>
                        <?php endif; ?>

                        <a href="orders.php" class="btn btn-outline-primary w-100">
                            <i class="fas fa-list me-2"></i>View All Orders
                        </a>
                    </div>
                </div>
            </div>
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

        .order-item:last-child {
            border-bottom: none;
        }

        .info-item {
            padding: 10px 0;
        }

        .info-item strong {
            color: #2c5aa0;
        }

        @media (max-width: 768px) {
            .display-4 {
                font-size: 2.5rem;
            }

            .order-item {
                flex-direction: column;
                text-align: center;
            }

            .order-item > div:last-child {
                text-align: center;
                margin-top: 15px;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            // Show success message for reorder action
            <?php if (isset($_POST['action']) && $_POST['action'] == 'reorder'): ?>
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-success position-fixed';
                alertDiv.style.cssText = 'top: 100px; right: 20px; z-index: 9999; min-width: 300px;';
                alertDiv.innerHTML = '<i class="fas fa-check-circle me-2"></i>Items added to cart successfully! <a href="cart.php" class="alert-link">View Cart</a>';

                document.body.appendChild(alertDiv);

                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            <?php endif; ?>
        });
    </script>
</body>
</html>
