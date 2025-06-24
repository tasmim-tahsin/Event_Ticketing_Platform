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

// Get sales data
$sales_query = mysqli_query($conn, "
    SELECT 
        e.id as event_id,
        e.title as event_title,
        e.date as event_date,
        t.id as ticket_id,
        t.name as ticket_name,
        t.price as ticket_price,
        COUNT(o.id) as tickets_sold,
        SUM(t.price) as total_revenue
    FROM 
        orders o
    JOIN 
        tickets t ON o.ticket_id = t.id
    JOIN 
        events e ON t.event_id = e.id
    WHERE 
        e.organizer_id = $organizer_id
    GROUP BY 
        t.id
    ORDER BY 
        e.date DESC, total_revenue DESC
");

// Calculate totals
$total_tickets_sold = 0;
$total_revenue = 0;
$sales_data = [];
while ($row = mysqli_fetch_assoc($sales_query)) {
    $sales_data[] = $row;
    $total_tickets_sold += $row['tickets_sold'];
    $total_revenue += $row['total_revenue'];
}

// Get events for filter dropdown
$events = mysqli_query($conn, "
    SELECT id, title 
    FROM events 
    WHERE organizer_id = $organizer_id 
    ORDER BY date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link href="./output.css" rel="stylesheet">
    <!-- Include Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card {
            transition: all 0.3s ease;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .progress-bar {
            height: 0.5rem;
            border-radius: 0.25rem;
        }
        .revenue-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 9999px;
        }
    </style>
</head>
<body class="bg-gray-50">
<nav class="sticky top-0 z-50">
        <?php
            include "./navbar.php";
        ?>
    </nav>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Sales Report</h1>
            <p class="text-gray-600">Track your ticket sales and revenue</p>
        </div>
        <div class="mt-4 md:mt-0">
            <select id="event-filter" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                <option value="all">All Events</option>
                <?php while ($event = mysqli_fetch_assoc($events)): ?>
                    <option value="<?php echo $event['id']; ?>"><?php echo htmlspecialchars($event['title']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Tickets Sold</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900"><?php echo number_format($total_tickets_sold); ?></div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">BDT <?php echo number_format($total_revenue, 2); ?></div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Average Revenue per Ticket</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900">
                                    BDT <?php echo $total_tickets_sold > 0 ? number_format($total_revenue / $total_tickets_sold, 2) : '0.00'; ?>
                                </div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="card bg-white p-6 shadow rounded-lg">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Revenue by Ticket Type</h2>
            <canvas id="revenueChart" class="w-full" height="300"></canvas>
        </div>
        <div class="card bg-white p-6 shadow rounded-lg">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Tickets Sold by Event</h2>
            <canvas id="ticketsChart" class="w-full" height="300"></canvas>
        </div>
    </div>

    <!-- Sales Table -->
    <div class="card bg-white shadow rounded-lg overflow-hidden mb-8">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Detailed Sales Breakdown</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sold</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($sales_data as $sale): ?>
                    <tr class="event-row" data-event="<?php echo $sale['event_id']; ?>">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($sale['event_title']); ?></div>
                            <div class="text-sm text-gray-500"><?php echo date('M j, Y', strtotime($sale['event_date'])); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($sale['ticket_name']); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">BDT <?php echo number_format($sale['ticket_price'], 2); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?php echo $sale['tickets_sold']; ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="revenue-badge bg-green-100 text-green-800">
                                BDT <?php echo number_format($sale['total_revenue'], 2); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Filter table by event
    document.getElementById('event-filter').addEventListener('change', function() {
        const eventId = this.value;
        const rows = document.querySelectorAll('.event-row');
        
        rows.forEach(row => {
            if (eventId === 'all' || row.dataset.event === eventId) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Prepare data for charts
    const revenueData = {
        labels: [<?php echo implode(',', array_map(function($sale) { return "'" . addslashes($sale['ticket_name']) . "'"; }, $sales_data)); ?>],
        datasets: [{
            label: 'Revenue (BDT)',
            data: [<?php echo implode(',', array_column($sales_data, 'total_revenue')); ?>],
            backgroundColor: [
                '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6', 
                '#EC4899', '#14B8A6', '#F97316', '#64748B', '#A855F7'
            ],
            borderWidth: 1
        }]
    };

    const ticketsData = {
        labels: [<?php 
            $event_totals = [];
            foreach ($sales_data as $sale) {
                if (!isset($event_totals[$sale['event_title']])) {
                    $event_totals[$sale['event_title']] = 0;
                }
                $event_totals[$sale['event_title']] += $sale['tickets_sold'];
            }
            echo implode(',', array_map(function($title) { return "'" . addslashes($title) . "'"; }, array_keys($event_totals))); 
        ?>],
        datasets: [{
            label: 'Tickets Sold',
            data: [<?php echo implode(',', array_values($event_totals)); ?>],
            backgroundColor: '#6366F1',
            borderWidth: 1
        }]
    };

    // Initialize charts when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue by Ticket Type (Doughnut Chart)
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'doughnut',
            data: revenueData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': BDT ' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Tickets by Event (Bar Chart)
        const ticketsCtx = document.getElementById('ticketsChart').getContext('2d');
        new Chart(ticketsCtx, {
            type: 'bar',
            data: ticketsData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value;
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw + ' tickets';
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>