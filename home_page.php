<?php
session_start();
include('includes/config.php');

// Check if user is logged in
if (isset($_SESSION['email'])) {
    // User is logged in - show cart and user menu
    $isLoggedIn = true;
    $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
} else {
    // User is not logged in - show login/register menu
    $isLoggedIn = false;
    $cartCount = 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Digital Dairy Management System - Modern Dairy Solutions</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
    
    /* Hero Section */
    .hero {
      background: #f8f9fa;
      color: var(--dark-text);
      padding: 100px 0;
      position: relative;
      overflow: hidden;
    }
    
    .hero-content {
      position: relative;
    }
    
    .hero h1 {
      font-size: 3.2rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      animation: fadeInUp 1s ease-out;
      line-height: 1.2;
    }
    
    .hero p {
      font-size: 1.25rem;
      margin-bottom: 2rem;
      animation: fadeInUp 1.2s ease-out;
      line-height: 1.6;
    }
    
    /* Search Container */
    .search-container {
      animation: fadeInUp 1.3s ease-out;
    }
    
    .search-container .form-control {
      border: none;
      border-radius: 50px 0 0 50px;
      padding: 15px 25px;
      font-size: 1rem;
    }
    
    .search-container .btn {
      border-radius: 0 50px 50px 0;
      padding: 15px 25px;
    }
    
    .hero-buttons {
      animation: fadeInUp 1.4s ease-out;
    }

    /* Enhanced Statistics */
    .hero-stats {
      animation: fadeInUp 1.6s ease-out;
      margin-top: 4rem !important;
    }

    .stat-card {
      background: white;
      padding: 30px 20px;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      border: 1px solid rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }

    .stat-icon {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, var(--accent-color), #ff8c42);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
      color: white;
      font-size: 1.8rem;
    }

    .stat-number {
      font-size: 2.5rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
      color: var(--primary-color);
    }

    .stat-label {
      font-size: 1rem;
      color: var(--light-text);
      font-weight: 500;
    }

    @keyframes fadeInRight {
      from {
        opacity: 0;
        transform: translateX(50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
    
    /* Features Section */
    .features {
      padding: 100px 0;
      background: var(--light-bg);
    }
    
    .feature-card {
      background: white;
      padding: 40px 30px;
      border-radius: 15px;
      text-align: center;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      margin-bottom: 30px;
    }
    
    .feature-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .feature-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 25px;
      color: white;
      font-size: 2rem;
    }
    
    /* Statistics Section */
    .statistics {
      padding: 80px 0;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
    }
    
    .stat-item {
      text-align: center;
      padding: 30px;
    }
    
    .stat-number {
      font-size: 3rem;
      font-weight: 700;
      margin-bottom: 10px;
      display: block;
    }
    
    .stat-label {
      font-size: 1.1rem;
      opacity: 0.9;
    }
    
    /* Demo Section */
    .section-padding {
      padding: 100px 0;
    }
    
    .bg-surface {
      background: var(--light-bg);
    }
    
    .container-max {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    
    .text-text-primary {
      color: var(--dark-text);
    }
    
    .text-text-secondary {
      color: var(--light-text);
    }
    
    .input-field {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e2e8f0;
      border-radius: 8px;
      font-size: 16px;
      transition: border-color 0.3s ease;
    }
    
    .input-field:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
    }
    
    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(44, 90, 160, 0.3);
    }
    
    .bg-secondary-50 {
      background-color: rgba(76, 175, 80, 0.1);
    }
    
    .text-secondary {
      color: var(--secondary-color);
    }

    /* Testimonials */
    .testimonials {
      padding: 100px 0;
      background: white;
    }
    
    .testimonial-card {
      background: var(--light-bg);
      padding: 30px;
      border-radius: 15px;
      margin: 20px;
      position: relative;
    }
    
    .testimonial-text {
      font-style: italic;
      margin-bottom: 20px;
    }
    
    .testimonial-author {
      font-weight: 600;
      color: var(--primary-color);
    }
    
   

    /* Call to Action */
    .cta {
      padding: 100px 0;
      background: linear-gradient(135deg, var(--accent-color), #ff8c42);
      color: white;
      text-align: center;
    }
    
    /* Footer */
    footer {
      background: #2c3e50;
      color: white;
      padding: 60px 0 30px;
    }
    
    .footer-links a {
      color: #bdc3c7;
      text-decoration: none;
      transition: color 0.3s ease;
    }
    
    .footer-links a:hover {
      color: white;
    }
    
    /* Animations */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.5rem;
      }
      
      .hero p {
        font-size: 1.1rem;
      }
      
      .feature-card {
        margin-bottom: 20px;
      }
    }
    
    /* Custom buttons */
    .btn-custom {
      padding: 12px 30px;
      border-radius: 50px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1px;
      transition: all 0.3s ease;
    }
    
    .btn-primary-custom {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
    }
    
    .btn-primary-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(44, 90, 160, 0.3);
    }
    
    .btn-outline-light-custom {
      border: 2px solid white;
      color: white;
      background: transparent;
    }
    
    .btn-outline-light-custom:hover {
      background: white;
      color: var(--primary-color);
    }

    /* product*/ 
     .categories-section {
            padding: 60px 0;
            background: var(--light-bg);
        }

        .category-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border-radius: 20px;
            padding: 35px 25px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            opacity: 0;
            animation: fadeInRotate 0.8s ease-out forwards;
        }

        .category-card:nth-child(1) { animation-delay: 0s; }
        .category-card:nth-child(2) { animation-delay: 0.2s; }
        .category-card:nth-child(3) { animation-delay: 0.4s; }
        .category-card:nth-child(4) { animation-delay: 0.6s; }

        @keyframes fadeInRotate {
            0% {
                opacity: 0;
                transform: translateY(30px) rotate(-10deg) scale(0.9);
            }
            100% {
                opacity: 1;
                transform: translateY(0) rotate(0deg) scale(1);
            }
        }

        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(44,90,160,0.05), transparent);
            transition: left 0.5s;
        }

        .category-card:hover::before {
            left: 100%;
        }

        .category-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
            border-color: rgba(44,90,160,0.1);
        }

        .category-icon {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            position: relative;
            overflow: hidden;
            border: 4px solid rgba(255,255,255,0.3);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }

        .category-card:hover .category-icon {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }

        .category-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            transition: transform 0.3s ease;
        }

        .category-card:hover .category-image {
            transform: scale(1.05);
        }

        .category-icon::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(44,90,160,0.6) 0%, rgba(76,175,80,0.6) 100%);
            z-index: 1;
            transition: opacity 0.3s ease;
            opacity: 0.7;
        }

        .category-card:hover .category-icon::before {
            opacity: 0.8;
        }

        .category-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 12px;
            transition: color 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .category-card:hover .category-title {
            color: var(--secondary-color);
        }

        .category-description {
            color: #666;
            font-size: 1rem;
            line-height: 1.5;
            transition: color 0.3s ease;
            position: relative;
            z-index: 2;
        }

        .category-card:hover .category-description {
            color: #444;
        }

        .category-btn {
            margin-top: 15px;
            padding: 8px 20px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(10px);
        }

        .category-card:hover .category-btn {
            opacity: 1;
            transform: translateY(0);
        }

        .category-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(44,90,160,0.3);
            color: white;
            text-decoration: none;
        }
    

    /* Hero Banner Styles */
    .dairy-banner {
      background: var(--primary-color);
      border-radius: 20px;
      padding: 40px 30px;
      text-align: center;
      color: white;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      position: relative;
      overflow: hidden;
      min-height: 350px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .banner-image-container {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 1;
    }

    .banner-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 20px;
      opacity: 0.3;
    }

    .banner-content-overlay {
      position: relative;
      z-index: 2;
    }

    .dairy-banner::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="30" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="70" r="2.5" fill="rgba(255,255,255,0.1)"/><circle cx="70" cy="80" r="3" fill="rgba(255,255,255,0.1)"/></svg>');
      background-size: 150px 150px;
    }

    .banner-icon {
      font-size: 4rem;
      margin-bottom: 1rem;
      opacity: 0.9;
      position: relative;
      z-index: 2;
    }

    .dairy-banner h3 {
      font-size: 1.8rem;
      font-weight: 600;
      margin-bottom: 0.5rem;
      position: relative;
      z-index: 2;
    }

    .dairy-banner p {
      font-size: 1rem;
      opacity: 0.9;
      position: relative;
      z-index: 2;
    }

    /* Enhanced Hero Section */
    .hero {
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 50%, #dee2e6 100%);
      position: relative;
      overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 50%;
      height: 100%;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="2" fill="rgba(44,90,160,0.05)"/><circle cx="75" cy="75" r="1.5" fill="rgba(76,175,80,0.05)"/><circle cx="50" cy="10" r="1" fill="rgba(255,107,53,0.05)"/><circle cx="10" cy="60" r="1.5" fill="rgba(44,90,160,0.05)"/><circle cx="90" cy="40" r="2" fill="rgba(76,175,80,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
      background-size: 200px 200px;
      opacity: 0.3;
      z-index: 1;
    }

    .hero-content {
      position: relative;
      z-index: 2;
    }

    .hero h1 {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-size: 3.5rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      line-height: 1.2;
    }

    .hero p {
      font-size: 1.3rem;
      margin-bottom: 2rem;
      line-height: 1.6;
      color: var(--dark-text);
      opacity: 0.9;
    }

    .hero-buttons {
      margin-bottom: 3rem;
    }

    /* Responsive adjustments for hero */
    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.5rem;
      }

      .hero p {
        font-size: 1.1rem;
      }

      .dairy-banner {
        margin-top: 2rem;
        min-height: 280px;
      }

      .banner-icon {
        font-size: 3rem;
      }

      .dairy-banner h3 {
        font-size: 1.5rem;
      }
    }

    /* Contact Section Styles */
    .contact-form-container {
      background: white;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .contact-info {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      height: fit-content;
    }

    .contact-item {
      display: flex;
      align-items: flex-start;
      gap: 15px;
    }

    .contact-icon {
      flex-shrink: 0;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--light-bg);
    }

    .contact-details h6 {
      margin-bottom: 5px;
      font-weight: 600;
      color: var(--dark-text);
    }

    .contact-details p {
      margin-bottom: 0;
      color: var(--light-text);
      line-height: 1.5;
    }

    .contact-details a {
      color: var(--primary-color);
      transition: color 0.3s ease;
    }

    .contact-details a:hover {
      color: var(--secondary-color);
      text-decoration: none;
    }

    .social-links .btn {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
      transition: all 0.3s ease;
    }

    .social-links .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .map-container {
      background: white;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .map-embed iframe {
      border-radius: 15px;
    }

    /* Form Messages */
    .form-message {
      padding: 15px 20px;
      border-radius: 8px;
      margin-top: 15px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .form-message.success {
      background-color: rgba(76, 175, 80, 0.1);
      border: 1px solid rgba(76, 175, 80, 0.3);
      color: #2e7d32;
    }

    .form-message.error {
      background-color: rgba(244, 67, 54, 0.1);
      border: 1px solid rgba(244, 67, 54, 0.3);
      color: #c62828;
    }

    /* Contact form enhancements */
    .contact-form .form-label {
      font-weight: 600;
      color: var(--dark-text);
      margin-bottom: 8px;
    }

    .contact-form .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
    }

    .contact-form .form-select:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
    }

    .form-check-input:checked {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    /* Responsive adjustments for contact section */
    @media (max-width: 768px) {
      .contact-form-container,
      .contact-info,
      .map-container {
        padding: 20px;
        margin-bottom: 20px;
      }

      .contact-item {
        flex-direction: column;
        text-align: center;
      }

      .contact-icon {
        margin: 0 auto;
      }
    }

    /* Screenshot section text color */
    #screenshots .carousel-caption h5,
    #screenshots .carousel-caption p {
      color: black;
    }
  </style>
</head>
<body>

<!-- Header/Navigation Bar -->
<header class="header">
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
      <!-- Logo and Brand -->
      <a class="navbar-brand d-flex align-items-center" href="#home">
        <div class="logo-container me-3">
          <img src="uploads/logo.png" alt="Digital Dairy Logo" style="height: 3.5rem;">
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
            <a class="nav-link" href="#home">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#about">About Us</a>
          </li>
          <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#products" id="navbarDropdown" role="button" 
     data-bs-toggle="dropdown" aria-expanded="false">
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
            <a class="nav-link" href="#contact">Contact</a>
          </li>
        </ul>

        <!-- Cart and User Section -->
        <div class="d-flex align-items-center gap-3">
          <!-- Cart Icon -->
          <a href="cart.php" class="btn btn-outline-primary position-relative">
            <i class="fas fa-shopping-cart"></i>
            <?php if ($cartCount > 0): ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?php echo $cartCount; ?>
                <span class="visually-hidden">items in cart</span>
              </span>
            <?php endif; ?>
          </a>

          <!-- User Dropdown -->
          <?php if ($isLoggedIn): ?>
            <div class="dropdown">
              <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user me-2"></i>
                <span class="d-none d-md-inline"><?php echo htmlspecialchars($_SESSION['email']); ?></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><h6 class="dropdown-header">
                  <i class="fas fa-user me-2"></i><?php echo htmlspecialchars($_SESSION['email']); ?>
                </h6></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="profile.php">
                  <i class="fas fa-user-edit me-2"></i>Profile
                </a></li>
                <li><a class="dropdown-item" href="orders.php">
                  <i class="fas fa-box me-2"></i>My Orders
                </a></li>
                <li><a class="dropdown-item" href="cart.php">
                  <i class="fas fa-shopping-cart me-2"></i>Cart (<?php echo $cartCount; ?>)
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="logout_cus.php">
                  <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a></li>
              </ul>
            </div>
          <?php else: ?>
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
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>
</header>

<!-- Spacer for fixed header -->
<div style="height: 80px;"></div>

<!-- Hero/Banner Section -->
<section id="home" class="hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="hero-content">
          <h1 class="display-4 fw-bold mb-4">
            Welcome to <span class="text-primary">Digital Dairy Management System</span>
          </h1>
          <p class="lead mb-4">
             A complete solution for managing farmers, milk collection, products, deliveries, and customer orders — all in one platform.
          </p>

          <!-- Hero Buttons -->
          <div class="hero-buttons d-flex gap-3 flex-wrap">

               <a href="#categories" class="btn btn-primary btn-lg">Explore Products</a>

               <a href="#about" class="btn btn-primary btn-lg">Learn More</a>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="hero-image text-center">
          <div class="dairy-banner">
            <div class="banner-image-container">
              <img src="uploads/banner.jpg"
                   alt="Fresh Dairy Products"
                   class="banner-image">
            </div>
            <div class="banner-content-overlay">
             
              </div>
              <h3 class="text-white">Manage your dairy smartly</h3>
              <p class="text-white mb-0">Connecting Farmers and Customers Seamlessly</p>
              
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Product Categories Section -->
<section id="categories" class="categories-section">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-4 fw-bold mb-3">Our Product Categories</h2>
      <p class="lead text-muted">Discover our range of fresh dairy products</p>
    </div>

    <div class="row">
      <div class="col-lg-3 col-md-6">
        <div class="category-card">
          <div class="category-icon">
            <img src="uploads/products/product_68d11758f140b4.57759496.png" alt="Milk Products" class="category-image">
          </div>
          <h3 class="category-title">Milk Products</h3>
          <p class="category-description">Fresh milk, flavored milk, and milk-based beverages</p>
          <a href="milk_product.php" class="category-btn">Explore</a>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="category-card">
          <div class="category-icon">
            <img src="uploads/products/product_68d118eb1afba8.07186790.png" alt="Curd & Sambaram" class="category-image">
          </div>
          <h3 class="category-title">Curd & Sambaram</h3>
          <p class="category-description">Traditional curd and spicy buttermilk varieties</p>
          <a href="curd_product.php" class="category-btn">Explore</a>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="category-card">
          <div class="category-icon">
            <img src="uploads/products/product_68d11896e32298.84480869.png" alt="Ice Cream" class="category-image">
          </div>
          <h3 class="category-title">Ice Cream</h3>
          <p class="category-description">Delicious ice cream in various flavors and sizes</p>
          <a href="icecream_product.php" class="category-btn">Explore</a>
        </div>
      </div>

      <div class="col-lg-3 col-md-6">
        <div class="category-card">
          <div class="category-icon">
            <img src="uploads/products/product_68d1198391f951.31223157.png" alt="Ghee & Butter" class="category-image">
          </div>
          <h3 class="category-title">Ghee & Butter</h3>
          <p class="category-description">Pure ghee and fresh butter from farm to table</p>
          <a href="ghee_product.php" class="category-btn">Explore</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="section-padding bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-4 fw-bold mb-3">How It Works</h2>
      <p class="lead text-muted">Streamlined dairy management from farm to customer</p>
    </div>

    <div class="row">
      <!-- Step 1: Milk Collection -->
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="process-step text-center">
          <div class="step-number bg-primary text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
            1
          </div>
          <div class="step-icon mb-3">
            <i class="fas fa-cow text-primary" style="font-size: 3rem;"></i>
          </div>
          <h5 class="step-title mb-3">Milk Collection</h5>
          <p class="step-description text-muted">
            Farmers record daily milk collection with quality parameters like fat content, SNF, and temperature through the digital interface.
          </p>
        </div>
      </div>

      <!-- Step 2: Quality Testing -->
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="process-step text-center">
          <div class="step-number bg-success text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
            2
          </div>
          <div class="step-icon mb-3">
            <i class="fas fa-flask text-success" style="font-size: 3rem;"></i>
          </div>
          <h5 class="step-title mb-3">Quality Testing</h5>
          <p class="step-description text-muted">
            Automated quality analysis and grading system ensures only premium quality milk enters the processing pipeline.
          </p>
        </div>
      </div>

      <!-- Step 3: Processing & Inventory -->
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="process-step text-center">
          <div class="step-number bg-warning text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
            3
          </div>
          <div class="step-icon mb-3">
            <i class="fas fa-cogs text-warning" style="font-size: 3rem;"></i>
          </div>
          <h5 class="step-title mb-3">Processing</h5>
          <p class="step-description text-muted">
            Milk is processed into various dairy products with real-time inventory tracking and automated stock management.
          </p>
        </div>
      </div>

      <!-- Step 4: Distribution -->
      <div class="col-lg-3 col-md-6 mb-4">
        <div class="process-step text-center">
          <div class="step-number bg-info text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
            4
          </div>
          <div class="step-icon mb-3">
            <i class="fas fa-truck text-info" style="font-size: 3rem;"></i>
          </div>
          <h5 class="step-title mb-3">Distribution</h5>
          <p class="step-description text-muted">
            Seamless order processing, delivery tracking, and customer notifications ensure timely and reliable product delivery.
          </p>
        </div>
      </div>
    </div>

    <!-- Process Flow Visualization -->
    <div class="row mt-5">
      <div class="col-12">
        <div class="process-flow bg-white p-4 rounded-3 shadow-sm">
          <div class="text-center mb-4">
            <h4>Complete Digital Workflow</h4>
          </div>
          <div class="d-flex justify-content-center align-items-center flex-wrap">
            <div class="flow-item text-center mx-3 mb-3">
              <div class="flow-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                <i class="fas fa-user"></i>
              </div>
              <small class="text-muted">Farmer</small>
            </div>
            <div class="flow-arrow mx-2">
              <i class="fas fa-arrow-right text-primary"></i>
            </div>
            <div class="flow-item text-center mx-3 mb-3">
              <div class="flow-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                <i class="fas fa-database"></i>
              </div>
              <small class="text-muted">System</small>
            </div>
            <div class="flow-arrow mx-2">
              <i class="fas fa-arrow-right text-success"></i>
            </div>
            <div class="flow-item text-center mx-3 mb-3">
              <div class="flow-icon bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                <i class="fas fa-industry"></i>
              </div>
              <small class="text-muted">Processing</small>
            </div>
            <div class="flow-arrow mx-2">
              <i class="fas fa-arrow-right text-warning"></i>
            </div>
            <div class="flow-item text-center mx-3 mb-3">
              <div class="flow-icon bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 50px; height: 50px;">
                <i class="fas fa-shopping-cart"></i>
              </div>
              <small class="text-muted">Customer</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- About Us Section -->
<section id="about" class="section-padding bg-surface">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <div class="text-start">
          <h2 class="display-4 fw-bold mb-4 text-text-primary">About Us</h2>
          <p class="lead mb-4 text-text-secondary">
            The Digital Dairy Management System (DDMS) is a comprehensive web-based application designed to modernize and streamline dairy farm operations.
          </p>
          <p class="mb-4">
            Built using PHP, MySQL, and Bootstrap, this system provides a complete solution for managing farmers, milk collection, product inventory, sales tracking, delivery management, and customer orders. The platform features a responsive design that works seamlessly across desktop and mobile devices.
          </p>

          <div class="row g-4 mt-4">
            <div class="col-6">
              <div class="text-center">
                <div class="stat-box summary-stats">
                  <div class="stat-value text-secondary">PHP 8+</div>
                  <div class="stat-label">Backend</div>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="text-center">
                <div class="stat-box summary-stats">
                  <div class="stat-value text-secondary">MySQL</div>
                  <div class="stat-label">Database</div>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-4">
            <h5 class="mb-3">Key Features:</h5>
            <ul class="list-unstyled">
              <li class="mb-2"><i class="fas fa-check-circle text-secondary me-2"></i> User authentication and role-based access (Admin, Farmer, Customer)</li>
              <li class="mb-2"><i class="fas fa-check-circle text-secondary me-2"></i> Real-time milk collection and quality tracking</li>
              <li class="mb-2"><i class="fas fa-check-circle text-secondary me-2"></i> Automated payment calculations and farmer earnings</li>
              <li class="mb-2"><i class="fas fa-check-circle text-secondary me-2"></i> Product inventory management with image uploads</li>
              <li class="mb-2"><i class="fas fa-check-circle text-secondary me-2"></i> E-commerce functionality for customers</li>
              <li class="mb-2"><i class="fas fa-check-circle text-secondary me-2"></i> Delivery tracking and status updates</li>
              <li class="mb-2"><i class="fas fa-check-circle text-secondary me-2"></i> Comprehensive reporting and analytics</li>
              <li class="mb-2"><i class="fas fa-check-circle text-secondary me-2"></i> Mobile-responsive design with Bootstrap</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="comparison-card digital">
          <div class="card-body p-4">
            <h5 class="card-title mb-4">
              <i class="fas fa-code text-secondary me-2"></i>
              Technical Architecture
            </h5>

            <div class="point-item">
              <div class="d-flex align-items-start">
                <i class="fas fa-server text-secondary me-3 mt-1"></i>
                <div>
                  <strong>Backend:</strong> PHP with MySQL database for robust data management and secure transactions
                </div>
              </div>
            </div>

            <div class="point-item">
              <div class="d-flex align-items-start">
                <i class="fas fa-palette text-secondary me-3 mt-1"></i>
                <div>
                  <strong>Frontend:</strong> Bootstrap 5 framework for responsive and modern user interface
                </div>
              </div>
            </div>

            <div class="point-item">
              <div class="d-flex align-items-start">
                <i class="fas fa-shield-alt text-secondary me-3 mt-1"></i>
                <div>
                  <strong>Security:</strong> Session-based authentication with role-based access control
                </div>
              </div>
            </div>

            <div class="point-item">
              <div class="d-flex align-items-start">
                <i class="fas fa-mobile-alt text-secondary me-3 mt-1"></i>
                <div>
                  <strong>Mobile-First:</strong> Responsive design ensuring optimal experience on all devices
                </div>
              </div>
            </div>

            <div class="point-item">
              <div class="d-flex align-items-start">
                <i class="fas fa-chart-line text-secondary me-3 mt-1"></i>
                <div>
                  <strong>Analytics:</strong> Built-in reporting system for operational insights and decision making
                </div>
              </div>
            </div>

            <div class="point-item">
              <div class="d-flex align-items-start">
                <i class="fas fa-graduation-cap text-secondary me-3 mt-1"></i>
                <div>
                  <strong>Developed by:</strong> Joyal - Final Year BCA Student at MACFAST, Thiruvalla
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Technology Stack Section -->
<section id="technology" class="section-padding bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-4 fw-bold mb-3">Technology Stack</h2>
      <p class="lead text-muted">Built with modern web technologies for reliability and performance</p>
    </div>

    <div class="row">
      <!-- Backend Technologies -->
      <div class="col-lg-4 mb-4">
        <div class="tech-card bg-light p-4 rounded-3 h-100">
          <div class="text-center mb-4">
            <div class="tech-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
              <i class="fas fa-server"></i>
            </div>
            <h4 class="tech-title">Backend</h4>
          </div>
          <div class="tech-list">
            <div class="tech-item d-flex align-items-center mb-3">
              <i class="fab fa-php text-primary me-3" style="font-size: 1.5rem;"></i>
              <div>
                <strong>PHP 8+</strong>
                <br><small class="text-muted">Server-side scripting & business logic</small>
              </div>
            </div>
            <div class="tech-item d-flex align-items-center mb-3">
              <i class="fas fa-database text-success me-3" style="font-size: 1.5rem;"></i>
              <div>
                <strong>MySQL</strong>
                <br><small class="text-muted">Relational database management</small>
              </div>
            </div>
            <div class="tech-item d-flex align-items-center mb-3">
              <i class="fas fa-shield-alt text-warning me-3" style="font-size: 1.5rem;"></i>
              <div>
                <strong>Session Management</strong>
                <br><small class="text-muted">Secure user authentication</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Frontend Technologies -->
      <div class="col-lg-4 mb-4">
        <div class="tech-card bg-light p-4 rounded-3 h-100">
          <div class="text-center mb-4">
            <div class="tech-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
              <i class="fas fa-desktop"></i>
            </div>
            <h4 class="tech-title">Frontend</h4>
          </div>
          <div class="tech-list">
            <div class="tech-item d-flex align-items-center mb-3">
              <i class="fab fa-html5 text-danger me-3" style="font-size: 1.5rem;"></i>
              <div>
                <strong>HTML5</strong>
                <br><small class="text-muted">Semantic markup & structure</small>
              </div>
            </div>
            <div class="tech-item d-flex align-items-center mb-3">
              <i class="fab fa-css3 text-primary me-3" style="font-size: 1.5rem;"></i>
              <div>
                <strong>CSS3</strong>
                <br><small class="text-muted">Styling & responsive design</small>
              </div>
            </div>
            <div class="tech-item d-flex align-items-center mb-3">
              <i class="fab fa-bootstrap text-info me-3" style="font-size: 1.5rem;"></i>
              <div>
                <strong>Bootstrap 5</strong>
                <br><small class="text-muted">UI framework & components</small>
              </div>
            </div>
            <div class="tech-item d-flex align-items-center mb-3">
              <i class="fab fa-js-square text-warning me-3" style="font-size: 1.5rem;"></i>
              <div>
                <strong>JavaScript</strong>
                <br><small class="text-muted">Interactive functionality</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Additional Technologies -->
      <div class="col-lg-4 mb-4">
        <div class="tech-card bg-light p-4 rounded-3 h-100">
          <div class="text-center mb-4">
            <div class="tech-icon bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 2rem;">
              <i class="fas fa-tools"></i>
            </div>
            <h4 class="tech-title">Tools & Features</h4>
          </div>
          <div class="tech-list">
            <div class="tech-item d-flex align-items-center mb-3">
              <i class="fas fa-mobile-alt text-success me-3" style="font-size: 1.5rem;"></i>
              <div>
                <strong>Responsive Design</strong>
                <br><small class="text-muted">Mobile-first approach</small>
              </div>
            </div>
            <div class="tech-item d-flex align-items-center mb-3">
              <i class="fas fa-upload text-primary me-3" style="font-size: 1.5rem;"></i>
              <div>
                <strong>File Upload</strong>
                <br><small class="text-muted">Image management system</small>
              </div>
            </div>
            <div class="tech-item d-flex align-items-center mb-3">
              <i class="fas fa-chart-bar text-warning me-3" style="font-size: 1.5rem;"></i>
              <div>
                <strong>Data Analytics</strong>
                <br><small class="text-muted">Reporting & insights</small>
              </div>
            </div>
            <div class="tech-item d-flex align-items-center mb-3">
              <i class="fas fa-lock text-danger me-3" style="font-size: 1.5rem;"></i>
              <div>
                <strong>Security</strong>
                <br><small class="text-muted">Role-based access control</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Architecture Diagram -->
    <div class="row mt-5">
      <div class="col-12">
        <div class="architecture-diagram bg-light p-4 rounded-3">
          <h4 class="text-center mb-4">System Architecture</h4>
          <div class="text-center">
            <div class="arch-layer user-layer bg-white p-3 rounded mb-3 mx-auto" style="max-width: 600px;">
              <i class="fas fa-users text-primary me-2"></i>
              <strong>User Interface Layer</strong> - Web browsers, mobile devices
            </div>
            <div class="arch-arrow mb-3">
              <i class="fas fa-arrow-down text-muted"></i>
            </div>
            <div class="arch-layer app-layer bg-white p-3 rounded mb-3 mx-auto" style="max-width: 600px;">
              <i class="fas fa-code text-success me-2"></i>
              <strong>Application Layer</strong> - PHP, Business Logic, Session Management
            </div>
            <div class="arch-arrow mb-3">
              <i class="fas fa-arrow-down text-muted"></i>
            </div>
            <div class="arch-layer data-layer bg-white p-3 rounded mx-auto" style="max-width: 600px;">
              <i class="fas fa-database text-warning me-2"></i>
              <strong>Data Layer</strong> - MySQL Database, File Storage
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- System Screenshots Section -->
<section id="screenshots" class="section-padding bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-4 fw-bold mb-3">System Screenshots</h2>
      <p class="lead text-muted">Explore the key interfaces of our Digital Dairy Management System</p>
    </div>

    <div id="screenshotsCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#screenshotsCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#screenshotsCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#screenshotsCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#screenshotsCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
        <button type="button" data-bs-target="#screenshotsCarousel" data-bs-slide-to="4" aria-label="Slide 5"></button>
      </div>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="uploads/admin-dashboard.png" class="d-block w-100" alt="Admin Dashboard">
          <div class="carousel-caption d-none d-md-block">
            <h5>Admin Dashboard</h5>
            <p>Comprehensive overview of system analytics, farmer management, and sales reports.</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="uploads/farmer-reg.png" class="d-block w-100" alt="farmer registration">
          <div class="carousel-caption d-none d-md-block">
            <h5>Farmer Registration</h5>
            <p>Easy registration process for dairy farmers to join the digital platform.</p>
          </div>
        </div>
        <div class="carousel-item">
           <img src="uploads/farmer.png" class="d-block w-100" alt="farmer Dashboard">
          <div class="carousel-caption d-none d-md-block">
            <h5>Farmer Dashboard</h5>
            <p>Farmers can manage their profiles, view earnings, and track milk collections.</p>
          </div>
        </div>
        <div class="carousel-item">
           <img src="uploads/manage-milk.png" class="d-block w-100" alt="Milk Management">
          <div class="carousel-caption d-none d-md-block">
            <h5>Milk Collection Management</h5>
            <p>Real-time monitoring and management of milk collection from multiple farmers.</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="uploads/admin-dashboard.png" class="d-block w-100" alt="Reports Analytics">
          <div class="carousel-caption d-none d-md-block">
            <h5>Reports & Analytics</h5>
            <p>Detailed insights into milk collection, sales, and farmer earnings.</p>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#screenshotsCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#screenshotsCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

    <div class="text-center mt-4">
      <a href="index.php" class="btn btn-primary btn-lg">Try the System</a>
    </div>
  </div>
</section>

<!-- Developer Section -->
<section id="developer" class="section-padding bg-primary text-white">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-4 fw-bold mb-3">Meet the Developer</h2>
      <p class="lead">Passionate about technology and creating solutions that make a difference</p>
    </div>

    <div class="row align-items-center">
      <div class="col-lg-4 text-center mb-4">
        <div class="developer-avatar mx-auto mb-4">
          <div class="bg-white text-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 150px; height: 150px; font-size: 4rem;">
            <i class="fas fa-user-graduate"></i>
          </div>
        </div>
        <h4 class="mb-2">Joyal & Kebin Varghese</h4>
        <p class="mb-0 text-light">Final Year BCA Students</p>
        <p class="mb-0 text-light">MACFAST, Thiruvalla</p>
      </div>

      <div class="col-lg-8">
        <div class="developer-info">
          <h4 class="mb-4">About the Project</h4>
          <p class="mb-4">
            This Digital Dairy Management System was developed as a comprehensive final year project to demonstrate practical application of web development skills and solve real-world problems in the dairy industry.
          </p>

          <div class="row">
            <div class="col-md-6 mb-4">
              <h5 class="mb-3"><i class="fas fa-lightbulb text-warning me-2"></i>Project Vision</h5>
              <p class="mb-0">
                To modernize traditional dairy farming operations by providing a digital platform that connects farmers, dairy processors, and customers in an efficient ecosystem.
              </p>
            </div>
            <div class="col-md-6 mb-4">
              <h5 class="mb-3"><i class="fas fa-code text-info me-2"></i>Learning Outcomes</h5>
              <ul class="list-unstyled mb-0">
                <li><i class="fas fa-check text-success me-2"></i>Full-stack web development</li>
                <li><i class="fas fa-check text-success me-2"></i>Database design & management</li>
                <li><i class="fas fa-check text-success me-2"></i>User experience design</li>
                <li><i class="fas fa-check text-success me-2"></i>Security implementation</li>
              </ul>
            </div>
          </div>

          <div class="mt-4">
            <h5 class="mb-3"><i class="fas fa-graduation-cap text-warning me-2"></i>Academic Achievement</h5>
            <p class="mb-0">
              This project showcases the application of theoretical knowledge gained during BCA studies into a practical, industry-relevant solution. It demonstrates proficiency in modern web technologies and problem-solving abilities.
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Skills Showcase -->
    <div class="row mt-5">
      <div class="col-12">
        <h4 class="text-center mb-4">Technical Skills Demonstrated</h4>
        <div class="row text-center">
          <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="skill-item">
              <i class="fab fa-php fa-2x text-warning mb-2"></i>
              <br><small>PHP</small>
            </div>
          </div>
          <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="skill-item">
              <i class="fas fa-database fa-2x text-success mb-2"></i>
              <br><small>MySQL</small>
            </div>
          </div>
          <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="skill-item">
              <i class="fab fa-html5 fa-2x text-danger mb-2"></i>
              <br><small>HTML5</small>
            </div>
          </div>
          <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="skill-item">
              <i class="fab fa-css3 fa-2x text-primary mb-2"></i>
              <br><small>CSS3</small>
            </div>
          </div>
          <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="skill-item">
              <i class="fab fa-bootstrap fa-2x text-info mb-2"></i>
              <br><small>Bootstrap</small>
            </div>
          </div>
          <div class="col-lg-2 col-md-4 col-6 mb-3">
            <div class="skill-item">
              <i class="fab fa-js-square fa-2x text-warning mb-2"></i>
              <br><small>JavaScript</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>






  <!-- User Panel Benefits -->
   <section id="benefits" class="section-padding bg-surface">
    <div class="row mt-5">
      <div class="col-12">
        <div class="bg-light p-5 rounded-3">
          <h3 class="text-center mb-4">Why Choose Our System?</h3>
          <div class="row">
            <div class="col-lg-3 col-md-6 text-center mb-4">
              <div class="mb-3">
                <i class="fas fa-clock text-primary fa-3x"></i>
              </div>
              <h5>Save Time</h5>
              <p>Automate routine tasks and reduce manual work by up to 60%</p>
            </div>
            <div class="col-lg-3 col-md-6 text-center mb-4">
              <div class="mb-3">
                <i class="fas fa-chart-bar text-success fa-3x"></i>
              </div>
              <h5>Increase Efficiency</h5>
              <p>Real-time data and analytics help optimize your dairy operations</p>
            </div>
            <div class="col-lg-3 col-md-6 text-center mb-4">
              <div class="mb-3">
                <i class="fas fa-users text-warning fa-3x"></i>
              </div>
              <h5>Better Management</h5>
              <p>Streamlined processes for better farmer-customer relationships</p>
            </div>
            <div class="col-lg-3 col-md-6 text-center mb-4">
              <div class="mb-3">
                <i class="fas fa-glass-whiskey text-info fa-3x"></i>
              </div>
              <h5>Fresh & Pure Milk</h5>
              <p>Direct from farm to table with guaranteed freshness and purity</p>
            </div>
            <div class="col-lg-3 col-md-6 text-center mb-4">
              <div class="mb-3">
                <i class="fas fa-shield-alt text-secondary fa-3x"></i>
              </div>
              <h5>Hygienic Processing</h5>
              <p>State-of-the-art processing facilities ensuring highest hygiene standards</p>
            </div>
            <div class="col-lg-3 col-md-6 text-center mb-4">
              <div class="mb-3">
                <i class="fas fa-handshake text-success fa-3x"></i>
              </div>
              <h5>Supporting Local Farmers</h5>
              <p>Empowering local dairy farmers with fair prices and sustainable practices</p>
            </div>
            <div class="col-lg-3 col-md-6 text-center mb-4">
              <div class="mb-3">
                <i class="fas fa-truck text-primary fa-3x"></i>
              </div>
              <h5>Affordable & Reliable Delivery</h5>
              <p>Fast, reliable delivery service at competitive prices across all locations</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  
<!-- Testimonials Section -->
<section class="testimonials">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-4 fw-bold mb-3">What Our Users Say</h2>
      <p class="lead text-muted">Real stories from dairy farmers and managers</p>
    </div>
    
    <div class="row">
      <div class="col-lg-4">
        <div class="testimonial-card">
          <div class="testimonial-text">
            "This system has transformed how we manage our dairy. The automated payments and real-time tracking have saved us countless hours."
          </div>
          <div class="testimonial-author">- Rajesh Kumar, Dairy Farmer</div>
        </div>
      </div>
      
      <div class="col-lg-4">
        <div class="testimonial-card">
          <div class="testimonial-text">
            "The analytics dashboard gives us incredible insights into our operations. We've increased efficiency by 40% since implementation."
          </div>
          <div class="testimonial-author">- Priya Sharma, Farm Manager</div>
        </div>
      </div>
      
      <div class="col-lg-4">
        <div class="testimonial-card">
          <div class="testimonial-text">
            "Mobile access means I can manage my dairy from anywhere. The user-friendly interface makes it easy for everyone to use."
          </div>
          <div class="testimonial-author">- Amit Patel, Dairy Owner</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section id="faq" class="section-padding bg-surface">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-4 fw-bold mb-3">Frequently Asked Questions</h2>
      <p class="lead text-muted">Find answers to common questions about our dairy products and services</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="accordion" id="faqAccordion">

          <!-- FAQ Item 1 -->
          <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                <i class="fas fa-question-circle text-primary me-3"></i>
                Is milk pasteurized?
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                <p>Yes, all our milk products undergo pasteurization to ensure safety and quality. We follow strict food safety standards and pasteurize our milk at controlled temperatures to eliminate harmful bacteria while preserving the natural nutrients and taste of fresh milk.</p>
              </div>
            </div>
          </div>

          <!-- FAQ Item 2 -->
          <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <i class="fas fa-shield-alt text-primary me-3"></i>
                How is quality maintained?
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                <p>Quality is maintained through our comprehensive Digital Dairy Management System that tracks every step from farm to customer. We conduct regular quality checks, maintain cold chain logistics, and work directly with verified farmers who follow best practices in dairy farming.</p>
              </div>
            </div>
          </div>

          <!-- FAQ Item 3 -->
          <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <i class="fas fa-truck text-primary me-3"></i>
                Do you deliver at shop?
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                <p>Yes, we offer both home delivery and shop pickup options. Our delivery service covers most areas, and we also have partner retail shops where you can purchase our products directly. You can choose your preferred delivery method during checkout.</p>
              </div>
            </div>
          </div>

          <!-- FAQ Item 4 -->
          <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="headingFour">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                <i class="fas fa-clock text-primary me-3"></i>
                What are your delivery timings?
              </button>
            </h2>
            <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                <p>We offer flexible delivery slots throughout the day. Morning deliveries (6 AM - 10 AM) and evening deliveries (4 PM - 8 PM) are available. We also provide same-day delivery for orders placed before 2 PM. Delivery schedules may vary by location.</p>
              </div>
            </div>
          </div>

          <!-- FAQ Item 5 -->
          <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="headingFive">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                <i class="fas fa-leaf text-primary me-3"></i>
                Are your products organic?
              </button>
            </h2>
            <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                <p>We offer both organic and conventional dairy products. Our organic range comes from farms that follow strict organic farming practices without the use of synthetic pesticides or hormones. All our products, whether organic or conventional, meet the highest quality standards.</p>
              </div>
            </div>
          </div>

          <!-- FAQ Item 6 -->
          <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="headingSix">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                <i class="fas fa-credit-card text-primary me-3"></i>
                What payment methods do you accept?
              </button>
            </h2>
            <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                <p>We accept various payment methods including cash on delivery, online payments (credit/debit cards, net banking, UPI), and digital wallets. For regular customers, we also offer monthly billing options with flexible payment terms.</p>
              </div>
            </div>
          </div>

          <!-- FAQ Item 7 -->
          <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="headingSeven">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                <i class="fas fa-undo text-primary me-3"></i>
                What is your return policy?
              </button>
            </h2>
            <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                <p>We have a customer-friendly return policy for dairy products. If you're not satisfied with the quality or if there's any issue with the product, you can return it within 24 hours of delivery. We ensure quick refunds or replacements to maintain your trust in our products.</p>
              </div>
            </div>
          </div>

          <!-- FAQ Item 8 -->
          <div class="accordion-item mb-3">
            <h2 class="accordion-header" id="headingEight">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                <i class="fas fa-users text-primary me-3"></i>
                How can I become a supplier/farmer partner?
              </button>
            </h2>
            <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
              <div class="accordion-body">
                <p>We're always looking to partner with quality-conscious dairy farmers. You can register through our farmer portal or contact our partnership team directly. We provide training, quality support, and fair pricing to ensure mutually beneficial partnerships.</p>
              </div>
            </div>
          </div>

        </div>

        <!-- Contact Support -->
        <div class="text-center mt-5">
          <div class="bg-light p-4 rounded-3">
            <h5 class="mb-3">Still have questions?</h5>
            <p class="text-muted mb-3">Our customer support team is here to help you with any queries.</p>
            <a href="#contact" class="btn btn-primary">Contact Support</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Contact Section -->
<section id="contact" class="section-padding bg-surface">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-4 fw-bold mb-3">Get In Touch</h2>
      <p class="lead text-muted">Have questions about our dairy products or services? We'd love to hear from you!</p>
    </div>

    <div class="row">
      <!-- Contact Form -->
      <div class="col-lg-8 mb-5">
        <div class="contact-form-container">
          <h4 class="mb-4">Send us a Message</h4>
          <form id="contactForm" class="contact-form">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="contactName" class="form-label">
                  <i class="fas fa-user me-2"></i>Full Name
                </label>
                <input type="text" class="form-control input-field" id="contactName" name="name" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="contactEmail" class="form-label">
                  <i class="fas fa-envelope me-2"></i>Email Address
                </label>
                <input type="email" class="form-control input-field" id="contactEmail" name="email" required>
              </div>
            </div>

            <div class="mb-3">
              <label for="contactSubject" class="form-label">
                <i class="fas fa-tag me-2"></i>Subject
              </label>
              <select class="form-control input-field" id="contactSubject" name="subject" required>
                <option value="">Choose a subject...</option>
                <option value="general">General Inquiry</option>
                <option value="products">Product Information</option>
                <option value="delivery">Delivery Questions</option>
                <option value="partnership">Partnership Opportunities</option>
                <option value="complaint">Complaint/Feedback</option>
                <option value="support">Technical Support</option>
                <option value="other">Other</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="contactMessage" class="form-label">
                <i class="fas fa-comment me-2"></i>Message
              </label>
              <textarea class="form-control input-field" id="contactMessage" name="message" rows="5" placeholder="Tell us how we can help you..." required></textarea>
            </div>

            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="newsletterCheck" name="newsletter">
                <label class="form-check-label" for="newsletterCheck">
                  Subscribe to our newsletter for updates on new products and offers
                </label>
              </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg">
              <i class="fas fa-paper-plane me-2"></i>Send Message
            </button>
          </form>

          <!-- Success/Error Messages -->
          <div id="formMessages" class="mt-3" style="display: none;"></div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="col-lg-4">
        <div class="contact-info">
          <h4 class="mb-4">Contact Information</h4>

          <!-- Address -->
          <div class="contact-item mb-4">
            <div class="contact-icon">
              <i class="fas fa-map-marker-alt fa-2x text-primary"></i>
            </div>
            <div class="contact-details">
              <h6>Our Location</h6>
              <p class="mb-0">MACFAST Campus<br>Thiruvalla, Kerala<br>India - 689101</p>
            </div>
          </div>

          <!-- Phone -->
          <div class="contact-item mb-4">
            <div class="contact-icon">
              <i class="fas fa-phone fa-2x text-success"></i>
            </div>
            <div class="contact-details">
              <h6>Phone Numbers</h6>
              <p class="mb-0">
                <a href="tel:+91xxxxxxxxxx" class="text-decoration-none">+91 xxxxx xxxxx</a><br>
                <a href="tel:+91xxxxxxxxxx" class="text-decoration-none">+91 xxxxx xxxxx</a>
              </p>
            </div>
          </div>

          <!-- Email -->
          <div class="contact-item mb-4">
            <div class="contact-icon">
              <i class="fas fa-envelope fa-2x text-warning"></i>
            </div>
            <div class="contact-details">
              <h6>Email Addresses</h6>
              <p class="mb-0">
                <a href="mailto:info@digitaldairy.com" class="text-decoration-none">info@digitaldairy.com</a><br>
                <a href="mailto:support@digitaldairy.com" class="text-decoration-none">support@digitaldairy.com</a>
              </p>
            </div>
          </div>

          <!-- Business Hours -->
          <div class="contact-item mb-4">
            <div class="contact-icon">
              <i class="fas fa-clock fa-2x text-info"></i>
            </div>
            <div class="contact-details">
              <h6>Business Hours</h6>
              <p class="mb-0">
                <strong>Monday - Friday:</strong> 8:00 AM - 8:00 PM<br>
                <strong>Saturday:</strong> 8:00 AM - 6:00 PM<br>
                <strong>Sunday:</strong> 9:00 AM - 4:00 PM
              </p>
            </div>
          </div>

          
        </div>
      </div>
    </div>

    <!-- Map Section -->
    <div class="row mt-5">
      <div class="col-12">
        <div class="map-container">
          <h4 class="text-center mb-4">Find Us on the Map</h4>
          <div class="map-embed">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3924.6381!2d76.5735!3d9.3850!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b0623b3b3b3b3b3%3A0x3b0623b3b3b3b3b3!2sMACFAST!5e0!3m2!1sen!2sin!4v1623456789012!5m2!1sen!2sin"
              width="100%"
              height="400"
              style="border:0; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);"
              allowfullscreen=""
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade">
            </iframe>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="cta">
  <div class="container">
    <h2 class="display-4 fw-bold mb-4">Ready to Transform Your Dairy Business?</h2>
    <p class="lead mb-4">Join hundreds of successful dairy farms using our management system</p>
    <div class="d-flex justify-content-center gap-3 flex-wrap">
      <a href="index.php?role=user" class="btn btn-light btn-custom">Farmer Login</a>
      <a href="login.php?role=admin" class="btn btn-outline-light btn-custom">Admin Login</a>
      <a href="customer_reg.php" class="btn btn-light btn-custom">Register Now</a>
    </div>
  </div>
</section>

<!-- Footer -->
<footer>
  <div class="container">
    <div class="row">
      <div class="col-lg-4 mb-4">
        <h5>Digital Dairy Management System</h5>
        <p>Modern solutions for traditional dairy farming. Making dairy management smarter and more efficient.</p>
      </div>
      <div class="col-lg-2 col-6 mb-4">
        <h6>Quick Links</h6>
        <div class="footer-links d-flex flex-column">
          <a href="#benefits">Features</a>
          <a href="index.php">Login</a>
          <a href="customer_reg.php">Register</a>
        </div>
      </div>
      <div class="col-lg-2 col-6 mb-4">
        <h6>Support</h6>
        <div class="footer-links d-flex flex-column">
          
        <a href="#contact">Contact Us</a>
          <a href="#faq">FAQs</a>
          
        </div>
      </div>
      <div class="col-lg-4 mb-4">
        <h6>Contact Info</h6>
        <p><i class="fas fa-envelope me-2"></i> info@digitaldairy.com</p>
        <p><i class="fas fa-phone me-2"></i> +91 xxxxxxxxxx</p>
        <p><i class="fas fa-map-marker-alt me-2"></i> MACFAST, Thiruvalla</p>
      </div>
    </div>
    <hr>
    <div class="text-center">
      <p>© 2025 Digital Dairy Management System | Developed by Joyal (MACFAST)</p>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Counter animation for statistics
  document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.stat-number');
    const speed = 200;
    
    counters.forEach(counter => {
      const target = parseInt(counter.getAttribute('data-count'));
      let count = 0;
      
      const updateCount = () => {
        const increment = target / speed;
        
        if (count < target) {
          count += increment;
          counter.innerText = Math.ceil(count);
          setTimeout(updateCount, 1);
        } else {
          counter.innerText = target;
        }
      };
      
      updateCount();
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
          behavior: 'smooth'
        });
      });
    });
  });

  // Milk earnings calculator function
  function calculateEarnings() {
    const morningMilk = parseFloat(document.getElementById('morning-milk').value) || 0;
    const eveningMilk = parseFloat(document.getElementById('evening-milk').value) || 0;
    const fatContent = parseFloat(document.getElementById('fat-content').value) || 0;
    
    const totalMilk = morningMilk + eveningMilk;
    
    // Calculate earnings based on milk quantity and fat content
    // Base rate: ₹40 per liter, plus premium for higher fat content
    let ratePerLiter = 40;
    if (fatContent > 4.0) {
      ratePerLiter += (fatContent - 4.0) * 5; // ₹5 extra per 0.1% above 4.0%
    }
    
    const totalEarning = totalMilk * ratePerLiter;
    
    // Update the results display
    document.getElementById('total-milk').textContent = `${totalMilk}L`;
    document.getElementById('total-earning').textContent = `₹${totalEarning.toLocaleString()}`;
    
    // Show the results
    document.getElementById('calculation-results').style.display = 'block';
  }
</script>
</body>
</html>
