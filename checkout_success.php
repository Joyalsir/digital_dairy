<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

// Check if this is a successful checkout
if (!isset($_SESSION['checkout_success'])) {
    header("Location: cart.php");
    exit;
}

include('includes/config.php');

// Get order details
$order_id = $_SESSION['order_id'];
$order_total = $_SESSION['order_total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful - Digital Dairy Management System</title>

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
                        <i class="fas fa-cow text-primary" style="font-size: 2rem;"></i>
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

    <!-- Success Section -->
    <section style="padding: 100px 0; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%); min-height: 70vh; display: flex; align-items: center;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="text-center" style="background: white; border-radius: 20px; padding: 60px 40px; box-shadow: 0 20px 60px rgba(0,0,0,0.1);">
                        <!-- Success Icon -->
                        <div style="font-size: 6rem; color: #4caf50; margin-bottom: 30px;">
                            <i class="fas fa-check-circle"></i>
                        </div>

                        <!-- Success Message -->
                        <h1 style="color: #2c5aa0; margin-bottom: 20px; font-weight: 700;">Order Placed Successfully!</h1>

                        <p style="color: #6c757d; font-size: 1.1rem; margin-bottom: 30px;">
                            Thank you for your order! Your order has been placed successfully and is being processed.
                        </p>

                        <!-- Order Details -->
                        <div class="order-details" style="background: #f8f9fa; border-radius: 15px; padding: 30px; margin-bottom: 30px;">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 style="color: #2c5aa0; margin-bottom: 15px;">Order Details</h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Order ID:</span>
                                        <strong style="color: #2c5aa0;"><?php echo htmlspecialchars($order_id); ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Total Amount:</span>
                                        <strong style="color: #4caf50;">₹<?php echo number_format($order_total, 2); ?></strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Status:</span>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 style="color: #2c5aa0; margin-bottom: 15px;">What's Next?</h5>
                                    <div style="text-align: left;">
                                        <p style="margin-bottom: 10px;"><i class="fas fa-check text-success me-2"></i>Order confirmation email sent</p>
                                        <p style="margin-bottom: 10px;"><i class="fas fa-clock text-warning me-2"></i>Order processing (1-2 hours)</p>
                                        <p style="margin-bottom: 10px;"><i class="fas fa-truck text-primary me-2"></i>Delivery within 24 hours</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons d-flex gap-3 justify-content-center flex-wrap">
                            <a href="orders.php" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #2c5aa0, #4caf50); border: none;">
                                <i class="fas fa-eye me-2"></i>View My Orders
                            </a>
                            <a href="home_page.php" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                            </a>
                        </div>

                        <!-- Additional Info -->
                        <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid #dee2e6;">
                            <p style="color: #6c757d; margin-bottom: 0;">
                                <small>
                                    <i class="fas fa-info-circle me-2"></i>
                                    You will receive order updates via email. For any questions, please contact our support team.
                                </small>
                            </p>
                        </div>
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

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .action-buttons .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto redirect to orders page after 10 seconds
            setTimeout(function() {
                if (confirm('Would you like to view your orders?')) {
                    window.location.href = 'orders.php';
                }
            }, 10000);

            // Clear session variables after showing success message
            <?php
            unset($_SESSION['checkout_success']);
            unset($_SESSION['order_id']);
            unset($_SESSION['order_total']);
            ?>
        });
    </script>
</body>
</html>
