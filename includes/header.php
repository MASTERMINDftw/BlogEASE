<?php
require_once 'config.php';

// Check if user is logged in (for admin pages)
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Redirect to login if not logged in (for admin pages)
function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if (basename($_SERVER['PHP_SELF']) == 'index.php' || basename($_SERVER['PHP_SELF']) == 'post.php'): ?>
        <link rel="stylesheet" href="css/style.css">
        <title>BlogEASE - Home</title>
    <?php else: ?>
        <link rel="stylesheet" href="../css/admin.css">
        <title>Admin Panel - BlogEASE</title>
    <?php endif; ?>
</head>
<body>