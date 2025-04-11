<?php
/**
 * Category Page
 * Displays protocols in a specific category
 * 
 * Place this file in: /category.php
 */

// Include required files
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get category ID from query string
$category_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get category data
$category = get_category($category_id);

// Check if category exists
if (!$category) {
    header('Location: index.php');
    exit;
}

// Get protocols in this category
$protocols = get_category_protocols($category_id);

// Set page title and active tab
$page_title = $category['title'];
$active_tab = 'protocols';

// Include header
include 'includes/frontend_header.php';
?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= $base_url ?>/index.php">Protocol Categories</a></li>
        <li class="breadcrumb-item active"><?= $category['title'] ?></li>
    </ol>
</nav>

<div class="category-header">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h1><?= $category['category_number'] ?>. <?= $category['title'] ?></h1>
            <p class="lead"><?= $category['description'] ?></p>
        </div>
        <a href="<?= $base_url ?>/index.php" class="back-to-categories">
            <i class="ti ti-arrow-left me-1"></i> Back to Categories
        </a>
    </div>
</div>

<?php if (empty($protocols)): ?>
    <div class="alert alert-info">
        No protocols found in this category.
    </div>
<?php else: ?>
    <div class="list-group">
        <?php foreach ($protocols as $protocol): ?>
            <a href="<?= $base_url ?>/protocol.php?id=<?= $protocol['id'] ?>" class="list-group-item list-group-item-action protocol-list-item p-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1"><?= $protocol['protocol_number'] ?>. <?= $protocol['title'] ?></h5>
                        <?php if (!empty($protocol['description'])): ?>
                            <p class="mb-0"><?= $protocol['description'] ?></p>
                        <?php endif; ?>
                    </div>

                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
// Include footer
include 'includes/frontend_footer.php';
?>