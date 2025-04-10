<?php
/**
 * Tools Page
 * 
 * Place this file in: /tools.php
 */

// Include required files
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Set page title and active tab
$page_title = 'Quick Tools';
$active_tab = 'tools';

// Include header
include 'includes/frontend_header.php';
?>

<header class="mb-5">
    <h1>Quick Tools</h1>
    <p class="lead">
        Clinical tools to assist with patient care
    </p>
</header>

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
    <div class="col">
        <div class="card h-100 category-card">
            <div class="card-body text-center p-4">
                <div class="category-icon mb-3">
                    <i class="ti ti-user"></i>
                </div>
                <h4 class="card-title">GCS Calculator</h4>
                <p class="card-text text-muted">Calculate Glasgow Coma Scale scores</p>
            </div>
            <div class="card-footer text-center bg-light">
                <a href="tool_gcs.php" class="btn btn-primary">Open Tool</a>
            </div>
        </div>
    </div>
    
    <div class="col">
        <div class="card h-100 category-card">
            <div class="card-body text-center p-4">
                <div class="category-icon mb-3">
                    <i class="ti ti-brain"></i>
                </div>
                <h4 class="card-title">NIHSS</h4>
                <p class="card-text text-muted">NIH Stroke Scale calculator</p>
            </div>
            <div class="card-footer text-center bg-light">
                <a href="tool_nihss.php" class="btn btn-primary">Open Tool</a>
            </div>
        </div>
    </div>
    
    <div class="col">
        <div class="card h-100 category-card">
            <div class="card-body text-center p-4">
                <div class="category-icon mb-3">
                    <i class="ti ti-medical-cross"></i>
                </div>
                <h4 class="card-title">Intubation Checklist</h4>
                <p class="card-text text-muted">RSI procedure checklist</p>
            </div>
            <div class="card-footer text-center bg-light">
                <a href="tool_intubation.php" class="btn btn-primary">Open Tool</a>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include 'includes/frontend_footer.php';
?>