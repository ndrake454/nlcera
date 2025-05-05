<?php
/**
 * Tools Page with Matching Card Styling
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
    <!-- GCS Calculator -->
    <div class="col">
        <a href="<?= $base_url ?>/tools/gcs.php" class="category-card-link">
            <div class="card-body">
                <div class="category-icon">
                    <i class="ti ti-user"></i>
                </div>
                <h4>GCS Calculator</h4>
                <p>Calculate Glasgow Coma Scale scores</p>
            </div>
        </a>
    </div>
    
    <!-- NIHSS Calculator -->
    <div class="col">
        <a href="<?= $base_url ?>/tools/nihss.php" class="category-card-link">
            <div class="card-body">
                <div class="category-icon">
                    <i class="ti ti-brain"></i>
                </div>
                <h4>NIHSS</h4>
                <p>NIH Stroke Scale calculator</p>
            </div>
        </a>
    </div>
    
    <!-- Intubation Checklist -->
    <div class="col">
        <a href="<?= $base_url ?>/tools/intubation.php" class="category-card-link">
            <div class="card-body">
                <div class="category-icon">
                    <i class="ti ti-stethoscope"></i>
                </div>
                <h4>Intubation Checklist</h4>
                <p>Intubation procedure checklist</p>
            </div>
        </a>
    </div>
    
    <!-- I-Gel/SGA Checklist -->
    <div class="col">
        <a href="<?= $base_url ?>/tools/igel.php" class="category-card-link">
            <div class="card-body">
                <div class="category-icon">
                    <i class="ti ti-lungs"></i>
                </div>
                <h4>I-Gel Checklist</h4>
                <p>I-Gel insertion procedure checklist</p>
            </div>
        </a>
    </div>
    
    <!-- BVM / Adjunct Checklist -->
    <div class="col">
        <a href="<?= $base_url ?>/tools/bvm.php" class="category-card-link">
            <div class="card-body">
                <div class="category-icon">
                    <i class="ti ti-lungs"></i>
                </div>
                <h4>BVM Checklist</h4>
                <p>Simple BVM procedure checklist</p>
            </div>
        </a>
    </div>    

    <!-- RSI Checklist -->
    <div class="col">
        <a href="<?= $base_url ?>/tools/rsi.php" class="category-card-link">
            <div class="card-body">
                <div class="category-icon">
                    <i class="ti ti-bed-filled"></i>
                </div>
                <h4>RSI Checklist</h4>
                <p>RSI procedure checklist</p>
            </div>
        </a>
    </div>

    <!-- 421 Calculator -->
    <div class="col">
        <a href="<?= $base_url ?>/tools/421.php" class="category-card-link">
            <div class="card-body">
                <div class="category-icon">
                    <i class="ti ti-heartbeat"></i>
                </div>
                <h4>Rule of 421</h4>
                <p>Fluid maintenance calculator</p>
            </div>
        </a>
    </div>
    
    <!-- Ideal Body Weight Calculator -->
    <div class="col">
        <a href="<?= $base_url ?>/tools/ibw.php" class="category-card-link">
            <div class="card-body">
                <div class="category-icon">
                    <i class="ti ti-scale"></i>
                </div>
                <h4>IBW Calculator</h4>
                <p>Ideal body weight calculation</p>
            </div>
        </a>
    </div>

    <!-- DKA / HHS Protocol Calculator -->
    <div class="col">
        <a href="<?= $base_url ?>/tools/dkahhs.php" class="category-card-link">
            <div class="card-body">
                <div class="category-icon">
                    <i class="ti ti-flask"></i>
                </div>
                <h4>Hyperglycemia Calculator</h4>
                <p>Insulin calculator for DKA / HHS</p>
            </div>
        </a>
    </div>
    
    <!-- O2 Calculator -->
    <div class="col">
        <a href="<?= $base_url ?>/tools/o2.php" class="category-card-link">
            <div class="card-body">
                <div class="category-icon">
                    <i class="ti ti-bottle"></i>
                </div>
                <h4>O₂ Calculator</h4>
                <p>Cylinder duration calculator</p>
            </div>
        </a>
    </div>
    
    <!-- Burns Calculator -->
    <div class="col">
        <a href="<?= $base_url ?>/tools/burn.php" class="category-card-link">
            <div class="card-body">
                <div class="category-icon">
                    <i class="ti ti-flame"></i>
                </div>
                <h4>Burns Calculator</h4>
                <p>Burns assessment and fluid calculator</p>
            </div>
        </a>
    </div>
    
    <!-- CPAP Management -->
    <div class="col">
        <a href="<?= $base_url ?>/tools/cpap.php" class="category-card-link">
            <div class="card-body">
                <div class="category-icon">
                    <i class="ti ti-mask"></i>
                </div>
                <h4>CPAP Management</h4>
                <p>CPAP setup and management guide</p>
            </div>
        </a>
    </div>
    
    <!-- Needle Cricothyrotomy -->
    <div class="col">
        <a href="<?= $base_url ?>/tools/needlecric.php" class="category-card-link">
            <div class="card-body">
                <div class="category-icon">
                    <i class="ti ti-needle"></i>
                </div>
                <h4>Needle Cricothyrotomy</h4>
                <p>Emergency needle cric procedure guide</p>
            </div>
        </a>
    </div>
    
    <!-- Surgical Cricothyrotomy -->
    <div class="col">
        <a href="<?= $base_url ?>/tools/surgcric.php" class="category-card-link">
            <div class="card-body">
                <div class="category-icon">
                    <i class="ti ti-cut"></i>
                </div>
                <h4>Surgical Cricothyrotomy</h4>
                <p>Emergency surgical airway procedure</p>
            </div>
        </a>
    </div>
</div>

<?php
// Include footer
include 'includes/frontend_footer.php';
?>