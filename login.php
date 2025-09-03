<?php
session_start();
include('includes/config.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = mysqli_query($con, "SELECT * FROM user WHERE email='$email' AND password='$password'");
    $row = mysqli_fetch_array($query);

    if ($row) {
        $_SESSION['email'] = $email;
        header("Location: dashboard.php");
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
  <title>Digital Dairy Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class ="body.login-page">
  <div class="login-container">
    <div class="login-box">
      <img src="images/logo.png" alt="Logo" class="logo">
      <h2>Admin</h2>
          <form action="login-check.php" method="POST">
      <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
      <form method="POST">
        <div class="form-group">
          <label>Email address</label>
          <input type="email" name="email" required>
        </div>
        <div class="form-group">
          <label>Password</label>
          <div class="password-wrapper">
            <input type="password" name="password" id="password" required>
            <span class="toggle" onclick="togglePassword()">👁️</span>
          </div>
        </div>
        <div class="form-options">
          <label><input type="checkbox"> Remember me</label>
        </div>
        <div class="form-group">
          <button type="submit" name="login">Login</button>
        </div>
        
      </form>
    </div>
  </div>

  <script>
    function togglePassword() {
      const password = document.getElementById("password");
      password.type = password.type === "password" ? "text" : "password";
    }
  </script>
</body>
</html>
