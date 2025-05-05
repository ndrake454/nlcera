<?php
/**
 * Image Upload Handler for TinyMCE
 * 
 * Place this file in: /api/upload_image.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/auth.php';

// Set content type to JSON
header('Content-Type: application/json');

// Make sure to disable output buffering and flush all buffers
if (ob_get_level()) ob_end_clean();

// Require login
if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'Authentication required.']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['file'])) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
    exit;
}

// Define allowed file types
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
$file = $_FILES['file'];

// Validate file type
if (!in_array($file['type'], $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, PNG, GIF, and SVG are allowed.']);
    exit;
}

// Validate file size (5MB max)
$max_size = 5 * 1024 * 1024; // 5MB
if ($file['size'] > $max_size) {
    echo json_encode(['success' => false, 'message' => 'File too large. Maximum size is 5MB.']);
    exit;
}

try {
    // Create uploads directory if it doesn't exist
    $upload_dir = dirname(__DIR__) . '/assets/uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generate a unique filename
    $filename = 'image_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9\.]/', '_', $file['name']);
    $upload_path = $upload_dir . $filename;
    
    // Move the uploaded file
    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        // Return success with file URL - format exactly as TinyMCE expects
        $location = '/assets/uploads/' . $filename;
        echo json_encode([
            'location' => $location  // THIS IS IMPORTANT: TinyMCE expects 'location', not 'url'
        ]);
    } else {
        throw new Exception('Failed to move uploaded file');
    }
} catch (Exception $e) {
    // Log error for debugging
    error_log('Upload error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Failed to upload file: ' . $e->getMessage()]);
}
?>