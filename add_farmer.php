<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit;
}
include('includes/header.php');
include('includes/config.php');
include('includes/uuid_helper.php');

// Generate UUID on server-side for consistency
$farmer_uuid = generateShortUUID();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Farmer - Digital Dairy Management System</title>
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
                    <h1>Add New Farmer</h1>
                    <p>Register a new farmer in the dairy management system</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <span id="currentDateTime"></span>
                </div>
            </div>

            <!-- Add Farmer Form Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-user-plus"></i> Farmer Registration Form</h3>
                    <p class="text-muted">Please fill in all required fields to register a new farmer</p>
                </div>

                <div class="form-container">
                    <form action="process_farmer.php" method="post" class="modern-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user"></i> Full Name <span class="required">*</span>
                                </label>
                                <input type="text" id="name" name="name" class="form-control" required
                                       placeholder="Enter farmer's full name">
                            </div>

                            <div class="form-group">
                                <label for="username" class="form-label">
                                    <i class="fas fa-at"></i> Username <span class="required">*</span>
                                </label>
                                <input type="text" id="username" name="username" class="form-control" required
                                       placeholder="Enter unique username" pattern="[a-zA-Z0-9_]{3,20}"
                                       title="Username must be 3-20 characters long and contain only letters, numbers, and underscores">
                                <small class="form-text text-muted">Choose a unique username for easy identification</small>
                            </div>

                            <div class="form-group">
                                <label for="contact" class="form-label">
                                    <i class="fas fa-phone"></i> Contact Number
                                </label>
                                <input type="tel" id="contact" name="contact" class="form-control"
                                       placeholder="Enter 10-digit phone number" pattern="[6-9][0-9]{9}"
                                       title="Enter a valid 10-digit Indian phone number starting with 6-9">
                                <small class="form-text text-muted">Enter a valid 10-digit phone number (e.g., 9876543210)</small>
                            </div>

                            <div class="form-group full-width">
                                <label for="address" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i> Address <span class="required">*</span>
                                </label>
                                <textarea id="address" name="address" class="form-control" rows="3" required
                                          placeholder="Enter complete address"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="farm_size" class="form-label">
                                    <i class="fas fa-tractor"></i> Farm Size
                                </label>
                                <div class="input-group">
                                    <input type="number" id="farm_size" name="farm_size" class="form-control"
                                           placeholder="0.0" step="0.01" min="0">
                                    <span class="input-addon">acres</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i> Email Address <span class="required">*</span>
                                </label>
                                <input type="email" id="email" name="email" class="form-control" required
                                       placeholder="Enter email address">
                            </div>

                            <div class="form-group">
                                <label for="aadhar" class="form-label">
                                    <i class="fas fa-id-card"></i> Aadhar Number
                                </label>
                                <input type="text" id="aadhar" name="aadhar" class="form-control"
                                       placeholder="Enter 12-digit Aadhar number" pattern="[0-9]{12}"
                                       title="Aadhar number must be exactly 12 digits" maxlength="12">
                                <small class="form-text text-muted">Enter a valid 12-digit Aadhar number (e.g., 123456789012)</small>
                            </div>

                            <div class="form-group">
                                <label for="bank_account" class="form-label">
                                    <i class="fas fa-university"></i> Bank Account
                                </label>
                                <input type="text" id="bank_account" name="bank_account" class="form-control"
                                       placeholder="Enter bank account number">
                            </div>

                            <div class="form-group">
                                <label for="ifsc" class="form-label">
                                    <i class="fas fa-code"></i> IFSC Code
                                </label>
                                <input type="text" id="ifsc" name="ifsc" class="form-control"
                                       placeholder="Enter IFSC code">
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock"></i> Password <span class="required">*</span>
                                </label>
                                <input type="password" id="password" name="password" class="form-control" required
                                       placeholder="Enter password">
                                <small class="form-text text-muted">Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character.</small>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password" class="form-label">
                                    <i class="fas fa-lock"></i> Confirm Password <span class="required">*</span>
                                </label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required
                                       placeholder="Confirm password">
                            </div>

                            <div class="form-group">
                                <label for="farmer_uuid_display" class="form-label">
                                    <i class="fas fa-id-badge"></i> Farmer UUID
                                </label>
                                <input type="text" id="farmer_uuid_display" name="farmer_uuid_display" class="form-control"
                                       placeholder="Auto-generated" readonly>
                                <small class="form-text text-muted">Auto-generated unique farmer identifier</small>
                            </div>

                            <!-- Hidden field to pass UUID to processing script -->
                            <input type="hidden" id="farmer_uuid" name="farmer_uuid" value="<?php echo $farmer_uuid; ?>">
                        </div>

                        <div class="form-actions">
                            <button type="reset" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i> Reset Form
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Register Farmer
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid" style="margin-top: 2rem;">
                <div class="stat-card">
                    <div class="stat-icon farmers">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as total FROM farmers");
                        $total_farmers = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $total_farmers = $row['total'];
                        }
                        ?>
                        <h3><?php echo number_format($total_farmers); ?></h3>
                        <p>Total Registered Farmers</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Active</h3>
                        <p>System Status</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .form-wrapper {
  max-width: 800px;
  margin: 0 auto;
  padding: 30px;
}

.form-layout {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  padding: 40px;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 25px;
  margin-bottom: 25px;
}

.form-group {
  display: flex;
  flex-direction: column;
}

.form-group label {
  font-weight: 600;
  color: #374151;
  margin-bottom: 8px;
  font-size: 14px;
}

.form-group label span {
  color: #ef4444;
}

.form-group input,
.form-group textarea {
  padding: 12px 16px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 15px;
  transition: all 0.3s ease;
  background: #f9fafb;
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: #3b82f6;
  background: #fff;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-group textarea {
  resize: vertical;
  min-height: 80px;
}

.btn-submit {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  padding: 14px 32px;
  font-size: 16px;
  font-weight: 600;
  border-radius: 8px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  width: fit-content;
  margin: 0 auto;
  display: block;
}

.btn-submit:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-submit:active {
  transform: translateY(0);
}

        .form-container {
            padding: 2rem;
        }

        .modern-form {
            max-width: 100%;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #374151;
        }

        .form-label i {
            margin-right: 0.5rem;
            color: #6b7280;
        }

        .required {
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        textarea.form-control {
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
            padding: 0.75rem;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-left: none;
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
            color: #6b7280;
            font-size: 0.875rem;
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: 1px solid transparent;
            border-radius: 0.375rem;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .btn-outline-secondary {
            background-color: transparent;
            color: #6b7280;
            border-color: #d1d5db;
        }

        .btn-outline-secondary:hover {
            background-color: #f9fafb;
            color: #374151;
        }

        .text-muted {
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .form-actions {
                flex-direction: column;
            }
        }
    </style>

    <script>
        // Function to update current date and time
        function updateDateTime() {
            const now = new Date();
            const options = {
                weekday: 'short',
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            };
            const formattedDateTime = now.toLocaleDateString('en-US', options);
            document.getElementById('currentDateTime').textContent = formattedDateTime;
        }

        // Update time immediately and then every second
        updateDateTime();
        setInterval(updateDateTime, 1000);

    // Use server-generated UUID for consistency
    document.addEventListener('DOMContentLoaded', function() {
        // Set the server-generated UUID
        document.getElementById('farmer_uuid_display').value = '<?php echo $farmer_uuid; ?>';

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            let isValid = true;

            // Validate all required fields
            document.querySelectorAll('[required]').forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Validate password confirmation
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            if (password.value !== confirmPassword.value) {
                confirmPassword.classList.add('is-invalid');
                isValid = false;
                alert('Passwords do not match!');
            }

            // Validate password length
            if (password.value && password.value.length < 8) {
                password.classList.add('is-invalid');
                isValid = false;
                alert('Password must be at least 8 characters long!');
            }

            // Validate password strength
            if (password.value && !/[A-Z]/.test(password.value)) {
                password.classList.add('is-invalid');
                isValid = false;
                alert('Password must contain at least one uppercase letter!');
            }
            if (password.value && !/[a-z]/.test(password.value)) {
                password.classList.add('is-invalid');
                isValid = false;
                alert('Password must contain at least one lowercase letter!');
            }
            if (password.value && !/[0-9]/.test(password.value)) {
                password.classList.add('is-invalid');
                isValid = false;
                alert('Password must contain at least one number!');
            }
            if (password.value && !/[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]/.test(password.value)) {
                password.classList.add('is-invalid');
                isValid = false;
                alert('Password must contain at least one special character!');
            }

            // Validate Aadhar number
            const aadhar = document.getElementById('aadhar');
            if (aadhar.value && (!/^[0-9]{12}$/.test(aadhar.value))) {
                aadhar.classList.add('is-invalid');
                isValid = false;
                alert('Aadhar number must be exactly 12 digits!');
            }

            if (!isValid) {
                e.preventDefault();
                alert('Please fill all required fields correctly!');
            }
        });
    });
    </script>
</body>
</html>

<?php include('includes/footer.php'); ?>
