<?php
session_start();
include 'DB/database.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    http_response_code(401);
    exit('Unauthorized access.');
}

// Check if file parameter exists
if (!isset($_GET['file'])) {
    http_response_code(400);
    exit('No file specified.');
}

// Sanitize the filename
$file = basename($_GET['file']);
$tickets_dir = __DIR__ . '/tickets/';
$path = $tickets_dir . $file;

// Verify the file exists
if (!file_exists($path)) {
    http_response_code(404);
    exit('File not found.');
}

// Verify the user has permission to download this file
$user_id = $_SESSION['user']['id'];
$query = "SELECT COUNT(*) FROM orders WHERE user_id = ? AND ticket_file = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "is", $user_id, $file);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$count = mysqli_fetch_row($result)[0];

if ($count == 0) {
    http_response_code(403);
    exit('You do not have permission to download this file.');
}

// Set headers for download
header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . basename($path) . '"');
header('Content-Length: ' . filesize($path));
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');

// Clear output buffer and send file
ob_clean();
flush();
readfile($path);
exit;