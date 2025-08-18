<?php
session_start();
include('includes/config.php'); // your DB connection

// Fetch records if date range is selected
$fromDate = isset($_POST['from_date']) ? $_POST['from_date'] : '';
$toDate   = isset($_POST['to_date']) ? $_POST['to_date'] : '';

$query = "SELECT * FROM sales WHERE 1";
if ($fromDate && $toDate) {
    $query .= " AND delivery_date BETWEEN '$fromDate' AND '$toDate'";
}
$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales Report</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="css/style.css"> <!-- your sidebar/dashboard CSS -->
</head>
<body>
  <!-- Sidebar -->
  <?php include('includes/sidebar.php'); ?>

  <!-- Main Content -->
  <div class="content">
    <h3>Sales Report</h3>
    <form method="post" class="mb-3">
      <div style="display:flex; gap:10px; align-items:center;">
        <input type="date" name="from_date" value="<?php echo $fromDate; ?>" required>
        <input type="date" name="to_date" value="<?php echo $toDate; ?>" required>
        <button type="submit">Search</button>
      </div>
    </form>

    <table id="salesTable" class="display nowrap" style="width:100%">
      <thead>
        <tr>
          <th>Serial</th>
          <th>Customer Name</th>
          <th>Contact</th>
          <th>Address</th>
          <th>Delivery Date</th>
          <th>Product Type</th>
          <th>Quantity</th>
          <th>Vehicle Number</th>
          <th>Driver Name</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $i=1;
        while($row=mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo $row['customer_name']; ?></td>
            <td><?php echo $row['contact']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td><?php echo $row['delivery_date']; ?></td>
            <td><?php echo $row['product_type']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo $row['vehicle_number']; ?></td>
            <td><?php echo $row['driver_name']; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <!-- JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

  <script>
    $(document).ready(function() {
      $('#salesTable').DataTable({
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
