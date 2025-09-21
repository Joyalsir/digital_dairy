<?php
include('includes/config.php');

// Add status column to farmers table
$sql = "ALTER TABLE farmers ADD COLUMN status ENUM('active', 'inactive') DEFAULT 'active' AFTER created_at";

if (mysqli_query($con, $sql)) {
    echo "Status column added successfully to farmers table.\n";
    echo "All existing farmers are set to 'active' status by default.\n";
} else {
    echo "Error adding status column: " . mysqli_error($con) . "\n";
}

echo "Database update completed!\n";
?>
