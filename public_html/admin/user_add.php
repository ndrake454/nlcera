<?php
/**
 * Add User Page
 * 
 * Place this file in: /admin/user_add.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require admin role
require_admin();

// Set page title
$page_title = 'Add New User';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $full_name = $_POST['full_name'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'editor';
    
    $errors = [];
    
    // Validate required fields
    if (empty($username)) {
        $errors[] = 'Username is required.';
    } elseif (strlen($username) < 3) {
        $errors[] = 'Username must be at least 3 characters long.';
    }
    
    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is invalid.';
    }
    
    if (empty($full_name)) {
        $errors[] = 'Full name is required.';
    }
    
    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }
    
    if ($role !== 'admin' && $role !== 'editor') {
        $errors[] = 'Invalid role selected.';
    }
    
    // Check if username already exists
    $existing_username = db_get_row(
        "SELECT * FROM users WHERE username = ?",
        [$username]
    );
    
    if ($existing_username) {
        $errors[] = 'Username already exists.';
    }
    
    // Check if email already exists
    $existing_email = db_get_row(
        "SELECT * FROM users WHERE email = ?",
        [$email]
    );
    
    if ($existing_email) {
        $errors[] = 'Email already exists.';
    }
    
    // If no errors, insert the user
    if (empty($errors)) {
        $hashed_password = hash_password($password);
        
        $data = [
            'username' => $username,
            'password' => $hashed_password,
            'email' => $email,
            'full_name' => $full_name,
            'role' => $role
        ];
        
        $user_id = db_insert('users', $data);
        
        if ($user_id) {
            set_flash_message('success', 'User created successfully.');
            header('Location: users.php');
            exit;
        } else {
            $errors[] = 'Failed to create user.';
        }
    }
}

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Add New User</h5>
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
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" 
                           value="<?= isset($username) ? $username : '' ?>" required>
                    <div class="form-text">Used for login, must be unique</div>
                </div>
                
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?= isset($email) ? $email : '' ?>" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" 
                       value="<?= isset($full_name) ? $full_name : '' ?>" required>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="form-text">At least 6 characters</div>
                </div>
                
                <div class="col-md-6">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="editor" <?= isset($role) && $role === 'editor' ? 'selected' : '' ?>>Editor</option>
                    <option value="admin" <?= isset($role) && $role === 'admin' ? 'selected' : '' ?>>Administrator</option>
                </select>
                <div class="form-text">
                    <strong>Editor:</strong> Can manage protocols and categories<br>
                    <strong>Administrator:</strong> Full access including user management
                </div>
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="users.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create User</button>
            </div>
        </form>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>