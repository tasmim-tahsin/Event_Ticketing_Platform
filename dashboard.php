<?php
session_start();
include 'organizer-auth.php';
include 'DB/database.php';

// Only allow organizer
if (!isset($_SESSION['user']) || strtolower($_SESSION['user']['role']) !== 'organizer') {
    header("Location: index.php");
    exit;
}

$organizer_id = $_SESSION['user']['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Dashboard</title>
    <link href="./output.css" rel="stylesheet">
    <!-- Include Heroicons -->
    <script src="https://unpkg.com/@heroicons/react@2.0.11/outline/index.js" defer></script>
    <style>
        .event-card {
            transition: all 0.3s ease;
        }
        .event-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
        }
        .status-upcoming {
            background-color: #3B82F6;
            color: white;
        }
        .status-live {
            background-color: #10B981;
            color: white;
        }
        .status-past {
            background-color: #6B7280;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50">
<?php include 'navbar.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Organizer Dashboard</h1>
        <div class="flex space-x-4">
            <a href="create_event.php" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                New Event
            </a>
            <a href="create_ticket.php" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                New Ticket
            </a>
        </div>
    </div>

    <!-- Events Section -->
    <div class="mb-12">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Your Events</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $events = mysqli_query($conn, "SELECT * FROM events WHERE organizer_id = $organizer_id ORDER BY date DESC");
            while ($event = mysqli_fetch_assoc($events)) {
                $status_class = "status-" . $event['status'];
                echo '
                <div class="event-card bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="status-badge ' . $status_class . '">' . ucfirst($event['status']) . '</span>
                            </div>
                            <div class="flex space-x-2">
                                <a href="edit_event.php?id=' . $event['id'] . '" class="text-blue-500 hover:text-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                                <a href="delete_event.php?id=' . $event['id'] . '" class="text-red-500 hover:text-red-700" onclick="return confirm(\'Delete this event?\')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-medium text-gray-900">' . htmlspecialchars($event['title']) . '</h3>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                </svg>
                                ' . date('M j, Y', strtotime($event['date'])) . ' at ' . date('g:i A', strtotime($event['time'])) . '
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                ' . htmlspecialchars($event['location']) . '
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                                </svg>
                                BDT ' . number_format($event['price'], 2) . '
                            </div>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>

    <!-- Tickets Section - Organized by Event -->
    <div class="mb-12">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Your Tickets</h2>
        
        <?php
        // Get events that have tickets
        $events_with_tickets = mysqli_query($conn, 
            "SELECT e.id, e.title 
             FROM events e 
             JOIN tickets t ON e.id = t.event_id 
             WHERE e.organizer_id = $organizer_id 
             GROUP BY e.id 
             ORDER BY e.date DESC");
        
        while ($event = mysqli_fetch_assoc($events_with_tickets)) {
            echo '
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">' . htmlspecialchars($event['title']) . '</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            ';
            
            // Get tickets for this event
            $tickets = mysqli_query($conn, 
                "SELECT * FROM tickets 
                 WHERE event_id = {$event['id']} 
                 ORDER BY price DESC");
            
            while ($ticket = mysqli_fetch_assoc($tickets)) {
                echo '
                <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    ' . ucfirst(str_replace('day', 'Day ', $ticket['day'])) . '
                                </span>
                            </div>
                            <div class="flex space-x-2">
                                <a href="edit_ticket.php?id=' . $ticket['id'] . '" class="text-blue-500 hover:text-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>
                                <a href="delete_ticket.php?id=' . $ticket['id'] . '" class="text-red-500 hover:text-red-700" onclick="return confirm(\'Delete this ticket?\')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="mt-4">
                            <h3 class="text-lg font-medium text-gray-900">' . htmlspecialchars($ticket['name']) . '</h3>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
                                </svg>
                                BDT ' . number_format($ticket['price'], 2) . '
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                </svg>
                                Available: ' . $ticket['quantity'] . ' tickets
                            </div>
                            <div class="mt-3 text-sm text-gray-600">
                                ' . htmlspecialchars($ticket['benefits']) . '
                            </div>
                        </div>
                    </div>
                </div>
                ';
            }
            
            echo '
                </div>
            </div>
            ';
        }
        ?>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>