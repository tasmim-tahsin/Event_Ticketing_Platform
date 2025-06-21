<?php
session_start();
include 'organizer-auth.php';
include 'DB/database.php';

// Only allow organizer
// if (!isset($_SESSION['user']) {
//     header("Location: login.php");
//     exit;
// }

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$event_id = $_GET['id'];
$organizer_id = $_SESSION['user']['id'];

// Fetch event data
$event_query = mysqli_query($conn, "SELECT * FROM events WHERE id = $event_id AND organizer_id = $organizer_id");
$event = mysqli_fetch_assoc($event_query);

if (!$event) {
    header("Location: dashboard.php");
    exit;
}

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
    $image = $event['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['image']['name']);
        $file_path = $upload_dir . uniqid() . '_' . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
            // Delete old image if it exists
            if ($image && file_exists($image)) {
                unlink($image);
            }
            $image = $file_path;
        }
    }

    $update_query = "UPDATE events SET 
                    title = '$title',
                    image = '$image',
                    date = '$date',
                    location = '$location',
                    price = $price,
                    description = '$description',
                    privacy = '$privacy',
                    policy = '$policy',
                    status = '$status',
                    category = '$category',
                    time = '$time',
                    payment_method = '$payment_method'
                    WHERE id = $event_id AND organizer_id = $organizer_id";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['success'] = "Event updated successfully!";
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Error updating event: " . mysqli_error($conn);
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
    <title>Edit Event</title>
    <link href="./output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<?php include 'navbar.php'; ?>

<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Edit Event</h1>
    
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
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" 
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-gray-700 font-medium mb-2">Event Image</label>
                    <input type="file" id="image" name="image" 
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php if ($event['image']): ?>
                        <div class="mt-2">
                            <img src="<?php echo htmlspecialchars($event['image']); ?>" alt="Current Event Image" class="h-32 object-cover">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="date" class="block text-gray-700 font-medium mb-2">Date*</label>
                    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($event['date']); ?>" 
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="time" class="block text-gray-700 font-medium mb-2">Time*</label>
                    <input type="time" id="time" name="time" value="<?php echo htmlspecialchars($event['time']); ?>" 
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="location" class="block text-gray-700 font-medium mb-2">Location*</label>
                    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($event['location']); ?>" 
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-gray-700 font-medium mb-2">Price (BDT)*</label>
                    <input type="number" id="price" name="price" step="0.01" min="0" value="<?php echo htmlspecialchars($event['price']); ?>" 
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <div class="mb-4">
                    <label for="category" class="block text-gray-700 font-medium mb-2">Category*</label>
                    <select id="category" name="category" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo $event['category'] == $cat ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-gray-700 font-medium mb-2">Status*</label>
                    <select id="status" name="status" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="upcoming" <?php echo $event['status'] == 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                        <option value="live" <?php echo $event['status'] == 'live' ? 'selected' : ''; ?>>Live</option>
                        <option value="past" <?php echo $event['status'] == 'past' ? 'selected' : ''; ?>>Past</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="privacy" class="block text-gray-700 font-medium mb-2">Privacy Policy</label>
                    <textarea id="privacy" name="privacy" rows="3" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($event['privacy']); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="policy" class="block text-gray-700 font-medium mb-2">Event Policy</label>
                    <textarea id="policy" name="policy" rows="3" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($event['policy']); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="payment_method" class="block text-gray-700 font-medium mb-2">Payment Methods</label>
                    <input type="text" id="payment_method" name="payment_method" value="<?php echo htmlspecialchars($event['payment_method']); ?>" 
                           class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-medium mb-2">Description*</label>
            <textarea id="description" name="description" rows="6" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required><?php echo htmlspecialchars($event['description']); ?></textarea>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="dashboard.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update Event</button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>
</body>
</html>