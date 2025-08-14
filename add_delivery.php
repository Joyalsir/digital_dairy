<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<div class="container mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">Add Delivery Details</h4>
    <a href="manage_delivery.php" class="btn btn-secondary">📦 View All</a>
  </div>

  <div class="card shadow p-4">
    <form action="insert_delivery.php" method="POST">
      <div class="row mb-3">
        <div class="col-md-6">
          <label for="customer_name" class="form-label">Customer Name</label>
          <input type="text" name="customer_name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label for="contact" class="form-label">Customer Contact</label>
          <input type="text" name="contact" class="form-control" required>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-12">
          <label for="address" class="form-label">Delivery Address</label>
          <textarea name="address" class="form-control" rows="2" required></textarea>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="delivery_date" class="form-label">Delivery Date</label>
          <input type="date" name="delivery_date" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label for="product_type" class="form-label">Product Type</label>
          <select name="product_type" class="form-select" required>
            <option value="">Select Product</option>
            <option value="Milk">Milk</option>
            <option value="Ghee">Ghee</option>
            <option value="Butter">Butter</option>
            <option value="Curd">Curd</option>
          </select>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="quantity" class="form-label">Quantity (Liters/Kg)</label>
          <input type="number" name="quantity" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label for="vehicle_no" class="form-label">Vehicle Number</label>
          <input type="text" name="vehicle_no" class="form-control" required>
        </div>
      </div>

      <div class="row mb-4">
        <div class="col-md-6">
          <label for="driver_name" class="form-label">Driver Name</label>
          <input type="text" name="driver_name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label for="driver_contact" class="form-label">Driver Contact</label>
          <input type="text" name="driver_contact" class="form-control" required>
        </div>
      </div>

      <button type="submit" name="submit" class="btn btn-primary">🚚 Submit Delivery</button>
    </form>
  </div>
</div>

<?php include('includes/footer.php'); ?>
