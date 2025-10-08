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
    $farmer_phone = '';
    $farmer_address = '';
    $farmer_username = '';
    $farmer_farm_size = '';
    $farmer_aadhar = '';
    $farmer_bank_account = '';
    $farmer_ifsc = '';
} else {
    $farmer = mysqli_fetch_assoc($query);
    $farmer_uuid = $farmer['uuid'];
    $farmer_name = $farmer['name'];
    $farmer_phone = $farmer['contact'] ?? '';
    $farmer_address = $farmer['address'] ?? '';
    $farmer_username = $farmer['username'] ?? '';
    $farmer_farm_size = $farmer['farm_size'] ?? '';
    $farmer_aadhar = $farmer['aadhar'] ?? '';
    $farmer_bank_account = $farmer['bank_account'] ?? '';
    $farmer_ifsc = $farmer['ifsc'] ?? '';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $farm_size = mysqli_real_escape_string($con, $_POST['farm_size']);
    $aadhar = mysqli_real_escape_string($con, $_POST['aadhar']);
    $bank_account = mysqli_real_escape_string($con, $_POST['bank_account']);
    $ifsc = mysqli_real_escape_string($con, $_POST['ifsc']);

    if ($farmer_uuid) {
        $update_query = "UPDATE farmers SET name='$name', contact='$phone', address='$address', username='$username', farm_size='$farm_size', aadhar='$aadhar', bank_account='$bank_account', ifsc='$ifsc' WHERE uuid='$farmer_uuid'";
    } else {
        // If no farmer record, perhaps insert, but for now, skip
        $update_query = null;
    }

    if ($update_query && mysqli_query($con, $update_query)) {
        $success = "Profile updated successfully.";
        // Update session name
        $_SESSION['name'] = $name;
        $farmer_name = $name;
    } else {
        $error = "Failed to update profile.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Profile - Digital Dairy</title>
    <link rel="stylesheet" href="css/user_style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        .main-content {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
            margin-left: 300px;
        }
        .page-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .page-title h1 {
            margin: 0;
            font-size: 1.8rem;
            font-weight: 700;
        }
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            padding: 20px 30px;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .card-header h2 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="number"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
        }
        .form-group textarea {
            resize: vertical;
            min-height: 80px;
        }
        .input-group {
            display: flex;
            align-items: center;
        }
        .input-group .form-control {
            flex: 1;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .input-addon {
            padding: 10px;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-left: none;
            border-top-right-radius: 6px;
            border-bottom-right-radius: 6px;
            color: #6b7280;
            font-size: 0.875rem;
        }
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .form-label i {
            margin-right: 0.5rem;
            color: #6b7280;
        }
        .btn-primary {
            background-color: #3b82f6;
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }
        .alert {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }
        .profile-avatar {
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-avatar i {
            font-size: 4rem;
            color: #3b82f6;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include('includes/user_sidebar.php'); ?>
        <div class="main-content">
            <div class="page-title">
                <h1>My Profile</h1>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2>Profile Information</h2>
                </div>
                <div class="profile-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="fas fa-user"></i> Full Name <span style="color: #ef4444;">*</span>
                        </label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($farmer_name); ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Email Address
                        </label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user_email); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label for="farmer_uuid_display" class="form-label">
                            <i class="fas fa-id-badge"></i> Farmer UUID
                        </label>
                        <input type="text" id="farmer_uuid_display" name="farmer_uuid_display" class="form-control" value="<?php echo htmlspecialchars($farmer_uuid); ?>" readonly />
                    </div>
                    <div class="form-group">
                        <label for="username" class="form-label">
                            <i class="fas fa-at"></i> Username
                        </label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($farmer_username); ?>" pattern="[a-zA-Z0-9_]{3,20}" title="Username must be 3-20 characters long and contain only letters, numbers, and underscores" />
                    </div>
                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone"></i> Contact Number
                        </label>
                        <input type="tel" id="phone" name="phone" class="form-control" value="<?php echo htmlspecialchars($farmer_phone); ?>" pattern="[6-9][0-9]{9}" title="Enter a valid 10-digit Indian phone number starting with 6-9" />
                    </div>
                    <div class="form-group">
                        <label for="address" class="form-label">
                            <i class="fas fa-map-marker-alt"></i> Address <span style="color: #ef4444;">*</span>
                        </label>
                        <textarea id="address" name="address" class="form-control" rows="3" required><?php echo htmlspecialchars($farmer_address); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="farm_size" class="form-label">
                            <i class="fas fa-tractor"></i> Farm Size
                        </label>
                        <div class="input-group">
                            <input type="number" id="farm_size" name="farm_size" class="form-control" value="<?php echo htmlspecialchars($farmer_farm_size); ?>" placeholder="0.0" step="0.01" min="0" />
                            <span class="input-addon">acres</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="aadhar" class="form-label">
                            <i class="fas fa-id-card"></i> Aadhar Number
                        </label>
                        <input type="text" id="aadhar" name="aadhar" class="form-control" value="<?php echo htmlspecialchars($farmer_aadhar); ?>" pattern="[0-9]{12}" title="Aadhar number must be exactly 12 digits" maxlength="12" />
                    </div>
                    <div class="form-group">
                        <label for="bank_account" class="form-label">
                            <i class="fas fa-university"></i> Bank Account
                        </label>
                        <input type="text" id="bank_account" name="bank_account" class="form-control" value="<?php echo htmlspecialchars($farmer_bank_account); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="ifsc" class="form-label">
                            <i class="fas fa-code"></i> IFSC Code
                        </label>
                        <input type="text" id="ifsc" name="ifsc" class="form-control" value="<?php echo htmlspecialchars($farmer_ifsc); ?>" />
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>