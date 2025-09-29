<?php
session_start();
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_input = mysqli_real_escape_string($con, $_POST['login_input']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $user_type = $_POST['user_type'];
    $hashed_password = md5($password);

    if ($user_type == 'farmer') {
        // Check if input is email or username
        $field = filter_var($login_input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $sql = "SELECT * FROM farmers WHERE ($field = '$login_input') AND password = '$hashed_password' LIMIT 1";
        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['email'] = $user['email'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['farmer_uuid'] = $user['uuid'];
            $_SESSION['user_type'] = 'farmer';

            // Redirect to user dashboard or home page after login
            header("Location: user_dashboard.php");
            exit;
        }
    } elseif ($user_type == 'customer') {
        // Customer login only uses email
        $sql = "SELECT * FROM tblcustomer WHERE email = '$login_input' AND password = '$hashed_password' LIMIT 1";
        $result = mysqli_query($con, $sql);

        if ($result && mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['customer_id'] = $user['id'];
            $_SESSION['customer_name'] = $user['customer_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = 'customer';

            // Redirect to customer dashboard
            header("Location: home_page.php");
            exit;
        }
    }

    $login_error = "Invalid credentials or user type.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Digital Dairy Management System</title>

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

                    <!-- Login/Register Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" id="loginDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user me-2"></i>Login / Register
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="loginDropdown">
                            <li><a class="dropdown-item" href="login.php?role=admin">
                                <i class="fas fa-user-shield me-2"></i>Admin Login
                            </a></li>
                            <li><a class="dropdown-item" href="index.php?role=farmer">
                                <i class="fas fa-user me-2"></i>Farmer/Customer Login
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="customer_reg.php">
                                <i class="fas fa-user-plus me-2"></i>Register New Account
                            </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Spacer for fixed header -->
    <div style="height: 80px;"></div>

    <!-- Login Hero Section -->
    <section class="hero" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%); padding: 80px 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="text-center mb-5">
                        <h1 class="display-4 fw-bold mb-4" style="background: linear-gradient(135deg, #2c5aa0, #4caf50); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            Welcome Back
                        </h1>
                        <p class="lead mb-4" style="color: #2c3e50; opacity: 0.9;">
                            Sign in to access your Digital Dairy Management System account and manage your dairy operations efficiently.
                        </p>
                    </div>

                    <!-- Login Card -->
                    <div class="login-card" style="background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden;">
                        <!-- Alert Messages -->
                        <?php if (isset($login_error)): ?>
                            <div class="alert alert-danger m-4" role="alert" style="border-radius: 10px; border: none;">
                                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $login_error; ?>
                            </div>
                        <?php endif; ?>

                        <?php
                        if (isset($_SESSION['logout_message'])) {
                            echo '<div class="alert alert-success m-4" role="alert" style="border-radius: 10px; border: none;">
                                    <i class="fas fa-check-circle me-2"></i>' . $_SESSION['logout_message'] . '
                                  </div>';
                            unset($_SESSION['logout_message']);
                        }
                        ?>

                        <?php if (isset($_GET['reset']) && $_GET['reset'] == 'success'): ?>
                            <div class="alert alert-success m-4" role="alert" style="border-radius: 10px; border: none;">
                                <i class="fas fa-check-circle me-2"></i>Password reset successfully! You can now login with your new password.
                            </div>
                        <?php endif; ?>

                        <!-- Login Form -->
                        <form method="POST" id="loginForm" class="p-4">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <label for="user_type" class="form-label fw-bold">
                                        <i class="fas fa-users me-2"></i>User Type
                                    </label>
                                    <select name="user_type" id="user_type" class="form-control"
                                            style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; font-size: 16px; transition: border-color 0.3s ease;" required>
                                        <option value="">Select User Type</option>
                                        <option value="farmer">Farmer</option>
                                        <option value="customer">Customer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-4">
                                    <label for="login_input" class="form-label fw-bold">
                                        <i class="fas fa-envelope me-2"></i>Username or Email
                                    </label>
                                    <input type="text" id="login_input" name="login_input" class="form-control"
                                           style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; font-size: 16px; transition: border-color 0.3s ease;"
                                           placeholder="Enter your username or email address"
                                           value="<?php echo isset($_POST['login_input']) ? htmlspecialchars($_POST['login_input']) : ''; ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-4">
                                    <label for="password" class="form-label fw-bold">
                                        <i class="fas fa-lock me-2"></i>Password
                                    </label>
                                    <input type="password" id="password" name="password" class="form-control"
                                           style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; font-size: 16px; transition: border-color 0.3s ease;"
                                           placeholder="Enter your password" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                            <label class="form-check-label" for="remember" style="color: #6c757d;">
                                                Remember me
                                            </label>
                                        </div>
                                        <a href="forgot-password.php" style="color: #2c5aa0; text-decoration: none; font-weight: 600;">
                                            <i class="fas fa-key me-1"></i>Forgot Password?
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #2c5aa0, #4caf50); border: none; padding: 15px 30px; font-size: 1.1rem; font-weight: 600; border-radius: 8px; transition: all 0.3s ease;">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="mb-0" style="color: #6c757d;">
                                    Don't have an account?
                                    <a href="customer_reg.php" style="color: #2c5aa0; text-decoration: none; font-weight: 600;">Create New Account</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section style="padding: 60px 0; background: #f8f9fa;">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3" style="color: #2c5aa0;">Why Choose DDMS?</h2>
                <p class="lead" style="color: #6c757d;">Experience the power of modern dairy management</p>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-6 text-center mb-4">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #2c5aa0, #4caf50); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 2rem;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h5 style="color: #2c5aa0; font-weight: 600;">Real-time Analytics</h5>
                    <p style="color: #6c757d;">Track your dairy operations with comprehensive analytics and insights</p>
                </div>
                <div class="col-lg-4 col-md-6 text-center mb-4">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #2c5aa0, #4caf50); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 2rem;">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h5 style="color: #2c5aa0; font-weight: 600;">Mobile Access</h5>
                    <p style="color: #6c757d;">Manage your dairy from anywhere with our mobile-friendly platform</p>
                </div>
                <div class="col-lg-4 col-md-6 text-center mb-4">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #2c5aa0, #4caf50); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 2rem;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 style="color: #2c5aa0; font-weight: 600;">Secure & Reliable</h5>
                    <p style="color: #6c757d;">Your data is protected with enterprise-grade security measures</p>
                </div>
            </div>
        </div>
    </section>

    <!-- User Benefits Section -->
    <section style="padding: 60px 0; background: white;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h3 style="color: #2c5aa0; font-weight: 600; margin-bottom: 30px;">For Farmers</h3>
                    <div class="benefit-item mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                            <div>
                                <h6 style="color: #2c5aa0; font-weight: 600;">Milk Collection Tracking</h6>
                                <p style="color: #6c757d; margin-bottom: 0;">Monitor daily milk collection and quality metrics in real-time</p>
                            </div>
                        </div>
                    </div>
                    <div class="benefit-item mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                            <div>
                                <h6 style="color: #2c5aa0; font-weight: 600;">Payment Management</h6>
                                <p style="color: #6c757d; margin-bottom: 0;">Automated payment calculations and transparent transaction history</p>
                            </div>
                        </div>
                    </div>
                    <div class="benefit-item mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                            <div>
                                <h6 style="color: #2c5aa0; font-weight: 600;">Farm Analytics</h6>
                                <p style="color: #6c757d; margin-bottom: 0;">Detailed insights into farm performance and productivity trends</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <h3 style="color: #2c5aa0; font-weight: 600; margin-bottom: 30px;">For Customers</h3>
                    <div class="benefit-item mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                            <div>
                                <h6 style="color: #2c5aa0; font-weight: 600;">Easy Ordering</h6>
                                <p style="color: #6c757d; margin-bottom: 0;">Browse and order fresh dairy products with just a few clicks</p>
                            </div>
                        </div>
                    </div>
                    <div class="benefit-item mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                            <div>
                                <h6 style="color: #2c5aa0; font-weight: 600;">Delivery Tracking</h6>
                                <p style="color: #6c757d; margin-bottom: 0;">Track your orders in real-time from processing to delivery</p>
                            </div>
                        </div>
                    </div>
                    <div class="benefit-item mb-4">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                            <div>
                                <h6 style="color: #2c5aa0; font-weight: 600;">Quality Assurance</h6>
                                <p style="color: #6c757d; margin-bottom: 0;">Fresh, pure dairy products delivered straight from local farms</p>
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

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(44, 90, 160, 0.3);
        }

        .alert-success {
            background-color: rgba(76, 175, 80, 0.1);
            border: 1px solid rgba(76, 175, 80, 0.3);
            color: #2e7d32;
        }

        .alert-danger {
            background-color: rgba(244, 67, 54, 0.1);
            border: 1px solid rgba(244, 67, 54, 0.3);
            color: #c62828;
        }

        .benefit-item {
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .benefit-item:last-child {
            border-bottom: none;
        }

        @media (max-width: 768px) {
            .display-4 {
                font-size: 2.5rem;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');

            form.addEventListener('submit', function(e) {
                let isValid = true;

                // Validate required fields
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.style.borderColor = '#dc3545';
                        isValid = false;
                    } else {
                        field.style.borderColor = '#4a8a4b';
                    }
                });

                // Validate user type selection
                const userTypeField = document.getElementById('user_type');
                if (!userTypeField.value) {
                    userTypeField.style.borderColor = '#dc3545';
                    isValid = false;
                }

                if (!isValid) {
                    e.preventDefault();
                    // Show error message
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-danger';
                    alertDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>Please fill all required fields correctly!';
                    form.insertBefore(alertDiv, form.firstChild);

                    setTimeout(() => {
                        alertDiv.remove();
                    }, 5000);
                }
            });

            // Remove validation styling on input
            document.querySelectorAll('.form-control').forEach(field => {
                field.addEventListener('input', function() {
                    this.style.borderColor = '#4a8a4b';
                });
            });

            // Remove validation styling on select change
            document.getElementById('user_type').addEventListener('change', function() {
                this.style.borderColor = '#4a8a4b';
            });
        });
    </script>
</body>
</html>
