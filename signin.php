<?php
session_start();
include "./DB/database.php";

$email = $password = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    if (empty($errors)) {
        // Include is_banned in the SELECT query
        $stmt = $conn->prepare("SELECT id, name, email, phone, password, role, is_banned FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Check if user is banned before verifying password
            if ($user['is_banned']) {
                $errors[] = "This account has been banned. Please contact support.";
            } elseif (password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id'       => $user['id'],
                    'name'     => $user['name'],
                    'email'    => $user['email'],
                    'phone'    => $user['phone'],
                    'role'     => $user['role'],
                    'is_banned'=> $user['is_banned'] // Store banned status in session
                ];

                if ($user['role'] === 'organizer') {
                    $_SESSION['organizer'] = $_SESSION['user'];
                }

                $redirectUrl = $_GET['redirect'] ?? 'index.php';
                header("Location: " . $redirectUrl);
                exit;
            } else {
                $errors[] = "Incorrect password.";
            }
        } else {
            $errors[] = "No account found with that email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="sticky top-0 z-50">
        <?php
            include "./navbar.php";
        ?>
    </nav>

<section class="bg-white dark:bg-gray-900">
    <div class="container flex items-center justify-center min-h-screen px-6 mx-auto">
        <form method="POST" class="w-full max-w-md">
            <div class="flex items-center justify-center">
                <img class="w-auto h-12 lg:h-20" src="./images/logo2.png" alt="logo">

            </div>

            <h1 class="mt-3 text-2xl font-semibold text-gray-800 capitalize sm:text-3xl dark:text-white">Sign In</h1>

            <?php if (!empty($errors)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mt-4 text-sm">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="relative flex items-center mt-8">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </span>
                <input name="email" type="email" value="<?= htmlspecialchars($email) ?>" required class="block w-full py-3 text-gray-700 bg-white border rounded-lg px-11 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:ring focus:ring-opacity-40" placeholder="Email address">
            </div>

            <div class="relative flex items-center mt-4">
                <span class="absolute">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mx-3 text-gray-300 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </span>
                <input name="password" type="password" required class="block w-full px-10 py-3 text-gray-700 bg-white border rounded-lg dark:bg-gray-900 dark:text-gray-300 dark:border-gray-600 focus:ring focus:ring-opacity-40" placeholder="Password">
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full px-6 py-3 text-sm font-medium tracking-wide text-white bg-purple-500 rounded-lg hover:bg-purple-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                    Sign In <i class="fa-solid fa-right-to-bracket ml-2"></i>
                </button>

                <p class="mt-4 text-center text-gray-600 dark:text-gray-400">or sign in with</p>

                <a href="./admin/admin_dashboard.php" class="flex items-center justify-center px-6 py-3 mt-4 text-gray-600 border rounded-lg dark:border-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <span><i class="fa-solid fa-user-tie mr-2"></i>Sign in as Admin</span>
                </a>

                <div class="mt-6 text-center flex flex-col">
                    <a href="./signup.php" class="text-sm text-blue-500 hover:underline dark:text-blue-400">
                        Don't have an account yet? Sign up
                    </a>
                    <a href="./forget_password.php" class="text-sm text-blue-500 hover:underline dark:text-blue-400">
                        Forget Password?
                    </a>
                </div>
            </div>
        </form>
    </div>
</section>

<?php include "./footer.php"; ?>
</body>
</html>