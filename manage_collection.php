<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<div class="container mt-5">
  
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="fw-bold">View Milk Collection Data</h4>
  <a href="add_collection.php" class="btn btn-primary">➕ Add New</a>
</div>
  <table id="milkTable" class="display nowrap table table-bordered" style="width:100%">
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
      </tr>
    </thead>
    <tbody>
      <?php
      include('includes/config.php');
      $query = mysqli_query($con, "SELECT * FROM milk_collection");
      $count = 1;
      while($row = mysqli_fetch_assoc($query)) {
        echo "<tr>";
        echo "<td>" . $count++ . "</td>";
        echo "<td>" . $row['collection_date'] . "</td>";
        echo "<td>" . $row['farmer_id'] . "</td>";
        echo "<td>" . $row['product_type'] . "</td>";
        echo "<td>" . $row['milk_quantity'] . "</td>";
        echo "<td>" . $row['milk_fat'] . "</td>";
        echo "<td>" . $row['temperature'] . "</td>";
        echo "<td>" . $row['payment'] . "</td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function () {
    $('#milkTable').DataTable({
      responsive: true,
      dom: 'Bfrtip',
      buttons: ['copy', 'excel', 'pdf', 'colvis']
    });
  });
</script>

<?php include('includes/footer.php'); ?>
