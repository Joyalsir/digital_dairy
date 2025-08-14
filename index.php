<?php
session_start();
include('includes/config.php');

if (isset($_POST['index'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = "SELECT * FROM tblusers WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        $_SESSION['email'] = $email;
        header("Location: user_dashboard.php"); // Redirect to dashboard after login
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Digital Dairy</title>
  <link rel="stylesheet" href="user_style.css">
</head>
<body>
<div class="container">
    <div class="left text-panel">
    <div class="text-content">
        <h1>Digital Dairy Management System</h1>
        <p>
            This system helps streamline operations in small to mid-sized dairy farms, similar to MILMA. 
            It features modules for managing farmer records, milk collection, product types, delivery tracking, and admin reporting.
        </p>
        <p>
            Built using PHP and MySQL, it is designed for ease of use, data accuracy, and transparency in daily dairy operations.
        </p>
        <ul>
            <li>Farmer & Milk Collection</li>
            <li>Product Delivery Management</li>
            <li>Sales, Expenses & Reporting</li>
        </ul>
    </div>
</div>

   <div class="right">
    <div class="login-box">
        <h2>User Login</h2>
        <form method="POST" action="index.php">
            <div class="input-box">
                <label>Email address</label>
                <input type="email" name="email" required>
            </div>
            <div class="input-box">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-options">
                <label><input type="checkbox" name="remember"> Remember me</label>
                <a href="forgot-password.php" class="link">Forgot Password?</a>
            </div>
            <button type="submit">Login</button>
        </form>
        <div class="register-link">
            Don't have an account? <a href="user_register.php">Register here</a>
        </div>
    </div>
</div>

</div>
</body>
</html>