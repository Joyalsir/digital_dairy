<?php
include('includes/config.php');

$query = "ALTER TABLE farmers MODIFY uuid VARCHAR(4) NOT NULL UNIQUE";
if (mysqli_query($con, $query)) {
    echo "Farmers uuid column altered to VARCHAR(4) successfully.\n";
} else {
    echo "Error altering farmers uuid: " . mysqli_error($con) . "\n";
}
?>
