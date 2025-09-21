<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include('includes/config.php');

if (!isset($_GET['invoice'])) {
    echo "<script>alert('Invalid invoice number.'); window.location.href='manage_sales.php';</script>";
    exit;
}

$invoice = mysqli_real_escape_string($con, $_GET['invoice']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form submission
    $customerName = mysqli_real_escape_string($con, $_POST['CustomerName']);
    $contact = mysqli_real_escape_string($con, $_POST['Contact']);
    $productName = mysqli_real_escape_string($con, $_POST['ProductName']);
    $quantity = floatval($_POST['Quantity']);
    $price = floatval($_POST['Price']);
    $totalAmount = $quantity * $price;
    $salesDate = mysqli_real_escape_string($con, $_POST['SalesDate']);

    $updateSql = "UPDATE tblsales SET 
        CustomerName = '$customerName',
        Contact = '$contact',
        ProductName = '$productName',
        Quantity = $quantity,
        Price = $price,
        TotalAmount = $totalAmount,
        SalesDate = '$salesDate'
        WHERE InvoiceNumber = '$invoice'";

    if (mysqli_query($con, $updateSql)) {
        echo "<script>
            alert('Sale record updated successfully.');
            window.location.href = 'manage_sales.php';
        </script>";
        exit;
    } else {
        $error = "Error updating record: " . mysqli_error($con);
    }
} else {
    // Fetch sale record for form
    $sql = "SELECT * FROM tblsales WHERE InvoiceNumber = '$invoice' LIMIT 1";
    $result = mysqli_query($con, $sql);
    if (!$result || mysqli_num_rows($result) == 0) {
        echo "<script>alert('Sale record not found.'); window.location.href='manage_sales.php';</script>";
        exit;
    }
    $sale = mysqli_fetch_assoc($result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sale - Digital Dairy Management System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-container" style="max-width: 600px; margin: 2rem auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h1 style="margin-bottom: 1.5rem; color: #333;">Edit Sale - Invoice #<?php echo htmlspecialchars($invoice); ?></h1>
        <?php if (isset($error)): ?>
            <div style="background-color: #f8d7da; color: #842029; padding: 10px 15px; border-radius: 5px; margin-bottom: 1rem; border: 1px solid #f5c2c7;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="" style="display: flex; flex-direction: column; gap: 1rem;">
            <div class="form-group" style="display: flex; flex-direction: column;">
                <label for="CustomerName" style="font-weight: 600; margin-bottom: 0.5rem;">Customer Name</label>
                <input type="text" id="CustomerName" name="CustomerName" required value="<?php echo htmlspecialchars($sale['CustomerName']); ?>" style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div class="form-group" style="display: flex; flex-direction: column;">
                <label for="Contact" style="font-weight: 600; margin-bottom: 0.5rem;">Contact</label>
                <input type="text" id="Contact" name="Contact" required value="<?php echo htmlspecialchars($sale['Contact']); ?>" style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div class="form-group" style="display: flex; flex-direction: column;">
                <label for="ProductName" style="font-weight: 600; margin-bottom: 0.5rem;">Product Name</label>
                <input type="text" id="ProductName" name="ProductName" required value="<?php echo htmlspecialchars($sale['ProductName']); ?>" style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div class="form-group" style="display: flex; flex-direction: column;">
                <label for="Quantity" style="font-weight: 600; margin-bottom: 0.5rem;">Quantity</label>
                <input type="number" step="0.01" id="Quantity" name="Quantity" required value="<?php echo htmlspecialchars($sale['Quantity']); ?>" style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div class="form-group" style="display: flex; flex-direction: column;">
                <label for="Price" style="font-weight: 600; margin-bottom: 0.5rem;">Price (₹)</label>
                <input type="number" step="0.01" id="Price" name="Price" required value="<?php echo htmlspecialchars($sale['Price']); ?>" style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div class="form-group" style="display: flex; flex-direction: column;">
                <label for="SalesDate" style="font-weight: 600; margin-bottom: 0.5rem;">Sales Date</label>
                <input type="date" id="SalesDate" name="SalesDate" required value="<?php echo htmlspecialchars(date('Y-m-d', strtotime($sale['SalesDate']))); ?>" style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="display: flex; gap: 10px; margin-top: 1rem;">
                <button type="submit" class="btn btn-primary" style="background-color: #667eea; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer;">Update Sale</button>
                <a href="manage_sales.php" class="btn btn-secondary" style="background-color: #6c757d; color: white; padding: 10px 20px; border-radius: 4px; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
