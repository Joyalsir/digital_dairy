<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

include('includes/header.php');
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Milk Products - Digital Dairy Management System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --light-bg: #f8f9fa;
            --dark-overlay: rgba(44, 62, 80, 0.7);
        }

        .full-width-container {
            width: 100%;
            max-width: 100%;
            margin: 0;
            padding: 0;
        }

        .hero-section {
            background: linear-gradient(var(--dark-overlay), var(--dark-overlay)),
                        url('https://images.unsplash.com/photo-1550583724-b2692b85b150?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(52, 152, 219, 0.1) 0%, rgba(231, 76, 60, 0.1) 100%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .btn-order-now {
            background: linear-gradient(135deg, var(--accent-color) 0%, #c0392b 100%);
            color: white;
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: 600;
            border: none;
            border-radius: 50px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }

        .btn-order-now:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
            color: white;
        }

        .products-section {
            padding: 60px 0;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 50px;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--accent-color) 100%);
            border-radius: 2px;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .product-image {
            height: 200px;
            background: var(--light-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-info {
            padding: 20px;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .product-category {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-block;
            margin-bottom: 15px;
        }

        .product-price {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 15px;
        }

        .btn-add-to-cart {
            background: linear-gradient(135deg, var(--success-color) 0%, #229954 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            width: 100%;
            justify-content: center;
        }

        .btn-add-to-cart:hover {
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        }

        .no-products {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-products i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .no-products h3 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="full-width-container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Milk Products</h1>
                <p class="hero-subtitle">Fresh, pure milk products from our trusted dairy farms</p>
                <a href="#products" class="btn-order-now">
                    <i class="fas fa-shopping-cart"></i>
                    View Products
                </a>
            </div>
        </section>

        <!-- Products Section -->
        <section id="products" class="products-section">
            <div class="container">
                <h2 class="section-title">Our Milk Products</h2>

                <div class="row">
                    <?php
                    // Fetch only milk products from database
                    $ret = mysqli_query($con, "SELECT * FROM tblproduct WHERE ProductType LIKE '%Milk%' ORDER BY ID DESC");
                    $count = mysqli_num_rows($ret);

                    if ($count > 0) {
                        while ($row = mysqli_fetch_array($ret)) {
                    ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="product-card">
                                <div class="product-image">
                                    <?php if (!empty($row['ProductImage']) && file_exists($row['ProductImage'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['ProductImage']); ?>"
                                             alt="<?php echo htmlspecialchars($row['ProductName']); ?>">
                                    <?php else: ?>
                                        <div style="font-size: 3rem; color: #ddd;">
                                            <i class="fas fa-cow"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <div class="product-name">
                                        <?php echo htmlspecialchars($row['ProductName']); ?>
                                    </div>
                                    <div class="product-category">
                                        <?php echo htmlspecialchars($row['ProductType']); ?>
                                    </div>
                                    <div class="product-price">
                                        ₹<?php echo number_format($row['UnitPrice'], 2); ?>
                                    </div>
                                    <form method="POST" action="cart.php" style="display: inline;">
                                        <input type="hidden" name="action" value="add">
                                        <input type="hidden" name="product_id" value="<?php echo $row['ID']; ?>">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn-add-to-cart">
                                            <i class="fas fa-shopping-cart"></i>
                                            Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                        }
                    } else {
                    ?>
                        <div class="col-12">
                            <div class="no-products">
                                <i class="fas fa-cow"></i>
                                <h3>No Milk Products Available</h3>
                                <p>There are currently no milk products in our inventory. Please check back later or contact us for more information.</p>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </section>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
