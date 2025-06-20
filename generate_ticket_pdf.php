<?php
require('./fpdf/fpdf.php');
require('./phpqrcode/phpqrcode.php');

function generateTicketPDF($order_id, $event, $ticket, $user) {
    // Ensure tickets directory exists
    $outputDir = __DIR__ . '/tickets';
    if (!is_dir($outputDir)) {
        mkdir($outputDir, 0755, true);
    }

    // Create QR Code
    $qrData = "Order ID: $order_id | Name: {$user['name']} | Ticket: {$ticket['name']}";
    $qrFile = "$outputDir/qr_$order_id.png";
    QRcode::png($qrData, $qrFile, QR_ECLEVEL_L, 4);

    // Create PDF
    $pdf = new FPDF();
    $pdf->AddPage();

    // Header
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Event Ticket Invoice', 0, 1, 'C');

    // User Info
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, "Name: " . $user['name'], 0, 1);
    $pdf->Cell(0, 10, "Email: " . $user['email'], 0, 1);

    // Ticket Info
    $pdf->Cell(0, 10, "Event: " . $event['title'], 0, 1);
    $pdf->Cell(0, 10, "Ticket: " . $ticket['name'] . " ({$ticket['selected_quantity']} pcs)", 0, 1);
    $pdf->Cell(0, 10, "Total Price: BDT " . number_format($ticket['total_price'], 2), 0, 1);

    // QR Code Image
    $pdf->Image($qrFile, 150, 30, 40, 40);

    // Save PDF
    $filename = "invoice_ticket_$order_id.pdf";
    $filepath = "$outputDir/$filename";
    $pdf->Output('F', $filepath);

    // Remove QR image
    if (file_exists($qrFile)) {
        unlink($qrFile);
    }

    return "tickets/$filename"; // Return relative path
}
