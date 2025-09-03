<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}
include('includes/config.php');

// Handle filter
$from_date = isset($_GET['from-date']) ? mysqli_real_escape_string($con, $_GET['from-date']) : '';
$to_date = isset($_GET['to-date']) ? mysqli_real_escape_string($con, $_GET['to-date']) : '';

// Get farmer details
$user_email = $_SESSION['email'];
$query = mysqli_query($con, "SELECT * FROM farmers WHERE email='$user_email'");
if (!$query) {
    die("Query failed: " . mysqli_error($con));
}
if (mysqli_num_rows($query) == 0) {
    $farmer_id = null;
    $farmer_name = $_SESSION['name'];
} else {
    $farmer = mysqli_fetch_assoc($query);
    $farmer_id = $farmer['id'];
    $farmer_name = $farmer['name'];
}

// Fetch payments
$payments = [];
$total_payments = 0;
$total_records = 0;
if ($farmer_id) {
    $where_clause = "WHERE farmer_id='$farmer_id'";
    if ($from_date) $where_clause .= " AND date >= '$from_date'";
    if ($to_date) $where_clause .= " AND date <= '$to_date'";
    $payments_query = mysqli_query($con, "SELECT date, payment FROM milk_collection $where_clause ORDER BY date DESC");
    while ($row = mysqli_fetch_assoc($payments_query)) {
        $row['status'] = 'Paid';
        $payments[] = $row;
    }
    $stats_query = mysqli_query($con, "SELECT SUM(payment) as total_pay, COUNT(*) as records FROM milk_collection $where_clause");
    if ($row = mysqli_fetch_assoc($stats_query)) {
        $total_payments = $row['total_pay'] ? $row['total_pay'] : 0;
        $total_records = $row['records'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Payment History - Digital Dairy</title>
    <link rel="stylesheet" href="css/user_style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <style>
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
            margin-left: 250px;
        }
        .page-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .page-title h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
        }
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            padding: 20px 30px;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .card-header h2 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
        }
        .btn-secondary {
            background-color: #6b7280;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        .table-responsive {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }
        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: 600;
        }
        .status {
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            color: white;
            display: inline-block;
        }
        .status.paid {
            background-color: #16a34a;
        }
        .filter-group {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .filter-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .filter-group input[type="date"] {
            padding: 8px 10px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            width: 200px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .stat-icon.revenue { color: #e74c3c; }
        .stat-icon.info { color: #27ae60; }
        .stat-content h3 {
            margin: 0;
            font-size: 1.5rem;
        }
        .stat-content p {
            margin: 5px 0 0;
            color: #666;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include('includes/user_sidebar.php'); ?>
        <div class="main-content">
            <div class="page-title">
                <h1>Payment History</h1>
                <button class="btn btn-primary">Download Report</button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2>Filter Payments</h2>
                </div>
                <form method="GET">
                    <div class="filter-group">
                        <div>
                            <label for="from-date">From Date</label>
                            <input type="date" id="from-date" name="from-date" value="<?php echo htmlspecialchars($from_date); ?>" />
                        </div>
                        <div>
                            <label for="to-date">To Date</label>
                            <input type="date" id="to-date" name="to-date" value="<?php echo htmlspecialchars($to_date); ?>" />
                        </div>
                        <div>
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary" style="margin-top: 5px;">Apply Filter</button>
                            <button type="button" onclick="window.location.href='user_payment.php'" class="btn btn-secondary" style="margin-top: 5px; margin-left: 10px;">Clear Filter</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2>Payment History</h2>
                    <div>
                        <button class="btn btn-secondary" style="margin-right: 10px;">Print</button>
                        <button class="btn btn-primary">Export PDF</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="paymentTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount (₹)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    <tbody>
                        <?php if (empty($payments)): ?>
                            <tr>
                                <td colspan="3" style="text-align:center;">No payments found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($payment['date']))); ?></td>
                                    <td>₹<?php echo htmlspecialchars(number_format($payment['payment'], 2)); ?></td>
                                    <td><span class="status <?php echo strtolower($payment['status']); ?>"><?php echo htmlspecialchars($payment['status']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    </table>
                </div>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="stat-content">
                        <h3>₹<?php echo number_format($total_payments, 2); ?></h3>
                        <p>Total Payments</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($total_records); ?></h3>
                        <p>Total Records</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#paymentTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "Search payments:",
                    lengthMenu: "Show _MENU_ records per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ payments",
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
