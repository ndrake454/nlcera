<?php
/**
 * Delete Protocol
 * 
 * Place this file in: /admin/protocol_delete.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require login
require_login();

// Get protocol ID from query string
$protocol_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Get protocol data
$protocol = get_protocol($protocol_id);

// Check if protocol exists
if (!$protocol) {
    set_flash_message('error', 'Protocol not found.');
    header('Location: protocols.php');
    exit;
}

// Process confirmation
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    // Start a transaction to ensure all related data is deleted
    $pdo = get_db_connection();
    $pdo->beginTransaction();
    
    try {
        // Delete all protocol sections and related data first
        $sections = db_get_rows(
            "SELECT id FROM protocol_sections WHERE protocol_id = ?",
            [$protocol_id]
        );
        
        foreach ($sections as $section) {
            // Delete decision branches
            db_delete('decision_branches', 'section_id = ?', [$section['id']]);
        }
        
        // Delete protocol sections
        db_delete('protocol_sections', 'protocol_id = ?', [$protocol_id]);
        
        // Delete protocol
        db_delete('protocols', 'id = ?', [$protocol_id]);
        
        // Commit the transaction
        $pdo->commit();
        
        set_flash_message('success', 'Protocol deleted successfully.');
        header('Location: protocols.php');
        exit;
    } catch (Exception $e) {
        // Rollback the transaction on error
        $pdo->rollBack();
        
        set_flash_message('error', 'Failed to delete protocol: ' . $e->getMessage());
        header('Location: protocols.php');
        exit;
    }
}

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header bg-danger text-white">
        <h5 class="mb-0">Confirm Delete Protocol</h5>
    </div>
    
    <div class="card-body">
        <div class="alert alert-warning">
            <i class="ti ti-alert-triangle me-2"></i>
            <strong>Warning:</strong> This action cannot be undone.
        </div>
        
        <p>Are you sure you want to delete the following protocol?</p>
        
        <div class="mb-4">
            <h5><?= $protocol['protocol_number'] ?>. <?= $protocol['title'] ?></h5>
            <p><strong>Category:</strong> <?= $protocol['category_name'] ?></p>
            <?php if (!empty($protocol['description'])): ?>
                <p><?= $protocol['description'] ?></p>
            <?php endif; ?>
        </div>
        
        <div class="d-flex justify-content-between">
            <a href="protocols.php" class="btn btn-secondary">
                <i class="ti ti-x"></i> Cancel
            </a>
            <a href="protocol_delete.php?id=<?= $protocol_id ?>&confirm=yes" class="btn btn-danger">
                <i class="ti ti-trash"></i> Delete Protocol
            </a>
        </div>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>