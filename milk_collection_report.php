<?php
session_start();
include('includes/config.php');

// Handle date range search
$fromDate = isset($_GET['from']) ? $_GET['from'] : '';
$toDate = isset($_GET['to']) ? $_GET['to'] : '';

$sql = "SELECT mc.*, f.name as farmer_name 
        FROM milk_collection mc 
        JOIN farmers f ON mc.farmer_id = f.id 
        WHERE 1";
if ($fromDate && $toDate) {
    $sql .= " AND mc.date BETWEEN '$fromDate' AND '$toDate'";
}
$sql .= " ORDER BY mc.date DESC";
$result = mysqli_query($con, $sql);
?>
<?php include("includes/sidebar.php"); ?>
<?php include("includes/header.php"); ?>



<div class="milk-report-container">
    <div class="container-fluid px-4">
        <div class="report-header">
            <h1 class="report-title">
                <i class="fas fa-chart-line me-3"></i>
                Milk Collection Report
            </h1>
            <p class="mb-0">Comprehensive analysis of milk collection data</p>
        </div>

    <div class="search-form-card">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-calendar-alt me-2"></i>
                    From Date <span class="text-danger">*</span>
                </label>
                <input type="date" name="from" class="form-control" value="<?php echo $fromDate; ?>" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-calendar-alt me-2"></i>
                    To Date <span class="text-danger">*</span>
                </label>
                <input type="date" name="to" class="form-control" value="<?php echo $toDate; ?>" required>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i>
                    Search
                </button>
                <a href="milk_collection_report.php" class="btn btn-secondary">
                    <i class="fas fa-refresh me-2"></i>
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="milkTable" class="table table-hover">
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $serial = 1;
                        $totalQuantity = 0;
                        $totalPayment = 0;
                        
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                $totalQuantity += $row['quantity'];
                                $totalPayment += $row['payment'];
                                
                                echo "<tr>
                                    <td><span class='badge bg-primary'>{$serial}</span></td>
                                    <td><strong>" . date('d M Y', strtotime($row['date'])) . "</strong></td>
                                    <td><span class='text-primary fw-bold'>{$row['farmer_name']}</span></td>
                                    <td><span class='badge bg-info'>{$row['product_type']}</span></td>
                                    <td><span class='badge bg-success'>{$row['quantity']} L</span></td>
                                    <td><span class='badge bg-warning text-dark'>{$row['fat']}%</span></td>
                                    <td><span class='badge bg-secondary'>{$row['temperature']}°C</span></td>
                                    <td><span class='badge bg-success'>₹{$row['payment']}</span></td>
                                </tr>";
                                $serial++;
                            }
                            
                            // Summary row
                            echo "<tr class='table-dark fw-bold'>
                                <td colspan='4' class='text-end'>TOTALS:</td>
                                <td><span class='badge bg-light text-dark'>{$totalQuantity} L</span></td>
                                <td colspan='2'></td>
                                <td><span class='badge bg-light text-dark'>₹{$totalPayment}</span></td>
                            </tr>";
                        } else {
                            echo "<tr>
                                <td colspan='8' class='text-center py-4'>
                                    <i class='fas fa-inbox fa-3x text-muted mb-3'></i>
                                    <h5 class='text-muted'>No records found</h5>
                                    <p class='text-muted'>Try adjusting your date range</p>
                                </td>
                            </tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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
$('#milk_collection').DataTable({
    dom: '<"d-flex justify-content-between align-items-center"lfB>rtip',
    buttons: [
        { extend: 'copy', className: 'btn btn-sm' },
        { extend: 'excel', className: 'btn btn-sm' },
        { extend: 'pdf', className: 'btn btn-sm' }
    ]
});

</script>

<?php include("includes/footer.php"); ?>
