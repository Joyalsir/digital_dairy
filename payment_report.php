<?php
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Report - Digital Dairy</title>
  
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
  
  <!-- Main Theme CSS -->
  <link rel="stylesheet" href="style.css">
  
  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  
  
</head>
<body>

<?php include('includes/sidebar.php'); ?>

<div class="content" id="mainContent">
  <!-- Page Header -->
  <div class="page-header">
    <h1><i class="fas fa-file-invoice-dollar"></i> Payment Report</h1>
    <p>Generate detailed payment reports with advanced filtering and export options</p>
  </div>

  <!-- Summary Cards -->
  <div class="summary-cards">
    <div class="summary-card">
      <div class="summary-icon total">
        <i class="fas fa-calculator"></i>
      </div>
      <div class="summary-content">
        <h4 id="totalAmount">₹0.00</h4>
        <p>Total Payment Amount</p>
      </div>
    </div>
    <div class="summary-card">
      <div class="summary-icon paid">
        <i class="fas fa-check-circle"></i>
      </div>
      <div class="summary-content">
        <h4 id="totalRecords">0</h4>
        <p>Total Records</p>
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
  </div>

  <!-- Search Form -->
  <div class="form-container">
    <div class="card-header">
      <h3 class="card-title">
        <i class="fas fa-filter"></i>
        Filter Payments
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
      
      <button type="submit" name="search" class="btn btn-primary">
        <i class="fas fa-search"></i>
        Search
      </button>
    </form>
  </div>

  <!-- Results Table -->
  <div class="table-container" id="resultsContainer" style="display: <?php echo isset($_POST['search']) ? 'block' : 'none'; ?>;">
    <div class="table-header">
      <h3><i class="fas fa-table"></i> Payment Details</h3>
    </div>
    
    <div style="padding: 0 30px 30px 30px;">
      <table id="paymentTable" class="display nowrap" style="width:100%">
        <thead>
          <tr>
            <th><i class="fas fa-hashtag"></i> Serial</th>
            <th><i class="fas fa-calendar"></i> Date</th>
            <th><i class="fas fa-user"></i> Farmer</th>
            <th><i class="fas fa-box"></i> Product Type</th>
            <th><i class="fas fa-tint"></i> Milk Quantity (L)</th>
            <th><i class="fas fa-percentage"></i> Fat Content (%)</th>
            <th><i class="fas fa-thermometer-half"></i> Temperature (°C)</th>
            <th><i class="fas fa-rupee-sign"></i> Payment (₹)</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $totalPayment = 0;
          $recordCount = 0;
          
          if(isset($_POST['search'])){
            $from = $_POST['from_date'];
            $to = $_POST['to_date'];
            $sql = "SELECT p.id, p.date, f.name as farmer, pr.product_name, p.milk_qty, p.milk_fat, p.temperature, p.payment
                    FROM payment p 
                    JOIN farmers f ON f.id=p.farmer_id
                    JOIN products pr ON pr.id=p.product_id
                    WHERE date BETWEEN '$from' AND '$to'
                    ORDER BY p.date DESC";
            $res = mysqli_query($con,$sql);
            $serial=1;
            while($row=mysqli_fetch_assoc($res)){
              $totalPayment += $row['payment'];
              $recordCount++;
              echo "<tr>
                      <td>".$serial++."</td>
                      <td>".date('d M Y', strtotime($row['date']))."</td>
                      <td><strong>".$row['farmer']."</strong></td>
                      <td>".$row['product_name']."</td>
                      <td>".number_format($row['milk_qty'], 2)."</td>
                      <td>".number_format($row['milk_fat'], 2)."</td>
                      <td>".number_format($row['temperature'], 1)."</td>
                      <td><strong>₹".number_format($row['payment'], 2)."</strong></td>
                    </tr>";
            }
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

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
    var table = $('#paymentTable').DataTable({
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
            searchPlaceholder: "Search payments...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ payments",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        },
        order: [[1, 'desc']],
        columnDefs: [
            { targets: [0], width: '5%' },
            { targets: [4, 5, 6, 7], className: 'text-right' }
        ]
    });

    // Update summary cards
    <?php if(isset($_POST['search'])): ?>
    $('#totalAmount').text('₹<?php echo number_format($totalPayment, 2); ?>');
    $('#totalRecords').text('<?php echo $recordCount; ?>');
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
