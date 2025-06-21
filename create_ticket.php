<?php
session_start();
include 'organizer-auth.php';
include 'DB/database.php';

// Only allow organizer
// if (!isset($_SESSION['user']) {
//     header("Location: login.php");
//     exit;
// }

$organizer_id = $_SESSION['user']['id'];

// Fetch organizer's events for dropdown
$events = mysqli_query($conn, "SELECT id, title FROM events WHERE organizer_id = $organizer_id");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $event_id = mysqli_real_escape_string($conn, $_POST['event_id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $benefits = mysqli_real_escape_string($conn, $_POST['benefits']);
    $day = mysqli_real_escape_string($conn, $_POST['day']);

    $insert_query = "INSERT INTO tickets (event_id, name, price, quantity, benefits, day)
                     VALUES ($event_id, '$name', $price, $quantity, '$benefits', '$day')";

    if (mysqli_query($conn, $insert_query)) {
        $_SESSION['success'] = "Ticket created successfully!";
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Error creating ticket: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Ticket</title>
    <link href="./output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<?php include 'navbar.php'; ?>

<div class="max-w-2xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Create New Ticket</h1>
    
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="bg-white shadow-md p-6 rounded-lg">
        <div class="mb-4">
            <label for="event_id" class="block text-gray-700 font-medium mb-2">Event*</label>
            <select id="event_id" name="event_id" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">Select an Event</option>
                <?php while ($event = mysqli_fetch_assoc($events)): ?>
                    <option value="<?php echo $event['id']; ?>"><?php echo htmlspecialchars($event['title']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-medium mb-2">Ticket Name*</label>
            <input type="text" id="name" name="name" 
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   required placeholder="Regular, VIP, Student, etc.">
        </div>

        <div class="mb-4">
            <label for="price" class="block text-gray-700 font-medium mb-2">Price (BDT)*</label>
            <input type="number" id="price" name="price" step="0.01" min="0" 
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   required placeholder="500.00">
        </div>

        <div class="mb-4">
            <label for="quantity" class="block text-gray-700 font-medium mb-2">Available Quantity*</label>
            <input type="number" id="quantity" name="quantity" min="1" 
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                   required placeholder="100">
        </div>

        <div class="mb-4">
            <label for="benefits" class="block text-gray-700 font-medium mb-2">Benefits/Description</label>
            <textarea id="benefits" name="benefits" rows="3" 
                      class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="What does this ticket include? (e.g., VIP lounge access, free merchandise, etc.)"></textarea>
        </div>

        <div class="mb-4">
            <label for="day" class="block text-gray-700 font-medium mb-2">Day (for multi-day events)</label>
            <select id="day" name="day" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="day1" selected>Day 1</option>
                <option value="day2">Day 2</option>
                <option value="day3">Day 3</option>
            </select>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="dashboard.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Ticket</button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>