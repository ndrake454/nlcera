<?php
/**
 * PWA Sync Script (Updated for PWA Maker Icons)
 * 
 * This script updates the service worker cache with the latest protocol data.
 * It should be run via cron job every night to ensure offline data is up to date.
 * 
 * Place this file in: /admin/pwa_sync.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Security: Only allow running from command line or with special key
$allowed = false;

// Check if running from CLI
if (php_sapi_name() === 'cli') {
    $allowed = true;
}

// Or check for security key in query string
if (isset($_GET['key']) && $_GET['key'] === HASH_SALT) {
    $allowed = true;
}

if (!$allowed) {
    header('HTTP/1.1 403 Forbidden');
    echo "Access denied";
    exit;
}

// Set content type if not CLI
if (php_sapi_name() !== 'cli') {
    header('Content-Type: text/plain');
}

// Start the log
$log = "PWA Sync started at " . date('Y-m-d H:i:s') . "\n";

try {
    // Get all active protocols
    $protocols = db_get_rows(
        "SELECT p.id, p.protocol_number, p.title, p.updated_at 
         FROM protocols p
         WHERE p.is_active = 1
         ORDER BY p.protocol_number ASC"
    );
    
    $log .= "Found " . count($protocols) . " active protocols\n";
    
    // Get active categories
    $categories = db_get_rows("SELECT id FROM categories ORDER BY display_order ASC");
    $log .= "Found " . count($categories) . " categories\n";
    
    // Create a manifest of URLs that need to be cached
    $manifest = [
        // Core pages
        'index.php',
        'offline.html',
        'assets/js/pwa.js',
        'assets/js/service-worker.js',
        'manifest.json'
    ];
    
    // Add protocols to manifest
    foreach ($protocols as $protocol) {
        $manifest[] = "protocol.php?id=" . $protocol['id'];
    }
    
    // Add categories to manifest
    foreach ($categories as $category) {
        $manifest[] = "category.php?id=" . $category['id'];
    }
    
    // Add the index and protocol list to manifest
    $manifest[] = "index.php";
    $manifest[] = "api/get_protocols_list.php?sync=1";
    
    // Add all PWA icon assets
    // Android icons
    $manifest[] = "assets/icons/android/android-launchericon-512-512.png";
    $manifest[] = "assets/icons/android/android-launchericon-192-192.png";
    $manifest[] = "assets/icons/android/android-launchericon-144-144.png";
    $manifest[] = "assets/icons/android/android-launchericon-96-96.png";
    $manifest[] = "assets/icons/android/android-launchericon-72-72.png";
    $manifest[] = "assets/icons/android/android-launchericon-48-48.png";
    
    // iOS icons
    $manifest[] = "assets/icons/ios/192.png";
    $manifest[] = "assets/icons/ios/512.png";
    $manifest[] = "assets/icons/ios/180.png";
    $manifest[] = "assets/icons/ios/167.png";
    $manifest[] = "assets/icons/ios/152.png";
    $manifest[] = "assets/icons/ios/32.png";
    $manifest[] = "assets/icons/ios/16.png";
    
    // Create the cache manifest file
    $manifestFile = dirname(__DIR__) . '/pwa-cache-manifest.json';
    file_put_contents($manifestFile, json_encode([
        'urls' => $manifest,
        'timestamp' => date('Y-m-d H:i:s'),
        'version' => 'v1-' . date('Ymd')
    ]));
    
    $log .= "Generated cache manifest with " . count($manifest) . " URLs\n";
    
    // Update service worker version to force refresh 
    $serviceWorkerFile = dirname(__DIR__) . '/service-worker.js'; // Root service worker
    $serviceWorkerContent = file_get_contents($serviceWorkerFile);
    
    // Update cache version with current date
    $newCacheVersion = "'ems-protocols-v1-" . date('Ymd') . "'";
    $serviceWorkerContent = preg_replace(
        "/const CACHE_NAME = ['\"](.*?)['\"]/",
        "const CACHE_NAME = $newCacheVersion",
        $serviceWorkerContent
    );
    
    // Write updated service worker
    file_put_contents($serviceWorkerFile, $serviceWorkerContent);
    $log .= "Updated service worker cache version\n";
    
    // Finish successfully
    $log .= "PWA Sync completed successfully at " . date('Y-m-d H:i:s') . "\n";
    echo $log;
    
    // Create logs directory if it doesn't exist
    $logDir = dirname(__DIR__) . '/logs';
    if (!is_dir($logDir)) {
        // Try to create the directory with proper permissions
        if (!mkdir($logDir, 0755, true)) {
            echo "Warning: Could not create logs directory at $logDir\n";
        }
    }
    
    // Also log to file if the directory exists
    $logFile = $logDir . '/pwa_sync.log';
    if (is_dir($logDir) && is_writable($logDir)) {
        file_put_contents($logFile, $log, FILE_APPEND);
    } else {
        echo "Warning: Logs directory does not exist or is not writable\n";
    }
    
    // Exit with success
    exit(0);
} catch (Exception $e) {
    // Log error
    $error = "ERROR: " . $e->getMessage() . "\n";
    $log .= $error;
    
    echo $log;
    
    // Create logs directory if it doesn't exist
    $logDir = dirname(__DIR__) . '/logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    // Try to log to file if possible
    if (is_dir($logDir) && is_writable($logDir)) {
        $logFile = $logDir . '/pwa_sync.log';
        file_put_contents($logFile, $log, FILE_APPEND);
    }
    
    // Exit with error
    exit(1);
}