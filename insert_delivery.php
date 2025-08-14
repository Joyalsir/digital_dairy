<?php
include('includes/config.php');

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

  $query = "INSERT INTO tbldelivery 
    (CustomerName, Contact, Address, DeliveryDate, ProductType, Quantity, VehicleNo, DriverName, DriverContact)
    VALUES
    ('$customer_name', '$contact', '$address', '$delivery_date', '$product_type', '$quantity', '$vehicle_no', '$driver_name', '$driver_contact')";

  if (mysqli_query($con, $query)) {
    echo "<script>alert('Delivery details added successfully'); window.location='manage_delivery.php';</script>";
  } else {
    echo "<script>alert('Error adding delivery details');</script>";
  }
}
?>
