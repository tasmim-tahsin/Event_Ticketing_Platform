<?php
session_start();
include "DB/database.php";
include "auth.php";
include "./navbar.php";

$user = $_SESSION['user'];
$user_id = $user['id'];

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = $_POST['current_password'] ?? '';
    $new = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    // Fetch current password hash
    $result = mysqli_query($conn, "SELECT password FROM users WHERE id = '$user_id'");
    $row = mysqli_fetch_assoc($result);
    $current_hash = $row['password'];

    // Validate
    if (!password_verify($current, $current_hash)) {
        $errors[] = "Current password is incorrect.";
    }

    if ($new !== $confirm) {
        $errors[] = "New password and confirmation do not match.";
    }

    if (strlen($new) < 8) {
        $errors[] = "Password must be at least 8 characters.";
    }

    if (is_numeric($new)) {
        $errors[] = "Password can't be entirely numeric.";
    }

    // if (stripos($new, $user['name']) !== false || stripos($new, $user['username']) !== false) {
    //     $errors[] = "Password is too similar to your personal information.";
    // }

    // If valid, update
    if (empty($errors)) {
        $new_hash = password_hash($new, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password = '$new_hash' WHERE id = '$user_id'");
        $success = true;
        header("refresh:2;url=profile.php");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($_SESSION['user']['name']) ?> - Reset Password</title>
  <link href="./output.css" rel="stylesheet">
</head>
<body>

<div class="bg-white p-10 rounded shadow-md w-full max-w-xl mx-auto text-center mt-5">
    <h2 class="text-2xl font-bold mb-2">Update Password</h2>
    <p class="text-sm text-gray-600 mb-6">We’ve sent a code to <strong><?= htmlspecialchars($user['email']) ?></strong>. The code expires shortly, so please enter it soon.</p>

    <?php if ($success): ?>
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">✅ Password updated successfully. Redirecting...</div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4 text-left">
            <ul class="list-disc pl-5 text-sm">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4 text-left">
        <div>
            <input type="password" name="current_password" placeholder="Current Password"
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div>
            <input type="password" name="new_password" placeholder="New Password"
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <div>
            <input type="password" name="confirm_password" placeholder="Re-write New Password"
                   class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>
        <button type="submit"
                class="bg-black text-white px-6 py-2 rounded hover:bg-gray-800 transition">Change Password</button>
    </form>

    <ul class="text-left mt-6 text-sm list-disc pl-5 text-gray-700">
        <li>Your password must contain at least 8 characters.</li>
        <li>Your password can’t be entirely numeric.</li>
        <li>Your password can’t be too similar to your other personal information.</li>
        <li>Your password can’t be a commonly used password.</li>
    </ul>
</div>

</body>
</html>
