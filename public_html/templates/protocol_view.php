<?php
// /templates/protocol_view.php - Displays a single protocol to the frontend user
// This script is called by /protocol.php

// Ensure essential variables are available (should be set by protocol.php)
if (!isset($protocol) || !isset($steps) || !isset($page_title)) {
     // Handle error: Redirect or show a generic error message
     // For now, just prevent further processing
     error_log("Error: Required variables (\$protocol, \$steps, \$page_title) not set in protocol_view.php");
     // You might want to include header/footer and an error message here
     // For simplicity, we'll assume protocol.php handles the "not found" case
     // and these variables will exist if we reach here.
}

// Ensure the rendering functions are available
// Note: functions.php might already be included by protocol.php, but including again is safe with require_once
require_once __DIR__ . '/../includes/functions.php';

// --- Start HTML Output ---
?>

<?php // Display protocol content only if protocol data was successfully loaded ?>
<?php if (!empty($protocol)): ?>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Categories</a></li>
        <?php if (!empty($protocol['category_id']) && !empty($protocol['category_name'])): ?>
            <li class="breadcrumb-item"><a href="category.php?id=<?php echo escape($protocol['category_id']); ?>"><?php echo escape($protocol['category_name']); ?></a></li>
        <?php endif; ?>
        <li class="breadcrumb-item active" aria-current="page"><?php echo escape($protocol['title']); ?></li>
      </ol>
    </nav>

    <h1 class="mb-0 protocol-main-title">
        <?php echo escape($protocol['protocol_number'] ? $protocol['protocol_number'] . '.' : ''); ?>
        <?php echo escape($protocol['title']); ?>
    </h1>
    <p class="text-muted mb-3 protocol-last-updated">
        Last Updated: <?php echo date('F j, Y, g:i a', strtotime($protocol['last_updated'])); ?>
    </p>

    <div class="protocol-content-wrapper border rounded p-3 mb-4"> <?php // Renamed class slightly ?>
        <?php
        // Call the main rendering function which handles sorting, recursion, and modals
        // It returns the HTML for steps and modals combined (or a "no steps" message)
        echo render_protocol_display($steps);
        ?>
    </div>

<?php else: ?>
    <?php // Fallback message if $protocol was empty (should ideally be handled in protocol.php) ?>
    <div class="alert alert-danger">Sorry, the requested protocol could not be displayed.</div>
<?php endif; ?>
