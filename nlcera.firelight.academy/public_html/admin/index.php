<?php
// admin/index.php - Admin Dashboard

require_once __DIR__ . '/includes/auth_check.php'; // Ensure user is logged in
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php'; // Use escape() if needed, though not used much here

$page_title = "Dashboard";

// --- Fetch some basic stats (Example) ---
$protocol_count = 'N/A'; // Default values in case of error
$category_count = 'N/A';
$user_count = 'N/A';

try {
    $protocol_count = $pdo->query("SELECT COUNT(*) FROM protocols")->fetchColumn();
    $category_count = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    $user_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
} catch (PDOException $e) {
    // Log the error - Don't show detailed DB errors on dashboard
    error_log("Dashboard DB Stats Error: " . $e->getMessage());
    // Optionally set a flash message if counts fail
    // $_SESSION['flash_message'] = ['type' => 'warning', 'text' => 'Could not retrieve dashboard statistics.'];
}


// Include Admin Header
include __DIR__ . '/templates/admin_header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo htmlspecialchars($page_title); ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <?php // Updated link for Add New Protocol ?>
        <a href="protocols.php?action=add" class="btn btn-sm btn-success me-2">
            <i class="bi bi-journal-plus me-1"></i> Add New Protocol
        </a>
         <a href="categories.php?action=add" class="btn btn-sm btn-primary">
             <i class="bi bi-folder-plus me-1"></i> Add New Category
        </a>
        <!-- Add more quick action buttons if needed -->
    </div>
</div>

<p>Welcome back, <?php echo htmlspecialchars($current_username); ?>!</p>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-journal-text me-2"></i>Protocols</h5>
                <p class="card-text display-4"><?php echo $protocol_count; ?></p>
                <?php // Updated link for Manage Protocols ?>
                <a href="protocols.php" class="btn btn-primary">Manage Protocols</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-folder me-2"></i>Categories</h5>
                <p class="card-text display-4"><?php echo $category_count; ?></p>
                <a href="categories.php" class="btn btn-primary">Manage Categories</a>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-people-fill me-2"></i>Admin Users</h5>
                <p class="card-text display-4"><?php echo $user_count; ?></p>
                <a href="users.php" class="btn btn-primary">Manage Users</a>
            </div>
        </div>
    </div>
</div>

<!-- Add more dashboard widgets or information here -->
<h2>Quick Overview</h2>
<p>From this panel, you can manage protocols, categories, and administrative users for the <?php echo htmlspecialchars(SITE_NAME); ?> website.</p>


<?php
// Include Admin Footer
include __DIR__ . '/templates/admin_footer.php';
?>