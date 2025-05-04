<?php
/**
 * API: Get Decision Branch
 * Retrieves a single decision branch for editing
 * 
 * Place this file in: /api/get_branch.php
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

// Get branch ID from query string
$branch_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($branch_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid branch ID.']);
    exit;
}

// Get branch data
$branch = db_get_row(
    "SELECT * FROM decision_branches WHERE id = ?",
    [$branch_id]
);

if (!$branch) {
    echo json_encode(['success' => false, 'message' => 'Branch not found.']);
    exit;
}

// Return branch data
echo json_encode([
    'success' => true,
    'branch' => $branch
]);
?>