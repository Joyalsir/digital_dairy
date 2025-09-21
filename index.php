


<?php
session_start();
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_input = mysqli_real_escape_string($con, $_POST['login_input']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $hashed_password = md5($password);

    // Check if input is email or username
    $field = filter_var($login_input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    $sql = "SELECT * FROM farmers WHERE ($field = '$login_input') AND password = '$hashed_password' LIMIT 1";
    $result = mysqli_query($con, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['farmer_uuid'] = $user['uuid'];

        // Redirect to user dashboard or home page after login
        header("Location: user_dashboard.php");
        exit;
    } else {
        $login_error = "Invalid username/email or password.";
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
<?php
if (isset($_SESSION['logout_message'])) {
    echo "<script>alert('" . addslashes($_SESSION['logout_message']) . "');</script>";
    unset($_SESSION['logout_message']);
}
?>
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
        <?php if (!empty($login_error)) : ?>
            <div class="error-message" style="color: red; margin-bottom: 10px;">
                <?php echo htmlspecialchars($login_error); ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="input-box">
                <label>Username or Email</label>
                <input type="text" name="login_input" required placeholder="Enter your username or email">
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
            Don't have an account? <a href="add_farmer.php">Register here</a>
        </div>
    </div>
</div>

</div>
</body>
</html>
