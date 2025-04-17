<?php
/**
 * Configuration file
 * Contains database credentials and other site-wide settings
 * 
 * Place this file in: /includes/config.php
 */

// Add to includes/config.php or at the top of key PHP files
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Database Connection Settings
define('DB_HOST', 'localhost'); // Database host - typically localhost
define('DB_NAME', '-'); // Database name - create this in SiteGround
define('DB_USER', '-'); // Replace with your database username
define('DB_PASS', '-'); // Replace with your database password

// Set timezone to Mountain Time
date_default_timezone_set('America/Denver');

// Site Settings
define('SITE_NAME', 'Northern Colorado Prehospital Protocols');
define('SITE_URL', 'https://nlcera.firelight.academy'); // Replace with your domain
define('ADMIN_EMAIL', 'admin@firelight.academy');

// Session Settings
define('SESSION_NAME', 'ems_protocols');
define('SESSION_TIMEOUT', 3600); // Session timeout in seconds (1 hour)

// Icon Directory Path
define('ICON_PATH', '/assets/icons/');

// Error Reporting (set to false in production)
define('DEBUG_MODE', true);

// Security
define('HASH_SALT', '-'); // Used for password hashing

// Init settings based on environment
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}
?>