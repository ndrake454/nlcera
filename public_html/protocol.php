<?php
/**
 * Protocol Page with PDF Diagram Support
 * 
 * This file displays a protocol with mobile-friendly PDF diagrams
 * Place this file in: /protocol.php
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

// Get protocol sections
$sections = get_protocol_sections($protocol_id);

// Get protocol XML diagram
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
    if ($pdf_diagram && !empty($pdf_diagram['filename'])) {
        // Get protocol URL
        $protocol_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $pdf_url = $protocol_url . '/assets/diagrams/' . $pdf_diagram['filename'];
        
        // Simple PDF.js viewer for preview
        echo '<div id="pdf-viewer-container"></div>';
        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>';
        echo '<script>
            pdfjsLib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js";
            
            const loadingTask = pdfjsLib.getDocument("' . $pdf_url . '");
            loadingTask.promise.then(function(pdf) {
                const container = document.getElementById("pdf-viewer-container");
                
                for(let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                    pdf.getPage(pageNum).then(function(page) {
                        const scale = 2.5; // Higher resolution for preview
                        const viewport = page.getViewport({scale: scale});
                        
                        const wrapper = document.createElement("div");
                        wrapper.className = "pdf-page-wrapper";
                        
                        const canvas = document.createElement("canvas");
                        wrapper.appendChild(canvas);
                        container.appendChild(wrapper);
                        
                        const context = canvas.getContext("2d");
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        
                        const renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        
                        page.render(renderContext);
                    });
                }
            });
        </script>';
        echo '<style>
            #pdf-viewer-container {
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            .pdf-page-wrapper {
                margin-bottom: 20px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.2);
                background: white;
            }
            .pdf-page-wrapper canvas {
                display: block;
                width: 100%;
                height: auto;
            }
        </style>';
    } elseif ($diagram && !empty($diagram['html_content'])) {
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
            <div>
                <a href="<?= $base_url ?>/admin/protocol_edit.php?id=<?= $protocol_id ?>" class="btn btn-primary btn-sm me-2">
                    <i class="ti ti-edit me-1"></i> Edit Content
                </a>
                <a href="<?= $base_url ?>/admin/protocol_edit_drawio.php?id=<?= $protocol_id ?>" class="btn btn-success btn-sm">
                    <i class="ti ti-chart-line me-1"></i> Edit Diagram
                </a>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="container">
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
    <?php if ($pdf_diagram && !empty($pdf_diagram['filename'])): ?>
        <!-- Display PDF using PDF.js -->
        <div class="protocol-diagram mt-4">
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
            <!-- Protocol Content Sections -->
    <?php if (empty($sections)): ?>

    <?php else: ?>
        <!-- Include the protocol_content.php to display sections -->
        <?php include 'includes/protocol_content.php'; ?>
    <?php endif; ?>
    
        <!-- Include PDF.js from CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
        
        <style>
            .pdf-container {
                width: 100%;
                max-width: 1250px;
                margin: 0 auto;
            }
            
            /* This wrapper makes the content scrollable on mobile */
            .pdf-viewer-scrollable-wrapper {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
            }
            
            .pdf-viewer {
                width: 100%;
                background: #f5f5f5;
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
                overflow: visible; /* Allow content to be visible outside container */
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
                display: inline-block; /* Allow centering */
                max-width: 100%;
                height: auto !important; /* Force maintain aspect ratio */
            }
            
            /* Mobile-friendly styles */
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
            // Set PDF.js worker path
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';
            
            // PDF URL
            const pdfUrl = window.location.origin + '/assets/diagrams/<?= $pdf_diagram['filename'] ?>';
            const container = document.getElementById('pdf-viewer-container');
            const errorContainer = document.getElementById('error-container');
            
            // Remove loading indicator and fullscreen button for initialization
            const loadingIndicator = document.getElementById('loading-indicator');
            const fullscreenBtn = document.querySelector('.fullscreen-btn-container');
            
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
                        // Use higher resolution for better quality
                        let scale;
                        if (isMobile) {
                            // For mobile, fit width but use higher resolution
                            scale = (containerWidth * 0.95) / viewport.width * 1.5; // 1.5x higher resolution
                        } else {
                            // For desktop, use even higher resolution
                            scale = Math.min(3.0, containerWidth / viewport.width * 1.5); // 3.0x higher max resolution
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
        <div class="protocol-diagram mt-4">
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
            
            <!-- Show a message that legacy format is being used -->
            <div class="alert alert-warning mt-2">
                <i class="ti ti-alert-triangle me-2"></i>
                This protocol is using legacy diagram format. It will be converted to a faster PDF format on the next edit.
            </div>
            
            <!-- Include the Draw.io viewer script -->
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
        </div>
    <?php else: ?>
        <!-- No diagram available -->
        <div class="alert alert-info mt-4">
            <i class="ti ti-info-circle me-2"></i>
            No diagram is available for this protocol.
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

<!-- Print Styles -->
<style>
@media print {
    header, footer, nav, .admin-edit-bar, .btn, .breadcrumb {
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
    
    .pdf-container, #diagram-container {
        page-break-inside: avoid;
        border: none !important;
    }
    
    /* Print all PDF pages properly */
    .pdf-page-wrapper {
        page-break-inside: avoid;
        break-inside: avoid;
        margin: 0 auto 20px auto !important;
        box-shadow: none !important;
    }
    
    /* Remove decorative elements */
    .alert-warning, .fullscreen-btn-container {
        display: none !important;
    }
    
    /* Ensure all protocol sections print properly */
    .protocol-section {
        page-break-inside: avoid;
        break-inside: avoid;
    }
}
</style>

<?php
// Include footer
include 'includes/frontend_footer.php';
?>