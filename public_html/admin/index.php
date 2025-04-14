<?php
/**
 * Admin Dashboard
 * 
 * Place this file in: /admin/index.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once dirname(__DIR__, 1) . '/includes/auth.php';
require_once '../includes/functions.php';

// Set page title
$page_title = 'Dashboard';

// Require login
require_login();

// Get stats for the dashboard
$category_count = db_get_row("SELECT COUNT(*) as count FROM categories")['count'];
$protocol_count = db_get_row("SELECT COUNT(*) as count FROM protocols")['count'];
$component_count = db_get_row("SELECT COUNT(*) as count FROM component_templates")['count'];
$user_count = db_get_row("SELECT COUNT(*) as count FROM users")['count'];

// Recent protocols
$recent_protocols = db_get_rows(
    "SELECT p.*, c.title as category_name, u.full_name as editor_name
     FROM protocols p
     JOIN categories c ON p.category_id = c.id
     JOIN users u ON p.updated_by = u.id
     ORDER BY p.updated_at DESC
     LIMIT 5"
);

// Include header
include 'includes/header.php';
?>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="ti ti-category text-primary" style="font-size: 3rem;"></i>
                <h2 class="mt-3"><?= $category_count ?></h2>
                <h5>Categories</h5>
            </div>
            <div class="card-footer bg-light">
                <a href="categories.php" class="btn btn-sm btn-primary">Manage Categories</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="ti ti-file-text text-success" style="font-size: 3rem;"></i>
                <h2 class="mt-3"><?= $protocol_count ?></h2>
                <h5>Protocols</h5>
            </div>
            <div class="card-footer bg-light">
                <a href="protocols.php" class="btn btn-sm btn-success">Manage Protocols</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-center h-100">
            <div class="card-body">
                <i class="ti ti-users text-info" style="font-size: 3rem;"></i>
                <h2 class="mt-3"><?= $user_count ?></h2>
                <h5>Users</h5>
            </div>
            <div class="card-footer bg-light">
                <?php if (is_admin()): ?>
                    <a href="users.php" class="btn btn-sm btn-info">Manage Users</a>
                <?php else: ?>
                    <button class="btn btn-sm btn-info" disabled>Admin Only</button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Protocol Updates</h5>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (empty($recent_protocols)): ?>
                        <div class="list-group-item text-center text-muted">
                            No protocols found.
                        </div>
                    <?php else: ?>
                        <?php foreach ($recent_protocols as $protocol): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?= $protocol['protocol_number'] ?>. <?= $protocol['title'] ?></h6>
                                        <small class="text-muted">
                                            <span class="badge bg-secondary"><?= $protocol['category_name'] ?></span>
                                            Updated by <?= $protocol['editor_name'] ?>
                                        </small>
                                    </div>
                                    <div>
                                        <small class="text-muted"><?= format_datetime($protocol['updated_at']) ?></small>
                                        <a href="protocol_edit.php?id=<?= $protocol['id'] ?>" class="btn btn-sm btn-outline-primary ms-2">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer">
                <a href="protocols.php" class="btn btn-sm btn-outline-secondary">View All Protocols</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="protocol_add.php" class="btn btn-outline-primary w-100 p-3">
                            <i class="ti ti-plus"></i><br>
                            Add New Protocol
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="category_add.php" class="btn btn-outline-success w-100 p-3">
                            <i class="ti ti-plus"></i><br>
                            Add New Category
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="../index.php" target="_blank" class="btn btn-outline-info w-100 p-3">
                            <i class="ti ti-external-link"></i><br>
                            View Public Site
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">System Information</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>PHP Version:</span>
                        <span class="badge bg-secondary"><?= phpversion() ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>MySQL Version:</span>
                        <span class="badge bg-secondary"><?= db_get_row("SELECT VERSION() as version")['version'] ?></span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Server:</span>
                        <span class="badge bg-secondary"><?= $_SERVER['SERVER_SOFTWARE'] ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>