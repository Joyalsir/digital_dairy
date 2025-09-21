<?php
include('includes/config.php');

// Allow null in farmer_uuid
$query1 = "ALTER TABLE milk_collection MODIFY farmer_uuid VARCHAR(4) NULL";
if (mysqli_query($con, $query1)) {
    echo "Column modified to allow NULL.\n";
} else {
    echo "Error modifying column: " . mysqli_error($con) . "\n";
}

// Set empty farmer_uuid to NULL
$query2 = "UPDATE milk_collection SET farmer_uuid = NULL WHERE farmer_uuid = ''";
if (mysqli_query($con, $query2)) {
    echo "Empty farmer_uuid set to NULL.\n";
} else {
    echo "Error updating: " . mysqli_error($con) . "\n";
}
?>
