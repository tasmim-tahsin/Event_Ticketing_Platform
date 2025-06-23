<?php
session_start();
include 'auth.php';
include 'DB/database.php';

if (!isset($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header("Location: signin.php");
    exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$is_organizer = strtolower($user['role']) === 'organizer';
// echo "<pre>";
// print_r($_SESSION['user']);
// echo "</pre>";
// exit;


// Fetch total tickets (applies to both users and organizers)
$total_tickets = 0;
$result_tickets = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE user_id = '$user_id'");
if ($row = mysqli_fetch_assoc($result_tickets)) {
    $total_tickets = $row['total'];
}

// Fetch total orders (only if not organizer)
$total_orders = 0;
if (!$is_organizer) {
    $result_orders = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE user_id = '$user_id'");
    if ($row = mysqli_fetch_assoc($result_orders)) {
        $total_orders = $row['total'];
    }
}

// Fetch total events (only if organizer)
$total_events = 0;
if ($is_organizer) {
    $result_events = mysqli_query($conn, "SELECT COUNT(*) AS total FROM events WHERE organizer_id = '$user_id'");
    if ($row = mysqli_fetch_assoc($result_events)) {
        $total_events = $row['total'];
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($_SESSION['user']['name']) ?> - Profile</title>
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <?php
        
        include "./navbar.php";
    ?>
    
    <div class="max-w-6xl mx-auto px-6 py-8">
    <div class="bg-white p-6 rounded-lg shadow-md flex flex-col md:flex-row items-center justify-between">
        <div class="flex items-center space-x-6">
            <img src="<?= !empty($user['profile_image']) ? $user['profile_image'] : './images/default-avatar.png' ?>" alt="Profile Image"
                class="w-28 h-28 rounded-full border border-gray-300 object-cover">
            <div>
                <h2 class="text-2xl font-bold">Hello, <?= htmlspecialchars($user['name']) ?></h2>
                <div class="text-sm text-gray-600 mt-1 space-y-1">
                    <div><i class="fa-solid fa-envelope text-blue-600 mr-1"></i> <?= htmlspecialchars($user['email']) ?></div>
                    <div><i class="fa-solid fa-phone text-blue-600 mr-1"></i> <?= htmlspecialchars($user['phone'] ?? 'None') ?></div>
                    <div><i class="fa-solid fa-user text-blue-600 mr-1"></i> <?= htmlspecialchars($user['address'] ?? '') ?></div>
                </div>
                <div class="mt-3 space-x-2">
                    <a href="editprofile.php" class="inline-block bg-gray-800 text-white px-4 py-1 rounded hover:bg-gray-700">
                        Edit Profile <i class="fa-regular fa-pen-to-square"></i>
                    </a>
                    <a href="resetpassword.php" class="inline-block bg-lime-400 text-black px-4 py-1 rounded hover:bg-lime-500">
                        Reset Password
                    </a>
                </div>
            </div>
        </div>

        <div class="flex space-x-6 mt-6 md:mt-0">
            <?php if (!$is_organizer): ?>
                <div class="text-center bg-stone-100 p-5 rounded-md">
                    <p class="text-sm text-gray-600">Your Total Orders</p>
                    <div class="text-lg font-bold bg-gray-800 text-white px-1 py-1 rounded"><?= $total_orders ?></div>
                </div>
            <?php endif; ?>
            <?php if (!$is_organizer): ?>
            <div class="text-center">
                <p class="text-sm text-gray-600 bg-stone-100 p-5 rounded-md">Your Total Tickets</p>
                <div class="text-lg font-bold bg-gray-800 text-white px-1 py-1 rounded"><?= $total_tickets ?></div>
            </div>
            <?php endif; ?>
            <?php if ($is_organizer): ?>
                <div class="text-center">
                    <p class="text-sm text-gray-600 bg-stone-100 p-5 rounded-md">Your Total Events</p>
                    <div class="text-lg font-bold bg-gray-800 text-white px-1 py-1 rounded"><?= $total_events ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class=" flex gap-5 max-w-6xl mx-auto px-6 py-6 mb-10">
    <button class="bg-black text-white px-4 py-2 rounded" data-tab="tickipass">
        <i class="fa-solid fa-qrcode mr-2"></i> TICKIPASS
    </button>
    <?php if ($is_organizer): ?>
        <button class="bg-red-500 text-white px-4 py-2 rounded">
            <i class="fa-solid fa-circle-check mr-2"></i> <a href="./verify_ticket.php">Verify Ticket</a>
        </button>
        <button class="bg-black text-white px-4 py-2 rounded">
            <i class="fa-solid fa-sliders mr-2"></i> <a href="./dashboard.php">Dashboard</a>
        </button>
        <button class="bg-black text-white px-4 py-2 rounded">
            <i class="fa-solid fa-chart-simple mr-2"></i> <a href="./sales_report.php">Sales Report</a>
        </button>
    <?php endif; ?>
</div>


<div id="tickipass-container" class="my-6 hidden max-w-6xl mx-auto">
    <div id="tickipass-loader" class="text-center text-gray-500">Loading tickets...</div>
    <div id="tickipass-content" class="grid md:grid-cols-2 gap-4 mt-4"></div>
</div>




    <?php
        include "./footer.php";
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
    const tickipassBtn = document.querySelector("button[data-tab='tickipass']");
    const container = document.getElementById("tickipass-container");
    const loader = document.getElementById("tickipass-loader");
    const content = document.getElementById("tickipass-content");

    tickipassBtn.addEventListener('click', () => {
        container.classList.remove("hidden");
        loader.style.display = "block";
        content.innerHTML = "";

        fetch('fetch_tickipass.php')
            .then(res => {
                if (!res.ok) throw new Error("Failed to fetch");
                return res.json();
            })
            .then(data => {
                loader.style.display = "none";

                if (!Array.isArray(data) || data.length === 0) {
                    content.innerHTML = `
                        <div class="col-span-2 text-center text-gray-500">
                            <img src="./images/empty.png" alt="No Tickets" class="w-36 mx-auto mb-3">
                            <p>You have no tickets to show.</p>
                        </div>`;
                    return;
                }

                // In the fetch_tickipass.php response handler
data.forEach(ticket => {
    const card = document.createElement("div");
    card.className = "bg-white rounded-lg shadow p-4 border border-gray-200";

    // Make sure ticket.ticket_file contains the correct filename
    card.innerHTML = `
        <h3 class="text-xl font-bold mb-2 text-gray-800">${ticket.title}</h3>
        <p class="text-sm text-gray-600"><i class="fa-solid fa-location-dot mr-1 text-blue-600"></i>${ticket.location}</p>
        <p class="text-sm text-gray-600"><i class="fa-solid fa-clock mr-1 text-blue-600"></i>${ticket.event_date}</p>
        <a href="download.php?file=${encodeURIComponent(ticket.ticket_file)}" class="inline-block mt-3 bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
            Download Ticket <i class="fa-solid fa-download ml-1"></i>
        </a>`;
    content.appendChild(card);
});
            })
            .catch(err => {
                loader.style.display = "none";
                content.innerHTML = `<div class="text-red-600">Error loading TickiPass: ${err.message}</div>`;
                console.error("TickiPass Fetch Error:", err);
            });
    });
});
</script>



</body>
</html>