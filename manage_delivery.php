<?php
include('includes/config.php'); // DB connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Milk Delivery</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Your custom CSS -->
    <style>
        body {
            background-color: #f4f4f4;
        }
        .container-box {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            margin: 40px auto;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .btn-add {
            float: right;
            margin-bottom: 15px;
        }
        .table {
            margin-top: 20px;
        }
        h4 {
            margin-bottom: 25px;
            font-weight: 600;
        }
        .table th {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
<?php include("includes/sidebar.php"); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-11 offset-md-1 container-box">
            <h4>Manage Milk Delivery</h4>
            <a href="add_delivery.php" class="btn btn-primary btn-add">+ Add New Delivery</a>
            <div class="table-responsive">
                <table id="deliveryTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Address</th>
                            <th>Delivery Date</th>
                            <th>Product Type</th>
                            <th>Qty</th>
                            <th>Vehicle No</th>
                            <th>Driver Name</th>
                            <th>Driver Contact</th>
                            <th>Posted On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($con, "SELECT * FROM tbldelivery ORDER BY ID DESC");
                        $cnt = 1;
                        while($row = mysqli_fetch_array($query)) {
                        ?>
                        <tr>
                            <td><?php echo $cnt++; ?></td>
                            <td><?php echo htmlentities($row['CustomerName']); ?></td>
                            <td><?php echo htmlentities($row['Contact']); ?></td>
                            <td><?php echo htmlentities($row['Address']); ?></td>
                            <td><?php echo htmlentities($row['DeliveryDate']); ?></td>
                            <td><?php echo htmlentities($row['ProductType']); ?></td>
                            <td><?php echo htmlentities($row['Quantity']); ?></td>
                            <td><?php echo htmlentities($row['VehicleNo']); ?></td>
                            <td><?php echo htmlentities($row['DriverName']); ?></td>
                            <td><?php echo htmlentities($row['DriverContact']); ?></td>
                            <td><?php echo htmlentities($row['PostingDate']); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/jquery.dataTables.min.js"></script>
<script src="assets/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function () {
        $('#deliveryTable').DataTable();
    });
</script>
</body>
</html>


<?php include('includes/footer.php'); ?>