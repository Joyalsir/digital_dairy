<?php
include('includes/config.php');

// Drop the existing foreign key
$query1 = "ALTER TABLE milk_collection DROP FOREIGN KEY fk_farmer_uuid";
if (mysqli_query($con, $query1)) {
    echo "Foreign key dropped successfully.\n";
} else {
    echo "Error dropping foreign key: " . mysqli_error($con) . "\n";
}

// Alter farmers.uuid to VARCHAR(4)
$query2 = "ALTER TABLE farmers MODIFY uuid VARCHAR(4) NOT NULL UNIQUE";
if (mysqli_query($con, $query2)) {
    echo "Farmers uuid altered to VARCHAR(4).\n";
} else {
    echo "Error altering farmers uuid: " . mysqli_error($con) . "\n";
}

// Alter milk_collection.farmer_uuid to VARCHAR(4)
$query3 = "ALTER TABLE milk_collection MODIFY farmer_uuid VARCHAR(4)";
if (mysqli_query($con, $query3)) {
    echo "Milk_collection farmer_uuid altered to VARCHAR(4).\n";
} else {
    echo "Error altering milk_collection farmer_uuid: " . mysqli_error($con) . "\n";
}

// Add new foreign key
$query4 = "ALTER TABLE milk_collection ADD CONSTRAINT fk_farmer_uuid FOREIGN KEY (farmer_uuid) REFERENCES farmers(uuid)";
if (mysqli_query($con, $query4)) {
    echo "New foreign key added successfully.\n";
} else {
    echo "Error adding new foreign key: " . mysqli_error($con) . "\n";
}
?>
