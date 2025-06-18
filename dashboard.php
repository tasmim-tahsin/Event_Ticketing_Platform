<?php
session_start();
include 'organizer-auth.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="./output.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <?php
        include "./navbar.php";
    ?>
    <span class="text-sm font-medium text-gray-700 dark:text-gray-200"><?= htmlspecialchars($_SESSION['user']['name']) ?></span>
    <?php
        include "./footer.php";
    ?>
</body>
</html>