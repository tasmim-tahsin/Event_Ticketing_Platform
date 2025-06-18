<?php
session_start();
include 'auth.php';
include 'DB/database.php';

$user = $_SESSION['user'];
$user_id = $user['id'];
$is_organizer = strtolower($user['role']) === 'organizer';

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
    <title>Document</title>
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
                    <div><i class="fa-solid fa-user text-blue-600 mr-1"></i> <?= htmlspecialchars($user['username'] ?? '') ?></div>
                </div>
                <div class="mt-3 space-x-2">
                    <a href="editprofile.php" class="inline-block bg-gray-800 text-white px-4 py-1 rounded hover:bg-gray-700">
                        Edit Profile
                    </a>
                    <a href="resetpassword.php" class="inline-block bg-lime-400 text-black px-4 py-1 rounded hover:bg-lime-500">
                        Reset Password
                    </a>
                </div>
            </div>
        </div>

        <div class="flex space-x-6 mt-6 md:mt-0">
            <?php if (!$is_organizer): ?>
                <div class="text-center">
                    <p class="text-sm text-gray-600">Your Total Orders:</p>
                    <div class="text-lg font-bold bg-gray-800 text-white px-3 py-1 rounded"><?= $total_orders ?></div>
                </div>
            <?php endif; ?>
            <div class="text-center">
                <p class="text-sm text-gray-600">Your Total Tickets:</p>
                <div class="text-lg font-bold bg-gray-800 text-white px-3 py-1 rounded"><?= $total_tickets ?></div>
            </div>
            <?php if ($is_organizer): ?>
                <div class="text-center">
                    <p class="text-sm text-gray-600">Your Total Events:</p>
                    <div class="text-lg font-bold bg-gray-800 text-white px-3 py-1 rounded"><?= $total_events ?></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


    <?php
        include "./footer.php";
    ?>
</body>
</html>