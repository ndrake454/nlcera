<?php
/**
 * CPAP Setup Checklist Tool - Optimized for Mobile/Touch
 * 
 * Place this file in: /tools/cpap.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'CPAP Setup Checklist';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="intubation-tool-container">
    <div class="intubation-header">
        <h1>Confirmation Checklist: <br> CPAP</h1>
    </div>
    
    <!-- Preparation Section -->
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
                        <span class="checkbox-label"><strong>Verify indications and rule out contraindications</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check1" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Check for respiratory distress with increased work of breathing.</li>
                        <li>Assess for hypoxemia despite conventional oxygen therapy.</li>
                        <li>Rule out contraindications: Hypotension, severe altered mental status, facial trauma, pneumothorax.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Assemble required equipment</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check2" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>CPAP device with O2 source and appropriate-sized mask.</li>
                        <li>Tubing, connectors, and headgear/straps.</li>
                        <li>Monitoring equipment (SpO2, BP, etc.)</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Explain the procedure to the patient.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check3" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Explain procedure, purpose and expected sensation.</li>
                        <li>Reassure about ability to communicate while wearing mask.</li>
                        <li>Address any concerns or anxiety.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Determine initial settings</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check4" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Set PEEP at 5 cmH2O.</li>
                    </ul>
                </div>
                <div class="selected-settings-display" id="selected-settings-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-settings-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Position patient appropriately</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check5" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Elevate head of bed to 30-60 degrees.</li>
                        <li>Ensure proper head alignment for optimal airway patency.</li>
                        <li>Adjust for patient comfort while maintaining position.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Application Section -->
    <div class="checklist-section" id="section-application">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-list-check"></i>
                <h2>Application & Monitoring</h2>
            </div>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Fit mask and establish seal</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check6" data-section="application">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Position mask over nose and mouth using two-hand technique.</li>
                        <li>Secure straps snugly but not excessively tight.</li>
                        <li>Check for appropriate sizing, leaks, and comfortable fit.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Activate system and verify function</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check7" data-section="application">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Turn on device and confirm airflow and pressure at set levels.</li>
                        <li>Check for and address any air leaks around mask.</li>
                        <li>Ensure oxygen flow is adequate for target FiO2.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Evaluate initial response</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check8" data-section="application">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Monitor SpO2, respiratory rate, and work of breathing.</li>
                        <li>Assess for reduced accessory muscle use.</li>
                        <li>Check patient comfort and tolerance.</li>
                    </ul>
                </div>
            </div>
            
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Establish ongoing monitoring plan</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check9" data-section="application">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Set vital sign and assessment intervals (q15-30min initially).</li>
                        <li>Define criteria for escalation of care.</li>
                        <li>Monitor for complications (hypotension).</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Maintain patient communication</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check10" data-section="application">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Check comfort regularly and address concerns promptly.</li>
                        <li>Establish reliable communication method.</li>
                        <li>Explain ongoing care plan and anticipated duration.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="tool-actions">
        <div class="action-warning">
            <small><em>This is not intended to be a comprehensive guide for CPAP management</em></small>
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

<!-- CPAP Settings Modal -->
<div class="tool-modal" id="cpapSettingsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>CPAP Settings</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="settings-form">
                <div class="form-group">
                    <label for="peep-setting">PEEP Level</label>
                    <div class="range-value-display">
                        <input type="number" id="peep-setting" value="5" class="number-input" readonly>
                        <span>cmH2O</span>
                    </div>
                    <p class="setting-note">Fixed at 5 cmH2O</p>
                </div>
                
                <div class="form-group">
                    <label for="fio2-setting">FiO2</label>
                    <div class="range-value-display">
                        <input type="number" id="fio2-setting" value="100" class="number-input" readonly>
                        <span>%</span>
                    </div>
                    <p class="setting-note">Fixed at 100%</p>
                </div>
                
                <div class="form-group">
                    <label for="mask-size">Mask Size/Type</label>
                    <select id="mask-size" class="form-select">
                        <option value="small">Small</option>
                        <option value="medium" selected>Medium</option>
                        <option value="large">Large</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-button primary-button" id="save-settings">Save Settings</button>
        </div>
    </div>
</div>

<!-- Documentation Form Modal -->
<div class="tool-modal" id="narrativeFormModal">
    <div class="modal-content large-modal">
        <div class="modal-header">
            <h2>Enter CPAP Details</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="patientInfoForm" class="narrative-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="cpapIndication">Primary Indication</label>
                        <select class="form-select" id="cpapIndication">
                            <option value="respiratory distress">Respiratory Distress</option>
                            <option value="pulmonary edema">Pulmonary Edema</option>
                            <option value="copd exacerbation">COPD Exacerbation</option>
                            <option value="hypoxemia">Persistent Hypoxemia</option>
                            <option value="covid pneumonia">COVID Pneumonia</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="initialOxygen">Initial Oxygen Level</label>
                        <div class="input-group">
                            <input type="number" id="initialOxygen" class="form-input" value="88" min="50" max="100">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="initialSettings">Initial CPAP Settings</label>
                        <div id="initialSettings" class="settings-display">
                            <!-- This will be populated with the selected settings -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="finalSettings">Final CPAP Settings</label>
                        <div class="settings-input-row">
                            <div class="settings-input-group">
                                <label for="finalPeep">PEEP</label>
                                <div class="input-group small">
                                    <input type="number" id="finalPeep" class="form-input" value="5" min="5" max="5" step="0" readonly>
                                    <span class="input-group-text">cmH2O</span>
                                </div>
                            </div>
                            <div class="settings-input-group">
                                <label for="finalFio2">FiO2</label>
                                <div class="input-group small">
                                    <input type="number" id="finalFio2" class="form-input" value="100" min="100" max="100" readonly>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="finalOxygen">Final Oxygen Level</label>
                        <div class="input-group">
                            <input type="number" id="finalOxygen" class="form-input" value="95" min="50" max="100">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="respiratoryRate">Respiratory Rate</label>
                        <div class="settings-input-row">
                            <div class="settings-input-group">
                                <label for="initialRR">Initial</label>
                                <div class="input-group small">
                                    <input type="number" id="initialRR" class="form-input" value="28" min="8" max="60">
                                </div>
                            </div>
                            <div class="settings-input-group">
                                <label for="finalRR">Final</label>
                                <div class="input-group small">
                                    <input type="number" id="finalRR" class="form-input" value="20" min="8" max="60">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Complications/Issues</h3>
                    <div class="comp-checkboxes">
                        <label class="comp-checkbox">
                            <input type="checkbox" id="comp-none" checked>
                            <span>No complications</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-mask-leak">
                            <span>Mask leak</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-claustrophobia">
                            <span>Claustrophobia/anxiety</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-skin-irritation">
                            <span>Skin irritation</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-gastric-distention">
                            <span>Gastric distention</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-hypotension">
                            <span>Hypotension</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-section">
                    <h3>Disposition</h3>
                    <div class="radio-group horizontal">
                        <label class="radio-option">
                            <input type="radio" name="disposition" value="continued" checked>
                            <span>CPAP Continued</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="disposition" value="weaned">
                            <span>Weaned to O2</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="disposition" value="intubated">
                            <span>Escalated to Intubation</span>
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
            <p>We've detected a previous CPAP checklist session. Would you like to continue where you left off or start a new checklist?</p>
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
    text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.7); /* Add subtle text shadow for better readability */
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

/* Display for selected settings */
.selected-settings-display {
    margin-top: 5px;
    margin-left: 35px;
    padding: 5px 10px;
    background-color: #f0f6fa;
    border-radius: 5px;
    font-size: 14px;
    display: none;
    width: calc(100% - 80px);
}

.selected-settings-display.active {
    display: block;
}

.selected-setting-item {
    display: flex;
    align-items: center;
    margin-bottom: 3px;
}

.selected-setting-item i {
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

/* Settings Modal Styles */
.settings-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-weight: 500;
    margin-bottom: 8px;
}

.range-input-container {
    display: flex;
    align-items: center;
    gap: 15px;
}

.range-input {
    flex: 1;
    height: 6px;
    -webkit-appearance: none;
    appearance: none;
    background: #dee2e6;
    border-radius: 3px;
    outline: none;
}

.range-input::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #106e9e;
    cursor: pointer;
    border: none;
}

.range-input::-moz-range-thumb {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #106e9e;
    cursor: pointer;
    border: none;
}

.range-value-display {
    display: flex;
    align-items: center;
    gap: 5px;
    min-width: 80px;
}

.number-input {
    width: 50px;
    padding: 6px;
    border: 1px solid #ced4da;
    border-radius: 4px;
    text-align: center;
}

.range-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 5px;
    color: #6c757d;
    font-size: 12px;
}

.radio-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.radio-group.horizontal {
    flex-direction: row;
    flex-wrap: wrap;
    gap: 15px;
}

.radio-option {
    display: flex;
    align-items: center;
    cursor: pointer;
}

.radio-option input {
    margin-right: 8px;
}

.form-select {
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    width: 100%;
    font-size: 16px;
    background-color: white;
}

/* Form Styling for Documentation */
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

.form-input {
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    width: 100%;
    font-size: 16px;
}

.input-group {
    display: flex;
    align-items: stretch;
}

.input-group .form-input {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    flex: 1;
}

.input-group-text {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 10px;
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-left: none;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
}

.settings-display {
    padding: 10px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    font-size: 14px;
}

.settings-input-row {
    display: flex;
    gap: 15px;
}

.settings-input-group {
    flex: 1;
}

.settings-input-group label {
    font-size: 13px;
    margin-bottom: 4px;
}

.input-group.small {
    max-width: 120px;
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
    margin-right: 12px;
    width: 20px;
    height: 20px;
    cursor: pointer;
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

/* Make the entire comp-checkbox clickable */
.comp-checkboxes {
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
    
    .checklist-item {
        padding: 15px;
    }
    
    .settings-input-row {
        flex-direction: column;
        gap: 10px;
    }
    
    .input-group.small {
        max-width: 100%;
    }
    
    .radio-group.horizontal {
        flex-direction: column;
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
        background: repeating-linear-gradient(
            45deg,
            #e3f2fd,
            #e3f2fd 10px,
            #bbdefb 10px,
            #bbdefb 20px
        ) !important;
        color: #0d47a1 !important;
        border: 2px solid #2196f3 !important;
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
    .edit-selection-btn,
    .toggle-info-btn,
    .tool-modal {
        display: none !important;
    }
    
    /* Show selected settings when printing */
    .selected-settings-display.active {
        display: block !important;
        background-color: #fff !important;
        border: 1px dashed #ccc !important;
    }
    
    .selected-setting-item i {
        color: #000 !important;
    }
    
    /* Set high resolution for printing */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Add custom page breaks to ensure content fits well */
    #section-application {
        page-break-before: always;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Main elements
    const checkboxes = document.querySelectorAll('.checkbox-input');
    const settingsCheckbox = document.getElementById('check4');
    let cpapSettings = {
        peep: 5,
        fio2: 100,
        maskSize: 'medium'
    };
    let previousSessionExists = false;
    
    // Calculate total checkboxes per section
    const sections = ['prepare', 'application'];
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
                        
                        // Special handlers for certain checkboxes
                        if (checkbox === settingsCheckbox) {
                            showCpapSettingsModal();
                        }
                    } else {
                        this.classList.remove('checked');
                        
                        // Clear display if unchecked
                        if (checkbox === settingsCheckbox) {
                            document.getElementById('edit-settings-btn').style.display = 'none';
                            document.getElementById('selected-settings-display').classList.remove('active');
                        }
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
                
                // Special handlers for certain checkboxes
                if (this === settingsCheckbox) {
                    showCpapSettingsModal();
                }
            } else {
                listItem.classList.remove('checked');
                
                // Clear display if unchecked
                if (this === settingsCheckbox) {
                    document.getElementById('edit-settings-btn').style.display = 'none';
                    document.getElementById('selected-settings-display').classList.remove('active');
                }
            }
            
            updateProgress();
        });
    });
    
    // Add click handlers for edit buttons
    document.getElementById('edit-settings-btn').addEventListener('click', function() {
        showCpapSettingsModal();
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
    
    // CPAP Settings Modal
    function showCpapSettingsModal() {
        // Set current values in the modal
        document.getElementById('peep-setting').value = 5; // Always fixed at 5 cmH2O
        document.getElementById('fio2-setting').value = 100; // Fixed at 100%
        
        // Set mask size
        document.getElementById('mask-size').value = cpapSettings.maskSize;
        
        // Show modal
        openModal('cpapSettingsModal');
    }
    
    // Save CPAP settings
    document.getElementById('save-settings').addEventListener('click', function() {
        cpapSettings.peep = 5; // Fixed at 5 cmH2O
        cpapSettings.fio2 = 100; // Fixed at 100%
        cpapSettings.maskSize = document.getElementById('mask-size').value;
        
        updateSettingsDisplay();
        closeModal('cpapSettingsModal');
        
        // Update form for documentation
        updateDocumentationSettings();
    });
    
    function updateSettingsDisplay() {
        const display = document.getElementById('selected-settings-display');
        let maskSizeText = cpapSettings.maskSize.charAt(0).toUpperCase() + cpapSettings.maskSize.slice(1);
        
        let html = `
            <div class="selected-setting-item"><i class="ti ti-gauge"></i>PEEP: 5 cmH2O (fixed)</div>
            <div class="selected-setting-item"><i class="ti ti-gas-cylinder"></i>FiO2: 100% (fixed)</div>
            <div class="selected-setting-item"><i class="ti ti-mask"></i>Mask Size: ${maskSizeText}</div>
        `;
        
        display.innerHTML = html;
        display.classList.add('active');
        document.getElementById('edit-settings-btn').style.display = 'block';
    }
    
    // Update documentation form with CPAP settings
    function updateDocumentationSettings() {
        const initialSettings = document.getElementById('initialSettings');
        
        initialSettings.innerHTML = `
            <div class="settings-input-row">
                <div class="settings-display-item">PEEP: 5 cmH2O (fixed)</div>
                <div class="settings-display-item">FiO2: 100% (fixed)</div>
            </div>
        `;
        
        // Final settings are also fixed
        document.getElementById('finalPeep').value = 5;
        document.getElementById('finalFio2').value = 100;
    }
    
    // Reset checklist button
    const resetButton = document.getElementById('reset-checklist');
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to reset the entire checklist?')) {
                resetAllCheckboxes();
                
                // Reset CPAP settings
                cpapSettings = {
                    peep: 5,
                    fio2: 50,
                    maskSize: 'medium'
                };
                
                // Hide settings display
                document.getElementById('selected-settings-display').classList.remove('active');
                document.getElementById('edit-settings-btn').style.display = 'none';
                
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
            // Prepare the documentation form with saved data
            updateDocumentationSettings();
            
            // Show the form to gather patient information
            openModal('narrativeFormModal');
        });
    }
    
    // Handle "No complications" checkbox logic
    const noComplicationsCheckbox = document.getElementById('comp-none');
    const complicationCheckboxes = document.querySelectorAll('.complication-check');
    const complicationLabels = document.querySelectorAll('.complication-check-label');
    
    if (noComplicationsCheckbox) {
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
        
        // Make the entire complication checkbox label clickable
        document.querySelectorAll('#narrativeFormModal .comp-checkbox').forEach(checkboxLabel => {
            checkboxLabel.addEventListener('click', function(e) {
                // Prevent default to stop the normal checkbox behavior initially
                if (e.target !== this.querySelector('input[type="checkbox"]')) {
                    e.preventDefault();
                    
                    // Get the checkbox and toggle it
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                        
                        // Manually trigger the change event
                        const event = new Event('change');
                        checkbox.dispatchEvent(event);
                    }
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
        
        // Save data
        const saveData = {
            checkboxes: checkboxStates,
            cpapSettings: cpapSettings
        };
        
        localStorage.setItem('cpapChecklist', JSON.stringify(saveData));
    }
    
    // Load checkbox states
    function loadCheckboxStates() {
        const savedData = localStorage.getItem('cpapChecklist');
        if (savedData) {
            try {
                const data = JSON.parse(savedData);
                const checkboxStates = data.checkboxes || {};
                
                // Load saved CPAP settings if available
                if (data.cpapSettings) {
                    cpapSettings = data.cpapSettings;
                }
                
                // Check if any checkboxes were saved as checked
                const anyChecked = Object.values(checkboxStates).some(state => state === true);
                previousSessionExists = anyChecked;
                
                if (anyChecked) {
                    // Show continue session modal
                    openModal('continueSessionModal');
                }
            } catch (error) {
                console.error('Error parsing saved data:', error);
                localStorage.removeItem('cpapChecklist');
            }
        }
    }
    
    // Handle previous session
    function resetAllCheckboxes() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
            
            // Update visual state
            const listItem = checkbox.closest('.checklist-item');
            if (listItem) {
                listItem.classList.remove('checked');
            }
        });
        
        // Update progress
        updateProgress();
        
        // Clear localStorage
        localStorage.removeItem('cpapChecklist');
    }
    
    // Event handlers for session management
    document.getElementById('continue-session-btn').addEventListener('click', function() {
        const savedData = localStorage.getItem('cpapChecklist');
        if (savedData) {
            try {
                const data = JSON.parse(savedData);
                const checkboxStates = data.checkboxes || {};
                
                // Apply saved checkbox states
                Object.keys(checkboxStates).forEach(id => {
                    const checkbox = document.getElementById(id);
                    if (checkbox) {
                        checkbox.checked = checkboxStates[id];
                        
                        // Update visual state
                        const listItem = checkbox.closest('.checklist-item');
                        if (listItem && checkbox.checked) {
                            listItem.classList.add('checked');
                        }
                    }
                });
                
                // Apply saved CPAP settings
                if (data.cpapSettings) {
                    cpapSettings = data.cpapSettings;
                    
                    // Update settings display if settings checkbox is checked
                    if (settingsCheckbox.checked) {
                        updateSettingsDisplay();
                    }
                }
                
                // Update progress
                updateProgress();
                
                // Close modal
                closeModal('continueSessionModal');
            } catch (error) {
                console.error('Error loading saved session:', error);
                resetAllCheckboxes();
            }
        }
    });
    
    document.getElementById('new-session-btn').addEventListener('click', function() {
        resetAllCheckboxes();
        closeModal('continueSessionModal');
    });
    
    // Generate narrative based on form inputs
    function generateNarrative() {
        // Get form values
        const indication = document.getElementById('cpapIndication').value;
        const initialOxygen = document.getElementById('initialOxygen').value;
        const finalOxygen = document.getElementById('finalOxygen').value;
        const initialRR = document.getElementById('initialRR').value;
        const finalRR = document.getElementById('finalRR').value;
        const finalPeep = 5; // Fixed at 5 cmH2O - not adjustable
        const finalFio2 = 100; // Fixed at 100% - not adjustable
        
        // Get disposition
        const disposition = document.querySelector('input[name="disposition"]:checked').value;
        let dispositionText = '';
        switch(disposition) {
            case 'continued':
                dispositionText = 'CPAP therapy was continued';
                break;
            case 'weaned':
                dispositionText = 'The patient was successfully weaned to conventional oxygen therapy';
                break;
            case 'intubated':
                dispositionText = 'Due to worsening respiratory status, the patient was escalated to endotracheal intubation';
                break;
        }
        
        // Get complications
        const noComplications = document.getElementById('comp-none').checked;
        let complicationsText = '';
        
        if (noComplications) {
            complicationsText = 'No complications were observed during CPAP therapy.';
        } else {
            const complications = [];
            
            if (document.getElementById('comp-mask-leak').checked) {
                complications.push('mask leak requiring adjustment');
            }
            if (document.getElementById('comp-claustrophobia').checked) {
                complications.push('claustrophobia/anxiety requiring reassurance');
            }
            if (document.getElementById('comp-skin-irritation').checked) {
                complications.push('facial skin irritation requiring mask padding');
            }
            if (document.getElementById('comp-gastric-distention').checked) {
                complications.push('gastric distention');
            }
            if (document.getElementById('comp-hypotension').checked) {
                complications.push('transient hypotension');
            }
            
            if (complications.length > 0) {
                // Format complications with proper grammar
                if (complications.length === 1) {
                    complicationsText = `The patient experienced ${complications[0]}.`;
                } else if (complications.length === 2) {
                    complicationsText = `The patient experienced ${complications[0]} and ${complications[1]}.`;
                } else {
                    const lastComplication = complications.pop();
                    complicationsText = `The patient experienced ${complications.join(', ')}, and ${lastComplication}.`;
                }
            } else {
                complicationsText = 'No complications were observed during CPAP therapy.';
            }
        }
        
        // Build the narrative
        const maskSizeText = cpapSettings.maskSize.charAt(0).toUpperCase() + cpapSettings.maskSize.slice(1);
        let narrative = `CPAP Application Documentation

Patient Presentation:
Patient presented with ${indication} and was noted to have an SpO2 of ${initialOxygen}% on room air with a respiratory rate of ${initialRR}. After assessment, CPAP therapy was initiated.

Initial Settings:
- PEEP: 5 cmH2O
- FiO2: 100%
- Mask Size: ${maskSizeText}

Patient was placed in an upright position and the CPAP mask was properly fitted and secured. Pressure was confirmed at 5 cmH2O and oxygen flow was set to provide 100% FiO2.

Response to Therapy:
After CPAP application, patient's work of breathing showed improvement with respiratory rate decreasing from ${initialRR} to ${finalRR}. Oxygen saturation improved from initial ${initialOxygen}% to ${finalOxygen}%.

Settings were maintained at PEEP 5 cmH2O and FiO2 100% throughout therapy.

Complications:
${complicationsText}

Monitoring and Ongoing Care:
Continuous vital sign monitoring was established with SpO2 and respiratory assessment every 15-30 minutes initially, then hourly as patient stabilized. The patient was educated on the importance of maintaining the mask seal and a communication method was established.

Disposition:
${dispositionText}.

This documentation is based on the CPAP setup checklist tool and does not represent a complete clinical record.`;

        return narrative;
    }
    
    // PDF Export Function - Improved version based on intubation tool
    document.getElementById('export-pdf').addEventListener('click', function() {
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
            mainHeader.style.border = '5px solid #2196f3';
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
                /* Force Application section to start on a new page */
                #section-application {
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

            // Add a timestamp to the PDF
            const timestamp = document.createElement('div');
            timestamp.classList.add('pdf-timestamp');
            timestamp.innerHTML = `<p>Generated: ${new Date().toLocaleString()}</p>`;
            element.insertBefore(timestamp, element.firstChild);

            // Generate PDF with html2pdf library
            const opt = {
                margin: 10,
                filename: 'CPAP_Setup_Checklist.pdf',
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
                
                // Remove timestamp
                if (element.contains(timestamp)) {
                    element.removeChild(timestamp);
                }
                
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
                
                // Remove timestamp
                if (element.contains(timestamp)) {
                    element.removeChild(timestamp);
                }
                
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
    
    // Check for previous session on page load
    function checkPreviousSession() {
        if (previousSessionExists) {
            openModal('continueSessionModal');
        }
    }
    
    // Initialize the page
    function init() {
        // Load checkbox states
        loadCheckboxStates();
        
        // Check for previous session
        setTimeout(checkPreviousSession, 500);
    }
    
    // Start initialization
    init();
});
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>