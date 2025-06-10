
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tailwind CSS Carousel</title>
    <link href="./output.css" rel="stylesheet">
    <style>
         .slider-container {
            overflow: hidden;
        }
        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }
        .slide {
            flex: 0 0 100%;
        }
    </style>
</head>
    <body class="bg-gray-100">
    <div class="container mx-auto px-4 py-12">
        
        <div class="relative overflow-hidden h-96 rounded-lg shadow-xl">
            <!-- Slides container -->
            <div id="slider" class="flex h-full">
                <!-- Slides will be inserted here by JavaScript -->
            </div>
            
            <!-- Navigation arrows -->
            <button id="prevBtn" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white/50 text-gray-800 rounded-full w-10 h-10 flex items-center justify-center backdrop-blur-sm">
                &larr;
            </button>
            <button id="nextBtn" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white/50 text-gray-800 rounded-full w-10 h-10 flex items-center justify-center backdrop-blur-sm">
                &rarr;
            </button>
            
            <!-- Indicators -->
            <div id="indicators" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                <!-- Indicators will be inserted here by JavaScript -->
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Image data - replace with your own images
    const images = [
        {
            url: './images/event1.png',
            alt: 'Nature 1'
        },
        {
            url: './images/event2.png',
            alt: 'Nature 2'
        },
        {
            url: './images/event4.jpg',
            alt: 'Nature 3'
        },
        {
            url: './images/event5.jpeg',
            alt: 'Nature 4'
        }
    ];

    const slider = document.getElementById('slider');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const indicatorsContainer = document.getElementById('indicators');
    
    let currentIndex = 0;
    let intervalId;
    const slideInterval = 3000; // 2 seconds

    // Create slides
    images.forEach((image, index) => {
        // Create slide
        const slide = document.createElement('div');
        slide.className = 'slide min-w-full h-full flex-shrink-0';
        slide.style.backgroundImage = `url(${image.url})`;
        slide.style.backgroundSize = 'auto';
        slide.style.backgroundPosition = 'center';
        slide.setAttribute('data-index', index);
        slider.appendChild(slide);
        
        // Create indicator
        const indicator = document.createElement('button');
        indicator.className = 'w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-colors';
        indicator.setAttribute('data-index', index);
        indicatorsContainer.appendChild(indicator);
        
        // Add click event to indicator
        indicator.addEventListener('click', () => {
            goToSlide(index);
        });
    });

    const slides = document.querySelectorAll('.slide');
    const indicators = document.querySelectorAll('#indicators button');

    // Initialize slider
    function updateSlider() {
        slider.style.transform = `translateX(-${currentIndex * 100}%)`;
        
        // Update active indicator
        indicators.forEach((indicator, index) => {
            if (index === currentIndex) {
                indicator.classList.add('bg-white');
                indicator.classList.remove('bg-white/50');
            } else {
                indicator.classList.remove('bg-white');
                indicator.classList.add('bg-white/50');
            }
        });
    }

    // Go to specific slide
    function goToSlide(index) {
        currentIndex = index;
        if (currentIndex >= slides.length) {
            currentIndex = 0;
        } else if (currentIndex < 0) {
            currentIndex = slides.length - 1;
        }
        updateSlider();
        resetInterval();
    }

    // Next slide
    function nextSlide() {
        goToSlide(currentIndex + 1);
    }

    // Previous slide
    function prevSlide() {
        goToSlide(currentIndex - 1);
    }

    // Auto-play functionality
    function startInterval() {
        intervalId = setInterval(nextSlide, slideInterval);
    }

    function resetInterval() {
        clearInterval(intervalId);
        startInterval();
    }

    // Event listeners
    nextBtn.addEventListener('click', () => {
        nextSlide();
    });

    prevBtn.addEventListener('click', () => {
        prevSlide();
    });

    // Pause on hover
    slider.addEventListener('mouseenter', () => {
        clearInterval(intervalId);
    });

    slider.addEventListener('mouseleave', () => {
        startInterval();
    });

    // Initialize
    updateSlider();
    startInterval();
});
    </script>
</body>
</html>