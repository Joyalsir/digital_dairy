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
    <title>Add Farmer - Digital Dairy Management System</title>
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
                    <h1>Add New Farmer</h1>
                    <p>Register a new farmer in the dairy management system</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <?php echo date("D, M j, Y - h:i A"); ?>
                </div>
            </div>

            <!-- Add Farmer Form Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-user-plus"></i> Farmer Registration Form</h3>
                    <p class="text-muted">Please fill in all required fields to register a new farmer</p>
                </div>
                
                <div class="form-container">
                    <form action="process_farmer.php" method="post" class="modern-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user"></i> Full Name <span class="required">*</span>
                                </label>
                                <input type="text" id="name" name="name" class="form-control" required 
                                       placeholder="Enter farmer's full name">
                            </div>

                            <div class="form-group">
                                <label for="contact" class="form-label">
                                    <i class="fas fa-phone"></i> Contact Number
                                </label>
                                <input type="tel" id="contact" name="contact" class="form-control" 
                                       placeholder="Enter phone number">
                            </div>

                            <div class="form-group full-width">
                                <label for="address" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i> Address <span class="required">*</span>
                                </label>
                                <textarea id="address" name="address" class="form-control" rows="3" required 
                                          placeholder="Enter complete address"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="farm_size" class="form-label">
                                    <i class="fas fa-tractor"></i> Farm Size
                                </label>
                                <div class="input-group">
                                    <input type="number" id="farm_size" name="farm_size" class="form-control" 
                                           placeholder="0.0" step="0.01" min="0">
                                    <span class="input-addon">acres</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email Address
                                </label>
                                <input type="email" id="email" name="email" class="form-control" 
                                       placeholder="Enter email address">
                            </div>

                            <div class="form-group">
                                <label for="aadhar" class="form-label">
                                    <i class="fas fa-id-card"></i> Aadhar Number
                                </label>
                                <input type="text" id="aadhar" name="aadhar" class="form-control" 
                                       placeholder="Enter 12-digit Aadhar number" maxlength="12">
                            </div>

                            <div class="form-group">
                                <label for="bank_account" class="form-label">
                                    <i class="fas fa-university"></i> Bank Account
                                </label>
                                <input type="text" id="bank_account" name="bank_account" class="form-control" 
                                       placeholder="Enter bank account number">
                            </div>

                            <div class="form-group">
                                <label for="ifsc" class="form-label">
                                    <i class="fas fa-code"></i> IFSC Code
                                </label>
                                <input type="text" id="ifsc" name="ifsc" class="form-control" 
                                       placeholder="Enter IFSC code">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i> Reset Form
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Register Farmer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid" style="margin-top: 2rem;">
                <div class="stat-card">
                    <div class="stat-icon farmers">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as total FROM farmers");
                        $total_farmers = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $total_farmers = $row['total'];
                        }
                        ?>
                        <h3><?php echo number_format($total_farmers); ?></h3>
                        <p>Total Registered Farmers</p>
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

<?php include('includes/footer.php'); ?>
