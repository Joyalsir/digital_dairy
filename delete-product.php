<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include('includes/config.php');

if (!isset($_GET['pid'])) {
    echo "<script>alert('Invalid product ID.'); window.location.href='manage_product.php';</script>";
    exit;
}

$pid = mysqli_real_escape_string($con, $_GET['pid']);

// Optional: Check for foreign key constraints here if needed

$sql = "DELETE FROM tblproduct WHERE ID = '$pid'";

if (mysqli_query($con, $sql)) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Deleted</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #f5f5f5;
            }
        </style>
    </head>
    <body>
        <script>
            Swal.fire({
                title: 'Deleted!',
                text: 'Product record deleted successfully!',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745'
            }).then((result) => {
                window.location.href = 'manage_product.php';
            });
        </script>
    </body>
    </html>";
    exit;
} else {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Error</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                background-color: #f5f5f5;
            }
        </style>
    </head>
    <body>
        <script>
            Swal.fire({
                title: 'Error!',
                text: 'Error: " . mysqli_error($con) . "',
                icon: 'error',
                confirmButtonText: 'Go Back',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                window.location.href = 'manage_product.php';
            });
        </script>
    </body>
    </html>";
}
?>
