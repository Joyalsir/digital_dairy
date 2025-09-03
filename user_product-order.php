<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}
include('includes/config.php');

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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Product Orders - Digital Dairy</title>
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
        .product-info {
            display: flex;
            align-items: center;
        }
        .product-icon {
            margin-right: 10px;
            color: #3b82f6;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .bg-info {
            background-color: #0ea5e9;
            color: white;
        }
        .text-success {
            color: #16a34a;
        }
        .text-muted {
            color: #6b7280;
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
        .stat-icon.milk { color: #3498db; }
        .stat-icon.success { color: #27ae60; }
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
                <h1>Product Orders</h1>
                <button class="btn btn-primary">Download Report</button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2>Available Products</h2>
                    <p class="text-muted">Browse and order products from our inventory</p>
                </div>
                <div class="table-responsive">
                    <table id="productTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Product Type</th>
                                <th>Unit Price</th>
                                <th>Added On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    <tbody>
                        <?php
                        $ret = mysqli_query($con, "SELECT * FROM tblproduct ORDER BY ID DESC");
                        $cnt = 1;
                        while ($row = mysqli_fetch_array($ret)) {
                        ?>
                            <tr>
                                <td><?php echo $cnt++; ?></td>
                                <td>
                                    <div class="product-info">
                                        <div class="product-icon">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div>
                                            <strong><?php echo htmlspecialchars($row['ProductName']); ?></strong>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php echo htmlspecialchars($row['ProductType']); ?>
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-success">
                                        <i class="fas fa-rupee-sign"></i>
                                        <?php echo number_format($row['UnitPrice'], 2); ?>
                                    </strong>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('M j, Y', strtotime($row['PostingDate'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <button class="btn btn-primary">Order</button>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    </table>
                </div>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as total FROM tblproduct");
                        $total_products = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $total_products = $row['total'];
                        }
                        ?>
                        <h3><?php echo number_format($total_products); ?></h3>
                        <p>Total Products</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon milk">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT AVG(UnitPrice) as avg_price FROM tblproduct");
                        $avg_price = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $avg_price = $row['avg_price'] ? $row['avg_price'] : 0;
                        }
                        ?>
                        <h3>₹<?php echo number_format($avg_price, 2); ?></h3>
                        <p>Average Price</p>
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
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#productTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "Search products:",
                    lengthMenu: "Show _MENU_ products per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ products",
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
