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
    <title>Manage Milk Delivery - Digital Dairy Management System</title>
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
                    <h1>Manage Milk Delivery</h1>
                    <p>View and manage all delivery records</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <?php echo date("D, M j, Y - h:i A"); ?>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <div class="action-info">
                    <h3><i class="fas fa-truck"></i> Delivery Management</h3>
                    <p class="text-muted">Manage all delivery records and their details</p>
                </div>
                <div class="action-buttons">
                    <a href="add_delivery.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Delivery
                    </a>
                </div>
            </div>

            <!-- Delivery Table Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-list-alt"></i> All Deliveries</h3>
                    <p class="text-muted">Comprehensive list of all delivery records</p>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="deliveryTable">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> ID</th>
                                    <th><i class="fas fa-user"></i> Customer</th>
                                    <th><i class="fas fa-phone"></i> Contact</th>
                                    <th><i class="fas fa-map-marker-alt"></i> Address</th>
                                    <th><i class="fas fa-calendar"></i> Delivery Date</th>
                                    <th><i class="fas fa-box"></i> Product</th>
                                    <th><i class="fas fa-weight"></i> Quantity</th>
                                    <th><i class="fas fa-truck"></i> Vehicle</th>
                                    <th><i class="fas fa-user-tie"></i> Driver</th>
                                    <th><i class="fas fa-phone"></i> Driver Contact</th>
                                    <th><i class="fas fa-clock"></i> Posted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($con, "SELECT * FROM tbldelivery ORDER BY ID DESC");
                                $cnt = 1;
                                while($row = mysqli_fetch_array($query)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <i class="fas fa-user-circle"></i>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($row['CustomerName']); ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <i class="fas fa-phone text-success"></i>
                                            <?php echo htmlspecialchars($row['Contact']); ?>
                                        </td>
                                        <td>
                                            <i class="fas fa-map-marker-alt text-info"></i>
                                            <?php echo htmlspecialchars(substr($row['Address'], 0, 50)); ?>...
                                        </td>
                                        <td>
                                            <i class="fas fa-calendar text-primary"></i>
                                            <?php echo date('M j, Y', strtotime($row['DeliveryDate'])); ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php echo htmlspecialchars($row['ProductType']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?php echo htmlspecialchars($row['Quantity']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?php echo htmlspecialchars($row['VehicleNo']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($row['DriverName']); ?></strong>
                                        </td>
                                        <td>
                                            <i class="fas fa-phone text-warning"></i>
                                            <?php echo htmlspecialchars($row['DriverContact']); ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo date('M j, Y H:i', strtotime($row['PostingDate'])); ?>
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
                    <div class="stat-icon deliveries">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as total FROM tbldelivery");
                        $total_deliveries = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $total_deliveries = $row['total'];
                        }
                        ?>
                        <h3><?php echo number_format($total_deliveries); ?></h3>
                        <p>Total Deliveries</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as pending FROM tbldelivery WHERE DeliveryDate > CURDATE()");
                        $pending_deliveries = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $pending_deliveries = $row['pending'];
                        }
                        ?>
                        <h3><?php echo number_format($pending_deliveries); ?></h3>
                        <p>Upcoming Deliveries</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as today FROM tbldelivery WHERE DeliveryDate = CURDATE()");
                        $today_deliveries = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $today_deliveries = $row['today'];
                        }
                        ?>
                        <h3><?php echo number_format($today_deliveries); ?></h3>
                        <p>Today's Deliveries</p>
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
            $('#deliveryTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "Search deliveries:",
                    lengthMenu: "Show _MENU_ deliveries per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ deliveries",
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
