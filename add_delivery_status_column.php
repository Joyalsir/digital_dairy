<?php
include('includes/config.php');

// Add delivery_status column to tbldelivery table
$sql = "ALTER TABLE tbldelivery ADD COLUMN delivery_status ENUM('pending', 'received') DEFAULT 'pending'";

if (mysqli_query($con, $sql)) {
    echo "Delivery status column added successfully to tbldelivery table.\n";
    echo "All existing deliveries are set to 'pending' status by default.\n";
} else {
    echo "Error adding delivery status column: " . mysqli_error($con) . "\n";
}

echo "Database update completed!\n";
?>
