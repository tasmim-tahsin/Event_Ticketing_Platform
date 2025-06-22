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

    // Set colors
    $primaryColor = array(59, 130, 246); // Blue-500
    $secondaryColor = array(139, 92, 246); // Purple-500
    $lightColor = array(243, 244, 246); // Gray-50
    $darkColor = array(31, 41, 55); // Gray-800

    // Header with gradient
    $pdf->SetFillColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
    $pdf->SetTextColor(255);
    $pdf->SetFont('Arial', 'B', 20);
    $pdf->Cell(0, 15, 'TicketKing', 0, 1, 'C', true);
    
    // Event image if available
    
    // Ticket title
    $pdf->SetFillColor($secondaryColor[0], $secondaryColor[1], $secondaryColor[2]);
    $pdf->SetTextColor(255);
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'YOUR EVENT TICKET', 0, 1, 'C', true);
    $pdf->Ln(5);

    // Ticket details section
    $pdf->SetTextColor($darkColor[0], $darkColor[1], $darkColor[2]);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Order ID:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, $order_id, 0, 1);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Ticket Holder:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, $user['name'], 0, 1);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Email:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, $user['email'], 0, 1);
    $pdf->Ln(5);

    // Event details box
    $pdf->SetFillColor($lightColor[0], $lightColor[1], $lightColor[2]);
    $pdf->SetDrawColor(200, 200, 200);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Event Details', 1, 1, 'C', true);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Event:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, $event['title'], 0, 1);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Location:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, $event['location'], 0, 1);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Date & Time:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, $event['date'] . ' at ' . $event['time'], 0, 1);
    $pdf->Ln(5);

    // Ticket type and price
    $pdf->SetFillColor($lightColor[0], $lightColor[1], $lightColor[2]);
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Ticket Information', 1, 1, 'C', true);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Ticket Type:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, $ticket['name'], 0, 1);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Quantity:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, $ticket['selected_quantity'] . ' pcs', 0, 1);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'Total Price:', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(0, 10, 'BDT ' . number_format($ticket['total_price'], 2), 0, 1);
    $pdf->Ln(10);

    // QR Code section
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, 'Present this QR code at the event entrance:', 0, 1, 'C');
    $pdf->Image($qrFile, ($pdf->GetPageWidth() - 40) / 2, null, 40, 40);
    $pdf->Ln(45);

    // Footer
    $pdf->SetFillColor($darkColor[0], $darkColor[1], $darkColor[2]);
    $pdf->SetTextColor(255);
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, 'Thank you for using TicketKing!', 0, 1, 'C', true);
    $pdf->Cell(0, 5, 'For any questions, contact support@ticketking.com', 0, 1, 'C');
    $pdf->Cell(0, 5, date('Y') . ' TicketKing. All rights reserved.', 0, 1, 'C');

    // Save PDF
    $filename = "invoice_ticket_$order_id.pdf";
    $filepath = "$outputDir/$filename";
    $relativePath = "tickets/$filename";
    $pdf->Output('F', $filepath);

    // Clean up QR code
    if (file_exists($qrFile)) {
        unlink($qrFile);
    }

    // Save to database
    $stmt = $conn->prepare("INSERT INTO ticket_files (order_id, user_id, pdf_path) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $order_id, $user['id'], $relativePath);
    $stmt->execute();
    $stmt->close();

    return $relativePath;
}