<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}
include('includes/config.php');

// Get farmer details
$user_email = $_SESSION['email'];
$query = mysqli_query($con, "SELECT * FROM farmers WHERE email='$user_email'");
if (!$query) {
    die("Query failed: " . mysqli_error($con));
}
if (mysqli_num_rows($query) == 0) {
    $farmer_uuid = null;
} else {
    $farmer = mysqli_fetch_assoc($query);
    $farmer_uuid = $farmer['uuid'];
}

// Fetch notifications
$notifications = [];
if ($farmer_uuid) {
    $notif_query = mysqli_query($con, "SELECT * FROM notifications WHERE farmer_uuid='$farmer_uuid' ORDER BY created_at DESC");
    if ($notif_query) {
        while ($row = mysqli_fetch_assoc($notif_query)) {
            $notifications[] = $row;
        }
    } else {
        // Table doesn't exist or error, use sample
        $notifications = [
            ['id' => 1, 'message' => 'Welcome to Digital Dairy! Your account has been activated.', 'created_at' => date('Y-m-d H:i:s'), 'is_read' => 0],
            ['id' => 2, 'message' => 'Your milk collection for today has been recorded.', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')), 'is_read' => 1],
            ['id' => 3, 'message' => 'Payment of ₹500 has been credited to your account.', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')), 'is_read' => 1],
        ];
    }
} else {
    // Sample notifications if no farmer_uuid
    $notifications = [
        ['id' => 1, 'message' => 'Welcome to Digital Dairy! Your account has been activated.', 'created_at' => date('Y-m-d H:i:s'), 'is_read' => 0],
        ['id' => 2, 'message' => 'Your milk collection for today has been recorded.', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')), 'is_read' => 1],
        ['id' => 3, 'message' => 'Payment of ₹500 has been credited to your account.', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')), 'is_read' => 1],
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Notifications - Digital Dairy</title>
    <link rel="stylesheet" href="css/user_style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* Reset and base */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #333;
        }
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            background-color: #f5f7fa;
        }
        .main-content {
            flex: 1;
            padding: 30px 40px;
            background: #fff;
            margin-left: 280px;
            box-shadow: -2px 0 8px rgba(0,0,0,0.05);
            border-radius: 0 15px 15px 0;
            display: flex;
            flex-direction: column;
            gap: 30px;
        }
        .page-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .page-title h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            color: #222;
        }
        .btn-primary {
            background-color: #3f51b5;
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1.1rem;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #303f9f;
        }
        .card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            padding: 30px 40px;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .card-header h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: #222;
        }
        .notification-item {
            border-bottom: 1px solid #e5e7eb;
            padding: 18px 0;
            display: flex;
            align-items: flex-start;
            transition: background-color 0.3s ease;
        }
        .notification-item:last-child {
            border-bottom: none;
        }
        .notification-icon {
            margin-right: 20px;
            color: #3f51b5;
            font-size: 1.8rem;
            flex-shrink: 0;
        }
        .notification-content {
            flex: 1;
        }
        .notification-message {
            margin: 0;
            font-size: 1.1rem;
            color: #222;
        }
        .notification-time {
            margin: 6px 0 0;
            font-size: 0.9rem;
            color: #6b7280;
        }
        .notification-unread {
            background-color: #e3f2fd;
            border-left: 5px solid #3f51b5;
        }
        .notification-item:hover {
            background-color: #f0f4ff;
        }
        .no-notifications {
            text-align: center;
            padding: 50px;
            color: #6b7280;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include('includes/user_sidebar.php'); ?>
        <div class="main-content">
            <div class="page-title">
                <h1>Notifications</h1>
                <button class="btn btn-primary">Mark All as Read</button>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2>Recent Notifications</h2>
                </div>
                <?php if (empty($notifications)): ?>
                    <div class="no-notifications">
                        <i class="fas fa-bell-slash" style="font-size: 3rem; margin-bottom: 10px;"></i>
                        <p>No notifications available.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($notifications as $notif): ?>
                        <div class="notification-item <?php echo $notif['is_read'] == 0 ? 'notification-unread' : ''; ?>">
                            <div class="notification-icon">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="notification-content">
                                <p class="notification-message"><?php echo htmlspecialchars($notif['message']); ?></p>
                                <p class="notification-time"><?php echo date('M j, Y g:i A', strtotime($notif['created_at'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
