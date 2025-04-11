<?php
/**
 * Delete Component Template
 * 
 * Place this file in: /admin/component_delete.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require login
require_login();

// Get component ID from query string
$component_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get component data
$component = db_get_row(
    "SELECT * FROM component_templates WHERE id = ?",
    [$component_id]
);

// Check if component exists
if (!$component) {
    set_flash_message('error', 'Component template not found.');
    header('Location: components.php');
    exit;
}

// Check if it's a system template (cannot be deleted)
if ($component['is_system']) {
    set_flash_message('error', 'System templates cannot be deleted.');
    header('Location: components.php');
    exit;
}

// Process confirmation
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    // Delete the component
    $result = db_delete('component_templates', 'id = ?', [$component_id]);
    
    if ($result) {
        set_flash_message('success', 'Component template deleted successfully.');
    } else {
        set_flash_message('error', 'Failed to delete component template.');
    }
    
    header('Location: components.php');
    exit;
}

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">Confirm Delete Component Template</h5>
    </div>
    
    <div class="card-body">
        <div class="alert alert-warning">
            <i class="ti ti-alert-triangle me-2"></i>
            <strong>Warning:</strong> This action cannot be undone.
        </div>
        
        <p>Are you sure you want to delete the following component template?</p>
        
        <div class="mb-4">
            <h5><?= $component['title'] ?></h5>
            <p><strong>Type:</strong> 
                <?php if ($component['section_type'] === 'entry_point'): ?>
                    Entry Point
                <?php elseif ($component['section_type'] === 'treatment'): ?>
                    Treatment
                <?php elseif ($component['section_type'] === 'decision'): ?>
                    Decision
                <?php elseif ($component['section_type'] === 'note'): ?>
                    Note
                <?php elseif ($component['section_type'] === 'reference'): ?>
                    Reference
                <?php endif; ?>
            </p>
            <?php if (!empty($component['description'])): ?>
                <p><strong>Description:</strong> <?= $component['description'] ?></p>
            <?php endif; ?>
        </div>
        
        <div class="d-flex justify-content-between">
            <a href="components.php" class="btn btn-secondary">
                <i class="ti ti-x"></i> Cancel
            </a>
            <a href="component_delete.php?id=<?= $component_id ?>&confirm=yes" class="btn btn-danger">
                <i class="ti ti-trash"></i> Delete Component Template
            </a>
        </div>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>