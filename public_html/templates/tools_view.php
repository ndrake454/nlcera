<?php
// templates/tools_view.php
// Expects $tools array

?>
<h1 class="mb-4 text-center">Quick Tools</h1>

<?php if (empty($tools)): ?>
    <p class="text-center">No tools are available at this time.</p>
<?php else: ?>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        <?php foreach ($tools as $tool): ?>
            <div class="col">
                 <a href="<?php echo escape($tool['link']); ?>" class="card h-100 text-center text-decoration-none category-card">
                     <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <i class="<?php echo escape($tool['icon'] ?? 'bi-tools'); ?> category-icon mb-2"></i>
                        <h5 class="card-title mb-1"><?php echo escape($tool['title']); ?></h5>
                        <?php if (!empty($tool['description'])): ?>
                            <p class="card-text small text-muted"><?php echo escape($tool['description']); ?></p>
                        <?php endif; ?>
                     </div>
                 </a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>