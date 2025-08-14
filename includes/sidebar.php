<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dairy Dashboard</title>
  <link rel="stylesheet" href="style.css">
  
</head>
<body>
  <div class="dashboard-container">
    <div class="sidebar">
      <h2>Digital Dairy</h2>
      <ul>
        <li class="active"><a href="dashboard.php">Dashboard</a></li>
        <li class="dropdown">
          <a href="#">Farmer Details ▾</a>
          <ul class="submenu">
            <li><a href="add_farmer.php">Add Farmer</a></li>
            <li><a href="manage_farmer.php">Manage Farmer</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#">milk Collection▾</a>
          <ul class="submenu">
            <li><a href="add_collection.php">Add </a></li>
            <li><a href="manage_collection.php">Manage </a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#">Delivery▾</a>
          <ul class="submenu">
            <li><a href="add_delivery.php">Add </a></li>
            <li><a href="manage_delivery.php">Manage </a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#">product▾</a>
          <ul class="submenu">
            <li><a href="add_product.php">Add </a></li>
            <li><a href="manage_product.php">Manage </a></li>
          </ul>
        </li>
         <li class="dropdown">
          <a href="#">sales▾</a>
          <ul class="submenu">
            <li><a href="add_sales.php">Add </a></li>
            <li><a href="manage_sales.php">Manage </a></li>
          </ul>
        </li>
          <li class="dropdown">
          <a href="#">report▾</a>
          <ul class="submenu">
            <li><a href="milk_collection_report.php">milk collection report </a></li>
            <li><a href="farmer_payment_report.php">farmer payment report </a></li>
             <li><a href="inventory_report.php">inventory report </a></li>
              <li><a href="sales_report.php">sales report </a></li>
          </ul>
        </li>
        <li><a href="logout.php">Logout</a></li>
      </ul>
    </div>
    <div class="main">
      <div class="topbar">
        <div><?php echo date("D M j Y H:i:s (T)"); ?></div>
        <select>
          <option>English</option>
          <option>Malayalam</option>
        </select>
      </div>
