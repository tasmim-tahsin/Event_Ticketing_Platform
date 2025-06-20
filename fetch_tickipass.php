<?php
session_start();
include 'DB/database.php';

// Ensure proper JSON response
header('Content-Type: application/json');

// Error handling: prevent any accidental output before JSON
ob_start();

if (!isset($_SESSION['user']['id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user']['id'];

$sql = "SELECT tf.pdf_path, o.id AS order_id, e.title, e.location, e.date 
        FROM ticket_files tf
        JOIN orders o ON tf.order_id = o.id
        JOIN events e ON o.event_id = e.id
        WHERE tf.user_id = ? 
        ORDER BY tf.created_at DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$tickets = [];

while ($row = $result->fetch_assoc()) {
    // Format date (optional)
    $row['event_date'] = date("F j, Y, g:i A", strtotime($row['event_date']));
    $tickets[] = $row;
}

$stmt->close();

ob_clean(); // Before echo json_encode()
header('Content-Type: application/json');

echo json_encode($tickets);
