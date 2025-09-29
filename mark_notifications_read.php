<?php
session_start();
if (!isset($_SESSION['email'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

include('includes/config.php');

$user_email = $_SESSION['email'];
$query = mysqli_query($con, "SELECT * FROM farmers WHERE email='$user_email'");
if (!$query || mysqli_num_rows($query) == 0) {
    http_response_code(400);
    echo json_encode(['error' => 'User not found']);
    exit;
}

$farmer = mysqli_fetch_assoc($query);
$farmer_uuid = $farmer['uuid'];

// Update notifications to mark all as read
$update_query = "UPDATE notifications SET is_read = 1 WHERE farmer_uuid = '".mysqli_real_escape_string($con, $farmer_uuid)."'";
if (mysqli_query($con, $update_query)) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update notifications']);
}
?>
