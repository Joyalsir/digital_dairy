<?php
include('includes/config.php');
include('includes/uuid_helper.php');

if (isset($_POST['submit'])) {
  $customer_name = $_POST['customer_name'];
  $contact = $_POST['contact'];
  $address = $_POST['address'];
  $delivery_date = $_POST['delivery_date'];
  $product_type = $_POST['product_type'];
  $quantity = $_POST['quantity'];
  $vehicle_no = $_POST['vehicle_no'];
  $driver_name = $_POST['driver_name'];
  $driver_contact = $_POST['driver_contact'];

  // Use UUIDs from the form or generate if not provided
  $delivery_uuid = isset($_POST['delivery_uuid']) && !empty($_POST['delivery_uuid']) ? $_POST['delivery_uuid'] : generateShortUUID();
  $customer_uuid = isset($_POST['customer_uuid']) && !empty($_POST['customer_uuid']) ? $_POST['customer_uuid'] : generateShortUUID();
  $driver_uuid = isset($_POST['driver_uuid']) && !empty($_POST['driver_uuid']) ? $_POST['driver_uuid'] : generateShortUUID();

  $query = "INSERT INTO tbldelivery
    (CustomerName, Contact, Address, DeliveryDate, ProductType, Quantity, VehicleNo, DriverName, DriverContact, delivery_uuid, customer_uuid, driver_uuid, delivery_status)
    VALUES
    ('$customer_name', '$contact', '$address', '$delivery_date', '$product_type', '$quantity', '$vehicle_no', '$driver_name', '$driver_contact', '$delivery_uuid', '$customer_uuid', '$driver_uuid', 'pending')";

  if (mysqli_query($con, $query)) {
    echo "<script>alert('Delivery details added successfully with UUID tracking'); window.location='manage_delivery.php';</script>";
  } else {
    echo "<script>alert('Error adding delivery details');</script>";
  }
}
?>
