<?php
include('includes/config.php');

$result = mysqli_query($con, "SHOW TABLES");
if ($result) {
    while ($row = mysqli_fetch_row($result)) {
        echo $row[0] . PHP_EOL;
    }
} else {
    echo "Error: " . mysqli_error($con);
}
?>
