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
    $farmer_name = $_SESSION['name'];
} else {
    $farmer = mysqli_fetch_assoc($query);
    $farmer_uuid = $farmer['uuid'];
    $farmer_name = $farmer['name'];
}

// Today's milk collection
$today_collection = 0;
$today_rate = 0;
$today_amount = 0;
if ($farmer_uuid) {
    $today_query = mysqli_query($con, "SELECT SUM(quantity) as qty, SUM(payment) as total_amt FROM milk_collection WHERE farmer_uuid='$farmer_uuid' AND DATE(date)=CURDATE()");
    if (!$today_query) {
        die("Query failed: " . mysqli_error($con));
    }
    if ($row = mysqli_fetch_assoc($today_query)) {
        $today_collection = $row['qty'] ? $row['qty'] : 0;
        $today_amount = $row['total_amt'] ? $row['total_amt'] : 0;
        $today_rate = $today_collection > 0 ? $today_amount / $today_collection : 0;
    }
}

// Total payments
$total_payments = 0;
if ($farmer_uuid) {
    $total_query = mysqli_query($con, "SELECT SUM(payment) as total_amt FROM milk_collection WHERE farmer_uuid='$farmer_uuid'");
    if (!$total_query) {
        die("Query failed: " . mysqli_error($con));
    }
    if ($row = mysqli_fetch_assoc($total_query)) {
        $total_payments = $row['total_amt'] ? $row['total_amt'] : 0;
    }
}

// Total collection and records
$total_collection = 0;
$total_records = 0;
if ($farmer_uuid) {
    $total_query = mysqli_query($con, "SELECT SUM(quantity) as total_qty, COUNT(*) as records FROM milk_collection WHERE farmer_uuid='$farmer_uuid'");
    if ($row = mysqli_fetch_assoc($total_query)) {
        $total_collection = $row['total_qty'] ? $row['total_qty'] : 0;
        $total_records = $row['records'];
    }
}

// Latest notifications
$notifications = [];
if ($farmer_uuid) {
    $notif_query = mysqli_query($con, "SELECT date, quantity, payment FROM milk_collection WHERE farmer_uuid='$farmer_uuid' ORDER BY date DESC LIMIT 5");
    if (!$notif_query) {
        die("Query failed: " . mysqli_error($con));
    }
    while ($row = mysqli_fetch_assoc($notif_query)) {
        $notifications[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Digital Dairy</title>
    <link rel="stylesheet" href="css/user_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .sidebar {
            width: 280px;
            background: #3f51b5;
            color: #fff;
            padding: 30px 25px;
            box-shadow: 2px 0 8px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            font-weight: 600;
            font-size: 16px;
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
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            font-size: 1.3rem;
            font-weight: 600;
            letter-spacing: 0.03em;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }
        .stat-card {
            background: #fff;
            padding: 25px 20px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: default;
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            transition: color 0.3s ease;
        }
        .stat-icon.milk { color: #3f51b5; }
        .stat-icon.payment { color: #e91e63; }
        .stat-icon.rate { color: #4caf50; }
        .stat-icon.info { color: #2196f3; }
        .stat-icon.revenue { color: #ff9800; }
        .stat-card h3 {
            font-size: 1.8rem;
            margin-bottom: 8px;
            font-weight: 700;
            color: #222;
        }
        .stat-card p {
            font-size: 1rem;
            color: #666;
            letter-spacing: 0.02em;
        }
        .notifications {
            background: #fff;
            padding: 30px 25px;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            max-width: 700px;
            margin: 0 auto;
        }
        .notifications h3 {
            font-weight: 700;
            font-size: 1.4rem;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }
        .notification-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            font-size: 1rem;
            color: #444;
            transition: background-color 0.3s ease;
        }
        .notification-item:last-child {
            border-bottom: none;
        }
        .notification-item:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include('includes/user_sidebar.php'); ?>
        
        <div class="main-content">
            <div class="welcome-section">
                <h1>Welcome back, <?php echo htmlspecialchars($farmer_name); ?><?php if ($farmer_uuid) echo ' (UUID: ' . htmlspecialchars($farmer_uuid) . ')'; ?>!</h1>
                <p>Here's an overview of your milk collection and payments.</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon milk">
                        <i class="fas fa-tint"></i>
                    </div>
                    <h3><?php echo number_format($today_collection, 2); ?> L</h3>
                    <p>Today's Milk Collection</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon rate">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <h3>₹<?php echo number_format($today_rate, 2); ?></h3>
                    <p>Today's Rate (₹/L)</p>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon payment">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h3>₹<?php echo number_format($today_amount, 2); ?></h3>
                    <p>Today's Amount</p>
                </div>

                <div class="stat-card">
                    <div class="stat-icon milk">
                        <i class="fas fa-tint"></i>
                    </div>
                    <h3><?php echo number_format($total_collection, 2); ?> L</h3>
                    <p>Total Collection</p>
                </div>

                <div class="stat-card">
                    <div class="stat-icon info">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3><?php echo number_format($total_records); ?></h3>
                    <p>Total Records</p>
                </div>

                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <h3>₹<?php echo number_format($total_payments, 2); ?></h3>
                    <p>Total Payments</p>
                </div>
            </div>
            
            <div class="notifications">
                <h3>Latest Collections</h3>
                <?php if (empty($notifications)): ?>
                    <p>No recent collections found.</p>
                <?php else: ?>
                    <?php foreach ($notifications as $notif): ?>
                        <div class="notification-item">
                            <p><strong><?php echo date('d M Y', strtotime($notif['date'])); ?>:</strong> <?php echo $notif['quantity']; ?> L collected, Amount: ₹<?php echo number_format($notif['payment'], 2); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

