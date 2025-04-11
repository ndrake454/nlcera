<?php
/**
 * Edit Component Template
 * 
 * Place this file in: /admin/component_edit.php
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

// Set page title
$page_title = 'Edit Component Template';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $section_type = $_POST['section_type'] ?? '';
    $html_template = $_POST['html_template'] ?? '';
    
    $errors = [];
    
    // Validate required fields
    if (empty($title)) {
        $errors[] = 'Title is required.';
    }
    
    if (empty($section_type)) {
        $errors[] = 'Section type is required.';
    }
    
    if (empty($html_template)) {
        $errors[] = 'HTML template is required.';
    }
    
    // If no errors, update the component template
    if (empty($errors)) {
        $data = [
            'title' => $title,
            'description' => $description,
            'section_type' => $section_type,
            'html_template' => $html_template
        ];
        
        $result = db_update('component_templates', $data, 'id = ?', [$component_id]);
        
        if ($result !== false) {
            set_flash_message('success', 'Component template updated successfully.');
            header('Location: components.php');
            exit;
        } else {
            $errors[] = 'Failed to update component template.';
        }
    }
}

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Component Template</h5>
        <a href="components.php" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Back to Components
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
        
        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?= $component['title'] ?>" required>
                <div class="form-text">A descriptive name for this component template</div>
            </div>
            
            <div class="mb-3">
                <label for="section_type" class="form-label">Section Type</label>
                <select class="form-select" id="section_type" name="section_type" required <?= $component['is_system'] ? 'disabled' : '' ?>>
                    <option value="entry_point" <?= $component['section_type'] === 'entry_point' ? 'selected' : '' ?>>Entry Point</option>
                    <option value="treatment" <?= $component['section_type'] === 'treatment' ? 'selected' : '' ?>>Treatment</option>
                    <option value="decision" <?= $component['section_type'] === 'decision' ? 'selected' : '' ?>>Decision</option>
                    <option value="note" <?= $component['section_type'] === 'note' ? 'selected' : '' ?>>Note</option>
                    <option value="reference" <?= $component['section_type'] === 'reference' ? 'selected' : '' ?>>Reference</option>
                </select>
                <?php if ($component['is_system']): ?>
                    <input type="hidden" name="section_type" value="<?= $component['section_type'] ?>">
                    <div class="form-text text-warning">System templates cannot change type</div>
                <?php else: ?>
                    <div class="form-text">The type of protocol section this template will create</div>
                <?php endif; ?>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= $component['description'] ?></textarea>
                <div class="form-text">Brief description of what this component does</div>
            </div>
            
            <div class="mb-3">
                <label for="html_template" class="form-label">HTML Template</label>
                <textarea class="form-control tinymce" id="html_template" name="html_template" rows="10"><?= $component['html_template'] ?></textarea>
                <div class="form-text">
                    The HTML content of this component. You can use standard HTML with the TinyMCE editor.
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="components.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Component Template</button>
            </div>
        </form>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>