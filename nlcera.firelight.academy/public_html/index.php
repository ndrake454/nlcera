<?php
/**
 * Homepage / Protocol Categories
 * 
 * Place this file in: /index.php
 */

// Include required files
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Set page title and active tab
$page_title = 'Protocol Categories';
$active_tab = 'protocols';

// Get all categories
$categories = get_all_categories();

// Include header
include 'includes/frontend_header.php';
?>

<header class="mb-5">
    <h1>Protocol Categories</h1>
    <p class="lead">
        Select a protocol category to view available protocols
    </p>
</header>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <?php foreach ($categories as $category): ?>
        <div class="col">
            <div class="card h-100 category-card">
                <div class="card-body text-center p-4">
                    <?php if (!empty($category['icon'])): ?>
                        <img src="<?= ICON_PATH . $category['icon'] ?>" alt="<?= $category['title'] ?>" class="mb-3" height="64">
                    <?php else: ?>
                        <div class="category-icon mb-3">
                            <i class="ti ti-folder"></i>
                        </div>
                    <?php endif; ?>
                    
                    <h4 class="card-title"><?= $category['category_number'] ?>. <?= $category['title'] ?></h4>
                    <p class="card-text text-muted"><?= $category['description'] ?></p>
                </div>
                <div class="card-footer text-center bg-light">
                    <a href="category.php?id=<?= $category['id'] ?>" class="btn btn-primary">View Protocols</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php
// Include footer
include 'includes/frontend_footer.php';
?>