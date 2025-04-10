<?php
/**
 * Delete Category
 * 
 * Place this file in: /admin/category_delete.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require login
require_login();

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

// Count protocols in this category
$protocol_count = db_get_row(
    "SELECT COUNT(*) as count FROM protocols WHERE category_id = ?",
    [$category_id]
)['count'];

// Process confirmation
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    // Start a transaction to ensure all related data is deleted
    $pdo = get_db_connection();
    $pdo->beginTransaction();
    
    try {
        // Delete all protocol sections and related data first
        $protocols = db_get_rows(
            "SELECT id FROM protocols WHERE category_id = ?",
            [$category_id]
        );
        
        foreach ($protocols as $protocol) {
            // Delete decision branches for each section
            $sections = db_get_rows(
                "SELECT id FROM protocol_sections WHERE protocol_id = ?",
                [$protocol['id']]
            );
            
            foreach ($sections as $section) {
                db_delete('decision_branches', 'section_id = ?', [$section['id']]);
            }
            
            // Delete protocol sections
            db_delete('protocol_sections', 'protocol_id = ?', [$protocol['id']]);
        }
        
        // Delete protocols
        db_delete('protocols', 'category_id = ?', [$category_id]);
        
        // Delete icon file if exists
        if (!empty($category['icon'])) {
            $icon_path = dirname(__DIR__) . '/assets/icons/' . $category['icon'];
            if (file_exists($icon_path)) {
                unlink($icon_path);
            }
        }
        
        // Delete category
        db_delete('categories', 'id = ?', [$category_id]);
        
        // Commit the transaction
        $pdo->commit();
        
        set_flash_message('success', 'Category deleted successfully.');
        header('Location: categories.php');
        exit;
    } catch (Exception $e) {
        // Rollback the transaction on error
        $pdo->rollBack();
        
        set_flash_message('error', 'Failed to delete category: ' . $e->getMessage());
        header('Location: categories.php');
        exit;
    }
}

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">Confirm Delete Category</h5>
    </div>
    
    <div class="card-body">
        <div class="alert alert-warning">
            <i class="ti ti-alert-triangle me-2"></i>
            <strong>Warning:</strong> This action cannot be undone.
        </div>
        
        <p>Are you sure you want to delete the following category?</p>
        
        <div class="mb-4">
            <h5><?= $category['category_number'] ?>. <?= $category['title'] ?></h5>
            <p><?= $category['description'] ?></p>
            
            <?php if ($protocol_count > 0): ?>
                <div class="alert alert-danger">
                    <i class="ti ti-file-alert me-2"></i>
                    <strong>Warning:</strong> This category contains <?= $protocol_count ?> protocol(s). Deleting this category will also delete all of its protocols.
                </div>
            <?php endif; ?>
        </div>
        
        <div class="d-flex justify-content-between">
            <a href="categories.php" class="btn btn-secondary">
                <i class="ti ti-x"></i> Cancel
            </a>
            <a href="category_delete.php?id=<?= $category_id ?>&confirm=yes" class="btn btn-danger">
                <i class="ti ti-trash"></i> Delete Category
            </a>
        </div>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>