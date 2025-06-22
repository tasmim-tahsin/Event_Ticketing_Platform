<?php
include 'DB/database.php';

// Fetch live events from database
$events = mysqli_query($conn, "SELECT id, title, image FROM events WHERE admin_status = 'approved' AND status = 'live' ORDER BY date ASC LIMIT 5");
$event_data = [];
while ($event = mysqli_fetch_assoc($events)) {
    $event_data[] = $event;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Events Carousel</title>
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
            position: relative;
        }
        .slide-content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            color: white;
            padding: 2rem;
        }
        @media (max-width: 768px) {
            .slide-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-12">
        <div class="relative overflow-hidden h-96 rounded-lg shadow-xl">
            <!-- Slides container -->
            <div id="slider" class="flex h-full">
                <?php if (count($event_data) > 0): ?>
                    <?php foreach ($event_data as $index => $event): ?>
                        <div class="slide min-w-full h-full flex-shrink-0 relative" 
                             style="background-image: url('<?php echo htmlspecialchars($event['image']); ?>'); 
                                    background-size: cover; 
                                    background-position: center;">
                            <div class="slide-content">
                                <h3 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($event['title']); ?></h3>
                                <a href="event-details.php?id=<?= $event['id'] ?>"
                                   class="inline-block px-4 py-2 bg-white text-gray-800 rounded hover:bg-gray-100 transition">
                                   View Event
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="slide min-w-full h-full flex-shrink-0 flex items-center justify-center bg-gray-200">
                        <p class="text-xl text-gray-600">No live events currently available</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Navigation arrows -->
            <?php if (count($event_data) > 1): ?>
                <button id="prevBtn" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white/50 text-gray-800 rounded-full w-10 h-10 flex items-center justify-center backdrop-blur-sm">
                    &larr;
                </button>
                <button id="nextBtn" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/30 hover:bg-white/50 text-gray-800 rounded-full w-10 h-10 flex items-center justify-center backdrop-blur-sm">
                    &rarr;
                </button>
                
                <!-- Indicators -->
                <div id="indicators" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2">
                    <?php foreach ($event_data as $index => $event): ?>
                        <button class="w-3 h-3 rounded-full bg-white/50 hover:bg-white/80 transition-colors" 
                                data-index="<?php echo $index; ?>"></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.getElementById('slider');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const indicatorsContainer = document.getElementById('indicators');
            
            // Only initialize carousel if there are multiple slides
            if (document.querySelectorAll('.slide').length > 1) {
                let currentIndex = 0;
                let intervalId;
                const slideInterval = 5000; // 5 seconds
                const slides = document.querySelectorAll('.slide');
                const indicators = document.querySelectorAll('#indicators button');

                // Initialize slider
                function updateSlider() {
                    slider.style.transform = `translateX(-${currentIndex * 100}%)`;
                    
                    // Update active indicator
                    if (indicators) {
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
                if (nextBtn) nextBtn.addEventListener('click', nextSlide);
                if (prevBtn) prevBtn.addEventListener('click', prevSlide);

                // Pause on hover
                slider.addEventListener('mouseenter', () => {
                    clearInterval(intervalId);
                });

                slider.addEventListener('mouseleave', () => {
                    startInterval();
                });

                // Add click events to indicators
                if (indicatorsContainer) {
                    indicatorsContainer.querySelectorAll('button').forEach((indicator, index) => {
                        indicator.addEventListener('click', () => {
                            goToSlide(index);
                        });
                    });
                }

                // Initialize
                updateSlider();
                startInterval();
            }
        });
    </script>
</body>
</html>