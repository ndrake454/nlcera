<?php
/**
 * Delete User
 * 
 * Place this file in: /admin/user_delete.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require admin role
require_admin();

// Get user ID from query string
$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Prevent deleting yourself
if ($user_id === get_current_user_id()) {
    set_flash_message('error', 'You cannot delete your own account.');
    header('Location: users.php');
    exit;
}

// Get user data
$user = db_get_row(
    "SELECT * FROM users WHERE id = ?",
    [$user_id]
);

// Check if user exists
if (!$user) {
    set_flash_message('error', 'User not found.');
    header('Location: users.php');
    exit;
}

// Process confirmation
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    // Delete the user
    $result = db_delete('users', 'id = ?', [$user_id]);
    
    if ($result) {
        set_flash_message('success', 'User deleted successfully.');
    } else {
        set_flash_message('error', 'Failed to delete user.');
    }
    
    header('Location: users.php');
    exit;
}

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">Confirm Delete User</h5>
    </div>
    
    <div class="card-body">
        <div class="alert alert-warning">
            <i class="ti ti-alert-triangle me-2"></i>
            <strong>Warning:</strong> This action cannot be undone.
        </div>
        
        <p>Are you sure you want to delete the following user?</p>
        
        <div class="mb-4">
            <h5><?= $user['username'] ?></h5>
            <p><strong>Name:</strong> <?= $user['full_name'] ?></p>
            <p><strong>Email:</strong> <?= $user['email'] ?></p>
            <p><strong>Role:</strong> <?= ucfirst($user['role']) ?></p>
        </div>
        
        <div class="d-flex justify-content-between">
            <a href="users.php" class="btn btn-secondary">
                <i class="ti ti-x"></i> Cancel
            </a>
            <a href="user_delete.php?id=<?= $user_id ?>&confirm=yes" class="btn btn-danger">
                <i class="ti ti-trash"></i> Delete User
            </a>
        </div>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>