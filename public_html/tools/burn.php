<?php
/**
 * Rule of Nines - Burn Estimation Tool
 * 
 * A mobile-friendly calculator for estimating percentage of body surface area affected by burns
 * Based on the Wallace Rule of Nines methodology with pediatric adjustments
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'Rule of Nines Calculator';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="container py-4 pb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Rule of Nines - Burn Estimation Tool</h1>
                </div>
                <div class="card-body">
                    <p class="lead mb-0">Calculate the total body surface area (TBSA) affected by burns. Select the appropriate patient type and burn areas below.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <!-- Age selector -->
        <div class="col-md-10 mb-4">
            <div class="card">
                <div class="card-header section-header-treatment">
                    <h2 class="h5 mb-0"><i class="ti ti-user me-2"></i>Patient Type</h2>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="btn-group w-100" role="group" aria-label="Patient Type">
                                <button type="button" class="btn btn-outline-primary py-3 active" id="adult-toggle">
                                    <i class="ti ti-user me-2"></i>Adult
                                </button>
                                <button type="button" class="btn btn-outline-primary py-3" id="pediatric-toggle">
                                    <i class="ti ti-baby-carriage me-2"></i>Pediatric
                                </button>
                            </div>
                            <div class="alert alert-info mt-3 mb-0" id="patient-type-info">
                                <i class="ti ti-info-circle me-2"></i><strong>Adult Mode:</strong> Standard Rule of Nines percentages for patients over 14 years.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selection panel -->
        <div class="col-md-10 mb-4">
            <div class="card">
                <div class="card-header section-header-treatment d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 mb-0"><i class="ti ti-fire me-2"></i>Burn Areas</h2>
                    </div>
                    <button type="button" class="section-info-btn" id="info-button">
                        <i class="ti ti-info-circle"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="row burn-selections">
                        <!-- Head -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 burn-item" id="head-card">
                                <div class="card-header py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Head and Face</span>
                                        <span class="percentage-badge" id="head-percentage">9%</span>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 active burn-btn" data-part="head" data-value="0">None</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="head" data-value="half">Partial</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="head" data-value="full">Full</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Torso -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 burn-item" id="torso-card">
                                <div class="card-header py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Torso (Front)</span>
                                        <span class="percentage-badge" id="torso-percentage">18%</span>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 active burn-btn" data-part="torso" data-value="0">None</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="torso" data-value="half">Partial</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="torso" data-value="full">Full</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Back -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 burn-item" id="back-card">
                                <div class="card-header py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Back</span>
                                        <span class="percentage-badge" id="back-percentage">18%</span>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 active burn-btn" data-part="back" data-value="0">None</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="back" data-value="half">Partial</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="back" data-value="full">Full</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Arm -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 burn-item" id="right-arm-card">
                                <div class="card-header py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Right Arm</span>
                                        <span class="percentage-badge" id="right-arm-percentage">9%</span>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 active burn-btn" data-part="right-arm" data-value="0">None</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="right-arm" data-value="half">Partial</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="right-arm" data-value="full">Full</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Left Arm -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 burn-item" id="left-arm-card">
                                <div class="card-header py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Left Arm</span>
                                        <span class="percentage-badge" id="left-arm-percentage">9%</span>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 active burn-btn" data-part="left-arm" data-value="0">None</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="left-arm" data-value="half">Partial</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="left-arm" data-value="full">Full</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Genitalia -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 burn-item" id="genitalia-card">
                                <div class="card-header py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Genital Area</span>
                                        <span class="percentage-badge" id="genitalia-percentage">1%</span>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 active burn-btn" data-part="genitalia" data-value="0">None</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="genitalia" data-value="full">Full</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Leg -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 burn-item" id="right-leg-card">
                                <div class="card-header py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Right Leg</span>
                                        <span class="percentage-badge" id="right-leg-percentage">18%</span>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 active burn-btn" data-part="right-leg" data-value="0">None</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="right-leg" data-value="half">Partial</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="right-leg" data-value="full">Full</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Left Leg -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 burn-item" id="left-leg-card">
                                <div class="card-header py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Left Leg</span>
                                        <span class="percentage-badge" id="left-leg-percentage">18%</span>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 active burn-btn" data-part="left-leg" data-value="0">None</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="left-leg" data-value="half">Partial</button>
                                        <button type="button" class="btn btn-outline-primary w-100 py-3 burn-btn" data-part="left-leg" data-value="full">Full</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <button id="reset-button" class="btn btn-secondary w-100 py-3">
                                <i class="ti ti-refresh me-2"></i>Reset All Selections
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Interpretation Key -->
        <div class="col-md-10 mb-4">
            <div class="card">
                <div class="card-header section-header-reference">
                    <h2 class="h5 mb-0"><i class="ti ti-info-circle me-2"></i>Burn Classification</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Total TBSA</th>
                                    <th>Classification</th>
                                    <th>Management Considerations</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center fw-bold">≤10%</td>
                                    <td class="text-success fw-bold">Minor Burn</td>
                                    <td>Typically outpatient management. Clean with mild soap and water, apply appropriate dressings, tetanus prophylaxis if needed.</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">10-20%</td>
                                    <td class="text-warning fw-bold">Moderate Burn</td>
                                    <td>Consider inpatient management. Monitor fluid status and urine output. Consider fluid resuscitation.</td>
                                </tr>
                                <tr>
                                    <td class="text-center fw-bold">>20%</td>
                                    <td class="text-danger fw-bold">Major Burn</td>
                                    <td>Transfer to burn center if available. Aggressive fluid resuscitation required. Monitor for complications.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="ti ti-alert-triangle me-2"></i>
                        <strong>Note:</strong> First-degree burns should not be included in TBSA calculations. This tool provides an estimation only and should not replace clinical judgment.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fixed Results Section -->
<div class="fixed-results-container">
    <div class="results-card">
        <div class="results-content">
            <div id="total-percentage">0%</div>
            <div class="severity-info">
                <span id="severity-level">Minor</span>
            </div>
            <button class="toggle-button" id="expand-button">
                <i class="ti ti-chevron-up"></i>
            </button>
        </div>
    </div>
</div>

<!-- About Modal -->
<div class="modal fade" id="about-modal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="aboutModalLabel">About the Rule of Nines</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>The Rule of Nines is a quick method for estimating the total body surface area (TBSA) affected by burns. It divides the body into regions, each representing a percentage of total body surface area.</p>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3 border-primary">
                            <div class="card-header bg-primary text-white">
                                Adult Distribution
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    <li><strong>Head and face:</strong> 9%</li>
                                    <li><strong>Front of torso:</strong> 18%</li>
                                    <li><strong>Back of torso:</strong> 18%</li>
                                    <li><strong>Each arm:</strong> 9%</li>
                                    <li><strong>Each leg:</strong> 18%</li>
                                    <li><strong>Genital area:</strong> 1%</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3 border-warning">
                            <div class="card-header bg-warning text-dark">
                                Pediatric Distribution
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    <li><strong>Head and face:</strong> 18%</li>
                                    <li><strong>Front of torso:</strong> 18%</li>
                                    <li><strong>Back of torso:</strong> 18%</li>
                                    <li><strong>Each arm:</strong> 9%</li>
                                    <li><strong>Each leg:</strong> 13.5%</li>
                                    <li><strong>Genital area:</strong> 1%</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h5 class="mt-3">Burn Classifications:</h5>
                <ul>
                    <li><strong>Minor Burns (≤10% TBSA):</strong> Generally managed on an outpatient basis</li>
                    <li><strong>Moderate Burns (10-20% TBSA):</strong> May require inpatient management with monitoring</li>
                    <li><strong>Major Burns (>20% TBSA):</strong> Typically require transfer to burn center with aggressive fluid resuscitation</li>
                </ul>
                
                <h5>Limitations:</h5>
                <ul>
                    <li>Less accurate for patients with unusual body proportions</li>
                    <li>First-degree burns should not be included in TBSA calculations</li>
                    <li>For more accurate pediatric assessments, the Lund-Browder chart may be preferred</li>
                </ul>
                
                <div class="alert alert-info mt-3">
                    <strong>Note:</strong> This tool provides an estimation only and should not replace clinical judgment. Patient assessment should always be performed by qualified healthcare providers.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Rule of Nines Calculator specific styles */
/* Add extra padding at the bottom to make room for fixed results */
body {
    padding-bottom: 90px !important;
}

/* Fixed results container */
.fixed-results-container {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    width: 280px;
    z-index: 1000;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
}

.results-card {
    background-color: #1d70b8;
    width: 100%;
    border-radius: 15px;
    transition: all 0.3s ease;
}

.results-content {
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: white;
}

#total-percentage {
    font-size: 36px;
    font-weight: 700;
    flex-grow: 1;
    text-align: center;
}

.severity-info {
    display: flex;
    align-items: center;
    margin: 0 15px;
}

#severity-level {
    font-size: 18px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    background-color: rgba(255, 255, 255, 0.2);
    white-space: nowrap;
}

.toggle-button {
    background: transparent;
    color: white;
    border: none;
    width: 28px;
    height: 28px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.2s;
}

.toggle-button.collapsed i {
    transform: rotate(180deg);
}

/* Severity colors */
.severity-minor {
    background-color: #28a745 !important;
}

.severity-moderate {
    background-color: #fd7e14 !important;
}

.severity-major {
    background-color: #dc3545 !important;
}

/* Pediatric mode styles */
.pediatric-mode .results-card {
    background-color: #fd7e14;
}

/* Other styles from previous version */
.percentage-badge {
    background-color: var(--primary-color);
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
}

.burn-item {
    transition: all 0.3s ease;
    border: 1px solid #dee2e6;
}

.burn-item .card-header {
    padding: 10px 15px;
    background-color: #f8f9fa;
}

/* Mode specific styles */
.adult-mode .burn-item .card-header {
    border-bottom: 2px solid var(--primary-color);
}

.pediatric-mode .burn-item .card-header {
    border-bottom: 2px solid #fd7e14;
    background-color: #fff3cd;
}

.pediatric-mode .percentage-badge {
    background-color: #fd7e14;
}

.pediatric-mode .burn-btn.selected {
    background-color: #fd7e14 !important;
    border-color: #fd7e14 !important;
}

.burn-btn {
    text-align: center;
    transition: all 0.2s ease;
}

.burn-btn.selected {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

/* Section info button */
.section-info-btn {
    background-color: transparent;
    color: #495057;
    border: none;
    padding: 0.25rem;
    font-size: 1.1rem;
    border-radius: 50%;
    transition: all 0.2s;
}

.section-info-btn:hover {
    color: var(--primary-color);
    transform: scale(1.1);
}

/* Mobile optimizations */
@media (max-width: 576px) {
    .fixed-results-container {
        width: 240px;
        bottom: 15px;
    }
    
    #total-percentage {
        font-size: 32px;
    }
    
    #severity-level {
        font-size: 16px;
        padding: 3px 10px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Define the percentage values for adult and pediatric patients
    const bodyPercentages = {
        adult: {
            'head': { full: 9, half: 4.5 },
            'torso': { full: 18, half: 9 },
            'back': { full: 18, half: 9 },
            'right-arm': { full: 9, half: 4.5 },
            'left-arm': { full: 9, half: 4.5 },
            'genitalia': { full: 1, half: 0 },
            'right-leg': { full: 18, half: 9 },
            'left-leg': { full: 18, half: 9 }
        },
        pediatric: {
            'head': { full: 18, half: 9 },
            'torso': { full: 18, half: 9 },
            'back': { full: 18, half: 9 },
            'right-arm': { full: 9, half: 4.5 },
            'left-arm': { full: 9, half: 4.5 },
            'genitalia': { full: 1, half: 0 },
            'right-leg': { full: 13.5, half: 6.75 },
            'left-leg': { full: 13.5, half: 6.75 }
        }
    };
    
    // Current patient type
    let patientType = 'adult';
    
    // Track burn values by body part
    const burnValues = {
        'head': 0,
        'torso': 0,
        'back': 0,
        'right-arm': 0,
        'left-arm': 0,
        'genitalia': 0,
        'right-leg': 0,
        'left-leg': 0
    };
    
    // Add the adult mode class to body initially
    document.body.classList.add('adult-mode');
    
    // Update display of percentages based on patient type
    function updatePercentageDisplay() {
        for (const part in bodyPercentages[patientType]) {
            const percentElem = document.getElementById(`${part}-percentage`);
            if (percentElem) {
                percentElem.textContent = `${bodyPercentages[patientType][part].full}%`;
            }
        }
    }
    
    // Toggle results expand/collapse
    const expandButton = document.getElementById('expand-button');
    const resultsCard = document.querySelector('.results-card');
    
    expandButton.addEventListener('click', function() {
        expandButton.classList.toggle('collapsed');
        resultsCard.classList.toggle('collapsed');
    });
    
    // Age toggle functionality
    document.getElementById('adult-toggle').addEventListener('click', function() {
        if (patientType !== 'adult') {
            patientType = 'adult';
            document.getElementById('adult-toggle').classList.add('active');
            document.getElementById('pediatric-toggle').classList.remove('active');
            
            // Update UI for adult mode
            document.body.classList.remove('pediatric-mode');
            document.body.classList.add('adult-mode');
            
            // Update info text
            document.getElementById('patient-type-info').innerHTML = '<i class="ti ti-info-circle me-2"></i><strong>Adult Mode:</strong> Standard Rule of Nines percentages for patients over 14 years.';
            document.getElementById('patient-type-info').classList.remove('alert-warning');
            document.getElementById('patient-type-info').classList.add('alert-info');
            
            updatePercentageDisplay();
            resetSelections();
        }
    });
    
    document.getElementById('pediatric-toggle').addEventListener('click', function() {
        if (patientType !== 'pediatric') {
            patientType = 'pediatric';
            document.getElementById('pediatric-toggle').classList.add('active');
            document.getElementById('adult-toggle').classList.remove('active');
            
            // Update UI for pediatric mode
            document.body.classList.remove('adult-mode');
            document.body.classList.add('pediatric-mode');
            
            // Update info text
            document.getElementById('patient-type-info').innerHTML = '<i class="ti ti-alert-triangle me-2"></i><strong>Pediatric Mode:</strong> Modified percentages for patients under 14 years. Head is larger, legs are smaller.';
            document.getElementById('patient-type-info').classList.remove('alert-info');
            document.getElementById('patient-type-info').classList.add('alert-warning');
            
            updatePercentageDisplay();
            resetSelections();
        }
    });
    
    // Connect selection panel buttons to calculation
    const burnButtons = document.querySelectorAll('.burn-btn');
    
    burnButtons.forEach(button => {
        button.addEventListener('click', function() {
            const bodyPart = this.getAttribute('data-part');
            const value = this.getAttribute('data-value');
            const buttonGroup = this.closest('.btn-group');
            
            // Remove active class from all buttons in this group
            buttonGroup.querySelectorAll('.burn-btn').forEach(btn => {
                btn.classList.remove('selected');
                btn.classList.remove('active');
            });
            
            // Add selected class to clicked button
            this.classList.add('selected');
            
            // Calculate burn value
            if (value === 'half') {
                burnValues[bodyPart] = bodyPercentages[patientType][bodyPart].half;
            } else if (value === 'full') {
                burnValues[bodyPart] = bodyPercentages[patientType][bodyPart].full;
            } else {
                burnValues[bodyPart] = 0;
                this.classList.add('active');
            }
            
            updateResults();
            
            // Save state to localStorage
            saveState();
        });
    });
    
    // Reset button
    document.getElementById('reset-button').addEventListener('click', resetSelections);
    
    // Info button for modal
    document.getElementById('info-button').addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('about-modal'));
        modal.show();
    });
    
    // Reset selections
    function resetSelections() {
        // Reset all burn values
        for (const part in burnValues) {
            burnValues[part] = 0;
        }
        
        // Reset all buttons to "None"
        document.querySelectorAll('.burn-btn').forEach(button => {
            button.classList.remove('selected');
            if (button.getAttribute('data-value') === '0') {
                button.classList.add('active');
            } else {
                button.classList.remove('active');
            }
        });
        
        updateResults();
        
        // Clear localStorage
        localStorage.removeItem('burnCalculator');
    }
    
    // Update results
    function updateResults() {
        // Calculate total percentage
        let totalPercentage = 0;
        for (const part in burnValues) {
            totalPercentage += burnValues[part];
        }
        
        // Round to one decimal place
        totalPercentage = Math.round(totalPercentage * 10) / 10;
        
        // Update results display
        document.getElementById('total-percentage').textContent = totalPercentage + '%';
        
        // Update severity classification
        const resultsCard = document.querySelector('.results-card');
        const severityLevel = document.getElementById('severity-level');
        
        resultsCard.classList.remove('severity-minor', 'severity-moderate', 'severity-major');
        
        if (totalPercentage <= 10) {
            severityLevel.textContent = 'Minor';
            resultsCard.classList.add('severity-minor');
        } else if (totalPercentage <= 20) {
            severityLevel.textContent = 'Moderate';
            resultsCard.classList.add('severity-moderate');
        } else {
            severityLevel.textContent = 'Major';
            resultsCard.classList.add('severity-major');
        }
    }
    
    // Save state to localStorage
    function saveState() {
        const state = {
            patientType: patientType,
            burnValues: burnValues
        };
        
        localStorage.setItem('burnCalculator', JSON.stringify(state));
    }
    
    // Load state from localStorage
    function loadState() {
        const savedState = localStorage.getItem('burnCalculator');
        if (savedState) {
            const state = JSON.parse(savedState);
            
            // Set patient type
            patientType = state.patientType;
            
            // Update UI for patient type
            if (patientType === 'adult') {
                document.getElementById('adult-toggle').classList.add('active');
                document.getElementById('pediatric-toggle').classList.remove('active');
                document.body.classList.add('adult-mode');
                document.body.classList.remove('pediatric-mode');
                document.getElementById('patient-type-info').innerHTML = '<i class="ti ti-info-circle me-2"></i><strong>Adult Mode:</strong> Standard Rule of Nines percentages for patients over 14 years.';
                document.getElementById('patient-type-info').classList.add('alert-info');
                document.getElementById('patient-type-info').classList.remove('alert-warning');
            } else {
                document.getElementById('pediatric-toggle').classList.add('active');
                document.getElementById('adult-toggle').classList.remove('active');
                document.body.classList.add('pediatric-mode');
                document.body.classList.remove('adult-mode');
                document.getElementById('patient-type-info').innerHTML = '<i class="ti ti-alert-triangle me-2"></i><strong>Pediatric Mode:</strong> Modified percentages for patients under 14 years. Head is larger, legs are smaller.';
                document.getElementById('patient-type-info').classList.add('alert-warning');
                document.getElementById('patient-type-info').classList.remove('alert-info');
            }
            
            // Update percentages display
            updatePercentageDisplay();
            
            // Set burn values
            for (const part in state.burnValues) {
                burnValues[part] = state.burnValues[part];
            }
            
            // Update button selections
            for (const part in burnValues) {
                if (burnValues[part] > 0) {
                    const isHalf = burnValues[part] === bodyPercentages[patientType][part].half;
                    const value = isHalf ? 'half' : 'full';
                    
                    // Find and select the appropriate button
                    const button = document.querySelector(`.burn-btn[data-part="${part}"][data-value="${value}"]`);
                    if (button) {
                        // Remove active from all buttons in group
                        const buttonGroup = button.closest('.btn-group');
                        buttonGroup.querySelectorAll('.burn-btn').forEach(btn => {
                            btn.classList.remove('selected');
                            btn.classList.remove('active');
                        });
                        
                        // Select this button
                        button.classList.add('selected');
                    }
                }
            }
            
            // Update results
            updateResults();
        }
    }
    
    // Initialize
    updatePercentageDisplay();
    loadState();
    updateResults();
});
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>