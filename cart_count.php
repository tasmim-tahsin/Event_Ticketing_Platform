<?php
session_start();

// Check if the cart exists in the session
$cart = $_SESSION['event_cart'] ?? [];

// Count the total number of items in the cart
$total_items = 0;
foreach ($cart as $event_id => $tickets) {
    $total_items += array_sum($tickets);
}

// Return the count as a JSON response
echo json_encode(['total_items' => $total_items]);
?>

