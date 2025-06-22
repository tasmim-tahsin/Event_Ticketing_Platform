<?php
session_start();

$event_id = intval($_POST['event_id'] ?? 0);
$ticket_id = intval($_POST['ticket_id'] ?? 0);
$action = $_POST['action'] ?? '';

if (!isset($_SESSION['event_cart'])) {
    $_SESSION['event_cart'] = [];
}
if (!isset($_SESSION['event_cart'][$event_id])) {
    $_SESSION['event_cart'][$event_id] = [];
}

if ($action === 'add') {
    $current_qty = array_sum($_SESSION['event_cart'][$event_id]);
    if ($current_qty < 10) {
        if (!isset($_SESSION['event_cart'][$event_id][$ticket_id])) {
            $_SESSION['event_cart'][$event_id][$ticket_id] = 1;
        } else {
            $_SESSION['event_cart'][$event_id][$ticket_id]++;
        }
    }
}

if ($action === 'remove') {
    unset($_SESSION['event_cart'][$event_id][$ticket_id]);
}

// Return updated HTML
include "./DB/database.php";

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

<!-- Return this as updated cart -->
<div>
  <!-- <div class="bg-gray-900 text-white rounded-t-md px-4 py-2 text-center font-semibold text-lg">
    Ticket Details | <span class="bg-white text-black px-2 py-0.5 rounded text-sm">Total: <?= array_sum($cart) ?> Tickets</span>
  </div> -->
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
      <!-- <div class="font-bold text-lg pt-2 border-t">SUB TOTAL <span class="float-right">৳<?= number_format($subtotal) ?></span></div>
      <a href="checkout.php?event_id=<?= $event_id ?>" class="block mt-4 bg-[#003C2F] hover:bg-[#00291f] text-white text-center py-2 rounded font-semibold">
        Go to Checkout
      </a>
      <p class="text-xs text-gray-500 mt-2 text-center">Check the details before make payment</p> -->
    <?php else: ?>
      <p class="text-gray-600 text-sm text-center">No tickets selected.</p>
    <?php endif; ?>
  </div>
</div>
<?php
// This would be part of the add/remove logic in cart_handler.php

// // After adding/removing items, we can send a response to update the cart count
// echo json_encode(['success' => true]);
// ?>
<script>
  function updateCartCount() {
    fetch('cart_count.php')
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById("cart-badge");
            badge.textContent = data.total_items > 0 ? data.total_items : '';
        })
        .catch(error => console.error("Error fetching cart count:", error));
}

</script>