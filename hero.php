<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Event Carousel</title>
  <link href="./output.css" rel="stylesheet">
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    .hero-gradient {
      background: linear-gradient(135deg, rgba(245, 245, 244, 1) 0%, rgba(255, 255, 255, 0) 50%, rgba(191, 219, 254, 0.3) 100%);
    }
    @media (max-width: 1023px) {
      .hero-gradient {
        background: linear-gradient(135deg, rgba(245, 245, 244, 1) 0%, rgba(191, 219, 254, 0.2) 100%);
      }
    }
  </style>
</head>
<body>
  <div class="hero-gradient w-full min-h-screen lg:min-h-[32rem] flex flex-col lg:flex-row px-4 sm:px-6 md:px-8 lg:px-10 xl:px-12">
    <!-- Text Content -->
    <div class="flex items-center justify-center w-full px-4 py-12 sm:py-16 lg:py-8 lg:w-1/2 xl:w-2/5">
      <div class="max-w-md sm:max-w-xl mx-auto text-center lg:text-left">
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-gray-900">
          Buy & Sell your 
          <span class="block w-full bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-transparent sm:inline">
            Event Tickets
          </span> 
          in one place
        </h1>

        <p class="mt-4 sm:mt-6 text-base sm:text-lg text-gray-600">
          Discover the best events in town or sell your extra tickets hassle-free. Your one-stop platform for all event ticketing needs.
        </p>

        <div class="flex flex-col sm:flex-row gap-4 mt-8 sm:mt-10">
          <a href="./events.php" class="px-6 py-3 sm:px-8 sm:py-3 text-sm sm:text-base font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
            Get Started
          </a>
          <a href="./about.php" class="px-6 py-3 sm:px-8 sm:py-3 text-sm sm:text-base font-medium text-gray-900 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2">
            About Us
          </a>
        </div>
      </div>
    </div>

    <!-- Carousel Section -->
    <div class="w-full h-64 sm:h-80 md:h-96 lg:h-auto lg:w-1/2 xl:w-3/5 flex items-center justify-center px-4 pb-12 lg:pb-0">
      <?php include "./carousel.php"; ?>
    </div>
  </div>

  <?php include "./icons.php"; ?>

  <script>
    // Optional: Add intersection observer for scroll animations
    document.addEventListener('DOMContentLoaded', function() {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('animate-fadeInUp');
          }
        });
      }, { threshold: 0.1 });

      document.querySelectorAll('h1, p, .flex a').forEach(el => {
        observer.observe(el);
      });
    });
  </script>
</body>
</html>