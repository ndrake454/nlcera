<?php
/**
 * API: Get Decision Branches
 * Retrieves decision branches for a section
 * 
 * Place this file in: /api/get_branches.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Set content type to JSON
header('Content-Type: application/json');

// Require login
if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Authentication required.']);
    exit;
}

// Get section ID from query string
$section_id = isset($_GET['section_id']) ? intval($_GET['section_id']) : 0;

if ($section_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid section ID.']);
    exit;
}

// Get branches data with target section titles
$branches = db_get_rows(
    "SELECT b.*, ps.title as target_section_title 
     FROM decision_branches b
     LEFT JOIN protocol_sections ps ON b.target_section_id = ps.id
     WHERE b.section_id = ?
     ORDER BY b.display_order ASC",
    [$section_id]
);

// Return branches data
echo json_encode([
    'success' => true,
    'branches' => $branches
]);
?>