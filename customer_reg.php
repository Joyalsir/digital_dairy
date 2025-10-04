<?php
include("includes/config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = mysqli_real_escape_string($con, $_POST['customer_name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // Validate passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long!";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $error = "Password must contain at least one uppercase letter!";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $error = "Password must contain at least one lowercase letter!";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $error = "Password must contain at least one number!";
    } elseif (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
        $error = "Password must contain at least one special character!";
    } else {
        // Check if customer already exists
        $check = mysqli_query($con, "SELECT * FROM tblcustomer WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Customer with this email already exists!";
        } else {
            // Hash the password
            $hashed_password = md5($password);

            $insert = mysqli_query($con, "INSERT INTO tblcustomer (customer_name, email, address, password) VALUES ('$customer_name', '$email', '$address', '$hashed_password')");

            if ($insert) {
                $success = "Customer registered successfully! You can now login to your account.";
                // Clear form data
                $customer_name = $email = $address = $password = $confirm_password = "";
            } else {
                $error = "Error occurred while registering customer: " . mysqli_error($con);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration - Digital Dairy Management System</title>

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

    <!-- Registration Hero Section -->
    <section class="hero" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%); padding: 80px 0;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="text-center mb-5">
                        <h1 class="display-4 fw-bold mb-4" style="background: linear-gradient(135deg, #2c5aa0, #4caf50); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            Join Our Dairy Family
                        </h1>
                        <p class="lead mb-4" style="color: #2c3e50; opacity: 0.9;">
                            Create your account to access fresh, pure dairy products delivered straight from local farms to your doorstep.
                        </p>
                    </div>

                    <!-- Registration Card -->
                    <div class="registration-card" style="background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); overflow: hidden;">
                        <!-- Alert Messages -->
                        <?php if (isset($success)): ?>
                            <div class="alert alert-success m-4" role="alert" style="border-radius: 10px; border: none;">
                                <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger m-4" role="alert" style="border-radius: 10px; border: none;">
                                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Registration Form -->
                        <form method="post" id="registrationForm" class="p-4">
                            <div class="row">
                                <div class="col-12 mb-4">
                                    <label for="customer_name" class="form-label fw-bold">
                                        <i class="fas fa-user me-2"></i>Full Name
                                    </label>
                                    <input type="text" id="customer_name" name="customer_name" class="form-control"
                                           style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; font-size: 16px; transition: border-color 0.3s ease;"
                                           placeholder="Enter your full name"
                                           value="<?php echo isset($customer_name) ? $customer_name : ''; ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-4">
                                    <label for="email" class="form-label fw-bold">
                                        <i class="fas fa-envelope me-2"></i>Email Address
                                    </label>
                                    <input type="email" id="email" name="email" class="form-control"
                                           style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; font-size: 16px; transition: border-color 0.3s ease;"
                                           placeholder="Enter your email address"
                                           value="<?php echo isset($email) ? $email : ''; ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="password" class="form-label fw-bold">
                                        <i class="fas fa-lock me-2"></i>Password
                                    </label>
                                    <input type="password" id="password" name="password" class="form-control"
                                           style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; font-size: 16px; transition: border-color 0.3s ease;"
                                           placeholder="Enter your password"
                                           value="<?php echo isset($password) ? $password : ''; ?>" required>
                                    <small class="text-muted" style="font-size: 0.85rem;">
                                        Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.
                                    </small>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label for="confirm_password" class="form-label fw-bold">
                                        <i class="fas fa-lock me-2"></i>Confirm Password
                                    </label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                                           style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; font-size: 16px; transition: border-color 0.3s ease;"
                                           placeholder="Confirm your password"
                                           value="<?php echo isset($confirm_password) ? $confirm_password : ''; ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-4">
                                    <label for="address" class="form-label fw-bold">
                                        <i class="fas fa-map-marker-alt me-2"></i>Delivery Address
                                    </label>
                                    <textarea id="address" name="address" class="form-control"
                                              style="border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; font-size: 16px; transition: border-color 0.3s ease; resize: vertical; min-height: 100px;"
                                              placeholder="Enter your complete delivery address"><?php echo isset($address) ? $address : ''; ?></textarea>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" style="background: linear-gradient(135deg, #2c5aa0, #4caf50); border: none; padding: 15px 30px; font-size: 1.1rem; font-weight: 600; border-radius: 8px; transition: all 0.3s ease;">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
                            </div>

                            <div class="text-center mt-4">
                                <p class="mb-0" style="color: #6c757d;">
                                    Already have an account?
                                    <a href="index.php" style="color: #2c5aa0; text-decoration: none; font-weight: 600;">Sign In</a>
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
            <div class="row">
                <div class="col-lg-3 col-md-6 text-center mb-4">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #2c5aa0, #4caf50); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 2rem;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h5 style="color: #2c5aa0; font-weight: 600;">Quality Assured</h5>
                    <p style="color: #6c757d;">Fresh dairy products from verified local farms</p>
                </div>
                <div class="col-lg-3 col-md-6 text-center mb-4">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #2c5aa0, #4caf50); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 2rem;">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h5 style="color: #2c5aa0; font-weight: 600;">Fast Delivery</h5>
                    <p style="color: #6c757d;">Quick and reliable delivery to your doorstep</p>
                </div>
                <div class="col-lg-3 col-md-6 text-center mb-4">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #2c5aa0, #4caf50); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 2rem;">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h5 style="color: #2c5aa0; font-weight: 600;">Farmer Direct</h5>
                    <p style="color: #6c757d;">Supporting local farmers with fair pricing</p>
                </div>
                <div class="col-lg-3 col-md-6 text-center mb-4">
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #2c5aa0, #4caf50); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: white; font-size: 2rem;">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h5 style="color: #2c5aa0; font-weight: 600;">Easy Ordering</h5>
                    <p style="color: #6c757d;">Simple online ordering system</p>
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

        @media (max-width: 768px) {
            .display-4 {
                font-size: 2.5rem;
            }
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');

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

                // Validate email format
                const emailField = document.getElementById('email');
                if (emailField.value && !isValidEmail(emailField.value)) {
                    emailField.style.borderColor = '#dc3545';
                    isValid = false;
                }

                // Validate password match
                const passwordField = document.getElementById('password');
                const confirmPasswordField = document.getElementById('confirm_password');
                if (passwordField.value !== confirmPasswordField.value) {
                    passwordField.style.borderColor = '#dc3545';
                    confirmPasswordField.style.borderColor = '#dc3545';
                    isValid = false;
                }

                // Validate password length
                if (passwordField.value && passwordField.value.length < 8) {
                    passwordField.style.borderColor = '#dc3545';
                    isValid = false;
                }

                // Validate password strength
                if (passwordField.value && !/[A-Z]/.test(passwordField.value)) {
                    passwordField.style.borderColor = '#dc3545';
                    isValid = false;
                }
                if (passwordField.value && !/[a-z]/.test(passwordField.value)) {
                    passwordField.style.borderColor = '#dc3545';
                    isValid = false;
                }
                if (passwordField.value && !/[0-9]/.test(passwordField.value)) {
                    passwordField.style.borderColor = '#dc3545';
                    isValid = false;
                }
                if (passwordField.value && !/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/.test(passwordField.value)) {
                    passwordField.style.borderColor = '#dc3545';
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

            // Email validation helper
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }
        });
    </script>
</body>
</html>
