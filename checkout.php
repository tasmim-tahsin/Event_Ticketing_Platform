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

// Cart details
$cart = $_SESSION['event_cart'][$event_id];
$ticketDetails = [];
$subtotal = 0;

if (!empty($cart)) {
    $ticket_ids = implode(",", array_keys($cart));
    $ticketQuery = mysqli_query($conn, "SELECT * FROM tickets WHERE id IN ($ticket_ids)");
    while ($ticket = mysqli_fetch_assoc($ticketQuery)) {
        $id = $ticket['id'];
        $tqty=$ticket['quantity'];
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
<div class="bg-gradient-to-r from-indigo-700 to-purple-800 text-white p-2 mb-8 rounded-md flex flex-col md:flex-row justify-between items-center gap-6 max-w-6xl mx-auto">
  <div class="md:w-2/3">
    <h1 class="text-xl md:text-3xl font-bold">Buy Tickets for <?= htmlspecialchars($event['title']) ?></h1>
    <p class="mt-2 text-sm text-gray-200">Each user can buy <span class="text-yellow-400 font-bold">10</span> tickets. You can add <span class="text-green-400 font-bold"><?= 10 - array_sum($cart) ?></span> more tickets.</p>
    <p class="mt-2 text-sm text-gray-200">Available Tickets <span class="text-green-400 font-bold"><?php echo"$tqty" ?></span></p>
  </div>
  <div class="md:w-1/3">
    <img src="<?= htmlspecialchars($event['image']) ?>" alt="Event Image" class="w-full rounded-lg shadow-md max-h-28 object-cover border border-white">
  </div>
</div>

<!-- Main Section -->
<div class="max-w-6xl mx-auto flex flex-col lg:flex-row justify-between gap-6 mb-20">
    
  <!-- Ticket List -->
  <div class="bg-white lg:w-1/2 p-4 rounded-b-md space-y-4 shadow" id="checkout-details">
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
            <p class="text-xs text-gray-500 mt-2"><?= htmlspecialchars($_SESSION['user']['name']) ?></p>
            <p class="text-xs text-gray-500"><?= htmlspecialchars($_SESSION['user']['email']) ?> | <?= htmlspecialchars($_SESSION['user']['phone']) ?></p>
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
  <div class="" id="cart-details">
    <div class="bg-white rounded-lg shadow-md sticky top-4">
      <div class="bg-gray-900 text-white px-4 py-3 rounded-t-lg">
        <h2 class="text-lg font-semibold flex items-center">
          <i class="fas fa-receipt mr-2"></i> Order Summary
        </h2>
      </div>
      
      <div class="p-4">
        <?php if (!empty($ticketDetails)): ?>
          <!-- Order Items -->
          <div class="space-y-3 mb-4">
            <?php foreach ($ticketDetails as $ticket): ?>
              <div class="flex justify-between text-sm">
                <span><?= $ticket['selected_quantity'] ?> × <?= htmlspecialchars($ticket['name']) ?></span>
                <span>৳<?= number_format($ticket['total_price']) ?></span>
              </div>
            <?php endforeach; ?>
          </div>
          <form action="./purchase.php" method="post">
          <input type="hidden" name="event_id" value="<?= $event_id ?>">
          <!-- Subtotal -->
          <div class="border-t pt-3 mb-4">
            <div class="flex justify-between font-semibold">
              <span>Subtotal</span>
              <span>৳<?= number_format($subtotal) ?></span>
            </div>
            <div class="flex justify-between text-sm text-gray-500 mt-1">
              <span>Service Fee</span>
              <span>৳0</span>
            </div>
          </div>
          
          <!-- Total -->
          <div class="border-t pt-3 mb-6">
            <div class="flex justify-between font-bold text-lg">
              <span>Total</span>
              <span>৳<?= number_format($subtotal) ?></span>
            </div>
          </div>
          
          <!-- Payment Methods -->
          <div class="mb-6">
            <h3 class="font-semibold mb-3 flex items-center">
              <i class="fas fa-credit-card mr-2"></i> Payment Method
            </h3>
            <div class="space-y-2">
              <label class="payment-method block">
                <input type="radio" name="payment_method" value="bkash" class="mr-2" checked>
                <img src="./images/bkash.png" alt="bKash" class="h-10 inline mr-2">
                bKash
              </label>
              <label class="payment-method block">
                <input type="radio" name="payment_method" value="nagad" class="mr-2">
                <img src="./images/nagad.png" alt="Nagad" class="h-10 inline mr-2">
                Nagad
              </label>
              <label class="payment-method block">
                <input type="radio" name="payment_method" value="card" class="mr-2">
                <!-- <i class="fab fa-cc-visa text-blue-500 mr-2"></i>
                <i class="fab fa-cc-mastercard text-red-500 mr-2"></i> -->
                <img src="./images/card.png" alt="card" class="h-10 inline mr-2">
                Credit/Debit Card
              </label>
            </div>
          </div>
          
          <!-- Terms and Conditions -->
          <div class="mb-6">
            <div class="flex items-start">
              <input type="checkbox" id="terms" name="terms" class="mt-1 mr-2" required>
              <label for="terms" class="text-sm text-gray-600">
                I agree to the <a href="#" class="text-[#003C2F] hover:underline">Terms & Conditions</a>, 
                <a href="#" class="text-[#003C2F] hover:underline">Privacy Policy</a>, and 
                <a href="#" class="text-[#003C2F] hover:underline">Refund Policy</a>.
              </label>
            </div>
          </div>
          
          <!-- Checkout Button -->
          
            <input type="hidden" name="event_id" value="<?= $event_id ?>">
            <button type="submit" name="proceed_to_pay" class="block w-full bg-[#003C2F] hover:bg-[#00291f] text-white text-center py-2 rounded font-semibold mt-2">
        Proceed To Pay <i class="fa-solid fa-forward animate-pulse mx-2"></i>
        </button>
          </form>
          
          <!-- Security Info -->
          <div class="mt-3 text-xs text-gray-500 text-center">
            <i class="fas fa-shield-alt text-green-500 mr-1"></i> Secure SSL encrypted payment
          </div>
          
          <!-- Refund Policy -->
          <div class="mt-4 p-3 bg-red-50 rounded-lg border border-red-100">
            <div class="flex items-start">
              <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-2"></i>
              <div class="text-sm text-red-600">
                <strong>Refund Policy:</strong> Tickets are non-refundable except in case of event cancellation. 
                All sales are final.
              </div>
            </div>
          </div>
        <?php else: ?>
          <div class="text-center py-8 text-gray-500">
            <i class="fas fa-shopping-cart fa-2x mb-3"></i>
            <p>Your cart is empty</p>
          </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['purchase_status'])): ?>
    <div class="bg-green-100 text-green-800 p-2 text-center font-semibold rounded">
        <?= $_SESSION['purchase_status'] ?>
    </div>
    <?php unset($_SESSION['purchase_status']); ?>
<?php endif; ?>
      </div>
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
