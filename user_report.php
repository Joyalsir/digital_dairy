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
    $farmer_uuid = null;
    $farmer_name = $_SESSION['name'];
} else {
    $farmer = mysqli_fetch_assoc($query);
    $farmer_uuid = $farmer['uuid'];
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

if ($farmer_uuid) {
    $where_clause = "WHERE farmer_uuid='$farmer_uuid'";
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
        .report-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }
        .summary-card {
            background: #fff;
            padding: 25px 20px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: default;
        }
        .summary-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        .summary-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            transition: color 0.3s ease;
        }
        .summary-icon.milk { color: #3f51b5; }
        .summary-icon.payment { color: #e91e63; }
        .summary-icon.records { color: #4caf50; }
        .summary-icon.avg { color: #f39c12; }
        .summary-content h3 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
            color: #222;
        }
        .summary-content p {
            margin: 5px 0 0;
            color: #666;
            font-size: 1rem;
            letter-spacing: 0.02em;
        }
        .report-actions {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .report-actions .btn {
            padding: 12px 24px;
            font-size: 1rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
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
                <button class="btn btn-secondary" id="exportPdfBtn">Export PDF</button>
                <button class="btn btn-secondary" id="exportExcelBtn">Export Excel</button>
            </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <script>
        document.getElementById('exportPdfBtn').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.text("Milk Collection Report", 14, 20);
            const periodText = "<?php echo htmlspecialchars($report_data['period']); ?>";
            doc.text("Period: " + periodText, 14, 30);

            const head = [['Metric', 'Value']];
            const body = [
                ['Total Milk Collection (L)', '<?php echo number_format($report_data['total_collection'], 2); ?>'],
                ['Total Payments (₹)', '₹<?php echo number_format($report_data['total_payments'], 2); ?>'],
                ['Total Records', '<?php echo number_format($report_data['total_records']); ?>'],
                ['Average Daily Collection (L)', '<?php echo number_format($report_data['average_daily'], 2); ?>']
            ];

            doc.autoTable({
                head: head,
                body: body,
                startY: 40,
            });

            doc.save('milk_collection_report.pdf');
        });

        document.getElementById('exportExcelBtn').addEventListener('click', function() {
            const wb = XLSX.utils.book_new();
            const ws_data = [
                ['Metric', 'Value'],
                ['Total Milk Collection (L)', '<?php echo number_format($report_data['total_collection'], 2); ?>'],
                ['Total Payments (₹)', '<?php echo number_format($report_data['total_payments'], 2); ?>'],
                ['Total Records', '<?php echo number_format($report_data['total_records']); ?>'],
                ['Average Daily Collection (L)', '<?php echo number_format($report_data['average_daily'], 2); ?>']
            ];
            const ws = XLSX.utils.aoa_to_sheet(ws_data);
            XLSX.utils.book_append_sheet(wb, ws, "Report");
            XLSX.writeFile(wb, "milk_collection_report.xlsx");
        });
    </script>
</body>
</html>
