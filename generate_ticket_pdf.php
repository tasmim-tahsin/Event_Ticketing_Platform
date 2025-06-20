<?php
require('./fpdf/fpdf.php');
require('./phpqrcode/phpqrcode.php');
require('./DB/database.php'); // Make sure $conn is available

function generateTicketPDF($order_id, $event, $ticket, $user) {
    global $conn;

    // Ensure tickets folder exists
    $outputDir = __DIR__ . '/tickets';
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
    }

    // Generate QR Code
    $qrData = "Order ID: $order_id | Name: {$user['name']} | Ticket: {$ticket['name']}";
    $qrFile = "$outputDir/qr_$order_id.png";
    QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 4);

    // Generate PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // PDF Styles
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, '🎫 Event Ticket Invoice', 0, 1, 'C');
    $pdf->Ln(5);

    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Order ID: $order_id", 0, 1);
    $pdf->Cell(0, 10, "Name: {$user['name']}", 0, 1);
    $pdf->Cell(0, 10, "Email: {$user['email']}", 0, 1);
    $pdf->Ln(5);

    $pdf->Cell(0, 10, "Event: {$event['title']}", 0, 1);
    $pdf->Cell(0, 10, "Location: {$event['location']}", 0, 1);
    $pdf->Cell(0, 10, "Date & Time: {$event['event_date']}", 0, 1);
    $pdf->Cell(0, 10, "Ticket: {$ticket['name']} ({$ticket['selected_quantity']} pcs)", 0, 1);
    $pdf->Cell(0, 10, "Total Price: BDT " . number_format($ticket['total_price'], 2), 0, 1);

    $pdf->Image($qrFile, 150, 30, 40, 40);

    // Save PDF
    $filename = "invoice_ticket_$order_id.pdf";
    $filepath = "$outputDir/$filename";
    $relativePath = "tickets/$filename";
    $pdf->Output('F', $filepath);

    if (file_exists($qrFile)) {
        unlink($qrFile);
    }

    // ✅ INSERT INTO ticket_files table
    $stmt = $conn->prepare("INSERT INTO ticket_files (order_id, user_id, pdf_path) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $order_id, $user['id'], $relativePath);
    $stmt->execute();
    $stmt->close();

    return $relativePath;
}
