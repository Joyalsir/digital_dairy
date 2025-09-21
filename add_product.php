<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit;
}
include('includes/header.php');
include('includes/config.php');

if (isset($_POST['submit'])) {
    $productName = $_POST['product_name'];
    $productType = $_POST['product_type'];
    $unitPrice = $_POST['unit_price'];

    $sql = "INSERT INTO tblproduct (ProductName, ProductType, UnitPrice) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssd", $productName, $productType, $unitPrice);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $msg = "Product added successfully.";
    } else {
        $msg = "Failed to add product.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Digital Dairy Management System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include('includes/sidebar.php'); ?>

        <div class="dashboard-main">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <div class="dashboard-title">
                    <h1>Add Product</h1>
                    <p>Add new dairy products to your inventory</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <?php echo date("D, M j, Y - h:i A"); ?>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <div class="action-info">
                    <h3><i class="fas fa-plus-circle"></i> Product Management</h3>
                    <p class="text-muted">Add new dairy products to your inventory system</p>
                </div>
                <div class="action-buttons">
                    <a href="manage_product.php" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> View All Products
                    </a>
                </div>
            </div>

            <!-- Product Form Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-box"></i> Add New Product</h3>
                    <p class="text-muted">Please fill in all required fields to add a new product</p>
                </div>
                
                <div class="form-container">
                    <?php if (isset($msg)): ?>
                        <div class="alert alert-info" style="margin-bottom: 1rem; padding: 1rem; background: #d1fae5; color: #065f46; border-radius: 0.5rem;">
                            <i class="fas fa-check-circle"></i> <?php echo $msg; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="post" class="modern-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="product_name" class="form-label">
                                    <i class="fas fa-tag"></i> Product Name <span class="required">*</span>
                                </label>
                                <input type="text" id="product_name" name="product_name" class="form-control" 
                                       placeholder="Enter product name" required>
                            </div>

                            <div class="form-group">
                                <label for="product_type" class="form-label">
                                    <i class="fas fa-cubes"></i> Product Type <span class="required">*</span>
                                </label>
                                <input type="text" id="product_type" name="product_type" class="form-control" 
                                       placeholder="e.g., Milk, Ghee, Butter, Curd" required>
                            </div>

                            <div class="form-group">
                                <label for="unit_price" class="form-label">
                                    <i class="fas fa-rupee-sign"></i> Unit Price (₹) <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-addon">₹</span>
                                    <input type="number" id="unit_price" name="unit_price" step="0.01" 
                                           class="form-control" placeholder="0.00" min="0" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i> Reset Form
                            </button>
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Add Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid" style="margin-top: 2rem;">
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as total FROM tblproduct");
                        $total_products = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $total_products = $row['total'];
                        }
                        ?>
                        <h3><?php echo number_format($total_products); ?></h3>
                        <p>Total Products</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon milk">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT AVG(UnitPrice) as avg_price FROM tblproduct");
                        $avg_price = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $avg_price = $row['avg_price'] ? $row['avg_price'] : 0;
                        }
                        ?>
                        <h3>₹<?php echo number_format($avg_price, 2); ?></h3>
                        <p>Average Price</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Active</h3>
                        <p>System Status</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

   
</body>
</html>
<style>
          
        </style>
<?php include('includes/footer.php'); ?>
