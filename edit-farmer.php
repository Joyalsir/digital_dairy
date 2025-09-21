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
    <title>Edit Farmer - Digital Dairy Management System</title>
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
                    <h1>Edit Farmer</h1>
                    <p>Update details for farmer: <?php echo htmlspecialchars($farmer['name']); ?></p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <?php echo date("D, M j, Y - h:i A"); ?>
                </div>
            </div>

            <!-- Edit Farmer Form Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-user-edit"></i> Farmer Edit Form</h3>
                    <p class="text-muted">Modify the farmer's information and save changes</p>
                </div>

                <div class="form-container">
                    <form action="process_edit_farmer.php" method="post" class="modern-form">
                        <input type="hidden" name="uuid" value="<?php echo $farmer['uuid']; ?>">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user"></i> Full Name <span class="required">*</span>
                                </label>
                                <input type="text" id="name" name="name" class="form-control" required
                                       placeholder="Enter farmer's full name" value="<?php echo htmlspecialchars($farmer['name']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="contact" class="form-label">
                                    <i class="fas fa-phone"></i> Contact Number
                                </label>
                                <input type="tel" id="contact" name="contact" class="form-control"
                                       placeholder="Enter phone number" value="<?php echo htmlspecialchars($farmer['contact']); ?>">
                            </div>

                            <div class="form-group full-width">
                                <label for="address" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i> Address <span class="required">*</span>
                                </label>
                                <textarea id="address" name="address" class="form-control" rows="3" required
                                          placeholder="Enter complete address"><?php echo htmlspecialchars($farmer['address']); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="farm_size" class="form-label">
                                    <i class="fas fa-tractor"></i> Farm Size
                                </label>
                                <div class="input-group">
                                    <input type="number" id="farm_size" name="farm_size" class="form-control"
                                           placeholder="0.0" step="0.01" min="0" value="<?php echo htmlspecialchars($farmer['farm_size']); ?>">
                                    <span class="input-addon">acres</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email Address
                                </label>
                                <input type="email" id="email" name="email" class="form-control"
                                       placeholder="Enter email address" value="<?php echo htmlspecialchars($farmer['email']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="aadhar" class="form-label">
                                    <i class="fas fa-id-card"></i> Aadhar Number
                                </label>
                                <input type="text" id="aadhar" name="aadhar" class="form-control"
                                       placeholder="Enter 12-digit Aadhar number" maxlength="12" value="<?php echo htmlspecialchars($farmer['aadhar']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="bank_account" class="form-label">
                                    <i class="fas fa-university"></i> Bank Account
                                </label>
                                <input type="text" id="bank_account" name="bank_account" class="form-control"
                                       placeholder="Enter bank account number" value="<?php echo htmlspecialchars($farmer['bank_account']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="ifsc" class="form-label">
                                    <i class="fas fa-code"></i> IFSC Code
                                </label>
                                <input type="text" id="ifsc" name="ifsc" class="form-control"
                                       placeholder="Enter IFSC code" value="<?php echo htmlspecialchars($farmer['ifsc']); ?>">
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock"></i> New Password (leave blank to keep current)
                                </label>
                                <input type="password" id="password" name="password" class="form-control"
                                       placeholder="Enter new password">
                            </div>

                            <div class="form-group">
                                <label for="confirm_password" class="form-label">
                                    <i class="fas fa-lock"></i> Confirm New Password
                                </label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                                       placeholder="Confirm new password">
                            </div>

                            <div class="form-group">
                                <label for="farmer_uuid_display" class="form-label">
                                    <i class="fas fa-id-badge"></i> Farmer UUID
                                </label>
                                <input type="text" id="farmer_uuid_display" name="farmer_uuid_display" class="form-control"
                                       value="<?php echo htmlspecialchars($farmer['uuid']); ?>" readonly>
                                <small class="form-text text-muted">Unique farmer identifier (cannot be changed)</small>
                            </div>
                        </div>

                        <div class="form-actions">
                            <a href="view-farmer.php?uuid=<?php echo $farmer['uuid']; ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i> Reset Changes
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Farmer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php include('includes/footer.php'); ?>
