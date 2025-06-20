<?php
session_start();
include 'auth.php';
include './DB/database.php';

require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'generate_ticket_pdf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proceed_to_pay'])) {
    $user_id = $_SESSION['user']['id'];
    $event_id = intval($_POST['event_id'] ?? 0);

    if (!isset($_POST['terms'])) {
        $_SESSION['purchase_status'] = "You must agree to the terms and conditions.";
        header("Location: checkout.php?event_id=$event_id");
        exit;
    }

    if (!isset($_SESSION['event_cart'][$event_id]) || empty($_SESSION['event_cart'][$event_id])) {
        $_SESSION['purchase_status'] = "Cart is empty.";
        header("Location: checkout.php?event_id=$event_id");
        exit;
    }

    $cart = $_SESSION['event_cart'][$event_id];
    $ticket_ids = implode(",", array_keys($cart));

    $ticketQuery = mysqli_query($conn, "SELECT * FROM tickets WHERE id IN ($ticket_ids)");
    $ticketDetails = [];
    $subtotal = 0;

    while ($ticket = mysqli_fetch_assoc($ticketQuery)) {
        $id = $ticket['id'];
        $qty = $cart[$id];
        $ticket['selected_quantity'] = $qty;
        $ticket['total_price'] = $qty * $ticket['price'];
        $subtotal += $ticket['total_price'];
        $ticketDetails[] = $ticket;
    }

    foreach ($ticketDetails as $ticket) {
        if ($ticket['quantity'] < $ticket['selected_quantity']) {
            $_SESSION['purchase_status'] = "Not enough tickets available for {$ticket['name']}.";
            header("Location: checkout.php?event_id=$event_id");
            exit;
        }
    }

    $eventRes = mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id");
    $event = mysqli_fetch_assoc($eventRes);

    $user = $_SESSION['user'];

    $all_success = true;

    foreach ($ticketDetails as $ticket) {
        $ticket_id = $ticket['id'];
        $qty = $ticket['selected_quantity'];
        $total = $ticket['total_price'];

        $stmt = $conn->prepare("INSERT INTO orders (user_id, event_id, ticket_id, quantity, total_price, order_date) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("iiidi", $user_id, $event_id, $ticket_id, $qty, $total);
        $stmt->execute();

        if ($stmt->affected_rows <= 0) {
            $all_success = false;
            $_SESSION['purchase_status'] = "Payment Failed.";
            break;
        }

        $order_id = $stmt->insert_id;
        $stmt->close();

        // Deduct quantity
        $conn->query("UPDATE tickets SET quantity = quantity - $qty WHERE id = $ticket_id");

        // Generate and save PDF (also inserts to ticket_files inside the function)
        $pdfPath = generateTicketPDF($order_id, $event, $ticket, $user);

        // Send email with attachment
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tahsinniyan@gmail.com';
            $mail->Password = 'tybk dsrc dsyf mwkv'; // use app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('tahsinniyan@gmail.com', 'TicketKing');
            $mail->addAddress($user['email'], $user['name']);
            $mail->isHTML(true);
            $mail->Subject = "Your Ticket Invoice for {$event['title']}";
            $mail->Body = "Dear {$user['name']},<br>Thank you for your purchase. Find your ticket attached.";
            $mail->addAttachment($pdfPath);
            $mail->send();
        } catch (Exception $e) {
            error_log("Mailer Error: " . $mail->ErrorInfo);
        }
    }

    if ($all_success) {
        unset($_SESSION['event_cart'][$event_id]);
        $_SESSION['purchase_status'] = "Payment Successful!";
    }

    header("Location: checkout.php?event_id=$event_id");
    exit;
} else {
    header("Location: index.php");
    exit;
}
