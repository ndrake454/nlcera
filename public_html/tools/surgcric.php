<?php
/**
 * Surgical Cricothyrotomy Checklist Tool - Optimized for Mobile/Touch
 * 
 * Place this file in: /tools/cricothyrotomy.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'Surgical Cricothyrotomy Checklist';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="intubation-tool-container">
    <div class="intubation-header">
        <h1>Procedural Checklist: <br>Surgical Cricothyrotomy</h1>
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
                        <span class="checkbox-label"><strong>Verbalize Patient's Clinical Indication for Surgical Airway:</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check1" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>"Can't Intubate, Can't Oxygenate" (CICO) situation.</li>
                        <li>Failed oxygenation via face mask.</li>
                        <li>Failed intubation after at least 2-3 attempts.</li>
                        <li>Failed supraglottic airway placement.</li>
                        <li>Hypoxia with anatomical distortion preventing usual approaches.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Identify Landmarks: Cricothyroid Membrane.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check2" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Palpate thyroid cartilage (Adam's apple).</li>
                        <li>Identify cricothyroid membrane (depression between thyroid and cricoid cartilages).</li>
                        <li>Locate cricoid cartilage (first ring of trachea).</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Position Patient in Sniffing Position.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check3" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Place patient supine.</li>
                        <li>Position neck in neutral or slight extension (unless contraindicated by trauma).</li>
                        <li>Remove any clothing or objects obstructing access to anterior neck.</li>
                        <li>Consider padding underneath shoulder for optimal position.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Prepare Equipment: Scalpel, 6.0 tube, Bougie, Alcohol swab, Gauze, Tape, and ETCO2.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check4" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Ensure all equipment is out and readilby available.</li>

                    </ul>
                </div>
            </div>

                        <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Stabilize Larynx, Tension the Skin, Cleanse Site.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check3" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Use non-dominant hand to stabilize the larynx.</li>
                        <li>Grasp thyroid cartilage between thumb and middle finger.</li>
                        <li>Apply tension to the skin, prevent lateral movement during procedure.</li>
                    </ul>
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
                        <span class="checkbox-label"><strong>Create Surgical Airway: Verticle incision, Horizontal Pierce, Dilate the Airway.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check5" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Make a 3-4 cm vertical midline incision over the cricothyroid membrane.</li>
                        <li>Identify the cricothyroid membrane by palpation through the incision.</li>
                        <li>Make a horizontal incision through the membrane.</li>
                        <li>Dilate the opening with finger.</li>
                        <li>If the trachea is deep to the neck, insert bougie to railroad ET tube.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Insert Airway Device.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check6" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>If the trachea is deep, insert bougie to guide ET tube.</li>
                        <li>Insert endotracheal or tracheostomy tube.</li>
                        <li>Direct tube caudally (toward feet).</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Confirm Placement: ETCO2, Bilateral Chest Rise / Lung Sounds, Negative Epigastric Sounds, Rising SPO2.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check7" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Confirm ETT placement at appropriate depth.</li>
                        <li>Maintain positive control over ETT until it can be secured.</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Secure Airway Device.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check8" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Secure tube using tracheostomy ties or tape.</li>
                        <li>Create better seal if significant air leak persists.</li>
                        <li>Document tube size and depth</li>
                    </ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Post Airway Established Follow-Up.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check9" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
                        <li>Ensure continous ETCO2 monitoring.</li>
                        <li>Reconfirm placement / depth with every patient movement / every 15 minutes.</li>
                        <li>Keep backup airways at the ready.</li>
                        <li>If complications occur consider dislodgement, obstruction, pneumothorax, equipment failure.</li>   
                    </ul>
                </div>
            </div>

        </div>
    </div>
    
    <div class="tool-actions">
        <div class="action-warning">
            <small><em>This is not intended to be a comprehensive guide for emergency surgical airway procedures</em></small>
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
                        <label for="cricothyrotomyIndication">Primary Indication</label>
                        <select class="form-select" id="cricothyrotomyIndication">
                            <option value="failed intubation">Failed Intubation</option>
                            <option value="facial trauma">Severe Facial Trauma</option>
                            <option value="angioedema">Angioedema/Airway Swelling</option>
                            <option value="foreign body">Upper Airway Foreign Body</option>
                            <option value="massive hemoptysis">Massive Hemoptysis</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tubeSize">Tube Size</label>
                        <select class="form-select" id="tubeSize">
                            <option value="5.0">5.0</option>
                            <option value="5.5">5.5</option>
                            <option value="6.0" selected>6.0</option>
                            <option value="6.5">6.5</option>
                            <option value="Shiley">Shiley 4</option>
                            <option value="Melker">Melker Kit</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="incisionType">Incision Type</label>
                        <div class="form-control-static">Vertical Midline Approach</div>
                        <input type="hidden" id="incisionType" value="vertical">
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
                            <option value="none">None - Primary Surgical Airway</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ventilation">Ventilation Method</label>
                        <select class="form-select" id="ventilation">
                            <option value="BVM" selected>BVM</option>
                            <option value="mechanical ventilator">Mechanical Ventilator</option>
                            <option value="portable ventilator">Portable Ventilator</option>
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
                            <input type="checkbox" class="complication-check" id="comp-tube-dislodgement">
                            <span>Tube dislodgement</span>
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
                            <input type="checkbox" class="complication-check" id="comp-posterior-wall">
                            <span>Posterior tracheal wall injury</span>
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
            <p>We've detected a previous cricothyrotomy checklist session. Would you like to continue where you left off or start a new checklist?</p>
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
    background: repeating-linear-gradient(
        45deg,
        #e3f2fd, /* Lighter pastel blue */
        #e3f2fd 2px,
        #bbdefb 2px, /* Slightly darker pastel blue for stripes */
        #bbdefb 20px
    );
    color: #0d47a1; /* Darker blue text for better contrast */
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    border: 5px solid #2196f3; /* Border in a complementary blue */
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

/* Display for selected incision type */
.selected-grade-display, .selected-incision-display {
    margin-top: 5px;
    margin-left: 35px;
    padding: 5px 10px;
    background-color: #f0f6fa;
    border-radius: 5px;
    font-size: 14px;
    display: none;
    width: calc(100% - 80px);
}

.selected-grade-display.active, .selected-incision-display.active {
    display: block;
}

.selected-med-item, .selected-grade-item, .selected-incision-item {
    display: flex;
    align-items: center;
    margin-bottom: 3px;
}

.selected-med-item i, .selected-grade-item i, .selected-incision-item i {
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

/* Incision Selection Styling */
.incision-options {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

.incision-option {
    border: 2px solid #dee2e6;
    border-radius: 10px;
    padding: 15px;
    position: relative;
    cursor: pointer;
    transition: all 0.2s;
}

.incision-option:hover {
    border-color: #adb5bd;
    background-color: #f8f9fa;
}

.incision-option.selected {
    border-color: #106e9e;
    background-color: #e3f2fd;
}

.incision-image {
    max-width: 100%;
    height: auto;
    margin-bottom: 10px;
    border-radius: 5px;
}

.incision-details h3 {
    margin: 0 0 5px;
    font-size: 16px;
    font-weight: 600;
}

.incision-details p {
    margin: 0 0 3px;
    font-size: 14px;
    line-height: 1.4;
}

.incision-note {
    color: #6c757d;
    font-style: italic;
    font-size: 13px !important;
}

.incision-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.incision-label {
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
    
    .checklist-item {
        padding: 15px;
    }
    
    .incision-options {
        grid-template-columns: 1fr;
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
    
    /* Show selected incision type when printing */
    .selected-incision-display.active {
        display: block !important;
        background-color: #fff !important;
        border: 1px dashed #ccc !important;
    }
    
    .selected-incision-item i {
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
            // Prepare the documentation form with saved data
            updateDocumentationIncisionType();
            
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
        
        localStorage.setItem('cricothyrotomyChecklist', JSON.stringify(saveData));
    }
    
    // Load checkbox states
    function loadCheckboxStates() {
        const savedData = localStorage.getItem('cricothyrotomyChecklist');
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
                        localStorage.removeItem('cricothyrotomyChecklist');
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
        const indication = document.getElementById('cricothyrotomyIndication').value;
        const tubeSize = document.getElementById('tubeSize').value;
        const attempts = document.getElementById('attempts').value;
        const priorAttempts = document.getElementById('priorAttempts').value;
        const ventilation = document.getElementById('ventilation').value;
        
        // Set incision description to vertical midline
        const incisionDescription = "vertical midline";
        
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
        let narrative = `Emergency surgical cricothyrotomy was performed for ${indication}. `;
        
        // Prior attempts
        if (priorAttempts !== 'none') {
            narrative += `"Can't Intubate, Can't Oxygenate" (CICO) situation was confirmed. `;
        }
        
        if (checkedItems.check2) {
            narrative += `Anterior neck landmarks were identified. `;
        }
        
        if (checkedItems.check3) {
            narrative += `Patient was positioned appropriately with neck in neutral/slight extension. `;
        }
        
        if (checkedItems.check4) {
            narrative += `All necessary equipment was prepared. `;
        }
        
        // Procedure
        if (checkedItems.check5) {
            narrative += `Larynx was stabilized with non-dominant hand. `;
        }
        
        // Procedure details
        narrative += `A ${incisionDescription} approach was utilized, `;
        
        if (checkedItems.check6) {
            narrative += `the cricothyroid membrane was identified and incised to create an opening into the trachea. `;
        }
        
        if (checkedItems.check7) {
            narrative += `A ${tubeSize} tube was inserted through the opening and advanced to appropriate depth. `;
        }
        
        if (checkedItems.check8) {
            narrative += `Proper placement was confirmed via chest rise, bilateral breath sounds, and end-tidal CO2 detection. `;
        }
        
        if (checkedItems.check9) {
            narrative += `The tube was secured in place. `;
        }
        
        narrative += `Ventilation was established using ${ventilation}. `;
        
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
        
        narrative += 'Patient was subsequently monitored for adequate oxygenation and ventilation.';
        
        return narrative;
    }
    
    // Initialize: Load saved states when page loads
    loadCheckboxStates();
    
    // Initialize complication checkboxes
    complicationLabels.forEach(label => {
        label.classList.remove('enabled');
    });
    
    // Update documentation incision type
    function updateDocumentationIncisionType() {
        // Since we're using fixed vertical midline approach
        document.getElementById('incisionType').value = 'vertical';
    }
    
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
            mainHeader.style.border = '2px solid #dc3545';
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
                filename: 'Surgical_Cricothyrotomy_Checklist.pdf',
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
});
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>