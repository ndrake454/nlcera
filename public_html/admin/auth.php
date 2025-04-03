<?php
// admin/auth.php - Handles Login/Logout Actions

require_once __DIR__ . '/../includes/db.php'; // Includes config.php and PDO
require_once __DIR__ . '/../includes/functions.php'; // For escape()

// Use FILTER_SANITIZE_FULL_SPECIAL_CHARS instead of deprecated FILTER_SANITIZE_STRING
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Login Logic ---
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $password = $_POST['password']; // Get raw password for verification

    if (empty($username) || empty($password)) {
        $_SESSION['login_error'] = 'Username and password are required.';
        header('Location: login.php');
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT user_id, username, password_hash, is_admin FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // Verify user exists, password is correct, and is an admin
        if ($user && password_verify($password, $user['password_hash']) && $user['is_admin']) {
            // Regenerate session ID for security
            session_regenerate_id(true);

            // Store user info in session
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = true; // Or check $user['is_admin'] explicitly

            // Clear any previous login errors
            unset($_SESSION['login_error']);

            // Redirect to the admin dashboard
            header("Location: index.php");
            exit;

        } else {
            // Invalid credentials or not an admin
            $_SESSION['login_error'] = 'Invalid username, password, or insufficient privileges.';
            header('Location: login.php');
            exit;
        }

    } catch (PDOException $e) {
        // Log error properly in production
        // error_log("Login DB Error: " . $e->getMessage());
        $_SESSION['login_error'] = 'An error occurred during login. Please try again.';
        header('Location: login.php');
        exit;
    }

} elseif ($action === 'logout') {
    // --- Logout Logic ---

    // Unset all session variables
    $_SESSION = [];

    // If using session cookies, destroy the cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: login.php");
    exit;

} else {
    // Invalid action or direct access attempt
    header("Location: login.php");
    exit;
}
?>