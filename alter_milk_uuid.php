<?php
include('includes/config.php');

$query = "ALTER TABLE milk_collection MODIFY farmer_uuid VARCHAR(4) NOT NULL";
if (mysqli_query($con, $query)) {
    echo "Milk_collection farmer_uuid column altered to VARCHAR(4) successfully.\n";
} else {
    echo "Error altering milk_collection farmer_uuid: " . mysqli_error($con) . "\n";
}
?>
