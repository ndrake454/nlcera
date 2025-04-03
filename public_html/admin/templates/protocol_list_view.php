<?php
// admin/templates/protocol_list_view.php
// Expects $protocols array and $csrf_token string
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo $page_title; ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="protocols.php?action=add" class="btn btn-sm btn-success">
            <i class="bi bi-journal-plus me-1"></i> Add New Protocol
        </a>
    </div>
</div>

<?php if (empty($protocols)): ?>
    <div class="alert alert-info">No protocols found. Add one using the button above.</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Number</th>
                    <th scope="col">Title</th>
                    <th scope="col">Category</th>
                    <th scope="col">Last Updated</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($protocols as $proto): ?>
                <tr>
                    <td><?php echo escape($proto['protocol_number'] ?? '---'); ?></td>
                    <td><?php echo escape($proto['title']); ?></td>
                    <td><?php echo escape($proto['category_name'] ?? 'N/A'); ?></td>
                    <td><?php echo date('Y-m-d H:i', strtotime($proto['last_updated'])); ?></td>
                    <td>
                        <a href="protocols.php?action=edit&id=<?php echo $proto['protocol_id']; ?>" class="btn btn-sm btn-primary me-1" title="Edit Basic Info">
                            <i class="bi bi-pencil-fill"></i> Edit Info
                        </a>
                        <a href="protocol_steps.php?action=edit&protocol_id=<?php echo $proto['protocol_id']; ?>" class="btn btn-sm btn-warning me-1" title="Edit Steps">
                            <i class="bi bi-list-nested"></i> Edit Steps
                        </a>
                        <a href="protocols.php?action=delete&id=<?php echo $proto['protocol_id']; ?>&token=<?php echo $csrf_token; ?>"
                           class="btn btn-sm btn-danger" title="Delete Protocol"
                           onclick="return confirm('Are you sure you want to delete the protocol \'<?php echo escape(addslashes($proto['title'])); ?>\' AND ALL ITS STEPS? This cannot be undone.');">
                            <i class="bi bi-trash-fill"></i> Delete
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>