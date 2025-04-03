<?php
// config.php - Site Configuration

error_reporting(E_ALL); // Use E_ALL for development
ini_set('display_errors', 1); // Display errors for development
// error_reporting(0); // Turn off error reporting for production
// ini_set('display_errors', 0); // Turn off error display for production

// Database Credentials (Replace with your actual details!)
define('DB_HOST', 'localhost'); // Often localhost on SiteGround, check your hosting details
define('DB_NAME', '-'); // The database name you created
define('DB_USER', '-'); // The database user you created
define('DB_PASS', '-'); // The password you set for the database user
define('DB_CHARSET', 'utf8mb4');

// Site Settings
define('SITE_NAME', 'Northern Colorado Prehospital Protocols');
define('BASE_URL', 'https://nlcera.firelight.academy'); // Change to your actual domain
define('ADMIN_EMAIL', 'admin@firelight.academy'); // For contact or error reporting

// Start the session for login management
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Set default timezone (optional, but good practice)
date_default_timezone_set('America/Denver');
?>