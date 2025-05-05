<?php
/**
 * User Management Page
 * 
 * Place this file in: /admin/users.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require admin role
require_admin();

// Set page title
$page_title = 'Users';

// Get all users
$users = db_get_rows("SELECT * FROM users ORDER BY username ASC");

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">User Management</h5>
        <a href="user_add.php" class="btn btn-primary">
            <i class="ti ti-plus"></i> Add User
        </a>
    </div>
    
    <div class="card-body">
        <?php if (empty($users)): ?>
            <div class="alert alert-info">
                No users found. Create your first user to get started.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['username'] ?></td>
                                <td><?= $user['full_name'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td>
                                    <?php if ($user['role'] === 'admin'): ?>
                                        <span class="badge bg-danger">Administrator</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary">Editor</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['last_login']): ?>
                                        <?= format_datetime($user['last_login']) ?>
                                    <?php else: ?>
                                        <span class="text-muted">Never</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="user_edit.php?id=<?= $user['id'] ?>" class="btn btn-outline-primary">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>
                                        <?php if ($user['id'] !== get_current_user_id()): // Prevent deleting yourself ?>
                                            <a href="user_delete.php?id=<?= $user['id'] ?>" class="btn btn-outline-danger" 
                                               onclick="return confirm('Are you sure you want to delete this user?')">
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