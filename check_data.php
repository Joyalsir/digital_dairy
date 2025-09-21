<?php
include('includes/config.php');

// Check for null or empty farmer_uuid in milk_collection
$query = "SELECT COUNT(*) as count FROM milk_collection WHERE farmer_uuid IS NULL OR farmer_uuid = ''";
$result = mysqli_query($con, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo "Number of records with null or empty farmer_uuid: " . $row['count'] . PHP_EOL;
} else {
    echo "Error: " . mysqli_error($con) . PHP_EOL;
}

// Check if all farmer_uuid exist in farmers table
$query2 = "SELECT COUNT(*) as count FROM milk_collection mc LEFT JOIN farmers f ON mc.farmer_uuid = f.uuid WHERE f.uuid IS NULL";
$result2 = mysqli_query($con, $query2);
if ($result2) {
    $row2 = mysqli_fetch_assoc($result2);
    echo "Number of records with farmer_uuid not in farmers table: " . $row2['count'] . PHP_EOL;
} else {
    echo "Error: " . mysqli_error($con) . PHP_EOL;
}
?>
