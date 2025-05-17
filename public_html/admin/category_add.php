<?php
/**
 * Add Category Page
 * 
 * Place this file in: /admin/category_add.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require login
require_login();

// Set page title
$page_title = 'Add New Category';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_number = $_POST['category_number'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $display_order = intval($_POST['display_order'] ?? 0);
    
    $errors = [];
    
    // Validate required fields
    if (empty($category_number)) {
        $errors[] = 'Category number is required.';
    } elseif (!preg_match('/^\d{4}$/', $category_number)) {
        $errors[] = 'Category number must be a 4-digit number.';
    }
    
    if (empty($title)) {
        $errors[] = 'Title is required.';
    }
    
    // Check if category number already exists
    $existing = db_get_row(
        "SELECT * FROM categories WHERE category_number = ?",
        [$category_number]
    );
    
    if ($existing) {
        $errors[] = 'A category with this number already exists.';
    }
    
    // Handle icon upload
    $icon = '';
    if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/svg+xml', 'image/png', 'image/jpeg'];
        $file_type = $_FILES['icon']['type'];
        
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = 'Icon must be an SVG, PNG, or JPEG file.';
        } else {
            // Create icons directory if it doesn't exist
            $upload_dir = dirname(__DIR__) . '/assets/icons/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            // Generate a unique filename
            $file_ext = pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION);
            $filename = 'icon_' . $category_number . '_' . uniqid() . '.' . $file_ext;
            
            // Move the uploaded file
            if (move_uploaded_file($_FILES['icon']['tmp_name'], $upload_dir . $filename)) {
                $icon = $filename;
            } else {
                $errors[] = 'Failed to upload icon.';
            }
        }
    }
    
    // If no errors, insert the category
    if (empty($errors)) {
        $data = [
            'category_number' => $category_number,
            'title' => $title,
            'description' => $description,
            'icon' => $icon,
            'display_order' => $display_order
        ];
        
        $category_id = db_insert('categories', $data);
        
        if ($category_id) {
            set_flash_message('success', 'Category created successfully.');
            header('Location: categories.php');
            exit;
        } else {
            $errors[] = 'Failed to create category.';
        }
    }
}

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Add New Category</h5>
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
        
        <form method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="category_number" class="form-label">Category Number</label>
                    <input type="text" class="form-control" id="category_number" name="category_number" 
                           value="<?= isset($category_number) ? $category_number : '' ?>" 
                           placeholder="e.g., 0000" required pattern="\d{4}">
                    <div class="form-text">4-digit number (e.g., 0000, 1000, 2000)</div>
                </div>
                
                <div class="col-md-6">
                    <label for="display_order" class="form-label">Display Order</label>
                    <input type="number" class="form-control" id="display_order" name="display_order" 
                           value="<?= isset($display_order) ? $display_order : '0' ?>" min="0">
                    <div class="form-text">Categories are sorted by this value (lowest first)</div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?= isset($title) ? $title : '' ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= isset($description) ? $description : '' ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="icon" class="form-label">Icon</label>
                <a href="https://tabler-icons.io/" target="_blank" class="ms-2 small text-primary" title="Find free SVG icons">
                    <i class="ti ti-search"></i> Find icons
                </a>
                <input type="file" class="form-control" id="icon" name="icon" accept=".svg,.png,.jpg,.jpeg">
                <div class="form-text">
                    Recommended: SVG icon (24x24px). 
                    <a href="https://svgrepo.com/collections/medical/" target="_blank">Medical icons</a>, 
                    <a href="https://heroicons.com/" target="_blank">Heroicons</a>, or 
                    <a href="https://feathericons.com/" target="_blank">Feather icons</a> are good sources.
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="categories.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Category</button>
            </div>
        </form>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>