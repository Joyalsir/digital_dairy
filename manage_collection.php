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
    <title>Manage Milk Collection - Digital Dairy Management System</title>
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
                    <h1>Manage Milk Collection</h1>
                    <p>View and manage all milk collection records</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <span id="currentDateTime"></span>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <div class="action-info">
                    <h3><i class="fas fa-table"></i> Milk Collection Records</h3>
                    <p class="text-muted">Comprehensive list of all milk collection entries</p>
                </div>
                <div class="action-buttons">
                    <a href="add_collection.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Collection
                    </a>
                </div>
            </div>

            <!-- Collection Table Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-list-alt"></i> All Milk Collections</h3>
                    <p class="text-muted">View and search through all milk collection records</p>
                </div>
                
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="milkTable">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> ID</th>
                                    <th><i class="fas fa-calendar"></i> Date</th>
                                    <th><i class="fas fa-user"></i> Farmer</th>
                                    <th><i class="fas fa-tint"></i> Product Type</th>
                                    <th><i class="fas fa-weight"></i> Quantity</th>
                                    <th><i class="fas fa-percentage"></i> Fat Content</th>
                                    <th><i class="fas fa-thermometer-half"></i> Temperature</th>
                                    <th><i class="fas fa-rupee-sign"></i> Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($con, "SELECT mc.*, f.name as farmer_name
                                                            FROM milk_collection mc
                                                            JOIN farmers f ON mc.farmer_uuid = f.uuid
                                                            ORDER BY mc.date DESC");
                                $count = 1;
                                while($row = mysqli_fetch_assoc($query)) {
                                ?>
                                    <tr>
                                        <td><?php echo $count++; ?></td>
                                        <td>
                                            <i class="fas fa-calendar text-primary"></i>
                                            <?php echo date('M j, Y', strtotime($row['date'])); ?>
                                        </td>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    <i class="fas fa-user-circle"></i>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($row['farmer_name']); ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php echo htmlspecialchars($row['product_type']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <?php echo number_format($row['quantity'], 2); ?> L
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                <?php echo number_format($row['fat'], 2); ?>%
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?php echo number_format($row['temperature'], 1); ?>°C
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                <i class="fas fa-rupee-sign"></i>
                                                <?php echo number_format($row['payment'], 2); ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <a href="delete-collection.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" title="Delete Collection" onclick="return confirm('Are you sure you want to delete this collection record? This action cannot be undone.');">
                                                <i class="fas fa-trash"></i>
                                            </a>
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
                    <div class="stat-icon milk">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT SUM(quantity) as total FROM milk_collection");
                        $total_collection = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $total_collection = $row['total'] ? $row['total'] : 0;
                        }
                        ?>
                        <h3><?php echo number_format($total_collection, 2); ?> L</h3>
                        <p>Total Collection</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT SUM(payment) as total FROM milk_collection");
                        $total_payment = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $total_payment = $row['total'] ? $row['total'] : 0;
                        }
                        ?>
                        <h3>₹<?php echo number_format($total_payment, 2); ?></h3>
                        <p>Total Payment</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as records FROM milk_collection");
                        $total_records = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $total_records = $row['records'];
                        }
                        ?>
                        <h3><?php echo number_format($total_records); ?></h3>
                        <p>Total Records</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        </style>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    

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

        $(document).ready(function() {
            $('#milkTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "Search collections:",
                    lengthMenu: "Show _MENU_ records per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ collections",
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
