<?php
/**
 * Adult Intubation Checklist Tool - Optimized for Mobile/Touch
 * 
 * Place this file in: /tools/intubation.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'Adult Intubation Checklist';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="intubation-tool-container">
    <div class="intubation-header">
        <h1>Procedural Checklist:<br>Adult Intubation</h1>
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
                        <span class="checkbox-label"><strong>Verbalize the Patient's Clinical Indication for Intubation:</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check1" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
  <li>Failure to oxygenate with less invasive means <strong>or</strong></li>
  <li>Failure to protect the airway <strong>or</strong></li>
    <li>Patient requires higher airway pressures. <strong></strong></li>
</ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Difficult Airway Assessment: HEAVEN.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check2" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                <ul>
  <li>Hypoxemia.</li>
    <li>Extremes of Size.</li>
      <li>Anatomy.</li>
        <li>Vomit / Blood / Fluid.</li>
          <li>Exsanguination.</li>
            <li>Neck Mobility.</li>
</ul> </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Pre-Oxygenation: BVM w/ PEEP, Passive NC, Suction.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check3" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                    <ul>
  <li>Ventilate with BVM at appropriate TV / Rate.</li>
  <li>Provide passive oxygenation via NC up to 15LPM.</li> 
  <li>Decontaminate the airway with suction as necessary.</li>
    <li>If unable to reach SPO2 of >99%, provide a minimum of 10 full TV breathes.</li>
</ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Prepare Equipment: Video Laryngoscope, ETT - Primary / Backup, Stylet / Bougie, 10mL Syringe, ETCO2, Tube restraint.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check4" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                                    <ul>
    <li>Set-out and prepare all of the necessary equipment for intubation.</li>
  <li>Video laryngoscope should be used for first attempt outside of extenuating circumstances.</li>
</ul>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Prepare Backup Airway Devices: Supraglottic, Mac / Miller Laryngoscope, Surgical Cric.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check6" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                <ul>
  <li>Ensure backup airway devices are out and immediately available.</li>
    <li>Verbalize plan for failed intubation.</li>
</ul>
                </div>
            </div>
        </div>
    </div>
  

    <!-- Intubation Procedure Section -->
    <div class="checklist-section" id="section-procedure">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-list-check"></i>
                <h2>Intubation</h2>
            </div>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Position the Patient: Align Oral, Pharyngeal, and Laryngeal Axis.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check8" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
 <ul>
  <li>Use pillow or towels under shoulders / occiput to ramp the patient if there are no c-spine precautions.</li> 
  <li>Ensure bed/stretcher is at proper height for intubator.</li>
<li>Consider extraglottic pressure to improve visualization.</li>
</ul>                   
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Perform Laryngoscopy & Intubation.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check9" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                     <ul>
  <li>Identify anatomy: teeth > tongue > uvula > arytenoids > vocal cords > glottic opening.</li> 
  <li>Verbalize Cormack-Lehane Grade.</li>
  <li>Pass endotracheal tube, inflate cuff.</li>
  <li>Video laryngoscope remains in place until ET placement confirmed.</li>
    <li><strong>If failed:</strong> Progress through failed intubation algorithm.</li>  
</ul>      
                </div>
                <div class="selected-grade-display" id="selected-grade-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-grade-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> 
                </button>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Confirm Placement: ETCO2, Bilateral Chest Rise / Lung Sounds, Negative Epigastric Sounds, Rising SPO2.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check11" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                                                      <ul>
  <li>Confirm ETT placement at appropriate depth.</li> 
  <li>Approximately x3 diameter of ETT. (<i>Typically 21-23 cm at teeth for males, 19-21 cm for females</i>)</li>
  <li>Note centimeter marking at teeth or gums.</li>
</ul>     
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Secure ETT and Record Depth.</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check12" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="info-content always-visible">
                <ul>
                    <li>Confirm ETT placement at appropriate depth.</li> 
    <li>Start with x3 diameter of ETT. (<i>Typically 21-23 cm at teeth for males, 19-21 cm for females.</i>)</li>  
  <li>Secure ETT using commercial device or tape.</li> 
  <li>Ensure tube is stable and cannot be easily dislodged during movement or transport.</li> 
</ul>
                </div>
            </div>

                        <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label"><strong>Post Airway Follow-Up:</strong></span>
                        <input type="checkbox" class="checkbox-input" id="check10" data-section="procedure">
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
            <small><em>This is not intended to be a comprehensive guide for prehospital intubation</em></small>
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


<!-- Airway Grade Modal -->
<div class="tool-modal" id="airwayGradeModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Cormack-Lehane Airway Grade</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="airway-grades">
                <p class="modal-text">Select the grade that best matches your view during direct laryngoscopy:</p>
                
                <div class="grade-options">
                    <div class="grade-option" data-grade="1">
                        <img src="../assets/img/grade1.png" alt="Grade 1" class="grade-image">
                        <div class="grade-details">
                            <h3>Grade 1</h3>
                            <p>Full view of glottis and vocal cords</p>
                        </div>
                        <input type="radio" name="airway-grade" value="1" id="grade-1">
                        <label for="grade-1" class="grade-label"></label>
                    </div>
                    
                    <div class="grade-option" data-grade="2">
                        <img src="../assets/img/grade2.png" alt="Grade 2" class="grade-image">
                        <div class="grade-details">
                            <h3>Grade 2</h3>
                            <p>Partial view of glottis or arytenoids</p>
                        </div>
                        <input type="radio" name="airway-grade" value="2" id="grade-2">
                        <label for="grade-2" class="grade-label"></label>
                    </div>
                    
                    <div class="grade-option" data-grade="3">
                        <img src="../assets/img/grade3.png" alt="Grade 3" class="grade-image">
                        <div class="grade-details">
                            <h3>Grade 3</h3>
                            <p>Only epiglottis visible</p>
                        </div>
                        <input type="radio" name="airway-grade" value="3" id="grade-3">
                        <label for="grade-3" class="grade-label"></label>
                    </div>
                    
                    <div class="grade-option" data-grade="4">
                        <img src="../assets/img/grade4.png" alt="Grade 4" class="grade-image">
                        <div class="grade-details">
                            <h3>Grade 4</h3>
                            <p>No airway structures visible</p>
                        </div>
                        <input type="radio" name="airway-grade" value="4" id="grade-4">
                        <label for="grade-4" class="grade-label"></label>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-button save-button" id="save-airway-grade">Save Grade</button>
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
                        <label for="intubationIndication">Primary Indication</label>
                        <select class="form-select" id="intubationIndication">
                            <option value="respiratory failure">Respiratory Failure</option>
                            <option value="airway protection">Airway Protection</option>
                            <option value="cardiac arrest">Cardiac Arrest</option>
                            <option value="trauma">Trauma</option>
                            <option value="decreased consciousness">Decreased LOC</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ettSize">ETT Size</label>
                        <select class="form-select" id="ettSize">
                            <option value="7.0">7.0</option>
                            <option value="7.5" selected>7.5</option>
                            <option value="8.0">8.0</option>
                            <option value="8.5">8.5</option>
                            <option value="6.0">6.0</option>
                            <option value="6.5">6.5</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="ettDepth">ETT Depth (cm)</label>
                        <input type="number" class="form-input" id="ettDepth" placeholder="Depth at teeth" value="22">
                    </div>
                    <div class="form-group">
                        <label for="attempts">Number of Attempts</label>
                        <select class="form-select" id="attempts">
                            <option value="1" selected>1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4+">4+</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group" id="airway-grade-container">
                        <!-- This will be populated if an airway grade has been selected -->
                    </div>
                    <div class="form-group">
                        <label for="device">Intubation Device</label>
                        <select class="form-select" id="device">
                            <option value="direct laryngoscopy">Direct Laryngoscopy</option>
                            <option value="video laryngoscopy">Video Laryngoscopy</option>
                            <option value="bougie-assisted">Bougie-Assisted</option>
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
                            <input type="checkbox" class="complication-check" id="comp-difficult">
                            <span>Difficult airway</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-hypoxia">
                            <span>Transient hypoxia</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-hypotension">
                            <span>Hypotension</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-esophageal">
                            <span>Esophageal intubation (recognized & corrected)</span>
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
            <p>We've detected a previous intubation checklist session. Would you like to continue where you left off or start a new checklist?</p>
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

/* Display for selected medications and airway grade */
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

.calculate-button {
    background-color: #106e9e;
    color: white;
}

.calculate-button:hover {
    background-color: #0c5578;
}

/* Airway Grade Styling */
.grade-options {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
}

.grade-option {
    border: 2px solid #dee2e6;
    border-radius: 10px;
    padding: 15px;
    position: relative;
    cursor: pointer;
    transition: all 0.2s;
}

.grade-option:hover {
    border-color: #adb5bd;
    background-color: #f8f9fa;
}

.grade-option.selected {
    border-color: #106e9e;
    background-color: #e3f2fd;
}

.grade-image {
    max-width: 100%;
    height: auto;
    margin-bottom: 10px;
    border-radius: 5px;
}

.grade-details h3 {
    margin: 0 0 5px;
    font-size: 16px;
    font-weight: 600;
}

.grade-details p {
    margin: 0;
    font-size: 14px;
    color: #6c757d;
}

.grade-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.grade-label {
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

.selected-medications-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.selected-meds-list {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.selected-med-item {
    display: flex;
    align-items: center;
    background-color: #f8f9fa;
    padding: 8px 12px;
    border-radius: 5px;
    font-size: 14px;
}

.selected-med-item i {
    margin-right: 8px;
    color: #106e9e;
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
}

.comp-checkbox input[type="checkbox"] {
    margin-right: 8px;
}

.comp-checkbox.complication-check-label {
    opacity: 0.5;
    pointer-events: none;
}

.comp-checkbox.complication-check-label.enabled {
    opacity: 1;
    pointer-events: auto;
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
    // Main elements
    const checkboxes = document.querySelectorAll('.checkbox-input');
    const intubationCheckbox = document.getElementById('check9');
    let airwayGrade = '';
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
                        
                        // Special handlers for certain checkboxes
                        if (checkbox === intubationCheckbox) {
                            showAirwayGradeModal();
                        }
                    } else {
                        this.classList.remove('checked');
                        
                        // Clear display if unchecked
                        if (checkbox === intubationCheckbox) {
                            clearAirwayGrade();
                            document.getElementById('edit-grade-btn').style.display = 'none';
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
                if (this === intubationCheckbox) {
                    showAirwayGradeModal();
                }
            } else {
                listItem.classList.remove('checked');
                
                // Clear display if unchecked
                if (this === intubationCheckbox) {
                    clearAirwayGrade();
                    document.getElementById('edit-grade-btn').style.display = 'none';
                }
            }
            
            updateProgress();
        });
    });
    
    // Add click handlers for edit buttons
    document.getElementById('edit-grade-btn').addEventListener('click', function() {
        showAirwayGradeModal();
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
    
    // Airway Grade Modal
    function showAirwayGradeModal() {
        // Pre-select the previously selected grade if any
        if (airwayGrade) {
            document.querySelectorAll('.grade-option').forEach(option => {
                option.classList.remove('selected');
                if (option.dataset.grade === airwayGrade) {
                    option.classList.add('selected');
                    document.getElementById(`grade-${airwayGrade}`).checked = true;
                }
            });
        }
        
        openModal('airwayGradeModal');
    }
    
    // Airway grade selection
    document.querySelectorAll('.grade-option').forEach(option => {
        option.addEventListener('click', function() {
            const gradeValue = this.getAttribute('data-grade');
            document.querySelectorAll('.grade-option').forEach(opt => opt.classList.remove('selected'));
            this.classList.add('selected');
            document.getElementById(`grade-${gradeValue}`).checked = true;
        });
    });
    
    // Save airway grade
    document.getElementById('save-airway-grade').addEventListener('click', function() {
        const selectedGrade = document.querySelector('input[name="airway-grade"]:checked');
        if (selectedGrade) {
            airwayGrade = selectedGrade.value;
            updateAirwayGradeDisplay();
            updateDocumentationGrade();
            closeModal('airwayGradeModal');
            
            // Show edit button
            document.getElementById('edit-grade-btn').style.display = 'block';
        } else {
            alert('Please select an airway grade.');
        }
    });
    
    function updateAirwayGradeDisplay() {
        const display = document.getElementById('selected-grade-display');
        if (airwayGrade) {
            display.innerHTML = `<div class="selected-grade-item"><i class="ti ti-eye"></i>Cormack-Lehane Grade ${airwayGrade}</div>`;
            display.classList.add('active');
        } else {
            display.innerHTML = '';
            display.classList.remove('active');
        }
    }
    
    function clearAirwayGrade() {
        airwayGrade = '';
        const display = document.getElementById('selected-grade-display');
        display.innerHTML = '';
        display.classList.remove('active');
    }
    
    // Update documentation form with airway grade
    function updateDocumentationGrade() {
        const container = document.getElementById('airway-grade-container');
        if (airwayGrade) {
            container.innerHTML = `
                <label for="airwayGradeDisplay">Airway Grade</label>
                <div class="form-control-static">Cormack-Lehane Grade ${airwayGrade}</div>
                <input type="hidden" id="airwayGrade" value="${airwayGrade}">
            `;
        } else {
            container.innerHTML = `
                <label for="airwayGrade">Airway Grade</label>
                <select class="form-select" id="airwayGrade">
                    <option value="" selected>Not documented</option>
                    <option value="1">Grade 1</option>
                    <option value="2">Grade 2</option>
                    <option value="3">Grade 3</option>
                    <option value="4">Grade 4</option>
                </select>
            `;
        }
    }
    
    // Reset checklist button
    const resetButton = document.getElementById('reset-checklist');
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to reset the entire checklist?')) {
                resetAllCheckboxes();
                clearAirwayGrade();
                
                // Hide edit buttons
                document.getElementById('edit-grade-btn').style.display = 'none';
                
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
            updateDocumentationGrade();
            
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
        
        // Also save airway grade
        const saveData = {
            checkboxes: checkboxStates,
            airwayGrade: airwayGrade
        };
        
        localStorage.setItem('intubationChecklist', JSON.stringify(saveData));
    }
    
    // Load checkbox states
    function loadCheckboxStates() {
        const savedData = localStorage.getItem('intubationChecklist');
        if (savedData) {
            try {
                const data = JSON.parse(savedData);
                const checkboxStates = data.checkboxes || {};
                
                // Load saved airway grade if available
                if (data.airwayGrade) {
                    airwayGrade = data.airwayGrade;
                }
                
                // Check if any checkboxes were saved as checked
                const anyChecked = Object.values(checkboxStates).some(state => state === true);
                previousSessionExists = anyChecked;
                
                if (anyChecked) {
                    // Show continue session modal
                    openModal('continueSessionModal');
                    
                    // Set up the continue session button
                    document.getElementById('continue-session-btn').addEventListener('click', function() {
                        applyCheckboxStates(checkboxStates);
                        updateAirwayGradeDisplay();
                        
                        // Show edit buttons if needed
                        document.getElementById('edit-grade-btn').style.display = airwayGrade ? 'block' : 'none';
                        
                        closeModal('continueSessionModal');
                    }, { once: true });
                    
                    // Set up the new session button
                    document.getElementById('new-session-btn').addEventListener('click', function() {
                        // Clear local storage and reset the UI
                        localStorage.removeItem('intubationChecklist');
                        resetAllCheckboxes();
                        clearAirwayGrade();
                        
                        // Hide edit buttons
                        document.getElementById('edit-grade-btn').style.display = 'none';
                        
                        closeModal('continueSessionModal');
                    }, { once: true });
                } else {
                    // If no checkboxes were previously checked, just apply the states directly
                    applyCheckboxStates(checkboxStates);
                    updateAirwayGradeDisplay();
                    
                    // Show edit buttons if needed
                    document.getElementById('edit-grade-btn').style.display = airwayGrade ? 'block' : 'none';
                }
            } catch (e) {
                console.error("Error parsing saved checklist:", e);
                // If there's an error, just reset everything
                resetAllCheckboxes();
                clearAirwayGrade();
                
                // Hide edit buttons
                document.getElementById('edit-grade-btn').style.display = 'none';
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
        const indication = document.getElementById('intubationIndication').value;
        const ettSize = document.getElementById('ettSize').value;
        const ettDepth = document.getElementById('ettDepth').value || "[depth]";
        const attempts = document.getElementById('attempts').value;
        const device = document.getElementById('device').value;
        const airwayGradeValue = document.getElementById('airwayGrade')?.value || airwayGrade;
        
        // Get checked items from the checklist
        const checkedItems = {};
        checkboxes.forEach(checkbox => {
            checkedItems[checkbox.id] = checkbox.checked;
        });
        
        // Get complications
        const complications = [];
        if (!document.getElementById('comp-none').checked) {
            document.querySelectorAll('.complication-check:checked').forEach(checkbox => {
                complications.push(checkbox.id.replace('comp-', ''));
            });
        }
        
        // Build the narrative
        let narrative = `Patient presented with ${indication}. `;
        
        // Pre-procedure assessment
        if (checkedItems.check1) {
            narrative += `Clinical indication for intubation was confirmed. `;
        }
        
        if (checkedItems.check2) {
            narrative += `LEMON airway assessment was performed. `;
        }
        
        // Pre-oxygenation
        if (checkedItems.check3) {
            narrative += `Pre-oxygenation was performed prior to intubation. `;
        }
        
        // Equipment preparation
        if (checkedItems.check4) {
            narrative += `All necessary airway equipment was prepared and checked prior to procedure. `;
        }
        
        // Positioning and procedure
        if (checkedItems.check8) {
            narrative += `Patient was positioned in sniffing position. `;
        }
        
        // Intubation procedure and airway grade
        narrative += `Patient was intubated using ${device} after ${attempts} attempt(s). `;
        if (airwayGradeValue) {
            narrative += `Cormack-Lehane Grade ${airwayGradeValue} view was obtained. `;
        }
        
        narrative += `A ${ettSize} ETT was inserted and secured at ${ettDepth} cm at the teeth. `;
        
        if (checkedItems.check11) {
            narrative += 'Tube placement was confirmed via ';
            let confirmationMethods = [];
            
            if (checkedItems.check11) {
                confirmationMethods.push('end-tidal CO2');
            }
            
            if (confirmationMethods.length > 0) {
                narrative += confirmationMethods.join(', ');
                narrative += ', chest rise with ventilation, and absence of epigastric sounds. ';
            } else {
                narrative += 'visualization of the tube passing through the vocal cords, chest rise with ventilation, and absence of epigastric sounds. ';
            }
        }
        
        // Complications
        if (complications.length > 0) {
            narrative += 'Complications noted during procedure: ';
            complications.forEach((comp, index) => {
                if (index > 0) {
                    narrative += (index === complications.length - 1) ? ' and ' : ', ';
                }
                narrative += comp.replace(/-/g, ' ');
            });
            narrative += '. ';
        } else {
            narrative += 'No complications were encountered during the procedure. ';
        }
        
        narrative += 'Patient is being ventilated with good compliance following intubation.';
        
        return narrative;
    }
    
    // Initialize: Load saved states when page loads
    loadCheckboxStates();
    
    // Initialize complication checkboxes
    complicationLabels.forEach(label => {
        label.classList.remove('enabled');
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
                filename: 'Adult_Intubation_Checklist.pdf',
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