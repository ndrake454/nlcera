<?php
/**
 * Frontend Header Template
 * Common header for all public-facing pages
 * 
 * Place this file in: /includes/frontend_header.php
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ' : '' ?><?= SITE_NAME ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #106e9e;
            --secondary-color: #0c5578;
        }
        
        body {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        
        .header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem 0;
        }
        
        .header a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
        }
        
        .header a:hover {
            color: white;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: var(--primary-color);
            padding: 0.75rem 1rem;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            font-weight: 500;
            border-bottom: 3px solid var(--primary-color);
            background-color: transparent;
        }
        
        .category-card {
            transition: transform 0.2s ease-in-out;
        }
        
        .category-card:hover {
            transform: translateY(-5px);
        }
        
        .category-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
        }
        
        .protocol-list-item {
            border-left: 3px solid transparent;
            transition: border-color 0.2s ease-in-out, background-color 0.2s ease-in-out;
        }
        
        .protocol-list-item:hover {
            border-left-color: var(--primary-color);
            background-color: rgba(16, 110, 158, 0.05);
        }
        
        .footer {
            background-color: #343a40;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
        
        /* Protocol Specific Styles */
        .protocol-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
        }
        
        .protocol-section {
            margin-bottom: 1.5rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            background-color: #fff;
            overflow: hidden;
        }
        
        .section-header {
            padding: 0.75rem 1rem;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-weight: 500;
        }
        
        .section-body {
            padding: 1rem;
        }
        
        .section-badge {
            font-size: 0.75rem;
            font-weight: normal;
            padding: 0.25rem 0.5rem;
            margin-left: 0.5rem;
        }
        
        .decision-yes {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .decision-no {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .decision-header {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        /* Skill Level Pills */
        .skill-pill {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 50rem;
            margin-right: 0.25rem;
        }
        
        .skill-emr { background-color: #6c757d; color: white; }
        .skill-emt { background-color: #007bff; color: white; }
        .skill-aemt { background-color: #28a745; color: white; }
        .skill-intermediate { background-color: #fd7e14; color: white; }
        .skill-paramedic { background-color: #dc3545; color: white; }

                /* Info Modal Button Styles */
        .info-button {
            margin: 0.5rem 0;
            font-size: 0.85rem;
            padding: 0.25rem 0.5rem;
        }
        
        .modal-body {
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .modal-body h4 {
            color: var(--primary-color);
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .modal-body ul {
            padding-left: 1.5rem;
        }

        /* Protocol Specific Styles */
        .protocol-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1.5rem 0;
            margin-bottom: 2rem;
        }
        
        .protocol-section {
            margin-bottom: 1.5rem;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            background-color: #fff;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: box-shadow 0.2s ease-in-out;
        }
        
        .protocol-section:hover {
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }
        
        .section-header {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #dee2e6;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }
        
        /* Different background colors for different section types */
        .section-header-entry_point {
            background-color: #e8f4f8;
            border-left: 4px solid #106e9e;
        }
        
        .section-header-treatment {
            background-color: #e8f8ef;
            border-left: 4px solid #28a745;
        }
        
        .section-header-decision {
            background-color: #fff8e8;
            border-left: 4px solid #ffc107;
        }
        
        .section-header-note {
            background-color: #f8f9fa;
            border-left: 4px solid #6c757d;
        }
        
        .section-header-reference {
            background-color: #f0e8f8;
            border-left: 4px solid #6f42c1;
        }
        
        .section-header i {
            font-size: 1.2rem;
            margin-right: 0.75rem;
            color: #495057;
        }
        
        .section-body {
            padding: 1.25rem;
            color: #212529;
            line-height: 1.6;
            width: 100%;
        }
        
        .section-body p {
            margin-bottom: 1rem;
        }
        
        .section-body ul, .section-body ol {
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .section-badge {
            font-size: 0.75rem;
            font-weight: normal;
            padding: 0.25rem 0.5rem;
            margin-left: 0.5rem;
        }
        
        .decision-yes {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #28a745;
        }
        
        .decision-no {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #dc3545;
        }
        
        .decision-header {
            font-weight: 600;
            font-size: 1.05rem;
            margin-bottom: 0.75rem;
            color: #495057;
        }
        
        /* Info Modal Button Styling */
        .info-button {
            margin: 0.5rem 0;
            font-size: 0.85rem;
            padding: 0.25rem 0.5rem;
            transition: all 0.2s ease;
        }
        
        .info-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .modal-body {
            font-size: 1rem;
            line-height: 1.6;
        }
        
        .modal-body h4 {
            color: var(--primary-color);
            margin-top: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .modal-body ul {
            padding-left: 1.5rem;
        }
        
        /* Skill Level Pills */
        .skill-pills-container {
            margin-left: auto;
            display: flex;
            gap: 0.3rem;
        }
        
        .skill-pill {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 50rem;
        }
        /* Skill Level Pills - Add these updated styles to frontend_header.php */
.section-header {
    flex-direction: column;
    align-items: flex-start;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #dee2e6;
    font-weight: 600;
    font-size: 1.1rem;
}

.section-header-title {
    display: flex;
    width: 100%;
    align-items: center;
}

.section-header-title i {
    font-size: 1.2rem;
    margin-right: 0.75rem;
    color: #495057;
}

.skill-pills-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.3rem;
    margin-top: 0.5rem;
    width: 100%;
}

/* Add to frontend_header.php - Additional Skill Pill styling */
.skill-pill {
    display: inline-block;
    padding: 0.35em 0.65em;
    font-size: 0.75em;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 50rem;
    cursor: help; /* Changes cursor to indicate there's tooltip help */
    transition: transform 0.1s ease;
}

.skill-pill:hover {
    transform: scale(1.05);
}

/* Basic provider level styling */
.skill-emr { background-color: #6c757d; color: white; }
.skill-emt { background-color: #007bff; color: white; }
.skill-emt-a { background-color: #28a745; color: white; }
.skill-emt-i { background-color: #fd7e14; color: white; }
.skill-emt-p { background-color: #dc3545; color: white; }
.skill-emt-cc { background-color: #6610f2; color: white; }
.skill-rn { background-color: #17a2b8; color: white; }

/* For backward compatibility */
.skill-aemt { background-color: #28a745; color: white; }
.skill-intermediate { background-color: #fd7e14; color: white; }
.skill-paramedic { background-color: #dc3545; color: white; }

/* Tooltip custom styling */
.tooltip .tooltip-inner {
    max-width: 300px;
    padding: 0.5rem 0.75rem;
    background-color: rgba(0, 0, 0, 0.8);
    font-size: 0.85rem;
}
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <a href="index.php">Northern Colorado</a>
                    </h1>
                    <p class="mb-0">Prehospital Protocols</p>
                </div>
                <div>
                    <form action="search.php" method="GET" class="d-flex">
                        <input type="text" name="q" class="form-control form-control-sm me-2" placeholder="Search protocols...">
                        <button type="submit" class="btn btn-sm btn-light">
                            <i class="ti ti-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="container">
            <ul class="nav nav-tabs border-bottom-0">
                <li class="nav-item">
                    <a class="nav-link <?= $active_tab === 'protocols' ? 'active' : '' ?>" href="index.php">
                        <i class="ti ti-file-text me-1"></i> Protocols
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $active_tab === 'medications' ? 'active' : '' ?>" href="category.php?id=12">
                        <i class="ti ti-pill me-1"></i> Medications
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $active_tab === 'tools' ? 'active' : '' ?>" href="tools.php">
                        <i class="ti ti-tools me-1"></i> Tools
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="py-4">
        <div class="container">