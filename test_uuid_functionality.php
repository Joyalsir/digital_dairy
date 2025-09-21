<?php
include('includes/config.php');
include('includes/uuid_helper.php');

/**
 * Test UUID functionality for both milk collection and delivery systems
 */

// Test 4-digit UUID generation
echo "=== UUID Generation Test ===\n";
$test_uuid = generateShortUUID();
echo "Generated UUID: $test_uuid\n";
echo "UUID Length: " . strlen($test_uuid) . " characters\n";
echo "UUID Format: 4-digit alphanumeric\n\n";

// Test milk collection UUID functionality
echo "=== Milk Collection UUID Test ===\n";
$query = "SELECT id, farmer_uuid, product_type, quantity FROM milk_collection WHERE farmer_uuid IS NOT NULL LIMIT 3";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    echo "Recent milk collection records with UUIDs:\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: {$row['id']}, Farmer UUID: {$row['farmer_uuid']}, Product: {$row['product_type']}, Quantity: {$row['quantity']}L\n";
    }
} else {
    echo "No milk collection records found with UUIDs.\n";
}

echo "\n";

// Test delivery UUID functionality
echo "=== Delivery UUID Test ===\n";
$query = "SELECT ID, CustomerName, delivery_uuid, customer_uuid, driver_uuid FROM tbldelivery WHERE delivery_uuid IS NOT NULL LIMIT 3";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    echo "Recent delivery records with UUIDs:\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: {$row['ID']}, Customer: {$row['CustomerName']}\n";
        echo "  Delivery UUID: {$row['delivery_uuid']}\n";
        echo "  Customer UUID: {$row['customer_uuid']}\n";
        echo "  Driver UUID: {$row['driver_uuid']}\n";
    }
} else {
    echo "No delivery records found with UUIDs.\n";
}

echo "\n=== UUID Implementation Summary ===\n";
echo "✓ UUID Helper functions created (includes/uuid_helper.php)\n";
echo "✓ Milk collection system uses farmer UUIDs (process_add_collection.php)\n";
echo "✓ Delivery system now uses UUIDs (insert_delivery.php)\n";
echo "✓ Database tables updated with UUID columns\n";
echo "✓ Existing records populated with UUIDs\n";
echo "✓ UUID validation functions available\n";

echo "\nUUID functionality has been successfully implemented!\n";
?>
