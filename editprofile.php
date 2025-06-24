<?php
session_start();
include "auth.php";
include "DB/database.php";

$userId = $_SESSION['user']['id'];

// Fetch current user data
$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $userId");
$user = mysqli_fetch_assoc($result);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $imagePath = $user['profile_image'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $destination = "uploads/" . $imageName;

        if (move_uploaded_file($imageTmp, $destination)) {
            $imagePath = $destination;
        } else {
            $error = "Image upload failed.";
        }
    }

    if (empty($error)) {
        $update = $conn->prepare("UPDATE users SET name = ?, phone = ?, email = ?, address = ?, profile_image = ? WHERE id = ?");
        $update->bind_param("sssssi", $name, $phone, $email, $address, $imagePath, $userId);

        if ($update->execute()) {
            // Update session values too
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['phone'] = $phone;
            $_SESSION['user']['address'] = $address;
            $_SESSION['user']['profile_image'] = $imagePath;
            header("Location: profile.php");
            exit();
        } else {
            $error = "Error updating profile.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($_SESSION['user']['name']) ?> - Edit Profile</title>
    <link href="./output.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<nav class="sticky top-0 z-50">
        <?php
            include "./navbar.php";
        ?>
    </nav>
<div  class="text-center p-5 my-5 bg-amber-50 rounded-md max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold">Edit Profile</h1>
        <p>Here, you can update your username, full name, phone number, email address, and profile image. Please ensure all information is accurate before saving changes.</p>
    </div>
<div class="max-w-4xl mx-auto my-10 bg-white p-8 rounded shadow border border-yellow-300">
    <h2 class="text-3xl font-bold text-gray-800 mb-2">Change Photo</h2>
    <p class="text-sm text-gray-500 mb-6">Click the camera icon to change photo.</p>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 mb-4 rounded"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex flex-col items-center space-y-2">
            <div class="relative">
                <img src="<?= $user['profile_image'] ?: './images/default.png' ?>" alt="Avatar" class="w-32 h-32 rounded-full border">
                <label for="image" class="absolute bottom-0 right-0 bg-white rounded-full p-2 shadow cursor-pointer">
                    <i class="fa fa-camera"></i>
                </label>
                <input type="file" name="image" id="image" class="hidden" accept="image/*">
            </div>
        </div>

        <div>
            <label class="block font-semibold text-sm mb-1">Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" class="w-full px-4 py-2 border rounded" required>

            <label class="block font-semibold text-sm mt-4 mb-1">Phone Number</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" class="w-full px-4 py-2 border rounded">

            <label class="block font-semibold text-sm mt-4 mb-1">Email Address</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="w-full px-4 py-2 border rounded" required>

            <label class="block font-semibold text-sm mt-4 mb-1">Address</label>
            <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" class="w-full px-4 py-2 border rounded">

            <button type="submit" class="mt-6 bg-black text-white py-2 px-6 rounded hover:bg-blue-700">Save Changes</button>
        </div>
    </form>
</div>

<?php include "footer.php"; ?>
</body>
</html>
