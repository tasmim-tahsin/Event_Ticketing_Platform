<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="../output.css" rel="stylesheet">
</head>
<body>
    <div class="w-64 bg-blue-800 text-white p-4">
            <h1 class="text-2xl font-bold mb-8">TicketKing Admin</h1>
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="admin_dashboard.php" class="flex items-center p-2 rounded hover:bg-blue-700">
                            <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="admin_users.php" class="flex items-center p-2 rounded hover:bg-blue-700">
                            <i class="fas fa-users mr-3"></i> User Management
                        </a>
                    </li>
                    <li>
                        <a href="admin_events.php" class="flex items-center p-2 rounded hover:bg-blue-700">
                            <i class="fas fa-calendar-alt mr-3"></i> Event Management
                        </a>
                    </li>
                    <li>
                        <a href="admin_orders.php" class="flex items-center p-2 rounded hover:bg-blue-700">
                            <i class="fas fa-ticket-alt mr-3"></i> Order Management
                        </a>
                    </li>
                    <?php if ($_SESSION['admin']['role'] === 'super_admin'): ?>
                    <li>
                        <a href="admin_admins.php" class="flex items-center p-2 rounded hover:bg-blue-700">
                            <i class="fas fa-user-shield mr-3"></i> Admin Management
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="../verify_ticket.php" class="flex items-center p-2 rounded hover:bg-blue-700">
                            <i class="fa-solid fa-circle-check mr-3"></i> Verify Ticket
                        </a>
                    </li>
                    <li>
                        <a href="admin_logout.php" class="flex items-center p-2 rounded hover:bg-blue-700">
                            <i class="fas fa-sign-out-alt mr-3"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
</body>
</html>