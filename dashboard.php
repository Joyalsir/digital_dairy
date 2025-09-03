<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit;
}
include('includes/header.php');
include('includes/config.php'); // DB connection

// Fetch dashboard data
$total_farmers = 0;
$total_deliveries = 0;
$total_milk = 0;
$total_revenue = 0;

// Get total farmers
$result = mysqli_query($con, "SELECT COUNT(*) as total FROM farmers");
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

            <!-- Recent Activity -->
            <div class="recent-activity">
                <div class="activity-header">
                    <h3>Recent Activity</h3>
                    <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div class="activity-content">
                            <h4>New Farmer Added</h4>
                            <p>Ramesh Kumar has been registered as a new farmer</p>
                            <span class="activity-time">2 hours ago</span>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon info">
                            <i class="fas fa-tint"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Milk Collection Updated</h4>
                            <p>2450 liters collected from 47 farmers today</p>
                            <span class="activity-time">4 hours ago</span>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon warning">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Delivery Scheduled</h4>
                            <p>Delivery #DLV-2024-001 has been scheduled for tomorrow</p>
                            <span class="activity-time">6 hours ago</span>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon success">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Payment Processed</h4>
                            <p>Farmer payments for this month have been processed</p>
                            <span class="activity-time">1 day ago</span>
                        </div>
                    </div>
                </div>
            </div>

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
                        data: [45, 35, 20],
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
        });
    </script>
</body>
</html>
