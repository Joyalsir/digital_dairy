<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
include('includes/header.php');
include('includes/config.php');

if (!isset($_GET['pid'])) {
    echo "<script>alert('Invalid product ID.'); window.location.href='manage_product.php';</script>";
    exit;
}

$pid = mysqli_real_escape_string($con, $_GET['pid']);
$query = "SELECT * FROM tblproduct WHERE ID = '$pid'";
$result = mysqli_query($con, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('Product not found.'); window.location.href='manage_product.php';</script>";
    exit;
}
$product = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $productName = mysqli_real_escape_string($con, $_POST['product_name']);
    $productType = mysqli_real_escape_string($con, $_POST['product_type']);
    $unitPrice = mysqli_real_escape_string($con, $_POST['unit_price']);

    $update_sql = "UPDATE tblproduct SET ProductName = ?, ProductType = ?, UnitPrice = ? WHERE ID = ?";
    $stmt = $con->prepare($update_sql);
    $stmt->bind_param("ssdi", $productName, $productType, $unitPrice, $pid);

    if ($stmt->execute()) {
        echo "<script>
            alert('Product updated successfully!');
            window.location.href='manage_product.php';
        </script>";
    } else {
        echo "<script>alert('Error updating product: " . mysqli_error($con) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Digital Dairy Management System</title>
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
                    <h1>Edit Product</h1>
                    <p>Update details for product: <?php echo htmlspecialchars($product['ProductName']); ?></p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <?php echo date("D, M j, Y - h:i A"); ?>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <div class="action-info">
                    <h3><i class="fas fa-edit"></i> Product Management</h3>
                    <p class="text-muted">Update product information and pricing</p>
                </div>
                <div class="action-buttons">
                    <a href="manage_product.php" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> View All Products
                    </a>
                </div>
            </div>

            <!-- Edit Product Form Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-box"></i> Product Edit Form</h3>
                    <p class="text-muted">Modify the product information and save changes</p>
                </div>

                <div class="form-container">
                    <form method="post" class="modern-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="product_name" class="form-label">
                                    <i class="fas fa-tag"></i> Product Name <span class="required">*</span>
                                </label>
                                <input type="text" id="product_name" name="product_name" class="form-control"
                                       placeholder="Enter product name" required
                                       value="<?php echo htmlspecialchars($product['ProductName']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="product_type" class="form-label">
                                    <i class="fas fa-cubes"></i> Product Type <span class="required">*</span>
                                </label>
                                <input type="text" id="product_type" name="product_type" class="form-control"
                                       placeholder="e.g., Milk, Ghee, Butter, Curd" required
                                       value="<?php echo htmlspecialchars($product['ProductType']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="unit_price" class="form-label">
                                    <i class="fas fa-rupee-sign"></i> Unit Price (₹) <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-addon">₹</span>
                                    <input type="number" id="unit_price" name="unit_price" step="0.01"
                                           class="form-control" placeholder="0.00" min="0" required
                                           value="<?php echo htmlspecialchars($product['UnitPrice']); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i> Reset Changes
                            </button>
                            <button type="submit" name="update" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Product Info -->
            <div class="chart-card" style="margin-top: 2rem;">
                <div class="chart-header">
                    <h3><i class="fas fa-info-circle"></i> Product Information</h3>
                    <p class="text-muted">Current product details</p>
                </div>
                <div class="product-details">
                    <div class="detail-row">
                        <span class="detail-label">Product ID:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($product['ID']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Created:</span>
                        <span class="detail-value">
                            <?php echo date('M j, Y', strtotime($product['CreationDate'] ?? date('Y-m-d'))); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php include('includes/footer.php'); ?>
