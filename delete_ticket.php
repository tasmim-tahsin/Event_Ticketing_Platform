<?php
session_start();
include 'organizer-auth.php';
include 'DB/database.php';

// Only allow organizer
// if (!isset($_SESSION['user']) || strtolower($_SESSION['user']['role']) !== 'organizer') {
//     header("Location: login.php");
//     exit;
// }

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$ticket_id = $_GET['id'];
$organizer_id = $_SESSION['user']['id'];

// Verify the ticket belongs to an event owned by this organizer
$ticket_query = mysqli_query($conn, 
    "SELECT t.id 
     FROM tickets t 
     JOIN events e ON t.event_id = e.id 
     WHERE t.id = $ticket_id AND e.organizer_id = $organizer_id");

if (mysqli_num_rows($ticket_query) == 0) {
    $_SESSION['error'] = "Ticket not found or you don't have permission to delete it";
    header("Location: dashboard.php");
    exit;
}

// Delete the ticket
$delete_query = "DELETE FROM tickets WHERE id = $ticket_id";
if (mysqli_query($conn, $delete_query)) {
    $_SESSION['success'] = "Ticket deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting ticket: " . mysqli_error($conn);
}

header("Location: dashboard.php");
exit;
?>