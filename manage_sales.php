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
    <title>Manage Sales - Digital Dairy Management System</title>
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
                    <h1>Manage Sales</h1>
                    <p>View and manage all sales records</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <?php echo date("D, M j, Y - h:i A"); ?>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <div class="action-info">
                    <h3><i class="fas fa-shopping-cart"></i> Sales Management</h3>
                    <p class="text-muted">Manage all sales records and their details</p>
                </div>
                <div class="action-buttons">
                    <a href="add_sales.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Sale
                    </a>
                </div>
            </div>

            <!-- Sales Table Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-list-alt"></i> All Sales Records</h3>
                    <p class="text-muted">Comprehensive list of all sales transactions</p>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="salesTable">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> ID</th>
                                    <th><i class="fas fa-file-invoice"></i> Invoice No</th>
                                    <th><i class="fas fa-user"></i> Customer Name</th>
                                    <th><i class="fas fa-phone"></i> Contact</th>
                                    <th><i class="fas fa-box"></i> Product</th>
                                    <th><i class="fas fa-weight"></i> Quantity</th>
                                    <th><i class="fas fa-rupee-sign"></i> Price</th>
                                    <th><i class="fas fa-rupee-sign"></i> Total</th>
                                    <th><i class="fas fa-calendar"></i> Sales Date</th>
                                    <th><i class="fas fa-calendar"></i> Posting Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($con, "SELECT * FROM tblsales ORDER BY ID DESC");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($query)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td><?php echo $row['InvoiceNumber']; ?></td>
                                        <td><?php echo $row['CustomerName']; ?></td>
                                        <td><?php echo $row['Contact']; ?></td>
                                        <td><?php echo $row['ProductName']; ?></td>
                                        <td><?php echo $row['Quantity']; ?></td>
                                        <td><?php echo number_format($row['Price'], 2); ?></td>
                                        <td><?php echo number_format($row['TotalAmount'], 2); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($row['SalesDate'])); ?></td>
                                        <td><?php echo date('M j, Y', strtotime($row['PostingDate'])); ?></td>
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

    

    <script>
        $(document).ready(function() {
            $('#salesTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "Search sales:",
                    lengthMenu: "Show _MENU_ sales per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ sales",
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
