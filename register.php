<?php
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $check = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Email already exists!');</script>";
    } else {
        $insert = mysqli_query($con, "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')");
        if ($insert) {
            echo "<script>alert('Registered successfully!');window.location='index.php';</script>";
        } else {
            echo "<script>alert('Error occurred');</script>";
        }
    }
}
?>
