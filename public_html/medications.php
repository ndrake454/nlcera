<?php
/**
 * Medications Page
 * Displays all medication protocols
 * 
 * Place this file in: /medications.php
 */

// Include required files
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Set medications category ID
$medications_category_id = 12;  // Change this if your medications category ID is different

// Get category data
$category = get_category($medications_category_id);

// Check if category exists
if (!$category) {
    // Fallback to show all categories if the medications category doesn't exist
    header('Location: index.php');
    exit;
}

// Get protocols in the medications category
$protocols = get_category_protocols($medications_category_id);

// Set page title and active tab
$page_title = 'Medications';
$active_tab = 'medications';  // This is important for the navigation highlighting

// Include header
include 'includes/frontend_header.php';
?>

<header class="mb-5">
    <h1>Medications</h1>
    <p class="lead">
        Prehospital medication reference including indications, contraindications, dosages, and special considerations
    </p>
</header>

<?php if (empty($protocols)): ?>
    <div class="alert alert-info">
        No medication protocols found.
    </div>
<?php else: ?>
    <div class="row">
        <!-- Alphabetical Index -->
        <div class="col-md-3 mb-4">
            <div class="card sticky-top" style="top: 1rem;">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Index</h5>
                </div>
                <div class="card-body p-2">
                    <div class="list-group list-group-flush">
                        <?php
                        // Group medications alphabetically
                        $alphabetical = [];
                        foreach ($protocols as $protocol) {
                            // Get first letter
                            $first_letter = strtoupper(substr($protocol['title'], 0, 1));
                            if (!isset($alphabetical[$first_letter])) {
                                $alphabetical[$first_letter] = [];
                            }
                            $alphabetical[$first_letter][] = $protocol;
                        }
                        
                        // Sort by letter
                        ksort($alphabetical);
                        
                        // Display letter links
                        foreach (array_keys($alphabetical) as $letter): ?>
                            <a href="#letter-<?= $letter ?>" class="list-group-item list-group-item-action py-2 px-3">
                                <?= $letter ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Medication Listing -->
        <div class="col-md-9">
            <?php 
            // Display medications grouped by first letter
            foreach ($alphabetical as $letter => $meds): ?>
                <div class="mb-4">
                    <h3 id="letter-<?= $letter ?>" class="border-bottom pb-2"><?= $letter ?></h3>
                    <div class="list-group">
                        <?php foreach ($meds as $med): ?>
                            <a href="protocol.php?id=<?= $med['id'] ?>" class="list-group-item list-group-item-action protocol-list-item p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-1"><?= $med['title'] ?></h5>
                                    <span class="badge bg-primary rounded-pill">
                                        <i class="ti ti-arrow-right"></i>
                                    </span>
                                </div>
                                <?php if (!empty($med['description'])): ?>
                                    <p class="mb-1 text-muted"><?= $med['description'] ?></p>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php
// Include footer
include 'includes/frontend_footer.php';
?>