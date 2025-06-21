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

$event_id = $_GET['id'];
$organizer_id = $_SESSION['user']['id'];

// Verify the event belongs to this organizer
$event_query = mysqli_query($conn, "SELECT id, image FROM events WHERE id = $event_id AND organizer_id = $organizer_id");
$event = mysqli_fetch_assoc($event_query);

if (!$event) {
    $_SESSION['error'] = "Event not found or you don't have permission to delete it";
    header("Location: dashboard.php");
    exit;
}

// Delete associated tickets first
mysqli_query($conn, "DELETE FROM tickets WHERE event_id = $event_id");

// Delete the event
$delete_query = "DELETE FROM events WHERE id = $event_id";
if (mysqli_query($conn, $delete_query)) {
    // Delete the image file if it exists
    if ($event['image'] && file_exists($event['image'])) {
        unlink($event['image']);
    }
    $_SESSION['success'] = "Event deleted successfully!";
} else {
    $_SESSION['error'] = "Error deleting event: " . mysqli_error($conn);
}

header("Location: dashboard.php");
exit;
?>