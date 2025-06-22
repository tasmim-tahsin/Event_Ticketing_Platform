<?php
session_start();
include '../DB/database.php';

// Check admin authentication
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}

// Get all orders with user and event info
$orders = mysqli_query($conn, "
    SELECT o.*, u.name as user_name, u.email as user_email, 
           e.title as event_title, t.name as ticket_name
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN events e ON o.event_id = e.id
    JOIN tickets t ON o.ticket_id = t.id
    ORDER BY o.order_date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management | TicketKing</title>
    <link href="../output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <?php include 'admin_sidebar.php'; ?>
        
        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow-sm p-4">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-semibold">Order Management</h2>
                    <div class="flex items-center space-x-4">
                        <span>Welcome, <?= htmlspecialchars($_SESSION['admin']['name']) ?></span>
                    </div>
                </div>
            </header>

            <main class="p-6">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($order = mysqli_fetch_assoc($orders)): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #<?= $order['id'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($order['user_name']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($order['user_email']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($order['event_title']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($order['ticket_name']) ?></div>
                                    <div class="text-sm text-gray-500">Qty: <?= $order['quantity'] ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">BDT <?= number_format($order['total_price'], 2) ?></div>
                                    <div class="text-sm text-gray-500"><?= $order['quantity'] ?> × BDT <?= number_format($order['total_price'] / $order['quantity'], 2) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M j, Y g:i A', strtotime($order['order_date'])) ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>
</body>
</html>