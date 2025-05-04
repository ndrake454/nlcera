<?php
/**
 * API: Get Template
 * Returns template data for a specific protocol template
 * 
 * Place this file in: /api/get_template.php
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

// Get template ID from query string
$template_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Validate template ID
if ($template_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid template ID.']);
    exit;
}

// Get the template data
$template = db_get_row(
    "SELECT * FROM protocol_templates WHERE id = ?",
    [$template_id]
);

if (!$template) {
    echo json_encode(['success' => false, 'message' => 'Template not found.']);
    exit;
}

// Return the template data
echo json_encode([
    'success' => true,
    'template' => $template
]);
?>