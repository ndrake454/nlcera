<?php
/**
 * Authentication Functions
 * Handles user login, logout, and session management
 * 
 * Place this file in: /includes/auth.php
 */

// Include required files
require_once 'config.php';
require_once 'db.php';

/**
 * Start the session if not already started
 */
function start_session() {
    if (session_status() === PHP_SESSION_NONE) {
        // Set secure session params
        session_name(SESSION_NAME);
        session_start();
        
        // Regenerate session ID periodically to prevent session fixation
        if (!isset($_SESSION['last_regeneration'])) {
            regenerate_session_id();
        } else {
            $time_passed = time() - $_SESSION['last_regeneration'];
            if ($time_passed > 1800) { // 30 minutes
                regenerate_session_id();
            }
        }
    }
}

/**
 * Regenerate the session ID for security
 */
function regenerate_session_id() {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

/**
 * Login a user
 * 
 * @param string $username Username
 * @param string $password Plain text password
 * @return bool|array False if login fails, user data if successful
 */
function login($username, $password) {
    // Get user data
    $user = db_get_row(
        "SELECT * FROM users WHERE username = ?",
        [$username]
    );
    
    if (!$user) {
        return false; // User not found
    }
    
    // Important: Add salt to password before verification, just like we do when hashing
    if (!password_verify($password . HASH_SALT, $user['password'])) {
        return false; // Invalid password
    }
    
    // Start session
    start_session();
    
    // Update last login time
    db_update('users', 
        ['last_login' => date('Y-m-d H:i:s')],
        'id = ?',
        [$user['id']]
    );
    
    // Set session data
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_role'] = $user['role'];
    $_SESSION['user_name'] = $user['full_name'];
    
    return $user;
}

/**
 * Logout the current user
 */
function logout() {
    start_session();
    
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }
    
    // Destroy the session
    session_destroy();
}

/**
 * Check if a user is logged in
 * 
 * @return bool True if logged in, false otherwise
 */
function is_logged_in() {
    start_session();
    return isset($_SESSION['user_id']);
}

/**
 * Get the current user's ID
 * 
 * @return int|null User ID if logged in, null otherwise
 */
function get_current_user_id() {
    start_session();
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

/**
 * Get the current user's role
 * 
 * @return string|null User role if logged in, null otherwise
 */
function get_current_user_role() {
    start_session();
    return isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
}

/**
 * Check if the current user is an admin
 * 
 * @return bool True if admin, false otherwise
 */
function is_admin() {
    return get_current_user_role() === 'admin';
}

/**
 * Require login to access a page
 * Redirects to login page if not logged in
 */
function require_login() {
    if (!is_logged_in()) {
        header('Location: /admin/login.php');
        exit;
    }
}

/**
 * Require admin role to access a page
 * Redirects to dashboard with error if not admin
 */
function require_admin() {
    require_login();
    
    if (!is_admin()) {
        set_flash_message('error', 'You do not have permission to access that page.');
        header('Location: /admin/index.php');
        exit;
    }
}

/**
 * Set a flash message
 * 
 * @param string $type Message type (success, error, info, warning)
 * @param string $message The message text
 */
function set_flash_message($type, $message) {
    start_session();
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

/**
 * Get and clear the flash message
 * 
 * @return array|null Flash message or null if none
 */
function get_flash_message() {
    start_session();
    
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    
    return null;
}

/**
 * Hash a password
 * 
 * @param string $password Plain text password
 * @return string Hashed password
 */
function hash_password($password) {
    return password_hash($password . HASH_SALT, PASSWORD_DEFAULT);
}
?>