<?php
session_start();
session_unset();
session_destroy();
session_start();
$_SESSION['logout_message'] = "You have been logged out successfully.";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging Out...</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c5aa0;
            --secondary-color: #4caf50;
            --accent-color: #ff6b35;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --light-text: #6c757d;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--dark-text);
            background: linear-gradient(135deg, var(--light-bg) 0%, #e9ecef 50%, #dee2e6 100%);
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 50%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="2" fill="rgba(44,90,160,0.05)"/><circle cx="75" cy="75" r="1.5" fill="rgba(76,175,80,0.05)"/><circle cx="50" cy="10" r="1" fill="rgba(255,107,53,0.05)"/><circle cx="10" cy="60" r="1.5" fill="rgba(44,90,160,0.05)"/><circle cx="90" cy="40" r="2" fill="rgba(76,175,80,0.05)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            background-size: 200px 200px;
            opacity: 0.3;
            z-index: 1;
        }

        .logout-box {
            background: white;
            padding: 40px 50px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            animation: fadeInUp 0.8s ease-out;
            position: relative;
            z-index: 2;
            max-width: 500px;
            width: 100%;
        }

        .logout-box h2 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 2rem;
        }

        .logout-box p {
            color: var(--light-text);
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .logout-icon {
            font-size: 4rem;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .logout-box {
                padding: 30px 30px;
                margin: 20px;
            }

            .logout-box h2 {
                font-size: 1.8rem;
            }

            .logout-icon {
                font-size: 3rem;
            }
        }
    </style>
    <script>
        // Redirect to index page after 2 seconds
        setTimeout(function(){
            window.location.href = "index.php?logout=1";
        }, 2000);
    </script>
</head>
<body>
    <div class="logout-box">
        <div class="logout-icon">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <h2>Logged Out Successfully</h2>
        <p>You have been logged out successfully. Redirecting to home page...</p>
    </div>
</body>
</html>
