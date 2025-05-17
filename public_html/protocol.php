<?php
/**
 * Simplified Protocol Display Page
 * 
 * Place this file in: /protocol_simple.php
 */

// Include required files
require_once 'includes/config.php';
require_once 'includes/db.php';
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

// Get protocol content
$protocol_content = db_get_row(
    "SELECT content FROM protocol_content WHERE protocol_id = ?",
    [$protocol_id]
);

$content = '';
if ($protocol_content) {
    $content = $protocol_content['content'];
}

// Get protocol diagram
$diagram = db_get_row(
    "SELECT * FROM protocol_diagrams WHERE protocol_id = ?",
    [$protocol_id]
);

// Get PDF diagram if available
$pdf_diagram = db_get_row(
    "SELECT * FROM protocol_diagrams_pdf WHERE protocol_id = ?",
    [$protocol_id]
);

// Get category data
$category = get_category($protocol['category_id']);

// Set page title and active tab
$page_title = $protocol['protocol_number'] . '. ' . $protocol['title'];
$active_tab = 'protocols';

// Check if user is logged in as admin
$showEditButton = false;
if (function_exists('is_logged_in') && is_logged_in()) {
    $showEditButton = true;
}

// For preview mode, only output the content
if ($is_preview) {
    echo $content;
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
            <div>
                <a href="<?= $base_url ?>/admin/protocol_edit_simple.php?id=<?= $protocol_id ?>" class="btn btn-primary btn-sm me-2">
                    <i class="ti ti-edit me-1"></i> Edit Content
                </a>
                <a href="<?= $base_url ?>/admin/protocol_edit_drawio.php?id=<?= $protocol_id ?>" class="btn btn-success btn-sm">
                    <i class="ti ti-chart-line me-1"></i> Edit Diagram
                </a>
            </div>
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
    <?php if ($pdf_diagram && !empty($pdf_diagram['filename'])): ?>
        <!-- Display PDF using PDF.js -->
        <div class="protocol-diagram mt-4 mb-5">
            <div class="pdf-container">
                <!-- PDF viewer container with an outer scrollable wrapper -->
                <div class="pdf-viewer-scrollable-wrapper">
                    <div id="pdf-viewer-container" class="pdf-viewer">
                        <!-- Fullscreen button - now inside the viewer at the top -->
                        <div class="fullscreen-btn-container">
                            <a href="/assets/diagrams/<?= $pdf_diagram['filename'] ?>" class="btn btn-primary btn-sm" target="_blank">
                                <i class="ti ti-maximize me-1"></i> View Fullscreen
                            </a>
                        </div>
                        
                        <div id="loading-indicator" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading PDF...</span>
                            </div>
                            <p class="mt-3">Loading diagram...</p>
                        </div>
                        
                        <!-- Error container will be placed at the bottom -->
                        <div id="error-container" class="mt-4"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Include PDF.js from CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
        
        <style>
            .pdf-container {
                width: 100%;
                max-width: 1250px;
                margin: 0 auto;
            }
            
            .pdf-viewer-scrollable-wrapper {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .pdf-viewer {
                width: 100%;
                background: #f5f5f5;
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
                overflow: visible;
                min-height: 500px;
                padding: 10px;
                position: relative;
            }
            
            .fullscreen-btn-container {
                text-align: left;
                margin-bottom: 15px;
                position: sticky;
                top: 10px;
                z-index: 10;
            }
            
            .pdf-page-wrapper {
                margin: 20px auto;
                background: white;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                display: block;
                text-align: center;
            }
            
            .pdf-page-wrapper canvas {
                display: inline-block;
                max-width: 100%;
                height: auto !important;
            }
            
            @media (max-width: 768px) {
                .pdf-viewer {
                    min-height: 400px;
                    padding: 5px;
                }
                
                .pdf-page-wrapper {
                    margin: 10px auto;
                }
            }
        </style>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize PDF viewer
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
            
            const pdfUrl = window.location.origin + '/assets/diagrams/<?= $pdf_diagram['filename'] ?>';
            const container = document.getElementById('pdf-viewer-container');
            const errorContainer = document.getElementById('error-container');
            const loadingIndicator = document.getElementById('loading-indicator');
            
            // Detect if we're on mobile
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            
            // Load the PDF
            pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
                console.log('PDF loaded, ' + pdf.numPages + ' pages found');
                
                // Hide loading indicator
                loadingIndicator.style.display = 'none';
                
                // Get container width for responsive scaling
                const containerWidth = container.clientWidth;
                
                // Process all pages
                for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                    pdf.getPage(pageNum).then(function(page) {
                        // Create wrapper for this page
                        const wrapper = document.createElement('div');
                        wrapper.className = 'pdf-page-wrapper';
                        
                        // Don't overwrite the fullscreen button
                        container.insertBefore(wrapper, errorContainer);
                        
                        // Get original viewport
                        const viewport = page.getViewport({ scale: 1.0 });
                        
                        // Calculate aspect ratio
                        const aspectRatio = viewport.width / viewport.height;
                        
                        // Calculate scale based on container width, maintaining aspect ratio
                        let scale;
                        if (isMobile) {
                            // For mobile, fit width but use higher resolution
                            scale = (containerWidth * 0.95) / viewport.width * 1.5;
                        } else {
                            // For desktop, use even higher resolution
                            scale = Math.min(3.0, containerWidth / viewport.width * 1.5);
                        }
                        
                        // Create scaled viewport
                        const scaledViewport = page.getViewport({ scale: scale });
                        
                        // Create canvas for rendering
                        const canvas = document.createElement('canvas');
                        wrapper.appendChild(canvas);
                        
                        // Set dimensions with high DPI support
                        const pixelRatio = window.devicePixelRatio || 1;
                        canvas.width = scaledViewport.width * pixelRatio;
                        canvas.height = scaledViewport.height * pixelRatio;
                        canvas.style.width = scaledViewport.width + 'px';
                        canvas.style.height = scaledViewport.height + 'px';
                        
                        // Get context and scale for high DPI
                        const context = canvas.getContext('2d');
                        context.scale(pixelRatio, pixelRatio);
                        
                        // Render the page
                        page.render({
                            canvasContext: context,
                            viewport: scaledViewport
                        });
                    });
                }
                
            }).catch(function(error) {
                console.error('Error loading PDF:', error);
                
                // Hide loading indicator
                loadingIndicator.style.display = 'none';
                
                // Show error in the error container at the bottom
                errorContainer.innerHTML = `
                    <div class="alert alert-warning">
                        <p>Error loading the PDF diagram. You can:</p>
                        <a href="/assets/diagrams/<?= $pdf_diagram['filename'] ?>" class="btn btn-primary mt-2" target="_blank">Open PDF directly</a>
                    </div>
                `;
            });
        });
        </script>
    <?php elseif ($diagram && !empty($diagram['xml_content'])): ?>
        <!-- Fallback to Draw.io viewer if PDF is not available -->
        <div class="protocol-diagram mt-4 mb-5">
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
        
        <!-- Include the Draw.io viewer script -->
        <script src="https://app.diagrams.net/js/viewer.min.js"></script>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('diagram-container');
            const xmlContent = document.getElementById('diagram-xml-content');
            
            if (!container || !xmlContent) {
                return;
            }
            
            try {
                // Get the XML content
                const xml = xmlContent.textContent || xmlContent.innerText;
                
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
                
                // Wait for the iframe to load
                iframe.onload = function() {
                    // Send the XML to the iframe
                    setTimeout(function() {
                        iframe.contentWindow.postMessage(xml, '*');
                    }, 1000);
                };
            } catch (e) {
                console.error('Failed to initialize diagram viewer:', e);
                container.innerHTML = '<div class="alert alert-danger">Error loading diagram. Please try refreshing the page.</div>';
            }
        });
        </script>
    <?php endif; ?>

    <?php if (empty($content)): ?>
        <div class="alert alert-info mt-4">
            This protocol is currently being developed. Check back soon.
        </div>
    <?php else: ?>
        <div class="protocol-content mt-4">
            <?= $content ?>
        </div>
    <?php endif; ?>

    <!-- Protocol Metadata -->
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

<?php
// Include footer
include 'includes/frontend_footer.php';
?>