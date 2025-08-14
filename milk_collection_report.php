<?php
session_start();
include('includes/config.php');

// Handle date range search
$fromDate = isset($_GET['from']) ? $_GET['from'] : '';
$toDate = isset($_GET['to']) ? $_GET['to'] : '';

$sql = "SELECT * FROM milk_collection WHERE 1";
if ($fromDate && $toDate) {
    $sql .= " AND date BETWEEN '$fromDate' AND '$toDate'";
}
$result = mysqli_query($con, $sql);
?>
<?php include("includes/sidebar.php"); ?>
<?php include("includes/header.php"); ?>

<div class="container-fluid px-4">
    <h4 class="mt-4 mb-4 text-primary">Milk Collection Report</h4>

    <form method="GET" class="row mb-4">
        <div class="col-md-4">
            <label>From Date<span class="text-danger">*</span></label>
            <input type="date" name="from" class="form-control" value="<?php echo $fromDate; ?>" required>
        </div>
        <div class="col-md-4">
            <label>To Date<span class="text-danger">*</span></label>
            <input type="date" name="to" class="form-control" value="<?php echo $toDate; ?>" required>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Search</button>
            <a href="milk_collection_report.php" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body">
            <table id="milkTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Serial</th>
                        <th>Date</th>
                        <th>Farmer</th>
                        <th>Product Type</th>
                        <th>Milk Quantity (L)</th>
                        <th>Milk Fat Content (%)</th>
                        <th>Temperature (°C)</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $serial = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                            <td>{$serial}</td>
                            <td>{$row['date']}</td>
                            <td>{$row['farmer_name']}</td>
                            <td>{$row['product_type']}</td>
                            <td>{$row['milk_quantity']}</td>
                            <td>{$row['milk_fat']}</td>
                            <td>{$row['temperature']}</td>
                            <td>{$row['payment']}</td>
                        </tr>";
                        $serial++;
                    }
                    ?>
                </tbody>
            </table>
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
$('#milkTable').DataTable({
    dom: '<"d-flex justify-content-between align-items-center"lfB>rtip',
    buttons: [
        { extend: 'copy', className: 'btn btn-sm' },
        { extend: 'excel', className: 'btn btn-sm' },
        { extend: 'pdf', className: 'btn btn-sm' }
    ]
});

</script>

<?php include("includes/footer.php"); ?>
