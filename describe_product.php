<?php
include('includes/config.php');

$result = mysqli_query($con, "DESCRIBE tblproduct");
if ($result) {
    echo "Table structure for tblproduct:" . PHP_EOL;
    while ($row = mysqli_fetch_assoc($result)) {
        echo $row['Field'] . ' - ' . $row['Type'] . ' - ' . $row['Key'] . ' - ' . $row['Extra'] . PHP_EOL;
    }
} else {
    echo "Error: " . mysqli_error($con);
}
?>
