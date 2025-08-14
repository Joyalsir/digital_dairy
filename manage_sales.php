<?php include('includes/header.php'); ?>
<link rel="stylesheet" href="style.css">

<div class="dashboard-container d-flex">
    <?php include("includes/sidebar.php"); ?>

    <div class="main p-4">
        <h4 class="mb-4 text-primary">View Sales Records</h4>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <table id="datatablesSimple" class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Serial</th>
                            <th>Invoice No</th>
                            <th>Customer Name</th>
                            <th>Contact</th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Sales Date</th>
                            <th>Posting Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('includes/config.php');
                        $ret = mysqli_query($con, "SELECT * FROM tblsales ORDER BY ID DESC");
                        $cnt = 1;
                        while ($row = mysqli_fetch_array($ret)) {
                        ?>
                            <tr>
                                <td><?php echo $cnt++; ?></td>
                                <td><?php echo $row['InvoiceNumber']; ?></td>
                                <td><?php echo $row['CustomerName']; ?></td>
                                <td><?php echo $row['Contact']; ?></td>
                                <td><?php echo $row['ProductName']; ?></td>
                                <td><?php echo $row['Quantity']; ?></td>
                                <td><?php echo $row['Price']; ?></td>
                                <td><?php echo $row['TotalAmount']; ?></td>
                                <td><?php echo $row['SalesDate']; ?></td>
                                <td><?php echo $row['PostingDate']; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>
