<?php
/**
 * I-Gel Airway Placement Tool - Optimized for Mobile/Touch
 * 
 * Place this file in: /tools/igel.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'I-Gel Placement Checklist';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="intubation-tool-container">
    <div class="intubation-header">
        <h1>Confirmation Checklist: <br>I-Gel Insertion.</h1>
    </div>
    
    <!-- Prepare Procedure Section -->
    <div class="checklist-section" id="section-prepare">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-list-check"></i>
                <h2>Preparation</h2>
            </div>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Verbalize the Patient's Clinical Indication for I-Gel:</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check1" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Cardiac arrest.</li>                    
                        <li>Failed or difficult endotracheal intubation.</li>
                        <li>Primary airway management when endotracheal intubation is not available.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Difficult Supraglottic Assessment: RODS</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check2" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li><strong>R</strong>: Restricted mouth opening (&lt;2.5cm may impede insertion).</li>
                        <li><strong>O</strong>: Obstruction of the upper airway.</li>
                        <li><strong>D</strong>: Disrupted or distorted airway anatomy.</li>
                        <li><strong>S</strong>: Stiff lungs or C-spine precautions.</li>
                        <li>Active vomiting, high aspiration risk, or known airway obstruction below glottis are relative contraindications.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Select Appropriate Size I-Gel.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check3" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Length Based Estimator for Pediatrics.</li>
                        <li>Adults are based off of ideal body weight.</li>
                    </ul>
                </div>
                <div class="selected-grade-display" id="selected-size-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-size-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Pre-Oxygenate: BVM with PEEP, High-flow oxygen, suction as necessary.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check4" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Ventilate with BVM at appropriate tidal volume and rate.</li>
                        <li>Non-rebreather mask or nasal cannula at high flow as alternative.</li>
                        <li>Decontaminate the airway with suction as necessary.</li>
                        <li>May forgo pre-oxygenation in cardiac arrest patients for immediate placement.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Prepare Equipment: I-Gel, lubricant, ETCO2, securement device.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check5" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Backup devices: different sizes of I-Gel, BVM with face mask, or ETT.</li>
                        <li>Verbalize failed I-Gel insertion plan.</li>
                        <li>Apply thin layer of water-based lubricant to front, back and sides of the cuff.</li>
                        <li>Check device integrity / pliability.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Insertion Procedure Section -->
    <div class="checklist-section" id="section-procedure">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-list-check"></i>
                <h2>Insertion</h2>
            </div>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Position Patient into "Sniffing Position".</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check7" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>'Sniffing position' to align oral, pharyngeal, and laryngeal axis.</li>
                        <li>Maintain C-spine precautions when indicated.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Insert Device.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check8" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Introduce along hard palate with continuous gentle pressure.</li>
                        <li>Advance until definitive resistance is felt.</li>
                        <li>Never force insertion.</li>
                        <li>If difficulty encountered, withdraw and retry with altered technique.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Confirm Placement.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check9" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Verify bilateral chest rise during ventilation.</li>
                        <li>Check for bilateral breath sounds.</li>
                        <li>Confirm absence of gastric insufflation sounds.</li>
                        <li>Verify positive ETCO2 waveform.</li>
                        <li>Ensure no audible leak during ventilation.</li>
                        <li>Confirm appropriate compliance with BVM ventilation.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Secure Device.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check10" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Secure using tape or commercial securing method</li>
                        <li>Use the integrated strap holes on the I-Gel</li>
                        <li>Confirm security before patient movement.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Post Airway Insertion Follow-Up:</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check11" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
  <li>Ensure continous ETCO2 monitoring.</li> 
  <li>Reconfirm placement / depth with every patient movement / every 15 minutes.</li>
  <li>Keep backup airways at the ready.</li>
    <li>If complications occur consider dislodgement, obstruction, pneumothorax, equipment failure.</li>
        <li>Insert OG tube in gastric port.</li>  
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="tool-actions">
        <div class="action-warning">
            <small><em>This is not intended to be a comprehensive guide for prehospital I-Gel airway management</em></small>
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

<!-- I-Gel Size Selector Modal -->
<div class="tool-modal" id="sizeChartModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>I-Gel Size Selection Guide</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-text">Select the appropriate I-Gel size based on patient weight:</p>
            
            <div class="size-options">
                <div class="size-option" data-size="1" data-weight="2-5kg" data-color="Pink">
                    <div class="size-details">
                        <h3>Size 1</h3>
                        <p><strong>Weight:</strong> 2-5 kg</p>
                        <p><strong>Patient Type:</strong> Neonate</p>
                        <p><strong>Color:</strong> Pink</p>
                    </div>
                    <input type="radio" name="igel-size" value="1" id="size-1">
                    <label for="size-1" class="size-label"></label>
                </div>
                
                <div class="size-option" data-size="1.5" data-weight="5-12kg" data-color="Light Blue">
                    <div class="size-details">
                        <h3>Size 1.5</h3>
                        <p><strong>Weight:</strong> 5-12 kg</p>
                        <p><strong>Patient Type:</strong> Infant</p>
                        <p><strong>Color:</strong> Light Blue</p>
                    </div>
                    <input type="radio" name="igel-size" value="1.5" id="size-1.5">
                    <label for="size-1.5" class="size-label"></label>
                </div>
                
                <div class="size-option" data-size="2" data-weight="10-25kg" data-color="Green">
                    <div class="size-details">
                        <h3>Size 2</h3>
                        <p><strong>Weight:</strong> 10-25 kg</p>
                        <p><strong>Patient Type:</strong> Small Pediatric</p>
                        <p><strong>Color:</strong> Green</p>
                    </div>
                    <input type="radio" name="igel-size" value="2" id="size-2">
                    <label for="size-2" class="size-label"></label>
                </div>
                
                <div class="size-option" data-size="2.5" data-weight="25-35kg" data-color="Orange">
                    <div class="size-details">
                        <h3>Size 2.5</h3>
                        <p><strong>Weight:</strong> 25-35 kg</p>
                        <p><strong>Patient Type:</strong> Large Pediatric</p>
                        <p><strong>Color:</strong> Orange</p>
                    </div>
                    <input type="radio" name="igel-size" value="2.5" id="size-2.5">
                    <label for="size-2.5" class="size-label"></label>
                </div>
                
                <div class="size-option" data-size="3" data-weight="30-60kg" data-color="Yellow">
                    <div class="size-details">
                        <h3>Size 3</h3>
                        <p><strong>Weight:</strong> 30-60 kg</p>
                        <p><strong>Patient Type:</strong> Small Adult</p>
                        <p><strong>Color:</strong> Yellow</p>
                    </div>
                    <input type="radio" name="igel-size" value="3" id="size-3">
                    <label for="size-3" class="size-label"></label>
                </div>
                
                <div class="size-option" data-size="4" data-weight="50-90kg" data-color="Green">
                    <div class="size-details">
                        <h3>Size 4</h3>
                        <p><strong>Weight:</strong> 50-90 kg</p>
                        <p><strong>Patient Type:</strong> Medium Adult</p>
                        <p><strong>Color:</strong> Green</p>
                    </div>
                    <input type="radio" name="igel-size" value="4" id="size-4">
                    <label for="size-4" class="size-label"></label>
                </div>
                
                <div class="size-option" data-size="5" data-weight="90kg+" data-color="Orange">
                    <div class="size-details">
                        <h3>Size 5</h3>
                        <p><strong>Weight:</strong> >90 kg</p>
                        <p><strong>Patient Type:</strong> Large Adult</p>
                        <p><strong>Color:</strong> Orange</p>
                    </div>
                    <input type="radio" name="igel-size" value="5" id="size-5">
                    <label for="size-5" class="size-label"></label>
                </div>
            </div>
            
            <div class="size-note">
                <p><strong>Note:</strong> For patients between sizes, consider clinical judgment. Factors like anatomy and body habitus may influence size selection. When in doubt between two sizes, choose the larger size for better seal.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-button save-button" id="save-igel-size">Save Selection</button>
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
                        <label for="sgaIndication">Primary Indication</label>
                        <select class="form-select" id="sgaIndication">
                            <option value="cardiac arrest">Cardiac Arrest</option>
                            <option value="failed intubation">Failed Intubation</option>
                            <option value="respiratory distress">Respiratory Distress</option>
                            <option value="airway protection">Airway Protection</option>
                            <option value="altered mental status">Decreased LOC</option>
                            <option value="procedural sedation">Procedural Sedation</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="deviceType">Device Type</label>
                        <select class="form-select" id="deviceType">
                            <option value="I-Gel" selected>I-Gel</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group" id="size-selector-container">
                        <!-- This will be populated with the selected size from the modal -->
                    </div>
                    <div class="form-group">
                        <label for="attempts">Number of Attempts</label>
                        <select class="form-select" id="attempts">
                            <option value="1" selected>1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="multiple">Multiple</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="gastricTube">Gastric Tube Placed</label>
                        <select class="form-select" id="gastricTube">
                            <option value="yes">Yes</option>
                            <option value="no" selected>No</option>
                            <option value="not applicable">Not Applicable</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ventilation">Ventilation Method</label>
                        <select class="form-select" id="ventilation">
                            <option value="BVM">BVM</option>
                            <option value="mechanical ventilator">Mechanical Ventilator</option>
                            <option value="spontaneous with supplemental O2">Spontaneous Breathing with O2</option>
                        </select>
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
                            <input type="checkbox" class="complication-check" id="comp-seal">
                            <span>Incomplete seal/air leak</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-placement">
                            <span>Difficult placement</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-ventilation">
                            <span>Inadequate ventilation</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-hypoxia">
                            <span>Transient hypoxia</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-vomiting">
                            <span>Vomiting/aspiration risk</span>
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
            <p>We've detected a previous I-Gel checklist session. Would you like to continue where you left off or start a new checklist?</p>
        </div>
        <div class="modal-footer session-buttons">
            <button class="action-button" id="new-session-btn">Start New</button>
            <button class="action-button primary-button" id="continue-session-btn">Continue Previous</button>
        </div>
    </div>
</div>

<!-- PDF Export Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
/* Reset and custom styles to override site defaults */
.intubation-tool-container * {
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.intubation-tool-container {
    max-width: 800px;
    margin: 0 auto 3rem;
    overflow: hidden;
    padding-bottom: 20px;
}

.intubation-header {
    background-color: #106e9e;
    color: white;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    border: 2px solid #106e9e;
    border-radius: 6px;
    margin-bottom: 15px;
}

.intubation-header h1 {
    margin: 0 0 10px;
    font-size: 28px;
    font-weight: 600;
}

.intubation-header p {
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

.section-status {
    background-color: #e9ecef;
    color: #495057;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 14px;
    font-weight: 500;
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
    padding: 5px 12px 8px 0; /* Reduced top padding */
    background-color: transparent;
    font-size: 13px;
    line-height: 1.5;
    color: #6c757d;
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

.info-content strong {
    color: #0c5578;
    font-weight: 600;
}

/* Add bullet list styling to match the intubation tool */
.info-content ul {
    margin: 0;
    padding-left: 20px;
    list-style-type: disc;
}

.info-content ul li {
    margin-bottom: 3px;
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

/* Display for selected medications and size */
.selected-meds-display, .selected-grade-display {
    margin-top: 5px;
    margin-left: 35px;
    padding: 5px 10px;
    background-color: #f0f6fa;
    border-radius: 5px;
    font-size: 14px;
    display: none;
    width: calc(100% - 80px);
}

.selected-meds-display.active, .selected-grade-display.active {
    display: block;
}

.selected-med-item, .selected-grade-item {
    display: flex;
    align-items: center;
    margin-bottom: 3px;
}

.selected-med-item i, .selected-grade-item i {
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
    border-left-color: #28a745;
}

.checklist-section.completed .section-header {
    background-color: rgba(40, 167, 69, 0.1);
}

/* Modal styles */
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

/* Size Selection Styling */
.size-options {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.size-option {
    border: 2px solid #dee2e6;
    border-radius: 10px;
    padding: 15px;
    position: relative;
    cursor: pointer;
    transition: all 0.2s;
}

.size-option:hover {
    border-color: #adb5bd;
    background-color: #f8f9fa;
}

.size-option.selected {
    border-color: #106e9e;
    background-color: #e3f2fd;
}

.size-details h3 {
    margin: 0 0 5px;
    font-size: 16px;
    font-weight: 600;
}

.size-details p {
    margin: 0;
    font-size: 14px;
    color: #6c757d;
}

.size-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.size-label {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    cursor: pointer;
}

/* Narrative Form Styling */
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

.form-select {
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    width: 100%;
    font-size: 16px;
    background-color: white;
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

/* Enhanced complication checkboxes */
#narrativeFormModal .comp-checkbox {
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

#narrativeFormModal .comp-checkbox:hover {
    background-color: #e9f2f9;
    border-color: #106e9e;
}

#narrativeFormModal .comp-checkbox input[type="checkbox"] {
    margin-right: 12px;
    width: 20px;
    height: 20px;
    cursor: pointer;
}

#narrativeFormModal .comp-checkbox span {
    font-size: 15px;
    padding: 2px 0;
}

#narrativeFormModal .comp-checkbox.complication-check-label {
    opacity: 0.5;
    pointer-events: none;
}

#narrativeFormModal .comp-checkbox.complication-check-label.enabled {
    opacity: 1;
    pointer-events: auto;
}

/* Make the entire comp-checkbox clickable */
#narrativeFormModal .comp-checkboxes {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 12px;
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
    
    .selected-meds-list {
        grid-template-columns: 1fr;
    }
    
    .checklist-item {
        padding: 15px;
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
        padding: 12px 20px;
        font-size: 16px;
    }
}

/* Print-specific styles for high-resolution printing */
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
    
    .intubation-tool-container {
        max-width: 100%;
        margin: 0;
        padding: 0;
    }
    
    .intubation-header {
        background-color: #f0f6fa !important;
        color: #000 !important;
        box-shadow: none;
        border-bottom: 1px solid #ccc;
        padding: 10px !important;
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
    .edit-selection-btn,
    .toggle-info-btn,
    .tool-modal {
        display: none !important;
    }
    
    /* Show selected medications/airway grade when printing */
    .selected-meds-display.active,
    .selected-grade-display.active {
        display: block !important;
        background-color: #fff !important;
        border: 1px dashed #ccc !important;
    }
    
    .selected-med-item i,
    .selected-grade-item i {
        color: #000 !important;
    }
    
    /* Set high resolution for printing */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Add custom page breaks to ensure content fits well */
    #section-procedure {
        page-break-before: always;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check for saved state
    checkForSavedSession();
    
    // Initialize the checklist
    initializeChecklist();
    
    // Set up modals
    setupModals();
    
    // Set up actions
    setupActions();
});

// Function to check if there's a saved session
function checkForSavedSession() {
    const savedData = localStorage.getItem('igelChecklist');
    
    if (savedData) {
        const continueSessionModal = document.getElementById('continueSessionModal');
        continueSessionModal.style.display = 'block';
        
        document.getElementById('continue-session-btn').addEventListener('click', function() {
            loadSavedSession(JSON.parse(savedData));
            continueSessionModal.style.display = 'none';
        });
        
        document.getElementById('new-session-btn').addEventListener('click', function() {
            localStorage.removeItem('igelChecklist');
            continueSessionModal.style.display = 'none';
        });
    }
}

// Function to load saved session
function loadSavedSession(data) {
    if (data.checkboxes) {
        data.checkboxes.forEach(item => {
            const checkbox = document.getElementById(item);
            if (checkbox) {
                checkbox.checked = true;
                const listItem = checkbox.closest('.checklist-item');
                if (listItem) {
                    listItem.classList.add('checked');
                }
            }
        });
    }
    
    if (data.selectedSize) {
        displaySelectedSize(data.selectedSize);
        document.getElementById('check3').checked = true;
    }
    
    // Update section completion status
    updateSectionStatus();
}

// Initialize checklist functionality
function initializeChecklist() {
    // Add event listeners to checkboxes
    const checkboxes = document.querySelectorAll('.checkbox-input');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const listItem = this.closest('.checklist-item');
            if (this.checked) {
                listItem.classList.add('checked');
                
                // Special handling for specific checkboxes
                if (this.id === 'check3') {
                    openSizeSelector();
                }
            } else {
                listItem.classList.remove('checked');
                
                // Clear displays if unchecked
                if (this.id === 'check3') {
                    clearSelectedSize();
                    document.getElementById('edit-size-btn').style.display = 'none';
                }
            }
            
            // Update section completion status
            updateSectionStatus();
            
            // Save state to localStorage
            saveCheckboxStates();
        });
    });
    
    // Make entire checklist item clickable
    document.querySelectorAll('.checklist-item').forEach(item => {
        item.addEventListener('click', function(e) {
            // Don't trigger if clicking on checkbox, edit button, or any dropdown
            if (!e.target.closest('.checkbox-container') && 
                !e.target.closest('.edit-selection-btn') && 
                !e.target.closest('button')) {
                
                // Find the checkbox within the item
                const checkbox = this.querySelector('.checkbox-input');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    
                    // Trigger the change event
                    const event = new Event('change');
                    checkbox.dispatchEvent(event);
                }
            }
        });
    });
    
    // Add event listeners to edit buttons
    document.getElementById('edit-size-btn').addEventListener('click', function(e) {
        e.stopPropagation();
        openSizeSelector();
    });
    
    // Update initial section status
    updateSectionStatus();
}

// Update section completion status
function updateSectionStatus() {
    const sections = ['prepare', 'procedure'];
    
    sections.forEach(section => {
        const sectionElement = document.getElementById(`section-${section}`);
        const checkboxes = document.querySelectorAll(`.checkbox-input[data-section="${section}"]`);
        const completedCheckboxes = document.querySelectorAll(`.checkbox-input[data-section="${section}"]:checked`);
        
        if (completedCheckboxes.length === checkboxes.length && checkboxes.length > 0) {
            sectionElement.classList.add('completed');
        } else {
            sectionElement.classList.remove('completed');
        }
    });
}

// Save checkbox states to localStorage
function saveCheckboxStates() {
    const checkedBoxes = Array.from(document.querySelectorAll('.checkbox-input:checked')).map(cb => cb.id);
    
    // Get selected size if it exists
    const sizeDisplay = document.getElementById('selected-size-display');
    const selectedSize = sizeDisplay.style.display !== 'none' ? {
        size: sizeDisplay.getAttribute('data-size'),
        weight: sizeDisplay.getAttribute('data-weight'),
        color: sizeDisplay.getAttribute('data-color')
    } : null;
    
    // Save to localStorage
    const state = {
        checkboxes: checkedBoxes,
        selectedSize: selectedSize
    };
    
    localStorage.setItem('igelChecklist', JSON.stringify(state));
}

// Set up modals
function setupModals() {
    // Close modal buttons
    document.querySelectorAll('.close-modal, .close-button').forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.tool-modal');
            modal.style.display = 'none';
        });
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('tool-modal')) {
            event.target.style.display = 'none';
        }
    });
    
    // Set up size selector modal
    setupSizeSelector();
    
    // Set up documentation modal
    setupDocumentationModal();
}

// Set up size selector modal
function setupSizeSelector() {
    const sizeOptions = document.querySelectorAll('.size-option');
    const saveButton = document.getElementById('save-igel-size');
    
    // Select size when clicking on an option
    sizeOptions.forEach(option => {
        option.addEventListener('click', function() {
            sizeOptions.forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });
    
    // Save button handler
    saveButton.addEventListener('click', function() {
        const selectedOption = document.querySelector('input[name="igel-size"]:checked');
        
        if (selectedOption) {
            const option = selectedOption.closest('.size-option');
            const size = option.getAttribute('data-size');
            const weight = option.getAttribute('data-weight');
            const color = option.getAttribute('data-color');
            
            displaySelectedSize({ size, weight, color });
            
            // Close the modal
            document.getElementById('sizeChartModal').style.display = 'none';
            
            // Save state
            saveCheckboxStates();
        } else {
            alert('Please select a size');
        }
    });
}

// Display selected size
function displaySelectedSize(sizeData) {
    const display = document.getElementById('selected-size-display');
    const editButton = document.getElementById('edit-size-btn');
    
    display.innerHTML = `<div class="selected-grade-item"><i class="ti ti-ruler"></i>I-Gel Size ${sizeData.size} (${sizeData.weight}), ${sizeData.color} connector</div>`;
    display.setAttribute('data-size', sizeData.size);
    display.setAttribute('data-weight', sizeData.weight);
    display.setAttribute('data-color', sizeData.color);
    
    display.style.display = 'block';
    editButton.style.display = 'block';
}

// Clear selected size
function clearSelectedSize() {
    const display = document.getElementById('selected-size-display');
    display.innerHTML = '';
    display.style.display = 'none';
    display.removeAttribute('data-size');
    display.removeAttribute('data-weight');
    display.removeAttribute('data-color');
}

// Open size selector modal
function openSizeSelector() {
    const modal = document.getElementById('sizeChartModal');
    modal.style.display = 'block';
    
    // Pre-select current size if one exists
    const display = document.getElementById('selected-size-display');
    const size = display.getAttribute('data-size');
    
    if (size) {
        const sizeOptions = document.querySelectorAll('.size-option');
        sizeOptions.forEach(option => {
            option.classList.remove('selected');
            if (option.getAttribute('data-size') === size) {
                option.classList.add('selected');
                const radio = option.querySelector('input[type="radio"]');
                radio.checked = true;
            }
        });
    }
}

// Set up documentation modal
function setupDocumentationModal() {
    const generateBtn = document.getElementById('generate-narrative');
    const narrativeBtn = document.getElementById('generateNarrativeBtn');
    const copyBtn = document.getElementById('copy-narrative');
    
    // Open documentation form
    generateBtn.addEventListener('click', function() {
        // Check if all essential steps are completed
        const essentialCheckboxes = [
            'check1', 'check3', 'check5',  // Preparation essentials
            'check8', 'check9', 'check10'  // Insertion essentials
        ];
        
        const allEssentialChecked = essentialCheckboxes.every(id => document.getElementById(id).checked);
        
        if (!allEssentialChecked) {
            const confirm = window.confirm('Not all essential steps are completed. Do you want to continue anyway?');
            if (!confirm) {
                return;
            }
        }
        
        // Populate the form with saved data
        populateDocumentationForm();
        
        // Show the form
        document.getElementById('narrativeFormModal').style.display = 'block';
    });
    
    // Generate narrative button
    narrativeBtn.addEventListener('click', function() {
        const narrative = generateNarrative();
        document.getElementById('narrative-text').innerHTML = narrative;
        
        // Close the form modal
        document.getElementById('narrativeFormModal').style.display = 'none';
        
        // Show the narrative modal
        document.getElementById('narrativeModal').style.display = 'block';
    });
    
    // Copy narrative button
    copyBtn.addEventListener('click', function() {
        const text = document.getElementById('narrative-text').textContent;
        
        navigator.clipboard.writeText(text).then(() => {
            this.innerHTML = '<i class="ti ti-check"></i> Copied!';
            setTimeout(() => {
                this.innerHTML = '<i class="ti ti-copy"></i> Copy to Clipboard';
            }, 2000);
        });
    });
    
    // Complication checkbox handlers
    document.getElementById('comp-none').addEventListener('change', function() {
        const compCheckboxes = document.querySelectorAll('.complication-check');
        const compLabels = document.querySelectorAll('.complication-check-label');
        
        if (this.checked) {
            compCheckboxes.forEach(cb => {
                cb.checked = false;
            });
            compLabels.forEach(label => {
                label.classList.remove('enabled');
            });
        } else {
            compLabels.forEach(label => {
                label.classList.add('enabled');
            });
        }
    });
    
    document.querySelectorAll('.complication-check').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('comp-none').checked = false;
                document.querySelectorAll('.complication-check-label').forEach(label => {
                    label.classList.add('enabled');
                });
            }
        });
    });
}

// Populate documentation form with saved data
function populateDocumentationForm() {
    // Get selected size if available
    const sizeDisplay = document.getElementById('selected-size-display');
    if (sizeDisplay.style.display !== 'none') {
        const size = sizeDisplay.getAttribute('data-size');
        document.getElementById('size-selector-container').innerHTML = `
            <label>Selected Size</label>
            <div class="form-control-static">I-Gel Size ${size} (${sizeDisplay.getAttribute('data-weight')})</div>
            <input type="hidden" name="size" value="${size}">
        `;
    } else {
        document.getElementById('size-selector-container').innerHTML = `
            <label for="size">I-Gel Size</label>
            <select class="form-select" id="size">
                <option value="3">Size 3 (Small Adult)</option>
                <option value="4" selected>Size 4 (Medium Adult)</option>
                <option value="5">Size 5 (Large Adult)</option>
                <option value="2">Size 2 (Pediatric)</option>
            </select>
        `;
    }
}

// Generate narrative based on form data
function generateNarrative() {
    const indication = document.getElementById('sgaIndication').value;
    const size = document.querySelector('#size-selector-container input[type="hidden"]')?.value || document.getElementById('size')?.value || '4';
    const attempts = document.getElementById('attempts').value;
    const gastricTube = document.getElementById('gastricTube').value;
    const ventilation = document.getElementById('ventilation').value;
    
    // Get complications
    const complications = [];
    if (!document.getElementById('comp-none').checked) {
        document.querySelectorAll('.complication-check:checked').forEach(checkbox => {
            complications.push(checkbox.parentElement.querySelector('span').textContent);
        });
    }
    
    // Build narrative
    let narrative = `<p><strong>I-Gel Airway Placement Documentation:</strong></p>`;
    
    narrative += `<p>Patient presented with ${indication} requiring advanced airway management. `;
    narrative += `After assessment and pre-oxygenation, a size ${size} I-Gel was selected as the appropriate device. `;
    narrative += `The I-Gel was prepared with water-soluble lubricant and inserted using proper technique with ${attempts} attempt(s). `;
    narrative += `Proper placement was confirmed via bilateral chest rise, bilateral breath sounds, absence of gastric sounds, and positive ETCO2 waveform. `;
    
    if (gastricTube === 'yes') {
        narrative += `A gastric tube was placed through the dedicated channel to decompress the stomach and reduce aspiration risk. `;
    }
    
    narrative += `The device was secured and ${ventilation} ventilation was established.</p>`;
    
    // Complications section
    if (complications.length > 0) {
        narrative += `<p><strong>Complications:</strong> ${complications.join(', ')}. Each was addressed appropriately.</p>`;
    } else {
        narrative += `<p><strong>Complications:</strong> No complications were encountered during the procedure. Patient tolerated the procedure well.</p>`;
    }
    
    return narrative;
}

// Reset checklist
function setupActions() {
    const resetButton = document.getElementById('reset-checklist');
    
    resetButton.addEventListener('click', function() {
        if (confirm('Are you sure you want to reset the entire checklist? All progress will be lost.')) {
            // Uncheck all checkboxes
            document.querySelectorAll('.checkbox-input').forEach(checkbox => {
                checkbox.checked = false;
                checkbox.closest('.checklist-item').classList.remove('checked');
            });
            
            // Clear displays
            clearSelectedSize();
            document.getElementById('edit-size-btn').style.display = 'none';
            
            // Clear localStorage
            localStorage.removeItem('igelChecklist');
            
            // Update section status
            updateSectionStatus();
        }
    });
    
    // Set up the PDF export feature
    const exportPdfButton = document.getElementById('export-pdf');
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
        const mainHeader = document.querySelector('.intubation-header');
        
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
                .intubation-tool-container, 
                .intubation-tool-container * {
                    visibility: visible !important;
                    display: block !important;
                }
                .intubation-tool-container {
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
                /* Force page break after Preparation section */
                #section-prepare {
                    page-break-after: always !important;
                }
                /* Force Insertion section to start on a new page */
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
            const element = document.querySelector('.intubation-tool-container');

            // Generate PDF with html2pdf library
            const opt = {
                margin: 10,
                filename: 'I-Gel_Airway_Checklist.pdf',
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
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>