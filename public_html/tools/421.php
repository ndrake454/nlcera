<?php
/**
 * 4-2-1 Rule Fluid Calculator
 * 
 * Place this file in: /tool_fluid_calculator.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = '4-2-1 Rule Fluid Calculator';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">4-2-1 Rule Fluid Calculator</h1>
                </div>
                <div class="card-body">
                    <p class="lead">Calculate maintenance fluid requirements based on the 4-2-1 rule, commonly used in pediatric fluid management.</p>
                    
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="ti ti-info-circle me-3 fs-4"></i>
                        <div>
                            The 4-2-1 rule is a method to estimate maintenance fluid requirements based on a patient's weight.
                            <a href="#" class="ms-2" data-bs-toggle="modal" data-bs-target="#rulesModal">Learn more about the 4-2-1 rule</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Input Section -->
                <div class="col-lg-5">
                    <div class="card mb-4">
                        <div class="card-header section-header-reference">
                            <h2 class="h5 mb-0"><i class="ti ti-user me-2"></i>Patient Parameters</h2>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="weight" class="form-label">Patient Weight:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control form-control-lg" id="weight" min="0" step="0.1" value="10">
                                    <div class="input-group-text">kg</div>
                                </div>
                                <div class="form-text">Enter the patient's weight in kilograms</div>
                            </div>
                            
                            <!-- Weight Adjustment Buttons -->
                            <div class="d-flex flex-wrap justify-content-between mb-3">
                                <div class="btn-group mb-2">
                                    <button type="button" class="btn btn-outline-secondary" id="decrease-weight-small">
                                        <i class="ti ti-minus"></i> 0.1
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="decrease-weight">
                                        <i class="ti ti-minus"></i> 1
                                    </button>
                                </div>
                                <div class="btn-group mb-2">
                                    <button type="button" class="btn btn-outline-secondary" id="increase-weight">
                                        <i class="ti ti-plus"></i> 1
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="increase-weight-small">
                                        <i class="ti ti-plus"></i> 0.1
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Common Pediatric Weights -->
                            <div class="mt-4">
                                <h6 class="mb-2">Common Pediatric Weight Presets:</h6>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary weight-preset" data-weight="3.5">Newborn</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary weight-preset" data-weight="7">6 mo</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary weight-preset" data-weight="10">1 yr</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary weight-preset" data-weight="12">2 yr</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary weight-preset" data-weight="15">4 yr</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary weight-preset" data-weight="20">6 yr</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary weight-preset" data-weight="30">10 yr</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary weight-preset" data-weight="50">14 yr</button>
                                    <button type="button" class="btn btn-sm btn-outline-primary weight-preset" data-weight="70">Adult</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Results Section -->
                <div class="col-lg-7">
                    <div class="card mb-4">
                        <div class="card-header section-header-treatment">
                            <h2 class="h5 mb-0"><i class="ti ti-droplet me-2"></i>Fluid Requirements</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">Hourly Rate</h5>
                                            <p class="display-4 mb-0" id="hourly-rate">40</p>
                                            <p class="text-muted">mL/hour</p>
                                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#hourlyModal">
                                                <i class="ti ti-info-circle"></i> Calculation Method
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 bg-light">
                                        <div class="card-body text-center">
                                            <h5 class="card-title">Daily Volume</h5>
                                            <p class="display-4 mb-0" id="daily-volume">960</p>
                                            <p class="text-muted">mL/24 hours</p>
                                            <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#dailyModal">
                                                <i class="ti ti-info-circle"></i> Calculation Method
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-primary mt-3">
                                <div class="d-flex">
                                    <i class="ti ti-calculator me-2 fs-5"></i>
                                    <div>
                                        <strong>Formula Applied:</strong> <span id="formula-used">4 mL/kg/hr × 10 kg = 40 mL/hr</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning">
                                <div class="d-flex">
                                    <i class="ti ti-alert-triangle me-2 fs-5"></i>
                                    <div>
                                        <strong>Clinical Note:</strong> This calculator provides an estimate of maintenance fluid requirements. Actual fluid needs may vary based on the patient's clinical condition, losses, and other factors. Always use clinical judgment and adjust as needed.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Information Card -->
            <div class="card mb-4">
                <div class="card-header section-header-note">
                    <h2 class="h5 mb-0"><i class="ti ti-notes me-2"></i>Clinical Considerations</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>When to Adjust Maintenance Fluids:</h6>
                            <ul>
                                <li>Increased environmental temperature</li>
                                <li>Fever (increase by ~10% per degree C above normal)</li>
                                <li>Hyperventilation</li>
                                <li>Additional ongoing losses (vomiting, diarrhea, drainage)</li>
                                <li>Renal or cardiac dysfunction</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Replacement of Fluid Deficits:</h6>
                            <ul>
                                <li>Assess dehydration status clinically</li>
                                <li>Mild dehydration: 3-5% of body weight</li>
                                <li>Moderate dehydration: 6-9% of body weight</li>
                                <li>Severe dehydration: ≥10% of body weight</li>
                                <li>Deficit (mL) = % dehydration × weight (kg) × 10</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 4-2-1 Rule Information Modal -->
<div class="modal fade" id="rulesModal" tabindex="-1" aria-labelledby="rulesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="rulesModalLabel">The 4-2-1 Rule Explained</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>The 4-2-1 rule is a commonly used method to calculate maintenance fluid requirements, particularly in pediatric patients. It is based on the patient's weight and accounts for the physiological principle that smaller patients have proportionally higher fluid requirements per kilogram.</p>
                
                <h6 class="mt-4">The rule works as follows:</h6>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Weight Range</th>
                            <th>Hourly Fluid Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>First 10 kg</td>
                            <td>4 mL/kg/hour</td>
                        </tr>
                        <tr>
                            <td>Second 10 kg (10-20 kg)</td>
                            <td>2 mL/kg/hour</td>
                        </tr>
                        <tr>
                            <td>Additional kg (>20 kg)</td>
                            <td>1 mL/kg/hour</td>
                        </tr>
                    </tbody>
                </table>
                
                <h6 class="mt-4">Examples:</h6>
                <ul>
                    <li><strong>8 kg child:</strong> 8 kg × 4 mL/kg/hr = 32 mL/hr</li>
                    <li><strong>15 kg child:</strong> (10 kg × 4 mL/kg/hr) + (5 kg × 2 mL/kg/hr) = 40 mL/hr + 10 mL/hr = 50 mL/hr</li>
                    <li><strong>25 kg child:</strong> (10 kg × 4 mL/kg/hr) + (10 kg × 2 mL/kg/hr) + (5 kg × 1 mL/kg/hr) = 40 mL/hr + 20 mL/hr + 5 mL/hr = 65 mL/hr</li>
                </ul>
                
                <div class="alert alert-info mt-4">
                    <strong>Historical Context:</strong> The 4-2-1 rule was developed based on the estimated caloric expenditure of patients of different weights. Each 100 calories expended requires approximately 100-120 mL of water, leading to these approximate hourly rates.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Hourly Rate Calculation Modal -->
<div class="modal fade" id="hourlyModal" tabindex="-1" aria-labelledby="hourlyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="hourlyModalLabel">Hourly Rate Calculation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>The hourly fluid rate is calculated directly using the 4-2-1 rule:</p>
                
                <div class="card mb-3">
                    <div class="card-body">
                        <h6>For weight ≤ 10 kg:</h6>
                        <p>Rate (mL/hr) = Weight (kg) × 4</p>
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-body">
                        <h6>For weight > 10 kg and ≤ 20 kg:</h6>
                        <p>Rate (mL/hr) = 40 + (Weight - 10) × 2</p>
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-body">
                        <h6>For weight > 20 kg:</h6>
                        <p>Rate (mL/hr) = 60 + (Weight - 20) × 1</p>
                    </div>
                </div>
                
                <p class="text-muted mt-3">Note: Results are typically rounded to the nearest whole number for practical administration.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Daily Volume Calculation Modal -->
<div class="modal fade" id="dailyModal" tabindex="-1" aria-labelledby="dailyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="dailyModalLabel">Daily Volume Calculation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>The daily fluid volume is calculated by multiplying the hourly rate by 24 hours:</p>
                
                <div class="card mb-3">
                    <div class="card-body">
                        <h6>Daily Volume Formula:</h6>
                        <p>Volume (mL/24 hr) = Hourly Rate (mL/hr) × 24</p>
                    </div>
                </div>
                
                <h6 class="mt-4">Alternative Daily Calculation:</h6>
                <p>The daily maintenance fluid can also be calculated directly using these weight-based formulas:</p>
                
                <ul>
                    <li><strong>For first 10 kg:</strong> 100 mL/kg/day</li>
                    <li><strong>For second 10 kg:</strong> 50 mL/kg/day</li>
                    <li><strong>For each kg above 20 kg:</strong> 20 mL/kg/day</li>
                </ul>
                
                <p class="text-muted mt-3">These methods are mathematically equivalent (daily formulas are simply the hourly rates × 24).</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const weightInput = document.getElementById('weight');
    const hourlyRateElement = document.getElementById('hourly-rate');
    const dailyVolumeElement = document.getElementById('daily-volume');
    const formulaUsedElement = document.getElementById('formula-used');
    
    // Weight adjustment buttons
    const decreaseWeightSmall = document.getElementById('decrease-weight-small');
    const decreaseWeight = document.getElementById('decrease-weight');
    const increaseWeight = document.getElementById('increase-weight');
    const increaseWeightSmall = document.getElementById('increase-weight-small');
    
    // Weight preset buttons
    const weightPresetButtons = document.querySelectorAll('.weight-preset');
    
    // Calculate fluid requirements based on weight
    function calculateFluidRequirements() {
        const weight = parseFloat(weightInput.value);
        let hourlyRate = 0;
        let formula = "";
        
        if (isNaN(weight) || weight <= 0) {
            hourlyRate = 0;
            formula = "Invalid weight";
        } else if (weight <= 10) {
            // For first 10 kg: 4 mL/kg/hour
            hourlyRate = weight * 4;
            formula = `4 mL/kg/hr × ${weight} kg = ${hourlyRate} mL/hr`;
        } else if (weight <= 20) {
            // For 10-20 kg: 40 mL/hour + 2 mL/kg/hour for each kg above 10
            hourlyRate = 40 + (weight - 10) * 2;
            formula = `40 mL/hr + 2 mL/kg/hr × (${weight} - 10) kg = ${hourlyRate} mL/hr`;
        } else {
            // For >20 kg: 60 mL/hour + 1 mL/kg/hour for each kg above 20
            hourlyRate = 60 + (weight - 20) * 1;
            formula = `60 mL/hr + 1 mL/kg/hr × (${weight} - 20) kg = ${hourlyRate} mL/hr`;
        }
        
        // Round to nearest whole number
        hourlyRate = Math.round(hourlyRate);
        
        // Calculate daily volume (24 hours)
        const dailyVolume = hourlyRate * 24;
        
        // Update display
        hourlyRateElement.textContent = hourlyRate;
        dailyVolumeElement.textContent = dailyVolume;
        formulaUsedElement.textContent = formula;
    }
    
    // Update weight and recalculate (with min value protection)
    function updateWeight(newValue) {
        const value = parseFloat(newValue);
        if (!isNaN(value) && value >= 0) {
            weightInput.value = value;
            calculateFluidRequirements();
        }
    }
    
    // Add event listeners
    weightInput.addEventListener('input', calculateFluidRequirements);
    
    decreaseWeightSmall.addEventListener('click', function() {
        const currentWeight = parseFloat(weightInput.value);
        if (!isNaN(currentWeight) && currentWeight >= 0.1) {
            updateWeight((currentWeight - 0.1).toFixed(1));
        }
    });
    
    decreaseWeight.addEventListener('click', function() {
        const currentWeight = parseFloat(weightInput.value);
        if (!isNaN(currentWeight) && currentWeight >= 1) {
            updateWeight((currentWeight - 1).toFixed(1));
        }
    });
    
    increaseWeight.addEventListener('click', function() {
        const currentWeight = parseFloat(weightInput.value);
        if (!isNaN(currentWeight)) {
            updateWeight((currentWeight + 1).toFixed(1));
        }
    });
    
    increaseWeightSmall.addEventListener('click', function() {
        const currentWeight = parseFloat(weightInput.value);
        if (!isNaN(currentWeight)) {
            updateWeight((currentWeight + 0.1).toFixed(1));
        }
    });
    
    // Add event listeners for weight presets
    weightPresetButtons.forEach(button => {
        button.addEventListener('click', function() {
            const presetWeight = parseFloat(this.getAttribute('data-weight'));
            if (!isNaN(presetWeight)) {
                updateWeight(presetWeight);
            }
        });
    });
    
    // Initial calculation
    calculateFluidRequirements();
});
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>