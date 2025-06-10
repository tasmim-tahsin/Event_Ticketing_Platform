<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="./output.css" rel="stylesheet">
</head>
<body>

    <div class="relative overflow-hidden max-w-screen-xl mx-auto mt-10">
  <!-- Left Button -->
  <!-- <button id="prevBtn" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-2 rounded-full">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
  </button> -->

  <!-- Carousel -->
  <div id="carousel" class="flex transition-transform duration-500 ease-in-out">
    <!-- Carousel Items (16 total) -->
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/ios/50/podium-without-speaker.png" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Competitions</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/?size=50&id=MWDPiwpILb1M&format=png&color=000000" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Fashion Shows</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/ios/50/audience.png" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Conferences</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/ios/50/training.png" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Seminars</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/?size=50&id=22118&format=png&color=000000" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Reunions</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/ios/50/exhibition.png" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Exhibitions</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/ios/50/scissors.png" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Launching</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/ios/50/microphone.png" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Stand-up</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/ios/50/drama.png" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Drama</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/ios/50/disco-ball.png" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Party</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/?size=50&id=gUBQbY8bvlrE&format=png&color=000000" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Pop Culture</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/ios/50/microphone--v1.png" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Concert</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/?size=50&id=192&format=png&color=000000" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Sports</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/?size=50&id=3656&format=png&color=000000" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Workshops</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/ios/50/donation.png" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Fundraisers</p>
    </div>
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/ios/50/confetti.png" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Festivals</p>
    </div>

    <!-- Cloned Item for seamless loop -->
    <div class="w-[10%] flex-shrink-0 text-center px-2">
      <img src="https://img.icons8.com/ios/50/podium-without-speaker.png" class="mx-auto mb-2 h-10" />
      <p class="text-sm text-gray-700">Competitions</p>
    </div>
  </div>

  <!-- Right Button -->
  <!-- <button id="nextBtn" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow p-2 rounded-full">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
    </svg>
  </button> -->
</div>

<script>
  const carousel = document.getElementById("carousel");
  const itemWidthPercent = 10;
  const totalItems = carousel.children.length;
  let index = 0;

  // Autoplay every 2.5 seconds
  setInterval(() => {
    nextSlide();
  }, 2500);

  function nextSlide() {
    index++;
    if (index > totalItems - 10) {
      carousel.style.transition = "none";
      carousel.style.transform = `translateX(0%)`;
      index = 1;
      void carousel.offsetWidth;
      carousel.style.transition = "transform 0.5s ease-in-out";
    }
    carousel.style.transform = `translateX(-${index * itemWidthPercent}%)`;
  }

  function prevSlide() {
    index--;
    if (index < 0) {
      index = totalItems - 11;
      carousel.style.transition = "none";
      carousel.style.transform = `translateX(-${index * itemWidthPercent}%)`;
      void carousel.offsetWidth;
      carousel.style.transition = "transform 0.5s ease-in-out";
    } else {
      carousel.style.transition = "transform 0.5s ease-in-out";
      carousel.style.transform = `translateX(-${index * itemWidthPercent}%)`;
    }
  }

  document.getElementById("nextBtn").addEventListener("click", nextSlide);
  document.getElementById("prevBtn").addEventListener("click", prevSlide);
</script>

    <section class="px-4 py-24 mx-auto max-w-7xl text-center">
    
    <h1
      class="block antialiased tracking-normal font-sans font-semibold text-blue-gray-900 my-4 !text-2xl !leading-snug lg:!text-3xl"
    >
      Trusted by Leading Brands
    </h1>
    <p
      class="block antialiased font-sans text-xl font-normal leading-relaxed text-inherit mx-auto max-w-5xl !text-gray-500 lg:px-8 mb-10"
    >
      From innovative startups to Fortune 500 companies, our client list spans a
      spectrum of sectors, each with unique challenges that we've successfully
      navigated.
    </p>
  <div class="grid grid-cols-2 gap-10 text-center lg:grid-cols-8">
    <div class="flex items-center justify-center">
      <img src="./images/todoist.svg" alt="Todoist Logo" class="block object-contain h-12" />
    </div>
    <div class="flex items-center justify-center">
      <img src="./images/slack-icon.svg" alt="Slack Logo" class="block object-contain h-12" />
    </div>
    <div class="flex items-center justify-center">
      <img src="./images/typeform.svg" alt="Typeform Logo" class="block object-contain h-12" />
    </div>
    <div class="flex items-center justify-center">
      <img src="./images/algolia.svg" alt="Algolia Logo" class="block object-contain h-12" />
    </div>
    <div class="flex items-center justify-center">
      <img src="./images/postcss.svg" alt="Postcss Logo" class="block object-contain h-12" />
    </div>
    <div class="flex items-center justify-center">
      <img src="./images/tailwindcss.svg" alt="TailwindCSS Logo" class="block object-contain h-12" />
    </div>
    <div class="flex items-center justify-center">
      <img src="./images/discord.svg" alt="Discord Logo" class="block object-contain h-12" />
    </div>
    <div class="flex items-center justify-center">
      <img src="./images/vimeo.svg" alt="Vimeo Logo" class="block object-contain h-12" />
    </div>
  </div>
</section>

</body>
</html>