<?php
/**
 * Protocol Editor Page
 * 
 * Place this file in: /admin/protocol_edit.php
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

// Set page title
$page_title = 'Edit Protocol: ' . $protocol['protocol_number'] . '. ' . $protocol['title'];

// Get all categories for dropdown
$categories = get_all_categories();

// Get protocol sections
$sections = get_protocol_sections($protocol_id);

// Get component templates
$component_templates = get_component_templates();

// Process info form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_info') {
    $category_id = intval($_POST['category_id'] ?? 0);
    $protocol_number = $_POST['protocol_number'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    
    $errors = [];
    
    // Validate required fields
    if ($category_id <= 0) {
        $errors[] = 'Category is required.';
    }
    
    if (empty($protocol_number)) {
        $errors[] = 'Protocol number is required.';
    } elseif (!preg_match('/^\d{4}(\.\d+)?$/', $protocol_number)) {
        $errors[] = 'Protocol number must be in format: 2030 or 2030.1';
    }
    
    if (empty($title)) {
        $errors[] = 'Title is required.';
    }
    
    // Check if protocol number already exists (excluding this protocol)
    $existing = db_get_row(
        "SELECT * FROM protocols WHERE protocol_number = ? AND id != ?",
        [$protocol_number, $protocol_id]
    );
    
    if ($existing) {
        $errors[] = 'A protocol with this number already exists.';
    }
    
    // If no errors, update the protocol
    if (empty($errors)) {
        $user_id = get_current_user_id();
        
        $data = [
            'category_id' => $category_id,
            'protocol_number' => $protocol_number,
            'title' => $title,
            'description' => $description,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ];
        
        $result = db_update('protocols', $data, 'id = ?', [$protocol_id]);
        
        if ($result !== false) {
            set_flash_message('success', 'Protocol information updated successfully.');
            // Reload the page to reflect changes
            header('Location: protocol_edit.php?id=' . $protocol_id);
            exit;
        } else {
            $errors[] = 'Failed to update protocol.';
        }
    }
}

// Process AJAX section actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_action'])) {
    header('Content-Type: application/json');
    
    $response = ['success' => false, 'message' => ''];
    
    switch ($_POST['ajax_action']) {
        case 'add_section':
            $section_type = $_POST['section_type'] ?? '';
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $skill_levels = isset($_POST['skill_levels']) ? json_encode($_POST['skill_levels']) : null;
            $contact_base = isset($_POST['contact_base']) ? 1 : 0;
            $display_order = count($sections) + 1;
            
            // Log the section type being added
            error_log("Adding section with type: $section_type");
            
            $data = [
                'protocol_id' => $protocol_id,
                'section_type' => $section_type,
                'title' => $title,
                'content' => $content,
                'skill_levels' => $skill_levels,
                'contact_base' => $contact_base,
                'display_order' => $display_order
            ];
            
            $section_id = db_insert('protocol_sections', $data);
            
            if ($section_id) {
                $response['success'] = true;
                $response['message'] = 'Section added successfully.';
                $response['section_id'] = $section_id;
            } else {
                $response['message'] = 'Failed to add section.';
            }
            break;
            
        case 'update_section':
            $section_id = intval($_POST['section_id'] ?? 0);
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $skill_levels = isset($_POST['skill_levels']) ? json_encode($_POST['skill_levels']) : null;
            $contact_base = isset($_POST['contact_base']) ? 1 : 0;
            
            $data = [
                'title' => $title,
                'content' => $content,
                'skill_levels' => $skill_levels,
                'contact_base' => $contact_base
            ];
            
            $result = db_update('protocol_sections', $data, 'id = ?', [$section_id]);
            
            if ($result !== false) {
                $response['success'] = true;
                $response['message'] = 'Section updated successfully.';
            } else {
                $response['message'] = 'Failed to update section.';
            }
            break;
            
        case 'delete_section':
            $section_id = intval($_POST['section_id'] ?? 0);
            
            // Delete decision branches first (if any)
            db_delete('decision_branches', 'section_id = ?', [$section_id]);
            
            // Delete section
            $result = db_delete('protocol_sections', 'id = ?', [$section_id]);
            
            if ($result !== false) {
                $response['success'] = true;
                $response['message'] = 'Section deleted successfully.';
            } else {
                $response['message'] = 'Failed to delete section.';
            }
            break;
            
        case 'reorder_sections':
            $section_ids = $_POST['section_ids'] ?? [];
            
            if (!empty($section_ids)) {
                $success = true;
                
                foreach ($section_ids as $index => $id) {
                    $order = $index + 1;
                    $result = db_update(
                        'protocol_sections',
                        ['display_order' => $order],
                        'id = ?',
                        [intval($id)]
                    );
                    
                    if ($result === false) {
                        $success = false;
                        break;
                    }
                }
                
                if ($success) {
                    $response['success'] = true;
                    $response['message'] = 'Sections reordered successfully.';
                } else {
                    $response['message'] = 'Failed to reorder sections.';
                }
            } else {
                $response['message'] = 'No sections to reorder.';
            }
            break;
            
        case 'add_decision_branch':
            $section_id = intval($_POST['section_id'] ?? 0);
            $label = $_POST['label'] ?? '';
            $outcome = $_POST['outcome'] ?? '';
            $target_section_id = !empty($_POST['target_section_id']) ? intval($_POST['target_section_id']) : null;
            
            // Get current max display order
            $max_order = db_get_row(
                "SELECT MAX(display_order) as max_order FROM decision_branches WHERE section_id = ?",
                [$section_id]
            );
            
            $display_order = $max_order ? intval($max_order['max_order']) + 1 : 1;
            
            $data = [
                'section_id' => $section_id,
                'label' => $label,
                'outcome' => $outcome,
                'target_section_id' => $target_section_id,
                'display_order' => $display_order
            ];
            
            $branch_id = db_insert('decision_branches', $data);
            
            if ($branch_id) {
                $response['success'] = true;
                $response['message'] = 'Decision branch added successfully.';
                $response['branch_id'] = $branch_id;
            } else {
                $response['message'] = 'Failed to add decision branch.';
            }
            break;
            
        case 'update_decision_branch':
            $branch_id = intval($_POST['branch_id'] ?? 0);
            $label = $_POST['label'] ?? '';
            $outcome = $_POST['outcome'] ?? '';
            $target_section_id = !empty($_POST['target_section_id']) ? intval($_POST['target_section_id']) : null;
            
            $data = [
                'label' => $label,
                'outcome' => $outcome,
                'target_section_id' => $target_section_id
            ];
            
            $result = db_update('decision_branches', $data, 'id = ?', [$branch_id]);
            
            if ($result !== false) {
                $response['success'] = true;
                $response['message'] = 'Decision branch updated successfully.';
            } else {
                $response['message'] = 'Failed to update decision branch.';
            }
            break;
            
        case 'delete_decision_branch':
            $branch_id = intval($_POST['branch_id'] ?? 0);
            
            $result = db_delete('decision_branches', 'id = ?', [$branch_id]);
            
            if ($result !== false) {
                $response['success'] = true;
                $response['message'] = 'Decision branch deleted successfully.';
            } else {
                $response['message'] = 'Failed to delete decision branch.';
            }
            break;
            
        default:
            $response['message'] = 'Invalid action.';
            break;
    }
    
    echo json_encode($response);
    exit;
}

// Include header
include 'includes/header.php';
?>

<style>
    /* Custom button styling for Quill */
    .ql-snow .ql-picker.ql-size .ql-picker-label::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item::before {
        content: 'Normal';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="small"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="small"]::before {
        content: 'Small';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="large"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="large"]::before {
        content: 'Large';
    }
    .ql-snow .ql-picker.ql-size .ql-picker-label[data-value="huge"]::before,
    .ql-snow .ql-picker.ql-size .ql-picker-item[data-value="huge"]::before {
        content: 'Huge';
    }

    .ql-snow .ql-tooltip {
        left: 0 !important;
    }

    /* Red Down Arrow */
    .red-arrow {
        display: flex;
        justify-content: center;
        padding: 15px 0;
        width: 100%;
    }
    .red-arrow svg {
        width: 60px;
        height: 60px;
        color: #dc3545;
    }
    
    /* Clinical Components */
    .section-header-indications {
        background-color: #e8eaf6 !important;
        border-left: 4px solid #3f51b5 !important;
    }
    
    .section-header-contraindications {
        background-color: #ffebee !important;
        border-left: 4px solid #f44336 !important;
    }
    
    .section-header-side_effects {
        background-color: #fff8e1 !important;
        border-left: 4px solid #ffc107 !important;
    }
    
    .section-header-precautions {
        background-color: #e0f2f1 !important;
        border-left: 4px solid #009688 !important;
    }
    
    .section-header-technique {
        background-color: #f3e5f5 !important;
        border-left: 4px solid #9c27b0 !important;
    }

    /* Custom Quill styles */
    .custom-quill-container {
        height: 400px;
    }
    .custom-quill-editor {
        height: 350px;
        overflow-y: auto;
    }
</style>

<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Protocol Information</h5>
        <div>
            <a href="../protocol.php?id=<?= $protocol_id ?>" target="_blank" class="btn btn-outline-secondary me-2">
                <i class="ti ti-eye"></i> View Protocol
            </a>
            <a href="protocols.php" class="btn btn-secondary">
                <i class="ti ti-arrow-left"></i> Back to Protocols
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="action" value="update_info">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="category_id" class="form-label">Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $protocol['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                <?= $category['category_number'] ?>. <?= $category['title'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="col-md-6">
                    <label for="protocol_number" class="form-label">Protocol Number</label>
                    <input type="text" class="form-control" id="protocol_number" name="protocol_number" 
                           value="<?= $protocol['protocol_number'] ?>" 
                           placeholder="e.g., 2030" required pattern="\d{4}(\.\d+)?">
                    <div class="form-text">Format: 2030 or 2030.1</div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?= $protocol['title'] ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="description" class="form-label">Description (optional)</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= $protocol['description'] ?></textarea>
            </div>
            
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" <?= $protocol['is_active'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Active (visible to users)</label>
            </div>
            
            <div class="d-flex justify-content-between">
                <small class="text-muted">
                    Last updated: <?= format_datetime($protocol['updated_at']) ?>
                </small>
                <button type="submit" class="btn btn-primary">Update Protocol Info</button>
            </div>
        </form>
    </div>
</div>

<!-- Protocol Editor -->
<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="protocol-editor-tabs">
            <li class="nav-item">
                <a class="nav-link active" id="editor-tab" data-bs-toggle="tab" href="#editor-pane">Protocol Editor</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="preview-tab" data-bs-toggle="tab" href="#preview-pane">Preview</a>
            </li>
        </ul>
    </div>
    
    <div class="card-body">
        <div class="tab-content" id="protocol-editor-content">
            <!-- Editor Tab -->
            <div class="tab-pane fade show active" id="editor-pane">
                <div class="row">
                    <!-- Component Library -->
                    <div class="col-md-3">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Component Library</h5>
                            </div>
                            <div class="card-body p-2">
                                <div class="mb-3">
                                    <select class="form-select form-select-sm" id="component-type-filter">
                                        <option value="">All Component Types</option>
                                        <option value="entry_point">Entry Point</option>
                                        <option value="treatment">Treatment</option>
                                        <option value="decision">Decision</option>
                                        <option value="note">Note</option>
                                        <option value="reference">Reference</option>
                                        <option value="red_arrow">Red Arrow</option>
                                        <option value="indications">Indications</option>
                                        <option value="contraindications">Contraindications</option>
                                        <option value="side_effects">Side Effects</option>
                                        <option value="precautions">Precautions</option>
                                        <option value="technique">Technique</option>
                                    </select>
                                </div>
                                
                                <div id="component-library" class="component-list">
                                    <!-- Entry Point Component -->
                                    <div class="component-item" data-type="entry_point">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-arrow-bar-to-down me-2"></i>
                                            <div>
                                                <strong>Entry Point</strong>
                                                <div class="small text-muted">Protocol introduction</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Treatment Component -->
                                    <div class="component-item" data-type="treatment">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-first-aid-kit me-2"></i>
                                            <div>
                                                <strong>Treatment Step</strong>
                                                <div class="small text-muted">Interventions & monitoring</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Decision Component -->
                                    <div class="component-item" data-type="decision">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-git-branch me-2"></i>
                                            <div>
                                                <strong>Decision Point</strong>
                                                <div class="small text-muted">Yes/No branching</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Note Component -->
                                    <div class="component-item" data-type="note">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-note me-2"></i>
                                            <div>
                                                <strong>Note</strong>
                                                <div class="small text-muted">Additional information</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Reference Component -->
                                    <div class="component-item" data-type="reference">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-book me-2"></i>
                                            <div>
                                                <strong>Reference</strong>
                                                <div class="small text-muted">External references</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Red Down Arrow Component -->
                                    <div class="component-item" data-type="red_arrow">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-arrow-down-circle me-2" style="color: #dc3545;"></i>
                                            <div>
                                                <strong>Red Down Arrow</strong>
                                                <div class="small text-muted">Directional indicator</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Indications Component -->
                                    <div class="component-item" data-type="indications">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-checkbox me-2" style="color: #3f51b5;"></i>
                                            <div>
                                                <strong>Indications</strong>
                                                <div class="small text-muted">When to use</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Contraindications Component -->
                                    <div class="component-item" data-type="contraindications">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-ban me-2" style="color: #f44336;"></i>
                                            <div>
                                                <strong>Contraindications</strong>
                                                <div class="small text-muted">When not to use</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Side Effects Component -->
                                    <div class="component-item" data-type="side_effects">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-alert-triangle me-2" style="color: #ffc107;"></i>
                                            <div>
                                                <strong>Side Effects</strong>
                                                <div class="small text-muted">Possible adverse effects</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Precautions Component -->
                                    <div class="component-item" data-type="precautions">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-shield-check me-2" style="color: #009688;"></i>
                                            <div>
                                                <strong>Precautions</strong>
                                                <div class="small text-muted">Special considerations</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Technique Component -->
                                    <div class="component-item" data-type="technique">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-tools me-2" style="color: #9c27b0;"></i>
                                            <div>
                                                <strong>Technique</strong>
                                                <div class="small text-muted">How to perform</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Custom Component Templates -->
                                    <?php foreach ($component_templates as $template): ?>
                                    <div class="component-item" data-type="<?= $template['section_type'] ?>" data-template-id="<?= $template['id'] ?>">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-template me-2"></i>
                                            <div>
                                                <strong><?= $template['title'] ?></strong>
                                                <div class="small text-muted"><?= substr($template['description'], 0, 30) ?><?= strlen($template['description']) > 30 ? '...' : '' ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Protocol Editor Area -->
                    <div class="col-md-9">
                        <div id="protocol-editor-area" class="protocol-editor-area">
                            <?php if (empty($sections)): ?>
                                <div class="text-center p-5 text-muted">
                                    <i class="ti ti-arrow-left-circle" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3">Drag components here to build your protocol</h5>
                                    <p>Start by adding an Entry Point component</p>
                                </div>
                            <?php else: ?>
                                <?php foreach ($sections as $section): ?>
                                    <?php 
                                    // Get decision branches if section is a decision
                                    $branches = [];
                                    if ($section['section_type'] === 'decision') {
                                        $branches = get_decision_branches($section['id']);
                                    }
                                    
                                    // Parse skill levels
                                    $skill_levels = !empty($section['skill_levels']) ? json_decode($section['skill_levels'], true) : [];
                                    ?>
                                    <div class="protocol-section" data-section-id="<?= $section['id'] ?>" data-section-type="<?= $section['section_type'] ?>">
                                        <?php if ($section['section_type'] === 'red_arrow'): ?>
                                            <!-- Red arrow doesn't need a header, just the content -->
                                            <div class="red-arrow">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-circle-fill" viewBox="0 0 16 16">
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
                                                </svg>
                                                <div class="d-flex ms-3">
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-section-btn">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="protocol-section-header">
                                                <div class="d-flex align-items-center">
                                                    <div class="handle me-2">
                                                        <i class="ti ti-grip-vertical"></i>
                                                    </div>
                                                    
                                                    <?php if ($section['section_type'] === 'entry_point'): ?>
                                                        <i class="ti ti-arrow-bar-to-down me-2"></i>
                                                        <span>Entry Point: <?= $section['title'] ?></span>
                                                    <?php elseif ($section['section_type'] === 'treatment'): ?>
                                                        <i class="ti ti-first-aid-kit me-2"></i>
                                                        <span>Treatment: <?= $section['title'] ?></span>
                                                    <?php elseif ($section['section_type'] === 'decision'): ?>
                                                        <i class="ti ti-git-branch me-2"></i>
                                                        <span>Decision: <?= $section['title'] ?></span>
                                                    <?php elseif ($section['section_type'] === 'note'): ?>
                                                        <i class="ti ti-note me-2"></i>
                                                        <span>Note: <?= $section['title'] ?></span>
                                                    <?php elseif ($section['section_type'] === 'reference'): ?>
                                                        <i class="ti ti-book me-2"></i>
                                                        <span>Reference: <?= $section['title'] ?></span>
                                                    <?php elseif ($section['section_type'] === 'indications'): ?>
                                                        <i class="ti ti-checkbox me-2"></i>
                                                        <span>Indications: <?= $section['title'] ?></span>
                                                    <?php elseif ($section['section_type'] === 'contraindications'): ?>
                                                        <i class="ti ti-ban me-2"></i>
                                                        <span>Contraindications: <?= $section['title'] ?></span>
                                                    <?php elseif ($section['section_type'] === 'side_effects'): ?>
                                                        <i class="ti ti-alert-triangle me-2"></i>
                                                        <span>Side Effects: <?= $section['title'] ?></span>
                                                    <?php elseif ($section['section_type'] === 'precautions'): ?>
                                                        <i class="ti ti-shield-check me-2"></i>
                                                        <span>Precautions: <?= $section['title'] ?></span>
                                                    <?php elseif ($section['section_type'] === 'technique'): ?>
                                                        <i class="ti ti-tools me-2"></i>
                                                        <span>Technique: <?= $section['title'] ?></span>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($skill_levels)): ?>
                                                        <div class="ms-2">
                                                            <?php foreach ($skill_levels as $level): ?>
                                                                <span class="badge bg-secondary me-1"><?= $level ?></span>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="section-actions">
                                                    <button type="button" class="btn btn-sm btn-outline-primary edit-section-btn">
                                                        <i class="ti ti-edit"></i> Edit
                                                    </button>
                                                    
                                                    <?php if ($section['section_type'] === 'decision'): ?>
                                                        <button type="button" class="btn btn-sm btn-outline-success manage-branches-btn">
                                                            <i class="ti ti-git-branch"></i> Branches
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-section-btn">
                                                        <i class="ti ti-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="protocol-section-body">
                                                <?= $section['content'] ?>
                                                
                                                <?php if ($section['section_type'] === 'decision' && !empty($branches)): ?>
                                                    <div class="mt-3">
                                                        <div class="row">
                                                            <?php foreach ($branches as $branch): ?>
                                                                <div class="col-md-6 mb-2">
                                                                    <div class="card <?= $branch['label'] === 'YES' ? 'border-success' : 'border-danger' ?>">
                                                                        <div class="card-header <?= $branch['label'] === 'YES' ? 'bg-success' : 'bg-danger' ?> text-white py-1 px-2">
                                                                            <strong><?= $branch['label'] ?></strong>
                                                                        </div>
                                                                        <div class="card-body py-2 px-3">
                                                                            <?= $branch['outcome'] ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Preview Tab -->
            <div class="tab-pane fade" id="preview-pane">
                <div class="d-flex justify-content-between mb-3">
                    <h4><?= $protocol['protocol_number'] ?>. <?= $protocol['title'] ?></h4>
                    <button type="button" class="btn btn-sm btn-primary" id="refresh-preview-btn">
                        <i class="ti ti-refresh"></i> Refresh Preview
                    </button>
                </div>
                
                <div id="protocol-preview-content">
                    <!-- Preview content will be loaded here -->
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading preview...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Section Modal -->
<div class="modal fade" id="section-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="section-modal-title">Add Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="section-form">
                    <input type="hidden" id="section-id" value="0">
                    <input type="hidden" id="section-type" value="">
                    
                    <div class="mb-3">
                        <label for="section-title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="section-title" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="contact-base" name="contact_base">
                            <label class="form-check-label" for="contact-base">
                                <i class="ti ti-phone-call text-danger me-1"></i> Requires Base Contact
                            </label>
                            <div class="form-text">Check this if this section requires contacting medical control</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Skill Levels (optional)</label>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="form-check">
                                <input class="form-check-input skill-level-checkbox" type="checkbox" value="EMR" id="skill-emr">
                                <label class="form-check-label" for="skill-emr">EMR</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input skill-level-checkbox" type="checkbox" value="EMT" id="skill-emt">
                                <label class="form-check-label" for="skill-emt">EMT</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input skill-level-checkbox" type="checkbox" value="EMT-A" id="skill-emt-a">
                                <label class="form-check-label" for="skill-emt-a">EMT-A</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input skill-level-checkbox" type="checkbox" value="EMT-I" id="skill-emt-i">
                                <label class="form-check-label" for="skill-emt-i">EMT-I</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input skill-level-checkbox" type="checkbox" value="EMT-P" id="skill-emt-p">
                                <label class="form-check-label" for="skill-emt-p">EMT-P</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input skill-level-checkbox" type="checkbox" value="EMT-CC" id="skill-emt-cc">
                                <label class="form-check-label" for="skill-emt-cc">EMT-CC</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input skill-level-checkbox" type="checkbox" value="RN" id="skill-rn">
                                <label class="form-check-label" for="skill-rn">RN</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="section-content" class="form-label">Content</label>
                        <div id="section-content-container" class="custom-quill-container">
                            <div id="section-content-editor" class="custom-quill-editor"></div>
                        </div>
                        <div id="section-content-hidden"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-section-btn">Save Section</button>
            </div>
        </div>
    </div>
</div>

<!-- Manage Decision Branches Modal -->
<div class="modal fade" id="branches-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Decision Branches</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="decision-section-id" value="0">
                
                <div class="mb-3">
                    <button type="button" class="btn btn-sm btn-success" id="add-branch-btn">
                        <i class="ti ti-plus"></i> Add Branch
                    </button>
                </div>
                
                <div id="branches-container">
                    <!-- Branches will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Branch Modal -->
<div class="modal fade" id="branch-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="branch-modal-title">Add Branch</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="branch-form">
                    <input type="hidden" id="branch-id" value="0">
                    <input type="hidden" id="branch-section-id" value="0">
                    
                    <div class="mb-3">
                        <label for="branch-label" class="form-label">Branch Label</label>
                        <select class="form-select" id="branch-label" required>
                            <option value="YES">YES</option>
                            <option value="NO">NO</option>
                            <option value="OTHER">OTHER</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="branch-outcome" class="form-label">Outcome Text</label>
                        <textarea class="form-control" id="branch-outcome" rows="3" required></textarea>
                        <div class="form-text">Example: "Proceed to Step 3", "Continue monitoring"</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="branch-target" class="form-label">Target Section (optional)</label>
                        <select class="form-select" id="branch-target">
                            <option value="">No target (text only)</option>
                            <?php foreach ($sections as $section): ?>
                                <option value="<?= $section['id'] ?>"><?= $section['title'] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Select a section to jump to when this branch is followed</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="save-branch-btn">Save Branch</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal for Info Button -->
<div class="modal fade" id="info-modal-template" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Insert Info Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="info-modal-form">
                    <div class="mb-3">
                        <label for="info-button-text" class="form-label">Button Text</label>
                        <input type="text" class="form-control" id="info-button-text" value="More Info">
                    </div>
                    
                    <div class="mb-3">
                        <label for="info-modal-title-input" class="form-label">Modal Title</label>
                        <input type="text" class="form-control" id="info-modal-title-input" value="Additional Information">
                    </div>
                    
                    <div class="mb-3">
                        <label for="info-modal-content" class="form-label">Modal Content</label>
                        <textarea class="form-control" id="info-modal-content" rows="5"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="insert-info-modal-btn">Insert</button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Protocol Editor -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variable to store the current Quill editor instance
    let sectionQuill = null;
    
    // Make protocol sections sortable
    $('#protocol-editor-area').sortable({
        handle: '.handle',
        placeholder: 'protocol-section-placeholder',
        update: function(event, ui) {
            const sectionIds = $(this).find('.protocol-section').map(function() {
                return $(this).data('section-id');
            }).get();
            
            // Save new order via AJAX
            $.post('protocol_edit.php?id=<?= $protocol_id ?>', {
                ajax_action: 'reorder_sections',
                section_ids: sectionIds
            }, function(response) {
                if (response.success) {
                    console.log('Sections reordered successfully');
                } else {
                    console.error('Failed to reorder sections:', response.message);
                }
            }, 'json');
        }
    });
    
    // Make component items draggable
    $('.component-item').draggable({
        connectToSortable: false,
        helper: 'clone',
        revert: 'invalid',
        start: function(event, ui) {
            // Add a class to highlight the editor area
            $('#protocol-editor-area').addClass('highlight');
        },
        stop: function(event, ui) {
            // Remove the highlight class
            $('#protocol-editor-area').removeClass('highlight');
        }
    });
    
    // Make protocol editor area accept draggable components
    $('#protocol-editor-area').droppable({
        accept: '.component-item',
        drop: function(event, ui) {
            // Get component data
            const $component = ui.draggable;
            const sectionType = $component.data('type');
            const templateId = $component.data('template-id') || 0;
            
            console.log("Dropped component with type:", sectionType);
            
            // Check if we're trying to add a singleton component that already exists
            const singletonComponents = ['indications', 'contraindications', 'side_effects', 'precautions', 'technique'];
            
            if (singletonComponents.includes(sectionType)) {
                let exists = false;
                $('.protocol-section').each(function() {
                    if ($(this).data('section-type') === sectionType) {
                        exists = true;
                        return false; // Break the loop
                    }
                });
                
                if (exists) {
                    alert(`A ${getSectionTypeName(sectionType)} section already exists in this protocol.`);
                    return;
                }
            }
            
            // Open the section modal to configure the new section
            openSectionModal(0, sectionType, templateId);
        }
    });
    
    // Filter component library items
    $('#component-type-filter').change(function() {
        const filterValue = $(this).val();
        
        if (filterValue === '') {
            // Show all items
            $('.component-item').show();
        } else {
            // Show only items matching the filter
            $('.component-item').hide();
            $(`.component-item[data-type="${filterValue}"]`).show();
        }
    });
    
    // Edit Section Button Click
    $(document).on('click', '.edit-section-btn', function() {
        const $section = $(this).closest('.protocol-section');
        const sectionId = $section.data('section-id');
        const sectionType = $section.data('section-type');
        
        openSectionModal(sectionId, sectionType);
    });
    
    // Delete Section Button Click
    $(document).on('click', '.delete-section-btn', function() {
        if (confirm('Are you sure you want to delete this section?')) {
            const $section = $(this).closest('.protocol-section');
            const sectionId = $section.data('section-id');
            
            // Delete section via AJAX
            $.post('protocol_edit.php?id=<?= $protocol_id ?>', {
                ajax_action: 'delete_section',
                section_id: sectionId
            }, function(response) {
                if (response.success) {
                    // Remove the section from the UI
                    $section.fadeOut(300, function() {
                        $(this).remove();
                    });
                } else {
                    alert('Failed to delete section: ' + response.message);
                }
            }, 'json');
        }
    });
    
    // Manage Branches Button Click
    $(document).on('click', '.manage-branches-btn', function() {
        const $section = $(this).closest('.protocol-section');
        const sectionId = $section.data('section-id');
        
        openBranchesModal(sectionId);
    });
    
    // Save Section Button Click
    $('#save-section-btn').click(function() {
        const sectionId = $('#section-id').val();
        const sectionType = $('#section-type').val();
        const title = $('#section-title').val();
        const contactBase = $('#contact-base').is(':checked') ? 1 : 0;
        
        // Get Quill editor content
        const content = sectionQuill ? sectionQuill.root.innerHTML : '';
        
        // Get selected skill levels
        const skillLevels = $('.skill-level-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
        
        // Validate form
        if (!title) {
            alert('Please enter a title for the section.');
            return;
        }
        
        // Prepare data
        const data = {
            section_type: sectionType,
            title: title,
            content: content,
            skill_levels: skillLevels,
            contact_base: contactBase
        };
        
        if (sectionId === '0') {
            // Add new section
            $.post('protocol_edit.php?id=<?= $protocol_id ?>', {
                ajax_action: 'add_section',
                ...data
            }, function(response) {
                if (response.success) {
                    // Close the modal
                    $('#section-modal').modal('hide');
                    
                    // Reload the page to show the new section
                    window.location.reload();
                } else {
                    alert('Failed to add section: ' + response.message);
                }
            }, 'json');
        } else {
            // Update existing section
            $.post('protocol_edit.php?id=<?= $protocol_id ?>', {
                ajax_action: 'update_section',
                section_id: sectionId,
                ...data
            }, function(response) {
                if (response.success) {
                    // Close the modal
                    $('#section-modal').modal('hide');
                    
                    // Reload the page to show the updated section
                    window.location.reload();
                } else {
                    alert('Failed to update section: ' + response.message);
                }
            }, 'json');
        }
    });
    
    // Add Branch Button Click
    $('#add-branch-btn').click(function() {
        const sectionId = $('#decision-section-id').val();
        openBranchModal(0, sectionId);
    });
    
    // Save Branch Button Click
    $('#save-branch-btn').click(function() {
        const branchId = $('#branch-id').val();
        const sectionId = $('#branch-section-id').val();
        const label = $('#branch-label').val();
        const outcome = $('#branch-outcome').val();
        const targetSectionId = $('#branch-target').val();
        
        // Validate form
        if (!label || !outcome) {
            alert('Please enter all required fields.');
            return;
        }
        
        // Prepare data
        const data = {
            label: label,
            outcome: outcome,
            target_section_id: targetSectionId
        };
        
        if (branchId === '0') {
            // Add new branch
            $.post('protocol_edit.php?id=<?= $protocol_id ?>', {
                ajax_action: 'add_decision_branch',
                section_id: sectionId,
                ...data
            }, function(response) {
                if (response.success) {
                    // Close the modal
                    $('#branch-modal').modal('hide');
                    
                    // Refresh the branches modal
                    openBranchesModal(sectionId);
                } else {
                    alert('Failed to add branch: ' + response.message);
                }
            }, 'json');
        } else {
            // Update existing branch
            $.post('protocol_edit.php?id=<?= $protocol_id ?>', {
                ajax_action: 'update_decision_branch',
                branch_id: branchId,
                ...data
            }, function(response) {
                if (response.success) {
                    // Close the modal
                    $('#branch-modal').modal('hide');
                    
                    // Refresh the branches modal
                    openBranchesModal(sectionId);
                } else {
                    alert('Failed to update branch: ' + response.message);
                }
            }, 'json');
        }
    });
    
    // Delete Branch Button Click
    $(document).on('click', '.delete-branch-btn', function() {
        if (confirm('Are you sure you want to delete this branch?')) {
            const branchId = $(this).data('branch-id');
            const sectionId = $('#decision-section-id').val();
            
            // Delete branch via AJAX
            $.post('protocol_edit.php?id=<?= $protocol_id ?>', {
                ajax_action: 'delete_decision_branch',
                branch_id: branchId
            }, function(response) {
                if (response.success) {
                    // Refresh the branches modal
                    openBranchesModal(sectionId);
                } else {
                    alert('Failed to delete branch: ' + response.message);
                }
            }, 'json');
        }
    });
    
    // Edit Branch Button Click
    $(document).on('click', '.edit-branch-btn', function() {
        const branchId = $(this).data('branch-id');
        const sectionId = $('#decision-section-id').val();
        
        openBranchModal(branchId, sectionId);
    });
    
    // Preview Tab Click
    $('#preview-tab').on('shown.bs.tab', function() {
        loadProtocolPreview();
    });
    
    // Refresh Preview Button Click
    $('#refresh-preview-btn').click(function() {
        loadProtocolPreview();
    });
    
    // Function to initialize Quill editor with HTML Blot support
    function initQuill(element, placeholder = 'Enter content here...', content = '') {
        // Register custom blot for rendering HTML content like buttons and modals
        const Inline = Quill.import('blots/inline');
        const Block = Quill.import('blots/block');
        
        // Create a custom HTML blot that can render HTML with full fidelity
        class HTMLBlot extends Block {
            static create(value) {
                let node = super.create();
                node.innerHTML = value;
                return node;
            }
            
            static value(node) {
                return node.innerHTML;
            }
        }
        
        HTMLBlot.tagName = 'div';
        HTMLBlot.className = 'html-embed';
        HTMLBlot.blotName = 'html';
        Quill.register(HTMLBlot);
        
        // Configure Quill
        const quill = new Quill(element, {
            modules: {
                toolbar: {
                    container: [
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        ['blockquote', 'code-block'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        [{ 'script': 'sub' }, { 'script': 'super' }],
                        [{ 'indent': '-1' }, { 'indent': '+1' }],
                        ['link', 'image', 'video'],
                        ['clean'],
                        ['info-modal']
                    ],
                    handlers: {
                        'image': function() {
                            const input = document.createElement('input');
                            input.setAttribute('type', 'file');
                            input.setAttribute('accept', 'image/*');
                            input.click();
                            
                            input.onchange = () => {
                                const file = input.files[0];
                                if (file) {
                                    const formData = new FormData();
                                    formData.append('file', file);
                                    
                                    // Show loading indicator
                                    const range = this.quill.getSelection(true);
                                    this.quill.insertText(range.index, 'Uploading image...', { 'italic': true });
                                    
                                    // Upload image
                                    fetch('../api/upload_image.php', {
                                        method: 'POST',
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(result => {
                                        // Remove loading text
                                        this.quill.deleteText(range.index, 'Uploading image...'.length);
                                        
                                        // Insert the image
                                        if (result.location) {
                                            this.quill.insertEmbed(range.index, 'image', result.location);
                                        } else {
                                            alert('Failed to upload image: ' + (result.message || 'Unknown error'));
                                        }
                                    })
                                    .catch(error => {
                                        // Remove loading text
                                        this.quill.deleteText(range.index, 'Uploading image...'.length);
                                        alert('Failed to upload image: ' + error);
                                    });
                                }
                            };
                        },
                        'info-modal': function() {
                            // Show the info modal dialog
                            const infoModal = new bootstrap.Modal(document.getElementById('info-modal-template'));
                            infoModal.show();
                            
                            // Set up the Insert button handler
                            document.getElementById('insert-info-modal-btn').onclick = () => {
                                const buttonText = document.getElementById('info-button-text').value || 'More Info';
                                const modalTitle = document.getElementById('info-modal-title-input').value || 'Additional Information';
                                const modalContent = document.getElementById('info-modal-content').value || '';
                                
                                // Generate unique ID for the modal
                                const modalId = 'infoModal_' + Math.floor(Math.random() * 10000);
                                
                                // Create HTML for button and modal
                                const html = `
                                <div class="html-embed">
                                    <button type="button" class="btn btn-sm btn-info info-button" data-bs-toggle="modal" data-bs-target="#${modalId}">
                                        <i class="ti ti-info-circle me-1"></i>${buttonText}
                                    </button>
                                    <div class="modal fade info-modal" id="${modalId}" tabindex="-1" aria-labelledby="${modalId}Label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="${modalId}Label">${modalTitle}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    ${modalContent.replace(/\n/g, '<br>')}
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;
                                
                                // Get the current selection
                                const range = this.quill.getSelection(true);
                                
                                // Insert the custom HTML block
                                this.quill.insertEmbed(range.index, 'html', html);
                                
                                // Close the info modal
                                infoModal.hide();
                                
                                // Clear the form fields for next use
                                document.getElementById('info-button-text').value = 'More Info';
                                document.getElementById('info-modal-title-input').value = 'Additional Information';
                                document.getElementById('info-modal-content').value = '';
                            };
                        }
                    }
                }
            },
            placeholder: placeholder,
            theme: 'snow'
        });
        
        // Set content if provided
        if (content) {
            quill.root.innerHTML = content;
        }
        
        return quill;
    }
    
    // Function to open section modal for add/edit
    function openSectionModal(sectionId, sectionType, templateId = 0) {
        console.log("Opening section modal for:", sectionType);
        
        // For special components, create them directly without showing a modal
        if (sectionType === 'red_arrow') {
            // For red arrow, we don't need a modal, just create the arrow directly
            const data = {
                section_type: 'red_arrow',
                title: 'Direction Indicator',
                content: '<div class="red-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-circle-fill" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/></svg></div>',
                skill_levels: []
            };
            
            // Add the arrow via AJAX
            $.post('protocol_edit.php?id=<?= $protocol_id ?>', {
                ajax_action: 'add_section',
                ...data
            }, function(response) {
                if (response.success) {
                    // Reload the page to show the new arrow
                    window.location.reload();
                } else {
                    alert('Failed to add arrow: ' + response.message);
                }
            }, 'json');
            
            return; // Exit the function without showing the modal
        } 
        
        // Handle indications section
        else if (sectionType === 'indications') {
            // First check if this section type already exists
            let sectionExists = false;
            $('.protocol-section').each(function() {
                if ($(this).data('section-type') === 'indications') {
                    sectionExists = true;
                    return false; // break the loop
                }
            });
            
            if (sectionExists) {
                alert('An Indications section already exists in this protocol.');
                return;
            }
            
            // Add indications section
            const data = {
                section_type: 'indications',
                title: 'Indications',
                content: '<ul><li>Add indications here</li></ul>',
                skill_levels: []
            };
            
            $.post('protocol_edit.php?id=<?= $protocol_id ?>', {
                ajax_action: 'add_section',
                ...data
            }, function(response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    alert('Failed to add indications: ' + response.message);
                }
            }, 'json');
            
            return;
        }
        
        // Handle contraindications section
        else if (sectionType === 'contraindications') {
            // Debugging
            console.log("Creating contraindications section with type:", 'contraindications');
            
            let sectionExists = false;
            $('.protocol-section').each(function() {
                if ($(this).data('section-type') === 'contraindications') {
                    sectionExists = true;
                    return false;
                }
            });
            
            if (sectionExists) {
                alert('A Contraindications section already exists in this protocol.');
                return;
            }
            
            const data = {
                section_type: 'contraindications',
                title: 'Contraindications',
                content: '<ul><li>Add contraindications here</li></ul>',
                skill_levels: []
            };
            
            // Log what we're sending to the server
            console.log("Sending section data:", data);
            
            $.post('protocol_edit.php?id=<?= $protocol_id ?>', {
                ajax_action: 'add_section',
                ...data
            }, function(response) {
                console.log("Server response:", response);
                if (response.success) {
                    window.location.reload();
                } else {
                    alert('Failed to add contraindications: ' + response.message);
                }
            }, 'json').fail(function(xhr, status, error) {
                console.error("AJAX error:", error);
            });
            
            return;
        }
        
        // Handle side effects section
        else if (sectionType === 'side_effects') {
            let sectionExists = false;
            $('.protocol-section').each(function() {
                if ($(this).data('section-type') === 'side_effects') {
                    sectionExists = true;
                    return false;
                }
            });
            
            if (sectionExists) {
                alert('A Side Effects section already exists in this protocol.');
                return;
            }
            
            const data = {
                section_type: 'side_effects',
                title: 'Side Effects',
                content: '<ul><li>Add side effects here</li></ul>',
                skill_levels: []
            };
            
            $.post('protocol_edit.php?id=<?= $protocol_id ?>', {
                ajax_action: 'add_section',
                ...data
            }, function(response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    alert('Failed to add side effects: ' + response.message);
                }
            }, 'json');
            
            return;
        }
        
        // Handle precautions section
        else if (sectionType === 'precautions') {
            let sectionExists = false;
            $('.protocol-section').each(function() {
                if ($(this).data('section-type') === 'precautions') {
                    sectionExists = true;
                    return false;
                }
            });
            
            if (sectionExists) {
                alert('A Precautions section already exists in this protocol.');
                return;
            }
            
            const data = {
                section_type: 'precautions',
                title: 'Precautions',
                content: '<ul><li>Add precautions here</li></ul>',
                skill_levels: []
            };
            
            $.post('protocol_edit.php?id=<?= $protocol_id ?>', {
                ajax_action: 'add_section',
                ...data
            }, function(response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    alert('Failed to add precautions: ' + response.message);
                }
            }, 'json');
            
            return;
        }
        
        // Handle technique section
        else if (sectionType === 'technique') {
            let sectionExists = false;
            $('.protocol-section').each(function() {
                if ($(this).data('section-type') === 'technique') {
                    sectionExists = true;
                    return false;
                }
            });
            
            if (sectionExists) {
                alert('A Technique section already exists in this protocol.');
                return;
            }
            
            const data = {
                section_type: 'technique',
                title: 'Technique',
                content: '<ol><li>Step 1</li><li>Step 2</li></ol>',
                skill_levels: []
            };
            
            $.post('protocol_edit.php?id=<?= $protocol_id ?>', {
                ajax_action: 'add_section',
                ...data
            }, function(response) {
                if (response.success) {
                    window.location.reload();
                } else {
                    alert('Failed to add technique: ' + response.message);
                }
            }, 'json');
            
            return;
        }
        
        // For other component types, show the modal
        // Reset the form
        $('#section-form')[0].reset();
        $('#section-id').val(sectionId);
        $('#section-type').val(sectionType);
        
        // Uncheck contact base checkbox by default
        $('#contact-base').prop('checked', false);
        
        // Uncheck all skill level checkboxes
        $('.skill-level-checkbox').prop('checked', false);
        
        // Clean up any existing Quill editor
        if (sectionQuill) {
            try {
                // Clean up the old editor
                sectionQuill = null;
            } catch (e) {
                console.error("Error cleaning up Quill:", e);
            }
        }
        
        // Completely remove and recreate the editor element to ensure clean slate
        const editorContainer = document.getElementById('section-content-container');
        if (editorContainer) {
            editorContainer.innerHTML = '<div id="section-content-editor" class="custom-quill-editor"></div>';
        }
        
        // Set modal title based on action
        if (sectionId === 0) {
            $('#section-modal-title').text('Add ' + getSectionTypeName(sectionType));
        } else {
            $('#section-modal-title').text('Edit ' + getSectionTypeName(sectionType));
        }
        
        // Show the modal first
        const sectionModal = new bootstrap.Modal(document.getElementById('section-modal'));
        sectionModal.show();
        
        // Initialize Quill after the modal is shown
        setTimeout(() => {
            const editorElement = document.getElementById('section-content-editor');
            
            if (sectionId === 0) {
                // Initialize Quill with empty content for new section
                sectionQuill = initQuill(editorElement);
                
                // If using a template, load it
                if (templateId > 0) {
                    // TODO: Load template content
                }
            } else {
                // Load section data
                $.getJSON(`../api/get_section.php?id=${sectionId}`, function(data) {
                    if (data.success) {
                        const section = data.section;
                        
                        $('#section-title').val(section.title);
                        
                        // Set contact base checkbox
                        $('#contact-base').prop('checked', section.contact_base == 1);
                        
                        // Set skill levels
                        if (section.skill_levels) {
                            try {
                                const skillLevels = section.skill_levels ? JSON.parse(section.skill_levels) : [];
                                skillLevels.forEach(level => {
                                    $(`.skill-level-checkbox[value="${level}"]`).prop('checked', true);
                                });
                            } catch (e) {
                                console.error("Error parsing skill levels:", e);
                            }
                        }
                        
                        // Initialize Quill with existing content
                        console.log("Initializing Quill with existing content");
                        sectionQuill = initQuill(editorElement, 'Edit content...', section.content);
                    } else {
                        alert('Failed to load section data: ' + data.message);
                        
                        // Initialize empty Quill editor
                        sectionQuill = initQuill(editorElement);
                    }
                }).fail(function() {
                    alert('Failed to load section data. Please try again.');
                    
                    // Initialize empty Quill editor
                    sectionQuill = initQuill(editorElement);
                });
            }
        }, 50); // Short delay to ensure modal is fully rendered
    }
    
    // Function to open branches modal
    function openBranchesModal(sectionId) {
        $('#decision-section-id').val(sectionId);
        
        // Load branches data
        $.getJSON(`../api/get_branches.php?section_id=${sectionId}`, function(data) {
            if (data.success) {
                // Clear branches container
                $('#branches-container').empty();
                
                const branches = data.branches;
                
                if (branches.length === 0) {
                    $('#branches-container').html('<div class="alert alert-info">No branches have been added yet.</div>');
                } else {
                    // Create branches UI
                    branches.forEach(branch => {
                        const $branch = $(`
                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between align-items-center ${branch.label === 'YES' ? 'bg-success' : (branch.label === 'NO' ? 'bg-danger' : 'bg-warning')} text-white">
                                    <span>${branch.label}</span>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-light edit-branch-btn" data-branch-id="${branch.id}">
                                            <i class="ti ti-edit"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light delete-branch-btn" data-branch-id="${branch.id}">
                                            <i class="ti ti-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p>${branch.outcome}</p>
                                    ${branch.target_section_id ? `<p class="mb-0"><strong>Target:</strong> ${branch.target_section_title}</p>` : ''}
                                </div>
                            </div>
                        `);
                        
                        $('#branches-container').append($branch);
                    });
                }
            } else {
                alert('Failed to load branches: ' + data.message);
            }
        }).fail(function() {
            alert('Failed to load branches. Please try again.');
        });
        
        // Show the modal
        const branchesModal = new bootstrap.Modal(document.getElementById('branches-modal'));
        branchesModal.show();
    }
    
    // Function to open branch modal for add/edit
    function openBranchModal(branchId, sectionId) {
        // Reset the form
        $('#branch-form')[0].reset();
        $('#branch-id').val(branchId);
        $('#branch-section-id').val(sectionId);
        
        // Set modal title based on action
        if (branchId === 0) {
            $('#branch-modal-title').text('Add Decision Branch');
        } else {
            $('#branch-modal-title').text('Edit Decision Branch');
            
            // Load branch data
            $.getJSON(`../api/get_branch.php?id=${branchId}`, function(data) {
                if (data.success) {
                    const branch = data.branch;
                    
                    $('#branch-label').val(branch.label);
                    $('#branch-outcome').val(branch.outcome);
                    $('#branch-target').val(branch.target_section_id || '');
                } else {
                    alert('Failed to load branch data: ' + data.message);
                }
            }).fail(function() {
                alert('Failed to load branch data. Please try again.');
            });
        }
        
        // Hide branches modal (will be reopened when this one closes)
        $('#branches-modal').modal('hide');
        
        // Show the modal
        setTimeout(function() {
            const branchModal = new bootstrap.Modal(document.getElementById('branch-modal'));
            branchModal.show();
            
            // When branch modal hides, show branches modal again
            $('#branch-modal').on('hidden.bs.modal', function() {
                const branchesModal = new bootstrap.Modal(document.getElementById('branches-modal'));
                branchesModal.show();
                
                // Remove the event listener to prevent multiple bindings
                $('#branch-modal').off('hidden.bs.modal');
            });
        }, 500);
    }
    
    // Function to load protocol preview
    function loadProtocolPreview() {
        $('#protocol-preview-content').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading preview...</p>
            </div>
        `);
        
        // Load preview via AJAX
        $('#protocol-preview-content').load('../protocol.php?id=<?= $protocol_id ?>&preview=1');
    }
    
    // Helper function to get section type name
    function getSectionTypeName(type) {
        switch (type) {
            case 'entry_point':
                return 'Entry Point';
            case 'treatment':
                return 'Treatment Step';
            case 'decision':
                return 'Decision Point';
            case 'note':
                return 'Note';
            case 'reference':
                return 'Reference';
            case 'red_arrow':
                return 'Red Arrow';
            case 'indications':
                return 'Indications';
            case 'contraindications':
                return 'Contraindications';
            case 'side_effects':
                return 'Side Effects';
            case 'precautions':
                return 'Precautions';
            case 'technique':
                return 'Technique';
            default:
                return 'Section';
        }
    }
    
    // Helper function to check if a value is empty
    function empty(value) {
        return value === undefined || value === null || value === '';
    }
    
    // Add cleanup when modal is hidden
    document.getElementById('section-modal').addEventListener('hidden.bs.modal', function () {
        if (sectionQuill) {
            console.log("Cleaning up Quill on modal close");
            // We don't need to do anything specific here
            // The next time the modal opens, it will recreate the editor
        }
    });
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?>
