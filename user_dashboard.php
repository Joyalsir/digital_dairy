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
    $farmer_id = null;
    $farmer_name = $_SESSION['name'];
} else {
    $farmer = mysqli_fetch_assoc($query);
    $farmer_id = $farmer['id'];
    $farmer_name = $farmer['name'];
}

// Today's milk collection
$today_collection = 0;
$today_rate = 0;
$today_amount = 0;
if ($farmer_id) {
    $today_query = mysqli_query($con, "SELECT SUM(quantity) as qty, SUM(payment) as total_amt FROM milk_collection WHERE farmer_id='$farmer_id' AND DATE(date)=CURDATE()");
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
if ($farmer_id) {
    $total_query = mysqli_query($con, "SELECT SUM(payment) as total_amt FROM milk_collection WHERE farmer_id='$farmer_id'");
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
if ($farmer_id) {
    $total_query = mysqli_query($con, "SELECT SUM(quantity) as total_qty, COUNT(*) as records FROM milk_collection WHERE farmer_id='$farmer_id'");
    if ($row = mysqli_fetch_assoc($total_query)) {
        $total_collection = $row['total_qty'] ? $row['total_qty'] : 0;
        $total_records = $row['records'];
    }
}

// Latest notifications
$notifications = [];
if ($farmer_id) {
    $notif_query = mysqli_query($con, "SELECT date, quantity, payment FROM milk_collection WHERE farmer_id='$farmer_id' ORDER BY date DESC LIMIT 5");
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
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
            margin-left: 250px;
        }
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        .stat-icon.milk { color: #3498db; }
        .stat-icon.payment { color: #e74c3c; }
        .stat-icon.rate { color: #27ae60; }
        .notifications {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .notification-item {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .notification-item:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include('includes/user_sidebar.php'); ?>
        
        <div class="main-content">
            <div class="welcome-section">
                <h1>Welcome back, <?php echo htmlspecialchars($farmer_name); ?>!</h1>
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

