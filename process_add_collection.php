<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include('includes/config.php');

if (isset($_POST['submit'])) {
    // Collect form data
    $date        = mysqli_real_escape_string($con, $_POST['date']);
    $farmer_id   = mysqli_real_escape_string($con, $_POST['farmer']);
    $product     = mysqli_real_escape_string($con, $_POST['product_type']);
    $quantity    = mysqli_real_escape_string($con, $_POST['quantity']);
    $fat         = mysqli_real_escape_string($con, $_POST['fat']);
    $temperature = mysqli_real_escape_string($con, $_POST['temperature']);
    $payment     = mysqli_real_escape_string($con, $_POST['payment']);

    // Insert Query
    $sql = "INSERT INTO milk_collection 
                (date, farmer_id, product_type, quantity, fat, temperature, payment) 
            VALUES 
                ('$date', '$farmer_id', '$product', '$quantity', '$fat', '$temperature', '$payment')";

    if (mysqli_query($con, $sql)) {
        $_SESSION['success'] = "Milk collection record added successfully!";
        header("Location: manage_collection.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>
