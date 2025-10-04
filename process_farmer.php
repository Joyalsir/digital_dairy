<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include('includes/config.php'); // contains $con
include('includes/uuid_helper.php'); // Add UUID helper

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data safely
    $name         = mysqli_real_escape_string($con, $_POST['name']);
    $username     = mysqli_real_escape_string($con, $_POST['username']);
    $contact      = mysqli_real_escape_string($con, $_POST['contact']);
    $address      = mysqli_real_escape_string($con, $_POST['address']);
    $farm_size    = mysqli_real_escape_string($con, $_POST['farm_size']);
    $email        = mysqli_real_escape_string($con, $_POST['email']);
    $aadhar       = mysqli_real_escape_string($con, $_POST['aadhar']);
    $bank_account = mysqli_real_escape_string($con, $_POST['bank_account']);
    $ifsc         = mysqli_real_escape_string($con, $_POST['ifsc']);
    $password     = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    // Validate email
    if (empty($email)) {
        echo "<script>alert('Email is required!'); window.history.back();</script>";
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format!'); window.history.back();</script>";
        exit;
    }
    // Check for duplicate email
    $check_query = mysqli_query($con, "SELECT id FROM farmers WHERE email='$email'");
    if (mysqli_num_rows($check_query) > 0) {
        echo "<script>alert('Email already exists!'); window.history.back();</script>";
        exit;
    }

    // Validate username
    if (empty($username)) {
        echo "<script>alert('Username is required!'); window.history.back();</script>";
        exit;
    }
    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        echo "<script>alert('Username must be 3-20 characters long and contain only letters, numbers, and underscores!'); window.history.back();</script>";
        exit;
    }
    // Check for duplicate username
    $check_query = mysqli_query($con, "SELECT id FROM farmers WHERE username='$username'");
    if (mysqli_num_rows($check_query) > 0) {
        echo "<script>alert('Username already exists! Please choose a different username.'); window.history.back();</script>";
        exit;
    }

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit;
    }

    // Validate password strength
    if (strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long!'); window.history.back();</script>";
        exit;
    }
    if (!preg_match('/[A-Z]/', $password)) {
        echo "<script>alert('Password must contain at least one uppercase letter!'); window.history.back();</script>";
        exit;
    }
    if (!preg_match('/[a-z]/', $password)) {
        echo "<script>alert('Password must contain at least one lowercase letter!'); window.history.back();</script>";
        exit;
    }
    if (!preg_match('/[0-9]/', $password)) {
        echo "<script>alert('Password must contain at least one number!'); window.history.back();</script>";
        exit;
    }
    if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/', $password)) {
        echo "<script>alert('Password must contain at least one special character!'); window.history.back();</script>";
        exit;
    }

    $hashed_password = md5($password);

    // Use the UUID from the form instead of generating a new one
    $uuid = mysqli_real_escape_string($con, $_POST['farmer_uuid']);

    // Verify the UUID is not empty and check for uniqueness
    if (empty($uuid)) {
        echo "<script>alert('UUID is required!'); window.history.back();</script>";
        exit;
    }

    // Check if UUID already exists
    $check_query = mysqli_query($con, "SELECT id FROM farmers WHERE uuid='$uuid'");
    if (mysqli_num_rows($check_query) > 0) {
        echo "<script>alert('UUID already exists! Please try again.'); window.history.back();</script>";
        exit;
    }

    // Validate Aadhar number
    if (!empty($aadhar) && !preg_match('/^[0-9]{12}$/', $aadhar)) {
        echo "<script>alert('Aadhar number must be exactly 12 digits!'); window.history.back();</script>";
        exit;
    }

    // SQL Insert Query
    $sql = "INSERT INTO farmers
            (uuid, name, username, contact, address, farm_size, email, aadhar, bank_account, ifsc, password)
            VALUES
            ('$uuid', '$name', '$username', '$contact', '$address', '$farm_size', '$email', '$aadhar', '$bank_account', '$ifsc', '$hashed_password')";

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
