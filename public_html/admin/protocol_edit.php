<?php
/**
 * Simplified Protocol Editor Page
 * 
 * Place this file in: /admin/protocol_edit_simple.php
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

// Get protocol content
$content = '';
$content_id = 0;

// Check if there's existing content in the database
$protocol_content = db_get_row(
    "SELECT id, content FROM protocol_content WHERE protocol_id = ?",
    [$protocol_id]
);

if ($protocol_content) {
    $content = $protocol_content['content'];
    $content_id = $protocol_content['id'];
}

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
            header('Location: protocol_edit_simple.php?id=' . $protocol_id);
            exit;
        } else {
            $errors[] = 'Failed to update protocol.';
        }
    }
}

// Process content form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_content') {
    $new_content = $_POST['protocol_content'] ?? '';
    $user_id = get_current_user_id();
    
    // Check if content already exists for this protocol
    if ($content_id > 0) {
        // Update existing content
        $result = db_update(
            'protocol_content', 
            [
                'content' => $new_content, 
                'updated_by' => $user_id
            ], 
            'id = ?', 
            [$content_id]
        );
    } else {
        // Insert new content
        $result = db_insert(
            'protocol_content', 
            [
                'protocol_id' => $protocol_id,
                'content' => $new_content,
                'created_by' => $user_id,
                'updated_by' => $user_id
            ]
        );
        
        // Get the new content ID
        if ($result) {
            $content_id = $result;
        }
    }
    
    if ($result !== false) {
        // Update the content variable to show the latest content
        $content = $new_content;
        set_flash_message('success', 'Protocol content updated successfully.');
    } else {
        set_flash_message('error', 'Failed to update protocol content.');
    }
}

// Include header
include 'includes/header.php';
?>

<style>
    /* Protocol editor specific styles */
    .protocol-editor {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        overflow: hidden;
    }
    
    #editor-container {
        height: 600px;
    }
    
    .ql-editor {
        min-height: 500px;
        font-size: 1rem;
    }
    
    /* Styling for the editor toolbar */
    .editor-toolbar {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 10px;
    }
    
    /* Help text */
    .editor-help {
        background-color: #f0f7ff;
        border-left: 4px solid #0d6efd;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Protocol Information</h5>
        <div>
            <a href="../protocol.php?id=<?= $protocol_id ?>" target="_blank" class="btn btn-outline-secondary me-2">
                <i class="ti ti-eye"></i> View Protocol
            </a>
            <a href="protocol_edit_drawio.php?id=<?= $protocol_id ?>" class="btn btn-outline-primary me-2">
                <i class="ti ti-chart-line"></i> Edit Diagram
            </a>
            <a href="protocols.php" class="btn btn-secondary">
                <i class="ti ti-arrow-left"></i> Back to Protocols
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <?php if (isset($errors) && !empty($errors)): ?>
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

<!-- Protocol Editor -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Protocol Content Editor</h5>
    </div>
    
    <div class="card-body">
        <div class="editor-help mb-4">
            <h6><i class="ti ti-info-circle me-2"></i>Editor Tips</h6>
            <p>Use the toolbar to format your content. You can add headings, lists, tables, images, and more.</p>
            <ul>
                <li>Use headings to structure your protocol (H2 for major sections, H3 for subsections)</li>
                <li>Upload images by clicking the image icon in the toolbar</li>
                <li>Add tables for structured data like medication dosages</li>
                <li>Use color-coding when appropriate (red for warnings, blue for notes)</li>
            </ul>
        </div>
        
        <form method="POST" id="content-form">
            <input type="hidden" name="action" value="update_content">
            
            <div class="protocol-editor mb-4">
                <div id="editor-container">
                    <!-- Quill editor will be initialized here -->
                </div>
                <textarea id="protocol_content" name="protocol_content" style="display:none;"><?= htmlspecialchars($content) ?></textarea>
            </div>
            
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-success" id="save-content-btn">
                    <i class="ti ti-device-floppy me-1"></i> Save Protocol Content
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill with more toolbar options
    var quill = new Quill('#editor-container', {
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'align': [] }],
                ['blockquote', 'code-block'],
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                ['link', 'image', 'video'],
                ['clean'],
                ['table']
            ]
        },
        theme: 'snow'
    });
    
    // Add table support (this is a simplified implementation)
    var tableModule = quill.getModule('toolbar');
    if (tableModule) {
        quill.getModule('toolbar').addHandler('table', function() {
            const tableHTML = '<table style="width:100%; border-collapse: collapse;"><tr><th style="border: 1px solid #ddd; padding: 8px;">Header 1</th><th style="border: 1px solid #ddd; padding: 8px;">Header 2</th></tr><tr><td style="border: 1px solid #ddd; padding: 8px;">Cell 1</td><td style="border: 1px solid #ddd; padding: 8px;">Cell 2</td></tr></table><p><br></p>';
            
            // Get the current selection and insert the table
            const range = quill.getSelection();
            if (range) {
                quill.clipboard.dangerouslyPasteHTML(range.index, tableHTML);
            } else {
                quill.clipboard.dangerouslyPasteHTML(quill.getLength(), tableHTML);
            }
        });
    }

    // Load content into the editor
    const content = document.getElementById('protocol_content').value;
    if (content) {
        quill.root.innerHTML = content;
    }
    
    // Handle form submission - update the hidden textarea with the editor content
    document.getElementById('content-form').addEventListener('submit', function() {
        document.getElementById('protocol_content').value = quill.root.innerHTML;
    });
    
    // Handle image upload
    const imageHandler = () => {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();
        
        input.onchange = () => {
            const file = input.files[0];
            
            if (file) {
                const formData = new FormData();
                formData.append('file', file);
                
                // Show loading indicator
                const range = quill.getSelection(true);
                quill.insertText(range.index, 'Uploading image...', { 'italic': true });
                
                // Upload the image
                fetch('../api/upload_image.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(result => {
                    // Remove loading text
                    quill.deleteText(range.index, 'Uploading image...'.length);
                    
                    // Insert the image
                    if (result.location) {
                        quill.insertEmbed(range.index, 'image', result.location);
                    } else {
                        alert('Failed to upload image: ' + (result.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    // Remove loading text
                    quill.deleteText(range.index, 'Uploading image...'.length);
                    alert('Failed to upload image: ' + error);
                });
            }
        };
    };
    
    // Override default image handler
    quill.getModule('toolbar').addHandler('image', imageHandler);
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?>
