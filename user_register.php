<?php
session_start();
include('includes/config.php');

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    if ($password !== $confirm_password) {
        $msg = "Passwords do not match!";
    } else {
        $hashed_password = md5($password); // You can use password_hash for more security
        $check_user = mysqli_query($con, "SELECT * FROM tblusers WHERE username='$username' OR email='$email'");
        
        if (mysqli_num_rows($check_user) > 0) {
            $msg = "Username or Email already exists!";
        } else {
            $insert = mysqli_query($con, "INSERT INTO tblusers(name, email, username, password) VALUES('$name', '$email', '$username', '$hashed_password')");
            if ($insert) {
                $msg = "Registration successful! You can now login.";
            } else {
                $msg = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Register - Digital Dairy</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: #f4f4f4;
        }
        .register-box {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h3 class="text-center mb-4">User Registration</h3>

    <?php if (!empty($msg)) { ?>
        <div class="alert alert-info"><?php echo $msg; ?></div>
    <?php } ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>

        <button type="submit" name="register" class="btn btn-primary btn-block">Register</button>
    </form>
    <p class="text-center mt-3">Already have an account? <a href="index.php">Login here</a></p>
</div>

</body>
</html>
