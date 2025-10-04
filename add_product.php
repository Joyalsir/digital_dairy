<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit;
}
include('includes/header.php');
include('includes/config.php');

if (isset($_POST['submit'])) {
    $productName = $_POST['product_type'];
    $productType = $_POST['product_category'];
    $unitPrice = $_POST['unit_price'];

    // Handle image upload
    $productImage = '';
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (in_array($_FILES['product_image']['type'], $allowed_types) && $_FILES['product_image']['size'] <= $max_size) {
            $upload_dir = 'uploads/products/';

            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid('product_', true) . '.' . $file_extension;
            $target_file = $upload_dir . $file_name;

            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
                $productImage = $target_file;
            } else {
                $error_msg = "Failed to upload image. Please check directory permissions and try again.";
            }
        } else {
            $error_msg = "Invalid file type or file too large. Please use JPG, PNG, GIF, or WebP images under 5MB.";
        }
    }

    if (!isset($error_msg)) {
        $sql = "INSERT INTO tblproduct (ProductName, ProductType, UnitPrice, ProductImage) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql);

        if ($stmt === false) {
            $error_msg = "Database error: " . $con->error;
        } else {
            $stmt->bind_param("ssds", $productName, $productType, $unitPrice, $productImage);
            if ($stmt->execute()) {
                $msg = "Product added successfully.";
            } else {
                $error_msg = "Failed to add product to database: " . $stmt->error;
            }
            $stmt->close();
        }
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

                    <?php if (isset($error_msg)): ?>
                        <div class="alert alert-error" style="margin-bottom: 1rem; padding: 1rem; background: #fee2e2; color: #dc2626; border-radius: 0.5rem;">
                            <i class="fas fa-exclamation-triangle"></i> <?php echo $error_msg; ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" enctype="multipart/form-data" class="modern-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="product_category" class="form-label">
                                    <i class="fas fa-cubes"></i> Product Category <span class="required">*</span>
                                </label>
                                <select id="product_category" name="product_category" class="form-control" required>
                                    <option value="">-- Select Product Category --</option>
                                    <option value="Milk Product">🥛 Milk Product</option>
                                    <option value="Curd & Sambaram">🥛 Curd & Sambaram</option>
                                    <option value="Ice Cream">🍦 Ice Cream</option>
                                    <option value="Ghee & Butter">🧈 Ghee & Butter</option>
                                    <option value="Other">📦 Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="product_type" class="form-label">
                                    <i class="fas fa-tag"></i> Product Name <span class="required">*</span>
                                </label>
                                <input type="text" id="product_type" name="product_type" class="form-control"
                                       placeholder="Enter product name" required>
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

                            <div class="form-group">
                                <label for="product_image" class="form-label">
                                    <i class="fas fa-image"></i> Product Image <span class="optional">(optional)</span>
                                </label>
                                <div class="file-input-container">
                                    <input type="file" id="product_image" name="product_image" class="form-control"
                                           accept="image/*" onchange="previewImage(this)">
                                    <div class="file-input-info">
                                        <small class="text-muted">Accepted formats: JPG, PNG, GIF, WebP (Max: 5MB)</small>
                                    </div>
                                </div>
                                <div id="image_preview" class="image-preview" style="display: none;">
                                    <img id="preview_img" src="" alt="Preview" style="max-width: 200px; max-height: 200px; border-radius: 0.5rem; margin-top: 0.5rem;">
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

    <script>
    function previewImage(input) {
        const preview = document.getElementById('image_preview');
        const previewImg = document.getElementById('preview_img');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }
    </script>
</body>
</html>
<style>
    .file-input-container {
        position: relative;
    }

    .file-input-info {
        margin-top: 0.25rem;
    }

    .image-preview {
        margin-top: 0.5rem;
    }

    .required {
        color: #dc2626;
        font-weight: bold;
    }

    .optional {
        color: #6b7280;
        font-weight: normal;
    }
</style>
<?php include('includes/footer.php'); ?>
