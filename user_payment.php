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

// Handle download report request
if (isset($_GET['download_report'])) {
    $user_email = $_SESSION['email'];
    $query = mysqli_query($con, "SELECT * FROM farmers WHERE email='$user_email'");
    if (!$query) {
        die("Query failed: " . mysqli_error($con));
    }
    if (mysqli_num_rows($query) == 0) {
        $farmer_uuid = null;
    } else {
        $farmer = mysqli_fetch_assoc($query);
        $farmer_uuid = $farmer['uuid'];
    }

    if ($farmer_uuid) {
        $where_clause = "WHERE farmer_uuid='$farmer_uuid'";
        if ($from_date) $where_clause .= " AND date >= '$from_date'";
        if ($to_date) $where_clause .= " AND date <= '$to_date'";
        $payments_query = mysqli_query($con, "SELECT date, payment FROM milk_collection $where_clause ORDER BY date DESC");

        // Prepare CSV
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="payment_report.csv"');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Date', 'Amount (₹)', 'Status']);
        while ($row = mysqli_fetch_assoc($payments_query)) {
            fputcsv($output, [date('d/m/Y', strtotime($row['date'])), number_format($row['payment'], 2), 'Paid']);
        }
        fclose($output);
        exit;
    }
}



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

// Fetch payments
$payments = [];
$total_payments = 0;
$total_records = 0;
if ($farmer_uuid) {
    $where_clause = "WHERE farmer_uuid='$farmer_uuid'";
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
        .status.paid {
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
                <h1>Payment History</h1>
                <form method="GET" style="display:inline;">
                    <input type="hidden" name="from-date" value="<?php echo htmlspecialchars($from_date); ?>" />
                    <input type="hidden" name="to-date" value="<?php echo htmlspecialchars($to_date); ?>" />
                    <button type="submit" name="download_report" value="1" class="btn btn-primary">Download Report</button>
                </form>
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
                    <form method="GET" style="display:inline;">
                        <input type="hidden" name="export_pdf" value="1" />
                        <input type="hidden" name="from-date" value="<?php echo htmlspecialchars($from_date); ?>" />
                        <input type="hidden" name="to-date" value="<?php echo htmlspecialchars($to_date); ?>" />
                        <button type="submit" class="btn btn-primary">Export PDF</button>
                    </form>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

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

            // Print button functionality
            $('.btn.btn-secondary').click(function() {
                window.print();
            });

            // Export PDF button functionality
            $('.btn.btn-primary').filter(function() {
                return $(this).text().trim() === 'Export PDF';
            }).click(function(e) {
                e.preventDefault();
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                doc.text("Payment History Report", 14, 20);
                const fromDate = $('#from-date').val();
                const toDate = $('#to-date').val();
                let periodText = 'All Time';
                if (fromDate && toDate) {
                    periodText = `Period: ${fromDate} to ${toDate}`;
                }
                doc.text(periodText, 14, 30);

                const rows = [];
                $('#paymentTable tbody tr').each(function() {
                    const row = [];
                    $(this).find('td').each(function() {
                        row.push($(this).text().trim());
                    });
                    if (row.length > 0) {
                        rows.push(row);
                    }
                });

                doc.autoTable({
                    head: [['Date', 'Amount (₹)', 'Status']],
                    body: rows,
                    startY: 40,
                });

                doc.save('payment_report.pdf');
            });
        });
    </script>
</body>
</html>
