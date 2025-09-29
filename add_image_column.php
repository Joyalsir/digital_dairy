<?php
include('includes/config.php');

// Add image column to tblproduct table
$sql = "ALTER TABLE tblproduct ADD COLUMN ProductImage VARCHAR(255) DEFAULT NULL";
if (mysqli_query($con, $sql)) {
    echo "✓ Successfully added ProductImage column to tblproduct table\n";
} else {
    echo "✗ Error adding column: " . mysqli_error($con) . "\n";
}

// Create uploads directory if it doesn't exist
$upload_dir = 'uploads/products/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
    echo "✓ Created uploads/products/ directory\n";
} else {
    echo "✓ Uploads directory already exists\n";
}

echo "Database update completed!\n";
?>
