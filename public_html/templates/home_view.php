<?php
// templates/home_view.php
// Expects $categories to be defined

if (empty($categories)) {
    echo "<p>No protocol categories found.</p>";
    return;
}
?>

<h1 class="mb-4 text-center">Protocol Categories</h1>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
    <?php foreach ($categories as $category): ?>
        <div class="col">
            <a href="category.php?id=<?php echo escape($category['category_id']); ?>" class="card h-100 text-center text-decoration-none category-card">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                    <i class="<?php echo escape($category['icon_class'] ?? 'bi-archive-fill'); ?> category-icon mb-2"></i>
                    <h5 class="card-title mb-1">
                        <?php echo escape($category['category_number'] ? $category['category_number'] . '.' : ''); ?>
                        <?php echo escape($category['name']); ?>
                    </h5>
                    <?php if (!empty($category['description'])): ?>
                        <p class="card-text small text-muted"><?php echo escape($category['description']); ?></p>
                    <?php endif; ?>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>