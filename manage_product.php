<?php
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Products</title>
    <?php include('includes/header.php'); ?>
</head>
<body>
    <div class="hk-wrapper">
        <?php include('includes/sidebar.php'); ?>
        <div class="hk-pg-wrapper">
            <div class="container mt-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="hk-sec-title">View Products</h4>
                    <a href="product_add.php" class="btn btn-primary">Add New</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Serial</th>
                                    <th>Product Name</th>
                                    <th>Product Type</th>
                                    <th>Unit Price (₹)</th>
                                    <th>Added On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM tblproduct");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                <tr>
                                    <td><?php echo $cnt++; ?></td>
                                    <td><?php echo $row['ProductName']; ?></td>
                                    <td><?php echo $row['ProductType']; ?></td>
                                    <td><?php echo number_format($row['UnitPrice'], 2); ?></td>
                                    <td><?php echo $row['PostingDate']; ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php include('includes/footer.php'); ?>
        </div>
    </div>
</body>
</html>
