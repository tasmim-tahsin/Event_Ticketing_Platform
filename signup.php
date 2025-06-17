<?php
// session_start();
include "./DB/database.php";

$name = $email = $password = $phone = $role = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $phone = trim($_POST['phone']);
    $agreed = isset($_POST['terms']);
    $isOrganizer = isset($_POST['organizer']);

    // Set role based on organizer checkbox
    $role = $isOrganizer ? 'organizer' : 'user';

    // Validate fields
    if (empty($name)) $errors[] = "Name is required";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Invalid email";
    if (strlen($password) < 8) $errors[] = "Password must be at least 8 characters";
    if (!preg_match("/^[0-9]{10,15}$/", $phone)) $errors[] = "Invalid phone number";
    if (!$agreed) $errors[] = "You must agree to the terms";

    // If no errors, insert into DB
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $checkQuery = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
        if (mysqli_num_rows($checkQuery) > 0) {
            $errors[] = "Email already registered.";
        } else {
            // INSERT with role
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $hashedPassword, $phone, $role);

            if ($stmt->execute()) {
                // Save session data
                $_SESSION['user'] = [
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'role' => $role
                ];
                header("Location: signin.php");
                exit();
            } else {
                $errors[] = "Database error: " . $stmt->error;
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>SignUp</title>
  <link href="./output.css" rel="stylesheet"/>
</head>
<body>
<?php include "./navbar.php"; ?>

<div class="flex w-full max-w-sm mx-auto overflow-hidden bg-white rounded-lg shadow-lg dark:bg-gray-800 lg:max-w-4xl my-20">
  <div class="hidden bg-cover lg:block lg:w-1/2" style="background-image: url('https://images.unsplash.com/photo-1606660265514-358ebbadc80d?auto=format&fit=crop&w=1575&q=80');"></div>

  <div class="w-full px-6 py-8 md:px-8 lg:w-1/2">
    <h4 class="text-xl font-medium text-slate-800">Sign Up</h4>
    <p class="text-slate-500 text-sm">Nice to meet you! Enter your details to register.</p>

    <?php if (!empty($errors)): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mt-4 text-sm">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form class="mt-6 space-y-3" method="POST" action="">
      <div>
        <label class="text-sm text-slate-600">Your Name</label>
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>" required class="w-full border px-3 py-2 rounded text-sm text-slate-700" />
      </div>
      <div>
        <label class="text-sm text-slate-600">Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required class="w-full border px-3 py-2 rounded text-sm text-slate-700" />
      </div>
      <div>
        <label class="text-sm text-slate-600">Password</label>
        <input type="password" name="password" required class="w-full border px-3 py-2 rounded text-sm text-slate-700" />
      </div>
      <div>
        <label class="text-sm text-slate-600">Phone</label>
        <input type="tel" name="phone" value="<?= htmlspecialchars($phone) ?>" required class="w-full border px-3 py-2 rounded text-sm text-slate-700" />
      </div>
      <div class="flex items-center">
        <input type="checkbox" name="organizer" id="organizer" class="h-4 w-4 border rounded mr-2" <?= isset($_POST['terms']) ? 'checked' : '' ?>>
        <label for="organizer" class="text-sm font-semibold text-red-400">Sign Up as Organizer</label>
      </div>
      <div class="flex items-center">
        <input type="checkbox" name="terms" id="terms" class="h-4 w-4 border rounded mr-2" <?= isset($_POST['terms']) ? 'checked' : '' ?>>
        <label for="terms" class="text-sm text-slate-600">Agree with our <a href="#" class=" underline">terms and conditions</a></label>
      </div>
      <button type="submit" class="w-full bg-slate-800 text-white py-2 rounded hover:bg-slate-700 transition">Sign Up</button>
      <p class="text-sm text-center text-slate-600 mt-2">Already have an account?
        <a href="./signin.php" class="text-slate-800 font-semibold underline">Sign in</a>
      </p>
    </form>
  </div>
</div>

<?php include "./footer.php"; ?>
</body>
</html>
