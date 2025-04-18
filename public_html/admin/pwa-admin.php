<?php
/**
 * PWA Admin Management Page
 * 
 * Place this file in: /admin/pwa-admin.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require admin role
require_admin();

// Set page title
$page_title = 'PWA Management';

// Get stats
$protocol_count = db_get_row("SELECT COUNT(*) as count FROM protocols WHERE is_active = 1")['count'];
$category_count = db_get_row("SELECT COUNT(*) as count FROM categories")['count'];

// Create logs directory if it doesn't exist
$logDir = dirname(__DIR__) . '/logs';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}

// Include header
include 'includes/header.php';
?>

<script src="../assets/js/cache-all.js"></script>

<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">Progressive Web App Management</h5>
    </div>
    
    <div class="card-body">
        <div class="alert alert-info">
            <h5><i class="ti ti-device-mobile me-2"></i> PWA Overview</h5>
            <p>This panel allows you to manage the Progressive Web App functionality of your protocols site.</p>
            <p>PWAs allow users to install the protocols site on their device and access it offline.</p>
        </div>
        
        <?php 
        // Check if logs directory exists and is writable
        if (!is_dir($logDir) || !is_writable($logDir)) {
            echo '<div class="alert alert-warning mb-4">
                <h5><i class="ti ti-alert-triangle me-2"></i> Logs Directory Issue</h5>
                <p>The logs directory does not exist or is not writable. Create this directory with proper permissions:</p>
                <pre>mkdir -p ' . $logDir . '
chmod 755 ' . $logDir . '</pre>
            </div>';
        }
        ?>
        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Statistics</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Active Protocols:</strong> <?= $protocol_count ?></p>
                        <p><strong>Categories:</strong> <?= $category_count ?></p>
                        <p><strong>PWA Version:</strong> v1-<?= date('Ymd') ?></p>
                        <p>
                            <strong>Last Sync:</strong> 
                            <?php
                            $logFile = $logDir . '/pwa_sync.log';
                            if (file_exists($logFile)) {
                                $logs = @file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                                if (!empty($logs)) {
                                    $lastLine = $logs[count($logs) - 1];
                                    if (preg_match('/PWA Sync completed successfully at ([\d-]+ [\d:]+)/', $lastLine, $matches)) {
                                        echo $matches[1];
                                    } else {
                                        echo 'Unknown (check logs)';
                                    }
                                } else {
                                    echo 'No logs found';
                                }
                            } else {
                                echo 'Never run';
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Manual Cache</h5>
                    </div>
                    <div class="card-body">
                        <p>Trigger a manual cache of all protocols and site content.</p>
                        <p>This will ensure all content is available offline for users who have installed the PWA.</p>
                        <p class="mb-3">Use this after making significant content changes.</p>
                        
                        <button class="btn btn-primary mb-3" id="cache-all-button">
                            <i class="ti ti-database-export me-2"></i> Cache All Content
                        </button>
                        
                        <div id="cache-status"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">PWA Configuration</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-3">Service Worker Location</h6>
                        <p class="mb-1"><code>/service-worker.js</code></p>
                        <p class="mb-3 small text-muted">This file should be in the root directory.</p>
                        
                        <h6 class="mb-3">Manifest File</h6>
                        <p class="mb-1"><code>/manifest.json</code></p>
                        <p class="small text-muted">This file defines how the app appears when installed.</p>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="mb-3">Cron Job Status</h6>
                        <?php
                        // Try to determine if cron job is running
                        $cronActive = false;
                        $logFile = $logDir . '/pwa_sync.log';
                        if (file_exists($logFile)) {
                            $fileTime = filemtime($logFile);
                            $cronActive = (time() - $fileTime) < 86400; // Within last 24 hours
                        }
                        ?>
                        
                        <?php if ($cronActive): ?>
                            <div class="alert alert-success">
                                <i class="ti ti-check me-2"></i> Cron job appears to be active
                            </div>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="ti ti-alert-triangle me-2"></i> Cron job may not be active
                                <p class="small mb-0 mt-1">
                                    Add this to your crontab: <br>
                                    <code>0 2 * * * php <?= $_SERVER['DOCUMENT_ROOT'] ?>/admin/pwa_sync.php</code>
                                </p>
                            </div>
                        <?php endif; ?>
                        
                        <h6 class="mb-3 mt-3">Manual Run</h6>
                        <a href="pwa_sync.php?key=<?= HASH_SALT ?>" class="btn btn-outline-secondary" target="_blank">
                            <i class="ti ti-refresh me-2"></i> Run Sync Script
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">How PWA Works</h5>
            </div>
            <div class="card-body">
                <ol class="mb-4">
                    <li class="mb-2">When users visit the site, the service worker is installed in their browser.</li>
                    <li class="mb-2">The service worker caches essential assets and pages they visit.</li>
                    <li class="mb-2">Users can install the app via their browser's "Add to Home Screen" option.</li>
                    <li class="mb-2">When offline, the service worker serves cached content.</li>
                    <li class="mb-2">The nightly cron job updates the service worker cache to ensure content is fresh.</li>
                </ol>
                
                <div class="alert alert-info">
                    <i class="ti ti-info-circle me-2"></i>
                    <strong>Tip:</strong> Encourage users to visit all protocols they might need offline before losing connection.
                    This ensures those specific protocols are cached for offline use.
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>