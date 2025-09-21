<?php
include('includes/config.php');

// First, drop the existing foreign key
$query1 = "ALTER TABLE milk_collection DROP FOREIGN KEY milk_collection_ibfk_1";
if (mysqli_query($con, $query1)) {
    echo "Foreign key dropped successfully.\n";
} else {
    echo "Error dropping foreign key: " . mysqli_error($con) . "\n";
}

// Alter the column from farmer_id to farmer_uuid
$query2 = "ALTER TABLE milk_collection CHANGE farmer_id farmer_uuid VARCHAR(4) NOT NULL";
if (mysqli_query($con, $query2)) {
    echo "Column altered successfully: farmer_id changed to farmer_uuid.\n";
} else {
    echo "Error altering column: " . mysqli_error($con) . "\n";
}

// Add new foreign key to farmers.uuid
$query3 = "ALTER TABLE milk_collection ADD CONSTRAINT fk_farmer_uuid FOREIGN KEY (farmer_uuid) REFERENCES farmers(uuid)";
if (mysqli_query($con, $query3)) {
    echo "New foreign key added successfully.\n";
} else {
    echo "Error adding new foreign key: " . mysqli_error($con) . "\n";
}
?>
