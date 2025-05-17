<?php
/**
 * API: Get Protocol Diagram
 * Returns the diagram data for a specific protocol
 * 
 * Place this file in: /api/get_diagram.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Set content type to JSON
header('Content-Type: application/json');

// Get protocol ID from query string
$protocol_id = isset($_GET['protocol_id']) ? intval($_GET['protocol_id']) : 0;

// Check if this is a public request
$is_public = isset($_GET['public']) && $_GET['public'] === '1';

// If not a public request, require login
if (!$is_public && !is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Authentication required.']);
    exit;
}

// Validate protocol ID
if ($protocol_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid protocol ID.']);
    exit;
}

// If public request, check if protocol is active
if ($is_public) {
    $protocol = get_protocol($protocol_id);
    if (!$protocol || !$protocol['is_active']) {
        echo json_encode(['success' => false, 'message' => 'Protocol not found or inactive.']);
        exit;
    }
}

// Get the diagram data
$diagram = db_get_row(
    "SELECT id, protocol_id, xml_content, html_content, updated_at 
     FROM protocol_diagrams 
     WHERE protocol_id = ?",
    [$protocol_id]
);

if (!$diagram) {
    echo json_encode(['success' => false, 'message' => 'No diagram found for this protocol.']);
    exit;
}

// For public requests, only return the HTML content
if ($is_public) {
    echo json_encode([
        'success' => true,
        'diagram' => [
            'id' => $diagram['id'],
            'protocol_id' => $diagram['protocol_id'],
            'html_content' => $diagram['html_content'],
            'updated_at' => $diagram['updated_at']
        ]
    ]);
} else {
    // For admin requests, return full data
    echo json_encode([
        'success' => true,
        'diagram' => $diagram
    ]);
}
?>