<?php
/**
 * Add Protocol Page
 * 
 * Place this file in: /admin/protocol_add.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require login
require_login();

// Set page title
$page_title = 'Add New Protocol';

// Get all categories for dropdown
$categories = get_all_categories();

// Check if we have categories
if (empty($categories)) {
    set_flash_message('error', 'You need to create at least one category before adding protocols.');
    header('Location: categories.php');
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
    
    // Check if protocol number already exists
    $existing = db_get_row(
        "SELECT * FROM protocols WHERE protocol_number = ?",
        [$protocol_number]
    );
    
    if ($existing) {
        $errors[] = 'A protocol with this number already exists.';
    }
    
    // If no errors, insert the protocol
    if (empty($errors)) {
        $user_id = get_current_user_id();
        
        $data = [
            'category_id' => $category_id,
            'protocol_number' => $protocol_number,
            'title' => $title,
            'description' => $description,
            'is_active' => $is_active,
            'created_by' => $user_id,
            'updated_by' => $user_id
        ];
        
        $protocol_id = db_insert('protocols', $data);
        
        if ($protocol_id) {
            set_flash_message('success', 'Protocol created successfully. Now add sections to your protocol.');
            header('Location: protocol_edit.php?id=' . $protocol_id);
            exit;
        } else {
            $errors[] = 'Failed to create protocol.';
        }
    }
}

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Add New Protocol</h5>
        <a href="protocols.php" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Back to Protocols
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
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= isset($category_id) && $category_id == $category['id'] ? 'selected' : '' ?>>
                                <?= $category['category_number'] ?>. <?= $category['title'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label for="protocol_number" class="form-label">Protocol Number</label>
                    <input type="text" class="form-control" id="protocol_number" name="protocol_number" 
                           value="<?= isset($protocol_number) ? $protocol_number : '' ?>" 
                           placeholder="e.g., 2030" required pattern="\d{4}(\.\d+)?">
                    <div class="form-text">Format: 2030 or 2030.1</div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?= isset($title) ? $title : '' ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description (optional)</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= isset($description) ? $description : '' ?></textarea>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?= !isset($is_active) || $is_active ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Active (visible to users)</label>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="protocols.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Protocol</button>
            </div>
        </form>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>