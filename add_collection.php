<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
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
    <title>Add Milk Collection - Digital Dairy Management System</title>
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
                    <h1>Add Milk Collection</h1>
                    <p>Record new milk collection data from farmers</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <?php echo date("D, M j, Y - h:i A"); ?>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <div class="action-info">
                    <h3><i class="fas fa-faucet-drip"></i> Milk Collection Entry</h3>
                    <p class="text-muted">Enter milk collection details from registered farmers</p>
                </div>
                <div class="action-buttons">
                    <a href="manage_collection.php" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> View All Records
                    </a>
                </div>
            </div>

            <!-- Collection Form Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-plus-circle"></i> Milk Collection Form</h3>
                    <p class="text-muted">Please fill in all required fields to record milk collection</p>
                </div>
                
                <div class="form-container">
                    <form method="POST" class="modern-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="date" class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Date <span class="required">*</span>
                                </label>
                                <input type="date" id="date" name="date" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="farmer" class="form-label">
                                    <i class="fas fa-user"></i> Select Farmer <span class="required">*</span>
                                </label>
                                <select id="farmer" name="farmer" class="form-control" required>
                                    <option value="">-- Select Farmer --</option>
                                    <?php
                                    $query = mysqli_query($con, "SELECT * FROM farmers ORDER BY name ASC");
                                    while ($row = mysqli_fetch_array($query)) {
                                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="product_type" class="form-label">
                                    <i class="fas fa-tint"></i> Product Type <span class="required">*</span>
                                </label>
                                <input type="text" id="product_type" name="product_type" class="form-control" 
                                       placeholder="E.g. Cow Milk" required>
                            </div>

                            <div class="form-group">
                                <label for="quantity" class="form-label">
                                    <i class="fas fa-weight"></i> Milk Quantity (L) <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" id="quantity" name="quantity" step="0.01" 
                                           class="form-control" placeholder="0.0" required>
                                    <span class="input-addon">liters</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="fat" class="form-label">
                                    <i class="fas fa-percentage"></i> Milk Fat Content (%) <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" id="fat" name="fat" step="0.01" 
                                           class="form-control" placeholder="0.0" required>
                                    <span class="input-addon">%</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="temperature" class="form-label">
                                    <i class="fas fa-thermometer-half"></i> Temperature (°C) <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" id="temperature" name="temperature" step="0.1" 
                                           class="form-control" placeholder="0.0" required>
                                    <span class="input-addon">°C</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="payment" class="form-label">
                                    <i class="fas fa-rupee-sign"></i> Payment (₹) <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-addon">₹</span>
                                    <input type="number" id="payment" name="payment" step="0.01" 
                                           class="form-control" placeholder="0.00" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i> Reset Form
                            </button>
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Submit Collection
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid" style="margin-top: 2rem;">
                <div class="stat-card">
                    <div class="stat-icon milk">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT SUM(quantity) as total FROM milk_collection WHERE DATE(date) = CURDATE()");
                        $today_collection = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $today_collection = $row['total'] ? $row['total'] : 0;
                        }
                        ?>
                        <h3><?php echo number_format($today_collection, 2); ?> L</h3>
                        <p>Today's Collection</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon farmers">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(DISTINCT farmer_id) as farmers FROM milk_collection WHERE DATE(date) = CURDATE()");
                        $today_farmers = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $today_farmers = $row['farmers'];
                        }
                        ?>
                        <h3><?php echo number_format($today_farmers); ?></h3>
                        <p>Farmers Today</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT SUM(payment) as total FROM milk_collection WHERE DATE(date) = CURDATE()");
                        $today_payment = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $today_payment = $row['total'] ? $row['total'] : 0;
                        }
                        ?>
                        <h3>₹<?php echo number_format($today_payment, 2); ?></h3>
                        <p>Today's Payment</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

   

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set today's date as default
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[type="date"]').value = today;
        
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate all required fields
            document.querySelectorAll('[required]').forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill all required fields!');
            }
        });
    });
    </script>
</body>
</html>

<?php include('includes/footer.php'); ?>
