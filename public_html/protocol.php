<?php
/**
 * Protocol Page with Draw.io Diagram Rendering (SIMPLIFIED VERSION)
 * 
 * This file displays a protocol diagram created with Draw.io
 * Place this file in: /protocol.php
 */

// Include required files
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Get protocol ID from query string
$protocol_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Check if this is a preview request from the admin
$is_preview = isset($_GET['preview']) && $_GET['preview'] === '1';

// Get protocol data
$protocol = get_protocol($protocol_id);

// Check if protocol exists and is active (unless preview)
if (!$protocol || (!$protocol['is_active'] && !$is_preview)) {
    header('Location: index.php');
    exit;
}

// Get protocol diagram
$diagram = db_get_row(
    "SELECT * FROM protocol_diagrams WHERE protocol_id = ?",
    [$protocol_id]
);

// Get category data
$category = get_category($protocol['category_id']);

// Set page title and active tab
$page_title = $protocol['protocol_number'] . '. ' . $protocol['title'];
$active_tab = 'protocols';

// Check if user is logged in as admin - do this BEFORE any HTML output
$showEditButton = false;
if (function_exists('is_logged_in') && is_logged_in()) {
    $showEditButton = true;
}

// For preview mode, only output the content without the header/footer
if ($is_preview) {
    if ($diagram && !empty($diagram['html_content'])) {
        echo $diagram['html_content'];
    } else {
        echo '<div class="alert alert-info m-3">No diagram content available for preview.</div>';
    }
    exit;
}

// Include header for normal view
include 'includes/frontend_header.php';
?>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Protocol Categories</a></li>
        <li class="breadcrumb-item"><a href="category.php?id=<?= $category['id'] ?>"><?= $category['title'] ?></a></li>
        <li class="breadcrumb-item active"><?= $protocol['title'] ?></li>
    </ol>
</nav>

<?php if ($showEditButton): ?>
    <div class="admin-edit-bar bg-warning text-dark p-2 mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <i class="ti ti-pencil me-1"></i> You are logged in as an administrator.
            </div>
            <a href="<?= $base_url ?>/admin/protocol_edit_drawio.php?id=<?= $protocol_id ?>" class="btn btn-primary btn-sm">
                <i class="ti ti-edit me-1"></i> Edit This Protocol
            </a>
        </div>
    </div>
<?php endif; ?>

<div class="protocol-header bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-9">
                <h1 class="mb-1"><?= $protocol['protocol_number'] ?>. <?= $protocol['title'] ?></h1>
                <?php if (!empty($protocol['description'])): ?>
                    <p class="mb-0"><?= $protocol['description'] ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-3 text-md-end mt-3 mt-md-0">
                <a href="category.php?id=<?= $category['id'] ?>" class="btn btn-light">
                    <i class="ti ti-arrow-left"></i> Back to Protocols
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <?php if (!$diagram || empty($diagram['xml_content'])): ?>
        <div class="alert alert-info mt-4">
            <i class="ti ti-info-circle me-2"></i>
            This protocol is currently being developed. Check back soon.
        </div>
    <?php else: ?>
        <div class="protocol-diagram mt-4">
            <!-- Draw the SVG diagram using diagrams.net viewer -->
            <div id="diagram-container" style="width: 100%; min-height: 600px; border: 1px solid #dee2e6;">
                <div id="loading-indicator" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Loading diagram...</p>
                </div>
            </div>
            
            <!-- Store the XML content in a hidden div -->
            <div id="diagram-xml-content" style="display: none;"><?= htmlspecialchars($diagram['xml_content']) ?></div>
        </div>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Protocol page loaded, initializing diagram...');
            
            // Initialize diagram viewer after a short delay
            setTimeout(function() {
                const container = document.getElementById('diagram-container');
                const xmlContent = document.getElementById('diagram-xml-content');
                
                if (!container || !xmlContent) {
                    console.error('Diagram container or XML content not found');
                    return;
                }
                
                try {
                    // Get the XML content
                    const xml = xmlContent.textContent || xmlContent.innerText;
                    console.log('Got XML content, length:', xml.length);
                    
                    // Clear the container
                    container.innerHTML = '';
                    
                    // Create iframe for the viewer
                    const iframe = document.createElement('iframe');
                    iframe.style.width = '100%';
                    iframe.style.height = '600px';
                    iframe.style.border = 'none';
                    
                    // Append the iframe to the container
                    container.appendChild(iframe);
                    
                    // Set the source for the iframe - use a simpler viewer
                    iframe.src = 'https://viewer.diagrams.net/?highlight=0000ff&nav=1&embed=1';
                    
                    console.log('Diagram viewer iframe created, waiting for load event');
                    
                    // Wait for the iframe to load
                    iframe.onload = function() {
                        console.log('Iframe loaded, sending diagram data...');
                        
                        // Send the XML to the iframe using postMessage with a delay
                        setTimeout(function() {
                            console.log('Sending XML to viewer');
                            try {
                                // Try various formats that diagrams.net might accept
                                const plainXml = xml.trim();
                                
                                // First attempt: Send as is
                                iframe.contentWindow.postMessage(plainXml, '*');
                                
                                // Second attempt (after a delay): Try with mxfile wrapper if needed
                                setTimeout(function() {
                                    if (!plainXml.includes('<mxfile')) {
                                        console.log('XML might need mxfile wrapper, trying alternative format');
                                        const wrappedXml = `<mxfile modified="${new Date().toISOString()}">\n${plainXml}\n</mxfile>`;
                                        iframe.contentWindow.postMessage(wrappedXml, '*');
                                    }
                                }, 2000);
                            } catch (e) {
                                console.error('Error sending XML to viewer:', e);
                            }
                        }, 1000);
                        
                        // Listen for messages from the iframe
                        window.addEventListener('message', function(evt) {
                            try {
                                if (typeof evt.data === 'string') {
                                    const msg = JSON.parse(evt.data);
                                    if (msg.event === 'configure') {
                                        console.log('Diagram viewer configured');
                                    } else if (msg.event === 'load') {
                                        console.log('Diagram loaded successfully');
                                    } else if (msg.event === 'error') {
                                        console.error('Diagram error:', msg.message);
                                        // Show error in container
                                        const errorDiv = document.createElement('div');
                                        errorDiv.className = 'alert alert-danger mt-3';
                                        errorDiv.innerHTML = `<i class="ti ti-alert-triangle me-2"></i> Error loading diagram: ${msg.message}`;
                                        container.appendChild(errorDiv);
                                    }
                                }
                            } catch (e) {
                                // Not a JSON message, ignore
                            }
                        });
                    };
                } catch (error) {
                    console.error('Error initializing diagram viewer:', error);
                    container.innerHTML = '<div class="alert alert-danger">Error loading diagram. Please try refreshing the page.</div>';
                }
            }, 500); // Short delay to ensure DOM is ready
        });
        </script>
    <?php endif; ?>
    
    <!-- Add additional information or metadata here -->
    <div class="protocol-metadata mt-5 mb-4">
        <div class="card">
            <div class="card-header bg-light">
                <h5 class="mb-0">Protocol Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Protocol Number:</strong> <?= $protocol['protocol_number'] ?></p>
                        <p><strong>Category:</strong> <?= $category['title'] ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Last Updated:</strong> <?= format_datetime($protocol['updated_at']) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Debug: Show XML Content (only visible with ?debug=1 parameter) -->
    <?php if ($diagram && !empty($diagram['xml_content']) && isset($_GET['debug'])): ?>
    <div class="card mt-3">
        <div class="card-header bg-info text-white">
            Debug: XML Content
        </div>
        <div class="card-body">
            <pre style="max-height: 300px; overflow: auto;"><?= htmlspecialchars(substr($diagram['xml_content'], 0, 1000)) ?>...</pre>
            <p>XML Length: <?= strlen($diagram['xml_content']) ?> characters</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Print Styles -->
<style>
@media print {
    header, footer, nav, .admin-edit-bar, .protocol-metadata, .btn {
        display: none !important;
    }
    
    .protocol-header {
        background-color: #f8f9fa !important;
        color: #212529 !important;
        print-color-adjust: exact;
    }
    
    body {
        padding-top: 0 !important;
    }
    
    .container {
        max-width: 100% !important;
        width: 100% !important;
    }
    
    #diagram-container {
        page-break-inside: avoid;
        border: none !important;
    }
    
    /* Ensure iframe content is visible */
    iframe {
        height: 100vh !important;
    }
}
</style>

<?php
// Include footer
include 'includes/frontend_footer.php';
?>