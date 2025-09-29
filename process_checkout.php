<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

include('includes/config.php');

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Handle checkout action
if (isset($_POST['action']) && $_POST['action'] == 'checkout') {
    // Calculate totals
    $subtotal = 0;
    $tax_rate = 0.05; // 5% tax
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $tax = $subtotal * $tax_rate;
    $total = $subtotal + $tax;

    // Get user email
    $user_email = $_SESSION['email'];

    // Generate order ID (you might want to use a more sophisticated method)
    $order_id = uniqid('ORD_');

    // Insert order into tblorders
    $order_query = "INSERT INTO tblorders (ID, Email, TotalAmount, Status, OrderDate)
                   VALUES ('$order_id', '$user_email', '$total', 'Pending', NOW())";

    if (mysqli_query($con, $order_query)) {
        // Insert order items into tblorderitems
        $order_items_success = true;
        foreach ($_SESSION['cart'] as $product_id => $item) {
            $item_total = $item['price'] * $item['quantity'];
            $item_query = "INSERT INTO tblorderitems (OrderID, ProductID, Quantity, Price, Total)
                          VALUES ('$order_id', '$product_id', '{$item['quantity']}', '{$item['price']}', '$item_total')";

            if (!mysqli_query($con, $item_query)) {
                $order_items_success = false;
                break;
            }
        }

        if ($order_items_success) {
            // Clear the cart after successful order
            $_SESSION['cart'] = array();

            // Set success message in session
            $_SESSION['checkout_success'] = true;
            $_SESSION['order_id'] = $order_id;
            $_SESSION['order_total'] = $total;

            // Redirect to success page
            header("Location: checkout_success.php");
            exit;
        } else {
            // Rollback order if items failed to insert
            mysqli_query($con, "DELETE FROM tblorders WHERE ID = '$order_id'");

            $_SESSION['checkout_error'] = "Failed to process order items. Please try again.";
            header("Location: cart.php");
            exit;
        }
    } else {
        $error_msg = mysqli_error($con);
        $_SESSION['checkout_error'] = "Failed to create order. MySQL error: " . $error_msg;
        header("Location: cart.php");
        exit;
    }
} else {
    header("Location: cart.php");
    exit;
}
?>
