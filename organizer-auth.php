<?php
// session_start();
if (!isset($_SESSION['user'])) {
    header("Location: signin.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}
elseif ($_SESSION['user']['role']!="organizer"){
    echo "you are not organizer";
    header("Location: error.php?");
    exit;
}