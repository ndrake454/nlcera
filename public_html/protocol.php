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

<?php 
// Include protocol content
include 'includes/protocol_content.php';
?>

<?php
// Include footer
include 'includes/frontend_footer.php';
?>