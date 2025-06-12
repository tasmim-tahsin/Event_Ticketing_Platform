<?php
include "./navbar.php";
include "./DB/database.php";
$id = $_GET['id'] ?? 0;

$query = "SELECT * FROM events WHERE id = " . intval($id);
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    echo "Event not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($row['title']) ?> - Event Details</title>
  <link href="./output.css" rel="stylesheet" />
</head>
<body class="bg-gray-100">

<div class="max-w-7xl mx-auto p-6 bg-white shadow-md rounded-lg">
  <img src="<?= $row['image'] ?>" alt="<?= $row['title'] ?>" class="w-full h-72 object-cover rounded-md mb-6">

  <h1 class="text-3xl font-bold mb-2"><?= htmlspecialchars($row['title']) ?></h1>
  <p class="text-gray-600 mb-4"><?= htmlspecialchars($row['description']) ?></p>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div>📍 Location: <?= $row['location'] ?></div>
    <div>📅 Date: <?= date('j M, Y', strtotime($row['date'])) ?></div>
    <div>⏰ Time: <?= date('g:i A', strtotime($row['time'])) ?></div>
    <div>💵 Ticket Price: ৳<?= $row['price'] ?></div>
    <div>🔒 Privacy: <?= $row['privacy'] ?></div>
    <div>📜 Policy: <?= $row['policy'] ?></div>
    <div>💳 Payment Method: <?= $row['payment_method'] ?></div>
  </div>

  <div class="mt-6">
    <!-- Social Sharing -->
    <p class="font-semibold">Share:</p>
    <div class="flex gap-3 mt-2">
      <a href="#" class="text-blue-600">Facebook</a>
      <a href="#" class="text-blue-400">Twitter</a>
      <a href="#" class="text-pink-500">Instagram</a>
    </div>
  </div>
</div>
<?php
    include "./footer.php";
?>
</body>
</html>
