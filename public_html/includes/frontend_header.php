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
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css">
    
    <!-- Custom Styles -->
    <style>
    /* CSS Version: <?= time() ?> - Forcing cache refresh */
        :root {
            --primary-color: #106e9e;
            --secondary-color: #0c5578;
        }
        
        body {
    background-color: #E5E4E2; /* Light gray base color */
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
        
/* Enhanced styling for protocol listings within categories */
.protocol-list-item, 
.category a {
    border: 3px solid var(--primary-color);
    border-radius: 0.75rem;
    transition: all 0.3s ease;
    background-color: #fff;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem !important;
    padding: 1.25rem !important;
    display: block;
    color: inherit;
    text-decoration: none;
}

/* Hover effect similar to category cards */
.protocol-list-item:hover,
.category a:hover {
    transform: translateY(-5px);
    border-color: var(--primary-color);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.12);
    background-color: #f8fbff;
    text-decoration: none;
    color: inherit;
}

/* Style for the protocol number and title */
.protocol-list-item h5,
.category a h4 {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    transition: color 0.3s ease;
}

/* Title hover effect */
.protocol-list-item:hover h5,
.category a:hover h4 {
    color: var(--primary-color);
}

/* Style for the description */
.protocol-list-item p,
.category a p {
    color: #6c757d;
    margin-bottom: 0;
    transition: color 0.3s ease;
    line-height: 1.5;
}

/* Description hover effect */
.protocol-list-item:hover p,
.category a:hover p {
    color: #495057;
}

/* Style for back to categories button */
.back-to-categories {
    border: 2px solid var(--primary-color);
    border-radius: 0.5rem;
    transition: all 0.25s ease;
    background-color: white;
    color: var(--primary-color);
    font-weight: 500;
    padding: 0.5rem 1rem;
    text-decoration: none; /* Remove the underline */
}

.back-to-categories:hover {
    background-color: var(--primary-color);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    text-decoration: none; /* Ensure it stays gone on hover */
}

/* Category header styling */
.category-header {
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid rgba(0, 0, 0, 0.1);
}

.category-header h1 {
    color: #2c3e50;
    font-weight: 600;
}

.category-header p {
    color: #6c757d;
    font-size: 1.15rem;
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
.skill-pills-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.3rem;
    margin-top: 0.5rem;
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
    cursor: help;
    transition: transform 0.1s ease;
}

.skill-pill:hover {
    transform: scale(1.05);
}

/* Provider level styling */
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

        /* Increase spacing between protocol items */
        .protocol-list-item {
            border-left: 3px solid transparent;
            transition: border-color 0.2s ease-in-out, background-color 0.2s ease-in-out;
            margin-bottom: 1rem !important; /* Add more bottom margin */
        }

        /* Also add spacing to the protocol boxes on category pages */
        .list-group-item {
            margin-bottom: 0.5rem !important;
        }

        /* Remove margin from the last item in each group to maintain proper spacing */
        .list-group-item:last-child {
            margin-bottom: 0 !important;
        }

        /* Additional styling for category.php protocol items */
        .category-protocols .list-group {
            margin-bottom: 2rem; /* Space between protocol groups if applicable */
        }

        /* Special styling for the boxes in your screenshot */
        .protocol-section {
            margin-bottom: 1.5rem; /* Increase from original value */
            border-radius: 0.5rem; /* Slightly larger radius for a more spaced look */
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); /* Subtle shadow for depth */
        }

        /* Add space between category items on the homepage */
        .col .card.category-card {
            margin-bottom: 1.5rem;
        }

        /* Sticky header styles */
        .header-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Add padding to the body to prevent content from being hidden under the fixed header */
        body {
            padding-top: 122px; /* Adjust this value based on your header's actual height */
        }

        /* Add this CSS to your frontend_header.php style section */

        /* Make the entire category card clickable with full border */
        .category-card-link {
            display: block;
            color: inherit;
            text-decoration: none;
            height: 100%;
            border-radius: 1.5rem;
            border: 3px solid var(--primary-color);
            transition: all 0.3s ease;
            background-color: #fff;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.56);
            overflow: hidden;
            position: relative;
        }

        /* Add hover effect with contrasting color border all around */
        .category-card-link:hover, .category-card-link:focus {
            text-decoration: none;
            color: inherit;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            border-color: var(--primary-color);
            background-color: #f8fbff; /* Even more subtle blue tint on hover */
        }

        /* Ensure the card has adequate padding */
        .category-card-link .card-body {
            padding: 2.5rem 1.5rem;
            text-align: center;
        }

        /* Style for the icon with improved sizing */
        .category-card-link .category-icon {
            font-size: 3.25rem;
            height: 70px; /* Fixed height for consistency */
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.75rem;
            color: var(--primary-color);
            transition: all 0.3s ease;
        }

        /* Enhance icon on hover */
        .category-card-link:hover .category-icon {
            transform: scale(1.1);
            color: var(--secondary-color); /* Slightly darker shade on hover */
        }

        /* Style for the title with improved typography */
        .category-card-link h4 {
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: #2c3e50; /* Darker text for better contrast */
            transition: color 0.3s ease;
            font-size: 1.35rem;
            line-height: 1.3;
        }

        /* Title hover effect */
        .category-card-link:hover h4 {
            color: var(--primary-color);
        }

        /* Style for the description text */
        .category-card-link p {
            color: #6c757d;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
            line-height: 1.5;
        }

        /* Description hover effect */
        .category-card-link:hover p {
            color: #495057; /* Slightly darker on hover for better readability */
        }

        /* Add subtle inner highlight for depth */
        .category-card-link:after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(to bottom, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0) 100%);
            opacity: 0.7;
            pointer-events: none;
        }

        /* Adjust card for consistency with image icons */
        .category-card-link img {
            height: 70px;
            width: auto;
            margin-bottom: 1.75rem;
            transition: transform 0.3s ease;
            /* Apply a filter to change the SVG color to your primary blue */
            filter: invert(30%) sepia(74%) saturate(1115%) hue-rotate(177deg) brightness(93%) contrast(96%);
        }

        .category-card-link:hover img {
            transform: scale(1.3);
        }

        .category-card-link:hover .category-number {
            opacity: 0.8;
        }

        /* For Firefox which doesn't handle background transitions well */
        @-moz-document url-prefix() {
            .category-card-link {
                background-image: none;
            }
        }

/* Clinical component styling */
/* Red Arrow */
.red-arrow {
    display: flex;
    justify-content: center;
    padding: 15px 0;
    width: 100%;
}
.red-arrow svg {
    width: 60px;
    height: 60px;
    color: #dc3545;
}

/* Clinical Component Headers - more specific and with !important */
.section-header.section-header-entry_point, 
.section-header-entry_point {
    background-color: #e8f4f8 !important;
    border-left: 4px solid #106e9e !important;
}

.section-header.section-header-treatment, 
.section-header-treatment {
    background-color: #e8f8ef !important;
    border-left: 4px solid #28a745 !important;
}

.section-header.section-header-decision, 
.section-header-decision {
    background-color: #fff8e8 !important;
    border-left: 4px solid #ffc107 !important;
}

.section-header.section-header-note, 
.section-header-note {
    background-color: #f8f9fa !important;
    border-left: 4px solid #6c757d !important;
}

.section-header.section-header-reference, 
.section-header-reference {
    background-color: #f0e8f8 !important;
    border-left: 4px solid #6f42c1 !important;
}

.section-header.section-header-indications, 
.section-header-indications {
    background-color: #e8eaf6 !important;
    border-left: 4px solid #3f51b5 !important;
}

.section-header.section-header-contraindications, 
.section-header-contraindications {
    background-color: #ffebee !important;
    border-left: 4px solid #f44336 !important;
}

.section-header.section-header-side_effects, 
.section-header-side_effects {
    background-color: #fff8e1 !important;
    border-left: 4px solid #ffc107 !important;
}

.section-header.section-header-precautions, 
.section-header-precautions {
    background-color: #e0f2f1 !important;
    border-left: 4px solid #009688 !important;
}

.section-header.section-header-technique, 
.section-header-technique {
    background-color: #f3e5f5 !important;
    border-left: 4px solid #9c27b0 !important;
}
    
/* START DIRECT CSS FIX */
/* Reset section-header styles */
.section-header {
  background-color: #f8f9fa !important;
  border-left: 4px solid #6c757d !important;
}

/* Force specific styling for each section type */
.section-header-entry_point,
div[class*="section-header-entry_point"] {
  background-color: #e8f4f8 !important;
  border-left: 4px solid #106e9e !important;
}

.section-header-treatment,
div[class*="section-header-treatment"] {
  background-color: #e8f8ef !important;
  border-left: 4px solid #28a745 !important;
}

.section-header-decision,
div[class*="section-header-decision"] {
  background-color: #fff8e8 !important;
  border-left: 4px solid #ffc107 !important;
}

.section-header-note,
div[class*="section-header-note"] {
  background-color: #f8f9fa !important;
  border-left: 4px solid #6c757d !important;
}

.section-header-reference,
div[class*="section-header-reference"] {
  background-color: #f0e8f8 !important;
  border-left: 4px solid #6f42c1 !important;
}

.section-header-indications,
div[class*="section-header-indications"] {
  background-color: #e8eaf6 !important;
  border-left: 4px solid #3f51b5 !important;
}

.section-header-contraindications,
div[class*="section-header-contraindications"] {
  background-color: #ffebee !important;
  border-left: 4px solid #f44336 !important;
}

.section-header-side_effects,
div[class*="section-header-side_effects"] {
  background-color: #fff8e1 !important;
  border-left: 4px solid #ffc107 !important;
}

.section-header-precautions,
div[class*="section-header-precautions"] {
  background-color: #e0f2f1 !important;
  border-left: 4px solid #009688 !important;
}

.section-header-technique,
div[class*="section-header-technique"] {
  background-color: #f3e5f5 !important;
  border-left: 4px solid #9c27b0 !important;
}

/* Add direct styling for section types via HTML attributes */
[data-section-type="entry_point"] .section-header {
  background-color: #e8f4f8 !important;
  border-left: 4px solid #106e9e !important;
}

[data-section-type="treatment"] .section-header {
  background-color: #e8f8ef !important;
  border-left: 4px solid #28a745 !important;
}

[data-section-type="decision"] .section-header {
  background-color: #fff8e8 !important;
  border-left: 4px solid #ffc107 !important;
}

[data-section-type="note"] .section-header {
  background-color: #f8f9fa !important;
  border-left: 4px solid #6c757d !important;
}

[data-section-type="reference"] .section-header {
  background-color: #f0e8f8 !important;
  border-left: 4px solid #6f42c1 !important;
}

[data-section-type="indications"] .section-header {
  background-color: #e8eaf6 !important;
  border-left: 4px solid #3f51b5 !important;
}

[data-section-type="contraindications"] .section-header {
  background-color: #ffebee !important;
  border-left: 4px solid #f44336 !important;
}

[data-section-type="side_effects"] .section-header {
  background-color: #fff8e1 !important;
  border-left: 4px solid #ffc107 !important;
}

[data-section-type="precautions"] .section-header {
  background-color: #e0f2f1 !important;
  border-left: 4px solid #009688 !important;
}

[data-section-type="technique"] .section-header {
  background-color: #f3e5f5 !important;
  border-left: 4px solid #9c27b0 !important;
}

/* For protocol_content.php - Ensure white background on section body */
.section-body {
  background-color: white !important;
  padding: 1.25rem !important;
}

/* Force all protocol sections to have a white background and proper margins */
.protocol-section {
  margin-bottom: 1.5rem !important;
  background-color: white !important;
  border: 1px solid #dee2e6 !important;
  border-radius: 0.375rem !important;
  overflow: hidden !important;
}
.decision-other {
    background-color: #fff3cd;  /* Light yellow background */
    border: 1px solid #ffeeba;
    border-left: 4px solid #ffc107;  /* Yellow left border */
    border-radius: 0.375rem;
    padding: 1rem;
    margin-bottom: 1rem;
}
/* Protocol item styling for category page */
.list-group a, 
a.list-group-item,
a.protocol-list-item {
    display: block;
    text-decoration: none;
    color: inherit;
    border: 3px solid var(--primary-color) !important;
    border-radius: 0.75rem !important;
    background-color: #fff;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 1rem;
    padding: 1.25rem;
    transition: all 0.3s ease;
}

.list-group a:hover, 
a.list-group-item:hover,
a.protocol-list-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.12);
    background-color: #f8fbff;
    border-color: var(--primary-color) !important; 
    text-decoration: none;
    color: inherit;
}

/* Override any conflicting styles */
.list-group-item {
    border-left: 3px solid var(--primary-color) !important;
    border-right: 3px solid var(--primary-color) !important;
    border-top: 3px solid var(--primary-color) !important;
    border-bottom: 3px solid var(--primary-color) !important;
}

/* Search button styling */
.btn-search {
    padding: 0.5rem;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    background-color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn-search:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
}

.btn-search i {
    font-size: 1.2rem;
    color: var(--primary-color);
}
/* Contact Base Required Styling */
.contact-base-required {
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.5), 0 3px 10px rgba(220, 53, 69, 0.3) !important;
    position: relative;
}

.contact-base-container {
    display: flex;
    align-items: center;
}

.contact-base-badge {
    display: inline-flex;
    align-items: center;
    background-color: #dc3545;
    color: white;
    padding: 0.3rem 0.6rem;
    border-radius: 4px;
    font-size: 0.8rem;
    font-weight: bold;
    animation: pulse-red 2s infinite;
}

.contact-base-badge i {
    margin-right: 0.3rem;
    font-size: 1rem;
}

@keyframes pulse-red {
    0% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7);
    }
    70% {
        box-shadow: 0 0 0 7px rgba(220, 53, 69, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
    }
}

/* Make the contact badge more prominent */
.contact-base-badge {
    background-color: #dc3545;
    border: 1px solid #c82333;
}

</style>
</head>
<body>
    <!-- Header -->
<div class="header-wrapper">
    <header class="header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <a href="index.php">Colorado</a>
                    </h1>
                    <p class="mb-0">Prehospital Protocols</p>
                </div>
                <div>
                    <a href="search.php" class="btn btn-light btn-search">
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