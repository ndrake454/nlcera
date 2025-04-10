<?php
/**
 * Edit User Page
 * 
 * Place this file in: /admin/user_edit.php
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

// Set page title
$page_title = 'Edit User: ' . $user['username'];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $role = $_POST['role'] ?? 'editor';
    $change_password = isset($_POST['change_password']) && $_POST['change_password'] === '1';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    $errors = [];
    
    // Validate required fields
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is invalid.';
    }
    
    if (empty($full_name)) {
        $errors[] = 'Full name is required.';
    }
    
    if ($role !== 'admin' && $role !== 'editor') {
        $errors[] = 'Invalid role selected.';
    }
    
    // Check if email already exists (excluding this user)
    $existing_email = db_get_row(
        "SELECT * FROM users WHERE email = ? AND id != ?",
        [$email, $user_id]
    );
    
    if ($existing_email) {
        $errors[] = 'Email already exists for another user.';
    }
    
    // Validate password if changing
    if ($change_password) {
        if (empty($password)) {
            $errors[] = 'Password is required when changing password.';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters long.';
        }
        
        if ($password !== $confirm_password) {
            $errors[] = 'Passwords do not match.';
        }
    }
    
    // If no errors, update the user
    if (empty($errors)) {
        $data = [
            'email' => $email,
            'full_name' => $full_name,
            'role' => $role
        ];
        
        // Add password to data if changing
        if ($change_password) {
            $data['password'] = hash_password($password);
        }
        
        $result = db_update('users', $data, 'id = ?', [$user_id]);
        
        if ($result !== false) {
            set_flash_message('success', 'User updated successfully.');
            header('Location: users.php');
            exit;
        } else {
            $errors[] = 'Failed to update user.';
        }
    }
}

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Edit User: <?= $user['username'] ?></h5>
        <a href="users.php" class="btn btn-secondary">
            <i class="ti ti-arrow-left"></i> Back to Users
        </a>
    </div>
    
    <div class="card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" value="<?= $user['username'] ?>" disabled>
                <div class="form-text">Username cannot be changed</div>
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" 
                       value="<?= isset($email) ? $email : $user['email'] ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" 
                       value="<?= isset($full_name) ? $full_name : $user['full_name'] ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="editor" <?= (isset($role) ? $role : $user['role']) === 'editor' ? 'selected' : '' ?>>Editor</option>
                    <option value="admin" <?= (isset($role) ? $role : $user['role']) === 'admin' ? 'selected' : '' ?>>Administrator</option>
                </select>
                <div class="form-text">
                    <strong>Editor:</strong> Can manage protocols and categories<br>
                    <strong>Administrator:</strong> Full access including user management
                </div>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="change_password" name="change_password" value="1" 
                       <?= isset($change_password) && $change_password ? 'checked' : '' ?>>
                <label class="form-check-label" for="change_password">Change Password</label>
            </div>
            
            <div id="password-fields" style="display: none;">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <div class="form-text">At least 6 characters</div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="users.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Update User</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const changePasswordCheckbox = document.getElementById('change_password');
    const passwordFields = document.getElementById('password-fields');
    
    // Show/hide password fields based on checkbox
    function togglePasswordFields() {
        passwordFields.style.display = changePasswordCheckbox.checked ? 'block' : 'none';
    }
    
    // Initial state
    togglePasswordFields();
    
    // Listen for changes
    changePasswordCheckbox.addEventListener('change', togglePasswordFields);
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?>