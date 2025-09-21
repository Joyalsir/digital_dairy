<?php
include('includes/config.php');
include('includes/uuid_helper.php');

/**
 * Add UUID support to delivery table
 * This script adds UUID columns and generates UUIDs for existing records
 */

// Add 4-digit UUID columns to tbldelivery table
$queries = [
    "ALTER TABLE tbldelivery ADD COLUMN delivery_uuid VARCHAR(4) DEFAULT NULL",
    "ALTER TABLE tbldelivery ADD COLUMN customer_uuid VARCHAR(4) DEFAULT NULL",
    "ALTER TABLE tbldelivery ADD COLUMN driver_uuid VARCHAR(4) DEFAULT NULL"
];

foreach ($queries as $query) {
    if (mysqli_query($con, $query)) {
        echo "Successfully added UUID column: " . substr($query, 7, 50) . "...\n";
    } else {
        echo "Error adding UUID column: " . mysqli_error($con) . "\n";
    }
}

// Generate UUIDs for existing records
$update_query = "SELECT ID, CustomerName, DriverName FROM tbldelivery WHERE delivery_uuid IS NULL";
$result = mysqli_query($con, $update_query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $delivery_uuid = generateShortUUID();
        $customer_uuid = generateShortUUID();
        $driver_uuid = generateShortUUID();

        $update_sql = "UPDATE tbldelivery SET
                      delivery_uuid = '$delivery_uuid',
                      customer_uuid = '$customer_uuid',
                      driver_uuid = '$driver_uuid'
                      WHERE ID = {$row['ID']}";

        if (mysqli_query($con, $update_sql)) {
            echo "Updated delivery record ID {$row['ID']} with UUIDs\n";
        } else {
            echo "Error updating delivery record ID {$row['ID']}: " . mysqli_error($con) . "\n";
        }
    }
    echo "UUID generation completed for existing delivery records.\n";
} else {
    echo "Error fetching delivery records: " . mysqli_error($con) . "\n";
}

// Make UUID columns NOT NULL after populating data
$final_queries = [
    "ALTER TABLE tbldelivery MODIFY delivery_uuid VARCHAR(4) NOT NULL",
    "ALTER TABLE tbldelivery MODIFY customer_uuid VARCHAR(4) NOT NULL",
    "ALTER TABLE tbldelivery MODIFY driver_uuid VARCHAR(4) NOT NULL"
];

foreach ($final_queries as $query) {
    if (mysqli_query($con, $query)) {
        echo "Successfully made UUID column required: " . substr($query, 7, 50) . "...\n";
    } else {
        echo "Error making UUID column required: " . mysqli_error($con) . "\n";
    }
}

echo "Delivery table UUID setup completed!\n";
?>
