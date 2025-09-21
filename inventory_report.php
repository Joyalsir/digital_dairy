<?php
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventory Report - Digital Dairy</title>

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

  <!-- Main Theme CSS -->
  <link rel="stylesheet" href="style.css">

  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style> </style>


</head>
<body>

<?php include('includes/sidebar.php'); ?>

<div class="content" id="mainContent">
  <!-- Page Header -->
  <div class="page-header">
    <h1><i class="fas fa-boxes"></i> Inventory Report</h1>
    <p>Generate detailed inventory reports with stock levels and product analysis</p>
  </div>

  <!-- Summary Cards -->
  <div class="summary-cards">
    <div class="summary-card">
      <div class="summary-icon total">
        <i class="fas fa-cubes"></i>
      </div>
      <div class="summary-content">
        <h4 id="totalProducts">0</h4>
        <p>Total Products</p>
      </div>
    </div>
    <div class="summary-card">
      <div class="summary-icon paid">
        <i class="fas fa-rupee-sign"></i>
      </div>
      <div class="summary-content">
        <h4 id="totalValue">₹0.00</h4>
        <p>Total Inventory Value</p>
      </div>
    </div>
    <div class="summary-card">
      <div class="summary-icon pending">
        <i class="fas fa-calendar-alt"></i>
      </div>
      <div class="summary-content">
        <h4 id="dateRange">-</h4>
        <p>Selected Date Range</p>
      </div>
    </div>
    <div class="summary-card">
      <div class="summary-icon warning">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <div class="summary-content">
        <h4 id="lowStockCount">0</h4>
        <p>Low Stock Items</p>
      </div>
    </div>
  </div>

  <!-- Search Form -->
  <div class="form-container">
    <div class="card-header">
      <h3 class="card-title">
        <i class="fas fa-filter"></i>
        Filter Inventory
      </h3>
    </div>
    <form method="POST" class="form-row">
      <div class="form-group">
        <label for="from_date">
          <i class="fas fa-calendar-start"></i>
          From Date <span style="color: #ef4444;">*</span>
        </label>
        <input type="date" id="from_date" name="from_date" class="form-control" required
               value="<?php echo isset($_POST['from_date']) ? $_POST['from_date'] : ''; ?>">
      </div>

      <div class="form-group">
        <label for="to_date">
          <i class="fas fa-calendar-end"></i>
          To Date <span style="color: #ef4444;">*</span>
        </label>
        <input type="date" id="to_date" name="to_date" class="form-control" required
               value="<?php echo isset($_POST['to_date']) ? $_POST['to_date'] : ''; ?>">
      </div>

      <div class="form-group">
        <label for="product_type">
          <i class="fas fa-box"></i>
          Product Type
        </label>
        <select id="product_type" name="product_type" class="form-control">
          <option value="">All Products</option>
          <option value="Milk" <?php echo (isset($_POST['product_type']) && $_POST['product_type'] == 'Milk') ? 'selected' : ''; ?>>Milk</option>
          <option value="Curd" <?php echo (isset($_POST['product_type']) && $_POST['product_type'] == 'Curd') ? 'selected' : ''; ?>>Curd</option>
          <option value="Butter" <?php echo (isset($_POST['product_type']) && $_POST['product_type'] == 'Butter') ? 'selected' : ''; ?>>Butter</option>
          <option value="Ghee" <?php echo (isset($_POST['product_type']) && $_POST['product_type'] == 'Ghee') ? 'selected' : ''; ?>>Ghee</option>
          <option value="Cheese" <?php echo (isset($_POST['product_type']) && $_POST['product_type'] == 'Cheese') ? 'selected' : ''; ?>>Cheese</option>
        </select>
      </div>

      <button type="submit" name="search" class="btn btn-primary">
        <i class="fas fa-search"></i>
        Search
      </button>
    </form>
  </div>

  <!-- Results Table -->
  <div class="table-container" id="resultsContainer" style="display: <?php echo isset($_POST['search']) ? 'block' : 'none'; ?>;">
    <div class="table-header">
      <h3><i class="fas fa-table"></i> Inventory Details</h3>
    </div>

    <div style="padding: 0 30px 30px 30px;">
      <table id="inventoryTable" class="display nowrap" style="width:100%">
        <thead>
          <tr>
            <th><i class="fas fa-hashtag"></i> Serial</th>
            <th><i class="fas fa-box"></i> Product Name</th>
            <th><i class="fas fa-cube"></i> Product Type</th>
            <th><i class="fas fa-rupee-sign"></i> Unit Price (₹)</th>
            <th><i class="fas fa-shopping-cart"></i> Total Sold</th>
            <th><i class="fas fa-chart-line"></i> Stock Status</th>
            <th><i class="fas fa-calculator"></i> Stock Value (₹)</th>
            <th><i class="fas fa-exclamation-triangle"></i> Alert</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $totalValue = 0;
          $totalProducts = 0;
          $lowStockCount = 0;

          if(isset($_POST['search'])){
            $from = $_POST['from_date'];
            $to = $_POST['to_date'];
            $product_type = isset($_POST['product_type']) ? $_POST['product_type'] : '';

            // Base query for products
            $productQuery = "SELECT * FROM tblproduct WHERE 1=1";
            if(!empty($product_type)) {
              $productQuery .= " AND ProductType = '$product_type'";
            }
            $productQuery .= " ORDER BY ProductName ASC";

            $productRes = mysqli_query($con, $productQuery);

            if (!$productRes) {
                die("SQL Error: " . mysqli_error($con) . "<br>Query: " . $productQuery);
            }

            $serial = 1;

            while($productRow = mysqli_fetch_assoc($productRes)){
              // Calculate total sold for this product in the date range
              $soldQuery = "SELECT COALESCE(SUM(Quantity), 0) as total_sold
                           FROM tblsales
                           WHERE ProductName = '" . $productRow['ProductName'] . "'
                           AND SalesDate BETWEEN '$from' AND '$to'";

              $soldRes = mysqli_query($con, $soldQuery);
              $soldData = mysqli_fetch_assoc($soldRes);
              $totalSold = $soldData['total_sold'];

              // Calculate stock value
              $stockValue = $productRow['UnitPrice'] * max(0, 100 - $totalSold); // Assuming base stock of 100 units
              $totalValue += $stockValue;

              // Determine stock status
              $remainingStock = max(0, 100 - $totalSold);
              if($remainingStock <= 10) {
                $stockStatus = '<span class="badge bg-danger">Low Stock</span>';
                $lowStockCount++;
              } elseif($remainingStock <= 25) {
                $stockStatus = '<span class="badge bg-warning">Medium Stock</span>';
              } else {
                $stockStatus = '<span class="badge bg-success">In Stock</span>';
              }

              // Alert column
              $alert = $remainingStock <= 10 ? '<i class="fas fa-exclamation-triangle text-danger"></i>' : '-';

              $totalProducts++;

              echo "<tr>
                      <td>".$serial++."</td>
                      <td><strong class=\"product-name\">".$productRow['ProductName']."</strong></td>
                      <td><span class=\"badge bg-info\">".$productRow['ProductType']."</span></td>
                      <td>".number_format($productRow['UnitPrice'], 2)."</td>
                      <td>".number_format($totalSold, 2)."</td>
                      <td>".$stockStatus."</td>
                      <td><strong>₹".number_format($stockValue, 2)."</strong></td>
                      <td>".$alert."</td>
                    </tr>";
            }
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<style>
  :root {
      --primary-color: #667eea;
      --secondary-color: #764ba2;
      --success-color: #10b981;
      --warning-color: #f59e0b;
      --danger-color: #ef4444;
      --gray-100: #f3f4f6;
      --gray-200: #e5e7eb;
      --gray-300: #d1d5db;
      --gray-600: #4b5563;
      --gray-800: #1f2937;
      --white: #ffffff;
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }

    * {
      box-sizing: border-box;
    }

    body {
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      min-height: 100vh;
    }

    .content {
      margin-left: 0;
      padding: 30px;
      transition: all 0.3s ease;
      min-height: 100vh;
      width: 100%;
    }

    .content.collapsed {
      margin-left: 0;
    }

    /* Full screen content */
    .content.fullscreen {
      margin-left: 0;
      margin-right: 0;
      padding-left: 20px;
      padding-right: 20px;
    }

    /* Page Header */
    .page-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 30px;
      border-radius: 16px;
      margin-bottom: 30px;
      box-shadow: var(--shadow-lg);
    }

    .page-header h1 {
      font-size: 32px;
      font-weight: 700;
      margin: 0 0 8px 0;
    }

    .page-header p {
      margin: 0;
      opacity: 0.9;
      font-size: 16px;
    }

    /* Enhanced Card Design */
    .card {
      background: var(--white);
      border-radius: 16px;
      box-shadow: var(--shadow-md);
      padding: 30px;
      margin-bottom: 25px;
      border: 1px solid var(--gray-200);
      transition: all 0.3s ease;
    }

    .card:hover {
      box-shadow: var(--shadow-xl);
      transform: translateY(-2px);
    }

    .card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 25px;
      padding-bottom: 15px;
      border-bottom: 2px solid var(--gray-100);
    }

    .card-title {
      font-size: 20px;
      font-weight: 600;
      color: var(--gray-800);
      margin: 0;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .card-title i {
      color: var(--primary-color);
    }

    /* Enhanced Form Styling */
    .form-container {
      background: var(--white);
      border-radius: 16px;
      padding: 30px;
      box-shadow: var(--shadow-md);
    }

    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr auto;
      gap: 20px;
      align-items: end;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group label {
      font-weight: 600;
      color: var(--gray-800);
      margin-bottom: 8px;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .form-group label i {
      color: var(--primary-color);
      font-size: 14px;
    }

    .form-control {
      padding: 12px 16px;
      border: 2px solid var(--gray-200);
      border-radius: 8px;
      font-size: 15px;
      transition: all 0.3s ease;
      background: var(--gray-100);
    }

    .form-control:focus {
      outline: none;
      border-color: var(--primary-color);
      background: var(--white);
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Enhanced Button Styling */
    .btn {
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    /* Enhanced Table Styling */
    .table-container {
      background: var(--white);
      border-radius: 16px;
      overflow: hidden;
      box-shadow: var(--shadow-md);
    }

    .table-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 20px 30px;
    }

    .table-header h3 {
      margin: 0;
      font-size: 18px;
      font-weight: 600;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper {
      padding: 0;
      overflow-x: auto;
    }

    #inventoryTable {
      margin: 0;
      border-radius: 0;
      min-width: 900px; /* Minimum width to prevent excessive shrinking */
    }

    #inventoryTable thead th {
      background: var(--gray-100);
      color: var(--gray-800);
      font-weight: 600;
      padding: 16px;
      border-bottom: 2px solid var(--gray-200);
      font-size: 14px;
      white-space: nowrap;
    }

    #inventoryTable tbody td {
      padding: 14px 16px;
      border-bottom: 1px solid var(--gray-200);
      font-size: 14px;
    }

    #inventoryTable tbody tr:hover {
      background: rgba(102, 126, 234, 0.05);
    }

    #inventoryTable tbody tr:nth-child(even) {
      background: rgba(249, 250, 251, 0.5);
    }

    /* Product name truncation for mobile */
    .product-name {
      max-width: 150px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    /* Badge styling */
    .badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .badge.bg-success {
      background-color: var(--success-color) !important;
    }

    .badge.bg-warning {
      background-color: var(--warning-color) !important;
    }

    .badge.bg-danger {
      background-color: var(--danger-color) !important;
    }

    .badge.bg-info {
      background: linear-gradient(135deg, #06b6d4, #0891b2);
    }

    /* Summary Cards */
    .summary-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 30px;
    }

    .summary-card {
      background: var(--white);
      padding: 25px;
      border-radius: 16px;
      box-shadow: var(--shadow-md);
      display: flex;
      align-items: center;
      gap: 20px;
      transition: all 0.3s ease;
    }

    .summary-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow-lg);
    }

    .summary-icon {
      width: 60px;
      height: 60px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      color: white;
    }

    .summary-icon.total {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }

    .summary-icon.paid {
      background: linear-gradient(135deg, #10b981, #059669);
    }

    .summary-icon.pending {
      background: linear-gradient(135deg, #f59e0b, #d97706);
    }

    .summary-icon.warning {
      background: linear-gradient(135deg, #ef4444, #dc2626);
    }

    .summary-content h4 {
      font-size: 28px;
      font-weight: 700;
      margin: 0 0 5px 0;
      color: var(--gray-800);
    }

    .summary-content p {
      margin: 0;
      color: var(--gray-600);
      font-size: 14px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .content {
        margin-left: 0;
        padding: 20px;
      }

      .content.collapsed {
        margin-left: 0;
      }

      .form-row {
        grid-template-columns: 1fr;
      }

      .summary-cards {
        grid-template-columns: 1fr;
      }

      .page-header {
        padding: 20px;
      }

      .page-header h1 {
        font-size: 24px;
      }
    }

    @media (max-width: 480px) {
      .content {
        padding: 15px;
      }

      .card {
        padding: 20px;
      }

      .page-header {
        padding: 15px;
      }
    }
</style>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable with enhanced options
    var table = $('#inventoryTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copy',
                text: '<i class="fas fa-copy"></i> Copy',
                className: 'btn btn-primary'
            },
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-primary'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-primary',
                orientation: 'landscape',
                pageSize: 'A4'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Print',
                className: 'btn btn-primary'
            }
        ],
        responsive: true,
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        language: {
            search: "<i class='fas fa-search'></i>",
            searchPlaceholder: "Search inventory...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ inventory items",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        order: [[1, 'asc']],
        columnDefs: [
            { targets: [0], width: '5%' },
            { targets: [3, 4, 6], className: 'text-right' }
        ]
    });

    // Update summary cards
    <?php if(isset($_POST['search'])): ?>
    $('#totalProducts').text('<?php echo $totalProducts; ?>');
    $('#totalValue').text('₹<?php echo number_format($totalValue, 2); ?>');
    $('#lowStockCount').text('<?php echo $lowStockCount; ?>');
    $('#dateRange').text('<?php echo date('d M Y', strtotime($_POST['from_date'])) . " - " . date('d M Y', strtotime($_POST['to_date'])); ?>');
    <?php endif; ?>

    // Sidebar collapse toggle
    document.querySelector('.sidebar-toggle')?.addEventListener('click', function(){
        document.getElementById("mainContent").classList.toggle("collapsed");
    });

    // Set today's date as default for "to" date
    if (!$('#to_date').val()) {
        $('#to_date').val(new Date().toISOString().split('T')[0]);
    }

    // Set 30 days ago as default for "from" date
    if (!$('#from_date').val()) {
        var thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
        $('#from_date').val(thirtyDaysAgo.toISOString().split('T')[0]);
    }
});
</script>

</body>
</html>
