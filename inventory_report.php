<?php
session_start();
include('includes/config.php'); // DB connection

// -----------------------------
// Build WHERE clause (date filter)
// -----------------------------
$where = "";
if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
    $from = date('Y-m-d', strtotime($_POST['from_date']));
    $to   = date('Y-m-d', strtotime($_POST['to_date']));
    $where = "WHERE mc.date BETWEEN '$from' AND '$to'";
}

// -----------------------------
// Query milk_collection + farmers
// -----------------------------
$sql = "SELECT mc.*, f.name AS farmer_name
        FROM milk_collection mc
        JOIN farmers f ON mc.farmer_id = f.id
        $where
        ORDER BY mc.date DESC";

$query = mysqli_query($con, $sql);

// Debug if SQL fails
if (!$query) {
    die("SQL Error: " . mysqli_error($con) . " <br>Query: " . $sql);
}
?>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<div class="inventory-report-container">
    <div class="container-fluid px-4">
        <!-- Report Header -->
        <div class="report-header">
            <h1 class="report-title">
                <i class="fas fa-boxes me-3"></i>
                Inventory Report
            </h1>
            <p class="mb-0">Comprehensive inventory tracking and analysis</p>
        </div>

        <!-- Date Search -->
        <div class="search-form-card">
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i> From Date
                    </label>
                    <input type="date" name="from_date" class="form-control"
                           value="<?php if(isset($_POST['from_date'])) echo $_POST['from_date']; ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i> To Date
                    </label>
                    <input type="date" name="to_date" class="form-control"
                           value="<?php if(isset($_POST['to_date'])) echo $_POST['to_date']; ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i> Search
                    </button>
                    <a href="inventory_report.php" class="btn btn-secondary">
                        <i class="fas fa-refresh me-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Data Table -->
        <div class="table-card mt-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="inventoryTable" class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Farmer</th>
                                <th>Product Type</th>
                                <th>Quantity (L)</th>
                                <th>Fat (%)</th>
                                <th>Temperature (°C)</th>
                                <th>Payment (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            $totalQty = 0;
                            $totalPay = 0;

                            if (mysqli_num_rows($query) > 0) {
                                while ($row = mysqli_fetch_assoc($query)) {
                                    $totalQty += $row['quantity'];
                                    $totalPay += $row['payment'];
                                    ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo date('d M Y', strtotime($row['date'])); ?></td>
                                        <td><?php echo $row['farmer_name']; ?></td>
                                        <td><?php echo $row['product_type']; ?></td>
                                        <td><?php echo number_format($row['quantity'], 2); ?></td>
                                        <td><?php echo number_format($row['fat'], 2); ?>%</td>
                                        <td><?php echo number_format($row['temperature'], 1); ?>°C</td>
                                        <td>₹<?php echo number_format($row['payment'], 2); ?></td>
                                    </tr>
                                <?php } ?>
                                <!-- Summary row -->
                                <tr class="table-secondary fw-bold">
                                    <td colspan="4" class="text-end">TOTAL</td>
                                    <td><?php echo number_format($totalQty, 2); ?> L</td>
                                    <td colspan="2"></td>
                                    <td>₹<?php echo number_format($totalPay, 2); ?></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        <i class="fas fa-inbox me-2"></i>No records found
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

<!-- DataTables Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>



<?php include("includes/footer.php"); ?>
