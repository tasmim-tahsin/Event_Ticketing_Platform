<?php
include './DB/database.php';
if(isset($_SESSION['user'])){
  $userEmail = $_SESSION['user']['email'];
  $query = mysqli_query($conn, "SELECT * FROM users WHERE email = '$userEmail'");
  $user = mysqli_fetch_assoc($query);
}

?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="./output.css" rel="stylesheet">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
<nav x-data="{ isOpen: false }" class="relative bg-white shadow dark:bg-gray-800">
  <div class="container mx-auto px-6 py-4 flex items-center justify-between">
    <!-- Mobile menu button -->
      <button @click="isOpen = !isOpen" class="md:hidden text-gray-600 dark:text-gray-200 focus:outline-none">
        <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
          viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" />
        </svg>
        <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
          viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    <!-- Left: Logo -->
    <div class="flex-shrink-0">
      <a href="./index.php">
        <img class="w-auto h-10 sm:h-10" src="./images/logo.png" alt="Logo">
      </a>
    </div>

    <!-- Center: Navigation Links -->
    <div class="hidden md:flex justify-center flex-1">
      <ul class="flex space-x-6 text-gray-700 dark:text-gray-200 font-medium">
        <li><a href="./index.php" class="hover:text-blue-500">Home</a></li>
        <li><a href="./events.php" class="hover:text-blue-500">Events</a></li>
        <li><a href="./contact_us.php" class="hover:text-blue-500">Contact Us</a></li>
        <li><a href="./about.php" class="hover:text-blue-500">About</a></li>
      </ul>
    </div>

    <!-- Right: Cart Icon -->
    <div class="flex items-center space-x-4">
      
      

      <!-- Cart Icon -->
      <!-- <button for="my_modal_7" onclick="my_modal_5.showModal()"> -->
        
        <a href="./cart.php" class="relative text-gray-700 dark:text-gray-200 hover:text-gray-600">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
        stroke-linejoin="round" viewBox="0 0 24 24">
        <path
            d="M3 3H5L6 13H17L21 5H6M6 13L4.5 16C4 17 5 18 6 18H17M17 18C16 18 15 19 15 20C15 21 16 22 17 22C18 22 19 21 19 20C19 19 18 18 17 18ZM7 20C7 21 6 22 5 22C4 22 3 21 3 20C3 19 4 18 5 18C6 18 7 19 7 20Z">
        </path>
    </svg>
    <!-- Dynamic Badge -->
    <span id="cart-badge" class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">0</span>
</a>

      <!-- </button> -->
      
<!-- Right: Cart Icon + User Dropdown -->
<div class="flex items-center space-x-4">
  <!-- Cart Icon -->

  <?php if (isset($_SESSION['user'])): ?>
    <!-- User Dropdown -->
    <div class="relative" x-data="{ dropdownOpen: false }">
      <button @click="dropdownOpen = !dropdownOpen" class="flex items-center space-x-2 border border-gray-200 rounded-full px-2 py-1 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
        <img class="w-8 h-8 rounded-full object-cover" src="<?= !empty($user['profile_image']) ? $user['profile_image'] : './images/default-avatar.png' ?>" alt="User">
        <span class="text-sm font-medium text-gray-700 dark:text-gray-200"><?= htmlspecialchars($_SESSION['user']['name']) ?></span>
        <svg class="w-4 h-4 text-gray-600 dark:text-gray-300" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414L10 13.414 5.293 8.707a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
      </button>

      <!-- Dropdown -->
      <div x-show="dropdownOpen" @click.away="dropdownOpen = false" class="absolute right-0 w-44 mt-2 bg-white dark:bg-gray-700 rounded-md shadow-lg z-50 overflow-hidden">
        <a href="./profile.php" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M5.121 17.804A12.042 12.042 0 0112 15c2.294 0 4.415.65 6.253 1.765M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
          </svg>
          Profile
        </a>

        <?php if ($_SESSION['user']['role'] === 'organizer'): ?>
        <a href="./dashboard.php" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M3 12l2-2m0 0l7-7 7 7M13 5v6a1 1 0 001 1h6" />
          </svg>
          Dashboard
        </a>
        <?php endif; ?>

        <a href="./logout.php" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-red-400">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H5a3 3 0 01-3-3V7a3 3 0 013-3h5a3 3 0 013 3v1" />
          </svg>
          Logout
        </a>
      </div>
    </div>
  <?php else: ?>
    <!-- Sign In Button -->
    <button class="font-semibold rounded-md bg-blue-600 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-blue-700 hover:bg-blue-700 ml-2">
      <a href="./signin.php">Sign In</a>
    </button>
  <?php endif; ?>
</div>

    </div>
  </div>

  <!-- Mobile Menu -->
  <div x-show="isOpen" x-transition class="md:hidden px-6 pb-4">
    <ul class="flex flex-col space-y-2 text-gray-700 dark:text-gray-200 font-medium">
      <li><a href="./index.php" class="hover:text-blue-500">Home</a></li>
      <li><a href="./events.php" class="hover:text-blue-500">Events</a></li>
      <li><a href="./contact_us.php" class="hover:text-blue-500">Contact</a></li>
      <li><a href="./about.php" class="hover:text-blue-500">About</a></li>
    </ul>
  </div>
</nav>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Function to fetch the cart count
    function updateCartCount() {
        fetch('cart_count.php')
            .then(response => response.json())
            .then(data => {
                // Update the cart badge with the total items count
                const badge = document.getElementById("cart-badge");
                badge.textContent = data.total_items > 0 ? data.total_items : '0'; // Don't show the badge if empty
            })
            .catch(error => console.error("Error fetching cart count:", error));
    }

    // Update the cart count when the page loads
    updateCartCount();

    // If you have buttons that change the cart (add/remove), you can call updateCartCount() after those actions too.
});
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
</body>
</html>