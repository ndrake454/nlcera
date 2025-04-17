<?php
/**
 * CPAP Checklist Tool - Optimized for Mobile/Touch
 * 
 * Place this file in: /tools/cpap.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'CPAP Checklist';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="cpap-tool-container">
    <div class="cpap-header">
        <h1>Continuous Positive Airway Pressure (CPAP) Checklist</h1>
        <p>Use this interactive checklist to ensure safe and effective CPAP application. Click on the <i class="ti ti-info-circle"></i> icons for detailed information about each step.</p>
    </div>
    
    <!-- Assessment Section -->
    <div class="checklist-section" id="section-assess">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-stethoscope"></i>
                <h2>1. Patient Assessment</h2>
            </div>
            <span class="section-status"><span class="completed-count">0</span>/<span class="total-count">7</span></span>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Verify Indications for CPAP</span>
                        <input type="checkbox" class="checkbox-input" id="check1" data-section="assess">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Verify patient has at least TWO of the following: respiratory distress with wheezing, CHF/pulmonary edema, rales (crackles), dyspnea with SpO2 < 90% despite O2, inability to speak full sentences, accessory muscle use, respiratory rate > 24/min despite O2, or diminished tidal volume.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
                <div class="selected-indicators-display" id="selected-indicators-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-indicators-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Rule Out Contraindications</span>
                        <input type="checkbox" class="checkbox-input" id="check2" data-section="assess">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Confirm NONE of the following are present: respiratory or cardiac arrest, systolic BP < 90mmHg, lack of airway protective reflexes, significant altered level of consciousness, vomiting or active upper GI bleed, suspected pneumothorax, trauma, or patient size/anatomy that prevents adequate mask seal.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Check Baseline Vitals</span>
                        <input type="checkbox" class="checkbox-input" id="check3" data-section="assess">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Measure and document baseline vital signs: BP, HR, RR, SpO2, and ETCO2. These will be important to compare during and after CPAP therapy.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
                <div class="selected-vitals-display" id="selected-vitals-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-vitals-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Position Patient Properly</span>
                        <input type="checkbox" class="checkbox-input" id="check4" data-section="assess">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Place patient in a seated position to maximize lung expansion and breathing efficiency. A seated or semi-Fowler's position helps reduce work of breathing.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Explain Procedure to Patient</span>
                        <input type="checkbox" class="checkbox-input" id="check5" data-section="assess">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Clearly explain the CPAP procedure to the patient, including what they will experience, expected benefits, and how to communicate while wearing the mask. Patient cooperation is essential for successful therapy.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Select Appropriate Mask Size</span>
                        <input type="checkbox" class="checkbox-input" id="check6" data-section="assess">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Choose the correct mask size for the patient. The mask should cover the nose and mouth without extending over the eyes or beyond the chin. Proper mask sizing is crucial for creating an effective seal.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Prepare CPAP Equipment</span>
                        <input type="checkbox" class="checkbox-input" id="check7" data-section="assess">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Assemble and check CPAP equipment. Ensure oxygen source is connected, circuit is properly assembled, and pressure settings are ready to be adjusted. Prepare in-line nebulizer if medication administration may be needed.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- CPAP Application Section -->
    <div class="checklist-section" id="section-apply">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-mask"></i>
                <h2>2. CPAP Application</h2>
            </div>
            <span class="section-status"><span class="completed-count">0</span>/<span class="total-count">4</span></span>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Apply CPAP Mask</span>
                        <input type="checkbox" class="checkbox-input" id="check8" data-section="apply">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Place the mask on the patient's face, covering nose and mouth. Apply head straps to secure the mask, progressively tightening as tolerated to minimize air leaks. Ensure patient comfort while maintaining a good seal.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Start with Lowest Effective Pressure</span>
                        <input type="checkbox" class="checkbox-input" id="check9" data-section="apply">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Begin with the lowest continuous pressure that appears effective (typically 5 cmH2O). Assess patient comfort and work of breathing at initial settings.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
                <div class="selected-settings-display" id="selected-settings-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-settings-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Check for Mask Leaks</span>
                        <input type="checkbox" class="checkbox-input" id="check10" data-section="apply">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Assess for air leaks around the mask seal. Adjust mask and straps as needed to minimize leaks while maintaining patient comfort. Major leaks will reduce therapy effectiveness.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Adjust Settings as Needed</span>
                        <input type="checkbox" class="checkbox-input" id="check11" data-section="apply">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Titrate pressure settings following manufacturer instructions to achieve the most stable respiratory status. Adjust FiO2 as needed based on SpO2 readings. Some fixed pressure CPAP devices may only deliver up to 30% oxygen - consider adding supplemental oxygen if no improvement in oxygenation.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monitoring Section -->
    <div class="checklist-section" id="section-monitor">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-heartbeat"></i>
                <h2>3. Ongoing Monitoring</h2>
            </div>
            <span class="section-status"><span class="completed-count">0</span>/<span class="total-count">5</span></span>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Monitor Vital Signs Every 5 Minutes</span>
                        <input type="checkbox" class="checkbox-input" id="check12" data-section="monitor">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Continuously monitor patient and record vital signs (BP, HR, RR, SpO2, ETCO2) every 5 minutes. Document trends in vital signs to assess response to therapy.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
                <div class="selected-monitor-vitals-display" id="selected-monitor-vitals-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-monitor-vitals-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Assess for Improvement Signs</span>
                        <input type="checkbox" class="checkbox-input" id="check13" data-section="monitor">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Evaluate for signs of improvement: reduced dyspnea, improved ability to speak, decreased respiratory and heart rates, increased SpO2, stabilized blood pressure, appropriate ETCO2 values/waveforms, and increased tidal volume.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Monitor for Deterioration Signs</span>
                        <input type="checkbox" class="checkbox-input" id="check14" data-section="monitor">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Watch for signs of deterioration: decreased level of consciousness, sustained/increased heart rate or respiratory rate, decreased blood pressure, sustained low/decreasing SpO2, rising ETCO2 levels or evidence of ventilatory failure, or no improvement in tidal volume.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Check Equipment Function</span>
                        <input type="checkbox" class="checkbox-input" id="check15" data-section="monitor">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Regularly verify equipment is functioning correctly. Check for proper gas flow, intact circuit connections, no blockages in tubing, and that pressure is maintained. If patient deteriorates, rule out equipment failure first.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Assess Need for Further Interventions</span>
                        <input type="checkbox" class="checkbox-input" id="check16" data-section="monitor">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="If patient deteriorates despite CPAP: Consider need for endotracheal intubation, assess for possible pneumothorax requiring decompression, evaluate for hypotension due to reduced preload from positive pressure ventilation, or determine if in-line nebulized medications are needed.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="tool-actions">
        <div class="action-warning">
            <small><em>This is not intended to be a comprehensive guide for CPAP administration</em></small>
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

<!-- Indicators Selection Modal -->
<div class="tool-modal" id="indicatorsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>CPAP Indications</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <p class="modal-text">Select all indications present (at least 2 required):</p>
            
            <div class="indicators-list">
                <div class="indicator-item" data-indicator="wheezing">
                    <div class="indicator-name">Wheezing</div>
                    <div class="indicator-note">Audible wheezing on exhalation</div>
                    <input type="checkbox" id="ind-wheezing">
                </div>
                
                <div class="indicator-item" data-indicator="pulmonary-edema">
                    <div class="indicator-name">CHF/Pulmonary Edema</div>
                    <div class="indicator-note">Signs of fluid in lungs</div>
                    <input type="checkbox" id="ind-pulmonary-edema">
                </div>
                
                <div class="indicator-item" data-indicator="rales">
                    <div class="indicator-name">Rales (Crackles)</div>
                    <div class="indicator-note">Crackles heard on auscultation</div>
                    <input type="checkbox" id="ind-rales">
                </div>
                
                <div class="indicator-item" data-indicator="hypoxia">
                    <div class="indicator-name">Dyspnea with Hypoxia</div>
                    <div class="indicator-note">SpO2 < 90% despite O2</div>
                    <input type="checkbox" id="ind-hypoxia">
                </div>
                
                <div class="indicator-item" data-indicator="verbal-impairment">
                    <div class="indicator-name">Verbal Impairment</div>
                    <div class="indicator-note">Cannot speak in full sentences</div>
                    <input type="checkbox" id="ind-verbal-impairment">
                </div>
                
                <div class="indicator-item" data-indicator="accessory-muscles">
                    <div class="indicator-name">Accessory Muscle Use</div>
                    <div class="indicator-note">Using neck/chest muscles to breathe</div>
                    <input type="checkbox" id="ind-accessory-muscles">
                </div>
                
                <div class="indicator-item" data-indicator="tachypnea">
                    <div class="indicator-name">Tachypnea</div>
                    <div class="indicator-note">RR > 24/min despite O2</div>
                    <input type="checkbox" id="ind-tachypnea">
                </div>
                
                <div class="indicator-item" data-indicator="diminished-volume">
                    <div class="indicator-name">Diminished Tidal Volume</div>
                    <div class="indicator-note">Shallow breathing</div>
                    <input type="checkbox" id="ind-diminished-volume">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-button primary-button" id="save-indicators">Save Selections</button>
        </div>
    </div>
</div>

<!-- Vitals Entry Modal -->
<div class="tool-modal" id="vitalsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Baseline Vital Signs</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="vitals-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="vital-bp">Blood Pressure</label>
                        <input type="text" id="vital-bp" class="form-input" placeholder="e.g., 120/80">
                    </div>
                    <div class="form-group">
                        <label for="vital-hr">Heart Rate</label>
                        <input type="number" id="vital-hr" class="form-input" placeholder="BPM">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="vital-rr">Respiratory Rate</label>
                        <input type="number" id="vital-rr" class="form-input" placeholder="Breaths/min">
                    </div>
                    <div class="form-group">
                        <label for="vital-spo2">SpO2</label>
                        <div class="input-group">
                            <input type="number" id="vital-spo2" class="form-input" placeholder="%">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="vital-etco2">ETCO2 (if available)</label>
                        <div class="input-group">
                            <input type="number" id="vital-etco2" class="form-input" placeholder="mmHg">
                            <span class="input-group-text">mmHg</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-button primary-button" id="save-vitals">Save Vitals</button>
        </div>
    </div>
</div>

<!-- CPAP Settings Modal -->
<div class="tool-modal" id="settingsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>CPAP Settings</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="settings-form">
                <div class="form-group">
                    <label for="setting-pressure">Initial CPAP Pressure</label>
                    <div class="input-group">
                        <input type="number" id="setting-pressure" class="form-input" placeholder="5-10" value="5">
                        <span class="input-group-text">cmH2O</span>
                    </div>
                    <small class="form-text">Typical starting range: 5-10 cmH2O</small>
                </div>
                
                <div class="form-group">
                    <label for="setting-fio2">FiO2 Setting</label>
                    <div class="input-group">
                        <input type="number" id="setting-fio2" class="form-input" placeholder="21-100" value="100">
                        <span class="input-group-text">%</span>
                    </div>
                    <small class="form-text">Oxygen percentage delivered</small>
                </div>
                
                <div class="form-group">
                    <label for="setting-device">CPAP Device Type</label>
                    <select id="setting-device" class="form-select">
                        <option value="fixed-pressure">Fixed Pressure Device</option>
                        <option value="adjustable-pressure">Adjustable Pressure Device</option>
                        <option value="blender">Flow-Restricted, Oxygen-Powered Device (e.g., Boussignac)</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-button primary-button" id="save-settings">Save Settings</button>
        </div>
    </div>
</div>

<!-- Monitor Vitals Modal -->
<div class="tool-modal" id="monitorVitalsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Current Vital Signs</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="vitals-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="monitor-bp">Blood Pressure</label>
                        <input type="text" id="monitor-bp" class="form-input" placeholder="e.g., 120/80">
                    </div>
                    <div class="form-group">
                        <label for="monitor-hr">Heart Rate</label>
                        <input type="number" id="monitor-hr" class="form-input" placeholder="BPM">
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="monitor-rr">Respiratory Rate</label>
                        <input type="number" id="monitor-rr" class="form-input" placeholder="Breaths/min">
                    </div>
                    <div class="form-group">
                        <label for="monitor-spo2">SpO2</label>
                        <div class="input-group">
                            <input type="number" id="monitor-spo2" class="form-input" placeholder="%">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="monitor-etco2">ETCO2 (if available)</label>
                        <div class="input-group">
                            <input type="number" id="monitor-etco2" class="form-input" placeholder="mmHg">
                            <span class="input-group-text">mmHg</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="response-assessment">Patient Response</label>
                    <select id="response-assessment" class="form-select">
                        <option value="improving">Improving</option>
                        <option value="stable">Stable - No Change</option>
                        <option value="deteriorating">Deteriorating</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-button primary-button" id="save-monitor-vitals">Save Vitals</button>
        </div>
    </div>
</div>

<!-- Documentation Form Modal -->
<div class="tool-modal" id="narrativeFormModal">
    <div class="modal-content large-modal">
        <div class="modal-header">
            <h2>Enter Patient Details</h2>
            <button class="close-modal">&times;</button>
        </div>
        <div class="modal-body">
            <form id="patientInfoForm" class="narrative-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="patientIndication">Primary Indication</label>
                        <select class="form-select" id="patientIndication">
                            <option value="respiratory distress">Respiratory Distress</option>
                            <option value="pulmonary edema">Pulmonary Edema/CHF</option>
                            <option value="hypoxia">Hypoxia</option>
                            <option value="copd">COPD Exacerbation</option>
                            <option value="asthma">Asthma</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="patientDuration">CPAP Duration</label>
                        <div class="input-group">
                            <input type="number" class="form-input" id="patientDuration" placeholder="Minutes" value="15">
                            <span class="input-group-text">min</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-section" id="indicators-container">
                    <!-- This will be populated with selected indicators -->
                </div>
                
                <div class="form-section" id="vital-signs-container">
                    <!-- This will be populated with vital signs -->
                </div>
                
                <div class="form-section" id="cpap-settings-container">
                    <!-- This will be populated with CPAP settings -->
                </div>
                
                <div class="form-section">
                    <h3>Response to Treatment</h3>
                    <div class="form-group">
                        <select class="form-select" id="patientResponse">
                            <option value="significant improvement">Significant Improvement</option>
                            <option value="moderate improvement">Moderate Improvement</option>
                            <option value="slight improvement">Slight Improvement</option>
                            <option value="no change">No Change</option>
                            <option value="deterioration">Deterioration</option>
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
                            <input type="checkbox" class="complication-check" id="comp-mask-leak">
                            <span>Mask leak issues</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-pressure-intolerance">
                            <span>Pressure intolerance</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-claustrophobia">
                            <span>Claustrophobia</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-hypotension">
                            <span>Hypotension</span>
                        </label>
                        <label class="comp-checkbox complication-check-label">
                            <input type="checkbox" class="complication-check" id="comp-vomiting">
                            <span>Vomiting</span>
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
.cpap-tool-container * {
    box-sizing: border-box;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

.cpap-tool-container {
    max-width: 800px;
    margin: 0 auto 3rem;
    overflow: hidden;
    padding-bottom: 20px;
}

.cpap-header {
    background-color: #106e9e;
    color: white;
    padding: 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
}

.cpap-header h1 {
    margin: 0 0 10px;
    font-size: 28px;
    font-weight: 600;
}

.cpap-header p {
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

.checklist-item:last-child {
    border-bottom: none;
}

.checklist-item:hover {
    background-color: #f8f9fa;
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
}

.checkbox-container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkbox-label {
    padding: 2px 0;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 24px;
    width: 24px;
    background-color: #e9ecef;
    border: 2px solid #ced4da;
    border-radius: 4px;
    transition: all 0.2s;
}

.checkbox-container:hover input ~ .checkmark {
    background-color: #d1e7f6;
    border-color: #106e9e;
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
    top: 3px;
    width: 6px;
    height: 12px;
    border: solid white;
    border-width: 0 3px 3px 0;
    transform: rotate(45deg);
}

/* Info Button */
.info-btn {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #106e9e;
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-left: auto;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
}

.info-btn:hover {
    background-color: #0c5578;
    transform: scale(1.1);
}

.info-btn i {
    font-size: 16px;
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

/* Display for selected indicators, vitals, and settings */
.selected-indicators-display,
.selected-vitals-display,
.selected-settings-display,
.selected-monitor-vitals-display {
    margin-top: 5px;
    margin-left: 35px;
    padding: 5px 10px;
    background-color: #f0f6fa;
    border-radius: 5px;
    font-size: 14px;
    display: none;
    width: calc(100% - 80px);
}

.selected-indicators-display.active,
.selected-vitals-display.active,
.selected-settings-display.active,
.selected-monitor-vitals-display.active {
    display: block;
}

.selected-indicator-item,
.selected-vital-item,
.selected-setting-item,
.selected-monitor-vital-item {
    display: flex;
    align-items: center;
    margin-bottom: 3px;
}

.selected-indicator-item i,
.selected-vital-item i,
.selected-setting-item i,
.selected-monitor-vital-item i {
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

/* Info Modal Styling */
.info-content {
    line-height: 1.6;
    font-size: 16px;
}

.info-content p {
    margin-bottom: 1em;
}

.info-content strong {
    color: #0c5578;
}

.modal-text {
    margin-bottom: 15px;
}

/* Indicators Modal Styling */
.indicators-list {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 15px;
}

.indicator-item {
    background-color: white;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    padding: 12px;
    transition: all 0.2s;
    cursor: pointer;
    position: relative;
}

.indicator-item:hover {
    border-color: #adb5bd;
    background-color: #f8f9fa;
}

.indicator-item.selected {
    border-color: #106e9e;
    background-color: #e3f2fd;
}

.indicator-item.selected:after {
    content: "\2713"; /* Checkmark */
    position: absolute;
    top: 10px;
    right: 10px;
    color: #106e9e;
    font-weight: bold;
    font-size: 16px;
}

.indicator-name {
    font-weight: 600;
    margin-bottom: 5px;
}

.indicator-note {
    font-size: 12px;
    color: #6c757d;
}

.indicator-item input[type="checkbox"] {
    position: absolute;
    opacity: 0;
}

/* Vitals Form Styling */
.vitals-form, .settings-form {
    width: 100%;
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
    background-color: #e9ecef;
    border: 1px solid #ced4da;
    border-left: none;
    padding: 10px;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
    color: #495057;
    font-size: 16px;
}

.form-text {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #6c757d;
}

/* Narrative Form Styling */
.narrative-form {
    max-width: 100%;
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
    
    .indicators-list {
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
    
    .info-btn {
        width: 36px;
        height: 36px;
    }
    
    .info-btn i {
        font-size: 18px;
    }
    
    .action-button {
        padding: 12px 20px;
        font-size: 16px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Main elements
    const checkboxes = document.querySelectorAll('.checkbox-input');
    const indicatorsCheckbox = document.getElementById('check1');
    const vitalsCheckbox = document.getElementById('check3');
    const settingsCheckbox = document.getElementById('check9');
    const monitorVitalsCheckbox = document.getElementById('check12');
    let selectedIndicators = [];
    let baselineVitals = {};
    let cpapSettings = {};
    let monitorVitals = {};
    let previousSessionExists = false;
    
    // Calculate total checkboxes per section
    const sections = ['assess', 'apply', 'monitor'];
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
    
    // Toggle checked state for list items when checkbox is clicked
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const listItem = this.closest('.checklist-item');
            if (this.checked) {
                listItem.classList.add('checked');
                
                // Special handlers for certain checkboxes
                if (this === indicatorsCheckbox) {
                    showIndicatorsModal();
                }
                
                if (this === vitalsCheckbox) {
                    showVitalsModal();
                }
                
                if (this === settingsCheckbox) {
                    showSettingsModal();
                }
                
                if (this === monitorVitalsCheckbox) {
                    showMonitorVitalsModal();
                }
            } else {
                listItem.classList.remove('checked');
                
                // Clear display if unchecked
                if (this === indicatorsCheckbox) {
                    clearSelectedIndicators();
                    document.getElementById('edit-indicators-btn').style.display = 'none';
                }
                
                if (this === vitalsCheckbox) {
                    clearBaselineVitals();
                    document.getElementById('edit-vitals-btn').style.display = 'none';
                }
                
                if (this === settingsCheckbox) {
                    clearCpapSettings();
                    document.getElementById('edit-settings-btn').style.display = 'none';
                }
                
                if (this === monitorVitalsCheckbox) {
                    clearMonitorVitals();
                    document.getElementById('edit-monitor-vitals-btn').style.display = 'none';
                }
            }
            
            updateProgress();
        });
    });
    
    // Add click handlers for edit buttons
    document.getElementById('edit-indicators-btn').addEventListener('click', function() {
        showIndicatorsModal();
    });
    
    document.getElementById('edit-vitals-btn').addEventListener('click', function() {
        showVitalsModal();
    });
    
    document.getElementById('edit-settings-btn').addEventListener('click', function() {
        showSettingsModal();
    });
    
    document.getElementById('edit-monitor-vitals-btn').addEventListener('click', function() {
        showMonitorVitalsModal();
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
    
    // Show info modal when info button is clicked
    document.querySelectorAll('.info-btn').forEach(button => {
        button.addEventListener('click', function() {
            const infoText = this.getAttribute('data-info');
            const itemTitle = this.previousElementSibling.querySelector('.checkbox-label').textContent;
            
            // Format the info text
            let formattedInfo = infoText;
            
            // Wrap important terms in <strong> tags
            const importantTerms = [
                'SpO2', 'ETCO2', 'FiO2', 'CPAP', 'RR', 'BP', 'HR',
                'dyspnea', 'tachypnea', 'hypoxia', 'pneumothorax', 'accessory muscle',
                'tidal volume', 'hypotension', 'ventilatory failure'
            ];
            
            importantTerms.forEach(term => {
                const regex = new RegExp(`\\b${term}\\b`, 'g');
                formattedInfo = formattedInfo.replace(regex, `<strong>${term}</strong>`);
            });
            
            // Set modal content
            document.getElementById('modalTitle').textContent = itemTitle;
            document.getElementById('modalInfo').innerHTML = formattedInfo;
            
            // Show modal
            openModal('infoModal');
        });
    });
    
    // Indicators Modal
    function showIndicatorsModal() {
        // Prepare modal by pre-selecting previously selected indicators
        document.querySelectorAll('#indicatorsModal .indicator-item').forEach(item => {
            item.classList.remove('selected');
            const indicator = item.dataset.indicator;
            
            // Check if this indicator was previously selected
            const isSelected = selectedIndicators.includes(formatIndicatorName(indicator));
            if (isSelected) {
                item.classList.add('selected');
                document.getElementById(`ind-${indicator}`).checked = true;
            } else {
                document.getElementById(`ind-${indicator}`).checked = false;
            }
        });
        
        openModal('indicatorsModal');
    }
    
    // Vitals Modal
    function showVitalsModal() {
        // Populate with previous values if any
        if (baselineVitals.bp) document.getElementById('vital-bp').value = baselineVitals.bp;
        if (baselineVitals.hr) document.getElementById('vital-hr').value = baselineVitals.hr;
        if (baselineVitals.rr) document.getElementById('vital-rr').value = baselineVitals.rr;
        if (baselineVitals.spo2) document.getElementById('vital-spo2').value = baselineVitals.spo2;
        if (baselineVitals.etco2) document.getElementById('vital-etco2').value = baselineVitals.etco2;
        
        openModal('vitalsModal');
    }
    
    // CPAP Settings Modal
    function showSettingsModal() {
        // Populate with previous values if any
        if (cpapSettings.pressure) document.getElementById('setting-pressure').value = cpapSettings.pressure;
        if (cpapSettings.fio2) document.getElementById('setting-fio2').value = cpapSettings.fio2;
        if (cpapSettings.device) document.getElementById('setting-device').value = cpapSettings.device;
        
        openModal('settingsModal');
    }
    
    // Monitor Vitals Modal
    function showMonitorVitalsModal() {
        // Populate with previous values if any
        if (monitorVitals.bp) document.getElementById('monitor-bp').value = monitorVitals.bp;
        if (monitorVitals.hr) document.getElementById('monitor-hr').value = monitorVitals.hr;
        if (monitorVitals.rr) document.getElementById('monitor-rr').value = monitorVitals.rr;
        if (monitorVitals.spo2) document.getElementById('monitor-spo2').value = monitorVitals.spo2;
        if (monitorVitals.etco2) document.getElementById('monitor-etco2').value = monitorVitals.etco2;
        if (monitorVitals.response) document.getElementById('response-assessment').value = monitorVitals.response;
        
        // Pre-fill with baseline vitals if first time entering monitor vitals
        if (!monitorVitals.bp && baselineVitals.bp) document.getElementById('monitor-bp').value = baselineVitals.bp;
        if (!monitorVitals.hr && baselineVitals.hr) document.getElementById('monitor-hr').value = baselineVitals.hr;
        if (!monitorVitals.rr && baselineVitals.rr) document.getElementById('monitor-rr').value = baselineVitals.rr;
        if (!monitorVitals.spo2 && baselineVitals.spo2) document.getElementById('monitor-spo2').value = baselineVitals.spo2;
        if (!monitorVitals.etco2 && baselineVitals.etco2) document.getElementById('monitor-etco2').value = baselineVitals.etco2;
        
        openModal('monitorVitalsModal');
    }
    
    // Make indicator items clickable
    document.querySelectorAll('.indicator-item').forEach(item => {
        item.addEventListener('click', function() {
            const checkbox = this.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;
            this.classList.toggle('selected', checkbox.checked);
        });
    });
    
    // Format indicator names
    function formatIndicatorName(indicator) {
        const names = {
            'wheezing': 'Wheezing',
            'pulmonary-edema': 'CHF/Pulmonary Edema',
            'rales': 'Rales (Crackles)',
            'hypoxia': 'Dyspnea with Hypoxia',
            'verbal-impairment': 'Verbal Impairment',
            'accessory-muscles': 'Accessory Muscle Use',
            'tachypnea': 'Tachypnea (RR>24)',
            'diminished-volume': 'Diminished Tidal Volume'
        };
        
        return names[indicator] || indicator.charAt(0).toUpperCase() + indicator.slice(1).replace(/-/g, ' ');
    }
    
    // Save selected indicators
    document.getElementById('save-indicators').addEventListener('click', function() {
        const selected = document.querySelectorAll('#indicatorsModal input[type="checkbox"]:checked');
        
        if (selected.length < 2) {
            alert('Please select at least 2 indications for CPAP therapy.');
            return;
        }
        
        selectedIndicators = [];
        
        selected.forEach(checkbox => {
            const indicator = checkbox.id.replace('ind-', '');
            selectedIndicators.push(formatIndicatorName(indicator));
        });
        
        updateSelectedIndicatorsDisplay();
        closeModal('indicatorsModal');
        
        // Show edit button if indicators selected
        document.getElementById('edit-indicators-btn').style.display = selectedIndicators.length > 0 ? 'block' : 'none';
    });
    
    // Save vitals
    document.getElementById('save-vitals').addEventListener('click', function() {
        const bp = document.getElementById('vital-bp').value;
        const hr = document.getElementById('vital-hr').value;
        const rr = document.getElementById('vital-rr').value;
        const spo2 = document.getElementById('vital-spo2').value;
        const etco2 = document.getElementById('vital-etco2').value;
        
        if (!bp || !hr || !rr || !spo2) {
            alert('Please enter all required vital signs (BP, HR, RR, SpO2).');
            return;
        }
        
        baselineVitals = {
            bp: bp,
            hr: hr,
            rr: rr,
            spo2: spo2,
            etco2: etco2 || ''
        };
        
        updateBaselineVitalsDisplay();
        closeModal('vitalsModal');
        
        // Show edit button
        document.getElementById('edit-vitals-btn').style.display = 'block';
    });
    
    // Save CPAP settings
    document.getElementById('save-settings').addEventListener('click', function() {
        const pressure = document.getElementById('setting-pressure').value;
        const fio2 = document.getElementById('setting-fio2').value;
        const device = document.getElementById('setting-device').value;
        
        if (!pressure || !fio2) {
            alert('Please enter the CPAP pressure and FiO2 settings.');
            return;
        }
        
        cpapSettings = {
            pressure: pressure,
            fio2: fio2,
            device: device
        };
        
        updateCpapSettingsDisplay();
        closeModal('settingsModal');
        
        // Show edit button
        document.getElementById('edit-settings-btn').style.display = 'block';
    });
    
    // Save monitor vitals
    document.getElementById('save-monitor-vitals').addEventListener('click', function() {
        const bp = document.getElementById('monitor-bp').value;
        const hr = document.getElementById('monitor-hr').value;
        const rr = document.getElementById('monitor-rr').value;
        const spo2 = document.getElementById('monitor-spo2').value;
        const etco2 = document.getElementById('monitor-etco2').value;
        const response = document.getElementById('response-assessment').value;
        
        if (!bp || !hr || !rr || !spo2) {
            alert('Please enter all required vital signs (BP, HR, RR, SpO2).');
            return;
        }
        
        monitorVitals = {
            bp: bp,
            hr: hr,
            rr: rr,
            spo2: spo2,
            etco2: etco2 || '',
            response: response
        };
        
        updateMonitorVitalsDisplay();
        closeModal('monitorVitalsModal');
        
        // Show edit button
        document.getElementById('edit-monitor-vitals-btn').style.display = 'block';
    });
    
    function updateSelectedIndicatorsDisplay() {
        const display = document.getElementById('selected-indicators-display');
        if (selectedIndicators.length > 0) {
            let html = '';
            selectedIndicators.forEach(indicator => {
                html += `<div class="selected-indicator-item"><i class="ti ti-check"></i>${indicator}</div>`;
            });
            display.innerHTML = html;
            display.classList.add('active');
            
            // Update documentation form
            updateDocumentationIndicators();
        } else {
            display.innerHTML = '';
            display.classList.remove('active');
        }
    }
    
    function updateBaselineVitalsDisplay() {
        const display = document.getElementById('selected-vitals-display');
        if (baselineVitals.bp) {
            let html = '';
            html += `<div class="selected-vital-item"><i class="ti ti-heart-rate-monitor"></i>BP: ${baselineVitals.bp}</div>`;
            html += `<div class="selected-vital-item"><i class="ti ti-heart"></i>HR: ${baselineVitals.hr} BPM</div>`;
            html += `<div class="selected-vital-item"><i class="ti ti-lungs"></i>RR: ${baselineVitals.rr} breaths/min</div>`;
            html += `<div class="selected-vital-item"><i class="ti ti-heartbeat"></i>SpO2: ${baselineVitals.spo2}%</div>`;
            
            if (baselineVitals.etco2) {
                html += `<div class="selected-vital-item"><i class="ti ti-wave-sine"></i>ETCO2: ${baselineVitals.etco2} mmHg</div>`;
            }
            
            display.innerHTML = html;
            display.classList.add('active');
            
            // Update documentation form
            updateDocumentationVitals();
        } else {
            display.innerHTML = '';
            display.classList.remove('active');
        }
    }
    
    function updateCpapSettingsDisplay() {
        const display = document.getElementById('selected-settings-display');
        if (cpapSettings.pressure) {
            let html = '';
            html += `<div class="selected-setting-item"><i class="ti ti-gauge"></i>Pressure: ${cpapSettings.pressure} cmH2O</div>`;
            html += `<div class="selected-setting-item"><i class="ti ti-droplet"></i>FiO2: ${cpapSettings.fio2}%</div>`;
            
            const deviceTypes = {
                'fixed-pressure': 'Fixed Pressure Device',
                'adjustable-pressure': 'Adjustable Pressure Device',
                'blender': 'Flow-Restricted, Oxygen-Powered Device'
            };
            
            html += `<div class="selected-setting-item"><i class="ti ti-device-medical"></i>Device: ${deviceTypes[cpapSettings.device] || cpapSettings.device}</div>`;
            
            display.innerHTML = html;
            display.classList.add('active');
            
            // Update documentation form
            updateDocumentationSettings();
        } else {
            display.innerHTML = '';
            display.classList.remove('active');
        }
    }
    
    function updateMonitorVitalsDisplay() {
        const display = document.getElementById('selected-monitor-vitals-display');
        if (monitorVitals.bp) {
            let html = '';
            html += `<div class="selected-monitor-vital-item"><i class="ti ti-heart-rate-monitor"></i>BP: ${monitorVitals.bp}</div>`;
            html += `<div class="selected-monitor-vital-item"><i class="ti ti-heart"></i>HR: ${monitorVitals.hr} BPM</div>`;
            html += `<div class="selected-monitor-vital-item"><i class="ti ti-lungs"></i>RR: ${monitorVitals.rr} breaths/min</div>`;
            html += `<div class="selected-monitor-vital-item"><i class="ti ti-heartbeat"></i>SpO2: ${monitorVitals.spo2}%</div>`;
            
            if (monitorVitals.etco2) {
                html += `<div class="selected-monitor-vital-item"><i class="ti ti-wave-sine"></i>ETCO2: ${monitorVitals.etco2} mmHg</div>`;
            }
            
            const responseText = {
                'improving': 'Improving',
                'stable': 'Stable - No Change',
                'deteriorating': 'Deteriorating'
            };
            
            html += `<div class="selected-monitor-vital-item"><i class="ti ti-activity"></i>Status: ${responseText[monitorVitals.response] || monitorVitals.response}</div>`;
            
            display.innerHTML = html;
            display.classList.add('active');
        } else {
            display.innerHTML = '';
            display.classList.remove('active');
        }
    }
    
    function clearSelectedIndicators() {
        selectedIndicators = [];
        const display = document.getElementById('selected-indicators-display');
        display.innerHTML = '';
        display.classList.remove('active');
    }
    
    function clearBaselineVitals() {
        baselineVitals = {};
        const display = document.getElementById('selected-vitals-display');
        display.innerHTML = '';
        display.classList.remove('active');
    }
    
    function clearCpapSettings() {
        cpapSettings = {};
        const display = document.getElementById('selected-settings-display');
        display.innerHTML = '';
        display.classList.remove('active');
    }
    
    function clearMonitorVitals() {
        monitorVitals = {};
        const display = document.getElementById('selected-monitor-vitals-display');
        display.innerHTML = '';
        display.classList.remove('active');
    }
    
    // Update the documentation form with selected indicators
    function updateDocumentationIndicators() {
        const container = document.getElementById('indicators-container');
        if (selectedIndicators.length > 0) {
            let html = `
                <h3>CPAP Indications</h3>
                <div class="indicator-list">
            `;
            
            selectedIndicators.forEach(indicator => {
                html += `<div class="selected-indicator-item"><i class="ti ti-check"></i>${indicator}</div>`;
            });
            
            html += '</div>';
            container.innerHTML = html;
        } else {
            container.innerHTML = '';
        }
    }
    
    // Update the documentation form with baseline vitals
    function updateDocumentationVitals() {
        const container = document.getElementById('vital-signs-container');
        if (baselineVitals.bp) {
            let html = `
                <h3>Vital Signs</h3>
                <div class="vitals-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>BP</th>
                                <th>HR</th>
                                <th>RR</th>
                                <th>SpO2</th>
                                ${baselineVitals.etco2 ? '<th>ETCO2</th>' : ''}
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Baseline</td>
                                <td>${baselineVitals.bp}</td>
                                <td>${baselineVitals.hr}</td>
                                <td>${baselineVitals.rr}</td>
                                <td>${baselineVitals.spo2}%</td>
                                ${baselineVitals.etco2 ? '<td>' + baselineVitals.etco2 + ' mmHg</td>' : ''}
                            </tr>
            `;
            
            if (monitorVitals.bp) {
                html += `
                            <tr>
                                <td>During CPAP</td>
                                <td>${monitorVitals.bp}</td>
                                <td>${monitorVitals.hr}</td>
                                <td>${monitorVitals.rr}</td>
                                <td>${monitorVitals.spo2}%</td>
                                ${monitorVitals.etco2 ? '<td>' + monitorVitals.etco2 + ' mmHg</td>' : baselineVitals.etco2 ? '<td>-</td>' : ''}
                            </tr>
                `;
            }
            
            html += `
                        </tbody>
                    </table>
                </div>
            `;
            
            container.innerHTML = html;
        } else {
            container.innerHTML = '';
        }
    }
    
    // Update the documentation form with CPAP settings
    function updateDocumentationSettings() {
        const container = document.getElementById('cpap-settings-container');
        if (cpapSettings.pressure) {
            const deviceTypes = {
                'fixed-pressure': 'Fixed Pressure Device',
                'adjustable-pressure': 'Adjustable Pressure Device',
                'blender': 'Flow-Restricted, Oxygen-Powered Device'
            };
            
            let html = `
                <h3>CPAP Settings</h3>
                <div class="settings-list">
                    <div class="selected-setting-item"><i class="ti ti-gauge"></i>Pressure: ${cpapSettings.pressure} cmH2O</div>
                    <div class="selected-setting-item"><i class="ti ti-droplet"></i>FiO2: ${cpapSettings.fio2}%</div>
                    <div class="selected-setting-item"><i class="ti ti-device-medical"></i>Device: ${deviceTypes[cpapSettings.device] || cpapSettings.device}</div>
                </div>
            `;
            
            container.innerHTML = html;
        } else {
            container.innerHTML = '';
        }
    }
    
    // Reset checklist button
    const resetButton = document.getElementById('reset-checklist');
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to reset the entire checklist?')) {
                resetAllCheckboxes();
                clearSelectedIndicators();
                clearBaselineVitals();
                clearCpapSettings();
                clearMonitorVitals();
                
                // Hide edit buttons
                document.getElementById('edit-indicators-btn').style.display = 'none';
                document.getElementById('edit-vitals-btn').style.display = 'none';
                document.getElementById('edit-settings-btn').style.display = 'none';
                document.getElementById('edit-monitor-vitals-btn').style.display = 'none';
                
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
            updateDocumentationIndicators();
            updateDocumentationVitals();
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
        
        // Also save selected data
        const saveData = {
            checkboxes: checkboxStates,
            indicators: selectedIndicators,
            baselineVitals: baselineVitals,
            cpapSettings: cpapSettings,
            monitorVitals: monitorVitals
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
                
                // Load saved data if available
                if (data.indicators) {
                    selectedIndicators = data.indicators;
                }
                
                if (data.baselineVitals) {
                    baselineVitals = data.baselineVitals;
                }
                
                if (data.cpapSettings) {
                    cpapSettings = data.cpapSettings;
                }
                
                if (data.monitorVitals) {
                    monitorVitals = data.monitorVitals;
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
                        updateSelectedIndicatorsDisplay();
                        updateBaselineVitalsDisplay();
                        updateCpapSettingsDisplay();
                        updateMonitorVitalsDisplay();
                        
                        // Show edit buttons if needed
                        document.getElementById('edit-indicators-btn').style.display = selectedIndicators.length > 0 ? 'block' : 'none';
                        document.getElementById('edit-vitals-btn').style.display = Object.keys(baselineVitals).length > 0 ? 'block' : 'none';
                        document.getElementById('edit-settings-btn').style.display = Object.keys(cpapSettings).length > 0 ? 'block' : 'none';
                        document.getElementById('edit-monitor-vitals-btn').style.display = Object.keys(monitorVitals).length > 0 ? 'block' : 'none';
                        
                        closeModal('continueSessionModal');
                    }, { once: true });
                    
                    // Set up the new session button
                    document.getElementById('new-session-btn').addEventListener('click', function() {
                        // Clear local storage and reset the UI
                        localStorage.removeItem('cpapChecklist');
                        resetAllCheckboxes();
                        clearSelectedIndicators();
                        clearBaselineVitals();
                        clearCpapSettings();
                        clearMonitorVitals();
                        
                        // Hide edit buttons
                        document.getElementById('edit-indicators-btn').style.display = 'none';
                        document.getElementById('edit-vitals-btn').style.display = 'none';
                        document.getElementById('edit-settings-btn').style.display = 'none';
                        document.getElementById('edit-monitor-vitals-btn').style.display = 'none';
                        
                        closeModal('continueSessionModal');
                    }, { once: true });
                } else {
                    // If no checkboxes were previously checked, just apply the states directly
                    applyCheckboxStates(checkboxStates);
                    updateSelectedIndicatorsDisplay();
                    updateBaselineVitalsDisplay();
                    updateCpapSettingsDisplay();
                    updateMonitorVitalsDisplay();
                    
                    // Show edit buttons if needed
                    document.getElementById('edit-indicators-btn').style.display = selectedIndicators.length > 0 ? 'block' : 'none';
                    document.getElementById('edit-vitals-btn').style.display = Object.keys(baselineVitals).length > 0 ? 'block' : 'none';
                    document.getElementById('edit-settings-btn').style.display = Object.keys(cpapSettings).length > 0 ? 'block' : 'none';
                    document.getElementById('edit-monitor-vitals-btn').style.display = Object.keys(monitorVitals).length > 0 ? 'block' : 'none';
                }
            } catch (e) {
                console.error("Error parsing saved checklist:", e);
                // If there's an error, just reset everything
                resetAllCheckboxes();
                clearSelectedIndicators();
                clearBaselineVitals();
                clearCpapSettings();
                clearMonitorVitals();
                
                // Hide edit buttons
                document.getElementById('edit-indicators-btn').style.display = 'none';
                document.getElementById('edit-vitals-btn').style.display = 'none';
                document.getElementById('edit-settings-btn').style.display = 'none';
                document.getElementById('edit-monitor-vitals-btn').style.display = 'none';
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
        // Get patient info from form
        const indication = document.getElementById('patientIndication').value;
        const duration = document.getElementById('patientDuration').value || "15";
        const response = document.getElementById('patientResponse').value;
        
        // Get complications
        const complications = [];
        if (!document.getElementById('comp-none').checked) {
            document.querySelectorAll('.complication-check:checked').forEach(checkbox => {
                complications.push(checkbox.id.replace('comp-', ''));
            });
        }
        
        // Build the narrative
        let narrative = `Patient presented with ${indication}. `;
        
        // Add indicators if selected
        if (selectedIndicators.length > 0) {
            narrative += `Assessment revealed the following indications for CPAP: `;
            selectedIndicators.forEach((indicator, index) => {
                if (index > 0) {
                    narrative += (index === selectedIndicators.length - 1) ? ' and ' : ', ';
                }
                narrative += indicator;
            });
            narrative += '. ';
        }
        
        // Add baseline vitals if recorded
        if (baselineVitals.bp) {
            narrative += `Initial vital signs: BP ${baselineVitals.bp}, HR ${baselineVitals.hr}, RR ${baselineVitals.rr}, SpO2 ${baselineVitals.spo2}%. `;
            
            if (baselineVitals.etco2) {
                narrative += `ETCO2 ${baselineVitals.etco2} mmHg. `;
            }
        }
        
        // Contraindications check
        if (document.getElementById('check2').checked) {
            narrative += 'All contraindications for CPAP were ruled out. ';
        }
        
        // Patient positioning and preparation
        if (document.getElementById('check4').checked) {
            narrative += 'Patient was placed in a seated position to optimize respiratory effort. ';
        }
        
        if (document.getElementById('check5').checked) {
            narrative += 'The CPAP procedure was explained to the patient. ';
        }
        
        // CPAP application
        if (document.getElementById('check8').checked) {
            narrative += 'CPAP mask was applied and secured with head straps to minimize air leaks. ';
        }
        
        // CPAP settings
        if (cpapSettings.pressure) {
            const deviceTypes = {
                'fixed-pressure': 'a fixed pressure device',
                'adjustable-pressure': 'an adjustable pressure device',
                'blender': 'a flow-restricted, oxygen-powered device'
            };
            
            narrative += `CPAP was initiated using ${deviceTypes[cpapSettings.device] || 'standard equipment'} at ${cpapSettings.pressure} cmH2O with FiO2 of ${cpapSettings.fio2}%. `;
        }
        
        // Therapy details
        narrative += `CPAP therapy was maintained for ${duration} minutes. `;
        
        // Response to therapy
        const responseText = {
            'significant improvement': 'significant improvement',
            'moderate improvement': 'moderate improvement',
            'slight improvement': 'slight improvement',
            'no change': 'no significant change',
            'deterioration': 'deterioration'
        };
        
        narrative += `Patient showed ${responseText[response] || response} with therapy. `;
        
        // Add monitoring details if recorded
        if (monitorVitals.bp) {
            narrative += `During treatment, vital signs were: BP ${monitorVitals.bp}, HR ${monitorVitals.hr}, RR ${monitorVitals.rr}, SpO2 ${monitorVitals.spo2}%. `;
            
            if (monitorVitals.etco2) {
                narrative += `ETCO2 ${monitorVitals.etco2} mmHg. `;
            }
            
            const responseDetail = {
                'improving': 'Patient showed improvement with decreased work of breathing and increased oxygen saturation.',
                'stable': 'Patient remained stable throughout CPAP therapy with no significant changes in condition.',
                'deteriorating': 'Patient showed signs of deterioration requiring reassessment of therapy strategy.'
            };
            
            narrative += responseDetail[monitorVitals.response] || '';
            narrative += ' ';
        }
        
        // Complications
        if (complications.length > 0) {
            narrative += 'Complications during CPAP therapy included: ';
            
            const complicationText = {
                'mask-leak': 'mask leak issues',
                'pressure-intolerance': 'pressure intolerance',
                'claustrophobia': 'claustrophobia',
                'hypotension': 'hypotension',
                'vomiting': 'vomiting'
            };
            
            complications.forEach((comp, index) => {
                if (index > 0) {
                    narrative += (index === complications.length - 1) ? ' and ' : ', ';
                }
                narrative += complicationText[comp] || comp.replace(/-/g, ' ');
            });
            narrative += '. ';
            
            // Add management of complications
            if (complications.includes('mask-leak')) {
                narrative += 'Mask was adjusted to improve seal. ';
            }
            
            if (complications.includes('pressure-intolerance')) {
                narrative += 'Pressure was temporarily decreased to improve tolerance. ';
            }
            
            if (complications.includes('claustrophobia')) {
                narrative += 'Patient was reassured and coached to reduce anxiety. ';
            }
            
            if (complications.includes('hypotension')) {
                narrative += 'Fluid bolus was administered for hypotension. ';
            }
            
            if (complications.includes('vomiting')) {
                narrative += 'CPAP was temporarily removed during emesis and airway was cleared. ';
            }
        } else {
            narrative += 'No complications were encountered during CPAP therapy. ';
        }
        
        // Ongoing care
        narrative += `CPAP therapy ${response === 'deterioration' ? 'was discontinued due to patient deterioration' : 'continues to be administered'} with ongoing monitoring of respiratory status and vital signs.`;
        
        return narrative;
    }
    
    // Initialize: Load saved states when page loads
    loadCheckboxStates();
    
    // Initialize complication checkboxes
    complicationLabels.forEach(label => {
        label.classList.remove('enabled');
    });
});
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>