<?php
session_start();
require('./DB/database.php');

// Check if admin/organizer is logged in
if (!isset($_SESSION['admin']) && !isset($_SESSION['organizer'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $verifier_id = isset($_SESSION['admin']) ? $_SESSION['admin']['id'] : $_SESSION['organizer']['id'];
    $verifier_type = isset($_SESSION['admin']) ? 'admin' : 'organizer';
    
    // Update the order to mark as attended
    $stmt = $conn->prepare("UPDATE orders SET attended = 1, verified_by = ?, verifier_type = ?, verification_time = NOW() WHERE id = ?");
    $stmt->bind_param("isi", $verifier_id, $verifier_type, $order_id);
    $stmt->execute();
    $stmt->close();
    
    // Redirect back with success message
    $_SESSION['verification_message'] = "Ticket #$order_id successfully marked as attended";
    header("Location: verify_ticket.php");
    exit;
}

header("Location: verify_ticket.php");
?>