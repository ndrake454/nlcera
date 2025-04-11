<?php
/**
 * Intubation Checklist Tool
 * 
 * Place this file in: /tool_intubation.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'Intubation Checklist';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Intubation Checklist</h1>
                </div>
                <div class="card-body">
                    <p class="lead">Use this interactive checklist to ensure safe and effective intubation procedures. Click on the <i class="ti ti-info-circle"></i> icons for detailed information about each step.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <!-- Prepare Procedure Section -->
        <div class="col-md-10 mb-4">
            <div class="card">
                <div class="card-header section-header-treatment">
                    <h2 class="h5 mb-0"><i class="ti ti-list-check me-2"></i>1. Prepare Procedure</h2>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush checkbox-list">
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check1">
                                <label class="form-check-label" for="check1">
                                    Verify Indication for Intubation
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Ensure clinical indication exists for intubation based on patient assessment, airway compromise, ventilatory failure, or anticipated clinical course.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check2">
                                <label class="form-check-label" for="check2">
                                    Airway Assessment – LEMON
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="LEMON assessment: Look externally, Evaluate 3-3-2 rule, Mallampati score, Obstruction/Obesity, Neck mobility. Document potential difficult airway characteristics.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check3">
                                <label class="form-check-label" for="check3">
                                    Pre-Oxygenation Strategy
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Prepare equipment for pre-oxygenation: BVM with PEEP valve, nasal cannula for apneic oxygenation (up to 15 LPM), non-rebreather mask and suction equipment.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check4">
                                <label class="form-check-label" for="check4">
                                    Equipment Ready
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Check all equipment: Laryngoscope (working light), ET tubes (primary + backup sizes), stylet, 10mL syringe, BVM, functioning suction, end-tidal CO2 detector, backup devices (supraglottic airways, surgical airway kit).">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check5">
                                <label class="form-check-label" for="check5">
                                    Medications Drawn (if RSI)
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Prepare sedative agents: Calculate and draw up appropriate doses based on patient's weight, hemodynamic status, and clinical condition. Common options include etomidate, ketamine, or midazolam.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check6">
                                <label class="form-check-label" for="check6">
                                    Sedation
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Administer appropriate sedative agent at calculated dose. Assess for adequate sedation before proceeding to paralytic administration.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check7">
                                <label class="form-check-label" for="check7">
                                    Paralytic
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Administer paralytic agent (e.g., succinylcholine or rocuronium) if performing RSI. Allow sufficient time for onset of action before attempting laryngoscopy.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check8">
                                <label class="form-check-label" for="check8">
                                    Post-intubation sedation/analgesia
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Prepare post-intubation medications: Long-acting sedative and analgesic medications for ongoing management (e.g., fentanyl, midazolam, propofol).">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check9">
                                <label class="form-check-label" for="check9">
                                    Final Safety Pause
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Perform a brief pause to confirm all team members are ready, roles are assigned, equipment is prepared, and backup plans are established. Address any concerns before proceeding.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Intubation Procedure Section -->
        <div class="col-md-10 mb-4">
            <div class="card">
                <div class="card-header section-header-treatment">
                    <h2 class="h5 mb-0"><i class="ti ti-first-aid-kit me-2"></i>2. Intubation Procedure</h2>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush checkbox-list">
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check10">
                                <label class="form-check-label" for="check10">
                                    Positioning
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Position patient optimally for intubation: Use pillow or towel under occiput to align oral, pharyngeal, and laryngeal axes (sniffing position). Ensure bed/stretcher is at proper height for intubator.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check11">
                                <label class="form-check-label" for="check11">
                                    Laryngoscopy & Intubation
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Use laryngoscope to visualize airway structures, identifying epiglottis and vocal cords. Apply appropriate technique (direct or video laryngoscopy) to obtain best view of glottic opening.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check12">
                                <label class="form-check-label" for="check12">
                                    Verify Appropriate Depth
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Confirm ETT placement at appropriate depth (typically 21-23 cm at teeth for adult males, 19-21 cm for adult females). Note centimeter marking at teeth or gums.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check13">
                                <label class="form-check-label" for="check13">
                                    Confirm Placement
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Verify tube placement using primary (direct visualization through cords) and secondary confirmations (ETCO2, chest rise, absence of epigastric sounds, misting in tube). Document all confirmation methods used.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check14">
                                <label class="form-check-label" for="check14">
                                    Secure ETT
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Secure ETT using commercial device or tape. Ensure tube is stable and cannot be easily dislodged during movement or transport.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Post Intubation Section -->
        <div class="col-md-10 mb-4">
            <div class="card">
                <div class="card-header section-header-treatment">
                    <h2 class="h5 mb-0"><i class="ti ti-heartbeat me-2"></i>3. Post Intubation</h2>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush checkbox-list">
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check15">
                                <label class="form-check-label" for="check15">
                                    Ventilation Management
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Set initial ventilator parameters: Typically 6-8 mL/kg ideal body weight tidal volume, RR 12-16, PEEP 5 cmH2O. Adjust FiO2 based on SpO2 goals. Monitor for compliance with ventilation.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check16">
                                <label class="form-check-label" for="check16">
                                    Sedation & Analgesia Maintained
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Administer ongoing sedation and analgesia to maintain patient comfort and synchrony with ventilator. Titrate to appropriate sedation score (e.g., RASS -2 to -3).">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check17">
                                <label class="form-check-label" for="check17">
                                    Reconfirm every 5 minutes or with every patient movement
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Reassess tube position with each patient movement. Monitor ETCO2 waveform, chest rise, SpO2, and auscultate to ensure tube remains properly positioned.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check18">
                                <label class="form-check-label" for="check18">
                                    OG Tube Placed
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Insert orogastric (OG) tube to decompress stomach, reduce aspiration risk, and improve ventilation mechanics. Confirm placement and secure OG tube.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check19">
                                <label class="form-check-label" for="check19">
                                    Backups Available
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Ensure backup airway equipment remains immediately available: Additional ETT sizes, supraglottic airways, surgical cricothyrotomy kit, BVM, laryngoscope.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Transfer & Documentation Section -->
        <div class="col-md-10 mb-4">
            <div class="card">
                <div class="card-header section-header-treatment">
                    <h2 class="h5 mb-0"><i class="ti ti-clipboard-check me-2"></i>4. Transfer & Documentation</h2>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush checkbox-list">
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check20">
                                <label class="form-check-label" for="check20">
                                    Recheck Capnography & Tube Security
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Prior to transport, verify ETT position using capnography and auscultation. Confirm tube is secure and ventilator/BVM is functioning properly. Reassess before moving patient.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check21">
                                <label class="form-check-label" for="check21">
                                    Team Debrief
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Conduct formal team debrief: Discuss what went well, challenges encountered, and opportunities for improvement. Address any concerns from team members.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                        
                        <li class="list-group-item d-flex align-items-start">
                            <div class="form-check flex-grow-1">
                                <input class="form-check-input" type="checkbox" id="check22">
                                <label class="form-check-label" for="check22">
                                    Document – ETT size/depth, meds, EtCO<sub>2</sub>, difficulties, ventilator settings
                                </label>
                            </div>
                            <button type="button" class="info-btn" data-info="Document complete procedure details: ETT size, depth, medications used, number of attempts, confirmation methods, ventilator settings, complications encountered, and post-procedure vital signs.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center mt-3">
        <div class="col-md-10">
            <div class="alert alert-warning text-center">
                <small><em>This is not intended to be a comprehensive guide for prehospital intubation</em></small>
            </div>
            <div class="text-center mt-4">
                <button id="reset-checklist" class="btn btn-outline-secondary">
                    <i class="ti ti-refresh me-2"></i>Reset Checklist
                </button>
                <button id="print-checklist" class="btn btn-outline-primary ms-2">
                    <i class="ti ti-printer me-2"></i>Print Checklist
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Window for Detailed Information -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Item Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modalInfo">Detailed information will appear here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for the checklist */
.checkbox-list .list-group-item.checked {
    background-color: rgba(16, 110, 158, 0.1);
}

/* Fixed styling for info button */
.info-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 28px;
    width: 28px;
    height: 28px;
    background-color: var(--primary-color);
    color: white;
    border-radius: 50%;
    border: none;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    padding: 0;
    margin-left: 8px;
    flex-shrink: 0; /* Prevent shrinking */
}

.info-btn:hover {
    transform: scale(1.1);
    background-color: var(--secondary-color);
}

/* Ensure proper wrapping of text elements */
.form-check-label {
    word-break: normal;
    overflow-wrap: break-word;
    hyphens: auto;
}

/* Ensure proper alignment */
.list-group-item {
    padding: 0.75rem 1.25rem;
    display: flex;
    align-items: flex-start;
}

/* Make sure very small screens still display properly */
@media (max-width: 576px) {
    .form-check-label {
        padding-right: 10px; /* Add space before the button */
    }
    
    .info-btn {
        margin-top: 3px; /* Push button down slightly for alignment */
    }
}

/* Print-specific styles */
@media print {
    header, footer, .navbar, .btn, .alert, #reset-checklist, #print-checklist {
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
    
    .list-group-item {
        page-break-inside: avoid;
    }
    
    body {
        padding: 0 !important;
        margin: 0 !important;
    }
    
    .info-btn {
        display: none !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle checked state for list items when checkbox is clicked
    const checkboxes = document.querySelectorAll('.form-check-input');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const listItem = this.closest('.list-group-item');
            if (this.checked) {
                listItem.classList.add('checked');
            } else {
                listItem.classList.remove('checked');
            }
        });
    });
    
    // Show modal when info button is clicked
    const infoButtons = document.querySelectorAll('.info-btn');
    infoButtons.forEach(button => {
        button.addEventListener('click', function() {
            const infoText = this.getAttribute('data-info');
            const itemTitle = this.closest('.list-group-item').querySelector('.form-check-label').textContent.trim();
            
            // Format the info text
            let formattedInfo = infoText;
            
            // Wrap important terms in <strong> tags
            const importantTerms = [
                'LEMON', 'ETT', 'PEEP', 'SpO2', 'FiO2', 'OG Tube', 'RSI', 
                'oral', 'pharyngeal', 'laryngeal', 'epiglottis', 'vocal cords',
                'glottic', 'cricothyrotomy', 'ETCO2', 'RASS', 'pre-oxygenation'
            ];
            
            importantTerms.forEach(term => {
                const regex = new RegExp(`\\b${term}\\b`, 'g');
                formattedInfo = formattedInfo.replace(regex, `<strong>${term}</strong>`);
            });
            
            // Set modal content
            document.getElementById('modalTitle').textContent = itemTitle;
            document.getElementById('modalInfo').innerHTML = formattedInfo;
            
            // Show modal using Bootstrap's API
            const modal = new bootstrap.Modal(document.getElementById('infoModal'));
            modal.show();
        });
    });
    
    // Reset checklist button
    const resetButton = document.getElementById('reset-checklist');
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.form-check-input');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
                const listItem = checkbox.closest('.list-group-item');
                listItem.classList.remove('checked');
            });
        });
    }
    
    // Print checklist button
    const printButton = document.getElementById('print-checklist');
    if (printButton) {
        printButton.addEventListener('click', function() {
            window.print();
        });
    }
    
    // Enable persistence through local storage
    // Save checkbox states
    function saveCheckboxStates() {
        const checkboxStates = {};
        checkboxes.forEach(checkbox => {
            checkboxStates[checkbox.id] = checkbox.checked;
        });
        localStorage.setItem('intubationChecklist', JSON.stringify(checkboxStates));
    }
    
    // Load checkbox states
    function loadCheckboxStates() {
        const savedStates = localStorage.getItem('intubationChecklist');
        if (savedStates) {
            const checkboxStates = JSON.parse(savedStates);
            checkboxes.forEach(checkbox => {
                if (checkboxStates[checkbox.id] !== undefined) {
                    checkbox.checked = checkboxStates[checkbox.id];
                    const listItem = checkbox.closest('.list-group-item');
                    if (checkbox.checked) {
                        listItem.classList.add('checked');
                    } else {
                        listItem.classList.remove('checked');
                    }
                }
            });
        }
    }
    
    // Add event listeners to save state
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', saveCheckboxStates);
    });
    
    // Load saved states when page loads
    loadCheckboxStates();
});
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>