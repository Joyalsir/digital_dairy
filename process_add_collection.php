<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include('includes/config.php');
include('includes/uuid_helper.php');

if (isset($_POST['submit'])) {
    // Collect form data
    $date        = mysqli_real_escape_string($con, $_POST['date']);
    $farmer_id   = mysqli_real_escape_string($con, $_POST['farmer']);
    $product     = mysqli_real_escape_string($con, $_POST['product_type']);
    $quantity    = mysqli_real_escape_string($con, $_POST['quantity']);
    $fat         = mysqli_real_escape_string($con, $_POST['fat']);
    $temperature = mysqli_real_escape_string($con, $_POST['temperature']);
    $payment     = mysqli_real_escape_string($con, $_POST['payment']);

    // Get farmer UUID from farmers table using farmer id
    $farmer_uuid = '';
    $farmer_query = mysqli_query($con, "SELECT uuid FROM farmers WHERE id = '$farmer_id'");
    if ($farmer_query && mysqli_num_rows($farmer_query) > 0) {
        $farmer_data = mysqli_fetch_assoc($farmer_query);
        $farmer_uuid = $farmer_data['uuid'];
    } else {
        // Handle error or set default
        $farmer_uuid = null;
    }

    // Insert Query
    $sql = "INSERT INTO milk_collection 
                (date, farmer_uuid, product_type, quantity, fat, temperature, payment) 
            VALUES 
                ('$date', '$farmer_uuid', '$product', '$quantity', '$fat', '$temperature', '$payment')";

    if (mysqli_query($con, $sql)) {
        $_SESSION['success'] = "Milk collection record added successfully with UUID tracking!";
        header("Location: manage_collection.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
