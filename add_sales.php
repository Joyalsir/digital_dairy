<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit;
}
include('includes/header.php');
include('includes/config.php');

if (isset($_POST['submit'])) {
    $invoice = $_POST['invoice'];
    $cname   = $_POST['cname'];
    $contact = $_POST['contact'];
    $product = $_POST['product'];
    $qty     = $_POST['qty'];
    $price   = $_POST['price'];
    $total   = $qty * $price;
    $sdate   = $_POST['sdate'];

    $query = mysqli_query($con, "
        INSERT INTO tblsales
        (InvoiceNumber, CustomerName, Contact, ProductName, Quantity, Price, TotalAmount, SalesDate)
        VALUES
        ('$invoice', '$cname', '$contact', '$product', '$qty', '$price', '$total', '$sdate')
    ");

    if ($query) {
        $msg = "Sales record added successfully.";
    } else {
        $msg = "Error occurred while adding sales record.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sales - Digital Dairy Management System</title>
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
                    <h1>Add Sales Record</h1>
                    <p>Record new sales transactions</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <span id="currentDateTime"></span>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <div class="action-info">
                    <h3><i class="fas fa-shopping-cart"></i> Sales Management</h3>
                    <p class="text-muted">Record new sales transactions and manage customer orders</p>
                </div>
                <div class="action-buttons">
                    <a href="manage_sales.php" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> View All Sales
                    </a>
          </div>
            </div>

            <!-- Sales Form Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-receipt"></i> Sales Entry Form</h3>
                    <p class="text-muted">Please fill in all required fields to record a new sale</p>
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
                                <label for="invoice" class="form-label">
                                    <i class="fas fa-file-invoice"></i> Invoice Number <span class="required">*</span>
                                </label>
                                <input type="text" id="invoice" name="invoice" class="form-control" 
                                       placeholder="e.g., INV-2024-001" required>
                            </div>

                            <div class="form-group">
                                <label for="cname" class="form-label">
                                    <i class="fas fa-user"></i> Customer Name <span class="required">*</span>
                                </label>
                                <input type="text" id="cname" name="cname" class="form-control" 
                                       placeholder="Enter customer name" required>
                            </div>

                            <div class="form-group">
                                <label for="contact" class="form-label">
                                    <i class="fas fa-phone"></i> Contact <span class="required">*</span>
                                </label>
                                <input type="tel" id="contact" name="contact" class="form-control" 
                                       placeholder="Enter phone number" required>
                            </div>

                            <div class="form-group">
                                <label for="product" class="form-label">
                                    <i class="fas fa-box"></i> Product Name <span class="required">*</span>
                                </label>
                                <input type="text" id="product" name="product" class="form-control" 
                                       placeholder="Enter product name" required>
                            </div>

                            <div class="form-group">
                                <label for="qty" class="form-label">
                                    <i class="fas fa-weight"></i> Quantity <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" id="qty" name="qty" class="form-control" 
                                           placeholder="0" min="1" required>
                                    <span class="input-addon">units</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="price" class="form-label">
                                    <i class="fas fa-rupee-sign"></i> Price per Unit <span class="required">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-addon">₹</span>
                                    <input type="number" id="price" name="price" step="0.01" 
                                           class="form-control" placeholder="0.00" min="0" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="sdate" class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Sales Date <span class="required">*</span>
                                </label>
                                <input type="date" id="sdate" name="sdate" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i> Reset Form
                            </button>
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Record Sale
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid" style="margin-top: 2rem;">
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as total FROM tblsales");
                        $total_sales = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $total_sales = $row['total'];
                        }
                        ?>
                        <h3><?php echo number_format($total_sales); ?></h3>
                        <p>Total Sales</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon milk">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT SUM(TotalAmount) as total FROM tblsales");
                        $total_revenue = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $total_revenue = $row['total'] ? $row['total'] : 0;
                        }
                        ?>
                        <h3>₹<?php echo number_format($total_revenue, 2); ?></h3>
                        <p>Total Revenue</p>
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

   <style>
   
   </style>

    <script>
        // Function to update current date and time
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            };
            const formattedDateTime = now.toLocaleDateString('en-US', options);
            document.getElementById('currentDateTime').textContent = formattedDateTime;
        }

        // Update time immediately and then every second
        updateDateTime();
        setInterval(updateDateTime, 1000);

    document.addEventListener('DOMContentLoaded', function() {
        // Set today's date as default
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[type="date"]').value = today;
        
        // Auto-calculate total when quantity or price changes
        const qtyInput = document.getElementById('qty');
        const priceInput = document.getElementById('price');
        
        function updateTotal() {
            const qty = parseFloat(qtyInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            // Total is calculated server-side, but we can show a preview
        }
        
        qtyInput.addEventListener('input', updateTotal);
        priceInput.addEventListener('input', updateTotal);
        
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
