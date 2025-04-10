<?php
/**
 * Add Component Template
 * 
 * Place this file in: /admin/component_add.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require login
require_login();

// Set page title
$page_title = 'Add Component Template';

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
    
    // If no errors, insert the component template
    if (empty($errors)) {
        $user_id = get_current_user_id();
        
        $data = [
            'title' => $title,
            'description' => $description,
            'section_type' => $section_type,
            'html_template' => $html_template,
            'is_system' => 0, // User-created templates are not system ones
            'created_by' => $user_id
        ];
        
        $component_id = db_insert('component_templates', $data);
        
        if ($component_id) {
            set_flash_message('success', 'Component template created successfully.');
            header('Location: components.php');
            exit;
        } else {
            $errors[] = 'Failed to create component template.';
        }
    }
}

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Add Component Template</h5>
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
                       value="<?= isset($title) ? $title : '' ?>" required>
                <div class="form-text">A descriptive name for this component template</div>
            </div>
            
            <div class="mb-3">
                <label for="section_type" class="form-label">Section Type</label>
                <select class="form-select" id="section_type" name="section_type" required>
                    <option value="">Select Type</option>
                    <option value="entry_point" <?= isset($section_type) && $section_type === 'entry_point' ? 'selected' : '' ?>>Entry Point</option>
                    <option value="treatment" <?= isset($section_type) && $section_type === 'treatment' ? 'selected' : '' ?>>Treatment</option>
                    <option value="decision" <?= isset($section_type) && $section_type === 'decision' ? 'selected' : '' ?>>Decision</option>
                    <option value="note" <?= isset($section_type) && $section_type === 'note' ? 'selected' : '' ?>>Note</option>
                    <option value="reference" <?= isset($section_type) && $section_type === 'reference' ? 'selected' : '' ?>>Reference</option>
                </select>
                <div class="form-text">The type of protocol section this template will create</div>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= isset($description) ? $description : '' ?></textarea>
                <div class="form-text">Brief description of what this component does</div>
            </div>
            
            <div class="mb-3">
                <label for="html_template" class="form-label">HTML Template</label>
                <textarea class="form-control tinymce" id="html_template" name="html_template" rows="10"><?= isset($html_template) ? $html_template : '' ?></textarea>
                <div class="form-text">
                    The HTML content of this component. You can use standard HTML with the TinyMCE editor.
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="components.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Component Template</button>
            </div>
        </form>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>