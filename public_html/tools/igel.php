<?php
/**
 * I-Gel / Supraglottic Airway Placement Tool - Optimized for Mobile/Touch
 * 
 * Place this file in: /tools/igel.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'I-Gel / SGA Placement Checklist';
$active_tab = 'tools';

// Define base URL to use absolute paths
$base_url = SITE_URL; // Add this line to define $base_url

// Include header
include '../includes/frontend_header.php';
?>

<div class="igel-tool-container">
    <div class="igel-header">
        <h1>I-Gel / Supraglottic Airway Placement</h1>
        <p>Use this interactive checklist to ensure safe and effective placement of supraglottic airway devices. Click on the <i class="ti ti-info-circle"></i> icons for detailed information about each step.</p>
    </div>
    
    <!-- Prepare Procedure Section -->
    <div class="checklist-section" id="section-prepare">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-list-check"></i>
                <h2>1. Prepare Procedure</h2>
            </div>
            <span class="section-status"><span class="completed-count">0</span>/<span class="total-count">6</span></span>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Verify Indication for SGA</span>
                        <input type="checkbox" class="checkbox-input" id="check1" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Verify clinical indication for supraglottic airway placement including: failed/difficult intubation, airway compromise requiring temporary management, cardiac arrest, or primary airway management when endotracheal intubation is not available or indicated.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Assess Patient Suitability</span>
                        <input type="checkbox" class="checkbox-input" id="check2" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Check for contraindications: conscious patient with intact gag reflex, known esophageal disease (varices, strictures, tumors), caustic ingestion, high aspiration risk, known airway obstruction or pathology below the glottis, limited mouth opening (<2.5cm), or high airway resistance situations.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Select Appropriate Size</span>
                        <input type="checkbox" class="checkbox-input" id="check3" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="I-Gel Size Guidelines: Size 1 (neonates 2-5kg), Size 1.5 (infants 5-12kg), Size 2 (small pediatrics 10-25kg), Size 2.5 (large pediatrics 25-35kg), Size 3 (small adults 30-60kg), Size 4 (medium adults 50-90kg), Size 5 (large adults 90+kg). When between sizes, choose the larger size for proper seal.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
                <div class="selected-size-display" id="selected-size-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-size-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Pre-Oxygenation Strategy</span>
                        <input type="checkbox" class="checkbox-input" id="check4" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Prepare equipment for pre-oxygenation: BVM with PEEP valve, non-rebreather mask at high flow, and functioning suction. Pre-oxygenate for 3-5 minutes when possible, or 8 vital capacity breaths when time-critical.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Equipment Ready</span>
                        <input type="checkbox" class="checkbox-input" id="check5" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Check all equipment: Appropriate size I-Gel/SGA, water-soluble lubricant, 10mL syringe (for LMA), BVM, functioning suction, end-tidal CO2 detector, securing method, backup devices (different sizes, different type of SGA or ETT kit).">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Medications (if needed)</span>
                        <input type="checkbox" class="checkbox-input" id="check6" data-section="prepare">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="I-Gel/SGA placement may be performed without medications in unconscious patients or in cardiac arrest. In semi-conscious patients or those with some respiratory drive, consider appropriate sedation while maintaining spontaneous breathing when possible.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
                <div class="selected-meds-display" id="selected-meds-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-meds-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
        </div>
    </div>
    
    <!-- Insertion Procedure Section -->
    <div class="checklist-section" id="section-procedure">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-first-aid-kit"></i>
                <h2>2. Insertion Procedure</h2>
            </div>
            <span class="section-status"><span class="completed-count">0</span>/<span class="total-count">5</span></span>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Position Patient</span>
                        <input type="checkbox" class="checkbox-input" id="check7" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Position patient in 'sniffing position' by aligning oral, pharyngeal, and laryngeal axes. For I-Gel: Head extension, neck flexion. For unconscious patients without C-spine concerns, slight head extension often improves insertion success. Ensure bed/stretcher at proper height.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Prepare Device</span>
                        <input type="checkbox" class="checkbox-input" id="check8" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="For I-Gel: Apply thin layer of water-based lubricant to front, back and sides of the cuff. Avoid excess lubricant and keep the device tilt upward to prevent lubricant blocking airway channel. For LMA: Check cuff integrity, then fully deflate cuff in 'spoon' shape and apply lubricant to posterior surface.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Insert Device</span>
                        <input type="checkbox" class="checkbox-input" id="check9" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="I-Gel: Hold device like a pencil with opening facing patient's chin. Introduce it along hard palate with continuous gentle pressure until definitive resistance is felt. LMA: Insert with index finger guiding along palate, then advance until resistance felt. For both: DO NOT FORCE insertion, avoid pushing on tongue, maintain midline approach.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Confirm Placement</span>
                        <input type="checkbox" class="checkbox-input" id="check10" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Confirm proper placement by: Chest rise with ventilation, bilateral breath sounds, absence of gastric insufflation sounds, positive ETCO2, no audible leak during ventilation, and appropriate compliance with BVM ventilation. Bite block should be between teeth, with visible marker at proper alignment.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Secure Device</span>
                        <input type="checkbox" class="checkbox-input" id="check11" data-section="procedure">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Once placement confirmed, secure the device using tape or commercial securing method. For I-Gel, use the integrated strap holes. Document depth marker position. Apply bite block or oropharyngeal airway as needed to prevent biting of the airway tube.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Post Insertion Section -->
    <div class="checklist-section" id="section-post">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-heartbeat"></i>
                <h2>3. Post Insertion</h2>
            </div>
            <span class="section-status"><span class="completed-count">0</span>/<span class="total-count">4</span></span>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Ventilation Management</span>
                        <input type="checkbox" class="checkbox-input" id="check12" data-section="post">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Set appropriate ventilation parameters. For spontaneously breathing patients, provide supplemental oxygen. For controlled ventilation, use lower tidal volumes (6-8 mL/kg ideal body weight) and lower peak pressures (<20 cmH2O) than with ETT to minimize gastric insufflation and leaks.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Gastric Access (if available)</span>
                        <input type="checkbox" class="checkbox-input" id="check13" data-section="post">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="For I-Gel and LMA Supreme/ProSeal: Consider inserting an appropriate size gastric tube through the dedicated gastric channel to decompress the stomach and reduce aspiration risk. Document size of gastric tube used. Secure gastric tube separately if inserted.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Ongoing Monitoring</span>
                        <input type="checkbox" class="checkbox-input" id="check14" data-section="post">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Continuously monitor: SpO2, ETCO2 waveform, ventilation compliance, vital signs, and device position. Watch for signs of device migration, loss of seal, or aspiration. Reassess after every patient movement. Consider transition to definitive airway (ETT) for prolonged ventilation needs.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Sedation Management (if needed)</span>
                        <input type="checkbox" class="checkbox-input" id="check15" data-section="post">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="If patient requires ongoing sedation, titrate to maintain device tolerance while preserving respiratory drive when possible. Monitor for signs of respiratory depression. In unconscious patients without sedation, observe for return of airway reflexes which may require device removal or conversion to ETT.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
                <div class="selected-meds-display" id="selected-post-meds-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-post-meds-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
        </div>
    </div>
    
    <div class="tool-actions">
        <div class="action-warning">
            <small><em>This is not intended to be a comprehensive guide for supraglottic airway management</em></small>
        </div>
        <div class="action-buttons">
            <button id="reset-checklist" class="action-button reset-button">
                <i class="ti ti-refresh"></i> Reset
            </button>
            <button id="generate-narrative" class="action-button generate-button">
                <i class="ti ti-file-text"></i> Generate Documentation
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
            <button class="action-button primary-button" id="save-igel-size">Save Selection</button>
        </div>
    </div>
</div>

<!-- Sedation Medication Modal -->
<div class="tool-modal" id="medicationModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Sedation Medication Selection</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-text">Select medications being used for sedation (if needed):</p>
            
            <div class="calculator-form">
                <div class="form-group">
                    <label for="patient-weight">Patient Weight (for dose calculation)</label>
                    <div class="input-group">
                        <input type="number" id="patient-weight" class="form-input" placeholder="Enter weight">
                        <div class="input-group-buttons">
                            <button class="unit-button active" data-unit="kg">kg</button>
                            <button class="unit-button" data-unit="lbs">lbs</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="med-category">
                <h4>Sedatives & Analgesics</h4>
                <div class="med-results">
                    <div class="med-result" data-med="midazolam">
                        <div class="med-name">Midazolam</div>
                        <div class="med-dose"><span id="midazolam-dose">--</span> mg IV</div>
                        <div class="med-note">0.05-0.1 mg/kg IV</div>
                    </div>
                    
                    <div class="med-result" data-med="ketamine-low">
                        <div class="med-name">Ketamine (Low Dose)</div>
                        <div class="med-dose"><span id="ketamine-low-dose">--</span> mg IV</div>
                        <div class="med-note">0.3-0.5 mg/kg IV</div>
                    </div>
                    
                    <div class="med-result" data-med="fentanyl">
                        <div class="med-name">Fentanyl</div>
                        <div class="med-dose"><span id="fentanyl-dose">--</span> mcg IV</div>
                        <div class="med-note">0.5-1 mcg/kg IV</div>
                    </div>
                    
                    <div class="med-result" data-med="propofol">
                        <div class="med-name">Propofol</div>
                        <div class="med-dose"><span id="propofol-dose">--</span> mg IV</div>
                        <div class="med-note">0.5-1 mg/kg IV</div>
                    </div>
                </div>
            </div>
            
            <div class="med-category">
                <h4>No Sedation Required</h4>
                <div class="med-results">
                    <div class="med-result" data-med="none">
                        <div class="med-name">No Sedation Needed</div>
                        <div class="med-note">Patient in cardiac arrest or already unconscious</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-button primary-button" id="save-medications">Save Selections</button>
        </div>
    </div>
</div>

<!-- Post-Insertion Medication Modal -->
<div class="tool-modal" id="postMedicationModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Post-Insertion Medications</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-text">Select medications used for ongoing sedation (if needed):</p>
            
            <div class="med-category">
                <h4>Ongoing Sedation</h4>
                <div class="med-results">
                    <div class="med-result" data-med="midazolam-maintenance">
                        <div class="med-name">Midazolam (Intermittent)</div>
                        <div class="med-dose">1-2 mg IV q 15-30 min</div>
                        <div class="med-note">Titrate to effect</div>
                    </div>
                    
                    <div class="med-result" data-med="ketamine-maintenance">
                        <div class="med-name">Ketamine (Intermittent)</div>
                        <div class="med-dose">10-20 mg IV q 15-30 min</div>
                        <div class="med-note">Preserves respiratory drive</div>
                    </div>
                    
                    <div class="med-result" data-med="fentanyl-maintenance">
                        <div class="med-name">Fentanyl (Intermittent)</div>
                        <div class="med-dose">25-50 mcg IV q 15-30 min</div>
                        <div class="med-note">Use caution with respiratory depression</div>
                    </div>
                </div>
            </div>
            
            <div class="med-category">
                <h4>No Additional Sedation</h4>
                <div class="med-results">
                    <div class="med-result" data-med="none-post">
                        <div class="med-name">No Ongoing Sedation</div>
                        <div class="med-note">Patient adequately sedated or unconscious</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-button primary-button" id="save-post-medications">Save Selections</button>
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
                            <option value="LMA">LMA</option>
                            <option value="King LT">King LT</option>
                            <option value="Air-Q">Air-Q</option>
                            <option value="other SGA">Other SGA</option>
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
                
                <div class="form-section" id="selected-medications-container">
                    <!-- This will be populated with selected medications -->
                </div>
                
                <div class="form-section" id="selected-post-medications-container">
                    <!-- This will be populated with post-insertion medications -->
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
            <p>We've detected a previous I-Gel/SGA checklist session. Would you like to continue where you left off or start a new checklist?</p>
        </div>
        <div class="modal-footer session-buttons">
            <button class="action-button" id="new-session-btn">Start New</button>
            <button class="action-button primary-button" id="continue-session-btn">Continue Previous</button>
        </div>
    </div>
</div>

<!-- Standard Info Modal -->
<div class="tool-modal" id="infoModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Item Details</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="info-content">
                <p id="modalInfo">Detailed information will appear here.</p>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-button close-button">Close</button>
        </div>
    </div>
</div>

<style>
/* Reset and custom styles to override site defaults */
.igel-tool-container * {
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.igel-tool-container {
    max-width: 800px;
    margin: 0 auto 3rem;
    overflow: hidden;
    padding-bottom: 20px;
}

.igel-header {
    background-color: #106e9e;
    color: white;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
    border-radius: 10px 10px 0 0;
    margin: 15px 15px 0;
}

.igel-header h1 {
    margin: 0 0 10px;
    font-size: 28px;
    font-weight: 600;
}

.igel-header p {
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
    border-left: 5px solid #106e9e;
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
    border-bottom: 1px solid #e6eef5;
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
    display: flex;
    align-items: center;
}

/* Checklist Items Styling */
.checklist-items {
    padding: 15px;
}

.checklist-item {
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
}

.checklist-item:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.checklist-item-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* Custom Checkbox */
.checkbox-container {
    display: flex;
    align-items: center;
    position: relative;
    padding-left: 35px;
    cursor: pointer;
    user-select: none;
    width: 100%;
}

.checkbox-label {
    font-size: 16px;
    color: #333;
    line-height: 1.4;
}

.checkbox-input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 22px;
    width: 22px;
    background-color: #f0f0f0;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    transition: all 0.2s;
}

.checkbox-container:hover .checkmark {
    background-color: #e0e0e0;
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
    left: 8px;
    top: 4px;
    width: 6px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

/* Info Button */
.info-btn {
    background: none;
    border: none;
    color: #6c757d;
    font-size: 18px;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: 10px;
}

.info-btn:hover {
    color: #106e9e;
    background-color: rgba(16, 110, 158, 0.1);
}

.info-btn:focus {
    outline: none;
}

/* Tool Actions */
.tool-actions {
    margin: 20px 15px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.action-warning {
    margin-bottom: 15px;
    text-align: center;
    color: #6c757d;
}

.action-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    width: 100%;
}

.action-button {
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.action-button i {
    margin-right: 8px;
}

.reset-button {
    background-color: #f8f9fa;
    color: #6c757d;
    border: 1px solid #dee2e6;
}

.reset-button:hover {
    background-color: #e9ecef;
}

.generate-button {
    background-color: #106e9e;
    color: white;
}

.generate-button:hover {
    background-color: #0c5578;
}

/* Selected Size and Medications Display */
.selected-size-display,
.selected-meds-display {
    background-color: #f8f9fa;
    border-radius: 5px;
    padding: 10px;
    margin-top: 10px;
    font-size: 14px;
    color: #495057;
    border-left: 3px solid #106e9e;
}

.edit-selection-btn {
    background: none;
    border: none;
    color: #106e9e;
    font-size: 14px;
    cursor: pointer;
    margin-top: 5px;
    padding: 0;
}

.edit-selection-btn:hover {
    text-decoration: underline;
}

/* Modal Styling */
.tool-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    /* Increase z-index to ensure it's above the header */
    z-index: 2000;
    overflow-y: auto;
    padding: 20px;
}

.modal-content {
    background-color: white;
    margin: 20px auto;
    width: 90%;
    max-width: 600px;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    animation: modalSlideIn 0.3s ease;
}

.large-modal {
    max-width: 700px;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    background-color: #106e9e;
    color: white;
    padding: 15px 20px;
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
    background: none;
    border: none;
    color: white;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    line-height: 1;
}

.modal-body {
    padding: 20px;
    max-height: 70vh;
    overflow-y: auto;
}

.modal-text {
    margin-top: 0;
    margin-bottom: 15px;
}

.modal-footer {
    padding: 15px 20px;
    display: flex;
    justify-content: flex-end;
    border-top: 1px solid #e9ecef;
}

.primary-button {
    background-color: #106e9e;
    color: white;
}

.primary-button:hover {
    background-color: #0c5578;
}

.close-button {
    background-color: #f8f9fa;
    color: #6c757d;
    border: 1px solid #dee2e6;
}

.close-button:hover {
    background-color: #e9ecef;
}

/* Size Selection Modal */
.size-options {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.size-option {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    position: relative;
    cursor: pointer;
    transition: all 0.2s;
}

.size-option:hover {
    border-color: #adb5bd;
    background-color: #f8f9fa;
}

.size-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

.size-option input[type="radio"]:checked + .size-label {
    background-color: rgba(16, 110, 158, 0.1);
    border: 2px solid #106e9e;
}

.size-label {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 8px;
    pointer-events: none;
}

.size-details h3 {
    margin-top: 0;
    margin-bottom: 10px;
    color: #106e9e;
}

.size-details p {
    margin: 5px 0;
    font-size: 14px;
}

.size-note {
    background-color: #fff3cd;
    border-left: 4px solid #ffc107;
    padding: 15px;
    border-radius: 5px;
    margin-top: 20px;
}

/* Medication Selection */
.calculator-form {
    margin-bottom: 20px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
}

.input-group {
    display: flex;
    align-items: center;
}

.form-input {
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px 0 0 5px;
    width: 100%;
    font-size: 16px;
}

.input-group-buttons {
    display: flex;
}

.unit-button {
    padding: 10px 15px;
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    font-size: 14px;
    cursor: pointer;
}

.unit-button:first-child {
    border-right: none;
}

.unit-button:last-child {
    border-radius: 0 5px 5px 0;
}

.unit-button.active {
    background-color: #106e9e;
    color: white;
    border-color: #106e9e;
}

.med-category {
    margin-bottom: 25px;
}

.med-category h4 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #2c3e50;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 8px;
}

.med-results {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 10px;
}

.med-result {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 12px;
    cursor: pointer;
    transition: all 0.2s;
}

.med-result:hover {
    border-color: #adb5bd;
    background-color: #f8f9fa;
}

.med-result.selected {
    background-color: rgba(16, 110, 158, 0.1);
    border: 2px solid #106e9e;
}

.med-name {
    font-weight: 500;
    color: #2c3e50;
    margin-bottom: 5px;
}

.med-dose {
    color: #106e9e;
    font-weight: 500;
    margin-bottom: 5px;
}

.med-note {
    font-size: 12px;
    color: #6c757d;
}

/* Documentation Form */
.narrative-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-row {
    display: flex;
    gap: 15px;
}

.form-row .form-group {
    flex: 1;
    margin-bottom: 0;
}

.form-select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    font-size: 16px;
    background-color: white;
    cursor: pointer;
}

.form-section {
    margin-top: 20px;
}

.form-section h3 {
    margin-top: 0;
    margin-bottom: 15px;
    color: #2c3e50;
    font-size: 18px;
}

.comp-checkboxes {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 10px;
}

.comp-checkbox {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
    cursor: pointer;
}

.comp-checkbox input[type="checkbox"] {
    margin-right: 8px;
}

/* Narrative Display */
.narrative-box {
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    max-height: 50vh;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    line-height: 1.6;
}

/* Session Buttons */
.session-buttons {
    display: flex;
    justify-content: space-between;
    gap: 15px;
}

.session-buttons button {
    flex: 1;
}

/* Info Modal Content */
.info-content {
    line-height: 1.6;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .form-row {
        flex-direction: column;
        gap: 15px;
    }
    
    .form-row .form-group {
        margin-bottom: 0;
    }
    
    .size-options, 
    .med-results,
    .comp-checkboxes {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
        width: 100%;
    }
    
    .action-button {
        width: 100%;
    }

    .modal-content {
        width: 95%;
        margin: 10px auto;
    }
    
    .igel-header h1 {
        font-size: 24px;
    }
    
    .section-title h2 {
        font-size: 16px;
    }
    
    .checkbox-label {
        font-size: 15px;
    }
}

/* Complete and incomplete section indicators */
.checklist-section.completed {
    border-left-color: #28a745;
}

.checklist-section.completed .section-status {
    background-color: #d4edda;
    color: #28a745;
}

/* Animation for checking items */
@keyframes checkPulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.checkbox-input:checked ~ .checkmark {
    animation: checkPulse 0.4s ease;
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
            }
        });
    }
    
    if (data.selectedSize) {
        displaySelectedSize(data.selectedSize);
        document.getElementById('check3').checked = true;
    }
    
    if (data.selectedMeds) {
        displaySelectedMeds(data.selectedMeds);
        document.getElementById('check6').checked = true;
    }
    
    if (data.selectedPostMeds) {
        displaySelectedPostMeds(data.selectedPostMeds);
        document.getElementById('check15').checked = true;
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
            // Update section completion status
            updateSectionStatus();
            
            // Save state
            saveChecklistState();
            
            // If Size checkbox checked, show size selector
            if (this.id === 'check3' && this.checked) {
                openSizeSelector();
            }
            
            // If Medications checkbox checked, show medication selector
            if (this.id === 'check6' && this.checked) {
                openMedicationSelector();
            }
            
            // If Post-insertion medications checkbox checked, show medication selector
            if (this.id === 'check15' && this.checked) {
                openPostMedicationSelector();
            }
        });
    });
    
    // Add event listeners to edit buttons
    document.getElementById('edit-size-btn').addEventListener('click', openSizeSelector);
    document.getElementById('edit-meds-btn').addEventListener('click', openMedicationSelector);
    document.getElementById('edit-post-meds-btn').addEventListener('click', openPostMedicationSelector);
    
    // Add event listeners to info buttons
    const infoButtons = document.querySelectorAll('.info-btn');
    infoButtons.forEach(button => {
        button.addEventListener('click', function() {
            const infoText = this.getAttribute('data-info');
            showInfoModal(infoText);
        });
    });
    
    // Update initial section status
    updateSectionStatus();
}

// Update section completion status
function updateSectionStatus() {
    const sections = document.querySelectorAll('.checklist-section');
    
    sections.forEach(section => {
        const sectionId = section.id;
        const sectionName = sectionId.split('-')[1];
        const checkboxes = section.querySelectorAll(`.checkbox-input[data-section="${sectionName}"]`);
        const completedCount = Array.from(checkboxes).filter(cb => cb.checked).length;
        const totalCount = checkboxes.length;
        
        // Update the count display
        section.querySelector('.completed-count').textContent = completedCount;
        section.querySelector('.total-count').textContent = totalCount;
        
        // Mark section as completed if all checkboxes are checked
        if (completedCount === totalCount) {
            section.classList.add('completed');
        } else {
            section.classList.remove('completed');
        }
    });
}

// Save current checklist state to localStorage
function saveChecklistState() {
    const checkboxes = document.querySelectorAll('.checkbox-input:checked');
    const checkedIds = Array.from(checkboxes).map(cb => cb.id);
    
    // Get selected size if applicable
    const sizeDisplay = document.getElementById('selected-size-display');
    const selectedSize = sizeDisplay.textContent ? {
        size: sizeDisplay.getAttribute('data-size'),
        weight: sizeDisplay.getAttribute('data-weight'),
        color: sizeDisplay.getAttribute('data-color')
    } : null;
    
    // Get selected medications if applicable
    const medsDisplay = document.getElementById('selected-meds-display');
    const selectedMeds = medsDisplay.textContent ? 
        Array.from(medsDisplay.querySelectorAll('.selected-med')).map(med => med.getAttribute('data-med')) : 
        null;
        
    // Get selected post-insertion medications if applicable
    const postMedsDisplay = document.getElementById('selected-post-meds-display');
    const selectedPostMeds = postMedsDisplay.textContent ? 
        Array.from(postMedsDisplay.querySelectorAll('.selected-med')).map(med => med.getAttribute('data-med')) : 
        null;
    
    const state = {
        checkboxes: checkedIds,
        selectedSize: selectedSize,
        selectedMeds: selectedMeds,
        selectedPostMeds: selectedPostMeds
    };
    
    localStorage.setItem('igelChecklist', JSON.stringify(state));
}

// Set up modal functionality
function setupModals() {
    // Close modal functionality
    const closeButtons = document.querySelectorAll('.close-modal, .close-button');
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.tool-modal');
            modal.style.display = 'none';
        });
    });
    
    // Close modal when clicking outside the content
    window.addEventListener('click', function(event) {
        if (event.target.classList.contains('tool-modal')) {
            event.target.style.display = 'none';
        }
    });
    
    // I-Gel Size selector
    setupSizeSelector();
    
    // Medication selector
    setupMedicationSelector();
    
    // Post-insertion medication selector
    setupPostMedicationSelector();
    
    // Documentation form
    setupNarrativeForm();
}

// Set up I-Gel size selector functionality
function setupSizeSelector() {
    const sizeOptions = document.querySelectorAll('.size-option');
    const saveButton = document.getElementById('save-igel-size');
    
    sizeOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Select the radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
        });
    });
    
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
            saveChecklistState();
        }
    });
}

// Display the selected I-Gel size
function displaySelectedSize(sizeData) {
    const display = document.getElementById('selected-size-display');
    const editButton = document.getElementById('edit-size-btn');
    
    display.innerHTML = `<strong>I-Gel Size ${sizeData.size}</strong> - ${sizeData.weight}, ${sizeData.color} connector`;
    display.setAttribute('data-size', sizeData.size);
    display.setAttribute('data-weight', sizeData.weight);
    display.setAttribute('data-color', sizeData.color);
    
    display.style.display = 'block';
    editButton.style.display = 'block';
}

// Open the I-Gel size selector modal
function openSizeSelector() {
    document.getElementById('sizeChartModal').style.display = 'block';
}

// Set up medication selector functionality
function setupMedicationSelector() {
    const weightInput = document.getElementById('patient-weight');
    const unitButtons = document.querySelectorAll('.unit-button');
    const medResults = document.querySelectorAll('#medicationModal .med-result');
    const saveButton = document.getElementById('save-medications');
    
    let weightUnit = 'kg'; // Default unit
    
    // Unit selection
    unitButtons.forEach(button => {
        button.addEventListener('click', function() {
            unitButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            weightUnit = this.getAttribute('data-unit');
            calculateDoses();
        });
    });
    
    // Weight input handler
    weightInput.addEventListener('input', calculateDoses);
    
    // Calculate medication doses
    function calculateDoses() {
        const weight = parseFloat(weightInput.value);
        
        if (!isNaN(weight) && weight > 0) {
            // Convert weight to kg if needed
            const weightInKg = weightUnit === 'lbs' ? weight * 0.453592 : weight;
            
            // Calculate doses
            document.getElementById('midazolam-dose').textContent = (weightInKg * 0.05).toFixed(1) + '-' + (weightInKg * 0.1).toFixed(1);
            document.getElementById('ketamine-low-dose').textContent = (weightInKg * 0.3).toFixed(1) + '-' + (weightInKg * 0.5).toFixed(1);
            document.getElementById('fentanyl-dose').textContent = (weightInKg * 0.5).toFixed(1) + '-' + (weightInKg * 1).toFixed(1);
            document.getElementById('propofol-dose').textContent = (weightInKg * 0.5).toFixed(1) + '-' + (weightInKg * 1).toFixed(1);
        }
    }
    
    // Medication selection
    medResults.forEach(med => {
        med.addEventListener('click', function() {
            this.classList.toggle('selected');
        });
    });
    
    // Save button handler
    saveButton.addEventListener('click', function() {
        const selectedMeds = document.querySelectorAll('#medicationModal .med-result.selected');
        
        if (selectedMeds.length > 0) {
            saveMedicationSelection(selectedMeds);
            document.getElementById('medicationModal').style.display = 'none';
        } else {
            alert('Please select at least one medication or "No Sedation Needed".');
        }
    });
}

// Save the selected medications
function saveMedicationSelection(selectedMeds) {
    const display = document.getElementById('selected-meds-display');
    const editButton = document.getElementById('edit-meds-btn');
    const weight = document.getElementById('patient-weight').value;
    const weightUnit = document.querySelector('.unit-button.active').getAttribute('data-unit');
    
    let html = `<div class="selected-weight">Patient weight: ${weight} ${weightUnit}</div>`;
    
    selectedMeds.forEach(med => {
        const medName = med.querySelector('.med-name').textContent;
        const medDose = med.querySelector('.med-dose').textContent;
        html += `<div class="selected-med" data-med="${med.getAttribute('data-med')}">${medName}: ${medDose}</div>`;
    });
    
    display.innerHTML = html;
    display.style.display = 'block';
    editButton.style.display = 'block';
    
    // Save state
    saveChecklistState();
}

// Open the medication selector modal
function openMedicationSelector() {
    document.getElementById('medicationModal').style.display = 'block';
}

// Set up post-insertion medication selector functionality
function setupPostMedicationSelector() {
    const medResults = document.querySelectorAll('#postMedicationModal .med-result');
    const saveButton = document.getElementById('save-post-medications');
    
    // Medication selection
    medResults.forEach(med => {
        med.addEventListener('click', function() {
            medResults.forEach(m => m.classList.remove('selected'));
            this.classList.add('selected');
        });
    });
    
    // Save button handler
    saveButton.addEventListener('click', function() {
        const selectedMeds = document.querySelectorAll('#postMedicationModal .med-result.selected');
        
        if (selectedMeds.length > 0) {
            savePostMedicationSelection(selectedMeds);
            document.getElementById('postMedicationModal').style.display = 'none';
        } else {
            alert('Please select at least one option.');
        }
    });
}

// Save the selected post-insertion medications
function savePostMedicationSelection(selectedMeds) {
    const display = document.getElementById('selected-post-meds-display');
    const editButton = document.getElementById('edit-post-meds-btn');
    
    let html = '';
    
    selectedMeds.forEach(med => {
        const medName = med.querySelector('.med-name').textContent;
        const medDose = med.querySelector('.med-dose')?.textContent || '';
        html += `<div class="selected-med" data-med="${med.getAttribute('data-med')}">${medName}${medDose ? ': ' + medDose : ''}</div>`;
    });
    
    display.innerHTML = html;
    display.style.display = 'block';
    editButton.style.display = 'block';
    
    // Save state
    saveChecklistState();
}

// Open the post-insertion medication selector modal
function openPostMedicationSelector() {
    document.getElementById('postMedicationModal').style.display = 'block';
}

// Set up the narrative form functionality
function setupNarrativeForm() {
    const generateBtn = document.getElementById('generate-narrative');
    const narrativeFormModal = document.getElementById('narrativeFormModal');
    const narrativeBtn = document.getElementById('generateNarrativeBtn');
    const narrativeModal = document.getElementById('narrativeModal');
    const copyButton = document.getElementById('copy-narrative');
    
    // Open narrative form
    generateBtn.addEventListener('click', function() {
        // Check if all sections are completed
        const sections = document.querySelectorAll('.checklist-section');
        let allCompleted = true;
        
        sections.forEach(section => {
            if (!section.classList.contains('completed')) {
                allCompleted = false;
            }
        });
        
        if (!allCompleted) {
            const confirm = window.confirm('Not all checklist sections are complete. Do you want to continue anyway?');
            if (!confirm) return;
        }
        
        // Populate form with saved data
        populateNarrativeForm();
        
        // Show the form
        narrativeFormModal.style.display = 'block';
    });
    
    // Generate narrative button
    narrativeBtn.addEventListener('click', function() {
        const narrative = generateNarrative();
        document.getElementById('narrative-text').innerHTML = narrative;
        
        narrativeFormModal.style.display = 'none';
        narrativeModal.style.display = 'block';
    });
    
    // Copy narrative button
    copyButton.addEventListener('click', function() {
        const text = document.getElementById('narrative-text').innerText;
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
        if (this.checked) {
            compCheckboxes.forEach(cb => {
                cb.checked = false;
                cb.disabled = true;
            });
        } else {
            compCheckboxes.forEach(cb => {
                cb.disabled = false;
            });
        }
    });
    
    document.querySelectorAll('.complication-check').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('comp-none').checked = false;
            }
        });
    });
}

// Populate the narrative form with saved data
function populateNarrativeForm() {
    // Get the size if available
    const sizeDisplay = document.getElementById('selected-size-display');
    if (sizeDisplay.textContent) {
        const size = sizeDisplay.getAttribute('data-size');
        document.getElementById('size-selector-container').innerHTML = `
            <label>Selected Size</label>
            <div class="selected-size-info">I-Gel Size ${size} (${sizeDisplay.getAttribute('data-weight')})</div>
        `;
    }
    
    // Get medications if available
    const medsDisplay = document.getElementById('selected-meds-display');
    if (medsDisplay.textContent) {
        const medications = Array.from(medsDisplay.querySelectorAll('.selected-med'));
        if (medications.length > 0) {
            let html = '<h3>Medications</h3><div class="selected-medications">';
            medications.forEach(med => {
                html += `<div>${med.textContent}</div>`;
            });
            html += '</div>';
            document.getElementById('selected-medications-container').innerHTML = html;
        }
    }
    
    // Get post-insertion medications if available
    const postMedsDisplay = document.getElementById('selected-post-meds-display');
    if (postMedsDisplay.textContent) {
        const medications = Array.from(postMedsDisplay.querySelectorAll('.selected-med'));
        if (medications.length > 0) {
            let html = '<h3>Post-Insertion Medications</h3><div class="selected-medications">';
            medications.forEach(med => {
                html += `<div>${med.textContent}</div>`;
            });
            html += '</div>';
            document.getElementById('selected-post-medications-container').innerHTML = html;
        }
    }
}

// Generate the narrative text
function generateNarrative() {
    // Get form values
    const indication = document.getElementById('sgaIndication').value;
    const deviceType = document.getElementById('deviceType').value;
    const attempts = document.getElementById('attempts').value;
    const gastricTube = document.getElementById('gastricTube').value;
    const ventilation = document.getElementById('ventilation').value;
    
    // Get size
    const sizeDisplay = document.getElementById('selected-size-display');
    const size = sizeDisplay.getAttribute('data-size') || 'appropriate';
    
    // Get complications
    let complications = [];
    if (!document.getElementById('comp-none').checked) {
        document.querySelectorAll('.complication-check:checked').forEach(comp => {
            complications.push(comp.parentElement.querySelector('span').textContent);
        });
    }
    
    // Get medications
    let medications = [];
    document.querySelectorAll('#selected-medications-container .selected-medications div').forEach(med => {
        medications.push(med.textContent);
    });
    
    // Get post-insertion medications
    let postMedications = [];
    document.querySelectorAll('#selected-post-medications-container .selected-medications div').forEach(med => {
        postMedications.push(med.textContent);
    });
    
    // Create narrative
    let narrative = `<p><strong>Supraglottic Airway Placement Documentation:</strong></p>`;
    
    // Procedure narrative
    narrative += `<p>Patient presented with ${indication} requiring advanced airway management. `;
    narrative += `After pre-oxygenation and preparation of equipment, a size ${size} ${deviceType} was selected as the most appropriate device for this patient. `;
    
    if (medications.length > 0 && !medications[0].includes('No Sedation')) {
        narrative += `Patient was sedated with ${medications.join(', ')}. `;
    }
    
    narrative += `The ${deviceType} was inserted using proper technique with ${attempts} attempt(s). `;
    narrative += `Proper placement was confirmed via bilateral chest rise, bilateral breath sounds, absence of gastric sounds, and positive ETCO2 waveform. `;
    
    if (gastricTube === 'yes') {
        narrative += `A gastric tube was placed through the dedicated channel to decompress the stomach and reduce aspiration risk. `;
    }
    
    narrative += `The device was secured and ${ventilation} ventilation was established.</p>`;
    
    // Post-procedure management
    narrative += `<p><strong>Post-insertion Management:</strong> `;
    
    if (postMedications.length > 0 && !postMedications[0].includes('No Ongoing')) {
        narrative += `Ongoing sedation maintained with ${postMedications.join(', ')}. `;
    }
    
    narrative += `Continuous monitoring of SpO2, ETCO2, respiratory parameters, and vital signs was maintained. `;
    
    if (complications.length > 0) {
        narrative += `Complications encountered: ${complications.join(', ')}. Each was addressed appropriately. `;
    } else {
        narrative += `No complications were encountered during the procedure. `;
    }
    
    narrative += `Patient tolerated the procedure well.</p>`;
    
    return narrative;
}

// Show info modal with provided text
function showInfoModal(text) {
    const modal = document.getElementById('infoModal');
    document.getElementById('modalInfo').innerHTML = text;
    modal.style.display = 'block';
}

// Set up action buttons
function setupActions() {
    // Reset button
    document.getElementById('reset-checklist').addEventListener('click', function() {
        if (confirm('Are you sure you want to reset the checklist? All progress will be lost.')) {
            // Clear localStorage
            localStorage.removeItem('igelChecklist');
            
            // Uncheck all checkboxes
            document.querySelectorAll('.checkbox-input').forEach(cb => {
                cb.checked = false;
            });
            
            // Clear displays
            document.getElementById('selected-size-display').innerHTML = '';
            document.getElementById('selected-size-display').style.display = 'none';
            document.getElementById('edit-size-btn').style.display = 'none';
            
            document.getElementById('selected-meds-display').innerHTML = '';
            document.getElementById('selected-meds-display').style.display = 'none';
            document.getElementById('edit-meds-btn').style.display = 'none';
            
            document.getElementById('selected-post-meds-display').innerHTML = '';
            document.getElementById('selected-post-meds-display').style.display = 'none';
            document.getElementById('edit-post-meds-btn').style.display = 'none';
            
            // Update section status
            updateSectionStatus();
        }
    });
}
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>