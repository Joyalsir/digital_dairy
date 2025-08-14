<?php
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
    <title>Add Product</title>
    <?php include('includes/header.php'); ?>
</head>
<body>
    <div class="hk-wrapper">
        <?php include('includes/sidebar.php'); ?>
        <div class="hk-pg-wrapper">
            <div class="container mt-4">
                <h4 class="hk-sec-title">Add Product</h4>
                <?php if (isset($msg)) echo "<div class='alert alert-info'>$msg</div>"; ?>
                <div class="card">
                    <div class="card-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="product_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Product Type</label>
                                <input type="text" name="product_type" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Unit Price (₹)</label>
                                <input type="number" step="0.01" name="unit_price" class="form-control" required>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary mt-2">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
</body>
</html>
