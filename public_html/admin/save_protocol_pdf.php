<?php
/**
 * Protocol PDF Diagram Upload Handler
 * 
 * This file handles saving PDF diagrams uploaded from the Draw.io editor
 * Place this file in: /admin/save_protocol_pdf.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Set content type to JSON
header('Content-Type: application/json');

// Enable error reporting for debugging
if (DEBUG_MODE) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

// Log function for debugging
function debug_log($message) {
    if (DEBUG_MODE) {
        error_log('[save_protocol_pdf] ' . $message);
    }
}

// Require login
if (!is_logged_in()) {
    debug_log('Authentication required');
    echo json_encode(['success' => false, 'message' => 'Authentication required.']);
    exit;
}

// Check if this is a save PDF request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_pdf') {
    $protocol_id = isset($_POST['protocol_id']) ? intval($_POST['protocol_id']) : 0;
    
    debug_log('Processing save_pdf request for protocol ID: ' . $protocol_id);
    
    // Validate protocol ID
    if ($protocol_id <= 0) {
        debug_log('Invalid protocol ID: ' . $protocol_id);
        echo json_encode(['success' => false, 'message' => 'Invalid protocol ID.']);
        exit;
    }
    
    // Check if protocol exists
    $protocol = get_protocol($protocol_id);
    if (!$protocol) {
        debug_log('Protocol not found: ' . $protocol_id);
        echo json_encode(['success' => false, 'message' => 'Protocol not found.']);
        exit;
    }
    
    // Check if PDF file was uploaded
    if (!isset($_FILES['pdf_file'])) {
        debug_log('No PDF file uploaded');
        echo json_encode(['success' => false, 'message' => 'No PDF file uploaded.']);
        exit;
    }
    
    // Check for upload errors
    if ($_FILES['pdf_file']['error'] !== UPLOAD_ERR_OK) {
        $error_message = 'Upload error: ';
        switch ($_FILES['pdf_file']['error']) {
            case UPLOAD_ERR_INI_SIZE:
                $error_message .= 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $error_message .= 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $error_message .= 'The uploaded file was only partially uploaded.';
                break;
            case UPLOAD_ERR_NO_FILE:
                $error_message .= 'No file was uploaded.';
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $error_message .= 'Missing a temporary folder.';
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $error_message .= 'Failed to write file to disk.';
                break;
            case UPLOAD_ERR_EXTENSION:
                $error_message .= 'A PHP extension stopped the file upload.';
                break;
            default:
                $error_message .= 'Unknown error code: ' . $_FILES['pdf_file']['error'];
        }
        debug_log($error_message);
        echo json_encode(['success' => false, 'message' => $error_message]);
        exit;
    }
    
    // Log file details for debugging
    debug_log('PDF file details: name=' . $_FILES['pdf_file']['name'] . 
              ', size=' . $_FILES['pdf_file']['size'] . 
              ', type=' . $_FILES['pdf_file']['type']);
    
    // Validate file size (should be non-zero)
    if ($_FILES['pdf_file']['size'] <= 0) {
        debug_log('PDF file has zero size');
        echo json_encode(['success' => false, 'message' => 'PDF file has zero size. Export failed.']);
        exit;
    }
    
    // Validate file type (more lenient approach)
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($_FILES['pdf_file']['tmp_name']);
    debug_log('Detected MIME type: ' . $mime_type);
    
    // Accept a wider range of MIME types that might be reported for PDFs
    $allowed_types = [
        'application/pdf', 
        'application/x-pdf', 
        'application/octet-stream',
        'application/binary',
        'binary/octet-stream'
    ];
    
    if (!in_array($mime_type, $allowed_types)) {
        // Additional check: if file starts with %PDF, it's likely a PDF regardless of MIME type
        $file_start = file_get_contents($_FILES['pdf_file']['tmp_name'], false, null, 0, 5);
        if ($file_start !== '%PDF-') {
            debug_log('Invalid file type: ' . $mime_type . ', file start: ' . bin2hex($file_start));
            echo json_encode(['success' => false, 'message' => 'Invalid file type: ' . $mime_type . '. File does not appear to be a PDF.']);
            exit;
        } else {
            debug_log('MIME type not in allowed list, but file starts with %PDF, continuing');
        }
    }
    
    // Create upload directory if it doesn't exist
    $upload_dir = dirname(__DIR__) . '/assets/diagrams/';
    if (!is_dir($upload_dir)) {
        debug_log('Creating diagrams directory: ' . $upload_dir);
        if (!mkdir($upload_dir, 0755, true)) {
            debug_log('Failed to create directory: ' . $upload_dir);
            echo json_encode(['success' => false, 'message' => 'Failed to create upload directory.']);
            exit;
        }
    }
    
    // Generate a unique filename
    $filename = 'diagram_' . $protocol_id . '_' . uniqid() . '.pdf';
    $upload_path = $upload_dir . $filename;
    
    debug_log('Saving PDF to: ' . $upload_path);
    
    // Move the uploaded file
    if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $upload_path)) {
        debug_log('File moved successfully');
        
        // Verify the file was saved and has content
        if (!file_exists($upload_path) || filesize($upload_path) <= 0) {
            debug_log('Error: File exists but is empty: ' . $upload_path);
            echo json_encode(['success' => false, 'message' => 'PDF file saved but appears to be empty.']);
            exit;
        }
        
        // Get the current user ID
        $user_id = get_current_user_id();
        
        // Check if there's an existing PDF record
        $existing_pdf = db_get_row(
            "SELECT id, filename FROM protocol_diagrams_pdf WHERE protocol_id = ?",
            [$protocol_id]
        );
        
        debug_log('Checking for existing PDF record: ' . ($existing_pdf ? 'found' : 'not found'));
        
        // Delete old PDF file if exists
        if ($existing_pdf && !empty($existing_pdf['filename'])) {
            $old_file = $upload_dir . $existing_pdf['filename'];
            if (file_exists($old_file)) {
                debug_log('Deleting old PDF file: ' . $old_file);
                if (!unlink($old_file)) {
                    debug_log('Warning: Failed to delete old PDF file: ' . $old_file);
                }
            }
        }
        
        if ($existing_pdf) {
            // Update existing record
            debug_log('Updating existing PDF record: ' . $existing_pdf['id']);
            $result = db_update(
                'protocol_diagrams_pdf',
                [
                    'filename' => $filename,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => $user_id
                ],
                'id = ?',
                [$existing_pdf['id']]
            );
        } else {
            // Insert new record
            debug_log('Inserting new PDF record');
            $result = db_insert(
                'protocol_diagrams_pdf',
                [
                    'protocol_id' => $protocol_id,
                    'filename' => $filename,
                    'created_by' => $user_id,
                    'updated_by' => $user_id
                ]
            );
        }
        
        if ($result !== false) {
            debug_log('PDF record saved successfully');
            echo json_encode([
                'success' => true, 
                'message' => 'PDF saved successfully.',
                'filename' => $filename,
                'url' => '/assets/diagrams/' . $filename,
                'filesize' => filesize($upload_path)
            ]);
        } else {
            debug_log('Failed to save PDF record to database');
            echo json_encode(['success' => false, 'message' => 'Failed to save PDF record.']);
        }
    } else {
        debug_log('Failed to move uploaded file from temporary location');
        echo json_encode(['success' => false, 'message' => 'Failed to save PDF file.']);
    }
    
    exit;
}

// If we reach here, it's an invalid request
debug_log('Invalid request received');
echo json_encode(['success' => false, 'message' => 'Invalid request.']);