<?php
/**
 * Frontend Header
 */

// Define base URL to use absolute paths
$base_url = SITE_URL; // Uses the SITE_URL from config.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ' : '' ?><?= SITE_NAME ?></title>
    
    <!-- PWA include -->
    <?php include dirname(__FILE__) . '/pwa-header.php'; ?>

    <!-- Cross-platform offline support -->
    <script src="/assets/js/offline-support.js"></script>

    <!-- iOS Offline Support (for backward compatibility) -->
    <?php if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false || 
            strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== false): ?>
    <script src="/assets/js/ios-offline-fixed.js"></script>
    <?php endif; ?>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css">
    
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="/assets/css/main.css?v=<?= filemtime(dirname(__DIR__) . '/assets/css/main.css') ?>">

</head>
<body>
<div class="alert alert-warning text-center py-2 mb-0" style="border-radius: 0; font-weight: bold;">
    <i class="ti ti-alert-triangle me-2"></i>This is a pre-release alpha, DO NOT use this for actual patient care.
</div>
    <!-- Header -->
<div class="header-wrapper">
    <header class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h4 mb-0">
                        <a href="index.php">Firelight Protocol Companion</a>
                    </h1>
                </div>
                <div>
                    <a href="search-ajax.php" class="btn btn-light btn-search">
                        <i class="ti ti-search"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="container">
            <ul class="nav nav-tabs border-bottom-0">
                <li class="nav-item">
                    <a class="nav-link <?= $active_tab === 'protocols' ? 'active' : '' ?>" href="<?= $base_url ?>/index.php">
                        <i class="ti ti-file-text me-1"></i> Protocols
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $active_tab === 'medications' ? 'active' : '' ?>" href="<?= $base_url ?>/medications.php">
                        <i class="ti ti-pill me-1"></i> Meds
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $active_tab === 'tools' ? 'active' : '' ?>" href="<?= $base_url ?>/tools.php">
                        <i class="ti ti-tools me-1"></i> Tools
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</div>

<script>
// Function to set the correct body padding based on header height
function adjustBodyPadding() {
    const headerHeight = document.querySelector('.header-wrapper').offsetHeight;
    document.body.style.paddingTop = headerHeight + 'px';
}

// Run on page load and window resize
document.addEventListener('DOMContentLoaded', adjustBodyPadding);
window.addEventListener('resize', adjustBodyPadding);
</script>

<!-- Main Content -->
<main class="py-4">
    <div class="container">