<?php
/**
 * API: Get Protocols List
 * Returns a list of all active protocols for PWA caching
 * 
 * Place this file in: /api/get_protocols_list.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if this is a sync request (no authentication required for sync requests)
$is_sync = isset($_GET['sync']) && $_GET['sync'] === '1';

// If not a sync request, require login
if (!$is_sync && !is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Authentication required.']);
    exit;
}

try {
    // Get all active protocols
    $protocols = db_get_rows(
        "SELECT p.id, p.protocol_number, p.title, p.updated_at, c.title as category_name 
         FROM protocols p
         JOIN categories c ON p.category_id = c.id
         WHERE p.is_active = 1
         ORDER BY p.protocol_number ASC"
    );
    
    // Get list of recently updated protocols (updated in the last 24 hours)
    $recentlyUpdated = db_get_rows(
        "SELECT p.id, p.protocol_number, p.title, p.updated_at, c.title as category_name 
         FROM protocols p
         JOIN categories c ON p.category_id = c.id
         WHERE p.is_active = 1 AND p.updated_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
         ORDER BY p.updated_at DESC"
    );
    
    // Get a list of all categories
    $categories = db_get_rows(
        "SELECT id, category_number, title 
         FROM categories 
         ORDER BY display_order ASC"
    );
    
    // Return the data
    echo json_encode([
        'success' => true,
        'protocols' => $protocols,
        'recently_updated' => $recentlyUpdated,
        'categories' => $categories,
        'timestamp' => date('Y-m-d H:i:s'),
        'total_count' => count($protocols)
    ]);
} catch (Exception $e) {
    // Return error message
    error_log('Error in get_protocols_list.php: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Failed to retrieve protocols list: ' . $e->getMessage()
    ]);
}