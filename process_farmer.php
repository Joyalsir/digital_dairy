<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include('includes/config.php'); // contains $con

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data safely
    $name         = mysqli_real_escape_string($con, $_POST['name']);
    $contact      = mysqli_real_escape_string($con, $_POST['contact']);
    $address      = mysqli_real_escape_string($con, $_POST['address']);
    $farm_size    = mysqli_real_escape_string($con, $_POST['farm_size']);
    $email        = mysqli_real_escape_string($con, $_POST['email']);
    $aadhar       = mysqli_real_escape_string($con, $_POST['aadhar']);
    $bank_account = mysqli_real_escape_string($con, $_POST['bank_account']);
    $ifsc         = mysqli_real_escape_string($con, $_POST['ifsc']);

    // SQL Insert Query
    $sql = "INSERT INTO farmers 
            (name, contact, address, farm_size, email, aadhar, bank_account, ifsc) 
            VALUES 
            ('$name', '$contact', '$address', '$farm_size', '$email', '$aadhar', '$bank_account', '$ifsc')";

    if (mysqli_query($con, $sql)) {
        // Success → show popup and redirect back to add farmer
        echo "<!DOCTYPE html>
        <html>
        <head>
            <title>Success</title>
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
                    title: 'Success!',
                    text: 'Farmer registered successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745'
                }).then((result) => {
                    window.location.href = 'add_farmer.php';
                });
            </script>
        </body>
        </html>";
        exit;
    } else {
        // Error → show popup with error message
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
                    window.history.back();
                });
            </script>
        </body>
        </html>";
    }
}
?>
