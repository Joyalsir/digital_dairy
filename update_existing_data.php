<?php
include('includes/config.php');

// Update existing milk_collection records to set farmer_uuid based on farmer_id
$query = "UPDATE milk_collection mc
          JOIN farmers f ON mc.farmer_uuid = f.id
          SET mc.farmer_uuid = f.uuid";

if (mysqli_query($con, $query)) {
    echo "Existing data updated successfully.";
} else {
    echo "Error updating data: " . mysqli_error($con);
}
?>
