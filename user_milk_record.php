
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

// Calculate monthly summary
$monthly_summary = [
    'total_milk' => 0,
    'total_earnings' => 0,
    'average_per_day' => 0,
];
if ($farmer_id) {
    $month_query = mysqli_query($con, "SELECT SUM(quantity) as total_milk, SUM(payment) as total_earnings, AVG(quantity) as avg_per_day FROM milk_collection WHERE farmer_id='$farmer_id' AND MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())");
    if ($month_query && $month_data = mysqli_fetch_assoc($month_query)) {
        $monthly_summary['total_milk'] = $month_data['total_milk'] ?? 0;
        $monthly_summary['total_earnings'] = $month_data['total_earnings'] ?? 0;
        $monthly_summary['average_per_day'] = $month_data['avg_per_day'] ?? 0;
    }
}

// Fetch records
$records = [];
$total_collection = 0;
$total_payment = 0;
$total_records = 0;
if ($farmer_id) {
    $where_clause = "WHERE id='$farmer_id'";
    if ($from_date) $where_clause .= " AND date >= '$from_date'";
    if ($to_date) $where_clause .= " AND date <= '$to_date'";
    $records_query = mysqli_query($con, "SELECT date, quantity, payment, product_type, fat, temperature FROM milk_collection $where_clause ORDER BY date DESC");
    if (!$records_query) {
        die("Records query failed: " . mysqli_error($con));
    }
    while ($row = mysqli_fetch_assoc($records_query)) {
        $row['rate'] = $row['quantity'] > 0 ? $row['payment'] / $row['quantity'] : 0;
        $row['status'] = 'Completed';
        $records[] = $row;
    }
    $stats_query = mysqli_query($con, "SELECT SUM(quantity) as total_qty, SUM(payment) as total_pay, COUNT(*) as records FROM milk_collection $where_clause");
    if (!$stats_query) {
        die("Stats query failed: " . mysqli_error($con));
    }
    if ($row = mysqli_fetch_assoc($stats_query)) {
        $total_collection = $row['total_qty'] ? $row['total_qty'] : 0;
        $total_payment = $row['total_pay'] ? $row['total_pay'] : 0;
        $total_records = $row['records'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Milk Collection Records - Digital Dairy</title>
    <link rel="stylesheet" href="css/user_style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
    <style>
        /* Reset and base */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            background-color: #f5f7fa;
        }
        .main-content {
            flex: 1;
            padding: 30px 40px;
            background: #fff;
            margin-left: 280px;
            box-shadow: -2px 0 8px rgba(0,0,0,0.05);
            border-radius: 0 15px 15px 0;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        .page-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .page-title h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: #222;
        }
        .btn-primary {
            background-color: #3f51b5;
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #303f9f;
        }
        .btn-secondary {
            background-color: #6b7280;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            padding: 30px 40px;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .card-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: #222;
        }
        .table-responsive {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1rem;
            color: #444;
        }
        th, td {
            padding: 15px 18px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: 700;
            color: #555;
        }
        .status {
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.9rem;
            color: white;
            display: inline-block;
        }
        .status.completed {
            background-color: #16a34a;
        }
        .filter-group {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }
        .filter-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #333;
        }
        .filter-group input[type="date"] {
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            width: 220px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        .filter-group input[type="date"]:focus {
            border-color: #3f51b5;
            outline: none;
        }
        .stat-card {
            background: #fff;
            padding: 25px 20px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: default;
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            transition: color 0.3s ease;
        }
        .stat-icon.milk { color: #3f51b5; }
        .stat-icon.revenue { color: #e91e63; }
        .stat-icon.info { color: #4caf50; }
        .stat-content h3 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            color: #222;
        }
        .stat-content p {
            margin: 5px 0 0;
            color: #666;
            font-size: 1rem;
            letter-spacing: 0.02em;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include('includes/user_sidebar.php'); ?>
        <div class="main-content">
            <div class="page-title">
                <h1>Milk Collection Records</h1>
                <button class="btn btn-primary">Download Report</button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2>Filter Records</h2>
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
                            <button type="button" onclick="window.location.href='user_milk_record.php'" class="btn btn-secondary" style="margin-top: 5px; margin-left: 10px;">Clear Filter</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2>Milk Collection History</h2>
                    <div>
                        <button class="btn btn-secondary" style="margin-right: 10px;">Print</button>
                        <button class="btn btn-primary">Export PDF</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="milkTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product Type</th>
                                <th>Quantity (L)</th>
                                <th>Fat Content (%)</th>
                                <th>Temperature (°C)</th>
                                <th>Rate (₹/L)</th>
                                <th>Amount (₹)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    <tbody>
                        <?php if (empty($records)): ?>
                            <tr>
                                <td colspan="8" style="text-align:center;">No records found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($records as $record): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($record['date']))); ?></td>
                                    <td><?php echo htmlspecialchars($record['product_type']); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($record['quantity'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($record['fat'], 2)); ?></td>
                                    <td><?php echo htmlspecialchars(number_format($record['temperature'], 1)); ?></td>
                                    <td>₹<?php echo htmlspecialchars(number_format($record['rate'], 2)); ?></td>
                                    <td>₹<?php echo htmlspecialchars(number_format($record['payment'], 2)); ?></td>
                                    <td><span class="status <?php echo strtolower($record['status']); ?>"><?php echo htmlspecialchars($record['status']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    </table>
                </div>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon milk">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($total_collection, 2); ?> L</h3>
                        <p>Total Collection</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="stat-content">
                        <h3>₹<?php echo number_format($total_payment, 2); ?></h3>
                        <p>Total Payment</p>
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
            <div class="card">
                <div class="card-header">
                    <h2>Monthly Summary</h2>
                </div>
                <div style="padding: 20px;">
                    <div style="display: flex; justify-content: space-around; text-align: center; flex-wrap: wrap;">
                        <div>
                            <h3><?php echo number_format($monthly_summary['total_milk'], 2); ?> L</h3>
                            <p>Total Milk This Month</p>
                        </div>
                        <div>
                            <h3>₹<?php echo number_format($monthly_summary['total_earnings'], 2); ?></h3>
                            <p>Total Earnings</p>
                        </div>
                        <div>
                            <h3><?php echo number_format($monthly_summary['average_per_day'], 2); ?> L</h3>
                            <p>Average Per Day</p>
                        </div>
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
            $('#milkTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "Search records:",
                    lengthMenu: "Show _MENU_ records per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ records",
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