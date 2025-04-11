<?php
/**
 * Edit Category Page
 * 
 * Place this file in: /admin/category_edit.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require login
require_login();

// Set page title
$page_title = 'Edit Category';

// Get category ID from query string
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get category data
$category = get_category($category_id);

// Check if category exists
if (!$category) {
    set_flash_message('error', 'Category not found.');
    header('Location: categories.php');
    exit;
}

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
    
    // Check if category number already exists (excluding this category)
    $existing = db_get_row(
        "SELECT * FROM categories WHERE category_number = ? AND id != ?",
        [$category_number, $category_id]
    );
    
    if ($existing) {
        $errors[] = 'A category with this number already exists.';
    }
    
    // Handle icon upload
    $icon = $category['icon']; // Keep existing icon by default
    
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
                // Delete old icon if it exists
                if (!empty($icon) && file_exists($upload_dir . $icon)) {
                    unlink($upload_dir . $icon);
                }
                
                $icon = $filename;
            } else {
                $errors[] = 'Failed to upload icon.';
            }
        }
    }
    
    // Handle icon deletion
    if (isset($_POST['delete_icon']) && $_POST['delete_icon'] === '1') {
        $upload_dir = dirname(__DIR__) . '/assets/icons/';
        
        if (!empty($icon) && file_exists($upload_dir . $icon)) {
            unlink($upload_dir . $icon);
        }
        
        $icon = '';
    }
    
    // If no errors, update the category
    if (empty($errors)) {
        $data = [
            'category_number' => $category_number,
            'title' => $title,
            'description' => $description,
            'icon' => $icon,
            'display_order' => $display_order
        ];
        
        $result = db_update('categories', $data, 'id = ?', [$category_id]);
        
        if ($result !== false) {
            set_flash_message('success', 'Category updated successfully.');
            header('Location: categories.php');
            exit;
        } else {
            $errors[] = 'Failed to update category.';
        }
    }
}

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit Category</h5>
        <a href="categories.php" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Back to Categories
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
        
        <form method="POST" enctype="multipart/form-data">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="category_number" class="form-label">Category Number</label>
                    <input type="text" class="form-control" id="category_number" name="category_number" 
                           value="<?= $category['category_number'] ?>" 
                           placeholder="e.g., 0000" required pattern="\d{4}">
                    <div class="form-text">4-digit number (e.g., 0000, 1000, 2000)</div>
                </div>
                
                <div class="col-md-6">
                    <label for="display_order" class="form-label">Display Order</label>
                    <input type="number" class="form-control" id="display_order" name="display_order" 
                           value="<?= $category['display_order'] ?>" min="0">
                    <div class="form-text">Categories are sorted by this value (lowest first)</div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?= $category['title'] ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= $category['description'] ?></textarea>
            </div>
            
            <div class="mb-3">
                <label for="icon" class="form-label">Icon</label>
                
                <?php if (!empty($category['icon'])): ?>
                    <div class="mb-2">
                        <div class="d-flex align-items-center">
                            <img src="<?= ICON_PATH . $category['icon'] ?>" alt="<?= $category['title'] ?>" height="40" class="me-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="delete_icon" name="delete_icon" value="1">
                                <label class="form-check-label" for="delete_icon">
                                    Delete current icon
                                </label>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <input type="file" class="form-control" id="icon" name="icon" accept=".svg,.png,.jpg,.jpeg">
                <div class="form-text">Recommended: SVG icon (24x24px)</div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="categories.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Category</button>
            </div>
        </form>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>