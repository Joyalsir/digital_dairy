<?php
include('includes/config.php');

$query = "UPDATE milk_collection SET farmer_uuid = NULL WHERE farmer_uuid = ''";
if (mysqli_query($con, $query)) {
    echo "Empty farmer_uuid updated to NULL.\n";
} else {
    echo "Error: " . mysqli_error($con) . "\n";
}
?>
