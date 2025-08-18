<?php
include('includes/config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Report</title>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
  <link rel="stylesheet" href="assets/css/style.css"> <!-- your sidebar + theme CSS -->

  <style>
    .content {
      margin-left: 260px; /* space for sidebar */
      padding: 20px;
      transition: all 0.3s ease;
    }

    .content.collapsed {
      margin-left: 80px; /* when sidebar collapsed */
    }

    .card {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .form-inline {
      display: flex;
      gap: 15px;
      align-items: center;
      flex-wrap: wrap;
    }

    .form-inline input {
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    .btn {
      background: #3c8dbc;
      color: #fff;
      padding: 8px 15px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn:hover {
      background: #367fa9;
    }

    /* Align export buttons */
    div.dt-buttons {
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

<?php include('includes/sidebar.php'); ?>


<div class="content" id="mainContent">
  <h3>Payment Report</h3>
  <div class="card">
    <form method="POST" class="form-inline">
      <label>From Date*</label>
      <input type="date" name="from_date" required>
      
      <label>To Date*</label>
      <input type="date" name="to_date" required>
      
      <button type="submit" name="search" class="btn">Search</button>
    </form>
  </div>

  <br>

  <div class="card">
    <table id="paymentTable" class="display nowrap" style="width:100%">
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
        if(isset($_POST['search'])){
          $from = $_POST['from_date'];
          $to = $_POST['to_date'];
          $sql = "SELECT p.id, p.date, f.name as farmer, pr.product_name, p.milk_qty, p.milk_fat, p.temperature, p.payment
                  FROM payment p 
                  JOIN farmers f ON f.id=p.farmer_id
                  JOIN products pr ON pr.id=p.product_id
                  WHERE date BETWEEN '$from' AND '$to'";
          $res = mysqli_query($con,$sql);
          $serial=1;
          while($row=mysqli_fetch_assoc($res)){
            echo "<tr>
                    <td>".$serial++."</td>
                    <td>".$row['date']."</td>
                    <td>".$row['farmer']."</td>
                    <td>".$row['product_name']."</td>
                    <td>".$row['milk_qty']."</td>
                    <td>".$row['milk_fat']."</td>
                    <td>".$row['temperature']."</td>
                    <td>".$row['payment']."</td>
                  </tr>";
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<!-- jQuery & DataTables -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
  $('#paymentTable').DataTable({
      dom: 'Bfrtip',
      buttons: [
          'copy', 'excel', 'pdf', 'colvis'
      ],
      responsive: true
  });

  // Sidebar collapse toggle
  document.querySelector('.sidebar-toggle').addEventListener('click', function(){
    document.getElementById("mainContent").classList.toggle("collapsed");
  });
});
</script>

</body>
</html>
