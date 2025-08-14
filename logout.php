<?php
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out...</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #ffffffff, #afc4ffff);
            font-family: 'Poppins', sans-serif;
        }
        .logout-box {
            background: #fff;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0px 6px 20px rgba(0,0,0,0.15);
            text-align: center;
            animation: fadeIn 0.5s ease-in-out;
        }
        .logout-box h2 {
            color: #006400;
            font-weight: 600;
        }
        .logout-box p {
            color: #555;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
    <script>
        // Redirect to login page after 2 seconds
        setTimeout(function(){
            window.location.href = "login.php";
        }, 2000);
    </script>
</head>
<body>
    <div class="logout-box">
        <h2>✅ Logged Out Successfully</h2>
        <p>Redirecting to login page...</p>
    </div>
</body>
</html>
