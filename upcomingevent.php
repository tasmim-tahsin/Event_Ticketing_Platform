<?php
    include "./DB/database.php";

    // Fetch all live or upcoming events
    $query = "SELECT * FROM events WHERE status = 'live'";
    $result = mysqli_query($conn, $query);

    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = $row;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Events</title>
    <link href="./output.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="text-center my-10">
        <h1 class="text-6xl font-bold">Explore Upcomings!</h1>
        <h3 class="text-2xl mt-3 text-gray-600">Explore the Universe of Events at Your Fingertips.</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 p-6" id="eventContainer">
        <?php foreach ($events as $index => $row): ?>
            <div class="max-w-min bg-white rounded-xl shadow-md overflow-hidden <?= $index >= 9 ? 'hidden more-events' : '' ?>">
                <div class="relative">
                    <?php if (strtolower($row['status']) === 'live'): ?>
                        <span class="absolute top-2 right-2 bg-white text- text-black text-xs font-bold px-2 py-1.5 rounded-lg">🔴 Live Now</span>
                    <?php endif; ?>
                    <img src="<?= $row['image'] ?>" alt="<?= $row['title'] ?>" class="w-full h-52 object-cover">
                    
                </div>
                <div class="p-4">
                    <h2 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($row['title']) ?></h2>
                    <div class="flex items-center gap-3 mt-3">
                        <div class="bg-black text-white rounded-md px-3 py-2 text-center">
                            <?php
                                $eventDate = date('d M', strtotime($row['date'])); 
                                $parts = explode(' ', $eventDate);
                            ?>
                            <div class="text-xl font-bold leading-none"><?= $parts[0] ?></div>
                            <div class="text-xs uppercase"><?= $parts[1] ?></div>
                        </div>
                        <div class="flex flex-col gap-1 text-sm text-gray-700">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 20s6-5.686 6-10a6 6 0 1 0-12 0c0 4.314 6 10 6 10z"/>
                                </svg>
                                <span><?= htmlspecialchars($row['location']) ?></span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4h12v2H4V4zm0 4h12v2H4V8zm0 4h8v2H4v-2z"/>
                                </svg>
                                <span>Price starts from ৳ <?= htmlspecialchars($row['price']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (count($events) > 9): ?>
        <div class="flex justify-center mt-0 mb-10">
            <button onclick="showAllEvents()" class="px-6 py-2 bg-black hover:bg-green-700 text-white rounded-lg shadow-md transition-all">
                Show All
            </button>
        </div>
    <?php endif; ?>

    <script>
        function showAllEvents() {
            document.querySelectorAll('.more-events').forEach(card => card.classList.remove('hidden'));
            event.target.style.display = 'none';
        }
    </script>
</body>
</html>
