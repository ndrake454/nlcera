<?php
/**
 * Basic Airway Management Checklist Tool
 * Optimized for Mobile/Touch
 * 
 * Place this file in: /tools/airway_management.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'BVM and Airway Adjunct Checklist';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="airway-tool-container">
    <div class="airway-header">
        <h1>Basic Airway Management Checklist</h1>
    </div>
    
    <!-- Patient Assessment Section -->
    <div class="checklist-section" id="section-assessment">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-stethoscope"></i>
                <h2>Patient Assessment & Preparation</h2>
            </div>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Assess Patient & Airway Status</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check1" data-section="assessment">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Verify level of consciousness and responsiveness</li>
                        <li>Check breathing: look for chest rise, listen for sounds, feel for air movement</li>
                        <li>Assess for airway obstructions (secretions, vomit, foreign bodies)</li>
                        <li>Monitor SpO2 and check for signs of hypoxemia</li>
                        <li>Consider C-spine precautions if trauma is suspected</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Prepare BVM Equipment</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check2" data-section="assessment">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Select appropriate BVM size.</li>
                        <li>Choose mask that extends from bridge of nose to cleft of chin</li>
                        <li>Ensure mask creates proper seal without covering eyes</li>
                        <li>Connect oxygen at 15 LPM.</li>
                        <li>Verify reservoir bag fills completely between ventilations</li>
                    </ul>
                </div>
                <div class="selected-device-display" id="selected-bvm-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-bvm-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Select & Prepare Airway Adjuncts</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check3" data-section="assessment">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li><strong>Oropharyngeal Airway (OPA):</strong>
                            <ul>
                                <li>Measure from corner of mouth to angle of jaw/earlobe</li>
                                <li>Use only in unconscious patients without gag reflex</li>
                                <li>Adult insertion: Insert upside down, then rotate 180°</li>
                            </ul>
                        </li>
                        <li><strong>Nasopharyngeal Airway (NPA):</strong>
                            <ul>
                                <li>Measure from tip of nose to tragus of ear</li>
                                <li>Can be used in patients with intact gag reflex</li>
                                <li>Lubricate with water-soluble lubricant before insertion</li>
                                <li>Insert along floor of nostril with bevel toward septum</li>
                            </ul>
                        </li>
                        <li>Prepare suction equipment with appropriate catheter</li>
                    </ul>
                </div>
                <div class="selected-device-display" id="selected-adjunct-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-adjunct-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
        </div>
    </div>
    
    <!-- Airway Management Section -->
    <div class="checklist-section" id="section-procedure">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-activity"></i>
                <h2>Airway Management Procedure</h2>
            </div>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Position & Prepare Airway</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check4" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Position patient with ear-to-sternal notch alignment</li>
                        <li>Perform head-tilt/chin-lift for non-trauma patients</li>
                        <li>Use jaw-thrust technique for suspected cervical spine injury</li>
                        <li>Suction airway if needed (limit attempts to 10-15 seconds)</li>
                        <li>Insert appropriate airway adjunct:
                            <ul>
                                <li>OPA for unconscious patients without gag reflex</li>
                                <li>NPA for patients with intact gag reflex or trismus</li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Perform BVM Ventilation</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check5" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Apply proper mask seal techniques:
                            <ul>
                                <li>EC-clamp technique (one-person) - thumb and index form 'C' on mask, other fingers form 'E' on jaw</li>
                                <li>Thenar eminence method for improved seal</li>
                                <li>Two-person technique for difficult cases (preferred method)</li>
                            </ul>
                        </li>
                        <li>Deliver breaths over 2 seconds with visible chest rise</li>
                        <li>Ventilation rates:
                            <ul>
                                <li>Adults: 10-12 breaths/min (1 breath every 5-6 seconds)</li>
                                <li>Children: 12-20 breaths/min</li>
                                <li>Infants: 20-30 breaths/min</li>
                                <li>Cardiac arrest: 10 breaths/min, asynchronous with 10th compressions</li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="selected-technique-display" id="selected-technique-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-technique-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Monitor & Adjust</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check6" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Verify effectiveness:
                            <ul>
                                <li>Watch for bilateral chest rise</li>
                                <li>Listen for equal breath sounds</li>
                                <li>Monitor SpO2 improvements</li>
                                <li>Watch for improved color and condition</li>
                            </ul>
                        </li>
                        <li>Monitor for complications:
                            <ul>
                                <li>Gastric distension (risking regurgitation/aspiration)</li>
                                <li>Inadequate ventilation (insufficient chest rise)</li>
                                <li>Barotrauma from excessive pressure</li>
                                <li>Mask leak reducing effective ventilation</li>
                            </ul>
                        </li>
                        <li>Switch providers every 2-3 minutes to prevent fatigue</li>
                        <li>Consider advanced airway if basic management inadequate</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="tool-actions">
        <div class="action-warning">
            <small><em>This is a guide for basic airway management. Clinical judgment should always be prioritized.</em></small>
        </div>
        <div class="action-buttons">
            <button id="reset-checklist" class="action-button reset-button">
                <i class="ti ti-refresh"></i> Reset
            </button>
            <button id="generate-narrative" class="action-button generate-button">
                <i class="ti ti-file-text"></i> Generate Documentation
            </button>
            <button id="export-pdf" class="action-button pdf-button">
                <i class="ti ti-file-download"></i> Export as PDF
            </button>
        </div>
    </div>
</div>

<!-- BVM Size Selection Modal -->
<div class="tool-modal" id="bvmSizeModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>BVM Size Selection</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-text">Select the appropriate BVM size for your patient:</p>
            
            <div class="equipment-options">
                <div class="equipment-option" data-equipment="adult-bvm">
                    <img src="../assets/img/adult-bvm.png" alt="Adult BVM" class="equipment-image">
                    <div class="equipment-details">
                        <h3>Adult BVM</h3>
                        <p>1500-2000 mL volume<br>For patients >40 kg</p>
                    </div>
                    <input type="radio" name="bvm-size" value="adult-bvm" id="adult-bvm">
                    <label for="adult-bvm" class="equipment-label"></label>
                </div>
                
                <div class="equipment-option" data-equipment="pediatric-bvm">
                    <img src="../assets/img/pediatric-bvm.png" alt="Pediatric BVM" class="equipment-image">
                    <div class="equipment-details">
                        <h3>Pediatric BVM</h3>
                        <p>500-700 mL volume<br>For patients 10-40 kg</p>
                    </div>
                    <input type="radio" name="bvm-size" value="pediatric-bvm" id="pediatric-bvm">
                    <label for="pediatric-bvm" class="equipment-label"></label>
                </div>
                
                <div class="equipment-option" data-equipment="infant-bvm">
                    <img src="../assets/img/infant-bvm.png" alt="Infant BVM" class="equipment-image">
                    <div class="equipment-details">
                        <h3>Infant BVM</h3>
                        <p>250 mL volume<br>For patients <10 kg</p>
                    </div>
                    <input type="radio" name="bvm-size" value="infant-bvm" id="infant-bvm">
                    <label for="infant-bvm" class="equipment-label"></label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-button save-button" id="save-bvm-size">Save Selection</button>
        </div>
    </div>
</div>

<!-- Airway Adjunct Selection Modal -->
<div class="tool-modal" id="adjunctModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Airway Adjunct Selection</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-text">Select the appropriate airway adjunct(s):</p>
            
            <div class="adjunct-selection">
                <div class="adjunct-type">
                    <h3>Oropharyngeal Airway (OPA)</h3>
                    <div class="equipment-options">
                        <div class="equipment-option" data-equipment="opa-large">
                            <img src="../assets/img/opa-large.png" alt="Large OPA" class="equipment-image">
                            <div class="equipment-details">
                                <h3>Large OPA</h3>
                                <p>Size 5-6<br>For average adults</p>
                            </div>
                            <input type="checkbox" name="adjunct-type" value="opa-large" id="opa-large">
                            <label for="opa-large" class="equipment-label"></label>
                        </div>
                        
                        <div class="equipment-option" data-equipment="opa-medium">
                            <img src="../assets/img/opa-medium.png" alt="Medium OPA" class="equipment-image">
                            <div class="equipment-details">
                                <h3>Medium OPA</h3>
                                <p>Size 3-4<br>For small adults/adolescents</p>
                            </div>
                            <input type="checkbox" name="adjunct-type" value="opa-medium" id="opa-medium">
                            <label for="opa-medium" class="equipment-label"></label>
                        </div>
                        
                        <div class="equipment-option" data-equipment="opa-small">
                            <img src="../assets/img/opa-small.png" alt="Small OPA" class="equipment-image">
                            <div class="equipment-details">
                                <h3>Small OPA</h3>
                                <p>Size 0-2<br>For children/infants</p>
                            </div>
                            <input type="checkbox" name="adjunct-type" value="opa-small" id="opa-small">
                            <label for="opa-small" class="equipment-label"></label>
                        </div>
                    </div>
                </div>
                
                <div class="adjunct-type mt-4">
                    <h3>Nasopharyngeal Airway (NPA)</h3>
                    <div class="equipment-options">
                        <div class="equipment-option" data-equipment="npa-large">
                            <img src="../assets/img/npa-large.png" alt="Large NPA" class="equipment-image">
                            <div class="equipment-details">
                                <h3>Large NPA</h3>
                                <p>Size 7-9<br>For average adults</p>
                            </div>
                            <input type="checkbox" name="adjunct-type" value="npa-large" id="npa-large">
                            <label for="npa-large" class="equipment-label"></label>
                        </div>
                        
                        <div class="equipment-option" data-equipment="npa-medium">
                            <img src="../assets/img/npa-medium.png" alt="Medium NPA" class="equipment-image">
                            <div class="equipment-details">
                                <h3>Medium NPA</h3>
                                <p>Size 5-6<br>For small adults/adolescents</p>
                            </div>
                            <input type="checkbox" name="adjunct-type" value="npa-medium" id="npa-medium">
                            <label for="npa-medium" class="equipment-label"></label>
                        </div>
                        
                        <div class="equipment-option" data-equipment="npa-small">
                            <img src="../assets/img/npa-small.png" alt="Small NPA" class="equipment-image">
                            <div class="equipment-details">
                                <h3>Small NPA</h3>
                                <p>Size 3-4.5<br>For children</p>
                            </div>
                            <input type="checkbox" name="adjunct-type" value="npa-small" id="npa-small">
                            <label for="npa-small" class="equipment-label"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-button save-button" id="save-adjunct">Save Selection</button>
        </div>
    </div>
</div>

<!-- BVM Technique Selection Modal -->
<div class="tool-modal" id="techniqueModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>BVM Technique Selection</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-text">Select the ventilation technique used:</p>
            
            <div class="technique-selection">
                <div class="technique-options">
                    <div class="technique-option" data-technique="one-person">
                        <img src="../assets/img/one-person-bvm.png" alt="One-Person Technique" class="technique-image">
                        <div class="technique-details">
                            <h3>One-Person BVM</h3>
                            <p>E-C clamp technique with one provider<br>Challenging to maintain good mask seal</p>
                        </div>
                        <input type="radio" name="bvm-technique" value="one-person" id="one-person">
                        <label for="one-person" class="technique-label"></label>
                    </div>
                    
                    <div class="technique-option" data-technique="two-person">
                        <img src="../assets/img/two-person-bvm.png" alt="Two-Person Technique" class="technique-image">
                        <div class="technique-details">
                            <h3>Two-Person BVM</h3>
                            <p>Two-handed mask seal with dedicated ventilator<br>Preferred technique for optimal ventilation</p>
                        </div>
                        <input type="radio" name="bvm-technique" value="two-person" id="two-person">
                        <label for="two-person" class="technique-label"></label>
                    </div>
                    
                    <div class="technique-option" data-technique="thenar-eminence">
                        <img src="../assets/img/thenar-eminence.png" alt="Thenar Eminence Technique" class="technique-image">
                        <div class="technique-details">
                            <h3>Thenar Eminence</h3>
                            <p>Using thenar muscles for improved mask seal<br>Better leverage compared to E-C clamp</p>
                        </div>
                        <input type="radio" name="bvm-technique" value="thenar-eminence" id="thenar-eminence">
                        <label for="thenar-eminence" class="technique-label"></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-button save-button" id="save-technique">Save Selection</button>
        </div>
    </div>
</div>

<!-- Documentation Form Modal -->
<div class="tool-modal" id="narrativeFormModal">
    <div class="modal-content large-modal">
        <div class="modal-header">
            <h2>Enter Procedure Details</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="patientInfoForm" class="narrative-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="airwayIndication">Primary Indication</label>
                        <select class="form-select" id="airwayIndication">
                            <option value="respiratory distress">Respiratory Distress</option>
                            <option value="respiratory failure">Respiratory Failure</option>
                            <option value="altered mental status">Altered Mental Status</option>
                            <option value="airway protection">Airway Protection</option>
                            <option value="cardiac arrest">Cardiac Arrest</option>
                            <option value="trauma">Trauma</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="initialSpO2">Initial SpO2</label>
                        <div class="input-group">
                            <input type="number" class="form-input" id="initialSpO2" placeholder="%" min="0" max="100">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-section" id="equipment-details-container">
                    <!-- This will be populated with selected equipment -->
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="ventilationRate">Ventilation Rate</label>
                        <div class="input-group">
                            <input type="number" class="form-input" id="ventilationRate" placeholder="breaths/min" value="10">
                            <span class="input-group-text">breaths/min</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="oxygenLPM">Oxygen Flow Rate</label>
                        <div class="input-group">
                            <input type="number" class="form-input" id="oxygenLPM" placeholder="LPM" value="15">
                            <span class="input-group-text">LPM</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="peepSetting">PEEP Setting (if used)</label>
                        <div class="input-group">
                            <input type="number" class="form-input" id="peepSetting" placeholder="cmH2O" value="0">
                            <span class="input-group-text">cmH2O</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="postSpO2">Post-Intervention SpO2</label>
                        <div class="input-group">
                            <input type="number" class="form-input" id="postSpO2" placeholder="%" min="0" max="100">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Complications</h3>
                    <div class="comp-checkboxes">
                        <label class="comp-checkbox">
                            <input type="checkbox" id="comp-none" checked>
                            <span>No complications</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-difficulty">
                            <span>Difficulty maintaining seal</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-vomiting">
                            <span>Vomiting</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-gastric">
                            <span>Gastric distention</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-inadequate">
                            <span>Inadequate ventilation</span>
                        </label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="action-button primary-button" id="generateNarrativeBtn">Generate Narrative</button>
        </div>
    </div>
</div>

<!-- Generated Narrative Modal -->
<div class="tool-modal" id="narrativeModal">
    <div class="modal-content large-modal">
        <div class="modal-header">
            <h2>Documentation Narrative</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="narrative-box">
                <p id="narrative-text">Narrative text will appear here.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-button primary-button" id="copy-narrative">
                <i class="ti ti-copy"></i> Copy to Clipboard
            </button>
        </div>
    </div>
</div>

<!-- Continue Session Modal -->
<div class="tool-modal" id="continueSessionModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Previous Session Detected</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <p>We've detected a previous airway management checklist session. Would you like to continue where you left off or start a new checklist?</p>
        </div>
        <div class="modal-footer session-buttons">
            <button class="action-button" id="new-session-btn">Start New</button>
            <button class="action-button primary-button" id="continue-session-btn">Continue Previous</button>
        </div>
    </div>
</div>

<!-- Add html2pdf library for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<!-- Updated styling to match intubation page -->
<style>
/* Reset and custom styles to override site defaults */
.airway-tool-container * {
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.airway-tool-container {
    max-width: 800px;
    margin: 0 auto 3rem;
    overflow: hidden;
    padding-bottom: 20px;
}

.airway-header {
    background-color: #106e9e;
    color: white;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
}

.airway-header h1 {
    margin: 0 0 10px;
    font-size: 28px;
    font-weight: 600;
}

.airway-header p {
    margin: 0;
    opacity: 0.9;
}

/* Section Styling */
.checklist-section {
    background-color: white;
    border-radius: 10px;
    margin: 15px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    transition: transform 0.2s, box-shadow 0.2s;
    overflow: hidden;
    border: 2px solid #106e9e;
}

.checklist-section:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background-color: #f0f6fa;
    border-bottom: 1px solid #106e9e;
}

.section-title {
    display: flex;
    align-items: center;
}

.section-title i {
    color: #106e9e;
    font-size: 20px;
    margin-right: 10px;
}

.section-title h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    color: #2c3e50;
}

/* Checklist Items */
.checklist-items {
    padding: 5px 0;
}

.checklist-item {
    display: flex;
    flex-direction: column;
    padding: 12px 15px;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s;
    position: relative;
}

.checklist-item:nth-child(even) {
    background-color: #f8f9fa;
}

.checklist-item:last-child {
    border-bottom: none;
}

.checklist-item:hover {
    background-color: #e9f2f9;
}

.checklist-item-row {
    display: flex;
    width: 100%;
    align-items: flex-start;
}

/* Custom Checkboxes */
.checkbox-container {
    display: flex;
    align-items: center;
    position: relative;
    padding-left: 35px;
    margin-bottom: 0;
    cursor: pointer;
    font-size: 16px;
    user-select: none;
    flex: 1;
    min-height: 30px; /* Ensure minimum height for better touch targets */
}

.checkbox-container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkbox-label {
    padding: 4px 0; /* Increased padding for better touch area */
    font-weight: 500; /* Slightly bolder text */
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 28px; /* Larger checkbox for touch */
    width: 28px;
    background-color: #e9ecef;
    border: 2px solid #ced4da;
    border-radius: 4px;
    transition: all 0.2s;
}

.checkbox-container:hover input ~ .checkmark {
    background-color: #d1e7f6;
    border-color: #106e9e;
    transform: scale(1.05); /* Slight grow effect on hover */
}

.checkbox-container input:checked ~ .checkmark {
    background-color: #106e9e;
    border-color: #106e9e;
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.checkbox-container input:checked ~ .checkmark:after {
    display: block;
}

.checkbox-container .checkmark:after {
    left: 9px;
    top: 4px;
    width: 7px;
    height: 14px;
    border: solid white;
    border-width: 0 3px 3px 0;
    transform: rotate(45deg);
}

/* Modified Information Content - Always Visible */
.info-content {
    margin-top: 5px; /* Reduced from 10px */
    padding: 0px 12px 8px 0; /* Reduced top padding */
    background-color: transparent;
    font-size: 13px;
    line-height: 1.5;
    color: #333;
    margin-left: 35px;
    font-family: "Helvetica Neue", Arial, sans-serif;
    font-weight: 400;
}

.info-content.always-visible {
    display: block !important;
}

.info-content p {
    margin: 0;
}

.info-content ul {
    margin-top: 5px;
    margin-bottom: 5px;
    padding-left: 20px;
}

.info-content li {
    margin-bottom: 3px;
}

.info-content ul ul {
    margin-top: 3px;
    margin-bottom: 5px;
}

.info-content strong {
    color: #0c5578;
    font-weight: 600;
}

/* Edit Selection Button */
.edit-selection-btn {
    position: absolute;
    right: 15px;
    top: 40px;
    padding: 4px 8px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    background-color: #f8f9fa;
    color: #495057;
    font-size: 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    transition: all 0.2s;
}

.edit-selection-btn i {
    margin-right: 4px;
    font-size: 12px;
}

.edit-selection-btn:hover {
    background-color: #e2e6ea;
    border-color: #ced4da;
}

/* Display for selected equipments */
.selected-device-display, .selected-technique-display {
    margin-top: 5px;
    margin-left: 35px;
    padding: 5px 10px;
    background-color: #f0f6fa;
    border-radius: 5px;
    font-size: 14px;
    display: none;
    width: calc(100% - 80px);
}

.selected-device-display.active, .selected-technique-display.active {
    display: block;
}

.selected-device-item, .selected-technique-item {
    display: flex;
    align-items: center;
    margin-bottom: 3px;
}

.selected-device-item i, .selected-technique-item i {
    margin-right: 5px;
    color: #106e9e;
    font-size: 14px;
}

/* Bottom Actions */
.tool-actions {
    padding: 15px;
    text-align: center;
}

.action-warning {
    background-color: #fff3cd;
    color: #856404;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 15px;
    font-size: 14px;
}

.action-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.action-button {
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    font-size: 15px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.action-button i {
    margin-right: 6px;
}

.reset-button {
    background-color: #6c757d;
    color: white;
}

.reset-button:hover {
    background-color: #5a6268;
}

.generate-button {
    background-color: #28a745;
    color: white;
}

.generate-button:hover {
    background-color: #218838;
}

.pdf-button {
    background-color: #dc3545;
    color: white;
}

.pdf-button:hover {
    background-color: #c82333;
}

/* Completed Item Styling */
.checklist-item.checked {
    background-color: rgba(40, 167, 69, 0.05);
    border-left: 4px solid #28a745;
}

/* Section completion styles */
.checklist-section.completed {
    border-color: #28a745;
}

.checklist-section.completed .section-header {
    background-color: rgba(40, 167, 69, 0.1);
}

/* Modal styles remain the same */
.tool-modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5);
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    position: relative;
    background-color: #fefefe;
    margin: 10vh auto;
    padding: 0;
    border: 1px solid #ddd;
    width: 90%;
    max-width: 500px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    animation: slideIn 0.3s;
}

.large-modal {
    max-width: 700px;
}

@keyframes slideIn {
    from { transform: translateY(-30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.modal-header {
    padding: 15px 20px;
    background-color: #106e9e;
    color: white;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 500;
}

.close-modal {
    color: white;
    background: none;
    border: none;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.close-modal:hover {
    opacity: 1;
}

.modal-body {
    padding: 20px;
    max-height: 70vh;
    overflow-y: auto;
}

.modal-footer {
    padding: 15px 20px;
    background-color: #f8f9fa;
    text-align: right;
    border-top: 1px solid #ddd;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.session-buttons {
    justify-content: space-between;
}

.primary-button {
    background-color: #106e9e;
    color: white;
}

.primary-button:hover {
    background-color: #0c5578;
}

.close-button {
    background-color: #6c757d;
    color: white;
}

.close-button:hover {
    background-color: #5a6268;
}

.save-button {
    background-color: #28a745;
    color: white;
}

.save-button:hover {
    background-color: #218838;
}

/* Equipment styling */
.equipment-options {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.equipment-option {
    border: 2px solid #dee2e6;
    border-radius: 10px;
    padding: 15px;
    position: relative;
    cursor: pointer;
    transition: all 0.2s;
}

.equipment-option:hover {
    border-color: #adb5bd;
    background-color: #f8f9fa;
}

.equipment-option.selected {
    border-color: #106e9e;
    background-color: #e3f2fd;
}

.equipment-image {
    max-width: 100%;
    height: auto;
    margin-bottom: 10px;
    border-radius: 5px;
}

.equipment-details h3 {
    margin: 0 0 5px;
    font-size: 16px;
    font-weight: 600;
}

.equipment-details p {
    margin: 0;
    font-size: 14px;
    color: #6c757d;
}

.equipment-option input[type="radio"],
.equipment-option input[type="checkbox"] {
    position: absolute;
    opacity: 0;
}

.equipment-label {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    cursor: pointer;
}

/* Technique Selection Styling */
.technique-options {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 15px;
}

.technique-option {
    border: 2px solid #dee2e6;
    border-radius: 10px;
    padding: 15px;
    position: relative;
    cursor: pointer;
    transition: all 0.2s;
}

.technique-option:hover {
    border-color: #adb5bd;
    background-color: #f8f9fa;
}

.technique-option.selected {
    border-color: #106e9e;
    background-color: #e3f2fd;
}

.technique-image {
    max-width: 100%;
    height: auto;
    margin-bottom: 10px;
    border-radius: 5px;
}

.technique-details h3 {
    margin: 0 0 5px;
    font-size: 16px;
    font-weight: 600;
}

.technique-details p {
    margin: 0;
    font-size: 14px;
    color: #6c757d;
}

.technique-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.technique-label {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    cursor: pointer;
}

/* Form Styling */
.narrative-form {
    max-width: 100%;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -10px 15px;
}

.form-row .form-group {
    flex: 1;
    padding: 0 10px;
    min-width: 180px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
}

.form-input {
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    width: 100%;
    font-size: 16px;
}

.form-select {
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    width: 100%;
    font-size: 16px;
    background-color: white;
}

.input-group {
    display: flex;
    align-items: center;
}

.input-group .form-input {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    flex: 1;
}

.input-group-text {
    padding: 10px;
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-left: none;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
}

.form-section {
    margin-top: 20px;
    margin-bottom: 20px;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
}

.form-section h3 {
    margin-top: 0;
    margin-bottom: 15px;
    font-size: 18px;
    color: #343a40;
}

.comp-checkboxes {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 10px;
}

.comp-checkbox {
    display: flex;
    align-items: center;
    cursor: pointer;
    margin-bottom: 10px;
    padding: 10px 15px;
    background-color: #f8f9fa;
    border-radius: 6px;
    transition: all 0.2s;
    border: 1px solid #dee2e6;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

.comp-checkbox:hover {
    background-color: #e9f2f9;
    border-color: #106e9e;
}

.comp-checkbox input[type="checkbox"] {
    margin-right: 8px;
}

.comp-checkbox span {
    font-size: 15px;
    padding: 2px 0;
}

.comp-checkbox.complication-check-label {
    opacity: 0.5;
    pointer-events: none;
}

.comp-checkbox.complication-check-label.enabled {
    opacity: 1;
    pointer-events: auto;
}

/* Equipment Display in documentation */
.equipment-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 15px;
}

.equipment-item {
    background-color: #f0f7fc;
    border: 1px solid #cce5ff;
    border-radius: 4px;
    padding: 5px 10px;
    font-size: 14px;
    display: flex;
    align-items: center;
}

.equipment-item i {
    margin-right: 5px;
    color: #106e9e;
}

/* Narrative Results Styling */
.narrative-box {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 15px;
    font-size: 16px;
    line-height: 1.6;
    white-space: pre-wrap;
}

#narrative-text {
    margin: 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .modal-content {
        width: 95%;
        margin: 10px auto;
    }
    
    .equipment-options,
    .technique-options {
        grid-template-columns: 1fr;
    }
    
    .checklist-item {
        padding: 15px;
    }
    
    .selected-device-display,
    .selected-technique-display {
        width: 100%;
        margin-left: 0;
        margin-top: 10px;
    }
}

/* Touch-optimized elements */
@media (max-width: 992px) {
    .checkbox-container {
        padding-left: 40px;
    }
    
    .checkmark {
        width: 30px;
        height: 30px;
    }
    
    .checkbox-container .checkmark:after {
        left: 10px;
        top: 5px;
        width: 8px;
        height: 14px;
    }
    
    .action-button {
        padding: 14px 22px;
        font-size: 16px;
    }
}

/* Print styles for PDF export */
@media print {
    @page {
        size: letter portrait;
        margin: 0.5cm;
    }
    
    body {
        font-size: 12pt;
        line-height: 1.3;
        background: #fff;
        color: #000;
    }
    
    .airway-tool-container {
        max-width: 100%;
        margin: 0;
        padding: 0;
    }
    
    .airway-header {
        background-color: #106e9e !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    .checklist-section {
        page-break-inside: avoid;
        break-inside: avoid;
        margin: 10px 0 !important;
        box-shadow: none !important;
        border: 1px solid #ccc !important;
        border-left: 3px solid #106e9e !important;
        border-radius: 0 !important;
        transform: none !important;
    }
    
    .section-header {
        background-color: #f8f9fa !important;
        padding: 8px 10px !important;
    }
    
    .info-content {
        background-color: transparent !important;
        padding: 5px 10px !important;
    }
    
    .checkbox-container {
        padding-left: 25px !important;
    }
    
    .checkmark {
        border: 1px solid #000 !important;
        width: 16px !important;
        height: 16px !important;
        background-color: white !important;
        print-color-adjust: exact !important;
        -webkit-print-color-adjust: exact !important;
    }
    
    .checkbox-container input:checked ~ .checkmark {
        background-color: white !important;
        border: 1px solid #000 !important;
    }
    
    .checkbox-container .checkmark:after {
        left: 5px !important;
        top: 2px !important;
        width: 3px !important;
        height: 8px !important;
        border-color: #000 !important;
    }
    
    /* Hide interactive elements when printing */
    .tool-actions,
    .edit-selection-btn {
        display: none !important;
    }
    
    /* Show selected equipment/technique when printing */
    .selected-device-display.active,
    .selected-technique-display.active {
        display: block !important;
        background-color: #fff !important;
        border: 1px dashed #ccc !important;
    }
    
    .selected-device-item i,
    .selected-technique-item i {
        color: #000 !important;
    }
    
    /* Set high resolution for printing */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Force page break after Assessment section */
    #section-assessment {
        page-break-after: always !important;
    }
    
    /* Force Procedure section to start on a new page */
    #section-procedure {
        page-break-before: always !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Main elements
    const checkboxes = document.querySelectorAll('.checkbox-input');
    let previousSessionExists = false;
    
    // Calculate total checkboxes per section
    const sections = ['assessment', 'procedure'];
    const sectionCounts = {};
    
    sections.forEach(section => {
        const sectionCheckboxes = document.querySelectorAll(`.checkbox-input[data-section="${section}"]`);
        sectionCounts[section] = sectionCheckboxes.length;
    });
    
    // Function to update progress and section completion status
    function updateProgress() {
        // Update section completion status
        sections.forEach(section => {
            const sectionCheckboxes = document.querySelectorAll(`.checkbox-input[data-section="${section}"]`);
            const sectionCheckedCount = document.querySelectorAll(`.checkbox-input[data-section="${section}"]:checked`).length;
            
            // Visual indicator for section completion
            const sectionEl = document.querySelector(`#section-${section}`);
            if (sectionCheckedCount === sectionCounts[section]) {
                sectionEl.classList.add('completed');
            } else {
                sectionEl.classList.remove('completed');
            }
        });
        
        // Save state to localStorage
        saveCheckboxStates();
    }
    
    // Make entire checklist item area clickable
    document.querySelectorAll('.checklist-item').forEach(item => {
        item.addEventListener('click', function(e) {
            // Only trigger if not clicking on the checkbox itself, its label, or any buttons
            if (!e.target.closest('.checkbox-container') && 
                !e.target.closest('.edit-selection-btn')) {
                
                // Find the checkbox within this checklist item and toggle it
                const checkbox = this.querySelector('.checkbox-input');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    
                    // Update visual state of the checklist item
                    if (checkbox.checked) {
                        this.classList.add('checked');
                    } else {
                        this.classList.remove('checked');
                    }
                    
                    // Update progress
                    updateProgress();
                }
            }
        });
        
        // Add a cursor style to indicate clickability
        item.style.cursor = 'pointer';
    });
    
    // Toggle checked state for list items when checkbox is clicked
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const listItem = this.closest('.checklist-item');
            if (this.checked) {
                listItem.classList.add('checked');
            } else {
                listItem.classList.remove('checked');
            }
            
            updateProgress();
        });
    });
    
    // Custom modal handling
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent scrolling
        }
    }
    
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }
    }
    
    // Close modal when clicking the close button or outside the modal
    document.querySelectorAll('.close-modal, .close-button').forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.tool-modal');
            if (modal) {
                closeModal(modal.id);
            }
        });
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        document.querySelectorAll('.tool-modal').forEach(modal => {
            if (event.target === modal) {
                closeModal(modal.id);
            }
        });
    });
    
    // Reset checklist button
    const resetButton = document.getElementById('reset-checklist');
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to reset the entire checklist?')) {
                resetAllCheckboxes();
                
                // Clear displayed selections
                document.querySelectorAll('.selected-device-display, .selected-technique-display').forEach(display => {
                    display.innerHTML = '';
                    display.classList.remove('active');
                });
                
                // Hide edit buttons
                document.querySelectorAll('.edit-selection-btn').forEach(btn => {
                    btn.style.display = 'none';
                });
                
                // Scroll to top of page
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        });
    }
    
    // Generate Documentation button
    const generateNarrativeButton = document.getElementById('generate-narrative');
    if (generateNarrativeButton) {
        generateNarrativeButton.addEventListener('click', function() {
            // Update equipment display in the form
            updateEquipmentDetails();
            
            // Show the form to gather patient information
            openModal('narrativeFormModal');
        });
    }
    
    // Handle "No complications" checkbox logic
    const noComplicationsCheckbox = document.getElementById('comp-none');
    if (noComplicationsCheckbox) {
        const complicationCheckboxes = document.querySelectorAll('.complication-check');
        const complicationLabels = document.querySelectorAll('.complication-check-label');
        
        noComplicationsCheckbox.addEventListener('change', function() {
            if (this.checked) {
                complicationCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
                complicationLabels.forEach(label => {
                    label.classList.remove('enabled');
                });
            } else {
                complicationLabels.forEach(label => {
                    label.classList.add('enabled');
                });
            }
        });
        
        complicationCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    noComplicationsCheckbox.checked = false;
                    complicationLabels.forEach(label => {
                        label.classList.add('enabled');
                    });
                }
            });
        });
    }
    
    // Generate the narrative when the button is clicked
    document.getElementById('generateNarrativeBtn').addEventListener('click', function() {
        const narrative = generateNarrative();
        
        // Close the form modal
        closeModal('narrativeFormModal');
        
        // Set the narrative in the modal and show it
        document.getElementById('narrative-text').textContent = narrative;
        
        // Show the narrative modal
        openModal('narrativeModal');
    });
    
    // Copy narrative to clipboard
    document.getElementById('copy-narrative').addEventListener('click', function() {
        const narrativeText = document.getElementById('narrative-text').textContent;
        
        // Use the newer clipboard API if available
        if (navigator.clipboard) {
            navigator.clipboard.writeText(narrativeText)
                .then(() => {
                    this.innerHTML = '<i class="ti ti-check"></i> Copied!';
                    setTimeout(() => {
                        this.innerHTML = '<i class="ti ti-copy"></i> Copy to Clipboard';
                    }, 2000);
                })
                .catch(err => {
                    console.error('Failed to copy text: ', err);
                    fallbackCopyTextToClipboard(narrativeText);
                });
        } else {
            fallbackCopyTextToClipboard(narrativeText);
        }
    });
    
    // Fallback clipboard copy method
    function fallbackCopyTextToClipboard(text) {
        const textArea = document.createElement("textarea");
        textArea.value = text;
        
        // Make the textarea out of viewport
        textArea.style.position = "fixed";
        textArea.style.left = "-999999px";
        textArea.style.top = "-999999px";
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            const successful = document.execCommand('copy');
            const copyBtn = document.getElementById('copy-narrative');
            if (successful) {
                copyBtn.innerHTML = '<i class="ti ti-check"></i> Copied!';
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="ti ti-copy"></i> Copy to Clipboard';
                }, 2000);
            }
        } catch (err) {
            console.error('Fallback: Unable to copy', err);
        }
        
        document.body.removeChild(textArea);
    }
    
    // Save checkbox states
    function saveCheckboxStates() {
        const checkboxStates = {};
        checkboxes.forEach(checkbox => {
            checkboxStates[checkbox.id] = checkbox.checked;
        });
        
        localStorage.setItem('bvmChecklist', JSON.stringify(checkboxStates));
    }
    
// Load checkbox states
function loadCheckboxStates() {
    const savedData = localStorage.getItem('bvmChecklist');
    if (savedData) {
        try {
            const checkboxStates = JSON.parse(savedData);
            
            // Check if any checkboxes were saved as checked
            const anyChecked = Object.values(checkboxStates).some(state => state === true);
            previousSessionExists = anyChecked;
            
            if (anyChecked) {
                // Show continue session modal
                openModal('continueSessionModal');
                
                // Set up the continue session button
                document.getElementById('continue-session-btn').addEventListener('click', function() {
                    applyCheckboxStates(checkboxStates);
                    
                    closeModal('continueSessionModal');
                }, { once: true });
                
                // Set up the new session button
                document.getElementById('new-session-btn').addEventListener('click', function() {
                    // Clear local storage and reset the UI
                    localStorage.removeItem('bvmChecklist');
                    resetAllCheckboxes();
                    
                    closeModal('continueSessionModal');
                }, { once: true });
            } else {
                // If no checkboxes were previously checked, just apply the states directly
                applyCheckboxStates(checkboxStates);
            }
        } catch (e) {
            console.error("Error parsing saved checklist:", e);
            // If there's an error, just reset everything
            resetAllCheckboxes();
        }
    }
}

                    // Function to update equipment details in documentation form
    function updateEquipmentDetails() {
        const container = document.getElementById('equipment-details-container');
        let html = '<h3>Equipment Used</h3><div class="equipment-list">';
        
        // Add selected BVM size if any
        const bvmDisplay = document.getElementById('selected-bvm-display');
        if (bvmDisplay.classList.contains('active')) {
            html += bvmDisplay.innerHTML.replace('selected-device-item', 'equipment-item');
        }
        
        // Add selected adjuncts if any
        const adjunctDisplay = document.getElementById('selected-adjunct-display');
        if (adjunctDisplay.classList.contains('active')) {
            html += adjunctDisplay.innerHTML.replace(/selected-device-item/g, 'equipment-item');
        }
        
        // Add selected technique if any
        const techniqueDisplay = document.getElementById('selected-technique-display');
        if (techniqueDisplay.classList.contains('active')) {
            html += techniqueDisplay.innerHTML.replace('selected-technique-item', 'equipment-item');
        }
        
        html += '</div>';
        container.innerHTML = html;
    }
    
    // Apply saved checkbox states to the UI
    function applyCheckboxStates(checkboxStates) {
        checkboxes.forEach(checkbox => {
            if (checkboxStates[checkbox.id] !== undefined) {
                checkbox.checked = checkboxStates[checkbox.id];
                const listItem = checkbox.closest('.checklist-item');
                if (checkbox.checked) {
                    listItem.classList.add('checked');
                } else {
                    listItem.classList.remove('checked');
                }
            }
        });
        
        // Update progress indicators
        updateProgress();
    }
    
    // Reset all checkboxes
    function resetAllCheckboxes() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
            const listItem = checkbox.closest('.checklist-item');
            listItem.classList.remove('checked');
        });
        
        updateProgress();
    }
    
    // Generate narrative based on checklist and form inputs
    function generateNarrative() {
        // Get form values
        const indication = document.getElementById('airwayIndication').value;
        const initialSpO2 = document.getElementById('initialSpO2').value;
        const ventRate = document.getElementById('ventilationRate').value;
        const oxygenLpm = document.getElementById('oxygenLPM').value;
        const peepSetting = document.getElementById('peepSetting').value;
        const postSpO2 = document.getElementById('postSpO2').value;
        
        // Get complications
        const noComplications = document.getElementById('comp-none').checked;
        let complications = [];
        
        if (!noComplications) {
            if (document.getElementById('comp-difficulty').checked) complications.push('difficulty maintaining mask seal');
            if (document.getElementById('comp-vomiting').checked) complications.push('vomiting');
            if (document.getElementById('comp-gastric').checked) complications.push('gastric distention');
            if (document.getElementById('comp-inadequate').checked) complications.push('inadequate ventilation');
        }
        
        // Get checked items from the checklist
        const checkedItems = {};
        checkboxes.forEach(checkbox => {
            checkedItems[checkbox.id] = checkbox.checked;
        });
        
        // Build narrative
        let narrative = `BASIC AIRWAY MANAGEMENT\n`;
        narrative += `Indication: Patient presented with ${indication}.\n`;
        if (initialSpO2) narrative += `Initial SpO2: ${initialSpO2}%.\n`;
        
        // Patient assessment narrative
        narrative += `\nPATIENT ASSESSMENT:\n`;
        if (checkedItems.check1) {
            narrative += `Performed full patient assessment including level of consciousness, breathing pattern, and airway patency. `;
        }
        
        if (checkedItems.check2) {
            narrative += `Prepared appropriate BVM equipment with oxygen flowing at ${oxygenLpm} LPM. `;
        }
        
        // Adjunct narrative
        if (checkedItems.check3) {
            // Extract adjunct information from the display if available
            const adjunctDisplay = document.getElementById('selected-adjunct-display');
            if (adjunctDisplay.classList.contains('active') && adjunctDisplay.textContent.includes('OPA')) {
                narrative += `Inserted appropriately sized OPA in patient without gag reflex. `;
            } else if (adjunctDisplay.classList.contains('active') && adjunctDisplay.textContent.includes('NPA')) {
                narrative += `Inserted lubricated NPA due to intact gag reflex or difficult anatomy. `;
            } else {
                narrative += `Selected and prepared appropriate airway adjuncts based on patient assessment. `;
            }
        }
        
        // Procedure narrative
        narrative += `\nPROCEDURE:\n`;
        if (checkedItems.check4) {
            narrative += `Positioned patient appropriately and applied manual airway maneuvers. `;
            if (checkedItems.check3) {
                narrative += `Suctioned the airway as needed to maintain patency. `;
            }
        }
        
        if (checkedItems.check5) {
            // Extract technique information from the display if available
            const techniqueDisplay = document.getElementById('selected-technique-display');
            if (techniqueDisplay.classList.contains('active') && techniqueDisplay.textContent.includes('Two-Person')) {
                narrative += `Utilized two-person BVM technique with one provider maintaining mask seal with both hands while second provider ventilated. `;
            } else if (techniqueDisplay.classList.contains('active') && techniqueDisplay.textContent.includes('Thenar')) {
                narrative += `Utilized thenar eminence technique for improved mask seal. `;
            } else {
                narrative += `Applied proper mask seal using EC-clamp technique. `;
            }
            
            narrative += `Delivered ventilations at a rate of ${ventRate} breaths per minute with visible chest rise. `;
            
            if (parseInt(peepSetting) > 0) {
                narrative += `Applied PEEP valve at ${peepSetting} cmH2O. `;
            }
        }
        
        if (checkedItems.check6) {
            narrative += `\nMONITORING:\n`;
            narrative += `Continuously monitored ventilation effectiveness by observing chest rise, listening for breath sounds, and tracking SpO2. `;
            if (postSpO2) {
                narrative += `Post-intervention SpO2: ${postSpO2}%. `;
                
                if (initialSpO2) {
                    const spo2Change = parseInt(postSpO2) - parseInt(initialSpO2);
                    if (spo2Change > 0) {
                        narrative += `SpO2 improved by ${spo2Change}% from baseline. `;
                    } else if (spo2Change < 0) {
                        narrative += `SpO2 decreased by ${Math.abs(spo2Change)}% from baseline. `;
                    } else {
                        narrative += `SpO2 remained unchanged from baseline. `;
                    }
                }
            }
            
            narrative += `Watched for signs of complications and adjusted technique as needed. `;
        }
        
        // Complications
        narrative += `\nCOMPLICATIONS:\n`;
        if (noComplications) {
            narrative += `No complications were observed during the procedure.`;
        } else {
            narrative += `The following complications were addressed during the procedure: ${complications.join(', ')}.`;
        }
        
        return narrative;
    }
    
    // Initialize: Load saved states when page loads
    loadCheckboxStates();
    
    // Set up BVM size selection
    document.querySelectorAll('.equipment-option').forEach(option => {
        option.addEventListener('click', function() {
            const equipmentType = this.dataset.equipment;
            const radioInput = this.querySelector('input[type="radio"]');
            const checkboxInput = this.querySelector('input[type="checkbox"]');
            
            if (radioInput) {
                // For radio buttons (BVM size and Technique)
                radioInput.checked = true;
                
                // Clear previous selection
                document.querySelectorAll(`.equipment-option[data-equipment]`).forEach(opt => {
                    if (opt.querySelector('input[name="' + radioInput.name + '"]')) {
                        opt.classList.remove('selected');
                    }
                });
                
                // Mark as selected
                this.classList.add('selected');
            } else if (checkboxInput) {
                // For checkboxes (adjuncts)
                checkboxInput.checked = !checkboxInput.checked;
                
                if (checkboxInput.checked) {
                    this.classList.add('selected');
                } else {
                    this.classList.remove('selected');
                }
            }
        });
    });
    
    // Save BVM Size Selection
    document.getElementById('save-bvm-size').addEventListener('click', function() {
        const selectedRadio = document.querySelector('input[name="bvm-size"]:checked');
        
        if (selectedRadio) {
            // Update display
            const display = document.getElementById('selected-bvm-display');
            display.innerHTML = `<div class="selected-device-item"><i class="ti ti-first-aid-kit"></i> ${getEquipmentDisplayName(selectedRadio.value)}</div>`;
            display.classList.add('active');
            
            // Show edit button
            document.getElementById('edit-bvm-btn').style.display = 'block';
            
            // Close modal
            closeModal('bvmSizeModal');
        } else {
            alert('Please select a BVM size.');
        }
    });
    
    // Save Adjunct Selection
    document.getElementById('save-adjunct').addEventListener('click', function() {
        const selectedAdjuncts = Array.from(document.querySelectorAll('input[name="adjunct-type"]:checked')).map(input => input.value);
        
        if (selectedAdjuncts.length > 0) {
            // Update display
            const display = document.getElementById('selected-adjunct-display');
            display.innerHTML = selectedAdjuncts.map(adjunct => 
                `<div class="selected-device-item"><i class="ti ti-medical-cross"></i> ${getEquipmentDisplayName(adjunct)}</div>`
            ).join('');
            display.classList.add('active');
            
            // Show edit button
            document.getElementById('edit-adjunct-btn').style.display = 'block';
            
            // Close modal
            closeModal('adjunctModal');
        } else {
            alert('Please select at least one airway adjunct.');
        }
    });
    
    // Save Technique Selection
    document.getElementById('save-technique').addEventListener('click', function() {
        const selectedRadio = document.querySelector('input[name="bvm-technique"]:checked');
        
        if (selectedRadio) {
            // Update display
            const display = document.getElementById('selected-technique-display');
            display.innerHTML = `<div class="selected-technique-item"><i class="ti ti-hand-stop"></i> ${getEquipmentDisplayName(selectedRadio.value)}</div>`;
            display.classList.add('active');
            
            // Show edit button
            document.getElementById('edit-technique-btn').style.display = 'block';
            
            // Close modal
            closeModal('techniqueModal');
        } else {
            alert('Please select a ventilation technique.');
        }
    });
    
    // Edit Buttons
    document.getElementById('edit-bvm-btn').addEventListener('click', function() {
        openModal('bvmSizeModal');
    });
    
    document.getElementById('edit-adjunct-btn').addEventListener('click', function() {
        openModal('adjunctModal');
    });
    
    document.getElementById('edit-technique-btn').addEventListener('click', function() {
        openModal('techniqueModal');
    });
    
    // Helper function to get display names for equipment
    function getEquipmentDisplayName(equipmentCode) {
        const names = {
            // BVM sizes
            'adult-bvm': 'Adult BVM (1500-2000ml)',
            'pediatric-bvm': 'Pediatric BVM (500-700ml)',
            'infant-bvm': 'Infant BVM (250ml)',
            
            // Airway adjuncts
            'opa-large': 'Large OPA (Size 5-6)',
            'opa-medium': 'Medium OPA (Size 3-4)',
            'opa-small': 'Small OPA (Size 0-2)',
            'npa-large': 'Large NPA (Size 7-9)',
            'npa-medium': 'Medium NPA (Size 5-6)',
            'npa-small': 'Small NPA (Size 3-4.5)',
            
            // Ventilation techniques
            'one-person': 'One-Person BVM Technique',
            'two-person': 'Two-Person BVM Technique',
            'thenar-eminence': 'Thenar Eminence Technique'
        };
        
        return names[equipmentCode] || equipmentCode;
    }
    
    // Implement PDF export functionality
    const exportPdfButton = document.getElementById('export-pdf');
    if (exportPdfButton) {
        exportPdfButton.addEventListener('click', function() {
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="ti ti-printer"></i> Preparing PDF...';
            this.disabled = true;
            
            // Force any open items to display for the PDF
            document.querySelectorAll('.info-content').forEach(item => {
                item.style.display = 'block';
            });
            
            // Save original styles
            const sections = document.querySelectorAll('.checklist-section');
            const sectionHeaders = document.querySelectorAll('.section-header');
            const mainHeader = document.querySelector('.airway-header');
            
            // Store original styles
            const originalStyles = {
                sections: sections.length > 0 ? sections[0].style.cssText : '',
                sectionHeaders: sectionHeaders.length > 0 ? sectionHeaders[0].style.cssText : '',
                mainHeader: mainHeader ? mainHeader.style.cssText : ''
            };
            
            // Apply borders directly to elements
            sections.forEach(section => {
                section.style.border = '2px solid #106e9e';
                section.style.borderRadius = '10px';
                section.style.overflow = 'hidden';
                section.style.marginBottom = '20px';
            });
            
            sectionHeaders.forEach(header => {
                header.style.borderBottom = '1px solid #106e9e';
            });
            
            if (mainHeader) {
                mainHeader.style.border = '2px solid #106e9e';
                mainHeader.style.borderRadius = '6px';
                mainHeader.style.marginBottom = '15px';
            }
            
            // Temporarily hide the action buttons
            const actionsDiv = document.querySelector('.tool-actions');
            const actionsDisplayStyle = actionsDiv ? actionsDiv.style.display : 'block';
            if (actionsDiv) {
                actionsDiv.style.display = 'none';
            }
            
            // Create a style element for print-specific styles
            const printStyle = document.createElement('style');
            printStyle.type = 'text/css';
            printStyle.innerHTML = `
                @media print {
                    body * {
                        visibility: hidden;
                    }
                    .airway-tool-container, 
                    .airway-tool-container * {
                        visibility: visible !important;
                        display: block !important;
                    }
                    .airway-tool-container {
                        position: absolute;
                        left: 0;
                        top: 0;
                        width: 100%;
                        padding: 20px;
                    }
                    .tool-actions, 
                    .edit-selection-btn {
                        display: none !important;
                        visibility: hidden !important;
                    }
                    /* Force page break after first section */
                    #section-assessment {
                        page-break-after: always !important;
                    }
                    /* Force procedure section to start on a new page */
                    #section-procedure {
                        page-break-before: always !important;
                    }
                    .info-content {
                        display: block !important;
                    }
                    .checkbox-container .checkmark:after {
                        border-color: #000 !important;
                    }
                }
            `;
            document.head.appendChild(printStyle);
            
            setTimeout(() => {
                // Get the container to be exported as PDF
                const element = document.querySelector('.airway-tool-container');
                
                // Generate PDF with html2pdf library
                const opt = {
                    margin: 10,
                    filename: 'Basic_Airway_Management_Checklist.pdf',
                    image: { type: 'jpeg', quality: 1 },
                    html2canvas: { 
                        scale: 2,
                        useCORS: true,
                        logging: true,
                        letterRendering: true,
                        allowTaint: true,
                        scrollY: 0
                    },
                    pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
                    jsPDF: { 
                        unit: 'mm', 
                        format: 'a4', 
                        orientation: 'portrait',
                        compress: true
                    }
                };
                
                html2pdf().set(opt).from(element).save().then(() => {
                    // Restore button text
                    this.innerHTML = originalText;
                    this.disabled = false;
                    document.head.removeChild(printStyle);
                    
                    // Restore action buttons display
                    if (actionsDiv) {
                        actionsDiv.style.display = actionsDisplayStyle;
                    }
                    
                    // Restore original styles
                    sections.forEach(section => {
                        section.style.cssText = originalStyles.sections;
                    });
                    
                    sectionHeaders.forEach(header => {
                        header.style.cssText = originalStyles.sectionHeaders;
                    });
                    
                    if (mainHeader) {
                        mainHeader.style.cssText = originalStyles.mainHeader;
                    }
                    
                    // Reset info content display
                    document.querySelectorAll('.info-content').forEach(item => {
                        if (!item.classList.contains('always-visible')) {
                            item.style.display = '';
                        }
                    });
                }).catch(error => {
                    console.error('PDF generation error:', error);
                    alert('Error creating PDF. Please try again.');
                    this.innerHTML = originalText;
                    this.disabled = false;
                    document.head.removeChild(printStyle);
                    
                    // Restore action buttons display
                    if (actionsDiv) {
                        actionsDiv.style.display = actionsDisplayStyle;
                    }
                    
                    // Restore original styles
                    sections.forEach(section => {
                        section.style.cssText = originalStyles.sections;
                    });
                    
                    sectionHeaders.forEach(header => {
                        header.style.cssText = originalStyles.sectionHeaders;
                    });
                    
                    if (mainHeader) {
                        mainHeader.style.cssText = originalStyles.mainHeader;
                    }
                });
            }, 1000); // Increased timeout to ensure complete rendering
        });
    }
});
</script>