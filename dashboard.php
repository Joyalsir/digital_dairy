<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location:login.php");
  exit;
}
include('includes/header.php');
include('includes/config.php'); // DB connection

// Fetch dashboard data
$total_farmers = 0;
$total_deliveries = 0;
$total_milk = 0;
$total_revenue = 0;

// Get total farmers (active only, to match manage_farmer.php table)
$result = mysqli_query($con, "SELECT COUNT(*) as total FROM farmers WHERE status = 'active' OR status IS NULL");
if ($row = mysqli_fetch_assoc($result)) {
    $total_farmers = $row['total'];
}

// Get total deliveries
$result = mysqli_query($con, "SELECT COUNT(*) as total FROM tbldelivery");
if ($row = mysqli_fetch_assoc($result)) {
    $total_deliveries = $row['total'];
}

// Get total milk collection (liters)
$result = mysqli_query($con, "SELECT SUM(quantity) as total FROM milk_collection");
if ($row = mysqli_fetch_assoc($result)) {
    $total_milk = $row['total'] ? $row['total'] : 0;
}

// Get total revenue (from tblsales)
$result = mysqli_query($con, "SELECT SUM(TotalAmount) as total FROM tblsales");
if ($row = mysqli_fetch_assoc($result)) {
    $total_revenue = $row['total'] ? $row['total'] : 0;
}

// Get active farmers
$result = mysqli_query($con, "SELECT COUNT(*) as total FROM farmers WHERE status='active'");
if ($row = mysqli_fetch_assoc($result)) {
    $active_farmers = $row['total'];
}

// Get active customers
$result = mysqli_query($con, "SELECT COUNT(*) as total FROM tblcustomer WHERE status='active'");
if ($row = mysqli_fetch_assoc($result)) {
    $active_customers = $row['total'];
}

// Get monthly milk collection (for chart)
$monthly_data = [];
for ($i = 1; $i <= 12; $i++) {
    $result = mysqli_query($con, "SELECT SUM(quantity) as total 
                                  FROM milk_collection 
                                  WHERE MONTH(date) = $i");
    $row = mysqli_fetch_assoc($result);
    $monthly_data[] = $row['total'] ? $row['total'] : 0;
}

// Get monthly sales revenue (for Expenses vs Income chart)
$monthly_sales = [];
for ($i = 1; $i <= 12; $i++) {
    $result = mysqli_query($con, "SELECT SUM(TotalAmount) as total 
                                  FROM tblsales 
                                  WHERE MONTH(SalesDate) = $i");
    $row = mysqli_fetch_assoc($result);
    $monthly_sales[] = $row['total'] ? $row['total'] : 0;
}

// Get product sales distribution
$product_sales = [];
$result = mysqli_query($con, "SELECT ProductName, SUM(Quantity) as total_qty 
                              FROM tblsales 
                              GROUP BY ProductName");
while ($row = mysqli_fetch_assoc($result)) {
    $product_sales[$row['ProductName']] = $row['total_qty'];
}

// Get milk collection distribution by time of day (morning, evening, night)
$time_distribution = [
    'Morning' => 0,
    'Evening' => 0,
    'Night' => 0
];
$result = mysqli_query($con, "
    SELECT 
        CASE 
            WHEN HOUR(date) BETWEEN 5 AND 11 THEN 'Morning'
            WHEN HOUR(date) BETWEEN 12 AND 17 THEN 'Evening'
            ELSE 'Night'
        END as time_of_day,
        SUM(quantity) as total_qty
    FROM milk_collection
    GROUP BY time_of_day
");
while ($row = mysqli_fetch_assoc($result)) {
    $time_distribution[$row['time_of_day']] = $row['total_qty'];
}

$active_farmers_list = [];
$result = mysqli_query($con, "SELECT name as FarmerName, contact FROM farmers WHERE status='active' OR status IS NULL ORDER BY created_at DESC LIMIT 10");
while ($row = mysqli_fetch_assoc($result)) {
    $active_farmers_list[] = $row;
}

$active_customers_list = [];
$result = mysqli_query($con, "SELECT customer_name, contact FROM tblcustomer WHERE status='active' ORDER BY registration_date DESC LIMIT 10");
while ($row = mysqli_fetch_assoc($result)) {
    $active_customers_list[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Digital Dairy Management System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
</head>
<body>
    <div class="dashboard-container">
        <?php include('includes/sidebar.php'); ?>
        
        <div class="dashboard-main">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <div class="dashboard-title">
                    <h1>Dashboard Overview</h1>
                    <p>Welcome back! Here's what's happening with your dairy today.</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <?php echo date("D, M j, Y - h:i A"); ?>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon farmers">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($total_farmers); ?></h3>
                        <p>Total Farmers</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon deliveries">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($total_deliveries); ?></h3>
                        <p>Total Deliveries</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon milk">
                        <i class="fas fa-tint"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?php echo number_format($total_milk); ?> L</h3>
                        <p>Total Milk Collected</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="stat-content">
                        <h3>₹<?php echo number_format($total_revenue); ?></h3>
                        <p>Total Revenue</p>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="charts-container">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Monthly Milk Collection Trend</h3>
                        <select id="yearFilter" class="form-select" style="width: auto;">
                            <option value="2024">2024</option>
                            <option value="2023">2023</option>
                        </select>
                    </div>
                    <div class="chart-canvas">
                        <canvas id="milkCollectionChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <h3>Collection Distribution</h3>
                    </div>
                    <div class="chart-canvas">
                        <canvas id="collectionPieChart"></canvas>
                    </div>
                </div>
            </div>

            <?php
            // Fetch recent activities from multiple tables
            $recent_activities = [];

            // New farmers (assuming CreatedAt column exists; adjust if needed)
            $query_farmers = "SELECT 'farmer' AS activity_type, FarmerName AS title, CONCAT(FarmerName, ' has been registered as a new farmer') AS description, CreatedAt AS activity_date
                              FROM farmers
                              WHERE CreatedAt IS NOT NULL
                              ORDER BY CreatedAt DESC
                              LIMIT 3";

            $result_farmers = mysqli_query($con, $query_farmers);
            if ($result_farmers) {
                while ($row = mysqli_fetch_assoc($result_farmers)) {
                    $recent_activities[] = $row;
                }
            }

            // Milk collection updates (grouped by date)
            $query_milk = "SELECT 'milk_collection' AS activity_type, 'Milk Collection Updated' AS title,
                                  CONCAT(FORMAT(SUM(quantity), 0), ' liters collected from ', COUNT(DISTINCT FarmerID), ' farmers on ', DATE_FORMAT(date, '%M %e')) AS description,
                                  DATE(date) AS activity_date
                           FROM milk_collection
                           WHERE date IS NOT NULL
                           GROUP BY DATE(date)
                           ORDER BY DATE(date) DESC
                           LIMIT 3";

            $result_milk = mysqli_query($con, $query_milk);
            if ($result_milk) {
                while ($row = mysqli_fetch_assoc($result_milk)) {
                    $recent_activities[] = $row;
                }
            }

            // Deliveries scheduled
            $query_delivery = "SELECT 'delivery' AS activity_type, 'Delivery Scheduled' AS title,
                                      CONCAT('Delivery #', DeliveryID, ' has been scheduled for ', DATE_FORMAT(DeliveryDate, '%M %e')) AS description,
                                      DeliveryDate AS activity_date
                               FROM tbldelivery
                               WHERE DeliveryDate IS NOT NULL
                               ORDER BY DeliveryDate DESC
                               LIMIT 3";

            $result_delivery = mysqli_query($con, $query_delivery);
            if ($result_delivery) {
                while ($row = mysqli_fetch_assoc($result_delivery)) {
                    $recent_activities[] = $row;
                }
            }

            // Payments processed (from tblsales)
            $query_payments = "SELECT 'payment' AS activity_type, 'Payment Processed' AS title,
                                      CONCAT('Payment of ₹', FORMAT(TotalAmount, 0), ' processed on ', DATE_FORMAT(SalesDate, '%M %e')) AS description,
                                      SalesDate AS activity_date
                               FROM tblsales
                               WHERE SalesDate IS NOT NULL
                               ORDER BY SalesDate DESC
                               LIMIT 3";

            $result_payments = mysqli_query($con, $query_payments);
            if ($result_payments) {
                while ($row = mysqli_fetch_assoc($result_payments)) {
                    $recent_activities[] = $row;
                }
            }

            // Sort all activities by activity_date descending
            usort($recent_activities, function($a, $b) {
                return strtotime($b['activity_date']) - strtotime($a['activity_date']);
            });

            // Limit to 4 most recent activities
            $recent_activities = array_slice($recent_activities, 0, 4);
            ?>

          

            <!-- Recent Activity -->
            <div class="recent-activity">
                <div class="activity-header">
                    <h3>Recent Activity</h3>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="activity-list">
                    <?php if (empty($recent_activities)): ?>
                        <div class="activity-item">
                            <div class="activity-icon secondary">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="activity-content">
                                <h4>No Recent Activity</h4>
                                <p>No recent activities to display.</p>
                                <span class="activity-time">-</span>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recent_activities as $activity): ?>
                            <?php
                                // Map activity type to icon and color class
                                switch ($activity['activity_type']) {
                                    case 'farmer':
                                        $icon = 'fa-plus';
                                        $color_class = 'success';
                                        break;
                                    case 'milk_collection':
                                        $icon = 'fa-tint';
                                        $color_class = 'info';
                                        break;
                                    case 'delivery':
                                        $icon = 'fa-truck';
                                        $color_class = 'warning';
                                        break;
                                    case 'payment':
                                        $icon = 'fa-check';
                                        $color_class = 'success';
                                        break;
                                    default:
                                        $icon = 'fa-info-circle';
                                        $color_class = 'secondary';
                                }

                                // Calculate relative time
                                $now = new DateTime();
                                $activity_time = new DateTime($activity['activity_date']);
                                $diff = $now->diff($activity_time);

                                if ($diff->days > 0) {
                                    $time_ago = $diff->days . ' day' . ($diff->days > 1 ? 's' : '') . ' ago';
                                } elseif ($diff->h > 0) {
                                    $time_ago = $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
                                } elseif ($diff->i > 0) {
                                    $time_ago = $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
                                } else {
                                    $time_ago = 'Just now';
                                }
                            ?>
                            <div class="activity-item">
                                <div class="activity-icon <?php echo $color_class; ?>">
                                    <i class="fas <?php echo $icon; ?>"></i>
                                </div>
                                <div class="activity-content">
                                    <h4><?php echo htmlspecialchars($activity['title']); ?></h4>
                                    <p><?php echo htmlspecialchars($activity['description']); ?></p>
                                    <span class="activity-time"><?php echo $time_ago; ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Active Customers and Farmers Section -->
            <div class="active-section" style="margin-top: 2rem;">
                <div class="section-header">
                    <h3><i class="fas fa-users-cog"></i> Active Customers and Farmers</h3>
                    <p class="section-subtitle">Quick overview of your active stakeholders</p>
                </div>
                <div class="active-lists" style="display: flex; gap: 2rem; flex-wrap: wrap;">
                    <div class="active-customers" style="flex: 1; min-width: 300px;">
                        <div class="list-header">
                            <h4><i class="fas fa-users"></i> Active Customers <span class="count">(<?php echo count($active_customers_list); ?>)</span></h4>
                            <input type="text" id="customerSearch" placeholder="Search customers..." class="search-input">
                        </div>
                        <div id="customerList" class="list-container">
                            <?php if (empty($active_customers_list)): ?>
                                <div class="empty-state">
                                    <i class="fas fa-users"></i>
                                    <p>No active customers found</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($active_customers_list as $customer): ?>
                                    <div class="list-item">
                                        <div class="item-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="item-content">
                                            <strong><?php echo htmlspecialchars($customer['customer_name']); ?></strong>
                                            <small><?php echo htmlspecialchars($customer['contact']); ?></small>
                                        </div>
                                        <div class="item-actions">
                                            <button class="btn-icon" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="active-farmers" style="flex: 1; min-width: 300px;">
                        <div class="list-header">
                            <h4><i class="fas fa-tractor"></i> Active Farmers <span class="count">(<?php echo count($active_farmers_list); ?>)</span></h4>
                            <input type="text" id="farmerSearch" placeholder="Search farmers..." class="search-input">
                        </div>
                        <div id="farmerList" class="list-container">
                            <?php if (empty($active_farmers_list)): ?>
                                <div class="empty-state">
                                    <i class="fas fa-tractor"></i>
                                    <p>No active farmers found</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($active_farmers_list as $farmer): ?>
                                    <div class="list-item">
                                        <div class="item-icon">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="item-content">
                                            <strong><?php echo htmlspecialchars($farmer['FarmerName']); ?></strong>
                                            <small><?php echo htmlspecialchars($farmer['contact']); ?></small>
                                        </div>
                                        <div class="item-actions">
                                            <button class="btn-icon" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <style>
            .active-section {
                background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
                border-radius: 12px;
                padding: 2rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            }

            .section-header {
                text-align: center;
                margin-bottom: 2rem;
            }

            .section-header h3 {
                color: #1f2937;
                font-size: 1.5rem;
                margin-bottom: 0.5rem;
            }

            .section-subtitle {
                color: #6b7280;
                font-size: 0.9rem;
                margin: 0;
            }

            .list-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
                padding-bottom: 0.5rem;
                border-bottom: 1px solid #e5e7eb;
            }

            .list-header h4 {
                margin: 0;
                color: #374151;
                font-size: 1.1rem;
            }

            .count {
                background: #dbeafe;
                color: #1d4ed8;
                padding: 0.2rem 0.5rem;
                border-radius: 12px;
                font-size: 0.8rem;
                font-weight: 600;
            }

            .search-input {
                width: 100%;
                padding: 0.5rem 1rem;
                border: 1px solid #d1d5db;
                border-radius: 8px;
                font-size: 0.9rem;
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            .search-input:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }

            .list-container {
                max-height: 400px;
                overflow-y: auto;
                border: 1px solid #e5e7eb;
                border-radius: 8px;
                background: white;
            }

            .list-item {
                display: flex;
                align-items: center;
                padding: 1rem;
                border-bottom: 1px solid #f3f4f6;
                transition: background-color 0.2s;
            }

            .list-item:hover {
                background-color: #f9fafb;
            }

            .list-item:last-child {
                border-bottom: none;
            }

            .item-icon {
                width: 40px;
                height: 40px;
                background: #dbeafe;
                color: #1d4ed8;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 1rem;
                font-size: 1rem;
            }

            .active-farmers .item-icon {
                background: #dcfce7;
                color: #166534;
            }

            .item-content {
                flex: 1;
            }

            .item-content strong {
                display: block;
                color: #111827;
                font-size: 0.95rem;
            }

            .item-content small {
                color: #6b7280;
                font-size: 0.85rem;
            }

            .item-actions {
                margin-left: 1rem;
            }

            .btn-icon {
                background: none;
                border: none;
                color: #6b7280;
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 4px;
                transition: color 0.2s, background-color 0.2s;
            }

            .btn-icon:hover {
                color: #374151;
                background-color: #f3f4f6;
            }

            .empty-state {
                text-align: center;
                padding: 3rem 1rem;
                color: #6b7280;
            }

            .empty-state i {
                font-size: 3rem;
                margin-bottom: 1rem;
                opacity: 0.5;
            }

            .empty-state p {
                margin: 0;
                font-size: 0.9rem;
            }

            @media (max-width: 768px) {
                .active-lists {
                    flex-direction: column;
                }

                .active-section {
                    padding: 1rem;
                }

                .list-container {
                    max-height: 300px;
                }
            }
            </style>
            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <div class="actions-grid">
                    <a href="add_farmer.php" class="action-btn">
                        <i class="fas fa-user-plus"></i>
                        Add New Farmer
                    </a>
                    <a href="add_collection.php" class="action-btn">
                        <i class="fas fa-tint"></i>
                        Record Collection
                    </a>
                    <a href="add_delivery.php" class="action-btn">
                        <i class="fas fa-truck"></i>
                        Schedule Delivery
                    </a>
                    <a href="milk_collection_report.php" class="action-btn">
                        <i class="fas fa-file-alt"></i>
                        View Reports
                    </a>
                    <a href="manage_farmer.php" class="action-btn">
                        <i class="fas fa-users"></i>
                        Manage Farmers
                    </a>
                    <a href="payment_report.php" class="action-btn">
                        <i class="fas fa-rupee-sign"></i>
                        Payment Reports
                    </a>
                </div>
            </div>

            
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Monthly Milk Collection Chart
            const milkCtx = document.getElementById('milkCollectionChart').getContext('2d');
            const monthlyData = <?php echo json_encode($monthly_data); ?>;
            
            new Chart(milkCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Milk Collection (Liters)',
                        data: monthlyData,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    }
                }
            });

            // Collection Distribution Pie Chart
            const pieCtx = document.getElementById('collectionPieChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Morning', 'Evening', 'Night'],
            datasets: [{
                data: <?php echo json_encode(array_values($time_distribution)); ?>,
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0
            }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20
                            }
                        }
                    }
                }
            });

            // Year filter functionality
            document.getElementById('yearFilter').addEventListener('change', function(e) {
                // Here you would typically fetch new data based on the selected year
                console.log('Year changed to:', e.target.value);
            });

            // Search functionality for active customers
            document.getElementById('customerSearch').addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const list = document.getElementById('customerList');
                const items = list.querySelectorAll('.list-item');
                items.forEach(item => {
                    const text = item.textContent || item.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });

            // Search functionality for active farmers
            document.getElementById('farmerSearch').addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const list = document.getElementById('farmerList');
                const items = list.querySelectorAll('.list-item');
                items.forEach(item => {
                    const text = item.textContent || item.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        item.style.display = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>