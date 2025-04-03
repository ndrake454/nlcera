<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) . ' | ' : ''; ?>Admin | <?php echo htmlspecialchars(SITE_NAME); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/admin/assets/css/admin_style.css">
    <!-- Add links for WYSIWYG editor CSS later if needed -->
</head>
<body>

<?php
// Assumes $page_title is set before including
// Assumes auth_check.php has already run successfully
// This require_once might be better placed inside the PHP block where $current_username is defined if needed earlier
require_once __DIR__ . '/../../config.php'; // Access BASE_URL etc.
global $current_username; // Get username from auth_check.php scope
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>/admin/">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <?php $current_script = basename($_SERVER['PHP_SELF']); // Define current script here ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_script == 'index.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_script == 'protocols.php' || $current_script == 'protocol_steps.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/protocols.php">Protocols</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_script == 'categories.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/categories.php">Categories</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link <?php echo ($current_script == 'users.php') ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/admin/users.php">Users</a>
                </li>
                <!-- Add more admin sections here -->
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                 <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i> <?php echo htmlspecialchars($current_username ?? 'User'); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/users.php?action=edit&id=<?php echo $_SESSION['user_id'] ?? ''; ?>">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>/admin/auth.php?action=logout"><i class="bi bi-box-arrow-right me-1"></i> Logout</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                     <a class="nav-link" href="<?php echo BASE_URL; ?>/" target="_blank" title="View Frontend Site"><i class="bi bi-box-arrow-up-right"></i> View Site</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid admin-container mt-4">
    <div class="row">
        <main class="col-12 px-md-4">
             <?php
             // Display flash messages
             if (isset($_SESSION['flash_message'])) {
                 $message_data = $_SESSION['flash_message'];
                 $message_text = htmlspecialchars($message_data['text'] ?? 'An unknown message occurred.');
                 $alert_type = match ($message_data['type'] ?? 'info') {
                     'success' => 'success', 'danger' => 'danger', 'warning' => 'warning', default => 'info',
                 };
                 unset($_SESSION['flash_message']);

                 echo '<div class="alert alert-' . $alert_type . ' alert-dismissible fade show" role="alert">';
                 echo $message_text;
                 echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                 echo '</div>';
             }
             ?>
            <!-- Page-specific content starts here -->