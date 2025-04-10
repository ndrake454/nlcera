<?php
/**
 * Category Management Page
 * 
 * Place this file in: /admin/categories.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require login
require_login();

// Set page title
$page_title = 'Protocol Categories';

// Get all categories
$categories = get_all_categories();

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Protocol Categories</h5>
        <a href="category_add.php" class="btn btn-primary">
            <i class="ti ti-plus"></i> Add Category
        </a>
    </div>
    
    <div class="card-body">
        <?php if (empty($categories)): ?>
            <div class="alert alert-info">
                No categories found. Create your first category to get started.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Icon</th>
                            <th>Protocols</th>
                            <th>Order</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <?php 
                            // Count protocols in this category
                            $protocol_count = db_get_row(
                                "SELECT COUNT(*) as count FROM protocols WHERE category_id = ?",
                                [$category['id']]
                            )['count'];
                            ?>
                            <tr>
                                <td><?= $category['category_number'] ?></td>
                                <td><?= $category['title'] ?></td>
                                <td><?= substr($category['description'], 0, 50) ?><?= strlen($category['description']) > 50 ? '...' : '' ?></td>
                                <td>
                                    <?php if (!empty($category['icon'])): ?>
                                        <img src="<?= ICON_PATH . $category['icon'] ?>" alt="<?= $category['title'] ?>" height="24">
                                    <?php else: ?>
                                        <span class="text-muted">No icon</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $protocol_count ?></td>
                                <td><?= $category['display_order'] ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="category_edit.php?id=<?= $category['id'] ?>" class="btn btn-outline-primary">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>
                                        <a href="category_delete.php?id=<?= $category['id'] ?>" class="btn btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this category? This will delete all protocols in this category.')">
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

<?php
// Include footer
include 'includes/footer.php';
?>