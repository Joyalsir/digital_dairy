<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit;
}
include('includes/header.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dairy Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="dashboard-container">
    <?php include('includes/sidebar.php'); ?>
    <div class="main">
      <div class="cards">
        <div class="card purple">0<br><small>Total Farmers</small></div>
        <div class="card pink">0<br><small>Total Delivery</small></div>
        <div class="card orange">0<br><small>Total Milk Collection</small></div>
      </div>
    </div>
     <div>
      <div class ="chart">
        <h2>Monthly Milk Collection</h2>
        <canvas id="milkChart"></canvas>
  <canvas id="myChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="chart/script.js"></script>
      </div>
    </div>
  </div>

  <?php include('includes/footer.php'); ?>
</body>
</html>
