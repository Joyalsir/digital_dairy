<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}
include('includes/header.php');
include('includes/config.php');

if (!isset($_GET['uuid'])) {
    echo "<script>alert('Invalid farmer UUID.'); window.location.href='manage_farmer.php';</script>";
    exit;
}

$uuid = mysqli_real_escape_string($con, $_GET['uuid']);
$query = "SELECT * FROM farmers WHERE uuid = '$uuid'";
$result = mysqli_query($con, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "<script>alert('Farmer not found.'); window.location.href='manage_farmer.php';</script>";
    exit;
}
$farmer = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Farmer - Digital Dairy Management System</title>
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
                    <h1>View Farmer Details</h1>
                    <p>Complete information for farmer: <?php echo htmlspecialchars($farmer['name']); ?></p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <?php echo date("D, M j, Y - h:i A"); ?>
                </div>
            </div>

            <!-- Farmer Details Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-user"></i> Farmer Information</h3>
                    <p class="text-muted">Detailed profile of the selected farmer</p>
                </div>

                <div class="form-container">
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label><i class="fas fa-hashtag"></i> Farmer UUID</label>
                            <p><?php echo htmlspecialchars($farmer['uuid']); ?></p>
                        </div>

                        <div class="detail-item">
                            <label><i class="fas fa-user"></i> Full Name</label>
                            <p><?php echo htmlspecialchars($farmer['name']); ?></p>
                        </div>

                        <div class="detail-item">
                            <label><i class="fas fa-phone"></i> Contact Number</label>
                            <p><?php echo htmlspecialchars($farmer['contact']); ?></p>
                        </div>

                        <div class="detail-item">
                            <label><i class="fas fa-envelope"></i> Email Address</label>
                            <p><?php echo htmlspecialchars($farmer['email']); ?></p>
                        </div>

                        <div class="detail-item full-width">
                            <label><i class="fas fa-map-marker-alt"></i> Address</label>
                            <p><?php echo htmlspecialchars($farmer['address']); ?></p>
                        </div>

                        <div class="detail-item">
                            <label><i class="fas fa-tractor"></i> Farm Size</label>
                            <p><?php echo htmlspecialchars($farmer['farm_size']); ?> acres</p>
                        </div>

                        <div class="detail-item">
                            <label><i class="fas fa-id-card"></i> Aadhar Number</label>
                            <p><?php echo htmlspecialchars($farmer['aadhar']); ?></p>
                        </div>

                        <div class="detail-item">
                            <label><i class="fas fa-university"></i> Bank Account</label>
                            <p><?php echo htmlspecialchars($farmer['bank_account']); ?></p>
                        </div>

                        <div class="detail-item">
                            <label><i class="fas fa-code"></i> IFSC Code</label>
                            <p><?php echo htmlspecialchars($farmer['ifsc']); ?></p>
                        </div>

                        <div class="detail-item">
                            <label><i class="fas fa-calendar"></i> Registration Date</label>
                            <p><?php echo date('M j, Y', strtotime($farmer['created_at'])); ?></p>
                        </div>
                    </div>

                        <div class="form-actions">
                            <a href="manage_farmer.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Farmers
                            </a>
                            <a href="edit-farmer.php?uuid=<?php echo $farmer['uuid']; ?>" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Farmer
                            </a>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .detail-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            border-left: 4px solid #007bff;
        }

        .detail-item.full-width {
            grid-column: 1 / -1;
        }

        .detail-item label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .detail-item label i {
            margin-right: 0.5rem;
            color: #6b7280;
        }

        .detail-item p {
            margin: 0;
            color: #6b7280;
            font-size: 1rem;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</body>
</html>
<?php include('includes/footer.php'); ?>
