<?php
session_start();
include "./DB/database.php";

$cart = $_SESSION['event_cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart</title>
  <link href="./output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
    <?php
    include "./navbar.php";
    ?>
  <h2 class="text-2xl font-bold mb-4">🛒 Your Cart</h2>

  <?php if (empty($cart)): ?>
    <p class="text-gray-500">Your cart is empty. <a href="events.php" class="text-blue-600 underline">Browse Events</a></p>
  <?php else: ?>
    <?php $total = 0; ?>
    <div class="space-y-4">
      <?php foreach ($cart as $event_id => $tickets): ?>
        <?php
        $event = mysqli_fetch_assoc(mysqli_query($conn, "SELECT title FROM events WHERE id = $event_id"));
        ?>
        <div class="bg-white p-4 rounded shadow">
          <h3 class="font-semibold text-lg mb-2">🎫 <?= $event['title'] ?></h3>
          <ul>
            <?php foreach ($tickets as $ticket_id => $quantity): ?>
              <?php
              $ticket = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tickets WHERE id = $ticket_id"));
              $subtotal = $ticket['price'] * $quantity;
              $total += $subtotal;
              ?>
              <li class="flex justify-between items-center border-b py-2">
                <div>
                  <p><?= $ticket['name'] ?> (<?= $quantity ?> × <?= $ticket['price'] ?>৳)</p>
                </div>
                <div class="space-x-2">
                  <a href="addticket.php?event_id=<?= $event_id ?>" class="text-green-600 underline">Add More</a>
                  <a href="cart_handler.php?action=remove&event_id=<?= $event_id ?>&ticket_id=<?= $ticket_id ?>" class="text-red-600 underline">Remove</a>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="mt-6 text-right">
      <p class="text-xl font-bold">Total: <?= $total ?>৳</p>
      <a href="checkout.php" class="inline-block bg-blue-600 text-white px-4 py-2 rounded mt-2">Proceed to Checkout</a>
    </div>
  <?php endif; ?>
</body>
</html>
