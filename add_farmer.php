<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit;
}
include('includes/header.php');
?>

<div class="dashboard-container d-flex">
  <?php include('includes/sidebar.php'); ?>

  <div class="main">
    <div class="topbar">
      <h2>Add Farmer Details</h2>
    </div>

    <div class="form-wrapper">
      <form action="process-add-farmer.php" method="post" class="form-layout">
        <div class="form-row">
          <div class="form-group">
            <label>Name<span>*</span></label>
            <input type="text" name="name" required>
          </div>
          <div class="form-group">
            <label>Contact</label>
            <input type="text" name="contact">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Address<span>*</span></label>
            <textarea name="address" rows="2" required></textarea>
          </div>
          <div class="form-group">
            <label>Farm Size</label>
            <input type="text" name="farm_size">
          </div>
        </div>

        <div class="form-row">
          <button type="submit" class="btn-submit">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include('includes/footer.php'); ?>
