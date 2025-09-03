<?php
session_start();
include('includes/config.php'); // DB connection

// Fetch records based on date filter
$where = "";
if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != "" && $_POST['to_date'] != "") {
    $from = date('Y-m-d', strtotime($_POST['from_date']));
    $to   = date('Y-m-d', strtotime($_POST['to_date']));
    $where = "WHERE date BETWEEN '$from' AND '$to'";
}

$query = mysqli_query($con, "SELECT * FROM inventory $where ORDER BY date DESC");
?>
<?php


// Fetch records based on date filter
$where = "";
if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != "" && $_POST['to_date'] != "") {
    $from = date('Y-m-d', strtotime($_POST['from_date']));
    $to   = date('Y-m-d', strtotime($_POST['to_date']));
    $where = "WHERE date BETWEEN '$from' AND '$to'";
}

$query = mysqli_query($con, "SELECT * FROM inventory $where ORDER BY date DESC");
?>
<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>



<div class="inventory-report-container">
    <div class="container-fluid px-4">
        <div class="report-header">
            <h1 class="report-title">
                <i class="fas fa-boxes me-3"></i>
                Inventory Report
            </h1>
            <p class="mb-0">Comprehensive inventory tracking and analysis</p>
        </div>

        <div class="search-form-card">
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i>
                        From Date
                    </label>
                    <input type="date" id="from_date" name="from_date" class="form-control"
                           value="<?php if(isset($_POST['from_date'])) echo $_POST['from_date']; ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i>
                        To Date
                    </label>
                    <input type="date" id="to_date" name="to_date" class="form-control"
                           value="<?php if(isset($_POST['to_date'])) echo $_POST['to_date']; ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>
                        Search
                    </button>
                    <a href="inventory_report.php" class="btn btn-secondary">
                        <i class="fas fa-refresh me-2"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="inventoryTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>Serial</th>
                                <th><i class="fas fa-calendar me-2"></i>Date</th>
                                <th><i class="fas fa-user me-2"></i>Farmer</th>
                                <th><i class="fas fa-box me-2"></i>Product Type</th>
                                <th><i class="fas fa-tint me-2"></i>Milk Quantity (L)</th>
                                <th><i class="fas fa-percentage me-2"></i>Fat Content (%)</th>
                                <th><i class="fas fa-thermometer-half me-2"></i>Temperature (°C)</th>
                                <th><i class="fas fa-dollar-sign me-2"></i>Payment</th>
                                <th><i class="fas fa-calculator me-2"></i>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            $totalQuantity = 0;
                            $totalPayment = 0;
                            $totalAmount = 0;
                            
                            if (mysqli_num_rows($query) > 0) {
                                while($row = mysqli_fetch_assoc($query)) {
                                    $totalQuantity += $row['milk_quantity'];
                                    $totalPayment += $row['payment'];
                                    $totalAmount += $row['total'];
                                    ?>
                                    <tr>
                                        <td><span class="badge bg-primary"><?php echo $i++; ?></span></td>
                                        <td><strong><?php echo date('d M Y', strtotime($row['date'])); ?></strong></td>
                                        <td><span class="text-primary fw-bold"><?php echo $row['farmer']; ?></span></td>
                                        <td><span class="badge bg-info"><?php echo $row['product_type']; ?></span></td>
                                        <td><span class="badge bg-success"><?php echo $row['milk_quantity']; ?> L</span></td>
                                        <td><span class="badge bg-warning text-dark"><?php echo $row['fat_content']; ?>%</span></td>
                                        <td><span class="badge bg-secondary"><?php echo $row['temperature']; ?>°C</span></td>
                                        <td><span class="badge bg-success">₹<?php echo $row['payment']; ?></span></td>
                                        <td><span class="badge bg-primary">₹<?php echo $row['total']; ?></span></td>
                                    </tr>
                                <?php } ?>
                                
                                <!-- Summary Row -->
                                <tr class="summary-row">
                                    <td colspan="4" class="text-end"><strong>TOTALS:</strong></td>
                                    <td><span class="badge bg-light text-dark"><?php echo $totalQuantity; ?> L</span></td>
                                    <td colspan="2"></td>
                                    <td><span class="badge bg-light text-dark">₹<?php echo $totalPayment; ?></span></td>
                                    <td><span class="badge bg-light text-dark">₹<?php echo $totalAmount; ?></span></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="9" class="empty-state">
                                        <i class="fas fa-inbox"></i>
                                        <h5>No records found</h5>
                                        <p>Try adjusting your date range</p>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- DataTables & Export Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    $('#inventoryTable').DataTable({
        dom: '<"d-flex justify-content-between align-items-center mb-3"lfB>rtip',
        buttons: [
            {
                extend: 'copy',
                className: 'btn btn-outline-primary btn-sm',
                text: '<i class="fas fa-copy"></i> Copy'
            },
            {
                extend: 'excel',
                className: 'btn btn-outline-success btn-sm',
                text: '<i class="fas fa-file-excel"></i> Excel'
            },
            {
                extend: 'pdf',
                className: 'btn btn-outline-danger btn-sm',
                text: '<i class="fas fa-file-pdf"></i> PDF'
            },
            {
                extend: 'print',
                className: 'btn btn-outline-info btn-sm',
                text: '<i class="fas fa-print"></i> Print'
            }
        ],
        language: {
            search: '<i class="fas fa-search"></i>',
            searchPlaceholder: 'Search records...',
            lengthMenu: 'Show _MENU_ records per page'
        },
        responsive: true,
        pageLength: 25,
        order: [[1, 'desc']]
    });
});
</script>

<?php include("includes/footer.php"); ?>
