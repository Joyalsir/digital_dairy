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
    <title>Add Delivery - Digital Dairy Management System</title>
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
                    <h1>Add Delivery Details</h1>
                    <p>Schedule a new delivery for customers</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <?php echo date("D, M j, Y - h:i A"); ?>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <div class="action-info">
                    <h3><i class="fas fa-truck"></i> Delivery Scheduling</h3>
                    <p class="text-muted">Schedule new delivery orders for customers</p>
                </div>
                <div class="action-buttons">
                    <a href="manage_delivery.php" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> View All Deliveries
                    </a>
                </div>
            </div>

            <!-- Delivery Form Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-plus-circle"></i> Delivery Form</h3>
                    <p class="text-muted">Please fill in all required fields to schedule a delivery</p>
                </div>
                
                <div class="form-container">
                    <form action="insert_delivery.php" method="POST" class="modern-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="customer_name" class="form-label">
                                    <i class="fas fa-user"></i> Customer Name <span class="required">*</span>
                                </label>
                                <input type="text" id="customer_name" name="customer_name" class="form-control" 
                                       placeholder="Enter customer name" required>
                            </div>

                            <div class="form-group">
                                <label for="contact" class="form-label">
                                    <i class="fas fa-phone"></i> Customer Contact <span class="required">*</span>
                                </label>
                                <input type="tel" id="contact" name="contact" class="form-control" 
                                       placeholder="Enter phone number" required>
                            </div>

                            <div class="form-group full-width">
                                <label for="address" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i> Delivery Address <span class="required">*</span>
                                </label>
                                <textarea id="address" name="address" class="form-control" rows="3" 
                                          placeholder="Enter complete delivery address" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="delivery_date" class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Delivery Date <span class="required">*</span>
                                </label>
                                <input type="date" id="delivery_date" name="delivery_date" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="product_type" class="form-label">
                                    <i class="fas fa-box"></i> Product Type <span class="required">*</span>
                                </label>
                                <select id="product_type" name="product_type" class="form-control" required>
                                    <option value="">Select Product</option>
                                    <option value="Milk">Milk</option>
                                    <option value="Ghee">Ghee</option>
                                    <option value="Butter">Butter</option>
                                    <option value="Curd">Curd</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="quantity" class="form-label">
                                    <i class="fas fa-weight"></i> Quantity <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" id="quantity" name="quantity" step="0.01" 
                                           class="form-control" placeholder="0.0" required>
                                    <span class="input-addon">Liters/Kg</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="vehicle_no" class="form-label">
                                    <i class="fas fa-truck"></i> Vehicle Number <span class="required">*</span>
                                </label>
                                <input type="text" id="vehicle_no" name="vehicle_no" class="form-control" 
                                       placeholder="e.g., MH12AB1234" required>
                            </div>

                            <div class="form-group">
                                <label for="driver_name" class="form-label">
                                    <i class="fas fa-user-tie"></i> Driver Name <span class="required">*</span>
                                </label>
                                <input type="text" id="driver_name" name="driver_name" class="form-control" 
                                       placeholder="Enter driver name" required>
                            </div>

                            <div class="form-group">
                                <label for="driver_contact" class="form-label">
                                    <i class="fas fa-phone"></i> Driver Contact <span class="required">*</span>
                                </label>
                                <input type="tel" id="driver_contact" name="driver_contact" class="form-control" 
                                       placeholder="Enter driver phone number" required>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i> Reset Form
                            </button>
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Schedule Delivery
                            </button>
                        </div>
                    </form>
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
