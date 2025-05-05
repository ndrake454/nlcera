<?php
/**
 * Ideal Body Weight Calculator Tool
 * 
 * Place this file in: /tools/ibw.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'Ideal Body Weight Calculator';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Ideal Body Weight Calculator</h1>
                </div>
                <div class="card-body">
                    <p class="lead">Calculate ideal body weight for medication dosing, ventilator settings, and clinical decision making.</p>
                    
                    <div class="d-flex align-items-center mb-2">
                        <h4 class="h5 mb-0 me-2">Calculation Method</h4>
                        <button type="button" class="info-btn" data-formula="true">
                            <i class="ti ti-info-circle"></i>
                        </button>
                    </div>
                    <p class="text-muted">Uses the Devine formula (most widely used) with adjustments for pediatric patients.</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header section-header-treatment">
                    <h2 class="h5 mb-0"><i class="ti ti-ruler me-2"></i>Patient Information</h2>
                </div>
                <div class="card-body">
                    <form id="ibwForm">
                        <!-- Units Toggle Switch -->
                        <div class="mb-4">
                            <div class="btn-group units-toggle w-100">
                                <input type="radio" class="btn-check calc-trigger" name="units" id="imperialUnits" value="imperial" checked>
                                <label class="btn btn-outline-primary" for="imperialUnits">Imperial (ft/in, lbs)</label>
                                
                                <input type="radio" class="btn-check calc-trigger" name="units" id="metricUnits" value="metric">
                                <label class="btn btn-outline-primary" for="metricUnits">Metric (cm, kg)</label>
                            </div>
                        </div>
                    
                        <!-- Gender Selection -->
                        <div class="mb-4">
                            <label class="form-label">Gender</label>
                            <div class="btn-group gender-toggle w-100" role="group">
                                <input type="radio" class="btn-check calc-trigger" name="gender" id="maleGender" value="male" checked>
                                <label class="btn btn-outline-primary" for="maleGender">Male</label>
                                
                                <input type="radio" class="btn-check calc-trigger" name="gender" id="femaleGender" value="female">
                                <label class="btn btn-outline-primary" for="femaleGender">Female</label>
                            </div>
                        </div>
                        
                        <!-- Age Toggle (Adult/Pediatric) -->
                        <div class="mb-4">
                            <label class="form-label">Age Group</label>
                            <div class="btn-group age-toggle w-100" role="group">
                                <input type="radio" class="btn-check calc-trigger" name="ageGroup" id="adultAge" value="adult" checked>
                                <label class="btn btn-outline-primary" for="adultAge">Adult</label>
                                
                                <input type="radio" class="btn-check calc-trigger" name="ageGroup" id="pediatricAge" value="pediatric">
                                <label class="btn btn-outline-primary" for="pediatricAge">Pediatric</label>
                            </div>
                        </div>
                        
                        <!-- Height Input (Imperial) -->
                        <div id="imperialHeightGroup" class="mb-4">
                            <label class="form-label d-flex align-items-center">
                                Height
                                <button type="button" class="info-btn ms-2" data-info="Enter the patient's height in inches. For most accurate results, measure height rather than using stated height.">
                                    <i class="ti ti-info-circle"></i>
                                </button>
                            </label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" id="decrementHeight">
                                    <i class="ti ti-minus"></i>
                                </button>
                                <input type="number" class="form-control form-control-lg calc-trigger text-center" id="heightInches" min="24" max="108" value="70">
                                <button type="button" class="btn btn-outline-secondary" id="incrementHeight">
                                    <i class="ti ti-plus"></i>
                                </button>
                                <span class="input-group-text">in</span>
                            </div>
                            <div class="text-center text-muted mt-1" id="heightFeetDisplay">5ft 10in</div>
                        </div>
                        
                        <!-- Height Input (Metric) -->
                        <div id="metricHeightGroup" class="mb-4" style="display: none;">
                            <label class="form-label d-flex align-items-center">
                                Height
                                <button type="button" class="info-btn ms-2" data-info="Enter the patient's height in centimeters. For most accurate results, measure height rather than using stated height.">
                                    <i class="ti ti-info-circle"></i>
                                </button>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control form-control-lg calc-trigger" id="heightCm" min="30" max="300" value="178">
                                <span class="input-group-text">cm</span>
                            </div>
                        </div>
                        
                        <!-- Weight Input (Imperial) -->
                        <div id="imperialWeightGroup" class="mb-4">
                            <label class="form-label d-flex align-items-center">
                                Actual Weight (optional)
                                <button type="button" class="info-btn ms-2" data-info="Enter the patient's actual weight for calculation of adjusted body weight. This is particularly useful for obese patients when dosing certain medications.">
                                    <i class="ti ti-info-circle"></i>
                                </button>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control form-control-lg calc-trigger" id="weightLbs" min="1" max="1000">
                                <span class="input-group-text">lbs</span>
                            </div>
                        </div>
                        
                        <!-- Weight Input (Metric) -->
                        <div id="metricWeightGroup" class="mb-4" style="display: none;">
                            <label class="form-label d-flex align-items-center">
                                Actual Weight (optional)
                                <button type="button" class="info-btn ms-2" data-info="Enter the patient's actual weight in kilograms for calculation of adjusted body weight. This is particularly useful for obese patients when dosing certain medications.">
                                    <i class="ti ti-info-circle"></i>
                                </button>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control form-control-lg calc-trigger" id="weightKg" min="1" max="500">
                                <span class="input-group-text">kg</span>
                            </div>
                        </div>
                        
                        <!-- Age Input for Pediatrics -->
                        <div id="pediatricAgeGroup" class="mb-4" style="display: none;">
                            <label class="form-label d-flex align-items-center">
                                Age
                                <button type="button" class="info-btn ms-2" data-info="Enter the patient's age in years. For infants less than 1 year old, use decimal values (e.g., 0.5 for 6 months).">
                                    <i class="ti ti-info-circle"></i>
                                </button>
                            </label>
                            <div class="input-group">
                                <input type="number" class="form-control form-control-lg calc-trigger" id="ageYears" min="0" max="17" step="0.1" value="10">
                                <span class="input-group-text">years</span>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4">
                            <button type="button" id="resetBtn" class="btn btn-outline-secondary btn-lg">
                                <i class="ti ti-refresh me-2"></i>Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4" id="resultCard">
                <div class="card-header section-header-note">
                    <h2 class="h5 mb-0"><i class="ti ti-calculator me-2"></i>Results</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="result-box">
                                <h3 class="h5 mb-3">Ideal Body Weight (IBW)</h3>
                                <div class="d-flex align-items-center">
                                    <span class="display-4 me-2" id="ibwResult">--</span>
                                    <span class="h5 mt-2">kg</span>
                                    <button type="button" class="info-btn ms-3" data-info="Ideal Body Weight (IBW) is a theoretical weight that is believed to be healthiest for a person based on their height, gender, and frame. It's commonly used for medication dosing and ventilator settings.">
                                        <i class="ti ti-info-circle"></i>
                                    </button>
                                </div>
                                <div id="ibwLbs" class="text-muted mt-1">-- lbs</div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="result-box" id="abwSection">
                                <h3 class="h5 mb-3">Adjusted Body Weight (ABW)</h3>
                                <div class="d-flex align-items-center">
                                    <span class="display-4 me-2" id="abwResult">--</span>
                                    <span class="h5 mt-2">kg</span>
                                    <button type="button" class="info-btn ms-3" data-info="Adjusted Body Weight (ABW) is used for dosing certain medications in overweight patients. ABW = IBW + 0.4(Actual Weight - IBW). Use for medications that don't distribute well into adipose tissue.">
                                        <i class="ti ti-info-circle"></i>
                                    </button>
                                </div>
                                <div id="abwLbs" class="text-muted mt-1">-- lbs</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="result-box">
                                <h3 class="h5 mb-3">Dosing Weight</h3>
                                <p class="mb-2">Recommended weight to use for medication dosing:</p>
                                <div class="fw-bold fs-4" id="dosingWeight">--</div>
                                <div class="text-muted mt-2" id="dosingExplanation">
                                    Complete the form to see recommendations
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="result-box" id="bmiSection">
                                <h3 class="h5 mb-3">Body Mass Index (BMI)</h3>
                                <div class="d-flex align-items-center">
                                    <span class="display-4 me-2" id="bmiResult">--</span>
                                    <span class="h5 mt-2">kg/m²</span>
                                    <button type="button" class="info-btn ms-3" data-info="BMI = weight(kg) / height(m)². Classification: <18.5 = Underweight, 18.5-24.9 = Normal, 25-29.9 = Overweight, >30 = Obese, >40 = Severely Obese.">
                                        <i class="ti ti-info-circle"></i>
                                    </button>
                                </div>
                                <div id="bmiCategory" class="text-muted mt-1">--</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<!-- Clinical Applications section removed as requested -->
</div>

<!-- Formula Info Modal -->
<div class="modal fade" id="formulaModal" tabindex="-1" aria-labelledby="formulaModalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="formulaModalTitle">Calculation Formulas</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Devine Formula (for Adults)</h6>
                <ul>
                    <li><strong>Males:</strong> IBW (kg) = 50 kg + 2.3 kg × (height in inches - 60)</li>
                    <li><strong>Females:</strong> IBW (kg) = 45.5 kg + 2.3 kg × (height in inches - 60)</li>
                </ul>
                <p>This formula applies for adults over 5 feet (60 inches). For patients under 5 feet, subtract 2.3 kg for each inch below 60 inches.</p>
                
                <h6>Pediatric IBW Calculations</h6>
                <ul>
                    <li><strong>Infants (< 12 months):</strong> Weight (kg) = (age in months + 9) / 2</li>
                    <li><strong>Children (1-10 years):</strong> Weight (kg) = (2 × age in years) + 8</li>
                    <li><strong>Older Children:</strong> We use age-based nomograms</li>
                </ul>
                
                <h6>Adjusted Body Weight (ABW)</h6>
                <p>ABW = IBW + 0.4 × (Actual Weight - IBW)</p>
                <p>Used for dosing medications in obese patients when the drug doesn't distribute well into fat tissue.</p>
                
                <h6>Body Mass Index (BMI)</h6>
                <p>BMI = Weight (kg) / [Height (m)]²</p>
                <p>Classifications:</p>
                <ul>
                    <li>Underweight: < 18.5 kg/m²</li>
                    <li>Normal weight: 18.5 - 24.9 kg/m²</li>
                    <li>Overweight: 25 - 29.9 kg/m²</li>
                    <li>Obese: 30 - 39.9 kg/m²</li>
                    <li>Severely obese: ≥ 40 kg/m²</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Info Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="infoModalTitle">Information</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="infoModalContent">Detailed information will appear here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for the IBW calculator */
.units-toggle, .gender-toggle, .age-toggle {
    width: 100%;
}

.units-toggle .btn, .gender-toggle .btn, .age-toggle .btn {
    width: 50%;
    padding: 0.75rem;
    font-weight: 500;
}

.input-group-text {
    min-width: 3rem;
    justify-content: center;
}

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
    flex-shrink: 0;
}

.info-btn:hover {
    transform: scale(1.1);
    background-color: var(--secondary-color);
}

.result-box {
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    padding: 1.5rem;
    height: 100%;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

/* Print styles */
@media print {
    button, .info-btn, #resetBtn {
        display: none !important;
    }
    
    input, select {
        border: none !important;
        box-shadow: none !important;
    }
    
    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
    }
    
    .result-box {
        box-shadow: none !important;
        border: 1px solid #dee2e6 !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Element references
    const unitRadios = document.querySelectorAll('input[name="units"]');
    const ageGroupRadios = document.querySelectorAll('input[name="ageGroup"]');
    const imperialHeightGroup = document.getElementById('imperialHeightGroup');
    const metricHeightGroup = document.getElementById('metricHeightGroup');
    const imperialWeightGroup = document.getElementById('imperialWeightGroup');
    const metricWeightGroup = document.getElementById('metricWeightGroup');
    const pediatricAgeGroup = document.getElementById('pediatricAgeGroup');
    const resetBtn = document.getElementById('resetBtn');
    const resultCard = document.getElementById('resultCard');
    const abwSection = document.getElementById('abwSection');
    const bmiSection = document.getElementById('bmiSection');
    
    // Results elements
    const ibwResult = document.getElementById('ibwResult');
    const ibwLbs = document.getElementById('ibwLbs');
    const abwResult = document.getElementById('abwResult');
    const abwLbs = document.getElementById('abwLbs');
    const bmiResult = document.getElementById('bmiResult');
    const bmiCategory = document.getElementById('bmiCategory');
    const dosingWeight = document.getElementById('dosingWeight');
    const dosingExplanation = document.getElementById('dosingExplanation');
    
    // Increment/decrement height buttons
    document.getElementById('incrementHeight').addEventListener('click', function() {
        const heightInput = document.getElementById('heightInches');
        const currentHeight = parseInt(heightInput.value) || 0;
        if (currentHeight < parseInt(heightInput.max)) {
            heightInput.value = currentHeight + 1;
            updateHeightDisplay();
            calculateIBW();
        }
    });
    
    document.getElementById('decrementHeight').addEventListener('click', function() {
        const heightInput = document.getElementById('heightInches');
        const currentHeight = parseInt(heightInput.value) || 0;
        if (currentHeight > parseInt(heightInput.min)) {
            heightInput.value = currentHeight - 1;
            updateHeightDisplay();
            calculateIBW();
        }
    });
    
    // Update height display (feet and inches)
    function updateHeightDisplay() {
        const totalInches = parseInt(document.getElementById('heightInches').value) || 0;
        const feet = Math.floor(totalInches / 12);
        const inches = totalInches % 12;
        document.getElementById('heightFeetDisplay').textContent = `${feet}ft ${inches}in`;
    }
    
    // Initialize height display
    updateHeightDisplay();
    
    // Add listener to update display when height changes
    document.getElementById('heightInches').addEventListener('input', updateHeightDisplay);
    
    // Toggle between imperial and metric units
    unitRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'imperial') {
                imperialHeightGroup.style.display = 'block';
                imperialWeightGroup.style.display = 'block';
                metricHeightGroup.style.display = 'none';
                metricWeightGroup.style.display = 'none';
                
                // Convert metric to imperial if values exist
                const heightCm = document.getElementById('heightCm').value;
                const weightKg = document.getElementById('weightKg').value;
                
                if (heightCm) {
                    const totalInches = Math.round(heightCm / 2.54);
                    document.getElementById('heightInches').value = totalInches;
                    updateHeightDisplay();
                }
                
                if (weightKg) {
                    const lbs = Math.round(weightKg * 2.2046);
                    document.getElementById('weightLbs').value = lbs;
                }
            } else {
                imperialHeightGroup.style.display = 'none';
                imperialWeightGroup.style.display = 'none';
                metricHeightGroup.style.display = 'block';
                metricWeightGroup.style.display = 'block';
                
                // Convert imperial to metric if values exist
                const totalInches = parseInt(document.getElementById('heightInches').value) || 0;
                const weightLbs = document.getElementById('weightLbs').value;
                
                if (totalInches) {
                    const cm = Math.round(totalInches * 2.54);
                    document.getElementById('heightCm').value = cm;
                }
                
                if (weightLbs) {
                    const kg = Math.round(weightLbs / 2.2046 * 10) / 10;
                    document.getElementById('weightKg').value = kg;
                }
            }
            
            // Calculate when unit changes
            calculateIBW();
        });
    });
    
    // Toggle between adult and pediatric
    ageGroupRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'pediatric') {
                pediatricAgeGroup.style.display = 'block';
            } else {
                pediatricAgeGroup.style.display = 'none';
            }
            
            // Calculate when age group changes
            calculateIBW();
        });
    });
    
    // Show formula modal when formula button is clicked
    document.querySelectorAll('[data-formula="true"]').forEach(button => {
        button.addEventListener('click', function() {
            const formulaModal = new bootstrap.Modal(document.getElementById('formulaModal'));
            formulaModal.show();
        });
    });
    
    // Show info modal when info button is clicked (except formula buttons)
    document.querySelectorAll('.info-btn:not([data-formula="true"])').forEach(button => {
        button.addEventListener('click', function() {
            const infoText = this.getAttribute('data-info');
            document.getElementById('infoModalContent').innerHTML = infoText;
            const infoModal = new bootstrap.Modal(document.getElementById('infoModal'));
            infoModal.show();
        });
    });
    
    // Auto-calculate on input change
    document.querySelectorAll('.calc-trigger').forEach(element => {
        element.addEventListener('input', calculateIBW);
        element.addEventListener('change', calculateIBW);
    });
    
    // Calculate IBW function
    function calculateIBW() {
        // Get values
        let height, weight, gender, ageGroup, age;
        
        gender = document.querySelector('input[name="gender"]:checked').value;
        ageGroup = document.querySelector('input[name="ageGroup"]:checked').value;
        
        // Get height and weight based on selected units
        if (document.getElementById('imperialUnits').checked) {
            height = parseInt(document.getElementById('heightInches').value) || 0;
            
            const weightLbs = document.getElementById('weightLbs').value;
            weight = weightLbs ? parseFloat(weightLbs) / 2.2046 : null; // Convert lbs to kg
        } else {
            const heightCm = parseFloat(document.getElementById('heightCm').value) || 0;
            height = heightCm / 2.54; // Convert cm to inches
            
            weight = parseFloat(document.getElementById('weightKg').value) || null;
        }
        
        // Get age for pediatric calculations
        if (ageGroup === 'pediatric') {
            age = parseFloat(document.getElementById('ageYears').value) || 0;
        }
        
        // Calculate IBW
        let ibw = 0;
        let calculationMethod = '';
        
        if (ageGroup === 'adult') {
            // Use Devine formula for adults
            if (height >= 60) { // 5 feet or taller
                if (gender === 'male') {
                    ibw = 50 + (2.3 * (height - 60));
                    calculationMethod = 'Devine formula for males';
                } else {
                    ibw = 45.5 + (2.3 * (height - 60));
                    calculationMethod = 'Devine formula for females';
                }
            } else {
                // For heights less than 5 feet
                if (gender === 'male') {
                    ibw = 50 - (2.3 * (60 - height));
                    calculationMethod = 'Devine formula (adjusted for height < 5ft)';
                } else {
                    ibw = 45.5 - (2.3 * (60 - height));
                    calculationMethod = 'Devine formula (adjusted for height < 5ft)';
                }
            }
        } else {
            // Pediatric weight estimation
            if (age < 1) {
                // Convert age to months if provided in years
                const ageMonths = age * 12;
                ibw = (ageMonths + 9) / 2;
                calculationMethod = 'Infant formula (age in months + 9) / 2';
            } else if (age <= 10) {
                ibw = (2 * age) + 8;
                calculationMethod = 'Child formula (2 × age + 8)';
            } else {
                // For children over 10, use age-based nomograms or adult formula with caution
                if (gender === 'male') {
                    ibw = 45 + (2.3 * (height - 60));
                    calculationMethod = 'Adapted Devine formula for adolescent males';
                } else {
                    ibw = 42.2 + (2.3 * (height - 60));
                    calculationMethod = 'Adapted Devine formula for adolescent females';
                }
            }
        }
        
        // Round IBW to one decimal place
        ibw = Math.round(ibw * 10) / 10;
        
        // Calculate ABW if actual weight is provided
        let abw = null;
        if (weight && weight > ibw) {
            abw = ibw + (0.4 * (weight - ibw));
            abw = Math.round(abw * 10) / 10;
            abwSection.style.display = 'block';
            abwResult.textContent = abw;
            abwLbs.textContent = `${Math.round(abw * 2.2046)} lbs`;
        } else {
            abwSection.style.display = 'none';
        }
        
        // Calculate BMI if actual weight is provided
        let bmi = null;
        let bmiCat = '';
        if (weight) {
            // Convert height to meters
            const heightM = (height * 2.54) / 100;
            bmi = weight / (heightM * heightM);
            bmi = Math.round(bmi * 10) / 10;
            
            // Determine BMI category
            if (bmi < 18.5) {
                bmiCat = 'Underweight';
            } else if (bmi < 25) {
                bmiCat = 'Normal weight';
            } else if (bmi < 30) {
                bmiCat = 'Overweight';
            } else if (bmi < 40) {
                bmiCat = 'Obese';
            } else {
                bmiCat = 'Severely obese';
            }
            
            bmiSection.style.display = 'block';
            bmiResult.textContent = bmi;
            bmiCategory.textContent = bmiCat;
        } else {
            bmiSection.style.display = 'none';
        }
        
        // Determine dosing weight recommendation
        let dosingRec = '';
        let dosingExp = '';
        
        if (weight) {
            if (weight > ibw * 1.3) { // More than 30% above IBW
                if (bmi >= 40) { // Severely obese
                    dosingRec = `ABW: ${abw} kg (${Math.round(abw * 2.2046)} lbs)`;
                    dosingExp = 'Adjusted Body Weight is recommended for severely obese patients (BMI ≥ 40)';
                } else if (bmi >= 30) { // Obese
                    dosingRec = `ABW: ${abw} kg (${Math.round(abw * 2.2046)} lbs)`;
                    dosingExp = 'Adjusted Body Weight is typically used for obese patients';
                } else {
                    dosingRec = `IBW: ${ibw} kg (${Math.round(ibw * 2.2046)} lbs)`;
                    dosingExp = 'Ideal Body Weight is recommended for most medications';
                }
            } else if (weight < ibw * 0.9) { // More than 10% below IBW
                dosingRec = `Actual Weight: ${Math.round(weight * 10) / 10} kg (${Math.round(weight * 2.2046)} lbs)`;
                dosingExp = 'Actual weight is used for underweight patients to avoid overdosing';
            } else { // Within normal range
                dosingRec = `IBW: ${ibw} kg (${Math.round(ibw * 2.2046)} lbs)`;
                dosingExp = 'Ideal Body Weight is recommended for most medications';
            }
        } else {
            dosingRec = `IBW: ${ibw} kg (${Math.round(ibw * 2.2046)} lbs)`;
            dosingExp = 'Actual weight not provided. IBW used by default.';
        }
        
        // Display results
        ibwResult.textContent = ibw;
        ibwLbs.textContent = `${Math.round(ibw * 2.2046)} lbs`;
        dosingWeight.textContent = dosingRec;
        dosingExplanation.textContent = dosingExp;
    }
    
    // Reset button handler
    resetBtn.addEventListener('click', function() {
        document.getElementById('ibwForm').reset();
        
        // Reset additional fields
        document.getElementById('heightFeet').value = '5';
        document.getElementById('heightInches').value = '10';
        document.getElementById('heightCm').value = '178';
        document.getElementById('ageYears').value = '10';
        document.getElementById('weightLbs').value = '';
        document.getElementById('weightKg').value = '';
        
        // Reset display of input groups based on current selection
        const currentUnits = document.querySelector('input[name="units"]:checked').value;
        const currentAgeGroup = document.querySelector('input[name="ageGroup"]:checked').value;
        
        if (currentUnits === 'imperial') {
            imperialHeightGroup.style.display = 'block';
            imperialWeightGroup.style.display = 'block';
            metricHeightGroup.style.display = 'none';
            metricWeightGroup.style.display = 'none';
        } else {
            imperialHeightGroup.style.display = 'none';
            imperialWeightGroup.style.display = 'none';
            metricHeightGroup.style.display = 'block';
            metricWeightGroup.style.display = 'block';
        }
        
        if (currentAgeGroup === 'pediatric') {
            pediatricAgeGroup.style.display = 'block';
        } else {
            pediatricAgeGroup.style.display = 'none';
        }
        
        // Recalculate with the reset values
        calculateIBW();
    });
    
    // Initialize calculation when page loads
    calculateIBW();
});
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>