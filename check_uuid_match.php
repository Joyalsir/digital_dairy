<?php
include('includes/config.php');

$query = "SELECT mc.id, mc.farmer_uuid, f.uuid FROM milk_collection mc LEFT JOIN farmers f ON mc.farmer_uuid = f.uuid WHERE f.uuid IS NULL";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    echo "Mismatched farmer_uuid in milk_collection:\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "Milk ID: " . $row['id'] . ", farmer_uuid: " . $row['farmer_uuid'] . "\n";
    }
} else {
    echo "All farmer_uuid in milk_collection match farmers.uuid\n";
}
?>
