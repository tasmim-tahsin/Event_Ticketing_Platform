<?php
session_start();
include '../DB/database.php';

// Check admin authentication
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

// Get counts for dashboard with error handling
function getCount($conn, $query) {
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Database error: " . mysqli_error($conn));
        return 0;
    }
    $row = mysqli_fetch_assoc($result);
    return $row ? $row['count'] : 0;
}

// Get dashboard statistics
$total_users = getCount($conn, "SELECT COUNT(*) as count FROM users");
$total_events = getCount($conn, "SELECT COUNT(*) as count FROM events");
$total_orders = getCount($conn, "SELECT COUNT(*) as count FROM orders");
$total_tickets_sold = getCount($conn, "SELECT SUM(quantity) as count FROM orders");
$pending_events = getCount($conn, "SELECT COUNT(*) as count FROM events WHERE admin_status='pending'");
$approved_events = getCount($conn, "SELECT COUNT(*) as count FROM events WHERE admin_status='approved'");
$rejected_events = getCount($conn, "SELECT COUNT(*) as count FROM events WHERE admin_status='rejected'");
$upcoming_events = getCount($conn, "SELECT COUNT(*) as count FROM events WHERE status='upcoming'");
$live_events = getCount($conn, "SELECT COUNT(*) as count FROM events WHERE status='live'");
$past_events = getCount($conn, "SELECT COUNT(*) as count FROM events WHERE status='past'");

// Get recent events
$recent_events = [];
$events_query = "SELECT e.id, e.title, e.image, e.date, e.location, e.status, e.admin_status 
                 FROM events e 
                 ORDER BY e.date DESC LIMIT 5";
$events_result = mysqli_query($conn, $events_query);
if ($events_result) {
    while ($event = mysqli_fetch_assoc($events_result)) {
        $recent_events[] = $event;
    }
}

// Get recent orders with event titles
$recent_orders = [];
$orders_query = "SELECT o.id, o.order_date, o.total_price, e.title as event_title 
                 FROM orders o
                 JOIN events e ON o.event_id = e.id
                 ORDER BY o.order_date DESC LIMIT 5";
$orders_result = mysqli_query($conn, $orders_query);
if ($orders_result) {
    while ($order = mysqli_fetch_assoc($orders_result)) {
        $recent_orders[] = $order;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | TicketKing</title>
    <link href="../output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <?php include 'admin_sidebar.php'; ?>
        
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm p-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Dashboard Overview</h2>
                    <div class="flex items-center space-x-4">
                        <span>Welcome, <?= htmlspecialchars($_SESSION['admin']['name']) ?></span>
                        <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                            <?= ucfirst(str_replace('_', ' ', $_SESSION['admin']['role'])) ?>
                        </span>
                    </div>
                </div>
            </header>

            <main class="p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Users -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Users</p>
                                <p class="text-2xl font-semibold"><?= $total_users ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Events -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                                <i class="fas fa-calendar-alt text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Events</p>
                                <p class="text-2xl font-semibold"><?= $total_events ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Orders -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                                <i class="fas fa-ticket-alt text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Total Tickets Sold</p>
                                <p class="text-2xl font-semibold"><?= $total_tickets_sold ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pending Events -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pending Events</p>
                                <p class="text-2xl font-semibold"><?= $pending_events ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Event Status Breakdown -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Event Status</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Upcoming</span>
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm"><?= $upcoming_events ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Live</span>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm"><?= $live_events ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Past</span>
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-sm"><?= $past_events ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Approval Status -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Approval Status</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Approved</span>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm"><?= $approved_events ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Pending</span>
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-sm"><?= $pending_events ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium">Rejected</span>
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-sm"><?= $rejected_events ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Orders -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold mb-4">Recent Orders</h3>
                        <?php if (empty($recent_orders)): ?>
                            <p class="text-gray-500 text-sm">No recent orders</p>
                        <?php else: ?>
                            <div class="space-y-3">
                                <?php foreach ($recent_orders as $order): ?>
                                <div class="border-b pb-2 last:border-0 last:pb-0">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <p class="text-sm font-medium"><?= htmlspecialchars($order['event_title']) ?></p>
                                            <p class="text-xs text-gray-500">Order #<?= $order['id'] ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold">৳<?= number_format($order['total_price'], 2) ?></p>
                                            <p class="text-xs text-gray-500"><?= date('M j, g:i A', strtotime($order['order_date'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Events -->
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h3 class="text-lg font-semibold mb-4">Recent Events</h3>
                    <?php if (empty($recent_events)): ?>
                        <p class="text-gray-500">No recent events found</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($recent_events as $event): ?>
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
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= date('M j, Y', strtotime($event['date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($event['location']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?= $event['status'] === 'live' ? 'bg-blue-100 text-blue-800' : 
                                                   ($event['status'] === 'past' ? 'bg-gray-100 text-gray-800' : 'bg-purple-100 text-purple-800') ?>">
                                                <?= ucfirst($event['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
</body>
</html>