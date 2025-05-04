<?php
/**
 * Protocol Page with Draw.io Diagram Rendering (FIXED VERSION)
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
        </div>
        
        <div class="mt-4 text-center">
            <button type="button" class="btn btn-primary" id="print-diagram-btn">
                <i class="ti ti-printer me-1"></i> Print Protocol
            </button>
            
            <button type="button" class="btn btn-outline-secondary ms-2" id="download-image-btn">
                <i class="ti ti-download me-1"></i> Download as Image
            </button>
        </div>
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Draw.io viewer
    <?php if ($diagram && !empty($diagram['xml_content'])): ?>
    initDiagramViewer();
    <?php endif; ?>
    
    // Initialize and load the diagram viewer
    function initDiagramViewer() {
        const container = document.getElementById('diagram-container');
        
        // Create iframe for the viewer
        const iframe = document.createElement('iframe');
        iframe.style.width = '100%';
        iframe.style.height = '600px';
        iframe.style.border = 'none';
        iframe.src = 'https://viewer.diagrams.net/?highlight=0000ff&nav=1&title=<?= urlencode($protocol['title']) ?>';
        
        // Replace the loading indicator with the iframe
        container.innerHTML = '';
        container.appendChild(iframe);
        
        // Wait for the iframe to load, then send the XML
        iframe.onload = function() {
            // The XML content of the diagram
            const xml = `<?= str_replace('"', '\\"', str_replace("\n", "\\n", $diagram['xml_content'])) ?>`;
            
            // Send the XML to the iframe
            iframe.contentWindow.postMessage(xml, '*');
        };
    }
    
    // Print Protocol Button
    const printBtn = document.getElementById('print-diagram-btn');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            window.print();
        });
    }
    
    // Download as Image Button
    const downloadBtn = document.getElementById('download-image-btn');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            // Create a new window with just the diagram for export
            const newWin = window.open('', '_blank');
            newWin.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title><?= $protocol['title'] ?></title>
                    <style>
                        body { margin: 0; padding: 20px; }
                    </style>
                </head>
                <body>
                    <div id="export-container" style="width:100%; height:700px;"></div>
                    <script>
                        // Create iframe for the viewer
                        const iframe = document.createElement('iframe');
                        iframe.style.width = '100%';
                        iframe.style.height = '100%';
                        iframe.style.border = 'none';
                        iframe.src = 'https://viewer.diagrams.net/?highlight=0000ff&nav=1&layers=1&lightbox=1&export=1&title=<?= urlencode($protocol['title']) ?>';
                        
                        document.getElementById('export-container').appendChild(iframe);
                        
                        // Wait for the iframe to load, then send the XML
                        iframe.onload = function() {
                            // The XML content of the diagram
                            const xml = '<?= str_replace("'", "\\'", str_replace("\n", "\\n", $diagram['xml_content'])) ?>';
                            
                            // Send the XML to the iframe
                            iframe.contentWindow.postMessage(xml, '*');
                        };
                    </script>
                </body>
                </html>
            `);
            newWin.document.close();
        });
    }
});
</script>

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