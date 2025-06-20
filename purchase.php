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

    // Insert multiple orders (1 per ticket)
    $all_success = true;

    foreach ($ticketDetails as $ticket) {
        $ticket_id = $ticket['id'];
        $qty = $ticket['selected_quantity'];
        $total = $ticket['total_price'];

        // Insert into orders
        $order_sql = "INSERT INTO orders (user_id, event_id, ticket_id, quantity, total_price, order_date)
                      VALUES ($user_id, $event_id, $ticket_id, $qty, $total, NOW())";
        $order_result = mysqli_query($conn, $order_sql);

        if (!$order_result) {
            $all_success = false;
            $_SESSION['purchase_status'] = "Payment Failed: " . mysqli_error($conn);
            break;
        }

        // Deduct ticket quantity
        mysqli_query($conn, "UPDATE tickets SET quantity = quantity - $qty WHERE id = $ticket_id");
    }

    if ($all_success) {
        
$order_id = mysqli_insert_id($conn);

// Fetch event data
$eventQuery = mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id");
$event = mysqli_fetch_assoc($eventQuery);

// Fetch user data
$userQuery = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($userQuery);


$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'tahsinniyan@gmail.com';
    $mail->Password = 'tybk dsrc dsyf mwkv';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('tahsinniyan@gmail.com', 'TicketKing');
    $mail->addAddress($_SESSION['user']['email'], $_SESSION['user']['name']);
    $mail->isHTML(true);
    $mail->Subject = "Your Ticket Invoice for {$event['title']}";
    
    $pdfPath = generateTicketPDF($order_id, $event, $ticket, $user);
    $mail->Body = "Dear {$_SESSION['user']['name']},<br>Thank you for your purchase. Find your ticket attached.";
    $mail->addAttachment($pdfPath);
    
    $mail->send();
    echo 'Message sent successfully';
} catch (Exception $e) {
    echo "Mailer Error: {$mail->ErrorInfo}";
}
        unset($_SESSION['event_cart'][$event_id]);
        $_SESSION['purchase_status'] = "Payment Successful!";

    }
        
    header("Location: checkout.php?event_id=$event_id");
    exit;
} else {
    header("Location: index.php");
    exit;
}
