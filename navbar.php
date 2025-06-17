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
        <li><a href="#" class="hover:text-blue-500">Contact</a></li>
        <li><a href="#" class="hover:text-blue-500">About</a></li>
      </ul>
    </div>

    <!-- Right: Cart Icon -->
    <div class="flex items-center space-x-4">
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
      <button class="font-semibold rounded-md bg-blue-600 py-2 px-4 border border-transparent text-center text-sm text-white transition-all shadow-md hover:shadow-lg focus:bg-blue-700 focus:shadow-none active:bg-blue-700 hover:bg-blue-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none ml-2" type="button">
  <a href="./signin.php">Sign In</a>
</button>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div x-show="isOpen" x-transition class="md:hidden px-6 pb-4">
    <ul class="flex flex-col space-y-2 text-gray-700 dark:text-gray-200 font-medium">
      <li><a href="./index.php" class="hover:text-blue-500">Home</a></li>
      <li><a href="./events.php" class="hover:text-blue-500">Events</a></li>
      <li><a href="#" class="hover:text-blue-500">Contact</a></li>
      <li><a href="#" class="hover:text-blue-500">About</a></li>
    </ul>
  </div>
</nav>

<dialog id="my_modal_5" class="modal modal-bottom sm:modal-middle">
  <div class="modal-box">
    <h3 class="text-lg font-bold">Hello!</h3>
    <p class="py-4">Press ESC key or click the button below to close</p>
    <div class="modal-action">
      <form method="dialog">
        <!-- if there is a button in form, it will close the modal -->
        <button class="btn">Close</button>
      </form>
    </div>
  </div>
</dialog>
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