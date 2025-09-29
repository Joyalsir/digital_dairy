<?php
include("includes/config.php");

// Add password column to tblcustomer table
$sql = "ALTER TABLE tblcustomer ADD COLUMN password VARCHAR(255) DEFAULT NULL";

if (mysqli_query($con, $sql)) {
    echo "✅ Password column added to tblcustomer table successfully!<br>";

    // Update existing customers with a default password (you may want to change this)
    $default_password = md5('password123'); // Default password for existing customers
    $update_sql = "UPDATE tblcustomer SET password = '$default_password' WHERE password IS NULL";

    if (mysqli_query($con, $update_sql)) {
        echo "✅ Default passwords set for existing customers.<br>";
        echo "⚠️  Please inform existing customers to change their password after first login.<br>";
    } else {
        echo "❌ Error setting default passwords: " . mysqli_error($con) . "<br>";
    }
} else {
    echo "❌ Error adding password column: " . mysqli_error($con) . "<br>";
}

mysqli_close($con);
?>
