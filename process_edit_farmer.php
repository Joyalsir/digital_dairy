<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uuid = mysqli_real_escape_string($con, $_POST['uuid']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $contact = mysqli_real_escape_string($con, $_POST['contact']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $farm_size = mysqli_real_escape_string($con, $_POST['farm_size']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $aadhar = mysqli_real_escape_string($con, $_POST['aadhar']);
    $bank_account = mysqli_real_escape_string($con, $_POST['bank_account']);
    $ifsc = mysqli_real_escape_string($con, $_POST['ifsc']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($password) || !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
            exit;
        }
        $hashed_password = md5($password);
        $sql = "UPDATE farmers SET
                name='$name', contact='$contact', address='$address', farm_size='$farm_size',
                email='$email', aadhar='$aadhar', bank_account='$bank_account', ifsc='$ifsc',
                password='$hashed_password' WHERE uuid='$uuid'";
    } else {
        $sql = "UPDATE farmers SET
                name='$name', contact='$contact', address='$address', farm_size='$farm_size',
                email='$email', aadhar='$aadhar', bank_account='$bank_account', ifsc='$ifsc'
                WHERE uuid='$uuid'";
    }

    if (mysqli_query($con, $sql)) {
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
                    text: 'Farmer details updated successfully!',
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
                    window.history.back();
                });
            </script>
        </body>
        </html>";
    }
}
?>
