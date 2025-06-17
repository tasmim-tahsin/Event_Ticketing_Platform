<?php
// session_start();
if (!isset($_SESSION['user'])) {
    header("Location: signin.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}
