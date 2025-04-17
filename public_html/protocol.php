<?php
/**
 * Protocol Page
 * Displays a single protocol
 * 
 * Place this file in: /protocol.php
 */

// Include required files
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php'; // Include auth BEFORE any output is generated
require_once 'includes/functions.php';

// Get protocol ID from query string
$protocol_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if this is a preview request from the admin
$is_preview = isset($_GET['preview']) && $_GET['preview'] === '1';

// Get protocol data
$protocol = get_protocol($protocol_id);

// Check if protocol exists and is active (unless preview)
if (!$protocol || (!$protocol['is_active'] && !$is_preview)) {
    header('Location: index.php');
    exit;
}

// Get protocol sections
$sections = get_protocol_sections($protocol_id);

// Get category data
$category = get_category($protocol['category_id']);

// Set page title and active tab
$page_title = $protocol['protocol_number'] . '. ' . $protocol['title'];
$active_tab = 'protocols';

// Check if user is logged in as admin - do this BEFORE any HTML output
$showEditButton = false;
if (function_exists('is_logged_in') && is_logged_in()) {
    $showEditButton = true;
}

// For preview mode, only output the content without the header/footer
if ($is_preview) {
    // Output protocol content for preview
    include 'includes/protocol_content.php';
    exit;
}

// Include header for normal view
include 'includes/frontend_header.php';
?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Protocol Categories</a></li>
        <li class="breadcrumb-item"><a href="category.php?id=<?= $category['id'] ?>"><?= $category['title'] ?></a></li>
        <li class="breadcrumb-item active"><?= $protocol['title'] ?></li>
    </ol>
</nav>

<?php if ($showEditButton): ?>
    <div class="admin-edit-bar bg-warning text-dark p-2 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="ti ti-pencil me-1"></i> You are logged in as an administrator.
            </div>
            <a href="<?= $base_url ?>/admin/protocol_edit.php?id=<?= $protocol_id ?>" class="btn btn-primary btn-sm">
                <i class="ti ti-edit me-1"></i> Edit This Protocol
            </a>
        </div>
    </div>
<?php endif; ?>

<?php 
// Include protocol content
include 'includes/protocol_content.php';
?>

<?php
// Include footer
include 'includes/frontend_footer.php';
?>