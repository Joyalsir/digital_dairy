<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

include('includes/config.php');

// Get user information
$user_email = $_SESSION['email'];
$user_name = $_SESSION['customer_name'] ?? $_SESSION['name'] ?? 'User';

// Handle profile update
if (isset($_POST['update_profile'])) {
    $new_name = mysqli_real_escape_string($con, $_POST['customer_name']);
    $new_phone = mysqli_real_escape_string($con, $_POST['phone']);
    $new_address = mysqli_real_escape_string($con, $_POST['address']);

    // Update session variables
    $_SESSION['customer_name'] = $new_name;

    // Update database if needed (assuming there's a customers table)
    $update_query = "UPDATE tblcustomer SET customer_name = '$new_name', contact = '$new_phone', address = '$new_address' WHERE email = '$user_email'";

    if (mysqli_query($con, $update_query)) {
        $_SESSION['profile_success'] = "Profile updated successfully!";
    } else {
        $_SESSION['profile_error'] = "Failed to update profile. Please try again.";
    }

    header("Location: profile.php");
    exit;
}

// Get user details from database
$user_query = "SELECT * FROM tblcustomer WHERE email = '$user_email'";
$user_result = mysqli_query($con, $user_query);
$user_data = mysqli_fetch_assoc($user_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Digital Dairy Management System</title>

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
                            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo count($_SESSION['cart']); ?>
                                </span>
                            <?php endif; ?>
                        </a>

                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($user_name); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item active" href="profile.php">
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

    <!-- Profile Success/Error Messages -->
    <?php if (isset($_SESSION['profile_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" style="margin: 20px; border-radius: 10px;">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo htmlspecialchars($_SESSION['profile_success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['profile_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['profile_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" style="margin: 20px; border-radius: 10px;">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?php echo htmlspecialchars($_SESSION['profile_error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['profile_error']); ?>
    <?php endif; ?>

    <!-- Profile Hero Section -->
    <section class="hero" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%); padding: 60px 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="hero-content">
                        <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #2c5aa0, #4caf50); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <i class="fas fa-user-circle" style="font-size: 3rem; color: white;"></i>
                        </div>
                        <h1 class="display-5 fw-bold mb-3" style="background: linear-gradient(135deg, #2c5aa0, #4caf50); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            My Profile
                        </h1>
                        <p class="lead" style="color: #2c3e50; opacity: 0.9;">
                            Manage your account information and preferences
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Profile Content Section -->
    <section style="padding: 60px 0; background: white;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="profile-card" style="background: white; border-radius: 15px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">

                        <!-- Profile Information -->
                        <div class="profile-info mb-5">
                            <h3 style="color: #2c5aa0; margin-bottom: 30px; border-bottom: 2px solid #e9ecef; padding-bottom: 15px;">
                                <i class="fas fa-id-card me-2"></i>Profile Information
                            </h3>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="info-item" style="padding: 20px; background: #f8f9fa; border-radius: 10px;">
                                        <label style="color: #6c757d; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Full Name</label>
                                        <p style="color: #2c5aa0; font-weight: 600; margin: 5px 0;"><?php echo htmlspecialchars($user_name); ?></p>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="info-item" style="padding: 20px; background: #f8f9fa; border-radius: 10px;">
                                        <label style="color: #6c757d; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Email Address</label>
                                        <p style="color: #2c5aa0; font-weight: 600; margin: 5px 0;"><?php echo htmlspecialchars($user_email); ?></p>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="info-item" style="padding: 20px; background: #f8f9fa; border-radius: 10px;">
                                        <label style="color: #6c757d; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Phone</label>
                                        <p style="color: #2c5aa0; font-weight: 600; margin: 5px 0;"><?php echo htmlspecialchars($user_data['contact'] ?? 'Not provided'); ?></p>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="info-item" style="padding: 20px; background: #f8f9fa; border-radius: 10px;">
                                        <label style="color: #6c757d; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Member Since</label>
                                        <p style="color: #2c5aa0; font-weight: 600; margin: 5px 0;"><?php echo date('F Y', strtotime($user_data['registration_date'] ?? 'now')); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Profile Form -->
                        <div class="edit-profile">
                            <h3 style="color: #2c5aa0; margin-bottom: 30px; border-bottom: 2px solid #e9ecef; padding-bottom: 15px;">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </h3>

                            <form method="POST" action="profile.php">
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <label for="customer_name" class="form-label" style="color: #2c5aa0; font-weight: 600;">Full Name</label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name"
                                               value="<?php echo htmlspecialchars($user_name); ?>" required
                                               style="border-radius: 10px; padding: 12px 16px; border: 2px solid #e9ecef;">
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label for="phone" class="form-label" style="color: #2c5aa0; font-weight: 600;">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                               value="<?php echo htmlspecialchars($user_data['contact'] ?? ''); ?>"
                                               style="border-radius: 10px; padding: 12px 16px; border: 2px solid #e9ecef;">
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="address" class="form-label" style="color: #2c5aa0; font-weight: 600;">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"
                                              style="border-radius: 10px; padding: 12px 16px; border: 2px solid #e9ecef;"><?php echo htmlspecialchars($user_data['address'] ?? ''); ?></textarea>
                                </div>

                                <div class="d-flex gap-3">
                                    <button type="submit" name="update_profile" class="btn btn-primary"
                                            style="background: linear-gradient(135deg, #2c5aa0, #4caf50); border: none; padding: 12px 30px; border-radius: 10px; font-weight: 600;">
                                        <i class="fas fa-save me-2"></i>Update Profile
                                    </button>

                                    <a href="home_page.php" class="btn btn-outline-secondary"
                                       style="padding: 12px 30px; border-radius: 10px;">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Home
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Account Statistics Section -->
    <section style="padding: 60px 0; background: #f8f9fa;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="stats-card" style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                        <h3 style="color: #2c5aa0; margin-bottom: 30px; text-align: center;">
                            <i class="fas fa-chart-bar me-2"></i>Account Statistics
                        </h3>

                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <div style="padding: 20px; background: linear-gradient(135deg, #e3f2fd, #bbdefb); border-radius: 10px;">
                                    <i class="fas fa-shopping-bag" style="font-size: 2rem; color: #1976d2; margin-bottom: 10px;"></i>
                                    <h4 style="color: #1976d2; margin-bottom: 5px;">Orders</h4>
                                    <p style="font-size: 1.5rem; font-weight: bold; color: #2c5aa0;">
                                        <?php
                                        $order_count_query = "SELECT COUNT(*) as count FROM tblorders WHERE Email = '$user_email'";
                                        $order_count_result = mysqli_query($con, $order_count_query);
                                        $order_count = mysqli_fetch_assoc($order_count_result)['count'];
                                        echo $order_count;
                                        ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div style="padding: 20px; background: linear-gradient(135deg, #e8f5e8, #c8e6c9); border-radius: 10px;">
                                    <i class="fas fa-rupee-sign" style="font-size: 2rem; color: #388e3c; margin-bottom: 10px;"></i>
                                    <h4 style="color: #388e3c; margin-bottom: 5px;">Total Spent</h4>
                                    <p style="font-size: 1.5rem; font-weight: bold; color: #2c5aa0;">
                                        ₹<?php
                                        $total_spent_query = "SELECT SUM(TotalAmount) as total FROM tblorders WHERE Email = '$user_email'";
                                        $total_spent_result = mysqli_query($con, $total_spent_query);
                                        $total_spent = mysqli_fetch_assoc($total_spent_result)['total'] ?? 0;
                                        echo number_format($total_spent, 2);
                                        ?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div style="padding: 20px; background: linear-gradient(135deg, #fff3e0, #ffe0b2); border-radius: 10px;">
                                    <i class="fas fa-calendar" style="font-size: 2rem; color: #f57c00; margin-bottom: 10px;"></i>
                                    <h4 style="color: #f57c00; margin-bottom: 5px;">Member Since</h4>
                                    <p style="font-size: 1.2rem; font-weight: bold; color: #2c5aa0;">
                                        <?php echo date('M Y', strtotime($user_data['registration_date'] ?? 'now')); ?>
                                    </p>
                                </div>
                            </div>
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

        .form-control:focus {
            border-color: #2c5aa0;
            box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
        }

        .info-item:hover {
            background: #e9ecef !important;
            transition: all 0.3s ease;
        }

        @media (max-width: 768px) {
            .display-5 {
                font-size: 2.5rem;
            }

            .profile-card {
                padding: 20px;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading state to update button
            const updateButton = document.querySelector('button[name="update_profile"]');
            if (updateButton) {
                updateButton.addEventListener('click', function() {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
                    this.disabled = true;
                });
            }

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>
