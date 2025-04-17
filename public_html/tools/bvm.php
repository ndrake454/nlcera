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
        <p>Interactive checklist for managing airways with BVM, facemask, and NPA/OPA techniques. Click on the <i class="ti ti-info-circle"></i> icons for detailed information.</p>
    </div>
    
    <!-- Patient Assessment Section -->
    <div class="checklist-section" id="section-assessment">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-stethoscope"></i>
                <h2>1. Patient Assessment</h2>
            </div>
            <span class="section-status"><span class="completed-count">0</span>/<span class="total-count">5</span></span>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Assess Responsiveness</span>
                        <input type="checkbox" class="checkbox-input" id="check1" data-section="assessment">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Verify patient's level of consciousness. Check for response to verbal or painful stimuli. Unresponsive or altered patients may need airway management.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Check Breathing (Look, Listen, Feel)</span>
                        <input type="checkbox" class="checkbox-input" id="check2" data-section="assessment">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Look for chest rise and fall. Listen for abnormal breathing sounds (stridor, gurgling, snoring). Feel for air movement. Assess rate and depth of respirations. Note work of breathing and use of accessory muscles.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Assess Airway Patency</span>
                        <input type="checkbox" class="checkbox-input" id="check3" data-section="assessment">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Check for airway obstructions such as secretions, blood, vomit, foreign bodies, or anatomical issues like tongue occlusion. Look for signs of obstruction including stridor, gurgling, or snoring respirations.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Check Oxygen Saturation</span>
                        <input type="checkbox" class="checkbox-input" id="check4" data-section="assessment">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Apply pulse oximeter to monitor SpO2 levels. Note baseline reading and trends. Normal SpO2 is 94-100%. Values below 90% indicate significant hypoxemia requiring immediate intervention.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Check for C-Spine Concerns</span>
                        <input type="checkbox" class="checkbox-input" id="check5" data-section="assessment">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Consider cervical spine stabilization for trauma patients. Determine if manual stabilization or full spinal motion restriction is needed while managing the airway. Avoid hyperextension of the neck if C-spine injury is suspected.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Equipment Preparation Section -->
    <div class="checklist-section" id="section-equipment">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-tool"></i>
                <h2>2. Equipment Preparation</h2>
            </div>
            <span class="section-status"><span class="completed-count">0</span>/<span class="total-count">6</span></span>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Select Appropriate BVM Size</span>
                        <input type="checkbox" class="checkbox-input" id="check6" data-section="equipment">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Choose appropriate bag-valve-mask: Adult (1500-2000ml), Pediatric (500-700ml), or Infant (250ml) based on patient size. Confirm self-inflating bag rebounds properly and reservoir is attached.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
                <div class="selected-device-display" id="selected-bvm-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-bvm-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Select Appropriate Mask Size</span>
                        <input type="checkbox" class="checkbox-input" id="check7" data-section="equipment">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Choose mask that extends from bridge of nose to cleft of chin. Ensure appropriate seal without covering eyes or extending below chin. Transparent masks allow visualization of secretions or vomitus.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Measure & Select OPA/NPA</span>
                        <input type="checkbox" class="checkbox-input" id="check8" data-section="equipment">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="For OPA: Measure from corner of mouth to angle of jaw or earlobe. For NPA: Measure from tip of nose to tragus of ear. Select appropriate size (adult, child, infant) based on measurement. Consider NPA for patients with intact gag reflex.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
                <div class="selected-device-display" id="selected-adjunct-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-adjunct-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Connect Oxygen Source</span>
                        <input type="checkbox" class="checkbox-input" id="check9" data-section="equipment">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Connect oxygen tubing to BVM reservoir. Set flow rate to 15 LPM for adults (8-10 LPM for pediatric). Ensure oxygen tank has adequate supply. Check that reservoir bag fills completely between ventilations.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Prepare Suction Equipment</span>
                        <input type="checkbox" class="checkbox-input" id="check10" data-section="equipment">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Ensure suction unit is operational with appropriate catheter attached. Tonsil-tip (Yankauer) for oral secretions. Test suction power before use. Keep suction readily accessible during airway management.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Prepare PEEP Valve (if indicated)</span>
                        <input type="checkbox" class="checkbox-input" id="check11" data-section="equipment">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Attach PEEP valve if indicated (hypoxemia, pulmonary edema). Initial setting typically 5-10 cmH2O. Ensure proper attachment to exhalation port of BVM. Monitor patient response to PEEP application.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Airway Maneuvers & Adjuncts Section -->
    <div class="checklist-section" id="section-maneuvers">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-activity"></i>
                <h2>3. Airway Maneuvers & Adjuncts</h2>
            </div>
            <span class="section-status"><span class="completed-count">0</span>/<span class="total-count">5</span></span>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Apply Manual Airway Maneuvers</span>
                        <input type="checkbox" class="checkbox-input" id="check12" data-section="maneuvers">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Perform head-tilt/chin-lift for non-trauma patients. Use jaw-thrust technique for suspected cervical spine injury. These maneuvers lift the tongue off the posterior pharynx, opening the airway.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Suction Airway (if needed)</span>
                        <input type="checkbox" class="checkbox-input" id="check13" data-section="maneuvers">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Clear oropharynx of secretions, blood, or vomitus. Limit each suction attempt to 10-15 seconds. Pre-oxygenate between suction attempts. Insert suction tip along side of mouth, not directly down center.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Insert OPA (if no gag reflex)</span>
                        <input type="checkbox" class="checkbox-input" id="check14" data-section="maneuvers">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="For unresponsive patients without gag reflex: Insert OPA upside down, then rotate 180° (adults). Or insert directly with concave side facing down (children). Ensure proper sizing to prevent airway obstruction.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Insert NPA (if gag reflex present)</span>
                        <input type="checkbox" class="checkbox-input" id="check15" data-section="maneuvers">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Lubricate NPA with water-soluble lubricant. Insert along floor of nostril with bevel toward septum. Use gentle pressure and slight rotation. Avoid force. Confirm proper placement with visible end at uvula and improved air movement.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Re-check for Improved Airway</span>
                        <input type="checkbox" class="checkbox-input" id="check16" data-section="maneuvers">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="After placing airway adjuncts, reassess airway patency. Listen for improved air movement. Check for chest rise with spontaneous respiration. Ensure no new sounds of obstruction. Confirm adjunct is not causing obstruction.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- BVM Ventilation Section -->
    <div class="checklist-section" id="section-ventilation">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-lung"></i>
                <h2>4. BVM Ventilation</h2>
            </div>
            <span class="section-status"><span class="completed-count">0</span>/<span class="total-count">6</span></span>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Position Patient & Provider</span>
                        <input type="checkbox" class="checkbox-input" id="check17" data-section="ventilation">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Position patient with ear-to-sternal notch alignment for optimal airway anatomy. Position yourself at head of bed (or patient) for optimal leverage. Use a step stool if necessary to achieve proper position over the head.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Apply Proper Mask Seal</span>
                        <input type="checkbox" class="checkbox-input" id="check18" data-section="ventilation">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Use EC-clamp technique (thumb and index finger form 'C' on mask while remaining fingers form 'E' on jawline). Ensure even pressure on mask without excessive force. Alternative: Thenar eminence method for improved seal. Consider two-person technique for difficult cases.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
                <div class="selected-technique-display" id="selected-technique-display"></div>
                <button type="button" class="edit-selection-btn" id="edit-technique-btn" style="display: none;">
                    <i class="ti ti-pencil"></i> Edit
                </button>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Deliver Appropriate Ventilations</span>
                        <input type="checkbox" class="checkbox-input" id="check19" data-section="ventilation">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Deliver each breath over 1 second with visible chest rise (approx. 500-600ml). Avoid excessive volume (stomach distention, barotrauma risk). Rate: Adults with advanced airway (10-12/min); Adult/Child (12-20/min); Infant (20-30/min). For cardiac arrest: 10 breaths/min (1 breath every 6 seconds).">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Verify Effective Ventilation</span>
                        <input type="checkbox" class="checkbox-input" id="check20" data-section="ventilation">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Watch for bilateral chest rise. Listen for equal breath sounds. Observe improvement in SpO2. Watch for improvements in patient color and condition. Assess for decreasing work of breathing with assisted ventilations.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Monitor for Complications</span>
                        <input type="checkbox" class="checkbox-input" id="check21" data-section="ventilation">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Watch for gastric distension (risking regurgitation/aspiration). Check for adequate chest rise (insufficient ventilation). Monitor for decreased SpO2 despite ventilation (suggests ineffective ventilation or seal). Assess for barotrauma signs from excessive pressure.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Switch Providers if Fatigue</span>
                        <input type="checkbox" class="checkbox-input" id="check22" data-section="ventilation">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="BVM ventilation is physically demanding. Switch providers every 2-3 minutes if possible to maintain effective ventilations. Communicate the switch plan before fatigue compromises ventilation quality. Ensure smooth handoff without interrupting ventilation cycle.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monitoring & Ongoing Care Section -->
    <div class="checklist-section" id="section-monitoring">
        <div class="section-header">
            <div class="section-title">
                <i class="ti ti-device-analytics"></i>
                <h2>5. Monitoring & Ongoing Care</h2>
            </div>
            <span class="section-status"><span class="completed-count">0</span>/<span class="total-count">5</span></span>
        </div>
        
        <div class="checklist-items">
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Continuous SpO2 Monitoring</span>
                        <input type="checkbox" class="checkbox-input" id="check23" data-section="monitoring">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Maintain continuous pulse oximetry monitoring. Note trends in SpO2 values. Target SpO2 94-99% for most patients (88-92% may be appropriate for COPD). Reassess probe placement if readings are inconsistent with clinical assessment.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Monitor EtCO2 (if available)</span>
                        <input type="checkbox" class="checkbox-input" id="check24" data-section="monitoring">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Apply capnography if available. Normal EtCO2 range: 35-45 mmHg. Values below 30 mmHg suggest hyperventilation. Values above 50 mmHg suggest hypoventilation or increased CO2 production. Waveform analysis provides feedback on ventilation effectiveness.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Reassess Airway Patency</span>
                        <input type="checkbox" class="checkbox-input" id="check25" data-section="monitoring">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Regularly check for airway adjunct placement and effectiveness. Reposition if necessary. Verify no new secretions, bleeding, or vomiting has occurred. Suction as needed. Assess for ongoing signs of airway obstruction.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Vital Signs Monitoring</span>
                        <input type="checkbox" class="checkbox-input" id="check26" data-section="monitoring">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Monitor heart rate, blood pressure, respiratory rate. Watch for signs of improvement or deterioration. Assess for signs of respiratory failure despite interventions. Document vital signs at regular intervals.">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
            </div>
            
            <div class="checklist-item">
                <div class="checklist-item-row">
                    <label class="checkbox-container">
                        <span class="checkbox-label">Consider Advanced Airway Need</span>
                        <input type="checkbox" class="checkbox-input" id="check27" data-section="monitoring">
                        <span class="checkmark"></span>
                    </label>
                    <button type="button" class="info-btn" data-info="Evaluate if basic airway management remains adequate or if advanced airway (ETT, supraglottic device) is needed. Consider if patient condition requires prolonged ventilation. Analyze trends in patient condition. Prepare for escalation of care if needed.">
                        <i class="ti ti-info-circle"></i>
                    </button>
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

/* Display for selected equipment and technique */
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

/* Equipment Selection Styling */
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

/* Additional styles for adjunct selection */
.adjunct-type {
    margin-bottom: 20px;
}

.adjunct-type h3 {
    margin-bottom: 15px;
    font-weight: 500;
    color: #495057;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 5px;
}

.mt-4 {
    margin-top: 20px;
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

/* Utility classes */
.mt-2 {
    margin-top: 10px;
}

.mt-3 {
    margin-top: 15px;
}

.form-control-static {
    padding-top: calc(0.375rem + 1px);
    padding-bottom: calc(0.375rem + 1px);
    margin-bottom: 0;
    line-height: 1.5;
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
        padding: 14px 22px;
        font-size: 16px;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Global state variables
    let checklistState = {
        assessment: {
            total: 5,
            completed: 0,
            checks: {}
        },
        equipment: {
            total: 6,
            completed: 0,
            checks: {}
        },
        maneuvers: {
            total: 5,
            completed: 0,
            checks: {}
        },
        ventilation: {
            total: 6,
            completed: 0,
            checks: {}
        },
        monitoring: {
            total: 5,
            completed: 0,
            checks: {}
        }
    };

    let selectedEquipment = {
        bvm: '',
        adjuncts: [],
        technique: ''
    };

    // Initialize counters
    updateAllCounters();

    // Load saved state if exists
    loadSavedState();

    // Checkbox Event Listeners
    document.querySelectorAll('.checkbox-input').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkId = this.id;
            const sectionName = this.dataset.section;
            const isChecked = this.checked;
            
            // Update state
            checklistState[sectionName].checks[checkId] = isChecked;
            
            // Update counter
            if (isChecked) {
                checklistState[sectionName].completed++;
            } else {
                checklistState[sectionName].completed--;
            }
            
            // Apply checked styling to parent item
            const parentItem = this.closest('.checklist-item');
            if (isChecked) {
                parentItem.classList.add('checked');
            } else {
                parentItem.classList.remove('checked');
            }
            
            // Update UI
            updateSectionCounter(sectionName);
            updateSectionCompletion(sectionName);
            
            // Save state
            saveState();
        });
    });

    // Info Button Event Listeners
    document.querySelectorAll('.info-btn').forEach(button => {
        button.addEventListener('click', function() {
            const infoText = this.dataset.info;
            const modalTitle = this.closest('.checklist-item').querySelector('.checkbox-label').textContent;
            
            // Populate and show info modal
            document.getElementById('modalTitle').textContent = modalTitle;
            document.getElementById('modalInfo').textContent = infoText;
            
            openModal('infoModal');
        });
    });

    // BVM Size Checkbox Event
    document.getElementById('check7').addEventListener('change', function() {
        if (this.checked && !selectedEquipment.bvm) {
            // Open BVM size selection modal
            openModal('bvmSizeModal');
        }
    });

    // OPA/NPA Selection Checkbox Event
    document.getElementById('check8').addEventListener('change', function() {
        if (this.checked && selectedEquipment.adjuncts.length === 0) {
            // Open adjunct selection modal
            openModal('adjunctModal');
        }
    });

    // Mask Seal Technique Checkbox Event
    document.getElementById('check18').addEventListener('change', function() {
        if (this.checked && !selectedEquipment.technique) {
            // Open technique selection modal
            openModal('techniqueModal');
        }
    });

    // Equipment option selection handlers
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
            selectedEquipment.bvm = selectedRadio.value;
            
            // Update display
            const display = document.getElementById('selected-bvm-display');
            display.innerHTML = `<div class="selected-device-item"><i class="ti ti-first-aid-kit"></i> ${getEquipmentDisplayName(selectedRadio.value)}</div>`;
            display.classList.add('active');
            
            // Show edit button
            document.getElementById('edit-bvm-btn').style.display = 'block';
            
            // Close modal
            closeModal('bvmSizeModal');
            
            // Save state
            saveState();
        } else {
            alert('Please select a BVM size.');
        }
    });

    // Save Adjunct Selection
    document.getElementById('save-adjunct').addEventListener('click', function() {
        const selectedAdjuncts = Array.from(document.querySelectorAll('input[name="adjunct-type"]:checked')).map(input => input.value);
        
        if (selectedAdjuncts.length > 0) {
            selectedEquipment.adjuncts = selectedAdjuncts;
            
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
            
            // Save state
            saveState();
        } else {
            alert('Please select at least one airway adjunct.');
        }
    });

    // Save Technique Selection
    document.getElementById('save-technique').addEventListener('click', function() {
        const selectedRadio = document.querySelector('input[name="bvm-technique"]:checked');
        
        if (selectedRadio) {
            selectedEquipment.technique = selectedRadio.value;
            
            // Update display
            const display = document.getElementById('selected-technique-display');
            display.innerHTML = `<div class="selected-technique-item"><i class="ti ti-hand-stop"></i> ${getEquipmentDisplayName(selectedRadio.value)}</div>`;
            display.classList.add('active');
            
            // Show edit button
            document.getElementById('edit-technique-btn').style.display = 'block';
            
            // Close modal
            closeModal('techniqueModal');
            
            // Save state
            saveState();
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

    // Close Modal Buttons
    document.querySelectorAll('.close-modal, .close-button').forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.tool-modal');
            closeModal(modal.id);
        });
    });

    // Generate Documentation Button
    document.getElementById('generate-narrative').addEventListener('click', function() {
        // Update equipment-details-container in the narrative form
        updateEquipmentDetails();
        
        // Open documentation form modal
        openModal('narrativeFormModal');
    });

    // Complications checkbox logic
    document.getElementById('comp-none').addEventListener('change', function() {
        const complicationCheckboxes = document.querySelectorAll('.complication-check');
        const complicationLabels = document.querySelectorAll('.complication-check-label');
        
        if (this.checked) {
            // Disable and uncheck all complication checkboxes
            complicationCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
                checkbox.disabled = true;
            });
            
            // Disable labels
            complicationLabels.forEach(label => {
                label.classList.remove('enabled');
            });
        } else {
            // Enable all complication checkboxes
            complicationCheckboxes.forEach(checkbox => {
                checkbox.disabled = false;
            });
            
            // Enable labels
            complicationLabels.forEach(label => {
                label.classList.add('enabled');
            });
        }
    });

    // Initialize complication checkboxes
    document.querySelectorAll('.complication-check').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                // Uncheck "No complications" if any specific complication is checked
                document.getElementById('comp-none').checked = false;
            }
        });
    });

    // Generate Narrative Button
    document.getElementById('generateNarrativeBtn').addEventListener('click', function() {
        const narrative = generateNarrative();
        document.getElementById('narrative-text').textContent = narrative;
        
        closeModal('narrativeFormModal');
        openModal('narrativeModal');
    });

    // Copy Narrative Button
    document.getElementById('copy-narrative').addEventListener('click', function() {
        const narrativeText = document.getElementById('narrative-text').textContent;
        navigator.clipboard.writeText(narrativeText)
            .then(() => {
                this.textContent = '✓ Copied!';
                setTimeout(() => {
                    this.innerHTML = '<i class="ti ti-copy"></i> Copy to Clipboard';
                }, 2000);
            })
            .catch(err => {
                console.error('Could not copy text: ', err);
                alert('Failed to copy to clipboard. Please try again or copy manually.');
            });
    });

    // Reset Checklist Button
    document.getElementById('reset-checklist').addEventListener('click', function() {
        if (confirm('Are you sure you want to reset the checklist? All progress will be lost.')) {
            resetChecklist();
        }
    });

    // Continue Session Modal Buttons
    document.getElementById('continue-session-btn').addEventListener('click', function() {
        restoreState();
        closeModal('continueSessionModal');
    });

    document.getElementById('new-session-btn').addEventListener('click', function() {
        localStorage.removeItem('airwayChecklistState');
        resetChecklist();
        closeModal('continueSessionModal');
    });

    // Helper Functions
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function updateSectionCounter(sectionName) {
        const section = document.getElementById(`section-${sectionName}`);
        if (section) {
            const completedElement = section.querySelector('.completed-count');
            if (completedElement) {
                completedElement.textContent = checklistState[sectionName].completed;
            }
        }
    }

    function updateAllCounters() {
        for (const section in checklistState) {
            updateSectionCounter(section);
        }
    }

    function updateSectionCompletion(sectionName) {
        const section = document.getElementById(`section-${sectionName}`);
        if (section) {
            if (checklistState[sectionName].completed === checklistState[sectionName].total) {
                section.classList.add('completed');
            } else {
                section.classList.remove('completed');
            }
        }
    }

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

    function saveState() {
        const state = {
            checklistState: checklistState,
            selectedEquipment: selectedEquipment
        };
        
        localStorage.setItem('airwayChecklistState', JSON.stringify(state));
    }

    function loadSavedState() {
        const savedState = localStorage.getItem('airwayChecklistState');
        
        if (savedState) {
            // Ask user if they want to continue from saved state
            openModal('continueSessionModal');
        }
    }

    function restoreState() {
        const savedState = localStorage.getItem('airwayChecklistState');
        
        if (savedState) {
            const state = JSON.parse(savedState);
            
            // Restore checklistState
            checklistState = state.checklistState;
            
            // Restore selectedEquipment
            selectedEquipment = state.selectedEquipment;
            
            // Restore checkbox states
            for (const section in checklistState) {
                for (const checkId in checklistState[section].checks) {
                    const isChecked = checklistState[section].checks[checkId];
                    const checkbox = document.getElementById(checkId);
                    
                    if (checkbox) {
                        checkbox.checked = isChecked;
                        
                        // Apply checked styling
                        const parentItem = checkbox.closest('.checklist-item');
                        if (isChecked) {
                            parentItem.classList.add('checked');
                        } else {
                            parentItem.classList.remove('checked');
                        }
                    }
                }
                
                // Update counter and completion status
                updateSectionCounter(section);
                updateSectionCompletion(section);
            }
            
            // Restore equipment selections
            if (selectedEquipment.bvm) {
                // Set selected BVM radio button
                const bvmRadio = document.querySelector(`input[name="bvm-size"][value="${selectedEquipment.bvm}"]`);
                if (bvmRadio) {
                    bvmRadio.checked = true;
                    bvmRadio.closest('.equipment-option').classList.add('selected');
                }
                
                // Update BVM display
                const bvmDisplay = document.getElementById('selected-bvm-display');
                bvmDisplay.innerHTML = `<div class="selected-device-item"><i class="ti ti-first-aid-kit"></i> ${getEquipmentDisplayName(selectedEquipment.bvm)}</div>`;
                bvmDisplay.classList.add('active');
                document.getElementById('edit-bvm-btn').style.display = 'block';
            }
            
            if (selectedEquipment.adjuncts.length > 0) {
                // Set selected adjunct checkboxes
                selectedEquipment.adjuncts.forEach(adjunct => {
                    const adjunctCheckbox = document.querySelector(`input[name="adjunct-type"][value="${adjunct}"]`);
                    if (adjunctCheckbox) {
                        adjunctCheckbox.checked = true;
                        adjunctCheckbox.closest('.equipment-option').classList.add('selected');
                    }
                });
                
                // Update adjunct display
                const adjunctDisplay = document.getElementById('selected-adjunct-display');
                adjunctDisplay.innerHTML = selectedEquipment.adjuncts.map(adjunct => 
                    `<div class="selected-device-item"><i class="ti ti-medical-cross"></i> ${getEquipmentDisplayName(adjunct)}</div>`
                ).join('');
                adjunctDisplay.classList.add('active');
                document.getElementById('edit-adjunct-btn').style.display = 'block';
            }
            
            if (selectedEquipment.technique) {
                // Set selected technique radio button
                const techniqueRadio = document.querySelector(`input[name="bvm-technique"][value="${selectedEquipment.technique}"]`);
                if (techniqueRadio) {
                    techniqueRadio.checked = true;
                    techniqueRadio.closest('.technique-option').classList.add('selected');
                }
                
                // Update technique display
                const techniqueDisplay = document.getElementById('selected-technique-display');
                techniqueDisplay.innerHTML = `<div class="selected-technique-item"><i class="ti ti-hand-stop"></i> ${getEquipmentDisplayName(selectedEquipment.technique)}</div>`;
                techniqueDisplay.classList.add('active');
                document.getElementById('edit-technique-btn').style.display = 'block';
            }
        }
    }

    function resetChecklist() {
        // Reset checkboxes
        document.querySelectorAll('.checkbox-input').forEach(checkbox => {
            checkbox.checked = false;
            checkbox.closest('.checklist-item').classList.remove('checked');
        });
        
        // Reset counters
        for (const section in checklistState) {
            checklistState[section].completed = 0;
            checklistState[section].checks = {};
            
            updateSectionCounter(section);
            updateSectionCompletion(section);
        }
        
        // Reset equipment selections
        selectedEquipment = {
            bvm: '',
            adjuncts: [],
            technique: ''
        };
        
        // Reset equipment displays
        document.getElementById('selected-bvm-display').classList.remove('active');
        document.getElementById('selected-bvm-display').innerHTML = '';
        document.getElementById('edit-bvm-btn').style.display = 'none';
        
        document.getElementById('selected-adjunct-display').classList.remove('active');
        document.getElementById('selected-adjunct-display').innerHTML = '';
        document.getElementById('edit-adjunct-btn').style.display = 'none';
        
        document.getElementById('selected-technique-display').classList.remove('active');
        document.getElementById('selected-technique-display').innerHTML = '';
        document.getElementById('edit-technique-btn').style.display = 'none';
        
        // Reset equipment selection modals
        document.querySelectorAll('.equipment-option, .technique-option').forEach(option => {
            option.classList.remove('selected');
        });
        
        document.querySelectorAll('input[name="bvm-size"], input[name="bvm-technique"]').forEach(radio => {
            radio.checked = false;
        });
        
        document.querySelectorAll('input[name="adjunct-type"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        // Clear saved state
        localStorage.removeItem('airwayChecklistState');
    }

    function updateEquipmentDetails() {
        const container = document.getElementById('equipment-details-container');
        let html = '<h3>Equipment Used</h3><div class="equipment-list">';
        
        if (selectedEquipment.bvm) {
            html += `<div class="equipment-item"><i class="ti ti-first-aid-kit"></i> ${getEquipmentDisplayName(selectedEquipment.bvm)}</div>`;
        }
        
        selectedEquipment.adjuncts.forEach(adjunct => {
            html += `<div class="equipment-item"><i class="ti ti-medical-cross"></i> ${getEquipmentDisplayName(adjunct)}</div>`;
        });
        
        if (selectedEquipment.technique) {
            html += `<div class="equipment-item"><i class="ti ti-hand-stop"></i> ${getEquipmentDisplayName(selectedEquipment.technique)}</div>`;
        }
        
        html += '</div>';
        container.innerHTML = html;
    }

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
        
        // Build narrative
        let narrative = `AIRWAY MANAGEMENT\n`;
        narrative += `Indication: Patient presented with ${indication}.\n`;
        if (initialSpO2) narrative += `Initial SpO2: ${initialSpO2}%.\n`;
        
        // Patient assessment narrative
        narrative += `\nPATIENT ASSESSMENT:\n`;
        narrative += `Assessed patient's level of consciousness and responsiveness. `;
        narrative += `Evaluated breathing pattern using look, listen, feel technique. `;
        narrative += `Assessed airway patency and checked for any obstructions or secretions. `;
        
        // Equipment narrative
        narrative += `\nEQUIPMENT UTILIZED:\n`;
        if (selectedEquipment.bvm) {
            narrative += `- ${getEquipmentDisplayName(selectedEquipment.bvm)}\n`;
        }
        
        if (selectedEquipment.adjuncts.length > 0) {
            selectedEquipment.adjuncts.forEach(adjunct => {
                narrative += `- ${getEquipmentDisplayName(adjunct)}\n`;
            });
        }
        
        // Technique narrative
        narrative += `\nPROCEDURE:\n`;
        if (checklistState.maneuvers.checks['check12']) {
            narrative += `Applied appropriate manual airway maneuvers to establish an open airway. `;
        }
        
        if (checklistState.maneuvers.checks['check13']) {
            narrative += `Suctioned airway to clear secretions. `;
        }
        
        if (selectedEquipment.adjuncts.some(adj => adj.includes('opa'))) {
            narrative += `Inserted appropriate-sized OPA. `;
        }
        
        if (selectedEquipment.adjuncts.some(adj => adj.includes('npa'))) {
            narrative += `Inserted appropriate-sized NPA with water-soluble lubricant. `;
        }
        
        narrative += `\nVENTILATION DETAILS:\n`;
        if (selectedEquipment.technique) {
            narrative += `Used ${getEquipmentDisplayName(selectedEquipment.technique)}. `;
        }
        
        narrative += `Delivered ventilations at a rate of ${ventRate} breaths per minute. `;
        narrative += `Administered oxygen at ${oxygenLpm} LPM. `;
        
        if (parseInt(peepSetting) > 0) {
            narrative += `Applied PEEP valve at ${peepSetting} cmH2O. `;
        }
        
        if (postSpO2) {
            narrative += `\nOUTCOME:\n`;
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
        
        // Complications
        narrative += `\nCOMPLICATIONS:\n`;
        if (noComplications) {
            narrative += `No complications were observed during the procedure.`;
        } else {
            narrative += `The following complications were addressed during the procedure: ${complications.join(', ')}.`;
        }
        
        // Monitoring
        if (checklistState.monitoring.checks['check23'] || checklistState.monitoring.checks['check24']) {
            narrative += `\n\nONGOING MONITORING:\n`;
            if (checklistState.monitoring.checks['check23']) {
                narrative += `Maintained continuous SpO2 monitoring. `;
            }
            if (checklistState.monitoring.checks['check24']) {
                narrative += `Monitored EtCO2 for ventilation effectiveness. `;
            }
            if (checklistState.monitoring.checks['check25']) {
                narrative += `Regularly reassessed airway patency and effectiveness of interventions. `;
            }
            if (checklistState.monitoring.checks['check26']) {
                narrative += `Continuously monitored vital signs throughout procedure. `;
            }
        }
        
        return narrative;
    }
});
</script>