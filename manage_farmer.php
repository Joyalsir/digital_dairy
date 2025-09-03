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
    <title>Manage Farmers - Digital Dairy Management System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include('includes/sidebar.php'); ?>

        <div class="dashboard-main">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <div class="dashboard-title">
                    <h1>Manage Farmers</h1>
                    <p>View, edit, and manage all registered farmers in the system</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <?php echo date("D, M j, Y - h:i A"); ?>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <div class="action-info">
                    <h3><i class="fas fa-users"></i> Farmer Management</h3>
                    <p class="text-muted">Manage all farmer records and their details</p>
                </div>
                <div class="action-buttons">
                    <a href="add_farmer.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Add New Farmer
                    </a>
                </div>
            </div>

            <!-- Farmers Table Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-table"></i> All Farmers</h3>
                    <p class="text-muted">Comprehensive list of all registered farmers</p>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="farmerTable">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> ID</th>
                                    <th><i class="fas fa-user"></i> Name</th>
                                    <th><i class="fas fa-phone"></i> Contact</th>
                                    <th><i class="fas fa-map-marker-alt"></i> Address</th>
                                    <th><i class="fas fa-tractor"></i> Farm Size</th>
                                    <th><i class="fas fa-envelope"></i> Email</th>
                                    <th><i class="fas fa-calendar"></i> Registered</th>
                                    <th><i class="fas fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM farmers ORDER BY created_at DESC");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <i class="fas fa-user-circle"></i>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                                                    <br>
                                                    <small class="text-muted">Aadhar: <?php echo htmlspecialchars($row['aadhar']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <i class="fas fa-phone text-success"></i>
                                            <?php echo htmlspecialchars($row['contact']); ?>
                                        </td>
                                        <td>
                                            <i class="fas fa-map-marker-alt text-info"></i>
                                            <?php echo htmlspecialchars(substr($row['address'], 0, 50)); ?>...
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?php echo htmlspecialchars($row['farm_size']); ?> acres
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fas fa-envelope text-warning"></i>
                                            <?php echo htmlspecialchars($row['email']); ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo date('M j, Y', strtotime($row['created_at'])); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="action-buttons-cell">
                                                <a href="view-farmer.php?id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-sm btn-info" 
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="edit-farmer.php?id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Edit Farmer">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="delete-farmer.php?id=<?php echo $row['id']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this farmer?');" 
                                                   title="Delete Farmer">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
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
                        <p>Total Farmers</p>
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

                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as recent FROM farmers WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
                        $recent_farmers = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $recent_farmers = $row['recent'];
                        }
                        ?>
                        <h3><?php echo number_format($recent_farmers); ?></h3>
                        <p>New This Week</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    
 

    <script>
        $(document).ready(function() {
            $('#farmerTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "Search farmers:",
                    lengthMenu: "Show _MENU_ farmers per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ farmers",
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
