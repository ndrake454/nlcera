<?php
/**
 * Oxygen Tank Duration Calculator
 * 
 * Place this file in: /tool_oxygen.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'Oxygen Tank Duration Calculator';
$active_tab = 'tools';

// Prevent caching for PWA
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Include header
include '../includes/frontend_header.php';

// Output meta tags to prevent caching
echo '<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<!-- Version parameter for cache busting: ' . time() . ' -->';
?>


<!-- Add meta tags to prevent caching -->
<script>
// Add timestamp to force cache refresh
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.getRegistrations().then(function(registrations) {
        for (let registration of registrations) {
            // Attempt to update the service worker
            registration.update();
        }
    });
    
    // Clear caches when possible
    if (window.caches) {
        caches.keys().then(function(cacheNames) {
            cacheNames.forEach(function(cacheName) {
                if (cacheName.includes('oxygen-calculator')) {
                    caches.delete(cacheName);
                }
            });
        });
    }
}

// Set a version parameter for cache busting
window.oxygenCalculatorVersion = '<?php echo time(); ?>';
</script>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Oxygen Tank Duration Calculator</h1>
                </div>
                <div class="card-body">
                    <p class="lead">Calculate how long an oxygen tank will last based on tank size, remaining pressure, and flow rate.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Results Card (Hidden initially) -->
    <div class="row" id="resultsRow" style="display: none;">
        <div class="col-12">
            <div class="card mb-4 mx-auto" style="max-width: 830px;">
                <div class="card-header bg-success text-white">
                    <h2 class="h5 mb-0"><i class="ti ti-clock me-2"></i>Tank Duration Results</h2>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="text-center mb-3 mb-md-0">
                                <div class="display-4 text-primary" id="resultTime">--:--</div>
                                <div class="h5 text-muted">Hours:Minutes</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Tank Size</th>
                                            <td id="resultTankSize">--</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Remaining PSI</th>
                                            <td id="resultPSI">--</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Effective PSI</th>
                                            <td id="resultEffectivePSI">--</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Flow Rate</th>
                                            <td id="resultFlow">--</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Total Minutes</th>
                                            <td id="resultMinutes">--</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <strong><i class="ti ti-alert-circle me-2"></i>Safety Note:</strong> 
                                This calculator uses a 200 PSI safety margin to provide a realistic estimate.
                                Always monitor tank levels and have backup oxygen available.
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <button id="newCalculation" class="btn btn-outline-secondary">
                                <i class="ti ti-refresh me-2"></i>New Calculation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header section-header-reference">
                    <h2 class="h5 mb-0"><i class="ti ti-calculator me-2"></i>Tank Duration Calculator</h2>
                </div>
                <div class="card-body">
                    <form id="tankCalculator" class="row g-3">
                        <!-- Tank Size Selection -->
                        <div class="col-md-6">
                            <label for="tankSize" class="form-label">Tank Size</label>
                            <div class="input-group">
                                <select class="form-select form-select-lg" id="tankSize" required>
                                    <!-- Medical Cylinders -->
                                    <optgroup label="Portable Cylinders">
                                        <option value="D" data-factor="0.16">D Cylinder</option>
                                        <option value="E" data-factor="0.28">E Cylinder</option>
                                    </optgroup>
                                    <!-- Medical Cylinders -->
                                    <optgroup label="Main Cylinders">
                                        <option value="G" data-factor="2.41">G Cylinder</option>
                                        <option value="H/K" data-factor="3.14">H/K Cylinder</option>
                                        <option value="M" data-factor="1.56">M Cylinder</option>
                                        <option value="MM" data-factor="3.14">MM Cylinder</option>
                                    </optgroup>
                                    <!-- Aircraft Systems -->
                                    <optgroup label="Aircraft Systems">
                                        <option value="H125" data-factor="1.48">H125 (3250L)</option>
                                        <option value="PC12" data-factor="1.56">PC12 (3450L)</option>
                                    </optgroup>
                                </select>
                                <button class="btn btn-outline-secondary" type="button" id="tankInfoBtn">
                                    <i class="ti ti-info-circle"></i>
                                </button>
                            </div>
                            <div class="form-text" id="tankSizeInfo">Tank factor: <span id="tankFactor">--</span></div>
                        </div>
                        
                        <!-- Remaining PSI -->
                        <div class="col-md-6">
                            <label for="psi" class="form-label">Remaining PSI</label>
                            <div class="input-group">
                                <input type="number" class="form-control form-control-lg" id="psi" placeholder="e.g., 2000" min="0" max="3000" required>
                                <span class="input-group-text">PSI</span>
                                <button class="btn btn-outline-secondary" type="button" id="psiInfoBtn">
                                    <i class="ti ti-info-circle"></i>
                                </button>
                            </div>
                            <div class="form-text">Full tank is typically 2000-2200 PSI</div>
                        </div>
                        
                        <!-- Flow Rate -->
                        <div class="col-md-6">
                            <label for="flowRate" class="form-label">Flow Rate</label>
                            <div class="input-group">
                                <input type="number" class="form-control form-control-lg" id="flowRate" placeholder="e.g., 15" min="0.1" max="100" step="0.1" required>
                                <span class="input-group-text">LPM</span>
                                <button class="btn btn-outline-secondary" type="button" id="flowInfoBtn">
                                    <i class="ti ti-info-circle"></i>
                                </button>
                            </div>
                            <div class="form-text">Liters per minute</div>
                        </div>
                        
                        <!-- Common Flow Rate Presets -->
                        <div class="col-md-6">
                            <label class="form-label">Common Flow Rates</label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-primary flow-preset" data-flow="1">1 LPM</button>
                                <button type="button" class="btn btn-outline-primary flow-preset" data-flow="2">2 LPM</button>
                                <button type="button" class="btn btn-outline-primary flow-preset" data-flow="4">4 LPM</button>
                                <button type="button" class="btn btn-outline-primary flow-preset" data-flow="6">6 LPM</button>
                                <button type="button" class="btn btn-outline-primary flow-preset" data-flow="10">10 LPM</button>
                                <button type="button" class="btn btn-outline-primary flow-preset" data-flow="15">15 LPM</button>
                                <button type="button" class="btn btn-outline-primary flow-preset" data-flow="25">25 LPM</button>
                                <button type="button" class="btn btn-outline-primary flow-preset" data-flow="40">40 LPM</button>
                                <button type="button" class="btn btn-outline-primary flow-preset" data-flow="60">60 LPM</button>
                            </div>
                        </div>
                        
                        <!-- Calculate Button -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg w-100">Calculate Duration</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    
    
    <!-- Tank Size Reference Card -->
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header section-header-note">
                    <h2 class="h5 mb-0"><i class="ti ti-gas-cylinder me-2"></i>Oxygen Tank Reference</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Tank Size</th>
                                    <th>Capacity (L)</th>
                                    <th>Tank Factor</th>
                                    <th>Typical Use</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Medical Cylinders -->
                                <tr class="table-secondary">
                                    <th colspan="4">Portable Cylinders</th>
                                </tr>
                                <tr>
                                    <td>D Cylinder</td>
                                    <td>350 L</td>
                                    <td>0.16</td>
                                    <td>Portable</td>
                                </tr>
                                <tr>
                                    <td>E Cylinder</td>
                                    <td>625 L</td>
                                    <td>0.28</td>
                                    <td>Portable</td>
                                </tr>
                                    <tr class="table-secondary">
                                    <th colspan="4">Ambulance Main Cylinders</th>
                                </tr>
                                <tr>
                                    <td>M Cylinder</td>
                                    <td>3,000 L</td>
                                    <td>1.56</td>
                                    <td>Ambulance main</td>
                                </tr>
                                <tr>
                                    <td>G Cylinder</td>
                                    <td>5,300 L</td>
                                    <td>2.41</td>
                                    <td>Ambulance main</td>
                                </tr>
                                <tr>
                                    <td>H/K Cylinder</td>
                                    <td>6,900 L</td>
                                    <td>3.14</td>
                                    <td>Ambulance main</td>
                                </tr>
                                <tr>
                                    <td>MM Cylinder</td>
                                    <td>6,900 L</td>
                                    <td>3.14</td>
                                    <td>Ambulance main</td>
                                </tr>
                                
                                <!-- Aircraft Systems -->
                                <tr class="table-secondary">
                                    <th colspan="4">Aircraft Systems</th>
                                </tr>
                                <tr>
                                    <td>H125</td>
                                    <td>3,250 L</td>
                                    <td>1.48</td>
                                    <td>H125 Helicopter supply</td>
                                </tr>
                                <tr>
                                    <td>PC12</td>
                                    <td>3,450 L</td>
                                    <td>1.56</td>
                                    <td>PC12 Airplane supply</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Window for Information -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Information</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="tankSizeModalContent" style="display: none;">
                    <h4>Oxygen Tank Sizes</h4>
                    <p>The tank size determines how much oxygen a cylinder can hold. Each size has a specific "tank factor" used in duration calculations.</p>
                    
                    <h5>Medical Cylinders</h5>
                    <ul>
                        <li><strong>D Cylinder:</strong> Small portable cylinder (350 L capacity, factor: 0.16)</li>
                        <li><strong>E Cylinder:</strong> Standard portable cylinder (625 L capacity, factor: 0.28)</li>
                        <li><strong>M Cylinder:</strong> Medium stationary cylinder (3,000 L capacity, factor: 1.56)</li>
                        <li><strong>G Cylinder:</strong> Large stationary cylinder (5,300 L capacity, factor: 2.41)</li>
                        <li><strong>H/K Cylinder:</strong> Very large stationary cylinder (6,900 L capacity, factor: 3.14)</li>
                        <li><strong>MM Cylinder:</strong> Very large stationary cylinder (6,900 L capacity, factor: 3.14)</li>
                    </ul>
                    
                    <h5>Aircraft Systems</h5>
                    <ul>
                        <li><strong>H125:</strong> Rotor wing aircraft system (3,250 L capacity, factor: 1.48)</li>
                        <li><strong>PC12:</strong> Fixed wing aircraft system (3,450 L capacity, factor: 1.56)</li>
                    </ul>
                    
                    <p>Tank factors are used to convert PSI readings into available oxygen volume.</p>
                </div>
                
                <div id="psiModalContent" style="display: none;">
                    <h4>PSI (Pounds per Square Inch)</h4>
                    <p>PSI is the measurement of pressure inside the oxygen tank.</p>
                    
                    <ul>
                        <li><strong>Full Tank:</strong> Typically 2000-2200 PSI</li>
                        <li><strong>Standard Operating Range:</strong> 500-2000 PSI</li>
                        <li><strong>Reserve Level:</strong> Below 500 PSI (time to replace)</li>
                        <li><strong>Critical Level:</strong> Below 200 PSI (immediate replacement needed)</li>
                    </ul>
                    
                    <p>Always read the pressure gauge perpendicular to the gauge face for accurate readings.</p>
                    
                    <div class="alert alert-warning">
                        <strong>Important:</strong> For safety reasons, never let your tank pressure drop below 200 PSI. 
                        Always have a backup oxygen source available.
                    </div>
                </div>
                
                <div id="flowModalContent" style="display: none;">
                    <h4>Flow Rate (Liters per Minute)</h4>
                    <p>The flow rate determines how quickly oxygen is being delivered to the patient. Different clinical situations require different flow rates.</p>
                    
                    <h5>Common Flow Rates:</h5>
                    <ul>
                        <li><strong>1-2 LPM:</strong> Low-flow oxygen therapy for COPD patients</li>
                        <li><strong>2-4 LPM:</strong> Nasal cannula for mild hypoxemia</li>
                        <li><strong>6-10 LPM:</strong> Moderate to severe hypoxemia</li>
                        <li><strong>10-15 LPM:</strong> Non-rebreather mask for severe hypoxemia</li>
                        <li><strong>15-25 LPM:</strong> Crisis situations, bag-valve-mask ventilation</li>
                        <li><strong>25-60 LPM:</strong> High-flow oxygen therapy, critical patients</li>
                    </ul>
                    
                    <p>Higher flow rates will deplete your oxygen tank more quickly. Always match the flow rate to clinical needs.</p>
                </div>
                
                <div id="calculationModalContent" style="display: none;">
                    <h4>Tank Duration Calculation Formula</h4>
                    <p>The formula used to calculate how long an oxygen tank will last is:</p>
                    
                    <div class="alert alert-primary">
                        <p class="text-center fw-bold">
                            Duration (minutes) = ((PSI - 200) × Tank Factor) ÷ Flow Rate
                        </p>
                    </div>
                    
                    <p><strong>Example:</strong> For an E cylinder at 2000 PSI with a flow rate of 15 LPM:</p>
                    <p>Duration = ((2000 - 200) × 0.28) ÷ 15 = 1800 × 0.28 ÷ 15 = 504 ÷ 15 = 33.6 minutes</p>
                    
                    <h5>Understanding the Formula:</h5>
                    <ul>
                        <li><strong>PSI - 200</strong> subtracts a safety margin of 200 PSI from the current pressure reading</li>
                        <li><strong>Tank Factor</strong> converts PSI to available oxygen volume (liters)</li>
                        <li><strong>Flow Rate</strong> is how many liters per minute are being delivered</li>
                    </ul>
                    
                    <p>The calculation gives you a realistic estimated duration that accounts for the 200 PSI safety margin.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for the oxygen calculator */
.form-control-lg, .form-select-lg {
    height: 54px;
    font-size: 1.1rem;
}

.flow-preset {
    min-width: 70px;
    font-size: 0.9rem;
}

/* Ensure proper centering for results card */
#resultsRow .card {
    margin-left: auto !important;
    margin-right: auto !important;
    width: 100% !important;
    max-width: 830px !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const calculator = document.getElementById('tankCalculator');
    const tankSelect = document.getElementById('tankSize');
    const psiInput = document.getElementById('psi');
    const flowInput = document.getElementById('flowRate');
    const flowPresets = document.querySelectorAll('.flow-preset');
    const tankFactor = document.getElementById('tankFactor');
    const resultsRow = document.getElementById('resultsRow');
    const resultTime = document.getElementById('resultTime');
    const resultTankSize = document.getElementById('resultTankSize');
    const resultPSI = document.getElementById('resultPSI');
    const resultEffectivePSI = document.getElementById('resultEffectivePSI');
    const resultFlow = document.getElementById('resultFlow');
    const resultMinutes = document.getElementById('resultMinutes');
    const newCalcButton = document.getElementById('newCalculation');
    
    // Modal elements
    const infoModal = document.getElementById('infoModal');
    const modalTitle = document.getElementById('modalTitle');
    const tankSizeContent = document.getElementById('tankSizeModalContent');
    const psiContent = document.getElementById('psiModalContent');
    const flowContent = document.getElementById('flowModalContent');
    const calculationContent = document.getElementById('calculationModalContent');
    
    // Info buttons
    const tankInfoBtn = document.getElementById('tankInfoBtn');
    const psiInfoBtn = document.getElementById('psiInfoBtn');
    const flowInfoBtn = document.getElementById('flowInfoBtn');
    
    // Set up modals
    const modal = new bootstrap.Modal(infoModal);
    
    // Update tank factor when tank size changes
    tankSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const factor = selectedOption.getAttribute('data-factor');
        tankFactor.textContent = factor;
    });
    
    // Flow rate preset buttons
    flowPresets.forEach(button => {
        button.addEventListener('click', function() {
            const flow = this.getAttribute('data-flow');
            flowInput.value = flow;
        });
    });
    
    // Info button events
    tankInfoBtn.addEventListener('click', function() {
        modalTitle.textContent = 'Oxygen Tank Sizes';
        hideAllModalContent();
        tankSizeContent.style.display = 'block';
        modal.show();
    });
    
    psiInfoBtn.addEventListener('click', function() {
        modalTitle.textContent = 'PSI (Pressure) Information';
        hideAllModalContent();
        psiContent.style.display = 'block';
        modal.show();
    });
    
    flowInfoBtn.addEventListener('click', function() {
        modalTitle.textContent = 'Flow Rate Information';
        hideAllModalContent();
        flowContent.style.display = 'block';
        modal.show();
    });
    
    // Hide all modal content sections
    function hideAllModalContent() {
        tankSizeContent.style.display = 'none';
        psiContent.style.display = 'none';
        flowContent.style.display = 'none';
        calculationContent.style.display = 'none';
    }
    
    // Calculate tank duration
    calculator.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get values
        const tankOption = tankSelect.options[tankSelect.selectedIndex];
        const tankName = tankOption.text;
        const factor = parseFloat(tankOption.getAttribute('data-factor'));
        const psi = parseFloat(psiInput.value);
        const flowRate = parseFloat(flowInput.value);
        
        // Check for valid inputs
        if (isNaN(factor) || isNaN(psi) || isNaN(flowRate) || flowRate <= 0) {
            alert('Please enter valid values for all fields.');
            return;
        }
        
        // Calculate duration in minutes using the updated formula with 200 PSI safety margin
        const effectivePSI = Math.max(0, psi - 200);
        const durationMinutes = (effectivePSI * factor) / flowRate;
        
        // Convert to hours and minutes
        const hours = Math.floor(durationMinutes / 60);
        const minutes = Math.round(durationMinutes % 60);
        
        // Format time as HH:MM
        const timeFormatted = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
        
        // Update results
        resultTime.textContent = timeFormatted;
        resultTankSize.textContent = tankName;
        resultPSI.textContent = psi + ' PSI';
        resultEffectivePSI.textContent = effectivePSI + ' PSI (after 200 PSI safety margin)';
        resultFlow.textContent = flowRate + ' LPM';
        resultMinutes.textContent = Math.round(durationMinutes) + ' minutes';
        
        // Show results
        resultsRow.style.display = 'block';
        
        // Scroll to results
        resultsRow.scrollIntoView({ behavior: 'smooth' });
    });
    
    // New calculation
    newCalcButton.addEventListener('click', function() {
        resultsRow.style.display = 'none';
        calculator.reset();
        tankFactor.textContent = '--';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    // Add extra info button for the calculation formula
    const formulaBtn = document.createElement('button');
    formulaBtn.classList.add('btn', 'btn-info', 'mt-3', 'w-100');
    formulaBtn.innerHTML = '<i class="ti ti-math me-2"></i>How is this calculated?';
    
    formulaBtn.addEventListener('click', function() {
        modalTitle.textContent = 'Tank Duration Calculation';
        hideAllModalContent();
        calculationContent.style.display = 'block';
        modal.show();
    });
    
    // Add formula button to the form
    calculator.appendChild(formulaBtn);
    
    // Set initial tank factor on page load
    if (tankSelect.options.length > 0) {
        const initialFactor = tankSelect.options[tankSelect.selectedIndex].getAttribute('data-factor');
        if (initialFactor) {
            tankFactor.textContent = initialFactor;
        }
    }
});
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>