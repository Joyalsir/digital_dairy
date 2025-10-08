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
    <title>Customer Report - Digital Dairy Management System</title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <!-- Main Theme CSS -->
    <link rel="stylesheet" href="style.css">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<?php include('includes/sidebar.php'); ?>

<div class="content" id="mainContent">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-users"></i> Customer Report</h1>
        <p>Generate detailed customer reports with advanced filtering and export options</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-icon total">
                <i class="fas fa-users"></i>
            </div>
            <div class="summary-content">
                <h4 id="totalCustomers">0</h4>
                <p>Total Customers</p>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon active">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="summary-content">
                <h4 id="activeCustomers">0</h4>
                <p>Active Customers</p>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon inactive">
                <i class="fas fa-user-times"></i>
            </div>
            <div class="summary-content">
                <h4 id="inactiveCustomers">0</h4>
                <p>Inactive Customers</p>
            </div>
        </div>
        <div class="summary-card">
            <div class="summary-icon pending">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="summary-content">
                <h4 id="dateRange">-</h4>
                <p>Selected Date Range</p>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="form-container">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-filter"></i>
                Filter Customers
            </h3>
        </div>
        <form method="POST" class="form-row">
            <div class="form-group">
                <label for="from_date">
                    <i class="fas fa-calendar-start"></i>
                    From Date <span style="color: #ef4444;">*</span>
                </label>
                <input type="date" id="from_date" name="from_date" class="form-control" required
                       value="<?php echo isset($_POST['from_date']) ? $_POST['from_date'] : ''; ?>">
            </div>

            <div class="form-group">
                <label for="to_date">
                    <i class="fas fa-calendar-end"></i>
                    To Date <span style="color: #ef4444;">*</span>
                </label>
                <input type="date" id="to_date" name="to_date" class="form-control" required
                       value="<?php echo isset($_POST['to_date']) ? $_POST['to_date'] : ''; ?>">
            </div>

            <div class="form-group">
                <label for="status">
                    <i class="fas fa-toggle-on"></i>
                    Status
                </label>
                <select id="status" name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="active" <?php echo (isset($_POST['status']) && $_POST['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo (isset($_POST['status']) && $_POST['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <button type="submit" name="search" class="btn btn-primary">
                <i class="fas fa-search"></i>
                Search
            </button>
        </form>
    </div>

    <!-- Results Table -->
    <div class="table-container" id="resultsContainer" style="display: <?php echo isset($_POST['search']) ? 'block' : 'none'; ?>;">
        <div class="table-header">
            <h3><i class="fas fa-table"></i> Customer Details</h3>
        </div>

        <div style="padding: 0 30px 30px 30px;">
            <table id="customerTable" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> Serial</th>
                        <th><i class="fas fa-id-card"></i> ID</th>
                        <th><i class="fas fa-user"></i> Name</th>
                        <th><i class="fas fa-phone"></i> Contact</th>
                        <th><i class="fas fa-envelope"></i> Email</th>
                        <th><i class="fas fa-map-marker-alt"></i> Address</th>
                        <th><i class="fas fa-calendar"></i> Reg. Date</th>
                        <th><i class="fas fa-toggle-on"></i> Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalCustomers = 0;
                    $activeCustomers = 0;
                    $inactiveCustomers = 0;

                    if(isset($_POST['search'])){
                        $from = $_POST['from_date'];
                        $to = $_POST['to_date'];
                        $status = isset($_POST['status']) ? $_POST['status'] : '';

                        $sql = "SELECT * FROM tblcustomer WHERE registration_date BETWEEN '$from' AND '$to'";

                        if(!empty($status)) {
                            $sql .= " AND status = '$status'";
                        }

                        $sql .= " ORDER BY registration_date DESC";

                        $res = mysqli_query($con, $sql);

                        if (!$res) {
                            die("SQL Error: " . mysqli_error($con) . "<br>Query: " . $sql);
                        }

                        $serial = 1;

                        while($row = mysqli_fetch_assoc($res)){
                            $totalCustomers++;
                            if($row['status'] == 'active') {
                                $activeCustomers++;
                            } else {
                                $inactiveCustomers++;
                            }

                            $status_badge = $row['status'] == 'active'
                                ? '<span class="badge bg-success">Active</span>'
                                : '<span class="badge bg-secondary">Inactive</span>';

                            echo "<tr>
                                    <td>".$serial++."</td>
                                    <td>".$row['id']."</td>
                                    <td><strong class=\"customer-name\">".htmlspecialchars($row['customer_name'])."</strong></td>
                                    <td>".htmlspecialchars($row['contact'])."</td>
                                    <td>".htmlspecialchars($row['email'] ?: 'N/A')."</td>
                                    <td>".htmlspecialchars(substr($row['address'], 0, 30) . (strlen($row['address']) > 30 ? '...' : ''))."</td>
                                    <td>".date('d M Y', strtotime($row['registration_date']))."</td>
                                    <td>".$status_badge."</td>
                                </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-color: #667eea;
        --secondary-color: #764ba2;
        --success-color: #10b981;
        --warning-color: #f59e0b;
        --danger-color: #ef4444;
        --gray-100: #f3f4f6;
        --gray-200: #e5e7eb;
        --gray-300: #d1d5db;
        --gray-600: #4b5563;
        --gray-800: #1f2937;
        --white: #ffffff;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
    }

    .content {
        margin-left: 0;
        padding: 30px;
        transition: all 0.3s ease;
        min-height: 100vh;
        width: 100%;
    }

    .content.collapsed {
        margin-left: 0;
    }

    /* Page Header */
    .page-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 30px;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: var(--shadow-lg);
    }

    .page-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }

    .page-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 16px;
    }

    /* Enhanced Card Design */
    .card {
        background: var(--white);
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        padding: 30px;
        margin-bottom: 25px;
        border: 1px solid var(--gray-200);
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: var(--shadow-xl);
        transform: translateY(-2px);
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--gray-100);
    }

    .card-title {
        font-size: 20px;
        font-weight: 600;
        color: var(--gray-800);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-title i {
        color: var(--primary-color);
    }

    /* Enhanced Form Styling */
    .form-container {
        background: var(--white);
        border-radius: 16px;
        padding: 30px;
        box-shadow: var(--shadow-md);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr auto;
        gap: 20px;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-weight: 600;
        color: var(--gray-800);
        margin-bottom: 8px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-group label i {
        color: var(--primary-color);
        font-size: 14px;
    }

    .form-control {
        padding: 12px 16px;
        border: 2px solid var(--gray-200);
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: var(--gray-100);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        background: var(--white);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Enhanced Button Styling */
    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    /* Enhanced Table Styling */
    .table-container {
        background: var(--white);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
    }

    .table-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 20px 30px;
    }

    .table-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper {
        padding: 0;
        overflow-x: auto;
    }

    #customerTable {
        margin: 0;
        border-radius: 0;
        min-width: 900px;
    }

    #customerTable thead th {
        background: var(--gray-100);
        color: var(--gray-800);
        font-weight: 600;
        padding: 16px;
        border-bottom: 2px solid var(--gray-200);
        font-size: 14px;
        white-space: nowrap;
    }

    #customerTable tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--gray-200);
        font-size: 14px;
    }

    #customerTable tbody tr:hover {
        background: rgba(102, 126, 234, 0.05);
    }

    #customerTable tbody tr:nth-child(even) {
        background: rgba(249, 250, 251, 0.5);
    }

    .customer-name {
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge.bg-success {
        background: linear-gradient(135deg, var(--success-color), #059669) !important;
    }

    .badge.bg-secondary {
        background: linear-gradient(135deg, var(--gray-600), #374151) !important;
    }

    /* Summary Cards */
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: var(--white);
        padding: 25px;
        border-radius: 16px;
        box-shadow: var(--shadow-md);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
    }

    .summary-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    .summary-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .summary-icon.total {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }

    .summary-icon.active {
        background: linear-gradient(135deg, var(--success-color), #059669);
    }

    .summary-icon.inactive {
        background: linear-gradient(135deg, var(--gray-600), #374151);
    }

    .summary-icon.pending {
        background: linear-gradient(135deg, var(--warning-color), #d97706);
    }

    .summary-content h4 {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 5px 0;
        color: var(--gray-800);
    }

    .summary-content p {
        margin: 0;
        color: var(--gray-600);
        font-size: 14px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .content {
            margin-left: 0;
            padding: 20px;
        }

        .content.collapsed {
            margin-left: 0;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .summary-cards {
            grid-template-columns: 1fr;
        }

        .page-header {
            padding: 20px;
        }

        .page-header h1 {
            font-size: 24px;
        }
    }

    @media (max-width: 480px) {
        .content {
            padding: 15px;
        }

        .card {
            padding: 20px;
        }

        .page-header {
            padding: 15px;
        }
    }
</style>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with enhanced options
    var table = $('#customerTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                text: '<i class="fas fa-copy"></i> Copy',
                className: 'btn btn-primary'
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-primary',
                orientation: 'landscape',
                pageSize: 'A4'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-primary'
            }
        ],
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        language: {
            search: "<i class='fas fa-search'></i>",
            searchPlaceholder: "Search customers...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ customers",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        order: [[6, 'desc']],
        columnDefs: [
            { targets: [0], width: '5%' },
            { targets: [1], width: '8%' }
        ]
    });

    // Update summary cards
    <?php if(isset($_POST['search'])): ?>
    $('#totalCustomers').text('<?php echo $totalCustomers; ?>');
    $('#activeCustomers').text('<?php echo $activeCustomers; ?>');
    $('#inactiveCustomers').text('<?php echo $inactiveCustomers; ?>');
    $('#dateRange').text('<?php echo date('d M Y', strtotime($_POST['from_date'])) . " - " . date('d M Y', strtotime($_POST['to_date'])); ?>');
    <?php endif; ?>

    // Sidebar collapse toggle
    document.querySelector('.sidebar-toggle')?.addEventListener('click', function(){
        document.getElementById("mainContent").classList.toggle("collapsed");
    });

    // Set today's date as default for "to" date
    if (!$('#to_date').val()) {
        $('#to_date').val(new Date().toISOString().split('T')[0]);
    }

    // Set 30 days ago as default for "from" date
    if (!$('#from_date').val()) {
        var thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
        $('#from_date').val(thirtyDaysAgo.toISOString().split('T')[0]);
    }
});
</script>

</body>
</html>

<?php include('includes/footer.php'); ?>
