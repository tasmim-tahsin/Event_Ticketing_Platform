<?php
if (!isset($_GET['file'])) {
    http_response_code(400);
    exit('No file specified.');
}

$file = basename($_GET['file']);  // Sanitize input
$path = __DIR__ . '/tickets/' . $file;

if (!file_exists($path)) {
    http_response_code(404);
    exit('File not found.');
}

header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $file . '"');
header('Content-Length: ' . filesize($path));
readfile($path);
exit;
