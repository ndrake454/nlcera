<?php
/**
 * API: Get Section
 * Retrieves section data for editing
 * 
 * Place this file in: /api/get_section.php
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
$section_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($section_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid section ID.']);
    exit;
}

// Get section data
$section = db_get_row(
    "SELECT * FROM protocol_sections WHERE id = ?",
    [$section_id]
);

if (!$section) {
    echo json_encode(['success' => false, 'message' => 'Section not found.']);
    exit;
}

// Return section data
echo json_encode([
    'success' => true,
    'section' => $section
]);
?>