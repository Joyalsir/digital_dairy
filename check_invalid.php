<?php
include('includes/config.php');

// Check invalid farmer_uuid in milk_collection
$query = "SELECT mc.id, mc.farmer_uuid FROM milk_collection mc LEFT JOIN farmers f ON mc.farmer_uuid = f.uuid WHERE f.uuid IS NULL";
$result = mysqli_query($con, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row['id'] . ", farmer_uuid: " . $row['farmer_uuid'] . PHP_EOL;
    }
} else {
    echo "Error: " . mysqli_error($con) . PHP_EOL;
}
?>
