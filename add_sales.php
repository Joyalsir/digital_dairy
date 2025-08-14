<?php
include('includes/config.php');

if (isset($_POST['submit'])) {
    $invoice = $_POST['invoice'];
    $cname   = $_POST['cname'];
    $contact = $_POST['contact'];
    $product = $_POST['product'];
    $qty     = $_POST['qty'];
    $price   = $_POST['price'];
    $total   = $qty * $price;
    $sdate   = $_POST['sdate'];

    $query = mysqli_query($con, "
        INSERT INTO tblsales
        (InvoiceNumber, CustomerName, Contact, ProductName, Quantity, Price, TotalAmount, SalesDate)
        VALUES
        ('$invoice', '$cname', '$contact', '$product', '$qty', '$price', '$total', '$sdate')
    ");

    if ($query) {
        echo "<script>alert('Sales record added successfully.');</script>";
    } else {
        echo "<script>alert('Error occurred.');</script>";
    }
}
?>

<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<div class="content">
    <div class="container-fluid px-4">
        <h4 class="mt-4 mb-4 text-primary">Add New Sale</h4>
        <form method="post">
            <div class="form-group mb-3">
                <label>Invoice Number</label>
                <input type="text" name="invoice" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Customer Name</label>
                <input type="text" name="cname" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Contact</label>
                <input type="text" name="contact" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Product Name</label>
                <input type="text" name="product" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Quantity</label>
                <input type="number" name="qty" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Price</label>
                <input type="text" name="price" class="form-control" required>
            </div>
            <div class="form-group mb-3">
                <label>Sales Date</label>
                <input type="date" name="sdate" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary w-100">Submit</button>
        </form>
    </div>
</div>

<?php include('includes/footer.php'); ?>

