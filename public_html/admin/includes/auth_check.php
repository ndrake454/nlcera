<?php
// admin/includes/auth_check.php - Protect admin pages

// Ensure session is started (config.php should handle this)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    // Store the intended destination page (optional, for redirecting after login)
    // $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];

    // Redirect to login page
    header("Location: login.php");
    exit; // IMPORTANT: Stop script execution immediately
}

// User is authenticated and is an admin, proceed with the rest of the page load.
$current_user_id = $_SESSION['user_id'];
$current_username = $_SESSION['username'];

?>