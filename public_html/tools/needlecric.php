<?php
/**
 * Needle Cricothyrotomy Checklist Tool - Optimized for Mobile/Touch
 * 
 * Place this file in: /tools/needle-cric.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'Needle Cricothyrotomy Checklist';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="intubation-tool-container">
    <div class="intubation-header">
        <h1>Needle Cricothyrotomy Checklist</h1>
        <p>Use this interactive checklist to perform an emergency needle cricothyrotomy with jet ventilation.</p>
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
                        <span class="checkbox-label">Verify Indication for Needle Cricothyrotomy</span>
                        <input type="checkbox" class="checkbox-input" id="check1" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <p>Confirm "Can't Intubate, Can't Oxygenate" (CICO) situation in a patient with complete upper airway obstruction. Consider as a temporary measure until a definitive airway can be established, especially in pediatric patients.</p>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Identify Landmarks</span>
                        <input type="checkbox" class="checkbox-input" id="check2" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <p>Palpate key anatomical structures: Identify thyroid cartilage (Adam's apple), cricothyroid membrane (depression between thyroid and cricoid cartilages), and cricoid cartilage. The cricothyroid membrane is your target site.</p>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Position Patient</span>
                        <input type="checkbox" class="checkbox-input" id="check3" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <p>Place patient supine with neck in neutral or slight extension position (unless contraindicated by trauma). Clear access to anterior neck. Maintain cervical spine precautions if trauma is suspected.</p>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Prepare Equipment</span>
                        <input type="checkbox" class="checkbox-input" id="check4" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <p>Gather: 14G or larger IV catheter (or commercial cricothyrotomy kit), 5-10 mL syringe, 3.0 mm pediatric ETT connector, oxygen source, jet ventilation system or bag-valve device with adapter, antiseptic solution.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Procedure Section -->
    <div class="checklist-section" id="section-procedure">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-list-check"></i>
                <h2>Procedure</h2>
            </div>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Stabilize Larynx</span>
                        <input type="checkbox" class="checkbox-input" id="check5" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <p>Use non-dominant hand to stabilize the larynx by grasping the thyroid cartilage between thumb and middle finger. This prevents lateral movement and helps maintain orientation to midline during needle insertion.</p>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Perform Needle Cricothyrotomy</span>
                        <input type="checkbox" class="checkbox-input" id="check6" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <p>Cleanse site with antiseptic. Attach syringe to catheter. Insert needle through cricothyroid membrane at 90° angle to neck, aiming slightly caudally. Advance until air is aspirated (confirms entry into trachea). Advance catheter further while withdrawing needle. Remove syringe, leaving catheter in place.</p>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Connect Ventilation System</span>
                        <input type="checkbox" class="checkbox-input" id="check7" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <p>Attach 3.0 mm ETT connector to catheter hub. Connect to oxygen source via jet ventilation device or specialized adapter. Alternately, a 3-way stopcock can be used with BVM. Be prepared for oxygen to exit the upper airway if not completely obstructed.</p>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Initiate Jet Ventilation</span>
                        <input type="checkbox" class="checkbox-input" id="check8" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <p>Begin ventilation with 1-second jet of oxygen, followed by 4-second exhalation period (1:4 ratio). Allow adequate time for passive exhalation between breaths. Watch for chest rise and adequate exhalation. If using BVM with stopcock adapter, occlude open port to deliver breath, then open port for exhalation.</p>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Secure Catheter</span>
                        <input type="checkbox" class="checkbox-input" id="check9" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <p>Secure the catheter to the neck using tape or available securing device to prevent dislodgement. Minimize movement of the catheter to avoid tracheal trauma. Consider placing a gauze roll on either side of the catheter to stabilize it further.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="tool-actions">
        <div class="action-warning">
            <small><em>This is not intended to be a comprehensive guide for emergency needle cricothyrotomy. Needle cricothyrotomy provides only temporary oxygenation (30-45 minutes).</em></small>
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
                        <label for="needleCricIndication">Primary Indication</label>
                        <select class="form-select" id="needleCricIndication">
                            <option value="complete upper airway obstruction">Complete Upper Airway Obstruction</option>
                            <option value="facial trauma">Severe Facial Trauma</option>
                            <option value="angioedema">Angioedema/Airway Swelling</option>
                            <option value="failed intubation">Failed Intubation/Ventilation</option>
                            <option value="pediatric airway emergency">Pediatric Airway Emergency</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="catheterSize">Catheter Size</label>
                        <select class="form-select" id="catheterSize">
                            <option value="12G">12 Gauge</option>
                            <option value="14G" selected>14 Gauge</option>
                            <option value="16G">16 Gauge</option>
                            <option value="commercial kit">Commercial Kit</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="ventilationMethod">Ventilation Method</label>
                        <select class="form-select" id="ventilationMethod">
                            <option value="jet ventilator" selected>Jet Ventilator</option>
                            <option value="BVM with adapter">BVM with Adapter</option>
                            <option value="wall oxygen with adapter">Wall Oxygen with Adapter</option>
                            <option value="portable oxygen with adapter">Portable Oxygen with Adapter</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="attempts">Attempts Required</label>
                        <select class="form-select" id="attempts">
                            <option value="1" selected>1</option>
                            <option value="2">2</option>
                            <option value="3+">3+</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="priorAttempts">Prior Airway Attempts</label>
                        <select class="form-select" id="priorAttempts">
                            <option value="direct laryngoscopy">Direct Laryngoscopy</option>
                            <option value="video laryngoscopy">Video Laryngoscopy</option>
                            <option value="supraglottic device">Supraglottic Device</option>
                            <option value="multiple" selected>Multiple Methods</option>
                            <option value="none">None - Primary Needle Approach</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="definitiveAirway">Definitive Airway Plan</label>
                        <select class="form-select" id="definitiveAirway">
                            <option value="surgical cricothyrotomy" selected>Surgical Cricothyrotomy</option>
                            <option value="tracheostomy">Tracheostomy</option>
                            <option value="reattempt intubation">Reattempt Intubation</option>
                            <option value="ENT consultation">ENT Consultation</option>
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
                            <input type="checkbox" class="complication-check" id="comp-bleeding">
                            <span>Significant bleeding</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-catheter-dislodgement">
                            <span>Catheter dislodgement</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-false-passage">
                            <span>False passage creation</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-subcutaneous-emphysema">
                            <span>Subcutaneous emphysema</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-barotrauma">
                            <span>Barotrauma/pneumothorax</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-inadequate-ventilation">
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
            <p>We've detected a previous needle cricothyrotomy checklist session. Would you like to continue where you left off or start a new checklist?</p>
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
    // Main elements
    const checkboxes = document.querySelectorAll('.checkbox-input');
    let previousSessionExists = false;
    
    // Calculate total checkboxes per section
    const sections = ['prepare', 'procedure'];
    const sectionCounts = {};
    
    sections.forEach(section => {
        const sectionCheckboxes = document.querySelectorAll(`.checkbox-input[data-section="${section}"]`);
        sectionCounts[section] = sectionCheckboxes.length;
        
        // Update section counters
        const totalCountEl = document.querySelector(`#section-${section} .total-count`);
        if (totalCountEl) {
            totalCountEl.textContent = sectionCounts[section];
        }
    });
    
    // Function to update progress and section completion status
    function updateProgress() {
        // Update section completion status
        sections.forEach(section => {
            const sectionCheckboxes = document.querySelectorAll(`.checkbox-input[data-section="${section}"]`);
            const sectionCheckedCount = document.querySelectorAll(`.checkbox-input[data-section="${section}"]:checked`).length;
            const completedCountEl = document.querySelector(`#section-${section} .completed-count`);
            
            if (completedCountEl) {
                completedCountEl.textContent = sectionCheckedCount;
            }
            
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
                !e.target.closest('.edit-selection-btn') &&
                !e.target.closest('.toggle-info-btn')) {
                
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
        
        const saveData = {
            checkboxes: checkboxStates
        };
        
        localStorage.setItem('needleCricothyrotomyChecklist', JSON.stringify(saveData));
    }
    
    // Load checkbox states
    function loadCheckboxStates() {
        const savedData = localStorage.getItem('needleCricothyrotomyChecklist');
        if (savedData) {
            try {
                const data = JSON.parse(savedData);
                const checkboxStates = data.checkboxes || {};
                
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
                        localStorage.removeItem('needleCricothyrotomyChecklist');
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
        // Get patient info
        const indication = document.getElementById('needleCricIndication').value;
        const catheterSize = document.getElementById('catheterSize').value;
        const ventilationMethod = document.getElementById('ventilationMethod').value;
        const attempts = document.getElementById('attempts').value;
        const priorAttempts = document.getElementById('priorAttempts').value;
        const definitiveAirway = document.getElementById('definitiveAirway').value;
        
        // Get checked items from the checklist
        const checkedItems = {};
        checkboxes.forEach(checkbox => {
            checkedItems[checkbox.id] = checkbox.checked;
        });
        
        // Get complications
        const complications = [];
        if (!document.getElementById('comp-none').checked) {
            document.querySelectorAll('.complication-check:checked').forEach(checkbox => {
                complications.push(checkbox.id.replace('comp-', '').replace(/-/g, ' '));
            });
        }
        
        // Build the narrative
        let narrative = `Emergency needle cricothyrotomy was performed for ${indication}. `;
        
        // Prior attempts
        if (priorAttempts !== 'none') {
            narrative += `Prior to needle cricothyrotomy, ${priorAttempts} airway management was attempted without success. `;
        } else {
            narrative += `Primary needle cricothyrotomy approach was chosen due to clinical urgency. `;
        }
        
        // Preparation
        if (checkedItems.check1) {
            narrative += `"Can't Intubate, Can't Oxygenate" (CICO) situation was confirmed. `;
        }
        
        if (checkedItems.check2) {
            narrative += `Anterior neck landmarks were identified with the cricothyroid membrane as the target. `;
        }
        
        if (checkedItems.check3) {
            narrative += `Patient was positioned appropriately. `;
        }
        
        if (checkedItems.check4) {
            narrative += `All necessary equipment was prepared. `;
        }
        
        // Procedure
        if (checkedItems.check5) {
            narrative += `Larynx was stabilized with non-dominant hand. `;
        }
        
        if (checkedItems.check6) {
            narrative += `A ${catheterSize} catheter was inserted through the cricothyroid membrane after cleansing the site. `;
            
            if (attempts !== '1') {
                narrative += `This was achieved after ${attempts} attempts. `;
            }
        }
        
        if (checkedItems.check7) {
            narrative += `Ventilation system was connected with ETT connector adapter. `;
        }
        
        if (checkedItems.check8) {
            narrative += `${ventilationMethod} was used to provide oxygenation with 1:4 ratio of insufflation to exhalation. `;
        }
        
        if (checkedItems.check9) {
            narrative += `The catheter was secured in place. `;
        }
        
        narrative += `Plan for ${definitiveAirway} as definitive airway was established. `;
        
        // Complications
        if (complications.length > 0) {
            narrative += 'Complications encountered during procedure: ';
            complications.forEach((comp, index) => {
                if (index > 0) {
                    narrative += (index === complications.length - 1) ? ' and ' : ', ';
                }
                narrative += comp;
            });
            narrative += '. ';
        } else {
            narrative += 'No complications were encountered during the procedure. ';
        }
        
        narrative += `This procedure is intended as a temporary measure (30-45 minutes) until a definitive airway can be established.`;
        
        return narrative;
    }
    
    // Initialize: Load saved states when page loads
    loadCheckboxStates();
    
    // Initialize complication checkboxes
    complicationLabels.forEach(label => {
        label.classList.remove('enabled');
    });
    
    // PDF Export functionality
    document.getElementById('export-pdf').addEventListener('click', function() {
        // Show loading indicator
        const originalText = this.innerHTML;
        this.innerHTML = '<i class="ti ti-printer"></i> Preparing PDF...';
        this.disabled = true;
        
        // Create a style element for print-specific CSS
        const printStyle = document.createElement('style');
        printStyle.type = 'text/css';
        printStyle.media = 'print';
        printStyle.textContent = `
            @page {
                size: letter portrait;
                margin: 0.5cm;
            }
            
            @media print {
                body * {
                    visibility: hidden;
                }
                
                .intubation-tool-container, 
                .intubation-tool-container * {
                    visibility: visible;
                }
                
                .intubation-tool-container {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    padding: 10px;
                }
                
                /* Hide buttons and interactive elements */
                .tool-actions,
                .edit-selection-btn,
                .toggle-info-btn {
                    display: none !important;
                }
                
                /* Make sure info content is visible */
                .info-content {
                    display: block !important;
                    background-color: #f8f9fa !important;
                    margin-left: 30px;
                    padding: 10px;
                    border-left: 3px solid #106e9e;
                }
                
                /* Ensure all sections start on a new page */
                #section-procedure {
                    page-break-before: always;
                }
                
                /* Prevent unwanted page breaks */
                .checklist-item {
                    page-break-inside: avoid;
                }
                
                /* Improve checkbox visibility for print */
                .checkmark {
                    border: 2px solid black !important;
                    background-color: white !important;
                }
                
                .checkbox-container input:checked ~ .checkmark {
                    background-color: white !important;
                }
                
                .checkbox-container input:checked ~ .checkmark:after {
                    border-color: black !important;
                }
            }
        `;
        
        // Append the print style to the head
        document.head.appendChild(printStyle);
        
        // Reset the button
        setTimeout(() => {
            const button = document.getElementById('export-pdf');
            button.innerHTML = originalText;
            button.disabled = false;
            
            // Trigger the print dialog
            window.print();
            
            // Remove the print style after a delay
            setTimeout(() => {
                document.head.removeChild(printStyle);
            }, 1000);
        }, 500);
    });
});
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>