<?php
session_start();
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Dairy - Milk Collection</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
  
    
</head>
<body>
    
        <?php include('includes/sidebar.php'); ?>
        
       
            <?php include('includes/header.php'); ?>


        <div class="main-content">
            <div class="d-flex justify-content-end mb-4">
                <a href="manage_collection.php" class="btn custom-view-btn">
                    <i class="fas fa-list"></i> View All Records
                </a>
            </div>

            <form method="POST" class="milk-form">
                <h2><i class="fas fa-faucet-drip"></i> Add Milk Collection Data</h2>
                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Select Farmer <span class="text-danger">*</span></label>
                        <select name="farmer" class="form-control" required>
                            <option value="">-- Select Farmer --</option>
                            <?php
                            $query = mysqli_query($con, "SELECT * FROM farmers ORDER BY name ASC");
                            while ($row = mysqli_fetch_array($query)) {
                                echo "<option value='{$row['id']}'>{$row['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Product Type <span class="text-danger">*</span></label>
                        <input type="text" name="product_type" class="form-control" placeholder="E.g. Cow Milk" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Milk Quantity (L) <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" step="0.01" class="form-control" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Milk Fat Content (%) <span class="text-danger">*</span></label>
                        <input type="number" name="fat" step="0.01" class="form-control" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Temperature (°C) <span class="text-danger">*</span></label>
                        <input type="number" name="temperature" step="0.1" class="form-control" required>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Payment (₹) <span class="text-danger">*</span></label>
                        <input type="number" name="payment" step="0.01" class="form-control" required>
                    </div>
                </div>

                <div class="form-group mt-4 text-end">
                    <button type="submit" name="submit" class="btn custom-submit-btn">
                        <i class="fas fa-save"></i> Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
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