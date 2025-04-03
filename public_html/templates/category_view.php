<?php
// templates/category_view.php
// Expects $category (info about the current category) and $protocols (list of protocols)

if (empty($category)) {
    echo "<p>Category not found.</p>";
    return;
}
?>

<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="index.php">Categories</a></li>
    <li class="breadcrumb-item active" aria-current="page"><?php echo escape($category['name']); ?></li>
  </ol>
</nav>


<h1>
    <?php echo escape($category['category_number'] ? $category['category_number'] . '.' : ''); ?>
    <?php echo escape($category['name']); ?> Protocols
</h1>
<p class="lead"><?php echo escape($category['description']); ?></p>
<hr>

<?php if (empty($protocols)): ?>
    <p>No protocols found in this category.</p>
<?php else: ?>
    <ul class="list-group">
        <?php foreach ($protocols as $protocol): ?>
            <li class="list-group-item">
                <a href="protocol.php?id=<?php echo escape($protocol['protocol_id']); ?>" class="text-decoration-none">
                    <?php echo escape($protocol['protocol_number'] ? $protocol['protocol_number'] . '.' : ''); ?>
                    <?php echo escape($protocol['title']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>