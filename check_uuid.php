<?php
include('includes/config.php');

$email = 'testuser@example.com';

$query = "SELECT uuid, name, email, username FROM farmers WHERE email='$email'";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    echo "UUID: " . $row['uuid'] . "\n";
    echo "Name: " . $row['name'] . "\n";
    echo "Email: " . $row['email'] . "\n";
    echo "Username: " . $row['username'] . "\n";
} else {
    echo "No user found with email $email\n";
}
?>
