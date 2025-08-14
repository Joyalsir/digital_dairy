<?php
session_start();
include('includes/config.php');
include('includes/header.php');
?>

<div class="dashboard-container d-flex">
  <?php include('includes/sidebar.php'); ?>

  <div class="main">
    <div class="topbar">
      <h2>View Farmer's Details</h2>
    </div>

    <div class="content">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div><h4 class="text-dark">Farmer Details</h4></div>
        <div><a href="add-farmer.php" class="btn-add">Add New</a></div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="farmerTable">
          <thead class="thead-dark">
            <tr>
              <th>Serial</th>
              <th>Name</th>
              <th>Contact</th>
              <th>Address</th>
              <th>Farm Size</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $ret = mysqli_query($con, "SELECT * FROM farmers");
            $cnt = 1;
            while ($row = mysqli_fetch_array($ret)) {
            ?>
              <tr>
                <td><?php echo $cnt++; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['contact']; ?></td>
                <td><?php echo $row['address']; ?></td>
                <td><?php echo $row['farm_size']; ?></td>
                <td>
                  <a href="view-farmer.php?id=<?php echo $row['id']; ?>" class="btn-action view"><i class="fa fa-eye"></i></a>
                  <a href="edit-farmer.php?id=<?php echo $row['id']; ?>" class="btn-action edit"><i class="fa fa-edit"></i></a>
                  <a href="delete-farmer.php?id=<?php echo $row['id']; ?>" class="btn-action delete" onclick="return confirm('Are you sure?');"><i class="fa fa-trash"></i></a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
