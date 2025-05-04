<?php
/**
 * Protocol Template Management
 * 
 * This file provides an interface to manage protocol diagram templates
 * Place this file in: /admin/templates.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require admin role
require_admin();

// Set page title
$page_title = 'Protocol Templates';

// Process template deletion
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $template_id = intval($_GET['delete']);
    
    // Delete the template
    $result = db_delete('protocol_templates', 'id = ?', [$template_id]);
    
    if ($result) {
        set_flash_message('success', 'Template deleted successfully.');
    } else {
        set_flash_message('error', 'Failed to delete template.');
    }
    
    // Redirect to avoid resubmission
    header('Location: templates.php');
    exit;
}

// Get all templates
$templates = db_get_rows(
    "SELECT t.*, u.username as creator 
     FROM protocol_templates t
     LEFT JOIN users u ON t.created_by = u.id
     ORDER BY t.name ASC"
);

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Protocol Templates</h5>
        <a href="template_edit.php" class="btn btn-primary">
            <i class="ti ti-plus"></i> Add Template
        </a>
    </div>
    
    <div class="card-body">
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            Templates allow you to quickly start a new protocol diagram with predefined elements and structure.
        </div>
        
        <?php if (empty($templates)): ?>
            <div class="alert alert-warning">
                <i class="ti ti-alert-triangle me-2"></i>
                No templates found. Click "Add Template" to create your first template.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Created</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($templates as $template): ?>
                            <tr>
                                <td><?= $template['name'] ?></td>
                                <td><?= substr($template['description'], 0, 100) ?><?= strlen($template['description']) > 100 ? '...' : '' ?></td>
                                <td>
                                    <?= format_datetime($template['created_at']) ?><br>
                                    <small class="text-muted">by <?= $template['creator'] ?></small>
                                </td>
                                <td><?= format_datetime($template['updated_at']) ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="template_edit.php?id=<?= $template['id'] ?>" class="btn btn-outline-primary">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>
                                        <a href="#" class="btn btn-outline-secondary preview-template" data-template-id="<?= $template['id'] ?>">
                                            <i class="ti ti-eye"></i> Preview
                                        </a>
                                        <a href="templates.php?delete=<?= $template['id'] ?>" class="btn btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this template?')">
                                            <i class="ti ti-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Template Preview Modal -->
<div class="modal fade" id="preview-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Template Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="preview-container" style="width: 100%; height: 600px; border: 1px solid #dee2e6;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle template preview
    const previewButtons = document.querySelectorAll('.preview-template');
    previewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const templateId = this.dataset.templateId;
            
            // Open the preview modal
            const previewModal = new bootstrap.Modal(document.getElementById('preview-modal'));
            previewModal.show();
            
            // Load the template XML
            fetch(`../api/get_template.php?id=${templateId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Initialize the viewer with the template XML
                        initViewer(data.template.xml_content);
                    } else {
                        document.getElementById('preview-container').innerHTML = `
                            <div class="alert alert-danger">
                                <i class="ti ti-alert-triangle me-2"></i>
                                Failed to load template: ${data.message}
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading template:', error);
                    document.getElementById('preview-container').innerHTML = `
                        <div class="alert alert-danger">
                            <i class="ti ti-alert-triangle me-2"></i>
                            Failed to load template. Please try again.
                        </div>
                    `;
                });
        });
    });
    
    // Initialize the viewer
    function initViewer(xml) {
        const container = document.getElementById('preview-container');
        
        // Clear the container
        container.innerHTML = '';
        
        // Create the viewer iframe
        const iframe = document.createElement('iframe');
        iframe.style.width = '100%';
        iframe.style.height = '100%';
        iframe.style.border = 'none';
        iframe.src = 'https://viewer.diagrams.net/?highlight=0000ff&nav=1&title=Template%20Preview';
        
        container.appendChild(iframe);
        
        // Wait for the iframe to load, then send the XML
        iframe.onload = function() {
            iframe.contentWindow.postMessage(xml, '*');
        };
    }
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?>