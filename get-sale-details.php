<?php
session_start();
if (!isset($_SESSION['email'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

include('includes/config.php');

if (!isset($_GET['invoice'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invoice number is required']);
    exit;
}

$invoice = mysqli_real_escape_string($con, $_GET['invoice']);

$sql = "SELECT * FROM tblsales WHERE InvoiceNumber = '$invoice' LIMIT 1";
$result = mysqli_query($con, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    http_response_code(404);
    echo json_encode(['error' => 'Sale record not found']);
    exit;
}

$sale = mysqli_fetch_assoc($result);

header('Content-Type: application/json');
echo json_encode($sale);
?>
