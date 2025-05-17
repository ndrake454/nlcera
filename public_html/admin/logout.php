<?php
/**
 * Admin Logout
 * 
 * Place this file in: /admin/logout.php
 */

// Include required files
require_once dirname(__DIR__, 1) . '/includes/auth.php';

// Log the user out
logout();

// Redirect to login page
header('Location: login.php');
exit;
?>