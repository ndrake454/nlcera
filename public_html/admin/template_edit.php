<?php
/**
 * Protocol Template Editor (FIXED VERSION)
 * 
 * This file provides an interface to create and edit protocol diagram templates
 * Place this file in: /admin/template_edit.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require admin role
require_admin();

// Get template ID from query string, 0 means new template
$template_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Initialize vars
$template = null;
$errors = [];

// Load existing template if editing
if ($template_id > 0) {
    $template = db_get_row(
        "SELECT * FROM protocol_templates WHERE id = ?",
        [$template_id]
    );
    
    if (!$template) {
        set_flash_message('error', 'Template not found.');
        header('Location: templates.php');
        exit;
    }
    
    $page_title = 'Edit Template: ' . $template['name'];
} else {
    $page_title = 'Add New Template';
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $xml_content = $_POST['xml_content'] ?? '';
    
    // Validate input
    if (empty($name)) {
        $errors[] = 'Template name is required.';
    }
    
    if (empty($xml_content)) {
        $errors[] = 'XML content is required.';
    }
    
    // Process if no errors
    if (empty($errors)) {
        $user_id = get_current_user_id();
        
        if ($template_id > 0) {
            // Update existing template
            $data = [
                'name' => $name,
                'description' => $description,
                'xml_content' => $xml_content
            ];
            
            $result = db_update('protocol_templates', $data, 'id = ?', [$template_id]);
            
            if ($result !== false) {
                set_flash_message('success', 'Template updated successfully.');
                header('Location: templates.php');
                exit;
            } else {
                $errors[] = 'Failed to update template.';
            }
        } else {
            // Create new template
            $data = [
                'name' => $name,
                'description' => $description,
                'xml_content' => $xml_content,
                'created_by' => $user_id
            ];
            
            $new_id = db_insert('protocol_templates', $data);
            
            if ($new_id) {
                set_flash_message('success', 'Template created successfully.');
                header('Location: templates.php');
                exit;
            } else {
                $errors[] = 'Failed to create template.';
            }
        }
    }
}

// Include header
include 'includes/header.php';
?>

<style>
    #editor-container {
        width: 100%;
        height: calc(100vh - 350px);
        min-height: 500px;
        border: 1px solid #dee2e6;
        position: relative;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><?= $template_id > 0 ? 'Edit Template' : 'Add New Template' ?></h5>
        <a href="templates.php" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Back to Templates
        </a>
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
        
        <form method="POST" id="template-form">
            <div class="mb-3">
                <label for="name" class="form-label">Template Name</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="<?= $template ? htmlspecialchars($template['name']) : '' ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= $template ? htmlspecialchars($template['description']) : '' ?></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Draw.io Editor</label>
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-2"></i>
                    Use the Draw.io editor below to create your template. Click "Get XML" when done to update the form.
                </div>
                
                <div class="mb-3">
                    <button type="button" class="btn btn-primary" id="get-xml-btn">
                        <i class="ti ti-code"></i> Get XML
                    </button>
                </div>
                
                <div id="editor-container">
                    <div id="loading-indicator" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3">Loading Draw.io editor...</p>
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="xml_content" class="form-label">XML Content</label>
                <textarea class="form-control" id="xml_content" name="xml_content" rows="8" required><?= $template ? htmlspecialchars($template['xml_content']) : '' ?></textarea>
                <div class="form-text">This field will be automatically updated when you click "Get XML".</div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="templates.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <?= $template_id > 0 ? 'Update Template' : 'Create Template' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    let editor = null;
    let editorReady = false;
    
    // Load the Draw.io editor
    initDrawioEditor();
    
    // Listen for messages from the Draw.io iframe
    window.addEventListener('message', function(evt) {
        if (typeof evt.data === 'string' && evt.data.length > 0) {
            try {
                const msg = JSON.parse(evt.data);
                
                // Handle ready event
                if (msg.event === 'init') {
                    editorReady = true;
                    
                    // Check if we have existing XML to load
                    const xmlContent = document.getElementById('xml_content').value;
                    if (xmlContent) {
                        loadDiagram(xmlContent);
                    }
                }
                
                // Handle export event
                if (msg.event === 'export') {
                    if (msg.format === 'xmlsvg') {
                        // Got XML, update the form
                        document.getElementById('xml_content').value = msg.xml;
                        alert('XML content updated successfully!');
                    }
                }
            } catch (e) {
                console.error('Error parsing message from Draw.io:', e);
            }
        }
    });
    
    // Get XML Button Click
    document.getElementById('get-xml-btn').addEventListener('click', function() {
        exportXml();
    });
    
    // Function to initialize the Draw.io editor
    function initDrawioEditor() {
        const container = document.getElementById('editor-container');
        
        // Clear the loading indicator
        container.innerHTML = '';
        
        // Create iframe for the editor
        const iframe = document.createElement('iframe');
        iframe.id = 'drawio-editor';
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        
        // Use a stable, direct URL to the Draw.io editor
        iframe.src = 'https://embed.diagrams.net/?embed=1&ui=atlas&spin=1&proto=json';
        
        container.appendChild(iframe);
        
        // Store reference to the editor
        editor = iframe;
    }
    
    // Function to export diagram as XML
    function exportXml() {
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
                spin: 'Exporting diagram'
            }), '*');
        } catch (e) {
            console.error('Error exporting XML:', e);
            alert('Failed to export diagram. Please try again.');
        }
    }
    
    // Function to load existing diagram
    function loadDiagram(xml) {
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
        } catch (e) {
            console.error('Error loading diagram:', e);
        }
    }
    
    // Form submission handler
    document.getElementById('template-form').addEventListener('submit', function(e) {
        // Make sure we have XML content before submitting
        const xmlContent = document.getElementById('xml_content').value;
        if (!xmlContent) {
            e.preventDefault();
            alert('Please get the XML content from the editor before submitting.');
            return false;
        }
    });
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?>