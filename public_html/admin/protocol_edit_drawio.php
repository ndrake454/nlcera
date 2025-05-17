<?php
/**
 * Protocol Editor with Draw.io Integration and Manual PDF Upload
 * 
 * This file provides a protocol editor that embeds Draw.io for visual protocol creation
 * with a manual PDF upload option for better mobile viewing
 * Place this file in: /admin/protocol_edit_drawio.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require login
require_login();

// Get protocol ID from query string
$protocol_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get protocol data
$protocol = get_protocol($protocol_id);

// Check if protocol exists
if (!$protocol) {
    set_flash_message('error', 'Protocol not found.');
    header('Location: protocols.php');
    exit;
}

// Set page title
$page_title = 'Edit Protocol: ' . $protocol['protocol_number'] . '. ' . $protocol['title'];

// Get all categories for dropdown
$categories = get_all_categories();

// Process info form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_info') {
    $category_id = intval($_POST['category_id'] ?? 0);
    $protocol_number = $_POST['protocol_number'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $errors = [];
    
    // Validate required fields
    if ($category_id <= 0) {
        $errors[] = 'Category is required.';
    }
    
    if (empty($protocol_number)) {
        $errors[] = 'Protocol number is required.';
    } elseif (!preg_match('/^\d{4}(\.\d+)?$/', $protocol_number)) {
        $errors[] = 'Protocol number must be in format: 2030 or 2030.1';
    }
    
    if (empty($title)) {
        $errors[] = 'Title is required.';
    }
    
    // Check if protocol number already exists (excluding this protocol)
    $existing = db_get_row(
        "SELECT * FROM protocols WHERE protocol_number = ? AND id != ?",
        [$protocol_number, $protocol_id]
    );
    
    if ($existing) {
        $errors[] = 'A protocol with this number already exists.';
    }
    
    // If no errors, update the protocol
    if (empty($errors)) {
        $user_id = get_current_user_id();
        
        $data = [
            'category_id' => $category_id,
            'protocol_number' => $protocol_number,
            'title' => $title,
            'description' => $description,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ];
        
        $result = db_update('protocols', $data, 'id = ?', [$protocol_id]);
        
        if ($result !== false) {
            set_flash_message('success', 'Protocol information updated successfully.');
            // Reload the page to reflect changes
            header('Location: protocol_edit_drawio.php?id=' . $protocol_id);
            exit;
        } else {
            $errors[] = 'Failed to update protocol.';
        }
    }
}

// Process diagram save (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_diagram') {
    header('Content-Type: application/json');
    
    $diagram_xml = $_POST['diagram_xml'] ?? '';
    $diagram_html = $_POST['diagram_html'] ?? '';
    $user_id = get_current_user_id();
    
    // Check if there's an existing diagram
    $existing_diagram = db_get_row(
        "SELECT id FROM protocol_diagrams WHERE protocol_id = ?",
        [$protocol_id]
    );
    
    if ($existing_diagram) {
        // Update existing diagram
        $result = db_update(
            'protocol_diagrams',
            [
                'xml_content' => $diagram_xml,
                'html_content' => $diagram_html,
                'updated_by' => $user_id
            ],
            'id = ?',
            [$existing_diagram['id']]
        );
    } else {
        // Insert new diagram
        $result = db_insert(
            'protocol_diagrams',
            [
                'protocol_id' => $protocol_id,
                'xml_content' => $diagram_xml,
                'html_content' => $diagram_html,
                'created_by' => $user_id,
                'updated_by' => $user_id
            ]
        );
    }
    
    if ($result !== false) {
        echo json_encode(['success' => true, 'message' => 'Diagram saved successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save diagram.']);
    }
    exit;
}

// Process PDF upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload_pdf') {
    // Check if PDF file was uploaded
    if (!isset($_FILES['pdf_file']) || $_FILES['pdf_file']['error'] !== UPLOAD_ERR_OK) {
        $error_message = 'No PDF file uploaded or upload error.';
        if (isset($_FILES['pdf_file']['error'])) {
            switch ($_FILES['pdf_file']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $error_message = 'The uploaded file exceeds the upload_max_filesize directive in php.ini.';
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $error_message = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $error_message = 'The uploaded file was only partially uploaded.';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error_message = 'No file was uploaded.';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $error_message = 'Missing a temporary folder.';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $error_message = 'Failed to write file to disk.';
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $error_message = 'A PHP extension stopped the file upload.';
                    break;
                default:
                    $error_message = 'Unknown error code: ' . $_FILES['pdf_file']['error'];
            }
        }
        
        set_flash_message('error', $error_message);
        header('Location: protocol_edit_drawio.php?id=' . $protocol_id);
        exit;
    }
    
    // Validate file type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($_FILES['pdf_file']['tmp_name']);
    $allowed_types = ['application/pdf', 'application/x-pdf', 'application/octet-stream'];
    
    if (!in_array($mime_type, $allowed_types)) {
        // Additional check: if file starts with %PDF, it's likely a PDF regardless of MIME type
        $file_start = file_get_contents($_FILES['pdf_file']['tmp_name'], false, null, 0, 5);
        if ($file_start !== '%PDF-') {
            set_flash_message('error', 'Invalid file type. Only PDF files are allowed.');
            header('Location: protocol_edit_drawio.php?id=' . $protocol_id);
            exit;
        }
    }
    
    // Create upload directory if it doesn't exist
    $upload_dir = dirname(__DIR__) . '/assets/diagrams/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generate a unique filename
    $filename = 'diagram_' . $protocol_id . '_' . uniqid() . '.pdf';
    $upload_path = $upload_dir . $filename;
    
    // Move the uploaded file
    if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $upload_path)) {
        $user_id = get_current_user_id();
        
        // Check if there's an existing PDF record
        $existing_pdf = db_get_row(
            "SELECT id, filename FROM protocol_diagrams_pdf WHERE protocol_id = ?",
            [$protocol_id]
        );
        
        // Delete old PDF file if exists
        if ($existing_pdf && !empty($existing_pdf['filename'])) {
            $old_file = $upload_dir . $existing_pdf['filename'];
            if (file_exists($old_file)) {
                unlink($old_file);
            }
        }
        
        if ($existing_pdf) {
            // Update existing record
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
            set_flash_message('success', 'PDF uploaded successfully.');
        } else {
            set_flash_message('error', 'Failed to update PDF record.');
        }
    } else {
        set_flash_message('error', 'Failed to save PDF file.');
    }
    
    header('Location: protocol_edit_drawio.php?id=' . $protocol_id);
    exit;
}

// Get existing diagram if any
$diagram = db_get_row(
    "SELECT * FROM protocol_diagrams WHERE protocol_id = ?",
    [$protocol_id]
);

// Get existing PDF diagram if any
$pdf_diagram = db_get_row(
    "SELECT * FROM protocol_diagrams_pdf WHERE protocol_id = ?",
    [$protocol_id]
);

// Include header
include 'includes/header.php';
?>

<style>
    /* Make the editor take up most of the screen */
    #editor-container {
        width: 100%;
        height: calc(100vh - 250px);
        min-height: 600px;
        border: 1px solid #dee2e6;
        position: relative;
    }
    
    #drawio-editor {
        width: 100%;
        height: 100%;
        border: none;
    }
    
    .modal-xl {
        max-width: 95%;
    }
    
    .btn-floating {
        position: absolute;
        bottom: 20px;
        right: 20px;
        z-index: 100;
    }

    .btn-loading {
        position: relative;
    }
    
    .btn-loading .spinner-border {
        position: absolute;
        left: 50%;
        top: 50%;
        margin-left: -12px;
        margin-top: -12px;
    }
    
    /* PDF preview styles */
    .pdf-preview-container {
        width: 100%;
        height: 300px;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        overflow: hidden;
        margin-bottom: 1rem;
    }
    
    .pdf-preview-iframe {
        width: 100%;
        height: 100%;
        border: none;
    }
</style>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Protocol Information</h5>
        <div>
            <a href="../protocol.php?id=<?= $protocol_id ?>" target="_blank" class="btn btn-outline-secondary me-2">
                <i class="ti ti-eye"></i> View Protocol
            </a>
            <a href="protocols.php" class="btn btn-secondary">
                <i class="ti ti-arrow-left"></i> Back to Protocols
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="action" value="update_info">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $protocol['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                <?= $category['category_number'] ?>. <?= $category['title'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label for="protocol_number" class="form-label">Protocol Number</label>
                    <input type="text" class="form-control" id="protocol_number" name="protocol_number" 
                           value="<?= $protocol['protocol_number'] ?>" 
                           placeholder="e.g., 2030" required pattern="\d{4}(\.\d+)?">
                    <div class="form-text">Format: 2030 or 2030.1</div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?= $protocol['title'] ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description (optional)</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= $protocol['description'] ?></textarea>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?= $protocol['is_active'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Active (visible to users)</label>
            </div>
            
            <div class="d-flex justify-content-between">
                <small class="text-muted">
                    Last updated: <?= format_datetime($protocol['updated_at']) ?>
                </small>
                <button type="submit" class="btn btn-primary">Update Protocol Info</button>
            </div>
        </form>
    </div>
</div>

<!-- Protocol PDF Upload -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Protocol Diagram PDF</h5>
    </div>
    
    <div class="card-body">
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            Upload a PDF version of your protocol diagram for better mobile viewing.
            <strong>TIP:</strong> Use the Draw.io editor below to design your diagram, then export as PDF and upload it here.
        </div>
        
        <!-- Show PDF preview if available -->
        <?php if ($pdf_diagram && !empty($pdf_diagram['filename'])): ?>
            <div class="alert alert-success mb-3">
                <i class="ti ti-check me-2"></i>
                PDF diagram is available. This is how users will view your protocol.
            </div>
            
            <div class="pdf-preview-container mb-3">
                <iframe src="/assets/diagrams/<?= $pdf_diagram['filename'] ?>" class="pdf-preview-iframe"></iframe>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload_pdf">
            
            <div class="mb-3">
                <label for="pdf_file" class="form-label">Upload PDF Diagram</label>
                <input type="file" class="form-control" id="pdf_file" name="pdf_file" accept="application/pdf">
                <div class="form-text">
                    To create a PDF from the Draw.io editor below:
                    <ol class="mt-1 mb-0">
                        <li>Edit your diagram in the editor</li>
                        <li>Click "File" > "Export as" > "PDF..." in the editor menu</li>
                        <li>Save the PDF to your computer</li>
                        <li>Upload it here using this form</li>
                    </ol>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="ti ti-upload"></i> Upload PDF
            </button>
        </form>
    </div>
</div>

<!-- Protocol Editor -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Protocol Flowchart Editor (Draw.io)</h5>
    </div>
    
    <div class="card-body">
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            Use the Draw.io editor below to create your protocol flowchart. Click "Save XML" when you're done to save your editable diagram.
            <br>
            <strong>Note:</strong> After designing your diagram, export it as PDF and upload it above for mobile-friendly viewing.
        </div>
        
        <div class="mb-3">
            <button type="button" class="btn btn-primary" id="save-diagram-btn">
                <i class="ti ti-device-floppy"></i> Save XML
            </button>
            <button type="button" class="btn btn-outline-secondary" id="show-diagram-xml-btn">
                <i class="ti ti-code"></i> Show XML
            </button>
            <button type="button" class="btn btn-outline-primary" id="load-template-btn">
                <i class="ti ti-template"></i> Load Template
            </button>
        </div>
        
        <div id="editor-container">
            <!-- The Draw.io editor will be loaded here -->
            <div id="loading-indicator" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Loading Draw.io editor...</p>
            </div>
        </div>
    </div>
</div>

<!-- XML Modal -->
<div class="modal fade" id="xml-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Diagram XML</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea id="diagram-xml" class="form-control" rows="15" readonly></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="copy-xml-btn">
                    <i class="ti ti-copy"></i> Copy to Clipboard
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Template Modal -->
<div class="modal fade" id="template-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Load Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="template-select" class="form-label">Select Template</label>
                    <select class="form-select" id="template-select">
                        <option value="basic">Basic Protocol</option>
                        <option value="medical">Medical Protocol</option>
                        <option value="trauma">Trauma Protocol</option>
                        <option value="medication">Medication Protocol</option>
                    </select>
                </div>
                <div class="alert alert-warning">
                    <i class="ti ti-alert-triangle me-2"></i>
                    Loading a template will replace your current diagram. Make sure to save your work first.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="load-selected-template-btn">Load Template</button>
            </div>
        </div>
    </div>
</div>

<script>
// Simplified diagram editor script with XML saving only
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    let editor = null;
    let editorReady = false;
    let diagramXml = null;
    let editorLoaded = false;
    
    // Load the Draw.io editor
    initDrawioEditor();
    
    // Listen for messages from the Draw.io iframe
    window.addEventListener('message', function(evt) {
        if (typeof evt.data === 'string' && evt.data.length > 0) {
            try {
                const msg = JSON.parse(evt.data);
                console.log('Received message from Draw.io:', msg.event);
                
                // Handle ready event
                if (msg.event === 'init') {
                    editorReady = true;
                    editorLoaded = true;
                    
                    // Hide loading indicator
                    const loading = document.getElementById('loading-indicator');
                    if (loading) {
                        loading.style.display = 'none';
                    }
                    
                    // Check if we have an existing diagram to load
                    <?php if ($diagram && !empty($diagram['xml_content'])): ?>
                    setTimeout(function() {
                        console.log('Loading existing diagram');
                        loadDiagram(`<?= str_replace('"', '\\"', str_replace("\n", "\\n", $diagram['xml_content'])) ?>`);
                    }, 1000); // Slight delay to ensure editor is fully initialized
                    <?php else: ?>
                    console.log('No existing diagram, editor ready for new content');
                    <?php endif; ?>
                }
                
                // Handle export event
                if (msg.event === 'export') {
                    console.log('Export event received from Draw.io:', msg.format);
                    
                    // Handle XML export
                    if (msg.format === 'xml' && msg.xml) {
                        console.log('Got XML content, length:', msg.xml.length);
                        // Store the XML data
                        diagramXml = msg.xml;
                        
                        // If this was triggered by save button, save to server
                        if (msg.extras && msg.extras.action === 'save') {
                            console.log('Saving diagram to server...');
                            saveDiagram(diagramXml);
                        } 
                        // If this was for XML display
                        else if (msg.extras && msg.extras.action === 'showXml') {
                            document.getElementById('diagram-xml').value = diagramXml;
                            const xmlModal = new bootstrap.Modal(document.getElementById('xml-modal'));
                            xmlModal.show();
                        }
                    }
                    // Handle SVG export for XML
                    else if (msg.format === 'xmlsvg' && msg.xml) {
                        console.log('Got XML+SVG content');
                        diagramXml = msg.xml;
                        
                        if (msg.extras && msg.extras.action === 'showXml') {
                            document.getElementById('diagram-xml').value = diagramXml;
                            const xmlModal = new bootstrap.Modal(document.getElementById('xml-modal'));
                            xmlModal.show();
                        }
                    }
                }
                
                // Handle save event (direct save from editor)
                if (msg.event === 'save') {
                    console.log('Save event received from Draw.io');
                    if (msg.xml) {
                        console.log('Got XML from save event');
                        diagramXml = msg.xml;
                        saveDiagram(diagramXml);
                    } else {
                        console.log('Save event missing XML content');
                    }
                }
                
                // Handle error events
                if (msg.event === 'error') {
                    console.error('Error from Draw.io:', msg.message);
                    alert('Error from diagram editor: ' + msg.message);
                }
            } catch (e) {
                console.error('Error processing message from Draw.io:', e);
            }
        }
    });
    
    // Save Diagram Button Click (XML only)
    document.getElementById('save-diagram-btn').addEventListener('click', function() {
        console.log('Save XML button clicked');
        
        // Show saving indicator
        const btn = document.getElementById('save-diagram-btn');
        const originalBtnText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
        btn.disabled = true;
        
        // Get the diagram directly from the iframe
        const iframe = document.getElementById('drawio-editor');
        
        if (iframe && iframe.contentWindow) {
            try {
                // Request the XML for editing
                iframe.contentWindow.postMessage(JSON.stringify({
                    action: 'export',
                    format: 'xml',
                    spin: 'Saving diagram...',
                    extras: {
                        action: 'save'
                    }
                }), '*');
                
                // Make the message event handler a named function so we can remove it later
                function saveMessageHandler(evt) {
                    if (typeof evt.data === 'string' && evt.data.length > 0) {
                        try {
                            const msg = JSON.parse(evt.data);
                            
                            // Handle export event
                            if (msg.event === 'export' && msg.format === 'xml' && msg.extras && msg.extras.action === 'save') {
                                // Remove this listener to avoid multiple calls
                                window.removeEventListener('message', saveMessageHandler);
                                
                                console.log('Got XML content, length:', msg.xml ? msg.xml.length : 0);
                                
                                if (!msg.xml) {
                                    console.error('No XML content in response');
                                    alert('Failed to get diagram XML. Please try again.');
                                    btn.innerHTML = originalBtnText;
                                    btn.disabled = false;
                                    return;
                                }
                                
                                // Save the XML content
                                const formData = new FormData();
                                formData.append('action', 'save_diagram');
                                formData.append('diagram_xml', msg.xml);
                                
                                // Request the HTML for embedded viewing
                                iframe.contentWindow.postMessage(JSON.stringify({
                                    action: 'export',
                                    format: 'html',
                                    embedImages: true,
                                    spin: 'Generating HTML...'
                                }), '*');
                                
                                // Listen for HTML export response
                                window.addEventListener('message', function htmlHandler(evt) {
                                    if (typeof evt.data === 'string' && evt.data.length > 0) {
                                        try {
                                            const htmlMsg = JSON.parse(evt.data);
                                            
                                            if (htmlMsg.event === 'export' && htmlMsg.format === 'html') {
                                                // Remove this listener
                                                window.removeEventListener('message', htmlHandler);
                                                
                                                if (htmlMsg.data) {
                                                    // Add HTML to the form data
                                                    formData.append('diagram_html', htmlMsg.data);
                                                }
                                                
                                                // Send AJAX request to save XML and HTML
                                                fetch('protocol_edit_drawio.php?id=<?= $protocol_id ?>', {
                                                    method: 'POST',
                                                    body: formData
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        alert('Diagram saved successfully!\n\nRemember to export as PDF and upload it using the form above for mobile-friendly viewing.');
                                                    } else {
                                                        alert('Failed to save diagram: ' + data.message);
                                                    }
                                                    
                                                    // Reset button
                                                    btn.innerHTML = originalBtnText;
                                                    btn.disabled = false;
                                                })
                                                .catch(error => {
                                                    console.error('Error saving diagram:', error);
                                                    alert('Failed to save diagram. Please try again.');
                                                    
                                                    // Reset button
                                                    btn.innerHTML = originalBtnText;
                                                    btn.disabled = false;
                                                });
                                            }
                                        } catch (e) {
                                            console.error('Error processing HTML message:', e);
                                        }
                                    }
                                }, {once: true});
                            }
                        } catch (e) {
                            console.error('Error processing message:', e);
                        }
                    }
                }
                
                // Add the message event listener
                window.addEventListener('message', saveMessageHandler);
                
            } catch (e) {
                console.error('Error requesting diagram export:', e);
                alert('Failed to save diagram. Please try again.');
                
                // Reset button
                btn.innerHTML = originalBtnText;
                btn.disabled = false;
            }
        } else {
            alert('Diagram editor not found. Please refresh the page and try again.');
            // Reset button
            btn.innerHTML = originalBtnText;
            btn.disabled = false;
        }
    });
    
    // Function to save diagram XML to server
    function saveDiagram(diagramXml) {
        if (!diagramXml) {
            console.error('No diagram XML to save');
            alert('Failed to get diagram content. Please try again.');
            return;
        }
        
        console.log('Preparing to save diagram, XML length:', diagramXml.length);
        
        // Create form data for saving
        const formData = new FormData();
        formData.append('action', 'save_diagram');
        formData.append('diagram_xml', diagramXml);
        
        console.log('Sending save request to server');
        
        // Send AJAX request
        fetch('protocol_edit_drawio.php?id=<?= $protocol_id ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Received response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Save response:', data);
            if (data.success) {
                alert('Diagram saved successfully!\n\nRemember to export as PDF and upload it using the form above for mobile-friendly viewing.');
            } else {
                alert('Failed to save diagram: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error saving diagram:', error);
            alert('Failed to save diagram. Please try again.');
        });
    }
    
    // Show XML Button Click
    document.getElementById('show-diagram-xml-btn').addEventListener('click', function() {
        console.log('Show XML button clicked');
        exportXml('showXml');
    });
    
    // Copy XML Button Click
    document.getElementById('copy-xml-btn').addEventListener('click', function() {
        const textarea = document.getElementById('diagram-xml');
        textarea.select();
        document.execCommand('copy');
        alert('XML copied to clipboard!');
    });
    
    // Load Template Button Click
    document.getElementById('load-template-btn').addEventListener('click', function() {
        const templateModal = new bootstrap.Modal(document.getElementById('template-modal'));
        templateModal.show();
    });
    
    // Load Selected Template Button Click
    document.getElementById('load-selected-template-btn').addEventListener('click', function() {
        const templateSelect = document.getElementById('template-select');
        const selectedTemplate = templateSelect.value;
        
        // Load the selected template
        loadTemplate(selectedTemplate);
        
        // Close the modal
        bootstrap.Modal.getInstance(document.getElementById('template-modal')).hide();
    });
    
    // Function to initialize the Draw.io editor
    function initDrawioEditor() {
        console.log('Initializing Draw.io editor');
        const container = document.getElementById('editor-container');
        
        // Make sure container exists
        if (!container) {
            console.error('Editor container not found');
            return;
        }
        
        // Create iframe for the editor
        const iframe = document.createElement('iframe');
        iframe.id = 'drawio-editor';
        
        // Important! Set these attributes before setting the src
        iframe.setAttribute('frameborder', '0');
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        
        // Clear the container and show loading
        container.innerHTML = `
            <div id="loading-indicator" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3">Loading Draw.io editor...</p>
            </div>
        `;
        
        // Append iframe after showing loading
        container.appendChild(iframe);
        
        // Set the source last to trigger the load
        iframe.src = 'https://embed.diagrams.net/?embed=1&ui=atlas&spin=1&proto=json';
        
        // Store reference to the editor
        editor = iframe;
        console.log('Editor iframe created');
    }
    
    // Function to export diagram as XML
    function exportXml(action = 'save') {
        console.log('Exporting XML with action:', action);
        
        if (!editor || !editorReady) {
            alert('Editor is not ready yet. Please try again in a moment.');
            return;
        }
        
        try {
            // Send message to the editor
            editor.contentWindow.postMessage(JSON.stringify({
                action: 'export',
                format: 'xmlsvg',
                xml: true,
                spin: 'Exporting diagram',
                extras: {
                    action: action
                }
            }), '*');
        } catch (e) {
            console.error('Error exporting XML:', e);
            alert('Failed to export diagram. Please try again.');
        }
    }
    
    // Function to load existing diagram
    function loadDiagram(xml) {
        console.log('Loading diagram into editor');
        if (!editor || !editorReady) {
            console.error('Editor not ready for loading diagram');
            return;
        }
        
        try {
            // Load the XML content into the editor
            editor.contentWindow.postMessage(JSON.stringify({
                action: 'load',
                xml: xml
            }), '*');
            console.log('Sent load command to editor');
        } catch (e) {
            console.error('Error loading diagram:', e);
        }
    }
    
    // Function to load a template
    function loadTemplate(templateName) {
        console.log('Loading template:', templateName);
        if (!editor || !editorReady) {
            alert('Editor is not ready yet. Please try again in a moment.');
            return;
        }
        
        // Define templates - these should match the ones in the SQL file
        const templates = {
            basic: getBasicTemplate(),
            medical: getMedicalTemplate(),
            trauma: getTraumaTemplate(),
            medication: getMedicationTemplate()
        };
        
        try {
            // Load the selected template into the editor
            editor.contentWindow.postMessage(JSON.stringify({
                action: 'load',
                xml: templates[templateName]
            }), '*');
            console.log('Template loaded into editor');
        } catch (e) {
            console.error('Error loading template:', e);
            alert('Failed to load template. Please try again.');
        }
    }
    
    // Helper function to escape HTML for safe insertion
    function escapeHtml(html) {
        const div = document.createElement('div');
        div.textContent = html;
        return div.innerHTML;
    }
    
    // Define template XML
    function getBasicTemplate() {
        return `<mxfile host="app.diagrams.net" modified="2023-04-20T12:00:00.000Z" agent="Mozilla/5.0" version="21.0.10" etag="your_etag" type="device">
  <diagram id="your_id" name="Basic Protocol">
    <mxGraphModel dx="1422" dy="762" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="850" pageHeight="1100" math="0" shadow="0">
      <root>
        <mxCell id="0" />
        <mxCell id="1" parent="0" />
        <mxCell id="2" value="Protocol Start" style="rounded=1;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;fontStyle=1" vertex="1" parent="1">
          <mxGeometry x="320" y="80" width="160" height="60" as="geometry" />
        </mxCell>
        <mxCell id="3" value="Step 1: Initial Assessment" style="rounded=0;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1">
          <mxGeometry x="320" y="180" width="160" height="60" as="geometry" />
        </mxCell>
        <mxCell id="4" value="Decision Point" style="rhombus;whiteSpace=wrap;html=1;fillColor=#fff2cc;strokeColor=#d6b656;" vertex="1" parent="1">
          <mxGeometry x="320" y="280" width="160" height="80" as="geometry" />
        </mxCell>
        <mxCell id="5" value="Option A" style="rounded=0;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1">
          <mxGeometry x="160" y="400" width="160" height="60" as="geometry" />
        </mxCell>
        <mxCell id="6" value="Option B" style="rounded=0;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1">
          <mxGeometry x="480" y="400" width="160" height="60" as="geometry" />
        </mxCell>
        <mxCell id="7" value="Protocol End" style="rounded=1;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;fontStyle=1" vertex="1" parent="1">
          <mxGeometry x="320" y="520" width="160" height="60" as="geometry" />
        </mxCell>
        <mxCell id="8" value="" style="endArrow=classic;html=1;rounded=0;exitX=0.5;exitY=1;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;" edge="1" parent="1" source="2" target="3">
          <mxGeometry width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
          </mxGeometry>
        </mxCell>
        <mxCell id="9" value="" style="endArrow=classic;html=1;rounded=0;exitX=0.5;exitY=1;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;" edge="1" parent="1" source="3" target="4">
          <mxGeometry width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
          </mxGeometry>
        </mxCell>
        <mxCell id="10" value="Yes" style="endArrow=classic;html=1;rounded=0;exitX=0;exitY=0.5;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="4" target="5">
          <mxGeometry x="-0.2" y="-10" width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
            <mxPoint as="offset" />
          </mxGeometry>
        </mxCell>
        <mxCell id="11" value="No" style="endArrow=classic;html=1;rounded=0;exitX=1;exitY=0.5;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="4" target="6">
          <mxGeometry x="-0.2" y="10" width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
            <mxPoint as="offset" />
          </mxGeometry>
        </mxCell>
        <mxCell id="12" value="" style="endArrow=classic;html=1;rounded=0;exitX=0.5;exitY=1;exitDx=0;exitDy=0;entryX=0;entryY=0.5;entryDx=0;entryDy=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="5" target="7">
          <mxGeometry width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
          </mxGeometry>
        </mxCell>
        <mxCell id="13" value="" style="endArrow=classic;html=1;rounded=0;exitX=0.5;exitY=1;exitDx=0;exitDy=0;entryX=1;entryY=0.5;entryDx=0;entryDy=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="6" target="7">
          <mxGeometry width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
          </mxGeometry>
        </mxCell>
      </root>
    </mxGraphModel>
  </diagram>
</mxfile>`;
    }
    
    function getMedicalTemplate() {
        return `<mxfile host="app.diagrams.net" modified="2023-04-20T12:00:00.000Z" agent="Mozilla/5.0" version="21.0.10" etag="your_etag" type="device">
  <diagram id="your_id" name="Medical Protocol">
    <mxGraphModel dx="1422" dy="762" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="850" pageHeight="1100" math="0" shadow="0">
      <root>
        <mxCell id="0" />
        <mxCell id="1" parent="0" />
        <mxCell id="2" value="Medical Emergency" style="rounded=1;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;fontStyle=1" vertex="1" parent="1">
          <mxGeometry x="320" y="80" width="160" height="60" as="geometry" />
        </mxCell>
        <mxCell id="3" value="Primary Assessment&#xa;ABCDE" style="rounded=0;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1">
          <mxGeometry x="320" y="180" width="160" height="60" as="geometry" />
        </mxCell>
        <mxCell id="4" value="Life-threatening condition?" style="rhombus;whiteSpace=wrap;html=1;fillColor=#fff2cc;strokeColor=#d6b656;" vertex="1" parent="1">
          <mxGeometry x="320" y="280" width="160" height="80" as="geometry" />
        </mxCell>
        <mxCell id="5" value="Immediate Intervention&#xa;- Airway management&#xa;- Oxygen&#xa;- IV access" style="rounded=0;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;" vertex="1" parent="1">
          <mxGeometry x="160" y="400" width="160" height="80" as="geometry" />
        </mxCell>
        <mxCell id="6" value="Secondary Assessment&#xa;- Vital signs&#xa;- SAMPLE history&#xa;- Focused exam" style="rounded=0;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1">
          <mxGeometry x="480" y="400" width="160" height="80" as="geometry" />
        </mxCell>
        <mxCell id="7" value="Contact Medical Control" style="rounded=0;whiteSpace=wrap;html=1;fillColor=#e1d5e7;strokeColor=#9673a6;" vertex="1" parent="1">
          <mxGeometry x="320" y="520" width="160" height="60" as="geometry" />
        </mxCell>
        <mxCell id="8" value="Transport" style="rounded=1;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;fontStyle=1" vertex="1" parent="1">
          <mxGeometry x="320" y="620" width="160" height="60" as="geometry" />
        </mxCell>
        <mxCell id="9" value="" style="endArrow=classic;html=1;rounded=0;exitX=0.5;exitY=1;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;" edge="1" parent="1" source="2" target="3">
          <mxGeometry width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
          </mxGeometry>
        </mxCell>
        <mxCell id="10" value="" style="endArrow=classic;html=1;rounded=0;exitX=0.5;exitY=1;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;" edge="1" parent="1" source="3" target="4">
          <mxGeometry width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
          </mxGeometry>
        </mxCell>
        <mxCell id="11" value="Yes" style="endArrow=classic;html=1;rounded=0;exitX=0;exitY=0.5;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="4" target="5">
          <mxGeometry x="-0.2" y="-10" width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
            <mxPoint as="offset" />
          </mxGeometry>
        </mxCell>
        <mxCell id="12" value="No" style="endArrow=classic;html=1;rounded=0;exitX=1;exitY=0.5;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="4" target="6">
          <mxGeometry x="-0.2" y="10" width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
            <mxPoint as="offset" />
          </mxGeometry>
        </mxCell>
        <mxCell id="13" value="" style="endArrow=classic;html=1;rounded=0;exitX=0.5;exitY=1;exitDx=0;exitDy=0;entryX=0;entryY=0.5;entryDx=0;entryDy=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="5" target="7">
          <mxGeometry width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
          </mxGeometry>
        </mxCell>
        <mxCell id="14" value="" style="endArrow=classic;html=1;rounded=0;exitX=0.5;exitY=1;exitDx=0;exitDy=0;entryX=1;entryY=0.5;entryDx=0;entryDy=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="6" target="7">
          <mxGeometry width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
          </mxGeometry>
        </mxCell>
        <mxCell id="15" value="" style="endArrow=classic;html=1;rounded=0;exitX=0.5;exitY=1;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;" edge="1" parent="1" source="7" target="8">
          <mxGeometry width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
          </mxGeometry>
        </mxCell>
      </root>
    </mxGraphModel>
  </diagram>
</mxfile>`;
    }
    
    function getTraumaTemplate() {
        return `<mxfile host="app.diagrams.net" modified="2023-04-20T12:00:00.000Z" agent="Mozilla/5.0" version="21.0.10" etag="your_etag" type="device">
  <diagram id="your_id" name="Trauma Protocol">
    <mxGraphModel dx="1422" dy="762" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="850" pageHeight="1100" math="0" shadow="0">
      <root>
        <mxCell id="0" />
        <mxCell id="1" parent="0" />
        <mxCell id="2" value="Trauma Patient" style="rounded=1;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;fontStyle=1" vertex="1" parent="1">
          <mxGeometry x="320" y="80" width="160" height="60" as="geometry" />
        </mxCell>
        <mxCell id="3" value="Scene Safety &amp; BSI" style="rounded=0;whiteSpace=wrap;html=1;fillColor=#f5f5f5;strokeColor=#666666;fontColor=#333333;" vertex="1" parent="1">
          <mxGeometry x="320" y="180" width="160" height="60" as="geometry" />
        </mxCell>
        <mxCell id="4" value="Primary Survey&#xa;ABCDE" style="rounded=0;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1">
          <mxGeometry x="320" y="280" width="160" height="60" as="geometry" />
        </mxCell>
        <mxCell id="5" value="Critical Trauma?" style="rhombus;whiteSpace=wrap;html=1;fillColor=#fff2cc;strokeColor=#d6b656;" vertex="1" parent="1">
          <mxGeometry x="320" y="380" width="160" height="80" as="geometry" />
        </mxCell>
        <mxCell id="6" value="Rapid Trauma Assessment&#xa;- Control bleeding&#xa;- Immobilize as needed&#xa;- IV access" style="rounded=0;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;" vertex="1" parent="1">
          <mxGeometry x="160" y="500" width="160" height="80" as="geometry" />
        </mxCell>
        <mxCell id="7" value="Detailed Assessment&#xa;- Vital signs&#xa;- SAMPLE history&#xa;- Secondary survey" style="rounded=0;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1">
          <mxGeometry x="480" y="500" width="160" height="80" as="geometry" />
        </mxCell>
        <mxCell id="8" value="Meets Trauma&#xa;Center Criteria?" style="rhombus;whiteSpace=wrap;html=1;fillColor=#fff2cc;strokeColor=#d6b656;" vertex="1" parent="1">
          <mxGeometry x="320" y="620" width="160" height="80" as="geometry" />
        </mxCell>
        <mxCell id="9" value="Trauma Center Transport" style="rounded=1;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;fontStyle=1" vertex="1" parent="1">
          <mxGeometry x="160" y="740" width="160" height="60" as="geometry" />
        </mxCell>
        <mxCell id="10" value="Appropriate Facility&#xa;Transport" style="rounded=1;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;fontStyle=1" vertex="1" parent="1">
          <mxGeometry x="480" y="740" width="160" height="60" as="geometry" />
        </mxCell>
        <mxCell id="11" value="" style="endArrow=classic;html=1;rounded=0;exitX=0.5;exitY=1;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;" edge="1" parent="1" source="2" target="3">
          <mxGeometry width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
          </mxGeometry>
        </mxCell>
        <mxCell id="12" value="" style="endArrow=classic;html=1;rounded=0;exitX=0.5;exitY=1;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;" edge="1" parent="1" source="3" target="4">
          <mxGeometry width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
          </mxGeometry>
        </mxCell>
        <mxCell id="13" value="" style="endArrow=classic;html=1;rounded=0;exitX=0.5;exitY=1;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;" edge="1" parent="1" source="4" target="5">
          <mxGeometry width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
          </mxGeometry>
        </mxCell>
        <mxCell id="14" value="Yes" style="endArrow=classic;html=1;rounded=0;exitX=0;exitY=0.5;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="5" target="6">
          <mxGeometry x="-0.2" y="-10" width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
            <mxPoint as="offset" />
          </mxGeometry>
        </mxCell>
        <mxCell id="15" value="No" style="endArrow=classic;html=1;rounded=0;exitX=1;exitY=0.5;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="5" target="7">
          <mxGeometry x="-0.2" y="10" width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
            <mxPoint as="offset" />
          </mxGeometry>
        </mxCell>
        <mxCell id="16" value="" style="endArrow=classic;html=1;rounded=0;exitX=0.5;exitY=1;exitDx=0;exitDy=0;entryX=0;entryY=0.5;entryDx=0;entryDy=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="6" target="8">
          <mxGeometry width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
          </mxGeometry>
        </mxCell>
        <mxCell id="17" value="" style="endArrow=classic;html=1;rounded=0;exitX=0.5;exitY=1;exitDx=0;exitDy=0;entryX=1;entryY=0.5;entryDx=0;entryDy=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="7" target="8">
          <mxGeometry width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
          </mxGeometry>
        </mxCell>
        <mxCell id="18" value="Yes" style="endArrow=classic;html=1;rounded=0;exitX=0;exitY=0.5;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="8" target="9">
          <mxGeometry x="-0.2" y="-10" width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
            <mxPoint as="offset" />
          </mxGeometry>
        </mxCell>
        <mxCell id="19" value="No" style="endArrow=classic;html=1;rounded=0;exitX=1;exitY=0.5;exitDx=0;exitDy=0;entryX=0.5;entryY=0;entryDx=0;entryDy=0;edgeStyle=orthogonalEdgeStyle;" edge="1" parent="1" source="8" target="10">
          <mxGeometry x="-0.2" y="10" width="50" height="50" relative="1" as="geometry">
            <mxPoint x="400" y="390" as="sourcePoint" />
            <mxPoint x="450" y="340" as="targetPoint" />
            <mxPoint as="offset" />
          </mxGeometry>
        </mxCell>
        <mxCell id="20" value="&lt;i&gt;Note: Consider air medical transport for critical patients with long transport times&lt;/i&gt;" style="text;html=1;strokeColor=none;fillColor=none;align=center;verticalAlign=middle;whiteSpace=wrap;rounded=0;fontColor=#666666;" vertex="1" parent="1">
          <mxGeometry x="240" y="810" width="320" height="30" as="geometry" />
        </mxCell>
      </root>
    </mxGraphModel>
  </diagram>
</mxfile>`;
    }
    
    function getMedicationTemplate() {
        return `<mxfile host="app.diagrams.net" modified="2023-04-20T12:00:00.000Z" agent="Mozilla/5.0" version="21.0.10" etag="your_etag" type="device">
  <diagram id="your_id" name="Medication Protocol">
    <mxGraphModel dx="1422" dy="762" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="850" pageHeight="1100" math="0" shadow="0">
      <root>
        <mxCell id="0" />
        <mxCell id="1" parent="0" />
        <mxCell id="2" value="Medication Name" style="rounded=1;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;fontStyle=1;fontSize=14" vertex="1" parent="1">
          <mxGeometry x="260" y="80" width="280" height="60" as="geometry" />
        </mxCell>
        <mxCell id="3" value="Class / Category" style="text;html=1;strokeColor=none;fillColor=none;align=center;verticalAlign=middle;whiteSpace=wrap;rounded=0;fontStyle=2" vertex="1" parent="1">
          <mxGeometry x="320" y="150" width="160" height="30" as="geometry" />
        </mxCell>
        <mxCell id="4" value="Indications" style="swimlane;fontStyle=1;childLayout=stackLayout;horizontal=1;startSize=30;horizontalStack=0;resizeParent=1;resizeParentMax=0;resizeLast=0;collapsible=0;marginBottom=0;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;" vertex="1" parent="1">
          <mxGeometry x="120" y="200" width="240" height="120" as="geometry" />
        </mxCell>
        <mxCell id="5" value="• Indication 1&#xa;• Indication 2&#xa;• Indication 3" style="text;strokeColor=none;fillColor=none;align=left;verticalAlign=middle;spacingLeft=4;spacingRight=4;overflow=hidden;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;rotatable=0;whiteSpace=wrap;html=1;" vertex="1" parent="4">
          <mxGeometry y="30" width="240" height="90" as="geometry" />
        </mxCell>
        <mxCell id="6" value="Contraindications" style="swimlane;fontStyle=1;childLayout=stackLayout;horizontal=1;startSize=30;horizontalStack=0;resizeParent=1;resizeParentMax=0;resizeLast=0;collapsible=0;marginBottom=0;whiteSpace=wrap;html=1;fillColor=#f8cecc;strokeColor=#b85450;" vertex="1" parent="1">
          <mxGeometry x="440" y="200" width="240" height="120" as="geometry" />
        </mxCell>
        <mxCell id="7" value="• Contraindication 1&#xa;• Contraindication 2&#xa;• Contraindication 3" style="text;strokeColor=none;fillColor=none;align=left;verticalAlign=middle;spacingLeft=4;spacingRight=4;overflow=hidden;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;rotatable=0;whiteSpace=wrap;html=1;" vertex="1" parent="6">
          <mxGeometry y="30" width="240" height="90" as="geometry" />
        </mxCell>
        <mxCell id="8" value="Dosage" style="swimlane;fontStyle=1;childLayout=stackLayout;horizontal=1;startSize=30;horizontalStack=0;resizeParent=1;resizeParentMax=0;resizeLast=0;collapsible=0;marginBottom=0;whiteSpace=wrap;html=1;fillColor=#d5e8d4;strokeColor=#82b366;" vertex="1" parent="1">
          <mxGeometry x="120" y="360" width="240" height="150" as="geometry" />
        </mxCell>
        <mxCell id="9" value="&lt;b&gt;Adult:&lt;/b&gt;&#xa;• Dose for condition 1&#xa;• Dose for condition 2&#xa;&#xa;&lt;b&gt;Pediatric:&lt;/b&gt;&#xa;• Pediatric dose calculation&#xa;• Maximum dose" style="text;strokeColor=none;fillColor=none;align=left;verticalAlign=middle;spacingLeft=4;spacingRight=4;overflow=hidden;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;rotatable=0;whiteSpace=wrap;html=1;" vertex="1" parent="8">
          <mxGeometry y="30" width="240" height="120" as="geometry" />
        </mxCell>
        <mxCell id="10" value="Side Effects" style="swimlane;fontStyle=1;childLayout=stackLayout;horizontal=1;startSize=30;horizontalStack=0;resizeParent=1;resizeParentMax=0;resizeLast=0;collapsible=0;marginBottom=0;whiteSpace=wrap;html=1;fillColor=#fff2cc;strokeColor=#d6b656;" vertex="1" parent="1">
          <mxGeometry x="440" y="360" width="240" height="150" as="geometry" />
        </mxCell>
        <mxCell id="11" value="&lt;b&gt;Common:&lt;/b&gt;&#xa;• Side effect 1&#xa;• Side effect 2&#xa;&#xa;&lt;b&gt;Serious:&lt;/b&gt;&#xa;• Serious side effect 1&#xa;• Serious side effect 2" style="text;strokeColor=none;fillColor=none;align=left;verticalAlign=middle;spacingLeft=4;spacingRight=4;overflow=hidden;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;rotatable=0;whiteSpace=wrap;html=1;" vertex="1" parent="10">
          <mxGeometry y="30" width="240" height="120" as="geometry" />
        </mxCell>
        <mxCell id="12" value="Special Considerations" style="swimlane;fontStyle=1;childLayout=stackLayout;horizontal=1;startSize=30;horizontalStack=0;resizeParent=1;resizeParentMax=0;resizeLast=0;collapsible=0;marginBottom=0;whiteSpace=wrap;html=1;fillColor=#e1d5e7;strokeColor=#9673a6;" vertex="1" parent="1">
          <mxGeometry x="120" y="550" width="560" height="120" as="geometry" />
        </mxCell>
        <mxCell id="13" value="• Monitor for [specific reactions]&#xa;• Use caution in patients with [specific conditions]&#xa;• Store medication at [storage conditions]&#xa;• Incompatible with [specific medications]" style="text;strokeColor=none;fillColor=none;align=left;verticalAlign=middle;spacingLeft=4;spacingRight=4;overflow=hidden;points=[[0,0.5],[1,0.5]];portConstraint=eastwest;rotatable=0;whiteSpace=wrap;html=1;" vertex="1" parent="12">
          <mxGeometry y="30" width="560" height="90" as="geometry" />
        </mxCell>
        <mxCell id="14" value="Protocol Approved By" style="text;html=1;strokeColor=none;fillColor=none;align=center;verticalAlign=middle;whiteSpace=wrap;rounded=0;fontStyle=1" vertex="1" parent="1">
          <mxGeometry x="320" y="690" width="160" height="30" as="geometry" />
        </mxCell>
        <mxCell id="15" value="Medical Director Name, MD" style="text;html=1;strokeColor=none;fillColor=none;align=center;verticalAlign=middle;whiteSpace=wrap;rounded=0;" vertex="1" parent="1">
          <mxGeometry x="320" y="720" width="160" height="30" as="geometry" />
        </mxCell>
      </root>
    </mxGraphModel>
  </diagram>
</mxfile>`;
    }
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?>