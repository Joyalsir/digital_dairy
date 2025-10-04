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

// Get user information
$user_email = $_SESSION['email'];
$cart_count = count($_SESSION['cart']);

// Get user's orders from database
$orders_query = "SELECT * FROM tblorders WHERE Email = '$user_email' ORDER BY OrderDate DESC";
$orders_result = mysqli_query($con, $orders_query);

// Check if query was successful
if (!$orders_result) {
    $orders_result = false;
    $total_orders = 0;
    $pending_orders = 0;
    $delivered_orders = 0;
} else {
    $total_orders = mysqli_num_rows($orders_result);
    $pending_orders = 0;
    $delivered_orders = 0;

    if ($total_orders > 0) {
        mysqli_data_seek($orders_result, 0); // Reset result pointer
        while ($order = mysqli_fetch_assoc($orders_result)) {
            if ($order['Status'] == 'Pending') $pending_orders++;
            if ($order['Status'] == 'Delivered') $delivered_orders++;
        }
        mysqli_data_seek($orders_result, 0); // Reset for later use
    }
}

// Handle order actions
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action == 'view_details' && isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];
        // Redirect to order details page (we'll implement this later)
        header("Location: order_details.php?id=" . $order_id);
        exit;
    } elseif ($action == 'reorder' && isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];

        // Get order items
        $order_items_query = "SELECT * FROM tblorderitems WHERE OrderID = '$order_id'";
        $order_items_result = mysqli_query($con, $order_items_query);

        if ($order_items_result && mysqli_num_rows($order_items_result) > 0) {
            while ($item = mysqli_fetch_assoc($order_items_result)) {
                $product_id = $item['ProductID'];
                $quantity = $item['Quantity'];

                // Add items to cart
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
                } else {
                    // Get product details
                    $product_query = "SELECT * FROM tblproduct WHERE ID = '$product_id'";
                    $product_result = mysqli_query($con, $product_query);
                    if ($product_result && mysqli_num_rows($product_result) > 0) {
                        $product = mysqli_fetch_assoc($product_result);
                        $_SESSION['cart'][$product_id] = array(
                            'id' => $product['ID'],
                            'name' => $product['ProductName'],
                            'price' => $product['UnitPrice'],
                            'image' => $product['ProductImage'],
                            'quantity' => $quantity
                        );
                    }
                }
            }
            header("Location: cart.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Digital Dairy Management System</title>

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
                                <li><a class="dropdown-item active" href="orders.php">
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

    <!-- Orders Hero Section -->
    <section class="hero" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%); padding: 80px 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="hero-content">
                        <h1 class="display-4 fw-bold mb-4" style="background: linear-gradient(135deg, #2c5aa0, #4caf50); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            My Orders
                        </h1>
                        <p class="lead mb-4" style="color: #2c3e50; opacity: 0.9;">
                            Track your order history and manage your purchases
                        </p>
                        <div class="hero-buttons d-flex gap-3 flex-wrap">
                            <a href="home_page.php" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="orders-summary-card" style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                        <h5 class="text-center mb-3" style="color: #2c5aa0;">Order Summary</h5>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total Orders:</span>
                            <span style="font-weight: 600; color: #2c5aa0;"><?php echo $total_orders; ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Pending:</span>
                            <span style="font-weight: 600; color: #ff6b35;"><?php echo $pending_orders; ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-0">
                            <span>Delivered:</span>
                            <span style="font-weight: 600; color: #4caf50;"><?php echo $delivered_orders; ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Orders Section -->
    <section style="padding: 60px 0; background: white;">
        <div class="container">
            <?php if ($orders_result === false || mysqli_num_rows($orders_result) == 0): ?>
                <!-- No Orders -->
                <div class="text-center" style="padding: 80px 20px;">
                    <div style="font-size: 4rem; color: #ddd; margin-bottom: 30px;">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h2 style="color: #2c5aa0; margin-bottom: 20px;">No orders found</h2>
                    <p style="color: #6c757d; margin-bottom: 30px;">You haven't placed any orders yet. Start shopping to see your order history here!</p>
                    <a href="home_page.php" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #2c5aa0, #4caf50); border: none;">
                        <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                    </a>
                </div>
            <?php else: ?>
                <!-- Orders List -->
                <div class="orders-list">
                    <h3 style="color: #2c5aa0; margin-bottom: 30px;">Order History</h3>

                    <?php
                    mysqli_data_seek($orders_result, 0); // Reset result pointer
                    while ($order = mysqli_fetch_assoc($orders_result)):
                        // Get order items for this order
                        $order_items_query = "SELECT oi.*, p.ProductName, p.ProductImage
                                             FROM tblorderitems oi
                                             JOIN tblproduct p ON oi.ProductID = p.ID
                                             WHERE oi.OrderID = '" . $order['ID'] . "'";
                        $order_items_result = mysqli_query($con, $order_items_query);
                        $item_count = mysqli_num_rows($order_items_result);
                    ?>
                        <div class="order-card" style="background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-left: 5px solid
                            <?php
                            echo $order['Status'] == 'Delivered' ? '#4caf50' : '#ff6b35';
                            ?>;">
                            <div class="order-header d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 style="color: #2c5aa0; margin-bottom: 5px;">
                                        Order #<?php echo $order['ID']; ?>
                                    </h5>
                                    <p style="color: #6c757d; margin-bottom: 0;">
                                        <i class="fas fa-calendar me-2"></i>
                                        <?php echo date('M d, Y', strtotime($order['OrderDate'])); ?>
                                        <span style="margin: 0 10px;">•</span>
                                        <i class="fas fa-clock me-2"></i>
                                        <?php echo date('h:i A', strtotime($order['OrderDate'])); ?>
                                    </p>
                                </div>
                                <div class="order-status text-end">
                                    <span class="badge fs-6 px-3 py-2" style="background:
                                        <?php
                                        echo $order['Status'] == 'Delivered' ? '#4caf50' :
                                             ($order['Status'] == 'Pending' ? '#ff6b35' : '#2c5aa0');
                                        ?>; color: white;">
                                        <i class="fas fa-<?php
                                            echo $order['Status'] == 'Delivered' ? 'check-circle' :
                                                 ($order['Status'] == 'Pending' ? 'clock' : 'truck');
                                        ?> me-1"></i>
                                        <?php echo $order['Status']; ?>
                                    </span>
                                </div>
                            </div>

                            <div class="order-items" style="margin-bottom: 20px;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <?php
                                        if ($order_items_result && mysqli_num_rows($order_items_result) > 0) {
                                            $items_displayed = 0;
                                            mysqli_data_seek($order_items_result, 0);
                                            while ($item = mysqli_fetch_assoc($order_items_result)) {
                                                if ($items_displayed < 3) {
                                                    echo '<div style="width: 50px; height: 50px; border-radius: 8px; overflow: hidden; margin-right: 10px; border: 2px solid #f0f0f0;">';
                                                    if (!empty($item['ProductImage']) && file_exists($item['ProductImage'])) {
                                                        echo '<img src="' . htmlspecialchars($item['ProductImage']) . '"
                                                             alt="' . htmlspecialchars($item['ProductName']) . '"
                                                             style="width: 100%; height: 100%; object-fit: cover;">';
                                                    } else {
                                                        echo '<div style="width: 100%; height: 100%; background: #f8f9fa; display: flex; align-items: center; justify-content: center; color: #ddd;">
                                                                <i class="fas fa-image"></i>
                                                              </div>';
                                                    }
                                                    echo '</div>';
                                                    $items_displayed++;
                                                }
                                            }
                                            if ($item_count > 3) {
                                                echo '<div style="width: 50px; height: 50px; border-radius: 8px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #666; font-weight: 600; margin-right: 10px;">+' . ($item_count - 3) . '</div>';
                                            }
                                        }
                                        ?>
                                        <div>
                                            <p style="margin-bottom: 0; color: #2c5aa0; font-weight: 500;">
                                                <?php echo $item_count; ?> item<?php echo $item_count > 1 ? 's' : ''; ?>
                                            </p>
                                            <small style="color: #6c757d;">
                                                Total: ₹<?php echo number_format($order['TotalAmount'], 2); ?>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="order-actions">
                                        <form method="POST" style="display: inline; margin-right: 10px;">
                                            <input type="hidden" name="action" value="view_details">
                                            <input type="hidden" name="order_id" value="<?php echo $order['ID']; ?>">
                                            <button type="submit" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>View Details
                                            </button>
                                        </form>

                                        <?php if ($order['Status'] == 'Delivered'): ?>
                                            <form method="POST" style="display: inline;">
                                                <input type="hidden" name="action" value="reorder">
                                                <input type="hidden" name="order_id" value="<?php echo $order['ID']; ?>">
                                                <button type="submit" class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-redo me-1"></i>Reorder
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <?php if ($order['Status'] == 'Pending'): ?>
                                <div class="alert alert-warning" style="padding: 10px 15px; margin-bottom: 0; border-radius: 8px;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <small>Your order is being processed. You'll receive updates via email.</small>
                                </div>
                            <?php elseif ($order['Status'] == 'Delivered'): ?>
                                <div class="alert alert-success" style="padding: 10px 15px; margin-bottom: 0; border-radius: 8px;">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <small>Order delivered successfully. Thank you for shopping with us!</small>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
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

        .order-card {
            transition: all 0.3s ease;
        }

        .order-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .badge {
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            .display-4 {
                font-size: 2.5rem;
            }

            .order-header {
                flex-direction: column;
                gap: 15px;
            }

            .order-actions {
                width: 100%;
                text-align: center;
            }

            .order-actions form {
                display: inline-block;
                margin: 5px;
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
