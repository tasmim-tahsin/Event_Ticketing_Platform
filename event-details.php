<?php
session_start();
include "./navbar.php";
include "./DB/database.php";
$id = $_GET['id'] ?? 0;

$query = "SELECT * FROM events WHERE id = " . intval($id);
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$organizerId = intval($row['organizer_id']);
$organizerQuery = "SELECT * FROM users WHERE id = $organizerId";
$organizerResult = mysqli_query($conn, $organizerQuery);
$organizer = mysqli_fetch_assoc($organizerResult);


$ticketsQuery = "SELECT * FROM tickets WHERE event_id = " . intval($id);
$ticketsResult = mysqli_query($conn, $ticketsQuery);

$status = strtolower($row['status']); // Assuming $row['status'] is available

// Define button text based on status
$buttonText = 'Get Tickets Now';
$disabled = '';
$extraClass = '';

if ($status === 'past') {
    $buttonText = 'Event Ended';
    $disabled = 'disabled';
} elseif ($status === 'upcoming') {
    $buttonText = 'Tickets Coming Soon';
    $disabled = 'disabled';
} elseif ($status === 'live') {
    $buttonText = 'Get Tickets Now';
    $disabled = ''; // Enable button if needed
} else {
    $buttonText = 'Unavailable';
    $disabled = 'disabled';
}

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
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>
<body class="bg-gray-100">

<div class="max-w-7xl mx-auto p-6 bg-white shadow-md rounded-lg">
  <img src="<?= $row['image'] ?>" alt="<?= $row['title'] ?>" class="w-full h-full object-cover rounded-md mb-6">

  <div class="flex justify-between">
      <h1 class=" text-3xl font-bold mb-2"><?= htmlspecialchars($row['title']) ?></h1>
      <button
  class="rounded-md bg-slate-800 py-2.5 px-5 border border-transparent text-center text-base text-white transition-all shadow-sm hover:shadow-lg focus:bg-slate-700 focus:shadow-none active:bg-slate-700 hover:bg-slate-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
  type="button" <?= $disabled ?>>
  <a href="#tickets"><?= $buttonText ?></a>
</button>
  </div>
  
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div class="flex gap-10">
      <div><i class="fa-solid fa-location-dot"></i> <?= $row['location'] ?></div>
    <div><i class="fa-solid fa-calendar"></i> <?= date('j M, Y', strtotime($row['date'])) ?></div>
    <div><i class="fa-solid fa-clock"></i> <?= date('g:i A', strtotime($row['time'])) ?></div>
    </div>
  </div>
  <div>
    <div class="max-w-7xl space-y-4">

  <!-- Description Section -->
  <div x-data="{ open: true }" class="border-gray-400 rounded-md p-4 shadow-md">
    <button @click="open = !open" class="w-full flex justify-between items-center text-left text-xl font-bold">
      Event Description
      
      <span x-text="open ? '−' : '+'"></span>
    </button>
    <span class="text-xs" x-text="open ? '' : 'Cick to view →'"></span>
    <div x-show="open" x-transition class="mt-2 text-gray-700">
      <?= htmlspecialchars($row['description']) ?>
    </div>
  </div>

  <!-- Privacy Section -->
  <div x-data="{ open: false }" class="border-gray-400 rounded-md p-4 shadow-md">
    <button @click="open = !open" class="w-full flex justify-between items-center text-left text-xl font-bold">
      Privacy
      <span x-text="open ? '−' : '+'"></span>
    </button>
    <span class="text-xs" x-text="open ? '' : 'Cick to view →'"></span>
    <div x-show="open" x-transition class="mt-2 text-gray-700">
      <?= htmlspecialchars($row['privacy']) ?>
    </div>
  </div>

  <!-- Policy Section -->
  <div x-data="{ open: false }" class="border-gray-400 rounded-md p-4 shadow-md">
    <button @click="open = !open" class="w-full flex justify-between items-center text-left text-xl font-bold">
      Policy
      <span x-text="open ? '−' : '+'"></span>
    </button>
    <span class="text-xs" x-text="open ? '' : 'Cick to view →'"></span>
    <div x-show="open" x-transition class="mt-2 text-gray-700">
      <?= htmlspecialchars($row['policy']) ?>
    </div>
  </div>

</div>
    


<!-- Tickets-->

<div class="my-10" id="tickets">
  <h2 class="text-xl font-bold mb-4">Ticket details & price</h2>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php while($ticket = mysqli_fetch_assoc($ticketsResult)): 
      $isSoldOut = $ticket['quantity'] <= 0;
    ?>
      <div class="border rounded-lg p-4 shadow-sm hover:shadow-md transition 
                  <?= $isSoldOut ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200' ?>">
        
        <div class="text-lg font-semibold rounded-md p-2 mb-2 text-center 
                    <?= $isSoldOut ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' ?>">
          <?= htmlspecialchars($ticket['name']) ?>
        </div>

        <div class="text-xl font-bold mb-2 <?= $isSoldOut ? 'text-red-700' : 'text-green-700' ?>">
          ৳<?= number_format($ticket['price'], 0) ?>
        </div>

        <?php if (!empty($ticket['benefits'])): ?>
          <p class="text-sm text-gray-700 mb-2"><?= nl2br(htmlspecialchars($ticket['benefits'])) ?></p>
        <?php endif; ?>

        <div>
          <?php if ($isSoldOut): ?>
            <button class="mt-auto bg-white text-black font-semibold py-1.5 px-4 rounded cursor-not-allowed" disabled>
              Sold Out!
            </button>
          <?php else: ?>
            <button class="mt-auto bg-slate-800 text-white py-1.5 px-4 rounded hover:bg-slate-700 transition">
              <a href="addticket.php?event_id=<?= $row['id'] ?>&ticket_id=<?= $ticket['id'] ?>" >Buy Now</a>
            </button>
          <?php endif; ?>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<?php if ($organizer): ?>
<div class="mb-10">
  <h2 class="text-xl font-bold mb-4">Event Organizer</h2>
  <div class="bg-white rounded-lg shadow-md p-6 flex items-center gap-6">
    <div class="w-16 h-16 overflow-hidden rounded-md bg-gray-100 flex items-center justify-center">
      <?php if (!empty($organizer['profile_image'])): ?>
        <img src="<?= htmlspecialchars($organizer['profile_image']) ?>" alt="Organizer Image" class="object-cover w-full h-full">
      <?php else: ?>
        <i class="fa-solid fa-user text-2xl text-gray-500"></i>
      <?php endif; ?>
    </div>
    
    <div class="flex-1">
      <h3 class="text-lg font-semibold"><?= htmlspecialchars($organizer['name']) ?></h3>
      <p class="text-sm text-gray-500 mb-2">Event Organizer</p>
      
      <div class="flex gap-4 text-lg text-gray-700">
        <?php if (!empty($organizer['facebook'])): ?>
          <a href="<?= htmlspecialchars($organizer['facebook']) ?>" target="_blank"><i class="fab fa-facebook"></i></a>
        <?php endif; ?>
        <?php if (!empty($organizer['instagram'])): ?>
          <a href="<?= htmlspecialchars($organizer['instagram']) ?>" target="_blank"><i class="fab fa-instagram"></i></a>
        <?php endif; ?>
        <?php if (!empty($organizer['email'])): ?>
          <a href="mailto:<?= htmlspecialchars($organizer['email']) ?>"><i class="fas fa-envelope mr-2"></i><?= htmlspecialchars($organizer['email']) ?></a>
        <?php endif; ?>
        <?php if (!empty($organizer['phone'])): ?>
          <a href="tel:<?= htmlspecialchars($organizer['phone']) ?>"><i class="fas fa-phone mr-2"></i><?= htmlspecialchars($organizer['phone']) ?></a>
        <?php endif; ?>
      </div>

      <?php if (!empty($organizer['address'])): ?>
        <div class="mt-2 text-sm text-gray-600"><i class="fas fa-map-marker-alt mr-1"></i><?= htmlspecialchars($organizer['address']) ?></div>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php endif; ?>


<div class="flex flex-col md:flex-row gap-10 justify-between mb-10">
        <div><span class="text-xl font-bold">
          Payment Method
        </span>
        <hr>
        <img style="width:500px" class="" src="./images/all-pgw.jpg" alt="" srcset="">
        <?= $row['payment_method'] ?>
        </div>

        <div>
          <div class="">
    <!-- Social Sharing -->
    <p class="text-xl font-bold">Share</p>
    <hr>
    <div class="flex gap-3 mt-2 text-white">
      <a href="#" class="text-white"><button class="btn bg-black text-white"><i class="fa-solid fa-share-from-square"></i></i>Facebook</button></a>
      <a href="#" class="text-white"><button class="btn bg-black text-white"><i class="fa-solid fa-copy"></i>Copy Link</button></a></a>
      <a href="#" class="text-white"><button class="btn bg-black text-white"><i class="fa-solid fa-calendar"></i>Add to Calander</button></a></a>
    </div>
  </div>
        </div>
    </div>
</div>
  
</div>
<?php
    include "./footer.php";
?>
<script src="https://kit.fontawesome.com/c233ed958f.js" crossorigin="anonymous"></script>
</body>
</html>
