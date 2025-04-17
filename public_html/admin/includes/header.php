<?php
/**
 * Admin Header Template
 * Common header for all admin pages
 * 
 * Place this file in: /admin/includes/header.php
 */

// Ensure authentication
require_once dirname(__DIR__, 1) . '/../includes/auth.php';
require_login();

// Get the current page for nav highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Admin Dashboard' ?> - <?= SITE_NAME ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css">
    
    <!-- Quill Editor CSS and JS -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- jQuery UI (for drag and drop) -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    
    <!-- Custom Admin Styles -->
    <style>
        :root {
            --primary-color: #106e9e;
            --secondary-color: #0c5578;
            --sidebar-width: 280px;
        }
        
        body {
            min-height: 100vh;
            background-color: #f5f7fb;
        }
        
        .admin-sidebar {
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            background-color: var(--primary-color);
            color: #fff;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .admin-sidebar-header {
            padding: 1.5rem 1rem;
            background-color: var(--secondary-color);
            text-align: center;
        }
        
        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 0.75rem 1rem;
            border-radius: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .admin-sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .admin-sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
            font-weight: 500;
        }
        
        .admin-content {
            margin-left: var(--sidebar-width);
            padding: 1rem;
            min-height: 100vh;
        }
        
        .admin-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }
            
            .admin-content {
                margin-left: 0;
            }
        }
        
        /* Drag and drop styles */
        .component-item {
            cursor: grab;
            margin-bottom: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            background-color: #f8f9fa;
            padding: 0.5rem;
        }
        
        .component-item:hover {
            background-color: #e9ecef;
        }
        
        .protocol-editor-area {
            min-height: 300px;
            border: 2px dashed #ddd;
            border-radius: 0.5rem;
            padding: 1rem;
            background-color: #fff;
        }
        
        .protocol-editor-area.highlight {
            border-color: var(--primary-color);
            background-color: rgba(16, 110, 158, 0.05);
        }
        
        .protocol-section {
            margin-bottom: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            background-color: #fff;
        }
        
        .protocol-section-header {
            padding: 0.75rem 1rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .protocol-section-body {
            padding: 1rem;
        }
        
        .section-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .handle {
            cursor: grab;
            color: #6c757d;
        }
        
        /* Quill editor styles */
        .quill-editor {
            background-color: white;
            border-radius: 4px;
            border: 1px solid #ced4da;
            margin-bottom: 1rem;
        }
        
        .ql-toolbar {
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            border-bottom: 1px solid #ced4da;
        }
        
        .ql-container {
            border-bottom-left-radius: 4px;
            border-bottom-right-radius: 4px;
            min-height: 200px;
        }
        
        /* Custom button for info modal */
        .ql-info-modal:after {
            content: "ℹ️";
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="admin-sidebar">
        <div class="admin-sidebar-header">
            <h5 class="m-0">Northern Colorado EMS</h5>
            <p class="m-0 small">Protocol Administrator</p>
        </div>
        
        <div class="p-3">
            <div class="d-flex align-items-center mb-3">
                <i class="ti ti-user-circle me-2"></i>
                <div>
                    <div class="fw-bold"><?= $_SESSION['user_name'] ?></div>
                    <div class="small text-light"><?= ucfirst($_SESSION['user_role']) ?></div>
                </div>
            </div>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?= $current_page === 'index.php' ? 'active' : '' ?>" href="index.php">
                    <i class="ti ti-dashboard"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $current_page === 'categories.php' ? 'active' : '' ?>" href="categories.php">
                    <i class="ti ti-category"></i> Categories
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $current_page === 'protocols.php' ? 'active' : '' ?>" href="protocols.php">
                    <i class="ti ti-file-text"></i> Protocols
                </a>
            </li>
            <?php if (is_admin()): ?>
            <li class="nav-item">
                <a class="nav-link <?= $current_page === 'users.php' ? 'active' : '' ?>" href="users.php">
                    <i class="ti ti-users"></i> Users
                </a>
            </li>
            <?php endif; ?>
            <li class="nav-item mt-4">
                <a class="nav-link" href="logout.php">
                    <i class="ti ti-logout"></i> Logout
                </a>
            </li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="admin-content">
        <!-- Top Bar -->
        <div class="admin-topbar">
            <h4 class="m-0"><?= $page_title ?? 'Dashboard' ?></h4>
            <div>
                <a href="../index.php" class="btn btn-sm btn-outline-primary" target="_blank">
                    <i class="ti ti-external-link"></i> View Site
                </a>
            </div>
        </div>
        
        <!-- Flash Messages -->
        <?php $flash = get_flash_message(); ?>
        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : $flash['type'] ?> alert-dismissible fade show">
                <?= $flash['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <!-- Main Content Container -->
        <div class="container-fluid p-0">