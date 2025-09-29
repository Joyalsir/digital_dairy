<?php
// Database setup script for order tables
include('includes/config.php');

echo "<h2>Setting up Order Tables</h2>";

// Create tblorders table
$create_orders_table = "
CREATE TABLE IF NOT EXISTS tblorders (
    ID VARCHAR(50) PRIMARY KEY,
    Email VARCHAR(255) NOT NULL,
    TotalAmount DECIMAL(10,2) NOT NULL,
    Status ENUM('Pending', 'Processing', 'Delivered', 'Cancelled') DEFAULT 'Pending',
    OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (Email),
    INDEX idx_status (Status),
    INDEX idx_order_date (OrderDate)
)";

echo "<h3>Creating tblorders table...</h3>";
if (mysqli_query($con, $create_orders_table)) {
    echo "<p style='color: green;'>✓ tblorders table created successfully!</p>";
} else {
    echo "<p style='color: red;'>✗ Error creating tblorders table: " . mysqli_error($con) . "</p>";
}

// Create tblorderitems table
$create_orderitems_table = "
CREATE TABLE IF NOT EXISTS tblorderitems (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    OrderID VARCHAR(50) NOT NULL,
    ProductID VARCHAR(50) NOT NULL,
    Quantity INT NOT NULL,
    Price DECIMAL(10,2) NOT NULL,
    Total DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (OrderID) REFERENCES tblorders(ID) ON DELETE CASCADE,
    INDEX idx_order_id (OrderID),
    INDEX idx_product_id (ProductID)
)";

echo "<h3>Creating tblorderitems table...</h3>";
if (mysqli_query($con, $create_orderitems_table)) {
    echo "<p style='color: green;'>✓ tblorderitems table created successfully!</p>";
} else {
    echo "<p style='color: red;'>✗ Error creating tblorderitems table: " . mysqli_error($con) . "</p>";
}

// Create view
$create_view = "
CREATE OR REPLACE VIEW vw_order_details AS
SELECT
    o.ID as OrderID,
    o.Email,
    o.TotalAmount,
    o.Status,
    o.OrderDate,
    oi.ProductID,
    oi.Quantity,
    oi.Price,
    oi.Total as ItemTotal
FROM tblorders o
LEFT JOIN tblorderitems oi ON o.ID = oi.OrderID
ORDER BY o.OrderDate DESC, o.ID, oi.ID";

echo "<h3>Creating vw_order_details view...</h3>";
if (mysqli_query($con, $create_view)) {
    echo "<p style='color: green;'>✓ vw_order_details view created successfully!</p>";
} else {
    echo "<p style='color: red;'>✗ Error creating view: " . mysqli_error($con) . "</p>";
}

echo "<h3>Setup Complete!</h3>";
echo "<p><a href='cart.php'>← Back to Cart</a></p>";

// Close connection
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - Digital Dairy Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h2 { color: #2c5aa0; }
        h3 { color: #4caf50; }
        p { margin: 10px 0; }
        a { color: #2c5aa0; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
</body>
</html>
