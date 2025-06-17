<?php
session_start();
include "./DB/database.php";

$event_id = intval($_GET['event_id'] ?? 0);

// Fetch event info
$eventQuery = mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id");
$event = mysqli_fetch_assoc($eventQuery);

// Initialize cart for this event
if (!isset($_SESSION['event_cart'])) {
    $_SESSION['event_cart'] = []; // [event_id => [ticket_id => qty]]
}
if (!isset($_SESSION['event_cart'][$event_id])) {
    $_SESSION['event_cart'][$event_id] = [];
}

// Add ticket
if (isset($_GET['add_ticket_id'])) {
    $ticket_id = intval($_GET['add_ticket_id']);
    $current_qty = array_sum($_SESSION['event_cart'][$event_id]);
    if ($current_qty < 10) {
        if (!isset($_SESSION['event_cart'][$event_id][$ticket_id])) {
            $_SESSION['event_cart'][$event_id][$ticket_id] = 1;
        } else {
            $_SESSION['event_cart'][$event_id][$ticket_id]++;
        }
    }
    header("Location: addticket.php?event_id=$event_id");
    exit;
}

// Remove ticket
if (isset($_GET['remove_ticket_id'])) {
    $ticket_id = intval($_GET['remove_ticket_id']);
    unset($_SESSION['event_cart'][$event_id][$ticket_id]);
    header("Location: addticket.php?event_id=$event_id");
    exit;
}

// Fetch tickets for this event
$ticketsResult = mysqli_query($conn, "SELECT * FROM tickets WHERE event_id = $event_id");

// Cart details
$cart = $_SESSION['event_cart'][$event_id];
$ticketDetails = [];
$subtotal = 0;

if (!empty($cart)) {
    $ticket_ids = implode(",", array_keys($cart));
    $ticketQuery = mysqli_query($conn, "SELECT * FROM tickets WHERE id IN ($ticket_ids)");
    while ($ticket = mysqli_fetch_assoc($ticketQuery)) {
        $id = $ticket['id'];
        $qty = $cart[$id];
        $ticket['selected_quantity'] = $qty;
        $ticket['total_price'] = $qty * $ticket['price'];
        $subtotal += $ticket['total_price'];
        $ticketDetails[] = $ticket;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Buy Tickets</title>
  <link href="./output.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include "./navbar.php"; ?>

<!-- Event Top Header -->
<div class="bg-[#003C2F] text-white p-2 mb-8 rounded-md flex flex-col md:flex-row justify-between items-center gap-6 max-w-6xl mx-auto">
  <div class="md:w-2/3">
    <h1 class="text-xl md:text-3xl font-bold">Buy Tickets for <?= htmlspecialchars($event['title']) ?>:<?= $event['id'] ?></h1>
    <p class="mt-2 text-sm text-gray-200">Each user can buy <span class="text-yellow-400 font-bold">10</span> tickets. You can add <span class="text-green-400 font-bold"><?= 10 - array_sum($cart) ?></span> more tickets.</p>
  </div>
  <div class="md:w-1/3">
    <img src="<?= htmlspecialchars($event['image']) ?>" alt="Event Image" class="w-full rounded-lg shadow-md max-h-28 object-cover border border-white">
  </div>
</div>

<!-- Main Section -->
<div class="max-w-6xl mx-auto flex justify-between gap-6 mb-20">
    
  <!-- Ticket List -->
  <div class="bg-white w-1/2 p-4 rounded-b-md space-y-4 shadow" id="checkout-details">
      <?php if (!empty($ticketDetails)): ?>
        <?php foreach ($ticketDetails as $ticket): ?>
          <div class="border rounded p-3">
            <div class="flex justify-between items-center mb-1">
              <h3 class="font-semibold"><?= htmlspecialchars($ticket['name']) ?>
                <span class="bg-gray-200 text-gray-700 text-xs px-2 py-0.5 rounded"><?= $ticket['selected_quantity'] ?> Tickets</span>
              </h3>
              <button class=" bg-white px-2 py-1 rounded-3xl shadow-md" onclick="removeTicket(<?= $event_id ?>, <?= $ticket['id'] ?>)">
                <i class="fa-solid fa-trash" style="color: #e42f2f;"></i>
              </button>

            </div>
            <p class="text-sm text-gray-600">Ticket info</p>
            <p class="text-xs text-gray-500 mt-2">Md Tasmim Al Tahsin</p>
            <p class="text-xs text-gray-500">tahsinniyan@gmail.com | 01715710035</p>
          </div>
        <?php endforeach; ?>
        <!-- <button onclick="location.reload();" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
            Update Cart
    </button> -->
        <!-- <div class="font-bold text-lg pt-2 border-t">SUB TOTAL <span class="float-right">৳<?= number_format($subtotal) ?></span></div> -->
        <!-- <a href="checkout.php?event_id=<?= $event_id ?>" class="block mt-4 bg-[#003C2F] hover:bg-[#00291f] text-white text-center py-2 rounded font-semibold">
          Go to Checkout
        </a>
        <p class="text-xs text-gray-500 mt-2 text-center">Check the details before make payment</p> -->
      <?php else: ?>
        <p class="text-gray-600 text-sm text-center">No tickets selected.</p>
      <?php endif; ?>


    </div>


  <!-- Cart Section -->
  <div class="w-xl" id="cart-details">
    <div class="bg-gray-900 text-white rounded-t-md px-4 py-2 text-center font-semibold text-lg">
      Ticket Details | <span class="bg-white text-black px-2 py-0.5 rounded text-sm">Total: <?= array_sum($cart) ?> Tickets</span>
    </div>
    <div class="bg-white p-4 rounded-b-md space-y-4 shadow">
      <?php if (!empty($ticketDetails)): ?>
        
        <div class="font-bold text-lg pt-2 border-t">SUB TOTAL <span class="float-right"><?= number_format($subtotal) ?></span></div>
        <a href="checkout.php?event_id=<?= $event_id ?>" class="block mt-4 bg-[#003C2F] hover:bg-[#00291f] text-white text-center py-2 rounded font-semibold">
          Proceed To Pay <i class="fa-solid fa-forward animate-pulse mx-2"></i>
        </a>
        <p class="text-xs text-red-500 mt-2 text-center"><i class="fa-solid fa-triangle-exclamation"></i> Tickets are non-refundable or subject to the organizer's decision.</p>
      <?php else: ?>
        <p class="text-gray-600 text-sm text-center">No tickets selected.</p>
        <div class="font-bold text-lg pt-2 border-t">SUB TOTAL <span class="float-right"><?= number_format($subtotal) ?></span></div>
        
        <a href="checkout.php?event_id=<?= $event_id ?>" class="block mt-4 bg-[#003C2F] hover:bg-[#00291f] text-white text-center py-2 rounded font-semibold">
          Proceed To Pay <i class="fa-solid fa-forward animate-pulse mx-2"></i>
        </a>
        
        <p class="text-xs text-red-500 mt-2 text-center"><i class="fa-solid fa-triangle-exclamation"></i> Tickets are non-refundable or subject to the organizer's decision.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include "./footer.php"; ?>
<script>
function addTicket(eventId, ticketId) {
  fetch('checkout_handler.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `event_id=${eventId}&ticket_id=${ticketId}&action=add`
  })
  .then(res => res.text())
  .then(html => {
    document.getElementById('checkout-details').innerHTML = html;
    location.reload();
  });
}

function removeTicket(eventId, ticketId) {
  fetch('checkout_handler.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `event_id=${eventId}&ticket_id=${ticketId}&action=remove`
  })
  .then(res => res.text())
  .then(html => {
    document.getElementById('checkout-details').innerHTML = html;
    location.reload();
  });
}

</script>

</body>
</html>
