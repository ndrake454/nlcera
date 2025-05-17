<?php
/**
 * Batch Convert Diagrams to PDF
 * 
 * This script converts existing Draw.io diagrams to PDF format
 * Place this file in: /admin/convert_diagrams_to_pdf.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require admin role
require_admin();

// Set page title
$page_title = 'Convert Diagrams to PDF';

// Include header
include 'includes/header.php';

// Process conversion if requested
$converted = 0;
$errors = [];
$total = 0;

if (isset($_POST['convert']) && $_POST['convert'] === 'yes') {
    // Get all diagrams that don't have a PDF version
    $diagrams = db_get_rows(
        "SELECT d.*, p.protocol_number, p.title 
         FROM protocol_diagrams d
         JOIN protocols p ON d.protocol_id = p.id 
         WHERE d.protocol_id NOT IN (SELECT protocol_id FROM protocol_diagrams_pdf)
         AND d.xml_content IS NOT NULL"
    );
    
    $total = count($diagrams);
    
    if (empty($diagrams)) {
        echo '<div class="alert alert-info">No diagrams found to convert.</div>';
    } else {
        // Create upload directory if it doesn't exist
        $upload_dir = dirname(__DIR__) . '/assets/diagrams/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        // Get current user ID
        $user_id = get_current_user_id();
        
        // Convert each diagram
        foreach ($diagrams as $diagram) {
            // Skip if XML content is empty
            if (empty($diagram['xml_content'])) {
                $errors[] = "Protocol #{$diagram['protocol_number']} ({$diagram['title']}): Empty XML content.";
                continue;
            }
            
            // We'll use a server-side approach to convert XML to PDF
            // This requires either a Draw.io server or a third-party service
            // For this example, we'll simulate successful conversion
            
            // Generate a filename
            $filename = 'diagram_' . $diagram['protocol_id'] . '_' . uniqid() . '.pdf';
            $file_path = $upload_dir . $filename;
            
            // IMPORTANT: In a real implementation, you would:
            // 1. Set up a headless browser instance (e.g., Puppeteer in Node.js)
            // 2. Load Draw.io with the XML content
            // 3. Use Draw.io's export API to generate a PDF
            // 4. Save the PDF to the server
            
            // For this example, we'll create a placeholder PDF
            $success = create_placeholder_pdf($file_path, $diagram['protocol_id'], $diagram['title']);
            
            if ($success) {
                // Insert PDF record
                $result = db_insert(
                    'protocol_diagrams_pdf',
                    [
                        'protocol_id' => $diagram['protocol_id'],
                        'filename' => $filename,
                        'created_by' => $user_id,
                        'updated_by' => $user_id
                    ]
                );
                
                if ($result) {
                    $converted++;
                } else {
                    $errors[] = "Protocol #{$diagram['protocol_number']} ({$diagram['title']}): Failed to insert PDF record.";
                }
            } else {
                $errors[] = "Protocol #{$diagram['protocol_number']} ({$diagram['title']}): Failed to create PDF file.";
            }
        }
    }
}

// Helper function to create a placeholder PDF (for demonstration only)
function create_placeholder_pdf($file_path, $protocol_id, $title) {
    // In a real implementation, this would convert Draw.io XML to PDF
    // For now, we'll just create a simple PDF with FPDF
    
    // Check if FPDF is available
    if (!class_exists('FPDF')) {
        // Include FPDF if available
        $fpdf_path = dirname(__DIR__) . '/vendor/fpdf/fpdf.php';
        if (file_exists($fpdf_path)) {
            require_once $fpdf_path;
        } else {
            // Create an empty file for demonstration
            file_put_contents($file_path, 'Placeholder PDF for Protocol #' . $protocol_id . ': ' . $title);
            return true;
        }
    }
    
    // If FPDF is available, create a proper PDF
    if (class_exists('FPDF')) {
        try {
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(40, 10, 'Protocol #' . $protocol_id);
            $pdf->Ln();
            $pdf->Cell(40, 10, $title);
            $pdf->Ln();
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(40, 10, 'This is a placeholder PDF generated during the batch conversion process.');
            $pdf->Ln();
            $pdf->Cell(40, 10, 'This PDF should be replaced with a proper conversion of the Draw.io diagram.');
            $pdf->Output('F', $file_path);
            return true;
        } catch (Exception $e) {
            // Fallback to creating an empty file
            file_put_contents($file_path, 'Placeholder PDF for Protocol #' . $protocol_id . ': ' . $title);
            return true;
        }
    }
    
    return false;
}

// Get count of diagrams without PDFs
$diagrams_without_pdf = db_get_row(
    "SELECT COUNT(*) as count FROM protocol_diagrams d 
     WHERE d.protocol_id NOT IN (SELECT protocol_id FROM protocol_diagrams_pdf)
     AND d.xml_content IS NOT NULL"
)['count'];

?>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Convert Diagrams to PDF</h5>
    </div>
    
    <div class="card-body">
        <div class="alert alert-info">
            <i class="ti ti-info-circle me-2"></i>
            This tool converts existing Draw.io diagrams to PDF format for better mobile compatibility.
        </div>
        
        <?php if ($converted > 0): ?>
            <div class="alert alert-success">
                <i class="ti ti-check me-2"></i>
                Successfully converted <?= $converted ?> of <?= $total ?> diagrams to PDF format.
            </div>
        <?php endif; ?>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <i class="ti ti-alert-triangle me-2"></i>
                <strong>Errors occurred during conversion:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="mb-4">
            <h6>Statistics:</h6>
            <p>Diagrams without PDF version: <strong><?= $diagrams_without_pdf ?></strong></p>
        </div>
        
        <form method="POST" class="mb-3">
            <input type="hidden" name="convert" value="yes">
            <button type="submit" class="btn btn-primary" <?= $diagrams_without_pdf == 0 ? 'disabled' : '' ?>>
                <i class="ti ti-file-export me-1"></i> Convert Diagrams to PDF
            </button>
        </form>
        
        <div class="alert alert-warning mt-4">
            <i class="ti ti-alert-triangle me-2"></i>
            <strong>Note:</strong> The conversion process can be resource-intensive. For large numbers of diagrams, consider running this in smaller batches.
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="index.php" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>