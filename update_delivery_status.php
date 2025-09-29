<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delivery_id'])) {
    $delivery_id = intval($_POST['delivery_id']);

    $query = "UPDATE tbldelivery SET delivery_status = 'received' WHERE ID = $delivery_id";

    if (mysqli_query($con, $query)) {
        $_SESSION['message'] = "Delivery marked as received successfully.";
    } else {
        $_SESSION['message'] = "Error updating delivery status: " . mysqli_error($con);
    }
} else {
    $_SESSION['message'] = "Invalid request.";
}

header("Location: manage_delivery.php");
exit;
?>
