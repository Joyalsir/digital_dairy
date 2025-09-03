<?php
session_start();
include('includes/config.php'); // your DB connection

// Fetch records if date range is selected
$fromDate = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$toDate   = isset($_POST['to_date']) ? $_POST['to_date'] : '';

$query = "SELECT * FROM sales WHERE 1";
if ($fromDate && $toDate) {
    $query .= " AND delivery_date BETWEEN '$fromDate' AND '$toDate'";
}
$result = mysqli_query($con, $query);
?>
<?php


// Fetch records if date range is selected
$fromDate = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$toDate   = isset($_POST['to_date']) ? $_POST['to_date'] : '';

$query = "SELECT * FROM sales WHERE 1";
if ($fromDate && $toDate) {
    $query .= " AND delivery_date BETWEEN '$fromDate' AND '$toDate'";
}
$result = mysqli_query($con, $query);
?>
<?php include("includes/header.php"); ?>
<?php include("includes/sidebar.php"); ?>


<div class="sales-report-container">
    <div class="container-fluid px-4">
        <div class="report-header">
            <h1 class="report-title">
                <i class="fas fa-chart-line me-3"></i>
                Sales Report
            </h1>
            <p class="mb-0">Comprehensive sales tracking and analysis</p>
        </div>

        <div class="search-form-card">
            <form method="post" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i>
                        From Date
                    </label>
                    <input type="date" name="from_date" class="form-control" value="<?php echo $fromDate; ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i>
                        To Date
                    </label>
                    <input type="date" name="to_date" class="form-control" value="<?php echo $toDate; ?>" required>
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>
                        Search
                    </button>
                    <a href="sales_report.php" class="btn btn-secondary">
                        <i class="fas fa-refresh me-2"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="salesTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-hashtag me-2"></i>Serial</th>
                                <th><i class="fas fa-user me-2"></i>Customer Name</th>
                                <th><i class="fas fa-phone me-2"></i>Contact</th>
                                <th><i class="fas fa-map-marker-alt me-2"></i>Address</th>
                                <th><i class="fas fa-calendar me-2"></i>Delivery Date</th>
                                <th><i class="fas fa-box me-2"></i>Product Type</th>
                                <th><i class="fas fa-tint me-2"></i>Quantity</th>
                                <th><i class="fas fa-truck me-2"></i>Vehicle Number</th>
                                <th><i class="fas fa-user-tie me-2"></i>Driver Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            $totalQuantity = 0;
                            
                            if (mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_assoc($result)) {
                                    $totalQuantity += $row['quantity'];
                                    ?>
                                    <tr>
                                        <td><span class="badge bg-primary"><?php echo $i++; ?></span></td>
                                        <td><span class="text-primary fw-bold"><?php echo $row['customer_name']; ?></span></td>
                                        <td><?php echo $row['contact']; ?></td>
                                        <td><?php echo $row['address']; ?></td>
                                        <td><strong><?php echo date('d M Y', strtotime($row['delivery_date'])); ?></strong></td>
                                        <td><span class="badge bg-info"><?php echo $row['product_type']; ?></span></td>
                                        <td><span class="badge bg-success"><?php echo $row['quantity']; ?></span></td>
                                        <td><span class="badge bg-warning text-dark"><?php echo $row['vehicle_number']; ?></span></td>
                                        <td><span class="badge bg-secondary"><?php echo $row['driver_name']; ?></span></td>
                                    </tr>
                                <?php } ?>
                                
                                <!-- Summary Row -->
                                <tr class="summary-row">
                                    <td colspan="6" class="text-end"><strong>TOTAL:</strong></td>
                                    <td><span class="badge bg-light text-dark"><?php echo $totalQuantity; ?></span></td>
                                    <td colspan="2"></td>
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script>
$(document).ready(function() {
    $('#salesTable').DataTable({
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
        order: [[4, 'desc']]
    });
});
</script>

<?php include("includes/footer.php"); ?>
