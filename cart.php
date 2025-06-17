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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <?php
    include "./navbar.php";
    ?>

<div class=" max-w-full lg:max-w-1/2 mx-auto">
    <h2 class=" text-center text-2xl font-bold my-4">🛒 Your Cart</h2>

  <?php if (empty($cart)): ?>
    <p class="text-gray-500">Your cart is empty. <a href="events.php" class="text-blue-600 underline">Browse Events</a></p>
  <?php else: ?>
    <div class="space-y-4">
      <?php foreach ($cart as $event_id => $tickets): ?>
        <!-- Check if the event has tickets, if not skip it -->
        <?php if (empty($tickets)) continue; ?>
        
        <?php
        $event = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id"));
        ?>
        
        <?php $event_total = 0; // Total for this specific event ?>
        
        <div class="bg-white p-6 rounded-lg shadow-md flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-4">
          <div class="flex-shrink-0">
            <img src="<?= htmlspecialchars($event['image']) ?>" alt="Event Image" class="w-full rounded-lg shadow-md max-h-28 object-cover border border-white">
          </div>
          <div class="flex-1">
            <h3 class="font-semibold text-xl mb-2"><?= $event['title'] ?></h3>
            <ul class="flex gap-2">
              <?php foreach ($tickets as $ticket_id => $quantity): ?>
                <?php
                $ticket = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM tickets WHERE id = $ticket_id"));
                $subtotal = $ticket['price'] * $quantity;
                $event_total += $subtotal; // Add subtotal for each ticket to event total
                ?>
                <div class="flex py-1">
                  <p class="bg-red-700 text-white rounded-lg p-1 px-1 text-xs font-bold"> <?= $ticket['name'] ?> - <?= $quantity ?></p>
                </div>
              <?php endforeach; ?>
            </ul>
            <div class="space-x-2 flex mt-2">
              <!-- Trash Button -->
    <a href="addticket.php?event_id=<?= $event_id ?>" class="bg-red-500 font-bold p-3  text-white rounded-lg flex items-center space-x-2">
        <i class="fa-solid fa-trash" style="color: white"></i>
    </a>

    <!-- Add More Button -->
    <a href="addticket.php?event_id=<?= $event_id ?>" class="bg-gray-500 font-bold p-2  text-white  rounded-lg flex items-center space-x-2">
        <i class="fa-solid fa-plus"></i>
        <span>Add More</span>
    </a>

    <!-- Checkout Button -->
    <a href="addticket.php?event_id=<?= $event_id ?>" class="bg-yellow-500 font-bold p-2  text-black rounded-lg flex items-center space-x-2">
        <i class="fa-solid fa-credit-card"></i>
        <span>Checkout</span>
    </a>
                    
                    <!-- <a href="addticket.php?event_id=<?= $event_id ?>" class="text-green-600 underline">Add More</a>
                    <a href="cart_handler.php?action=remove&event_id=<?= $event_id ?>&ticket_id=<?= $ticket_id ?>" class="text-red-600 underline">Remove</a> -->
                  </div>
          </div>
          <div class="text-center md:text-right mt-4 md:mt-0">
            <p class="font-bold text-lg">Total: <?= $event_total ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

  <?php endif; ?>
</div>

</body>
</html>
