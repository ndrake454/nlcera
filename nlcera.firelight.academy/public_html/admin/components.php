<?php
/**
 * Component Templates Management
 * 
 * Place this file in: /admin/components.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require login
require_login();

// Set page title
$page_title = 'Component Templates';

// Get all component templates
$components = get_component_templates();

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Component Templates</h5>
        <a href="component_add.php" class="btn btn-primary">
            <i class="ti ti-plus"></i> Add Component Template
        </a>
    </div>
    
    <div class="card-body">
        <p class="text-muted">
            Component templates allow you to create reusable sections for your protocols.
            These will appear in the component library when editing protocols.
        </p>
        
        <?php if (empty($components)): ?>
            <div class="alert alert-info">
                No component templates found. Create your first component template to get started.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($components as $component): ?>
                            <?php 
                            // Get creator name
                            $creator = db_get_row(
                                "SELECT full_name FROM users WHERE id = ?",
                                [$component['created_by']]
                            );
                            ?>
                            <tr>
                                <td><?= $component['title'] ?></td>
                                <td>
                                    <?php if ($component['section_type'] === 'entry_point'): ?>
                                        <span class="badge bg-info">Entry Point</span>
                                    <?php elseif ($component['section_type'] === 'treatment'): ?>
                                        <span class="badge bg-success">Treatment</span>
                                    <?php elseif ($component['section_type'] === 'decision'): ?>
                                        <span class="badge bg-warning">Decision</span>
                                    <?php elseif ($component['section_type'] === 'note'): ?>
                                        <span class="badge bg-secondary">Note</span>
                                    <?php elseif ($component['section_type'] === 'reference'): ?>
                                        <span class="badge bg-primary">Reference</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= substr($component['description'], 0, 50) ?><?= strlen($component['description']) > 50 ? '...' : '' ?></td>
                                <td><?= $creator ? $creator['full_name'] : 'Unknown' ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="component_edit.php?id=<?= $component['id'] ?>" class="btn btn-outline-primary">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>
                                        <?php if (!$component['is_system']): ?>
                                            <a href="component_delete.php?id=<?= $component['id'] ?>" class="btn btn-outline-danger" 
                                               onclick="return confirm('Are you sure you want to delete this component template?')">
                                                <i class="ti ti-trash"></i> Delete
                                            </a>
                                        <?php endif; ?>
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