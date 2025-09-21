<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include('includes/config.php');

if (!isset($_GET['uuid'])) {
    echo "<script>alert('Invalid farmer UUID.'); window.location.href='manage_farmer.php';</script>";
    exit;
}

$uuid = mysqli_real_escape_string($con, $_GET['uuid']);

$check_fk = "SELECT COUNT(*) as count FROM milk_collection WHERE farmer_uuid = '$uuid'";
$result_fk = mysqli_query($con, $check_fk);
$row_fk = mysqli_fetch_assoc($result_fk);

if ($row_fk['count'] > 0) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Cannot Archive</title>
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
                title: 'Cannot Archive!',
                text: 'This farmer has associated milk collection records and cannot be archived. Please delete milk collection records first.',
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                window.location.href = 'manage_farmer.php';
            });
        </script>
    </body>
    </html>";
    exit;
}

$sql = "UPDATE farmers SET status = 'inactive' WHERE uuid = '$uuid'";

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
                title: 'Archived!',
                text: 'Farmer record archived successfully!',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745'
            }).then((result) => {
                window.location.href = 'manage_farmer.php';
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
                window.location.href = 'manage_farmer.php';
            });
        </script>
    </body>
    </html>";
}
?>
