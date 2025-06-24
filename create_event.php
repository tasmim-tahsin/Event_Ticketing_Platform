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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $privacy = mysqli_real_escape_string($conn, $_POST['privacy']);
    $policy = mysqli_real_escape_string($conn, $_POST['policy']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $time = mysqli_real_escape_string($conn, $_POST['time']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

    // Handle image upload
    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['image']['name']);
        $file_path = $upload_dir . uniqid() . '_' . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
            $image = $file_path;
        }
    }

    $insert_query = "INSERT INTO events (title, image, date, location, price, description, 
                     privacy, policy, status, category, time, payment_method, organizer_id)
                     VALUES ('$title', '$image', '$date', '$location', $price, '$description',
                     '$privacy', '$policy', '$status', '$category', '$time', '$payment_method', $organizer_id)";

    if (mysqli_query($conn, $insert_query)) {
        $event_id = mysqli_insert_id($conn);
        $_SESSION['success'] = "Event created successfully!";
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Error creating event: " . mysqli_error($conn);
    }
}

// Get unique categories from existing events
$categories_query = mysqli_query($conn, "SELECT DISTINCT category FROM events");
$categories = [];
while ($row = mysqli_fetch_assoc($categories_query)) {
    $categories[] = $row['category'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Event</title>
    <link href="./output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<nav class="sticky top-0 z-50">
        <?php
            include "./navbar.php";
        ?>
    </nav>

<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Create New Event</h1>
    
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="bg-white shadow-md p-6 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div>
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-medium mb-2">Event Title*</label>
                    <input type="text" id="title" name="title" 
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-gray-700 font-medium mb-2">Event Image*</label>
                    <input type="file" id="image" name="image" 
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="date" class="block text-gray-700 font-medium mb-2">Date*</label>
                    <input type="date" id="date" name="date" 
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="time" class="block text-gray-700 font-medium mb-2">Time*</label>
                    <input type="time" id="time" name="time" value="10:30:00"
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="location" class="block text-gray-700 font-medium mb-2">Location*</label>
                    <input type="text" id="location" name="location" 
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-gray-700 font-medium mb-2">Price (BDT)*</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" value="0.00"
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <div class="mb-4">
                    <label for="category" class="block text-gray-700 font-medium mb-2">Category*</label>
                    <select id="category" name="category" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="General" selected>General</option>
                        <?php foreach ($categories as $cat): ?>
                            <?php if ($cat != 'General'): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <option value="Other">Other (specify in description)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-gray-700 font-medium mb-2">Status*</label>
                    <select id="status" name="status" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="upcoming" selected>Upcoming</option>
                        <option value="live">Live</option>
                        <option value="past">Past</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="privacy" class="block text-gray-700 font-medium mb-2">Privacy Policy</label>
                    <textarea id="privacy" name="privacy" rows="3" 
                              class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Example: Public event, Open to all, etc."></textarea>
                </div>

                <div class="mb-4">
                    <label for="policy" class="block text-gray-700 font-medium mb-2">Event Policy</label>
                    <textarea id="policy" name="policy" rows="3" 
                              class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Example: No refunds after 24 hours, ID required, etc."></textarea>
                </div>

                <div class="mb-4">
                    <label for="payment_method" class="block text-gray-700 font-medium mb-2">Payment Methods</label>
                    <input type="text" id="payment_method" name="payment_method" 
                           value="Bkash, Nagad, Visa, Mastercard"
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-medium mb-2">Description*</label>
            <textarea id="description" name="description" rows="6" 
                      class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" 
                      required placeholder="Detailed description of your event..."></textarea>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="dashboard.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Create Event</button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>