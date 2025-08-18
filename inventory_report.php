<?php
session_start();
include('includes/config.php'); // DB connection

// Fetch records based on date filter
$where = "";
if (isset($_POST['from_date']) && isset($_POST['to_date']) && $_POST['from_date'] != "" && $_POST['to_date'] != "") {
    $from = date('Y-m-d', strtotime($_POST['from_date']));
    $to   = date('Y-m-d', strtotime($_POST['to_date']));
    $where = "WHERE date BETWEEN '$from' AND '$to'";
}

$query = mysqli_query($con, "SELECT * FROM inventory $where ORDER BY date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Inventory Report</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
      body {
          background: #f8f9fa;
      }
      .content-wrapper {
          margin-left: 250px; /* space for sidebar */
          padding: 20px;
      }
      .card {
          border-radius: 8px;
          box-shadow: 0 2px 6px rgba(0,0,0,0.1);
          background: #fff;
          padding: 20px;
      }
  </style>
</head>
<body>

<!-- Sidebar (include your existing sidebar here) -->
<?php include('includes/sidebar.php'); ?>

<div class="content-wrapper">
  <h4 class="mb-3">Inventory Report</h4>

  <!-- Filter Form -->
  <div class="card mb-4">
    <form method="POST" class="row g-3">
      <div class="col-md-4">
        <label for="from_date" class="form-label">From Date</label>
        <input type="date" id="from_date" name="from_date" class="form-control"
               value="<?php if(isset($_POST['from_date'])) echo $_POST['from_date']; ?>">
      </div>
      <div class="col-md-4">
        <label for="to_date" class="form-label">To Date</label>
        <input type="date" id="to_date" name="to_date" class="form-control"
               value="<?php if(isset($_POST['to_date'])) echo $_POST['to_date']; ?>">
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Search</button>
      </div>
    </form>
  </div>

  <!-- DataTable -->
  <div class="card">
    <table id="inventoryTable" class="display nowrap" style="width:100%">
      <thead>
        <tr>
          <th>Serial</th>
          <th>Date</th>
          <th>Farmer</th>
          <th>Product Type</th>
          <th>Milk Quantity</th>
          <th>Milk Fat Content</th>
          <th>Temperature</th>
          <th>Payment</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i=1;
        while($row = mysqli_fetch_assoc($query)) { ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo date('d-m-Y', strtotime($row['date'])); ?></td>
            <td><?php echo $row['farmer']; ?></td>
            <td><?php echo $row['product_type']; ?></td>
            <td><?php echo $row['milk_quantity']; ?></td>
            <td><?php echo $row['fat_content']; ?></td>
            <td><?php echo $row['temperature']; ?></td>
            <td><?php echo $row['payment']; ?></td>
            <td><?php echo $row['total']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    $('#inventoryTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'excel', 'pdf', 'colvis'
        ],
        responsive: true
    });
});
</script>

</body>
</html>
