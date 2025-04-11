<?php
/**
 * Glasgow Coma Scale (GCS) Calculator Tool
 * 
 * Place this file in: /tool_gcs.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'GCS Calculator';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Glasgow Coma Scale (GCS) Calculator</h1>
                </div>
                <div class="card-body">
                    <p class="lead mb-0">Select the appropriate response for each category to calculate the total GCS score. Click the <i class="ti ti-info-circle"></i> icons for guidance on assessment techniques.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <!-- Eye Opening Response Section -->
        <div class="col-md-10 mb-4">
            <div class="card gcs-card" id="eye-card">
                <div class="card-header section-header-treatment d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 mb-0"><i class="ti ti-eye me-2"></i>Eye Opening Response</h2>
                    </div>
                    <button type="button" class="section-info-btn" data-section="eye">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row gcs-options">
                        <div class="col-6 col-md-3 mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="eye" data-value="4">
                                <div class="fw-bold">4</div>
                                <div>Spontaneous</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="eye" data-value="3">
                                <div class="fw-bold">3</div>
                                <div>To Sound</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="eye" data-value="2">
                                <div class="fw-bold">2</div>
                                <div>To Pressure</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-3 mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="eye" data-value="1">
                                <div class="fw-bold">1</div>
                                <div>None</div>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Selected: <span class="selected-option" id="eye-selected">None</span></div>
                        <div>Score: <span class="selected-score" id="eye-score">0</span>/4</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Verbal Response Section -->
        <div class="col-md-10 mb-4">
            <div class="card gcs-card" id="verbal-card">
                <div class="card-header section-header-treatment d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 mb-0"><i class="ti ti-message-circle me-2"></i>Verbal Response</h2>
                    </div>
                    <button type="button" class="section-info-btn" data-section="verbal">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row gcs-options">
                        <div class="col-6 col-md-2 col-lg mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="verbal" data-value="5">
                                <div class="fw-bold">5</div>
                                <div>Oriented</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-2 col-lg mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="verbal" data-value="4">
                                <div class="fw-bold">4</div>
                                <div>Confused</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-2 col-lg mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="verbal" data-value="3">
                                <div class="fw-bold">3</div>
                                <div>Words</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-2 col-lg mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="verbal" data-value="2">
                                <div class="fw-bold">2</div>
                                <div>Sounds</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-2 col-lg mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="verbal" data-value="1">
                                <div class="fw-bold">1</div>
                                <div>None</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-2 col-lg mb-3">
                            <button class="btn btn-outline-warning w-100 gcs-btn-special" data-category="verbal" data-value="T">
                                <div class="fw-bold">T</div>
                                <div>Intubated</div>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Selected: <span class="selected-option" id="verbal-selected">None</span></div>
                        <div>Score: <span class="selected-score" id="verbal-score">0</span>/5</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Motor Response Section -->
        <div class="col-md-10 mb-4">
            <div class="card gcs-card" id="motor-card">
                <div class="card-header section-header-treatment d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 mb-0"><i class="ti ti-hand-move me-2"></i>Motor Response</h2>
                    </div>
                    <button type="button" class="section-info-btn" data-section="motor">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row gcs-options">
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="motor" data-value="6">
                                <div class="fw-bold">6</div>
                                <div>Obeys Commands</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="motor" data-value="5">
                                <div class="fw-bold">5</div>
                                <div>Localizes Pain</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="motor" data-value="4">
                                <div class="fw-bold">4</div>
                                <div>Normal Flexion</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="motor" data-value="3">
                                <div class="fw-bold">3</div>
                                <div>Abnormal Flexion</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="motor" data-value="2">
                                <div class="fw-bold">2</div>
                                <div>Extension</div>
                            </button>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2 mb-3">
                            <button class="btn btn-outline-primary w-100 gcs-btn" data-category="motor" data-value="1">
                                <div class="fw-bold">1</div>
                                <div>None</div>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>Selected: <span class="selected-option" id="motor-selected">None</span></div>
                        <div>Score: <span class="selected-score" id="motor-score">0</span>/6</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Total GCS Score -->
        <div class="col-md-10 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0">Total GCS Score</h2>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4 mb-3 mb-md-0 text-center">
                            <div class="display-1 fw-bold" id="total-score">3</div>
                            <div class="text-muted" id="total-range">(of 3-15)</div>
                        </div>
                        <div class="col-md-8">
                            <div class="p-3 rounded" id="score-interpretation">
                                <h5 class="mb-2" id="severity-level">Severe Brain Injury</h5>
                                <p class="mb-1">GCS Score Breakdown:</p>
                                <ul class="mb-0">
                                    <li>Eye Opening: <span id="eye-summary">1 (None)</span></li>
                                    <li>Verbal Response: <span id="verbal-summary">1 (None)</span></li>
                                    <li>Motor Response: <span id="motor-summary">1 (None)</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <div class="d-grid">
                                <button class="btn btn-outline-secondary" id="reset-gcs">
                                    <i class="ti ti-refresh me-2"></i>Reset Calculator
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-grid">
                                <button class="btn btn-outline-primary" id="print-gcs">
                                    <i class="ti ti-printer me-2"></i>Print Results
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Interpretation Key -->
        <div class="col-md-10 mb-4">
            <div class="card">
                <div class="card-header section-header-reference">
                    <h2 class="h5 mb-0"><i class="ti ti-info-circle me-2"></i>GCS Interpretation</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Total GCS</th>
                                    <th>Severity Level</th>
                                    <th>Clinical Significance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold">13-15</td>
                                    <td class="text-success fw-bold">Mild Brain Injury</td>
                                    <td>Patient may be awake, alert, and able to follow commands with minor neurological deficits</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">9-12</td>
                                    <td class="text-warning fw-bold">Moderate Brain Injury</td>
                                    <td>Patient may be drowsy or confused, but responsive to stimulation</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">3-8</td>
                                    <td class="text-danger fw-bold">Severe Brain Injury</td>
                                    <td>Patient is comatose, unconscious, and may require airway protection and ventilatory support</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="ti ti-alert-triangle me-2"></i>
                        <strong>Note:</strong> For intubated patients, the verbal score is recorded as "T" and the total score is reported with a designation that the verbal component could not be assessed (e.g., "10T").
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Information Modals -->
<!-- Eye Response Modal -->
<div class="modal fade" id="eye-modal" tabindex="-1" aria-labelledby="eyeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="eyeModalLabel">Assessing Eye Opening Response</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Eye opening is an important indicator of arousal. The points are assigned as follows:</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Score</th>
                                <th>Response</th>
                                <th>Description</th>
                                <th>Assessment Technique</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center fw-bold">4</td>
                                <td><strong>Spontaneous</strong></td>
                                <td>Eyes open without any stimulation</td>
                                <td>Observe the patient without interaction. If eyes are open when you approach, score 4.</td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">3</td>
                                <td><strong>To Sound</strong></td>
                                <td>Eyes open in response to auditory stimulation</td>
                                <td>If eyes closed, use verbal commands in normal voice, then progressively louder if no response. Say the patient's name or give a command like "Open your eyes!"</td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">2</td>
                                <td><strong>To Pressure</strong></td>
                                <td>Eyes open only in response to painful stimulation</td>
                                <td>Apply pressure to the nail bed, supraorbital ridge, or trapezius muscle. Note: Use pressure stimuli only if no response to sound.</td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">1</td>
                                <td><strong>None</strong></td>
                                <td>No eye opening despite stimulation</td>
                                <td>After applying appropriate stimuli as described above, there is no eye opening response.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-info mt-3">
                    <strong>Special Considerations:</strong>
                    <ul class="mb-0">
                        <li>If eyes are swollen shut, document as "NT" (not testable).</li>
                        <li>Always progress from least to most painful stimulation.</li>
                        <li>Document which stimulus produced the response.</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Verbal Response Modal -->
<div class="modal fade" id="verbal-modal" tabindex="-1" aria-labelledby="verbalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="verbalModalLabel">Assessing Verbal Response</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Verbal response assesses the patient's awareness and cognitive function. The points are assigned as follows:</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Score</th>
                                <th>Response</th>
                                <th>Description</th>
                                <th>Assessment Technique</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center fw-bold">5</td>
                                <td><strong>Oriented</strong></td>
                                <td>Patient is fully oriented to person, place, time, and situation</td>
                                <td>Ask questions like: "What is your name?", "Where are you right now?", "What month/year is it?", "What happened to you?"</td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">4</td>
                                <td><strong>Confused</strong></td>
                                <td>Patient can converse but is disoriented or confused</td>
                                <td>Patient can speak in sentences but answers questions incorrectly, is disoriented to one or more spheres (person, place, time, situation).</td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">3</td>
                                <td><strong>Words</strong></td>
                                <td>Patient speaks intelligible words but not in sentences or coherently</td>
                                <td>Patient may speak only a few words in response to questions or stimuli, may repeat the same word, or may curse.</td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">2</td>
                                <td><strong>Sounds</strong></td>
                                <td>Patient makes vocal sounds but no recognizable words</td>
                                <td>Patient makes moaning, groaning, or other vocal sounds but no comprehensible words.</td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">1</td>
                                <td><strong>None</strong></td>
                                <td>No verbal response despite stimulation</td>
                                <td>Patient makes no sounds even with painful stimulation.</td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">T</td>
                                <td><strong>Intubated</strong></td>
                                <td>Patient has an artificial airway (endotracheal tube or tracheostomy)</td>
                                <td>Record as "T" and note that verbal response cannot be assessed; the score should be reported with the suffix "T" (e.g., "10T").</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-info mt-3">
                    <strong>Special Considerations:</strong>
                    <ul class="mb-0">
                        <li>Consider language barriers, deafness, or dysarthria when assessing verbal response.</li>
                        <li>Inappropriate words or random speech score as 3 (words).</li>
                        <li>For children, adjust expectations based on developmental stage.</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Motor Response Modal -->
<div class="modal fade" id="motor-modal" tabindex="-1" aria-labelledby="motorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="motorModalLabel">Assessing Motor Response</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Motor response is the most important component of the GCS and has strong prognostic value. Always record the best motor response observed. The points are assigned as follows:</p>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Score</th>
                                <th>Response</th>
                                <th>Description</th>
                                <th>Assessment Technique</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center fw-bold">6</td>
                                <td><strong>Obeys Commands</strong></td>
                                <td>Patient follows commands correctly</td>
                                <td>Give the patient a simple two-step command such as "Raise your arms and then wiggle your fingers." Avoid commands that could be reflexive responses.</td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">5</td>
                                <td><strong>Localizes Pain</strong></td>
                                <td>Patient attempts to remove source of pain or moves limb toward painful stimulus</td>
                                <td>Apply central painful stimulus (e.g., trapezius pinch or sternal rub). Patient reaches toward or attempts to remove the source of pain.</td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">4</td>
                                <td><strong>Normal Flexion (Withdraws)</strong></td>
                                <td>Patient flexes arm at elbow rapidly but without localizing features</td>
                                <td>Apply painful stimulus to limbs (e.g., nail bed pressure). Patient withdraws from pain in a non-specific manner without localizing.</td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">3</td>
                                <td><strong>Abnormal Flexion (Decorticate)</strong></td>
                                <td>Abnormal posturing with arm flexion, wrist flexion, and leg extension</td>
                                <td>After painful stimulus, arms flex at elbow, wrists flex, and legs extend (decorticate posturing).</td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">2</td>
                                <td><strong>Extension (Decerebrate)</strong></td>
                                <td>Abnormal posturing with arm extension, internal rotation, and leg extension</td>
                                <td>After painful stimulus, arms extend at elbow, wrists rotate internally, and legs extend (decerebrate posturing).</td>
                            </tr>
                            <tr>
                                <td class="text-center fw-bold">1</td>
                                <td><strong>None</strong></td>
                                <td>No motor response to any stimulus</td>
                                <td>No movement in any limb even after maximum painful stimulus.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="alert alert-info mt-3">
                    <strong>Special Considerations:</strong>
                    <ul class="mb-0">
                        <li>Always record the best motor response observed in any limb.</li>
                        <li>Spinal cord injury may affect motor response in certain limbs; test unaffected limbs.</li>
                        <li>Distinguish between voluntary and reflexive movements.</li>
                        <li>In patients with asymmetric responses, document both sides (e.g., "Right: 5, Left: 3").</li>
                    </ul>
                </div>
                <div class="alert alert-warning mt-2">
                    <strong>Note on Painful Stimuli:</strong> Use peripheral stimuli (e.g., nail bed pressure) first. Central stimuli (trapezius pinch, sternal rub, supraorbital pressure) should be used only if necessary. Always use the minimum pressure required to elicit a response.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
/* GCS Calculator specific styles */
.gcs-btn, .gcs-btn-special {
    height: 100%;
    min-height: 70px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 0.75rem 0.5rem;
    transition: all 0.2s ease;
}

.gcs-btn.selected, .gcs-btn-special.selected {
    transform: scale(1.02);
    box-shadow: 0 2px 4px rgba(0,0,0,0.15);
}

.gcs-btn.selected {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.gcs-btn-special.selected {
    background-color: #fd7e14;
    color: white;
    border-color: #fd7e14;
}

/* Section info button */
.section-info-btn {
    background-color: transparent;
    color: #495057;
    border: none;
    padding: 0.25rem;
    font-size: 1.1rem;
    border-radius: 50%;
    transition: all 0.2s;
}

.section-info-btn:hover {
    color: var(--primary-color);
    transform: scale(1.1);
}

/* Severity styling */
#score-interpretation {
    background-color: #f8f9fa;
    border-left: 4px solid #6c757d;
}

/* Severity levels */
.severity-mild {
    border-left-color: #28a745 !important;
}
.severity-moderate {
    border-left-color: #fd7e14 !important;
}
.severity-severe {
    border-left-color: #dc3545 !important;
}

/* Print-specific styles */
@media print {
    header, footer, .navbar, .btn, #reset-gcs, #print-gcs, .section-info-btn {
        display: none !important;
    }
    
    .container {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .card {
        break-inside: avoid;
        border: 1px solid #ddd !important;
        margin-bottom: 15px !important;
    }
    
    .card-header {
        background-color: #f1f1f1 !important;
        color: #000 !important;
        border-bottom: 1px solid #ddd !important;
    }
    
    .gcs-btn, .gcs-btn-special {
        border: 1px solid #ddd !important;
        color: #000 !important;
        background-color: #fff !important;
    }
    
    .gcs-btn.selected, .gcs-btn-special.selected {
        background-color: #e9e9e9 !important;
        border: 1px solid #999 !important;
        font-weight: bold !important;
    }
    
    body {
        padding: 0 !important;
        margin: 0 !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables to track scores
    let scores = {
        eye: 0,
        verbal: 0,
        motor: 0
    };
    
    let labels = {
        eye: {
            1: "None",
            2: "To Pressure",
            3: "To Sound",
            4: "Spontaneous"
        },
        verbal: {
            1: "None",
            2: "Sounds",
            3: "Words",
            4: "Confused",
            5: "Oriented",
            "T": "Intubated"
        },
        motor: {
            1: "None",
            2: "Extension",
            3: "Abnormal Flexion",
            4: "Normal Flexion",
            5: "Localizes Pain",
            6: "Obeys Commands"
        }
    };
    
    // Handle GCS selection
    const gcsButtons = document.querySelectorAll('.gcs-btn, .gcs-btn-special');
    gcsButtons.forEach(button => {
        button.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            const value = this.getAttribute('data-value');
            
            // Deselect all buttons in this category
            document.querySelectorAll(`.gcs-btn[data-category="${category}"], .gcs-btn-special[data-category="${category}"]`).forEach(btn => {
                btn.classList.remove('selected');
            });
            
            // Select this button
            this.classList.add('selected');
            
            // Update score
            if (value === 'T') {
                scores[category] = 'T';
                document.getElementById(`${category}-selected`).textContent = labels[category][value];
                document.getElementById(`${category}-score`).textContent = 'T';
            } else {
                scores[category] = parseInt(value);
                document.getElementById(`${category}-selected`).textContent = labels[category][value];
                document.getElementById(`${category}-score`).textContent = value;
            }
            
            // Update totals
            updateTotalScore();
            
            // Save to localStorage
            saveGcsState();
        });
    });
    
    // Section info buttons
    const sectionInfoButtons = document.querySelectorAll('.section-info-btn');
    sectionInfoButtons.forEach(button => {
        button.addEventListener('click', function() {
            const section = this.getAttribute('data-section');
            const modal = new bootstrap.Modal(document.getElementById(`${section}-modal`));
            modal.show();
        });
    });
    
    // Reset GCS calculator
    const resetButton = document.getElementById('reset-gcs');
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            // Deselect all buttons
            document.querySelectorAll('.gcs-btn, .gcs-btn-special').forEach(btn => {
                btn.classList.remove('selected');
            });
            
            // Reset scores
            scores = {
                eye: 0,
                verbal: 0,
                motor: 0
            };
            
            // Update displays
            document.getElementById('eye-selected').textContent = 'None';
            document.getElementById('verbal-selected').textContent = 'None';
            document.getElementById('motor-selected').textContent = 'None';
            
            document.getElementById('eye-score').textContent = '0';
            document.getElementById('verbal-score').textContent = '0';
            document.getElementById('motor-score').textContent = '0';
            
            // Update totals
            updateTotalScore();
            
            // Clear localStorage
            localStorage.removeItem('gcsCalculator');
        });
    }
    
    // Print GCS results
    const printButton = document.getElementById('print-gcs');
    if (printButton) {
        printButton.addEventListener('click', function() {
            window.print();
        });
    }
    
    // Update total score and interpretation
    function updateTotalScore() {
        let totalScore = 0;
        let scoreString = '';
        
        // Calculate total score
        if (scores.eye !== 'T' && scores.verbal !== 'T' && scores.motor !== 'T') {
            totalScore = (scores.eye || 0) + (scores.verbal || 0) + (scores.motor || 0);
            scoreString = totalScore.toString();
            document.getElementById('total-range').textContent = '(of 3-15)';
        } else {
            // Handle intubated patient
            let countableScores = 0;
            
            if (scores.eye !== 'T') countableScores += (scores.eye || 0);
            if (scores.verbal !== 'T') countableScores += (scores.verbal || 0);
            if (scores.motor !== 'T') countableScores += (scores.motor || 0);
            
            totalScore = countableScores;
            
            if (scores.verbal === 'T') {
                scoreString = countableScores + 'T';
                document.getElementById('total-range').textContent = '(verbal score not included)';
            } else {
                scoreString = countableScores.toString();
                document.getElementById('total-range').textContent = '(of 3-15)';
            }
        }
        
        // Update total score display
        document.getElementById('total-score').textContent = scoreString;
        
        // Update interpretation
        let severityLevel = '';
        let scoreColor = '';
        
        if (totalScore >= 13 && totalScore <= 15) {
            severityLevel = 'Mild Brain Injury';
            scoreColor = 'severity-mild';
        } else if (totalScore >= 9 && totalScore <= 12) {
            severityLevel = 'Moderate Brain Injury';
            scoreColor = 'severity-moderate';
        } else {
            severityLevel = 'Severe Brain Injury';
            scoreColor = 'severity-severe';
        }
        
        document.getElementById('severity-level').textContent = severityLevel;
        
        // Update the border color
        const scoreInterpretation = document.getElementById('score-interpretation');
        scoreInterpretation.classList.remove('severity-mild', 'severity-moderate', 'severity-severe');
        scoreInterpretation.classList.add(scoreColor);
        
        // Update summary
        const eyeVal = scores.eye || 0;
        const verbalVal = scores.verbal || 0;
        const motorVal = scores.motor || 0;
        
        document.getElementById('eye-summary').textContent = scores.eye === 'T' ? 
            'T (Untestable)' : `${eyeVal} (${eyeVal > 0 ? labels.eye[eyeVal] : 'Not Assessed'})`;
            
        document.getElementById('verbal-summary').textContent = scores.verbal === 'T' ? 
            'T (Intubated)' : `${verbalVal} (${verbalVal > 0 ? labels.verbal[verbalVal] : 'Not Assessed'})`;
            
        document.getElementById('motor-summary').textContent = scores.motor === 'T' ? 
            'T (Untestable)' : `${motorVal} (${motorVal > 0 ? labels.motor[motorVal] : 'Not Assessed'})`;
    }
    
    // Save state to localStorage
    function saveGcsState() {
        const state = {
            scores: scores,
            selectedButtons: {}
        };
        
        // Get selected buttons
        document.querySelectorAll('.gcs-btn.selected, .gcs-btn-special.selected').forEach(btn => {
            const category = btn.getAttribute('data-category');
            const value = btn.getAttribute('data-value');
            state.selectedButtons[category] = value;
        });
        
        localStorage.setItem('gcsCalculator', JSON.stringify(state));
    }
    
    // Load state from localStorage
    function loadGcsState() {
        const savedState = localStorage.getItem('gcsCalculator');
        if (savedState) {
            const state = JSON.parse(savedState);
            scores = state.scores;
            
            // Set selected buttons
            for (const category in state.selectedButtons) {
                const value = state.selectedButtons[category];
                const button = document.querySelector(`.gcs-btn[data-category="${category}"][data-value="${value}"], .gcs-btn-special[data-category="${category}"][data-value="${value}"]`);
                if (button) {
                    button.classList.add('selected');
                }
                
                // Update displayed values
                if (value === 'T') {
                    document.getElementById(`${category}-selected`).textContent = labels[category][value];
                    document.getElementById(`${category}-score`).textContent = 'T';
                } else {
                    document.getElementById(`${category}-selected`).textContent = labels[category][value];
                    document.getElementById(`${category}-score`).textContent = value;
                }
            }
            
            // Update total score
            updateTotalScore();
        } else {
            // Initialize with lowest scores (3)
            const defaultButtons = [
                document.querySelector('.gcs-btn[data-category="eye"][data-value="1"]'),
                document.querySelector('.gcs-btn[data-category="verbal"][data-value="1"]'),
                document.querySelector('.gcs-btn[data-category="motor"][data-value="1"]')
            ];
            
            defaultButtons.forEach(button => {
                if (button) {
                    button.click();
                }
            });
        }
    }
    
    // Initialize the page
    loadGcsState();
});
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>