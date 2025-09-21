
<?php
session_start();
include('includes/config.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);

    $query = mysqli_query($con, "SELECT * FROM farmers WHERE email='$email' AND password='$password'");
    $row = mysqli_fetch_array($query);

    if ($row) {
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['uuid'] = $row['uuid'];
        $_SESSION['email'] = $email;
        header("Location: user_dashboard.php");
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
  <style>
    .login-container {
  background: #fff;
  padding: 40px;
  width: 100%;
  max-width: 360px;
  margin: 20px;
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
  border-radius: 12px;
}

.logo {
  display: block;
  margin: 0 auto 20px;
  max-width: 120px;
}

.login-box h2 {
  text-align: center;
  color: #2c2c54;
  margin-bottom: 20px;
}

.form-group {
  margin-bottom: 18px;
  display: flex;
  flex-direction: column;
}

.form-group label {
  font-size: 14px;
  color: #333;
  font-weight: 500;
  margin-bottom: 8px;
}

.form-group label span {
  color: red;
}

.form-group input,
.form-group textarea {
  width: 100%;
  padding: 10px 14px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 15px;
  transition: 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
  border-color: #007bff;
  outline: none;
}

.password-wrapper {
  position: relative;
}

.password-wrapper .toggle {
  position: absolute;
  right: 10px;
  top: 35%;
  cursor: pointer;
  font-size: 14px;
}

button {
  width: 100%;
  padding: 10px;
  background-color: #2c2c54;
  color: white;
  border: none;
  border-radius: 5px;
  font-weight: bold;
  cursor: pointer;
}

.form-options {
  display: flex;
  justify-content: space-between;
  font-size: 13px;
  margin-bottom: 10px;
}

.form-footer {
  text-align: center;
  font-size: 13px;
  margin-top: 10px;
}

.error {
  background: #ffe0e0;
  color: #c00;
  padding: 8px;
  border-radius: 5px;
  margin-bottom: 10px;
  text-align: center;
}
  </style>

  <script>
    function togglePassword() {
      const password = document.getElementById("password");
      password.type = password.type === "password" ? "text" : "password";
    }
  </script>
</body>
</html>
