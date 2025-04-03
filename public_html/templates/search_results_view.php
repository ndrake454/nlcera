<?php
// templates/search_results_view.php
// Expects $query and $results

?>

<h1><?php echo $page_title; ?></h1>

<?php if (empty($query) || trim($query) === ''): ?>
    <p>Please enter a search term.</p>
<?php elseif (empty($results)): ?>
    <p>No protocols found matching your search term "<?php echo escape($query); ?>".</p>
<?php else: ?>
    <p><?php echo count($results); ?> result(s) found for "<?php echo escape($query); ?>":</p>
    <ul class="list-group">
        <?php foreach ($results as $protocol): ?>
            <li class="list-group-item">
                <a href="protocol.php?id=<?php echo escape($protocol['protocol_id']); ?>" class="text-decoration-none">
                    <strong>
                        <?php echo escape($protocol['protocol_number'] ? $protocol['protocol_number'] . '.' : ''); ?>
                        <?php echo escape($protocol['title']); ?>
                    </strong>
                </a>
                <?php if ($protocol['category_name']): ?>
                    <br><small class="text-muted">Category: <a href="category.php?id=<?php echo escape($protocol['category_id']); ?>"><?php echo escape($protocol['category_name']); ?></a></small>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>