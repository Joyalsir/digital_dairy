<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
include('includes/header.php');
include('includes/config.php');

// Handle status update
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $action = $_GET['action'];

    if ($action == 'activate') {
        mysqli_query($con, "UPDATE tblcustomer SET status='active' WHERE id='$id'");
        $success_msg = "Customer activated successfully!";
    } elseif ($action == 'deactivate') {
        mysqli_query($con, "UPDATE tblcustomer SET status='inactive' WHERE id='$id'");
        $success_msg = "Customer deactivated successfully!";
    }
}

// Handle delete customer
if (isset($_GET['delete']) && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    mysqli_query($con, "DELETE FROM tblcustomer WHERE id='$id'");
    $success_msg = "Customer deleted successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers - Digital Dairy Management System</title>
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
                    <h1>Manage Customers</h1>
                    <p>View and manage all registered customers</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <span id="currentDateTime"></span>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <div class="action-info">
                    <h3><i class="fas fa-users"></i> Customer Management</h3>
                    <p class="text-muted">Manage customer accounts and information</p>
                </div>
                <div class="action-buttons">
                    <a href="customer_reg.php" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Add New Customer
                    </a>
                    <a href="customer_report.php" class="btn btn-outline-primary">
                        <i class="fas fa-chart-bar"></i> Customer Report
                    </a>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($success_msg)): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i> <?php echo $success_msg; ?>
                </div>
            <?php endif; ?>

            <!-- Customer Stats -->
            <div class="stats-grid" style="margin-bottom: 2rem;">
                <div class="stat-card">
                    <div class="stat-icon farmers">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as total FROM tblcustomer");
                        $total_customers = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $total_customers = $row['total'];
                        }
                        ?>
                        <h3><?php echo number_format($total_customers); ?></h3>
                        <p>Total Customers</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon deliveries">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as total FROM tblcustomer WHERE status='active'");
                        $active_customers = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $active_customers = $row['total'];
                        }
                        ?>
                        <h3><?php echo number_format($active_customers); ?></h3>
                        <p>Active Customers</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon milk">
                        <i class="fas fa-calendar-plus"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as total FROM tblcustomer WHERE DATE(registration_date) = CURDATE()");
                        $today_registrations = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $today_registrations = $row['total'];
                        }
                        ?>
                        <h3><?php echo number_format($today_registrations); ?></h3>
                        <p>Today's Registrations</p>
                    </div>
                </div>
            </div>

            <!-- Customers Table -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-table"></i> Customer List</h3>
                    <p class="text-muted">All registered customers in the system</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th><i class="fas fa-hashtag"></i> ID</th>
                                <th><i class="fas fa-user"></i> Name</th>
                                <th><i class="fas fa-phone"></i> Contact</th>
                                <th><i class="fas fa-envelope"></i> Email</th>
                                <th><i class="fas fa-map-marker-alt"></i> Address</th>
                                <th><i class="fas fa-calendar"></i> Reg. Date</th>
                                <th><i class="fas fa-toggle-on"></i> Status</th>
                                <th><i class="fas fa-cogs"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($con, "SELECT * FROM tblcustomer ORDER BY registration_date DESC");
                            while ($row = mysqli_fetch_assoc($query)) {
                                $status_badge = $row['status'] == 'active'
                                    ? '<span class="badge bg-success">Active</span>'
                                    : '<span class="badge bg-secondary">Inactive</span>';
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['customer_name']); ?></strong>
                                </td>
                                <td><?php echo htmlspecialchars($row['contact']); ?></td>
                                <td><?php echo htmlspecialchars($row['email'] ?: 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars(substr($row['address'], 0, 30) . (strlen($row['address']) > 30 ? '...' : '')); ?></td>
                                <td><?php echo date('d M Y', strtotime($row['registration_date'])); ?></td>
                                <td><?php echo $status_badge; ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <?php if ($row['status'] == 'active'): ?>
                                            <a href="?action=deactivate&id=<?php echo $row['id']; ?>"
                                               class="btn btn-outline-warning btn-sm"
                                               title="Deactivate Customer">
                                                <i class="fas fa-toggle-off"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="?action=activate&id=<?php echo $row['id']; ?>"
                                               class="btn btn-outline-success btn-sm"
                                               title="Activate Customer">
                                                <i class="fas fa-toggle-on"></i>
                                            </a>
                                        <?php endif; ?>

                                        <button class="btn btn-outline-info btn-sm"
                                                title="View Details"
                                                onclick="viewCustomer(<?php echo $row['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <a href="?delete&id=<?php echo $row['id']; ?>"
                                           class="btn btn-outline-danger btn-sm"
                                           title="Delete Customer"
                                           onclick="return confirm('Are you sure you want to delete this customer?')">
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
    </div>

    <!-- Customer Details Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user"></i> Customer Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="customerDetails">
                    <!-- Customer details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <style>
    .success-message {
        background: #d1fae5;
        color: #065f46;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #10b981;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table th {
        font-weight: 600;
        border: none;
    }

    .table td {
        vertical-align: middle;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .stat-card {
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
    }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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

    function viewCustomer(customerId) {
        // This would typically make an AJAX call to get customer details
        // For now, we'll show a placeholder
        const modal = new bootstrap.Modal(document.getElementById('customerModal'));
        document.getElementById('customerDetails').innerHTML = `
            <div class="text-center">
                <i class="fas fa-user-circle fa-4x text-muted mb-3"></i>
                <h4>Customer ID: ${customerId}</h4>
                <p class="text-muted">Detailed customer information would be loaded here.</p>
                <p class="text-muted">This would typically include complete address, registration details, order history, etc.</p>
            </div>
        `;
        modal.show();
    }

    // Auto-refresh page every 30 seconds to show new registrations
    setTimeout(function() {
        location.reload();
    }, 30000);
    </script>
</body>
</html>

<?php include('includes/footer.php'); ?>
