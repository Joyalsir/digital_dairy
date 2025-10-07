<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

include('includes/header.php');
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Products - Digital Dairy Management System</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
</head>
<body>
    <div class="dashboard-container" style="max-width: 100%; overflow-x: hidden;">
        <?php include('includes/sidebar.php'); ?>

        <div class="dashboard-main" style="width: 100%; max-width: 100%;">
            <!-- Dashboard Header -->
            <div class="dashboard-header">
                <div class="dashboard-title">
                    <h1>Manage Products</h1>
                    <p>View, add, edit, and delete products in the system</p>
                </div>
                <div class="date-display">
                    <i class="fas fa-calendar"></i>
                    <span id="currentDateTime"></span>
                </div>
            </div>

            <!-- Action Bar -->
            <div class="action-bar">
                <div class="action-info">
                    <h3><i class="fas fa-box"></i> Product Management</h3>
                    <p class="text-muted">Manage all products available in the inventory</p>
                </div>
                <div class="action-buttons">
                    <a href="add_product.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Product
                    </a>
                </div>
            </div>

           <!-- Products Table Card -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3><i class="fas fa-table"></i> All Products</h3>
                    <p class="text-muted">Comprehensive list of all products in inventory</p>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="productsTable" style="width: 100%; max-width: 100%;">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-hashtag"></i> ID</th>
                                    <th><i class="fas fa-image"></i> Image</th>
                                    <th><i class="fas fa-tag"></i> Product Name</th>
                                    <th><i class="fas fa-tags"></i> Product Type</th>
                                    <th><i class="fas fa-rupee-sign"></i> Unit Price</th>
                                    <th><i class="fas fa-calendar"></i> Added On</th>
                                    <th><i class="fas fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ret = mysqli_query($con, "SELECT * FROM tblproduct ORDER BY ID DESC");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td>
                                            <div class="product-image-cell">
                                                <?php if (!empty($row['ProductImage']) && file_exists($row['ProductImage'])): ?>
                                                    <img src="<?php echo htmlspecialchars($row['ProductImage']); ?>"
                                                         alt="<?php echo htmlspecialchars($row['ProductName']); ?>"
                                                         class="product-thumbnail"
                                                         onclick="showImageModal('<?php echo htmlspecialchars($row['ProductImage']); ?>', '<?php echo htmlspecialchars($row['ProductName']); ?>')">
                                                <?php else: ?>
                                                    <div class="no-image">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="product-info">
                                                <div class="product-icon">
                                                    <i class="fas fa-box"></i>
                                                </div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($row['ProductName']); ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <i class="fas fa-tags"></i>
                                                <?php echo htmlspecialchars($row['ProductType']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                <i class="fas fa-rupee-sign"></i>
                                                <?php echo number_format($row['UnitPrice'], 2); ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?php echo date('M j, Y', strtotime($row['PostingDate'])); ?>
                                            </small>
                                        </td>
                                        <td>
                                            <div class="action-buttons-cell">
                                                <a href="edit-product.php?pid=<?php echo $row['ID']; ?>" class="btn btn-sm btn-warning" title="Edit Product">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="delete-product.php?pid=<?php echo $row['ID']; ?>" class="btn btn-sm btn-danger" title="Delete Product" onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Image Modal -->
            <div id="imageModal" class="image-modal" onclick="closeImageModal()">
                <span class="close-modal">&times;</span>
                <img class="modal-content" id="modalImage">
                <div class="modal-caption" id="modalCaption"></div>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid" style="margin-top: 2rem;">
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT COUNT(*) as total FROM tblproduct");
                        $total_products = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $total_products = $row['total'];
                        }
                        ?>
                        <h3><?php echo number_format($total_products); ?></h3>
                        <p>Total Products</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon milk">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-content">
                        <?php
                        $result = mysqli_query($con, "SELECT AVG(UnitPrice) as avg_price FROM tblproduct");
                        $avg_price = 0;
                        if ($row = mysqli_fetch_assoc($result)) {
                            $avg_price = $row['avg_price'] ? $row['avg_price'] : 0;
                        }
                        ?>
                        <h3>₹<?php echo number_format($avg_price, 2); ?></h3>
                        <p>Average Price</p>
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

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

        $(document).ready(function() {
            $('#productsTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[1, 'asc']],
                language: {
                    search: "Search products:",
                    lengthMenu: "Show _MENU_ products per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ products",
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                }
            });
        });

        // Image Modal Functions
        function showImageModal(imageSrc, imageAlt) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            const captionText = document.getElementById('modalCaption');

            modal.style.display = 'block';
            modalImg.src = imageSrc;
            captionText.innerHTML = imageAlt;
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
        }

        // Close modal when clicking outside the image
        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
<style>
    .product-image-cell {
        text-align: center;
        width: 80px;
    }

    .product-thumbnail {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 0.375rem;
        cursor: pointer;
        border: 2px solid #e5e7eb;
        transition: transform 0.2s;
    }

    .product-thumbnail:hover {
        transform: scale(1.1);
        border-color: #3b82f6;
    }

    .no-image {
        width: 50px;
        height: 50px;
        border-radius: 0.375rem;
        background-color: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        font-size: 1.25rem;
    }

    /* Image Modal Styles */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        cursor: pointer;
    }

    .modal-content {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }

    .close-modal {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }

    .modal-caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }
</style>
</html>
<?php include('includes/footer.php'); ?>
