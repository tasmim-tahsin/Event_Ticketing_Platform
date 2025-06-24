<?php
include 'DB/database.php';

// Fetch past events from database
$past_events = mysqli_query($conn, "SELECT id, title, image FROM events WHERE status = 'past' ORDER BY date DESC LIMIT 8");
$events = [];
while ($event = mysqli_fetch_assoc($past_events)) {
    $events[] = $event;
}

// Split into two rows (4 events each)
$row1 = array_slice($events, 0, 4);
$row2 = array_slice($events, 4, 4);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Past Events Showcase</title>
    <link href="./output.css" rel="stylesheet">
    <style>
        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
            position: relative;
        }
        .marquee {
            display: inline-block;
            animation: marquee 30s linear infinite;
        }
        .marquee-reverse {
            animation-direction: reverse;
        }
        .marquee:hover {
            animation-play-state: paused;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .event-card {
            display: inline-block;
            margin: 0 15px;
            transition: transform 0.3s ease;
        }
        .event-card:hover {
            transform: scale(1.05);
        }
        .event-image {
            width: 250px;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .event-title {
            margin-top: 8px;
            font-weight: 600;
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body class="bg-gray-50">
    <section class="py-16 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4"><span class="bg-gradient-to-r from-indigo-500 to-purple-600 bg-clip-text text-transparent">Flagship Events</span> in Review</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                We're proud to showcase the success of our previous flagship events, where TicketKing provided exceptional ticketing solutions from start to finish.
            </p>
        </div>

        <!-- First Row (Left to Right) -->
        <div class="marquee-container mb-8">
            <div class="marquee">
                <?php foreach (array_merge($row1, $row1) as $event): ?>
                    <div class="event-card">
                        <img src="<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" class="event-image">
                        <p class="event-title"><?php echo htmlspecialchars($event['title']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Second Row (Right to Left) -->
        <div class="marquee-container">
            <div class="marquee marquee-reverse">
                <?php foreach (array_merge($row2, $row2) as $event): ?>
                    <div class="event-card">
                        <img src="<?php echo htmlspecialchars($event['image']); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" class="event-image">
                        <p class="event-title"><?php echo htmlspecialchars($event['title']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (empty($events)): ?>
            <div class="text-center py-12">
                <p class="text-gray-500">No past events to display yet. Check back later!</p>
            </div>
        <?php endif; ?>
    </section>

    <script>
        // Pause animation when hovering
        document.querySelectorAll('.marquee-container').forEach(container => {
            container.addEventListener('mouseenter', () => {
                container.querySelector('.marquee').style.animationPlayState = 'paused';
            });
            container.addEventListener('mouseleave', () => {
                container.querySelector('.marquee').style.animationPlayState = 'running';
            });
        });

        // Adjust animation duration based on content width
        function adjustMarqueeSpeed() {
            const marquees = document.querySelectorAll('.marquee');
            marquees.forEach(marquee => {
                const width = marquee.scrollWidth / 2;
                const duration = Math.max(50, width / 50); // Base speed on content width
                marquee.style.animationDuration = `${duration}s`;
            });
        }

        // Run on load and resize
        window.addEventListener('load', adjustMarqueeSpeed);
        window.addEventListener('resize', adjustMarqueeSpeed);
    </script>
</body>
</html>