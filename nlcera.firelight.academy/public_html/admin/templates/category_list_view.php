<?php
// admin/templates/category_list_view.php
// Expects $categories array and $csrf_token string
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo $page_title; ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="categories.php?action=add" class="btn btn-sm btn-success">
            <i class="bi bi-folder-plus me-1"></i> Add New Category
        </a>
    </div>
</div>

<?php if (empty($categories)): ?>
    <div class="alert alert-info">No categories found. Add one using the button above.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Number</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Icon</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $index => $cat): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo escape($cat['category_number'] ?? 'N/A'); ?></td>
                    <td><?php echo escape($cat['name']); ?></td>
                    <td><?php echo escape($cat['description'] ? substr($cat['description'], 0, 50) . (strlen($cat['description']) > 50 ? '...' : '') : '---'); ?></td>
                    <td><i class="<?php echo escape($cat['icon_class']); ?>"></i> (<?php echo escape($cat['icon_class']); ?>)</td>
                    <td>
                        <a href="categories.php?action=edit&id=<?php echo $cat['category_id']; ?>" class="btn btn-sm btn-primary me-1" title="Edit">
                            <i class="bi bi-pencil-fill"></i>
                        </a>
                        <a href="categories.php?action=delete&id=<?php echo $cat['category_id']; ?>&token=<?php echo $csrf_token; ?>"
                           class="btn btn-sm btn-danger" title="Delete"
                           onclick="return confirm('Are you sure you want to delete the category \'<?php echo escape(addslashes($cat['name'])); ?>\'? This cannot be undone if no protocols are using it.');">
                            <i class="bi bi-trash-fill"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<p><a href="https://icons.getbootstrap.com/" target="_blank">Browse Bootstrap Icons</a> (use the full class name, e.g., 'bi-heart-fill')</p>