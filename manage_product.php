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
    <title>Manage Products - Digital Dairy Management System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include('includes/sidebar.php'); ?>

        <div class="dashboard-main">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <div class="dashboard-title">
                    <h1>Manage Products</h1>
                    <p>View and manage all products in your inventory</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <?php echo date("D, M j, Y - h:i A"); ?>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <div class="action-info">
                    <h3><i class="fas fa-boxes"></i> Product Inventory</h3>
                    <p class="text-muted">Manage all products and their details</p>
                </div>
                <div class="action-buttons">
                    <a href="add_product.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                </div>
            </div>

            <!-- Products Table Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-table"></i> All Products</h3>
                    <p class="text-muted">Comprehensive list of all products in inventory</p>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="productTable">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> ID</th>
                                    <th><i class="fas fa-tag"></i> Product Name</th>
                                    <th><i class="fas fa-cubes"></i> Product Type</th>
                                    <th><i class="fas fa-rupee-sign"></i> Unit Price</th>
                                    <th><i class="fas fa-calendar"></i> Added On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM tblproduct ORDER BY ID DESC");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td>
                                            <div class="product-info">
                                                <div class="product-icon">
                                                    <i class="fas fa-box"></i>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($row['ProductName']); ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php echo htmlspecialchars($row['ProductType']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                <i class="fas fa-rupee-sign"></i>
                                                <?php echo number_format($row['UnitPrice'], 2); ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo date('M j, Y', strtotime($row['PostingDate'])); ?>
                                            </small>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
   

    <script>
        $(document).ready(function() {
            $('#productTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "Search products:",
                    lengthMenu: "Show _MENU_ products per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ products",
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                }
            });
        });
    </script>
</body>
</html>

<?php include('includes/footer.php'); ?>
