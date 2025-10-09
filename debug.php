<?php
include('includes/config.php');

echo "<h1>Debug Milk Collection Data</h1>";

// Check distinct years
$result = mysqli_query($con, "SELECT DISTINCT YEAR(date) as year FROM milk_collection ORDER BY year DESC");
echo "<h2>Distinct Years:</h2>";
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['year'] . "<br>";
}

// Check sample data
$result = mysqli_query($con, "SELECT date, quantity FROM milk_collection LIMIT 10");
echo "<h2>Sample Data:</h2>";
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['date'] . " - " . $row['quantity'] . "<br>";
}

// Check for 2024
$result = mysqli_query($con, "SELECT SUM(quantity) as total FROM milk_collection WHERE YEAR(date) = 2024");
$row = mysqli_fetch_assoc($result);
echo "<h2>Total for 2024:</h2>" . ($row['total'] ? $row['total'] : 0);

// Check for 2025
$result = mysqli_query($con, "SELECT SUM(quantity) as total FROM milk_collection WHERE YEAR(date) = 2025");
$row = mysqli_fetch_assoc($result);
echo "<h2>Total for 2025:</h2>" . ($row['total'] ? $row['total'] : 0);
?>
