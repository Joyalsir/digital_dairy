<?php
include('includes/config.php');
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
      padding: 120px 0 100px;
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
    
    /* Products Section */
    .products {
      padding: 100px 0;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .products .card {
      border: none;
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      height: 100%;
    }
    
    .products .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }
    
    .products .card-img-top {
      height: 200px;
      object-fit: cover;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 3rem;
    }
    
    .products .card-body {
      padding: 25px;
    }
    
    .products .card-title {
      color: var(--primary-color);
      font-weight: 600;
      margin-bottom: 15px;
    }
    
    .products .price {
      font-size: 1.2rem;
      font-weight: 700;
      color: var(--secondary-color);
      margin-bottom: 20px;
    }
    
    .products .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border: none;
      border-radius: 25px;
      padding: 10px 25px;
      font-weight: 600;
    }
    
    .products .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(44, 90, 160, 0.3);
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

    /* Demo Section Styles */
    .demo-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .demo-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0,0,0,0.15) !important;
    }
    
    .demo-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
      color: white;
    }
    
    .form-group {
      position: relative;
    }
    
    .form-label {
      display: flex;
      align-items: center;
      font-weight: 500;
    }
    
    .form-control {
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      padding: 15px;
      font-size: 16px;
      transition: all 0.3s ease;
    }
    
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(44, 90, 160, 0.1);
    }
    
    .form-control-lg {
      padding: 15px 20px;
      font-size: 18px;
    }
    
    /* Comparison Section Styles */
    .comparison-card {
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }
    
    .comparison-card:hover {
      transform: translateY(-3px);
    }
    
    .comparison-card.traditional {
      border-left: 4px solid #dc3545;
    }
    
    .comparison-card.digital {
      border-left: 4px solid #28a745;
    }
    
    .comparison-icon {
      width: 50px;
      height: 50px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .point-item {
      padding: 10px 0;
      border-bottom: 1px solid #f1f3f4;
    }
    
    .point-item:last-child {
      border-bottom: none;
    }
    
    .progress {
      background-color: #f8f9fa;
      border-radius: 10px;
    }
    
    .progress-bar {
      border-radius: 10px;
      transition: width 0.6s ease;
    }
    
    .summary-stats {
      border: 2px solid rgba(44, 90, 160, 0.1);
    }
    
    .stat-box {
      padding: 15px;
    }
    
    .stat-value {
      font-size: 1.5rem;
      margin-bottom: 5px;
    }
    
    .stat-label {
      font-size: 0.9rem;
      color: var(--light-text);
    }
  </style>
</head>
<body>

<section class="hero py-5">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <span class="badge mb-3 px-3 py-2 fs-6 fw-semibold">Trusted by 5,000+ farmers across India</span>
        <h1 class="display-4 fw-bold mb-4">
          Take Control of Your <span class="text-primary">Dairy Business</span>
        </h1>
        <p class="lead mb-4">
          Get complete transparency over milk collection records and payments - as simple as checking your phone. No more disputes, no more delays.
        </p>
        <div class="d-flex flex-wrap gap-4 mb-4">
          <div class="d-flex align-items-center gap-2">
            <i class="fas fa-shield-alt text-success fs-4"></i>
            <span>Bank-level security</span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <i class="fas fa-check-circle text-success fs-4"></i>
            <span>99.8% payment accuracy</span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <i class="fas fa-wifi text-success fs-4"></i>
            <span>Works offline</span>
          </div>
        </div>
        <div class="d-flex flex-wrap gap-3">
          <a href="#" class="btn btn-primary btn-lg px-4 py-3 fw-semibold">Start Free Trial - No Credit Card Required</a>
          <a href="#" class="btn btn-outline-primary btn-lg px-4 py-3 fw-semibold">Watch 2-Min Demo</a>
        </div>
      </div>
      <div class="col-lg-6 position-relative">
        <img src="https://images.unsplash.com/photo-1570042225831-d98fa7577f1e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Dashboard" class="img-fluid rounded-4 border border-primary border-3 shadow-lg" />
        <div class="position-absolute top-0 end-0 px-3 py-2 rounded-3 shadow" style="transform: translate(20%, -50%); font-weight: 600; background-color: #166534; color: #d1d5db;">
          ₹45,230 <small class="d-block" style="font-weight: 400; font-size: 0.8rem;">This month's earnings</small>
        </div>
        <div class="position-absolute bottom-0 start-0 shadow rounded-3 px-3 py-2" style="transform: translate(-20%, 50%); min-width: 100px; font-weight: 600; background-color: #374151; color: #d1d5db;">
          850L <small class="d-block" style="font-weight: 400; font-size: 0.8rem;">Today's collection</small>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section id="features" class="features">
  <div class="container">
    <div class="text-center mb-5">
      <h2 class="display-4 fw-bold mb-3">Why Choose Our System?</h2>
      <p class="lead text-muted">Comprehensive features designed for modern dairy management</p>
    </div>
    
    <div class="row">
      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-users"></i>
          </div>
          <h4>Farmer Management</h4>
          <p>Efficiently manage farmer profiles, track milk supply, and handle payments seamlessly.</p>
        </div>
      </div>
      
      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-chart-line"></i>
          </div>
          <h4>Real-time Analytics</h4>
          <p>Get instant insights with comprehensive dashboards and detailed reports.</p>
        </div>
      </div>
      
      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-truck"></i>
          </div>
          <h4>Delivery Tracking</h4>
          <p>Monitor milk collection and product delivery with real-time tracking.</p>
        </div>
      </div>
      
      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-shopping-cart"></i>
          </div>
          <h4>Product Sales</h4>
          <p>Manage dairy product inventory and sales with ease.</p>
        </div>
      </div>
      
      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-money-bill-wave"></i>
          </div>
          <h4>Payment Processing</h4>
          <p>Automated payment calculations and transaction management.</p>
        </div>
      </div>
      
      <div class="col-lg-4 col-md-6">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fas fa-mobile-alt"></i>
          </div>
          <h4>Mobile Friendly</h4>
          <p>Access your dairy management system from any device, anywhere.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Solution Demo Section -->
<section id="demo" class="section-padding bg-surface">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-4 fw-bold mb-3">See How Easy Digital Tracking Can Be</h2>
            <p class="lead text-muted">Watch your milk records get automatically generated and synced in real-time</p>
        </div>
        
        <div class="row">
            <!-- Left Side: Interactive Demo -->
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="demo-card bg-white rounded-4 shadow-lg p-5 h-100">
                    <div class="text-center mb-4">
                        <div class="demo-icon mb-3">
                            <i class="fas fa-calculator fa-3x text-primary"></i>
                        </div>
                        <h3 class="h4 fw-bold text-text-primary mb-3">Try Our Smart Calculator</h3>
                        <p class="text-text-secondary">Experience how our system automatically calculates your earnings</p>
                    </div>
                    
                    <!-- Demo Form -->
                    <div class="demo-form">
                        <div class="form-group mb-4">
                            <label class="form-label fw-semibold text-text-secondary mb-2">
                                <i class="fas fa-sun me-2 text-warning"></i>Morning Collection (Liters)
                            </label>
                            <input type="number" id="morning-milk" class="form-control form-control-lg" placeholder="Enter liters" value="25" />
                        </div>
                        
                        <div class="form-group mb-4">
                            <label class="form-label fw-semibold text-text-secondary mb-2">
                                <i class="fas fa-moon me-2 text-primary"></i>Evening Collection (Liters)
                            </label>
                            <input type="number" id="evening-milk" class="form-control form-control-lg" placeholder="Enter liters" value="20" />
                        </div>
                        
                        <div class="form-group mb-4">
                            <label class="form-label fw-semibold text-text-secondary mb-2">
                                <i class="fas fa-tint me-2 text-info"></i>Fat Content (%)
                            </label>
                            <input type="number" id="fat-content" class="form-control form-control-lg" placeholder="Enter fat %" value="4.2" step="0.1" />
                        </div>
                        
                        <button onclick="calculateEarnings()" class="btn btn-primary btn-lg w-100 py-3 fw-semibold">
                            <i class="fas fa-calculator me-2"></i>Calculate Today's Earnings
                        </button>
                    </div>
                    
                    <!-- Results -->
                    <div id="calculation-results" class="mt-5 p-4 bg-success bg-opacity-10 rounded-3 border border-success border-opacity-25" style="display: none;">
                        <div class="text-center mb-3">
                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                            <h4 class="fw-bold text-success">Calculation Complete!</h4>
                        </div>
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="stat-box">
                                    <div class="stat-value text-primary fw-bold fs-4" id="total-milk">45L</div>
                                    <div class="stat-label text-text-secondary">Total Milk</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-box">
                                    <div class="stat-value text-success fw-bold fs-4" id="total-earning">₹1,890</div>
                                    <div class="stat-label text-text-secondary">Today's Earning</div>
                                </div>
                            </div>
                        </div>
                       
                  </div>
                </div>
            </div>

            <!-- Right Side: Comparison Section -->
            <div class="col-lg-6">
                <div class="comparison-section">
                    <div class="text-center mb-4">
                        <h3 class="h4 fw-bold text-text-primary">Why Choose Digital Over Traditional?</h3>
                        <p class="text-text-secondary">See the clear advantages of our digital system</p>
                    </div>
                    
                    <!-- Comparison Cards -->
                    <div class="comparison-cards">
                        <!-- Traditional Method Card -->
                        <div class="comparison-card traditional mb-4">
                            <div class="card-header bg-danger bg-opacity-10 py-3 px-4 border-0">
                                <div class="d-flex align-items-center">
                                    <div class="comparison-icon me-3">
                                        <i class="fas fa-times-circle fa-2x text-danger"></i>
                                    </div>
                                    <h4 class="fw-bold text-danger mb-0">Traditional Paper Method</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="comparison-points">
                                    <div class="point-item mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-times text-danger me-3"></i>
                                            <span class="text-text-secondary">Manual calculations prone to errors</span>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-danger" style="width: 30%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="point-item mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-times text-danger me-3"></i>
                                            <span class="text-text-secondary">No backup if records are lost</span>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-danger" style="width: 20%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="point-item mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-times text-danger me-3"></i>
                                            <span class="text-text-secondary">Disputes over handwriting</span>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-danger" style="width: 25%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="point-item">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-times text-danger me-3"></i>
                                            <span class="text-text-secondary">Time-consuming monthly reports</span>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-danger" style="width: 15%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Digital Method Card -->
                        <div class="comparison-card digital">
                            <div class="card-header bg-success bg-opacity-10 py-3 px-4 border-0">
                                <div class="d-flex align-items-center">
                                    <div class="comparison-icon me-3">
                                        <i class="fas fa-check-circle fa-2x text-success"></i>
                                    </div>
                                    <h4 class="fw-bold text-success mb-0"> Digital Dairy</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="comparison-points">
                                    <div class="point-item mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check text-success me-3"></i>
                                            <span class="text-text-secondary">Automatic calculations, 99.8% accurate</span>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 95%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="point-item mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check text-success me-3"></i>
                                            <span class="text-text-secondary">Cloud backup, never lose data</span>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 100%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="point-item mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check text-success me-3"></i>
                                            <span class="text-text-secondary">Easy to use interface</span>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 90%"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="point-item">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check text-success me-3"></i>
                                            <span class="text-text-secondary">Real-time syncing and reports</span>
                                        </div>
                                        <div class="progress mt-2" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: 98%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Summary Stats -->
                    <div class="summary-stats mt-4 p-4 bg-primary bg-opacity-5 rounded-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="stat">
                                    <div class="stat-value fw-bold text-primary">70%</div>
                                    <div class="stat-label text-text-secondary">Time Saved</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat">
                                    <div class="stat-value fw-bold text-success">99.8%</div>
                                    <div class="stat-label text-text-secondary">Accuracy</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="stat">
                                    <div class="stat-value fw-bold text-info">24/7</div>
                                    <div class="stat-label text-text-secondary">Access</div>
                                </div>
                            </div>
                        </div>
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

    <!-- Our Products Section -->
    <section id="products" class="products">
      <div class="container">
        <div class="text-center mb-5">
          <h2 class="display-4 fw-bold mb-3">Our Products</h2>
          <p class="lead text-muted">Explore our range of fresh dairy products</p>
        </div>
        
        <div class="row">
          <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
              <div class="card-img-top">
                <i class="fas fa-milk-bottle"></i>
              </div>
              <div class="card-body">
                <h5 class="card-title">Fresh Milk</h5>
                <p class="card-text">Pure and fresh milk sourced from local farms.</p>
                <p class="price">₹50 per liter</p>
                <a href="#" class="btn btn-primary">Order Now</a>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
              <div class="card-img-top">
                <i class="fas fa-cheese"></i>
              </div>
              <div class="card-body">
                <h5 class="card-title">Cheese</h5>
                <p class="card-text">Delicious cheese made from the finest milk.</p>
                <p class="price">₹200 per 200g</p>
                <a href="#" class="btn btn-primary">Order Now</a>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
              <div class="card-img-top">
                <i class="fas fa-bread-slice"></i>
              </div>
              <div class="card-body">
                <h5 class="card-title">Butter</h5>
                <p class="card-text">Creamy and rich butter made from fresh cream.</p>
                <p class="price">₹150 per 250g</p>
                <a href="#" class="btn btn-primary">Order Now</a>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
              <div class="card-img-top">
                <i class="fas fa-ice-cream"></i>
              </div>
              <div class="card-body">
                <h5 class="card-title">Ice Cream</h5>
                <p class="card-text">Premium quality ice cream in various flavors.</p>
                <p class="price">₹100 per scoop</p>
                <a href="#" class="btn btn-primary">Order Now</a>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
              <div class="card-img-top">
                <i class="fas fa-cookie"></i>
              </div>
              <div class="card-body">
                <h5 class="card-title">Curd</h5>
                <p class="card-text">Fresh and creamy curd made daily.</p>
                <p class="price">₹40 per 500g</p>
                <a href="#" class="btn btn-primary">Order Now</a>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
              <div class="card-img-top">
                <i class="fas fa-egg"></i>
              </div>
              <div class="card-body">
                <h5 class="card-title">Paneer</h5>
                <p class="card-text">Fresh cottage cheese for your recipes.</p>
                <p class="price">₹180 per kg</p>
                <a href="#" class="btn btn-primary">Order Now</a>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
              <div class="card-img-top">
                <i class="fas fa-cookie-bite"></i>
              </div>
              <div class="card-body">
                <h5 class="card-title">Ghee</h5>
                <p class="card-text">Pure clarified butter for cooking.</p>
                <p class="price">₹500 per kg</p>
                <a href="#" class="btn btn-primary">Order Now</a>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
              <div class="card-img-top">
                <i class="fas fa-cake"></i>
              </div>
              <div class="card-body">
                <h5 class="card-title">Yogurt</h5>
                <p class="card-text">Healthy probiotic yogurt.</p>
                <p class="price">₹60 per 500g</p>
               <a href="login.php?role=user" class="btn btn-primary">Order Now</a>

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
      <a href="register.php" class="btn btn-light btn-custom">Register Now</a>
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
          <a href="index.php">Features</a>
          <a href="login.php">Login</a>
          <a href="register.php">Register</a>
        </div>
      </div>
      <div class="col-lg-2 col-6 mb-4">
        <h6>Support</h6>
        <div class="footer-links d-flex flex-column">
          <a href="#">Help Center</a>
          <a href="#">Contact Us</a>
          <a href="#">FAQs</a>
        </div>
      </div>
      <div class="col-lg-4 mb-4">
        <h6>Contact Info</h6>
        <p><i class="fas fa-envelope me-2"></i> info@digitaldairy.com</p>
        <p><i class="fas fa-phone me-2"></i> +91 9876543210</p>
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
