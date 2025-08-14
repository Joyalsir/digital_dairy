<?php
session_start();
include('includes/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Hardcoded Admin Login (for development/demo only)
    if ($email === 'admin@gmail.com' && $password === 'admin') {
        $_SESSION['admin_id'] = 0; // Use a fixed ID for hardcoded admin
        $_SESSION['email'] = $email;
        header("Location: dashboard.php");
        exit();
    }

    // 2. Database Admin Login
    // Use prepared statements to prevent SQL injection
    $stmt = mysqli_prepare($con, "SELECT ID, Password FROM admin WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row && md5($password) === $row['Password']) { // Still using md5 for compatibility
        $_SESSION['admin_id'] = $row['ID'];
        $_SESSION['email'] = $email;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid credentials');window.location='login.php';</script>";
    }
}
?>
