<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Event Carousel</title>
      <link href="./output.css" rel="stylesheet">
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
    <div class="lg:flex mx-10">
        <div class="flex items-center justify-center w-full px-6 py-8 lg:h-[32rem] lg:w-1/3">
            <div class="max-w-xl">
                <h1 class="mb-6 text-3xl font-extrabold leading-none tracking-normal text-gray-900 md:text-5xl md:tracking-tight">
      Buy & Sale your <span class="block w-full bg-gradient-to-r from-indigo-400 to-cyan-400 bg-clip-text text-transparent lg:inline">Event Tickets</span> in one single place
    </h1>

                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400 lg:text-base">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis commodi cum cupiditate ducimus, fugit harum id necessitatibus odio quam quasi, quibusdam rem tempora voluptates.</p>

                <div class="flex flex-col mt-6 space-y-3 lg:space-y-0 lg:flex-row">
                    <a href="#" class="block px-5 py-2 text-sm font-medium tracking-wider text-center text-white transition-colors duration-300 transform bg-gray-900 rounded-md hover:bg-gray-700">Get Started</a>
                    <a href="#" class="block px-5 py-2 text-sm font-medium tracking-wider text-center text-gray-700 transition-colors duration-300 transform bg-gray-200 rounded-md lg:mx-4 hover:bg-gray-300">See Events</a>
                </div>
            </div>
        </div>

        <div class="w-full h-64 lg:w-2/3 lg:h-auto">
           <?php
            include "./carousel.php";
           ?>

        </div>
    </div>
</body>
</html>
