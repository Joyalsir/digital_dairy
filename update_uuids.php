<?php
include('includes/config.php');

function generate_unique_uuid($con) {
    do {
        $uuid = generateShortUUID();
        $check = mysqli_query($con, "SELECT id FROM farmers WHERE uuid='$uuid'");
    } while (mysqli_num_rows($check) > 0);
    return $uuid;
}

// Update existing farmers with new 4-digit UUIDs
$query = "SELECT id FROM farmers";
$result = mysqli_query($con, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $new_uuid = generate_unique_uuid($con);
        $update = "UPDATE farmers SET uuid='$new_uuid' WHERE id=" . $row['id'];
        if (!mysqli_query($con, $update)) {
            echo "Error updating farmer ID " . $row['id'] . ": " . mysqli_error($con) . "\n";
        } else {
            echo "Updated farmer ID " . $row['id'] . " to UUID $new_uuid\n";
        }
    }
    echo "All existing UUIDs updated.\n";
} else {
    echo "Error fetching farmers: " . mysqli_error($con) . "\n";
}
?>
