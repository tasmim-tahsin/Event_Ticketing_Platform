<?php
session_start();
include 'auth.php';
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
$tickets = mysqli_fetch_assoc($ticketsResult);
$tqty = $tickets['quantity'];
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
<nav class="sticky top-0 z-50">
        <?php
            include "./navbar.php";
        ?>
    </nav>

<!-- Event Top Header -->
<div class="bg-gradient-to-r from-indigo-700 to-purple-800 text-white p-6 mb-8 rounded-md flex flex-col md:flex-row justify-between items-center gap-6 max-w-6xl mx-auto">
  <div class="md:w-2/3">
    <h1 class="text-2xl md:text-3xl font-bold">Buy Tickets for <?= htmlspecialchars($event['title']) ?></h1>
    <p class="mt-2 text-sm text-gray-200">Each user can buy <span class="text-yellow-400 font-bold">10</span> tickets. You can add <span class="text-green-400 font-bold"><?= 10 - array_sum($cart) ?></span> more tickets.</p>
    <p class="mt-2 text-sm text-gray-200">Available Tickets <span class="text-green-400 font-bold"><?php echo"$tqty" ?></span></p>
  </div>
  <div class="md:w-1/3">
    <img src="<?= htmlspecialchars($event['image']) ?>" alt="Event Image" class="w-full rounded-lg shadow-md max-h-28 object-cover border border-white">
  </div>
</div>

<!-- Main Section -->
<div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 mb-20">
  <!-- Ticket List -->
  <div class="md:col-span-2 space-y-4">
    <?php while ($ticket = mysqli_fetch_assoc($ticketsResult)): ?>
      <?php $soldOut = $ticket['quantity'] <= 0; ?>
      <div class="border p-4 rounded-md <?= $soldOut ? 'bg-red-100' : 'bg-gray-50' ?>">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold"><?= htmlspecialchars($ticket['name']) ?></h2>
          <span class="text-black font-bold bg-green-100 px-2 py-1 rounded text-lg">৳<?= number_format($ticket['price']) ?></span>
        </div>
        <?php if (!empty($ticket['benefits'])): ?>
          <div class="text-sm text-gray-600 mt-2"><?= nl2br(htmlspecialchars($ticket['benefits'])) ?></div>
        <?php endif; ?>
        <?php if ($soldOut): ?>
          <button class="mt-4 bg-red-600 text-white w-full py-2 rounded cursor-not-allowed" disabled>Sold Out</button>
        <?php else: ?>
          <button onclick="addTicket(<?= $event_id ?>, <?= $ticket['id'] ?>)" class="mt-4 bg-gray-900 text-white w-full py-2 rounded block text-center hover:bg-gray-800 transition">
              + Add Ticket
          </button>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </div>

  <!-- Cart Section -->
  <div id="cart-details">
    <div class="bg-gray-900 text-white rounded-t-md px-4 py-2 text-center font-semibold text-lg">
      Ticket Details | <span class="bg-white text-black px-2 py-0.5 rounded text-sm">Total: <?= array_sum($cart) ?> Tickets</span>
    </div>
    <div class="bg-white p-4 rounded-b-md space-y-4 shadow">
      <?php if (!empty($ticketDetails)): ?>
        <?php foreach ($ticketDetails as $ticket): ?>
          <div class="border rounded p-3">
            <div class="flex justify-between items-center mb-1">
              <h3 class="font-semibold"><?= htmlspecialchars($ticket['name']) ?>
                <span class="bg-gray-200 text-gray-700 text-xs px-2 py-0.5 rounded"><?= $ticket['selected_quantity'] ?> Tickets</span>
              </h3>

              <button onclick="removeTicket(<?= $event_id ?>, <?= $ticket['id'] ?>)">
                <i class="fa-solid fa-trash" style="color: #e42f2f;"></i>
              </button>

            </div>
            <p class="text-sm text-gray-600">Ticket info</p>
            <p class="text-xs text-gray-500 mt-2"><?= htmlspecialchars($_SESSION['user']['name']) ?></p>
            <p class="text-xs text-gray-500"><?= htmlspecialchars($_SESSION['user']['email']) ?> | <?= htmlspecialchars($_SESSION['user']['phone']) ?></p>
          </div>
        <?php endforeach; ?>
        <div class="font-bold text-lg pt-2 border-t">SUB TOTAL <span class="float-right">৳<?= number_format($subtotal) ?></span></div>
        <a href="checkout.php?event_id=<?= $event_id ?>" class="block mt-4 bg-[#003C2F] hover:bg-[#00291f] text-white text-center py-2 rounded font-semibold">
          Go to Checkout
        </a>
        <p class="text-xs text-gray-500 mt-2 text-center">Check the details before make payment</p>
      <?php else: ?>
        <p class="text-gray-600 text-sm text-center">No tickets selected.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include "./footer.php"; ?>
<script>
function addTicket(eventId, ticketId) {
  fetch('cart_handler.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `event_id=${eventId}&ticket_id=${ticketId}&action=add`
  })
  .then(res => res.text())
  .then(html => {
    document.getElementById('cart-details').innerHTML = html;
  });
}

function removeTicket(eventId, ticketId) {
  fetch('cart_handler.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `event_id=${eventId}&ticket_id=${ticketId}&action=remove`
  })
  .then(res => res.text())
  .then(html => {
    document.getElementById('cart-details').innerHTML = html;
  });
}
</script>

</body>
</html>
