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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">

</head>
<body>
    <div class="dashboard-container" style="max-width: 100%; overflow-x: hidden;">
        <?php include('includes/sidebar.php'); ?>

        <div class="dashboard-main" style="width: 100%; max-width: 100%;">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <div class="dashboard-title">
                    <h1>Manage Sales</h1>
                    <p>View, edit, and manage all sales records in the system</p>
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
                    <p class="text-muted">Manage all sales records and customer transactions</p>
                </div>
                <div class="action-buttons">
                    <a href="add_sales.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Sale
                    </a>
                    <a href="sales_report.php" class="btn btn-outline-primary">
                        <i class="fas fa-chart-line"></i> View Reports
                    </a>
                 
                </div>
            </div>

            <!-- Sales Table Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-table"></i> All Sales Records</h3>
                    <p class="text-muted">Comprehensive list of all sales transactions</p>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="salesTable">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> ID</th>
                                    <th><i class="fas fa-file-invoice"></i> Invoice</th>
                                    <th><i class="fas fa-user"></i> Customer</th>
                                    <th><i class="fas fa-phone"></i> Contact</th>
                                    <th><i class="fas fa-box"></i> Product</th>
                                    <th><i class="fas fa-weight"></i> Quantity</th>
                                    <th><i class="fas fa-rupee-sign"></i> Price</th>
                                    <th><i class="fas fa-rupee-sign"></i> Total</th>
                                    <th><i class="fas fa-calendar"></i> Date</th>
                                    <th><i class="fas fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM tblsales ORDER BY SalesDate DESC");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($cnt++); ?></td>
                                        <td>
                                            <div class="invoice-info">
                                                <strong class="invoice-number">
                                                    <?php echo htmlspecialchars($row['InvoiceNumber']); ?>
                                                </strong>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="customer-info">
                                                <div class="customer-avatar">
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
                                            <span class="badge bg-info">
                                                <i class="fas fa-box"></i>
                                                <?php echo htmlspecialchars($row['ProductName']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?php echo htmlspecialchars(number_format($row['Quantity'], 2)); ?> units
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fas fa-rupee-sign text-warning"></i>
                                            <?php echo htmlspecialchars(number_format($row['Price'], 2)); ?>
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                <i class="fas fa-rupee-sign"></i>
                                                <?php echo htmlspecialchars(number_format($row['TotalAmount'], 2)); ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo date('M j, Y', strtotime($row['SalesDate'])); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="action-buttons-cell">
                                                <button class="btn btn-sm btn-info view-sale"
                                                        data-invoice="<?php echo $row['InvoiceNumber']; ?>"
                                                        title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-warning edit-sale"
                                                        data-invoice="<?php echo $row['InvoiceNumber']; ?>"
                                                        title="Edit Sale">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger delete-sale"
                                                        data-invoice="<?php echo $row['InvoiceNumber']; ?>"
                                                        onclick="return confirm('Are you sure you want to delete this sale record? This action cannot be undone.');"
                                                        title="Delete Sale">
                                                    <i class="fas fa-trash"></i>
                                                </button>
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
                    <div class="stat-icon sales">
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
                    <div class="stat-icon revenue">
                        <i class="fas fa-rupee-sign"></i>
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
                    <div class="stat-icon today">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as today FROM tblsales WHERE DATE(SalesDate) = CURDATE()");
                        $today_sales = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $today_sales = $row['today'];
                        }
                        ?>
                        <h3><?php echo number_format($today_sales); ?></h3>
                        <p>Today's Sales</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon month">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as month FROM tblsales WHERE SalesDate >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
                        $month_sales = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $month_sales = $row['month'];
                        }
                        ?>
                        <h3><?php echo number_format($month_sales); ?></h3>
                        <p>This Month</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sale Details Modal -->
    <div id="saleModal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-receipt"></i> Sale Details</h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body" id="saleDetails">
                <!-- Sale details will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

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
            // Initialize DataTable
            $('#salesTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[8, 'desc']], // Sort by date descending
                language: {
                    search: "Search sales:",
                    lengthMenu: "Show _MENU_ sales per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ sales",
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                },
                columnDefs: [
                    { targets: [5, 6, 7], className: 'text-right' }
                ]
            });

            // View Sale Details
            $('.view-sale').click(function() {
                const invoice = $(this).data('invoice');
                $.ajax({
                    url: 'get-sale-details.php',
                    method: 'GET',
                    data: { invoice: invoice },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            $('#saleDetails').html(`<p class="text-danger">${data.error}</p>`);
                        } else {
                            $('#saleDetails').html(`
                                <p><strong>Invoice Number:</strong> ${data.InvoiceNumber}</p>
                                <p><strong>Customer Name:</strong> ${data.CustomerName}</p>
                                <p><strong>Contact:</strong> ${data.Contact}</p>
                                <p><strong>Product:</strong> ${data.ProductName}</p>
                                <p><strong>Quantity:</strong> ${parseFloat(data.Quantity).toFixed(2)} units</p>
                                <p><strong>Price:</strong> ₹${parseFloat(data.Price).toFixed(2)}</p>
                                <p><strong>Total Amount:</strong> ₹${parseFloat(data.TotalAmount).toFixed(2)}</p>
                                <p><strong>Sales Date:</strong> ${new Date(data.SalesDate).toLocaleDateString()}</p>
                            `);
                        }
                        $('#saleModal').show();
                    },
                    error: function() {
                        $('#saleDetails').html('<p class="text-danger">Failed to fetch sale details.</p>');
                        $('#saleModal').show();
                    }
                });
            });

            // Edit Sale
            $('.edit-sale').click(function() {
                const invoice = $(this).data('invoice');
                window.location.href = `edit-sales.php?invoice=${invoice}`;
            });

            // Delete Sale
            $('.delete-sale').click(function() {
                const invoice = $(this).data('invoice');
                if (confirm(`Are you sure you want to delete sale record ${invoice}?`)) {
                    window.location.href = `delete-sales.php?invoice=${invoice}`;
                }
            });

            // Close Modal
            $('.close, .modal').click(function(e) {
                if (e.target === this) {
                    $('#saleModal').hide();
                }
            });
        });
    </script>

    <style>
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 0;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .modal-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .close {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            opacity: 0.7;
        }

        .modal-body {
            padding: 20px;
        }

        .invoice-info, .customer-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .customer-avatar, .user-avatar {
            color: #667eea;
            font-size: 18px;
        }

        .invoice-number {
            color: #667eea;
            font-family: monospace;
        }

        .action-buttons-cell {
            display: flex;
            gap: 5px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
        }

        .stat-icon.sales { background: linear-gradient(135deg, #667eea, #764ba2); }
        .stat-icon.revenue { background: linear-gradient(135deg, #10b981, #059669); }
        .stat-icon.today { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .stat-icon.month { background: linear-gradient(135deg, #ef4444, #dc2626); }

        .stat-content h3 {
            margin: 0 0 5px 0;
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
        }

        .stat-content p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
    </style>
</body>
</html>
