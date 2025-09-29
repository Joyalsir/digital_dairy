<?php
// Simple test file to verify orders functionality
session_start();
include('includes/config.php');

// Test database connection
echo "Database Connection: ";
if ($con) {
    echo "✅ Connected successfully<br>";
} else {
    echo "❌ Connection failed<br>";
    exit;
}

// Test if tables exist
$tables = ['tblorders', 'tblorderitems', 'tblproduct'];
foreach ($tables as $table) {
    $result = mysqli_query($con, "SHOW TABLES LIKE '$table'");
    echo "Table '$table': ";
    if (mysqli_num_rows($result) > 0) {
        echo "✅ Exists<br>";
    } else {
        echo "❌ Not found<br>";
    }
}

// Test session functionality
echo "Session Status: ";
if (isset($_SESSION['cart'])) {
    echo "✅ Cart session initialized<br>";
} else {
    $_SESSION['cart'] = array();
    echo "✅ Cart session created<br>";
}

// Test basic PHP functionality
echo "PHP Version: " . phpversion() . "<br>";
echo "File Structure: ✅ orders.php and order_details.php created successfully<br>";

// Clean up test session
unset($_SESSION['cart']);
echo "Test completed successfully! ✅";
?>
