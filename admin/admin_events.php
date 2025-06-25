<?php
session_start();
include '../DB/database.php';

// Check admin authentication
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

// Handle event actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve_event']) || isset($_POST['reject_event'])) {
        $event_id = intval($_POST['event_id']);
        $new_status = isset($_POST['approve_event']) ? 'approved' : 'rejected';
        
        $stmt = $conn->prepare("UPDATE events SET admin_status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $event_id);
        
        if ($stmt->execute()) {
            $_SESSION['admin_message'] = "Event {$new_status} successfully";
        } else {
            $_SESSION['admin_message'] = "Error updating event: " . $stmt->error;
        }
        $stmt->close();
        
        header("Location: admin_events.php");
        exit;
    }
}

// Get filter if exists
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Build query based on filter
$query = "SELECT e.*, u.name as organizer_name FROM events e JOIN users u ON e.organizer_id = u.id";
if ($filter === 'pending') {
    $query .= " WHERE e.admin_status = 'pending'";
} elseif ($filter === 'approved') {
    $query .= " WHERE e.admin_status = 'approved'";
} elseif ($filter === 'rejected') {
    $query .= " WHERE e.admin_status = 'rejected'";
}
$query .= " ORDER BY e.date DESC";

// Execute query with error handling
$events = [];
$result = mysqli_query($conn, $query);

if (!$result) {
    // Log error and show user-friendly message
    error_log("Database error: " . mysqli_error($conn));
    $_SESSION['admin_message'] = "Error loading events. Please try again later.";
} else {
    while ($event = mysqli_fetch_assoc($result)) {
        $events[] = $event;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management | TicketKing</title>
    <link href="../output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <?php include 'admin_sidebar.php'; ?>
        
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm p-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Event Management</h2>
                    <div class="flex items-center space-x-4">
                        <span>Welcome, <?= htmlspecialchars($_SESSION['admin']['name']) ?></span>
                    </div>
                </div>
            </header>

            <main class="p-6">
                <?php if (isset($_SESSION['admin_message'])): ?>
                <div class="mb-4 p-4 rounded <?= strpos($_SESSION['admin_message'], 'Error') !== false ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' ?>">
                    <?= $_SESSION['admin_message'] ?>
                    <?php unset($_SESSION['admin_message']); ?>
                </div>
                <?php endif; ?>

                <div class="mb-4 flex justify-between items-center">
                    <div class="flex space-x-2">
                        <a href="admin_events.php?filter=all" class="px-3 py-1 rounded <?= $filter === 'all' ? 'bg-blue-500 text-white' : 'bg-gray-200' ?>">All Events</a>
                        <a href="admin_events.php?filter=pending" class="px-3 py-1 rounded <?= $filter === 'pending' ? 'bg-yellow-500 text-white' : 'bg-gray-200' ?>">Pending</a>
                        <a href="admin_events.php?filter=approved" class="px-3 py-1 rounded <?= $filter === 'approved' ? 'bg-green-500 text-white' : 'bg-gray-200' ?>">Approved</a>
                        <a href="admin_events.php?filter=rejected" class="px-3 py-1 rounded <?= $filter === 'rejected' ? 'bg-red-500 text-white' : 'bg-gray-200' ?>">Rejected</a>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">
                            Showing <?= count($events) ?> event(s)
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <?php if (empty($events)): ?>
                        <div class="p-6 text-center text-gray-500">
                            No events found matching your criteria
                        </div>
                    <?php else: ?>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Organizer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date/Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($events as $event): ?>
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <?php if (!empty($event['image'])): ?>
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded" src="../<?= htmlspecialchars($event['image']) ?>" alt="">
                                            </div>
                                            <?php endif; ?>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($event['title']) ?></div>
                                                <div class="text-sm text-gray-500"><?= htmlspecialchars($event['location']) ?></div>
                                                <div class="text-xs text-gray-400"><?= htmlspecialchars($event['category']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($event['organizer_name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?= date('M j, Y', strtotime($event['date'])) ?></div>
                                        <div class="text-sm text-gray-500"><?= date('g:i A', strtotime($event['time'])) ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ৳<?= number_format($event['price'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= $event['admin_status'] === 'approved' ? 'bg-green-100 text-green-800' : 
                                               ($event['admin_status'] === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                            <?= ucfirst($event['admin_status']) ?>
                                        </span>
                                        <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= $event['status'] === 'live' ? 'bg-blue-100 text-blue-800' : 
                                               ($event['status'] === 'past' ? 'bg-gray-100 text-gray-800' : 'bg-purple-100 text-purple-800') ?>">
                                            <?= ucfirst($event['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <?php if ($event['admin_status'] === 'pending'): ?>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                            <button type="submit" name="approve_event" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                            <button type="submit" name="reject_event" class="text-red-600 hover:text-red-900 mr-3">Reject</button>
                                        </form>
                                        <?php endif; ?>
                                        <?php if ($event['admin_status'] === 'rejected'): ?>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                            <button type="submit" name="approve_event" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                        </form>
                                        <?php endif; ?>
                                        <form method="POST" class="inline">
                                            <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                            <button type="submit" name="reject_event" class="text-red-600 hover:text-red-900 mr-3">Reject</button>
                                        </form>
                                        <a href="../event-details.php?id=<?= $event['id'] ?>" class="text-blue-600 hover:text-blue-900">View</a>
                                        <a href="admin_edit_event.php?id=<?= $event['id'] ?>" class="ml-2 text-indigo-600 hover:text-indigo-900">Edit</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>