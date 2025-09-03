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

// Generate report data
$report_data = [
    'total_collection' => 0,
    'total_payments' => 0,
    'total_records' => 0,
    'average_daily' => 0,
    'period' => $from_date && $to_date ? "$from_date to $to_date" : 'All Time',
];

if ($farmer_id) {
    $where_clause = "WHERE farmer_id='$farmer_id'";
    if ($from_date) $where_clause .= " AND date >= '$from_date'";
    if ($to_date) $where_clause .= " AND date <= '$to_date'";

    $report_query = mysqli_query($con, "SELECT SUM(quantity) as total_qty, SUM(payment) as total_pay, COUNT(*) as records, AVG(quantity) as avg_daily FROM milk_collection $where_clause");
    if ($report_query && $row = mysqli_fetch_assoc($report_query)) {
        $report_data['total_collection'] = $row['total_qty'] ? $row['total_qty'] : 0;
        $report_data['total_payments'] = $row['total_pay'] ? $row['total_pay'] : 0;
        $report_data['total_records'] = $row['records'];
        $report_data['average_daily'] = $row['avg_daily'] ? $row['avg_daily'] : 0;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Reports - Digital Dairy</title>
    <link rel="stylesheet" href="css/user_style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
        .report-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .summary-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .summary-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .summary-icon.milk { color: #3498db; }
        .summary-icon.payment { color: #e74c3c; }
        .summary-icon.records { color: #27ae60; }
        .summary-icon.avg { color: #f39c12; }
        .summary-content h3 {
            margin: 0;
            font-size: 1.5rem;
        }
        .summary-content p {
            margin: 5px 0 0;
            color: #666;
        }
        .report-actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include('includes/user_sidebar.php'); ?>
        <div class="main-content">
            <div class="page-title">
                <h1>Milk Collection Reports</h1>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2>Filter Report Period</h2>
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
                            <button type="submit" class="btn btn-primary" style="margin-top: 5px;">Generate Report</button>
                            <button type="button" onclick="window.location.href='user_report.php'" class="btn btn-secondary" style="margin-top: 5px; margin-left: 10px;">Clear Filter</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2>Report Summary - <?php echo htmlspecialchars($report_data['period']); ?></h2>
                </div>
                <div class="report-summary">
                    <div class="summary-card">
                        <div class="summary-icon milk">
                            <i class="fas fa-tint"></i>
                        </div>
                        <div class="summary-content">
                            <h3><?php echo number_format($report_data['total_collection'], 2); ?> L</h3>
                            <p>Total Milk Collection</p>
                        </div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-icon payment">
                            <i class="fas fa-rupee-sign"></i>
                        </div>
                        <div class="summary-content">
                            <h3>₹<?php echo number_format($report_data['total_payments'], 2); ?></h3>
                            <p>Total Payments</p>
                        </div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-icon records">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="summary-content">
                            <h3><?php echo number_format($report_data['total_records']); ?></h3>
                            <p>Total Records</p>
                        </div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-icon avg">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        <div class="summary-content">
                            <h3><?php echo number_format($report_data['average_daily'], 2); ?> L</h3>
                            <p>Average Daily Collection</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2>Report Actions</h2>
                </div>
                <div class="report-actions">
                    <button class="btn btn-primary" onclick="window.print()">Print Report</button>
                    <button class="btn btn-secondary">Export PDF</button>
                    <button class="btn btn-secondary">Export Excel</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
