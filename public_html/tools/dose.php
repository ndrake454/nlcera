<?php
/**
 * Medication Calculator Tool
 * 
 * Place this file in: /tools/medication_calculator.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'Medication Dosing Calculator';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Medication Calculator</h1>
                </div>
                <div class="card-body">
                    <p class="lead">Calculate medication doses, infusion rates, drip rates, and more with this comprehensive medication calculator.</p>
                    
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="ti ti-info-circle me-3 fs-4"></i>
                        <div>
                            This calculator is designed to assist healthcare providers with medication calculations. Always verify doses and follow your protocol guidelines.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header section-header-reference">
                            <h2 class="h5 mb-0"><i class="ti ti-calculator me-2"></i>Calculator Type</h2>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-primary calculator-type active" data-type="weight-based">
                                    <i class="ti ti-scale me-1"></i> Weight-Based
                                </button>
                                <button type="button" class="btn btn-outline-primary calculator-type" data-type="continuous-infusion">
                                    <i class="ti ti-clock me-1"></i> Continuous Infusion
                                </button>
                                <button type="button" class="btn btn-outline-primary calculator-type" data-type="iv-drip">
                                    <i class="ti ti-droplet me-1"></i> IV Drip Rate
                                </button>
                                <button type="button" class="btn btn-outline-primary calculator-type" data-type="dilution">
                                    <i class="ti ti-flask me-1"></i> Dilution/Reconstitution
                                </button>
                                <button type="button" class="btn btn-outline-primary calculator-type" data-type="dose-conversion">
                                    <i class="ti ti-exchange me-1"></i> Dose Conversion
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Calculator sections -->
            <div class="row">
                <!-- Weight-Based Calculator -->
                <div class="col-12 calculator-section" id="weight-based-section">
                    <div class="card mb-4">
                        <div class="card-header section-header-treatment">
                            <h2 class="h5 mb-0"><i class="ti ti-scale me-2"></i>Weight-Based Dosing</h2>
                        </div>
                        <div class="card-body">
                            <form id="weight-based-form">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="patient-weight" class="form-label">Patient Weight</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="patient-weight" min="0" step="0.1" value="70">
                                                <select class="form-select" id="weight-unit" style="max-width: 80px;">
                                                    <option value="kg" selected>kg</option>
                                                    <option value="lb">lb</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="dose-per-weight" class="form-label">Dose Per Weight</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="dose-per-weight" min="0" step="0.01" value="5">
                                                <input type="text" class="form-control" id="dose-unit" value="mg" style="max-width: 80px;">
                                                <span class="input-group-text">/</span>
                                                <select class="form-select" id="per-weight-unit" style="max-width: 80px;">
                                                    <option value="kg" selected>kg</option>
                                                    <option value="lb">lb</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="medication-concentration" class="form-label">Medication Concentration (optional)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="medication-concentration" min="0" step="0.1" value="10">
                                                <input type="text" class="form-control" id="concentration-unit" value="mg" style="max-width: 80px;">
                                                <span class="input-group-text">/</span>
                                                <input type="text" class="form-control" id="volume-unit" value="mL" style="max-width: 80px;">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="card h-100 bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title text-center mb-4">Results</h5>
                                                
                                                <div class="row mb-3">
                                                    <div class="col-sm-6 mb-3">
                                                        <div class="text-center">
                                                            <h6>Total Dose</h6>
                                                            <p class="display-6 mb-0" id="total-dose">350</p>
                                                            <p class="text-muted" id="total-dose-unit">mg</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 mb-3">
                                                        <div class="text-center">
                                                            <h6>Volume to Administer</h6>
                                                            <p class="display-6 mb-0" id="volume-to-administer">35</p>
                                                            <p class="text-muted" id="volume-to-administer-unit">mL</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="alert alert-primary">
                                                    <div class="d-flex">
                                                        <i class="ti ti-calculator me-2 fs-5"></i>
                                                        <div>
                                                            <strong>Formula:</strong> <span id="weight-based-formula">5 mg/kg × 70 kg = 350 mg</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Common Medication Presets -->
                                <div class="mt-4">
                                    <h6 class="mb-2">Common Weight-Based Medication Presets:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary weight-med-preset" 
                                                data-dose="0.01" data-unit="mg" data-concentration="1" data-volunit="mg/mL">
                                            Epinephrine 0.01 mg/kg
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary weight-med-preset"
                                                data-dose="5" data-unit="mg" data-concentration="10" data-volunit="mg/mL">
                                            Diazepam 5 mg/kg
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary weight-med-preset"
                                                data-dose="0.1" data-unit="mg" data-concentration="0.4" data-volunit="mg/mL">
                                            Atropine 0.1 mg/kg
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary weight-med-preset"
                                                data-dose="1.5" data-unit="mg" data-concentration="50" data-volunit="mg/mL">
                                            Magnesium 1.5 mg/kg
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary weight-med-preset"
                                                data-dose="0.1" data-unit="mg" data-concentration="0.1" data-volunit="mg/mL">
                                            Fentanyl 0.1 mg/kg
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning mt-4">
                                    <div class="d-flex">
                                        <i class="ti ti-alert-triangle me-2 fs-5"></i>
                                        <div>
                                            <strong>Clinical Note:</strong> Always double-check your calculations and verify the 
                                            appropriate dosage based on the patient's clinical condition and your local protocols.
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Continuous Infusion Calculator -->
                <div class="col-12 calculator-section" id="continuous-infusion-section" style="display: none;">
                    <div class="card mb-4">
                        <div class="card-header section-header-treatment">
                            <h2 class="h5 mb-0"><i class="ti ti-clock me-2"></i>Continuous Infusion</h2>
                        </div>
                        <div class="card-body">
                            <form id="infusion-form">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="infusion-patient-weight" class="form-label">Patient Weight (optional)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="infusion-patient-weight" min="0" step="0.1" value="70">
                                                <select class="form-select" id="infusion-weight-unit" style="max-width: 80px;">
                                                    <option value="kg" selected>kg</option>
                                                    <option value="lb">lb</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="drug-amount" class="form-label">Drug Amount</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="drug-amount" min="0" step="0.1" value="500">
                                                <input type="text" class="form-control" id="drug-unit" value="mg" style="max-width: 80px;">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="solution-volume" class="form-label">Solution Volume</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="solution-volume" min="0" step="1" value="250">
                                                <input type="text" class="form-control" id="solution-unit" value="mL" style="max-width: 80px;">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="infusion-rate" class="form-label">Desired Infusion Rate</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="infusion-rate" min="0" step="0.1" value="5">
                                                <select class="form-select" id="infusion-rate-unit" style="max-width: 150px;">
                                                    <option value="mcg/min">mcg/min</option>
                                                    <option value="mcg/kg/min">mcg/kg/min</option>
                                                    <option value="mg/hr" selected>mg/hr</option>
                                                    <option value="mg/kg/hr">mg/kg/hr</option>
                                                    <option value="mg/min">mg/min</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="card h-100 bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title text-center mb-4">Results</h5>
                                                
                                                <div class="row mb-3">
                                                    <div class="col-sm-6 mb-3">
                                                        <div class="text-center">
                                                            <h6>Flow Rate</h6>
                                                            <p class="display-6 mb-0" id="flow-rate">2.5</p>
                                                            <p class="text-muted">mL/hr</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 mb-3">
                                                        <div class="text-center">
                                                            <h6>Concentration</h6>
                                                            <p class="display-6 mb-0" id="drug-concentration">2</p>
                                                            <p class="text-muted" id="concentration-result-unit">mg/mL</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="alert alert-primary">
                                                    <div class="d-flex">
                                                        <i class="ti ti-calculator me-2 fs-5"></i>
                                                        <div>
                                                            <strong>Formula:</strong> <span id="infusion-formula">Flow Rate (mL/hr) = (Dose (mg/hr) × Volume (mL)) ÷ Drug Amount (mg)</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Common Infusion Presets -->
                                <div class="mt-4">
                                    <h6 class="mb-2">Common Infusion Presets:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary infusion-preset" 
                                                data-drug="50" data-drug-unit="mg" data-volume="250" data-rate="1" data-rate-unit="mg/hr">
                                            Amiodarone 50mg in 250mL
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary infusion-preset" 
                                                data-drug="100" data-drug-unit="mg" data-volume="100" data-rate="2" data-rate-unit="mg/hr">
                                            Lidocaine 100mg in 100mL
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary infusion-preset" 
                                                data-drug="500" data-drug-unit="mcg" data-volume="100" data-rate="2" data-rate-unit="mcg/min">
                                            Epinephrine 0.5mg in 100mL
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary infusion-preset" 
                                                data-drug="50" data-drug-unit="mg" data-volume="50" data-rate="2" data-rate-unit="mcg/kg/min">
                                            Dopamine 50mg in 50mL
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning mt-4">
                                    <div class="d-flex">
                                        <i class="ti ti-alert-triangle me-2 fs-5"></i>
                                        <div>
                                            <strong>Clinical Note:</strong> Verify all continuous infusion calculations with a second provider. 
                                            For weight-based infusions, regularly reassess if the patient's weight changes.
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- IV Drip Rate Calculator -->
                <div class="col-12 calculator-section" id="iv-drip-section" style="display: none;">
                    <div class="card mb-4">
                        <div class="card-header section-header-treatment">
                            <h2 class="h5 mb-0"><i class="ti ti-droplet me-2"></i>IV Drip Rate</h2>
                        </div>
                        <div class="card-body">
                            <form id="drip-form">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="fluid-volume" class="form-label">Total Volume to Infuse</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="fluid-volume" min="0" step="1" value="1000">
                                                <span class="input-group-text">mL</span>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="infusion-time" class="form-label">Infusion Time</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="infusion-time" min="0" step="0.25" value="8">
                                                <select class="form-select" id="time-unit" style="max-width: 100px;">
                                                    <option value="min">Minutes</option>
                                                    <option value="hr" selected>Hours</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="drop-factor" class="form-label">Drop Factor</label>
                                            <div class="input-group">
                                                <select class="form-select" id="drop-factor">
                                                    <option value="10">10 drops/mL (Blood)</option>
                                                    <option value="15">15 drops/mL (Standard)</option>
                                                    <option value="20" selected>20 drops/mL (Standard)</option>
                                                    <option value="60">60 drops/mL (Microdrip)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="card h-100 bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title text-center mb-4">Results</h5>
                                                
                                                <div class="row mb-3">
                                                    <div class="col-sm-6 mb-3">
                                                        <div class="text-center">
                                                            <h6>Flow Rate</h6>
                                                            <p class="display-6 mb-0" id="flow-rate-ml">125</p>
                                                            <p class="text-muted">mL/hr</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 mb-3">
                                                        <div class="text-center">
                                                            <h6>Drip Rate</h6>
                                                            <p class="display-6 mb-0" id="drip-rate">42</p>
                                                            <p class="text-muted">drops/min</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="alert alert-primary">
                                                    <div class="d-flex">
                                                        <i class="ti ti-calculator me-2 fs-5"></i>
                                                        <div>
                                                            <strong>Formula:</strong> <span id="drip-formula">Drops/min = (Volume (mL) × Drop factor) ÷ (Time (min))</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Common IV Fluid Presets -->
                                <div class="mt-4">
                                    <h6 class="mb-2">Common IV Fluid Presets:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary drip-preset" 
                                                data-volume="1000" data-time="8" data-factor="20">
                                            NS 1L over 8 hours
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary drip-preset" 
                                                data-volume="500" data-time="4" data-factor="20">
                                            D5W 500mL over 4 hours
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary drip-preset" 
                                                data-volume="250" data-time="60" data-factor="60">
                                            Medication 250mL over 1 hour (microdrip)
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary drip-preset" 
                                                data-volume="100" data-time="30" data-factor="60">
                                            Antibiotic 100mL over 30 min
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning mt-4">
                                    <div class="d-flex">
                                        <i class="ti ti-alert-triangle me-2 fs-5"></i>
                                        <div>
                                            <strong>Clinical Note:</strong> When counting drops, count for at least 15 seconds 
                                            and multiply accordingly to get drops per minute. Always use a pump for critical medications.
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Dilution/Reconstitution Calculator -->
                <div class="col-12 calculator-section" id="dilution-section" style="display: none;">
                    <div class="card mb-4">
                        <div class="card-header section-header-treatment">
                            <h2 class="h5 mb-0"><i class="ti ti-flask me-2"></i>Dilution/Reconstitution</h2>
                        </div>
                        <div class="card-body">
                            <form id="dilution-form">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="drug-quantity" class="form-label">Drug Quantity</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="drug-quantity" min="0" step="0.1" value="100">
                                                <input type="text" class="form-control" id="drug-quantity-unit" value="mg" style="max-width: 80px;">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <h6>Reconstitution Method</h6>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="reconMethod" id="methodDesiredVolume" checked>
                                                <label class="form-check-label" for="methodDesiredVolume">
                                                    I want to add a specific volume of diluent
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="reconMethod" id="methodDesiredConcentration">
                                                <label class="form-check-label" for="methodDesiredConcentration">
                                                    I want a specific final concentration
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div id="desiredVolumeSection">
                                            <div class="mb-3">
                                                <label for="diluent-volume" class="form-label">Diluent Volume to Add</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="diluent-volume" min="0" step="0.1" value="10">
                                                    <span class="input-group-text">mL</span>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id="desiredConcentrationSection" style="display: none;">
                                            <div class="mb-3">
                                                <label for="desired-concentration" class="form-label">Desired Concentration</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="desired-concentration" min="0" step="0.1" value="10">
                                                    <input type="text" class="form-control" id="concentration-unit-input" value="mg" style="max-width: 70px;">
                                                    <span class="input-group-text">/</span>
                                                    <span class="input-group-text">mL</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="card h-100 bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title text-center mb-4">Results</h5>
                                                
                                                <div class="mb-4">
                                                    <div class="text-center" id="dilutionVolumeResult">
                                                        <h6>Final Solution Volume</h6>
                                                        <p class="display-6 mb-0" id="final-volume">10</p>
                                                        <p class="text-muted">mL</p>
                                                    </div>
                                                    
                                                    <div class="text-center" id="dilutionConcentrationResult" style="display: none;">
                                                        <h6>Diluent Volume to Add</h6>
                                                        <p class="display-6 mb-0" id="required-volume">10</p>
                                                        <p class="text-muted">mL</p>
                                                    </div>
                                                </div>
                                                
                                                <div class="text-center">
                                                    <h6>Final Concentration</h6>
                                                    <p class="display-6 mb-0" id="final-concentration">10</p>
                                                    <p class="text-muted" id="final-concentration-unit">mg/mL</p>
                                                </div>
                                                
                                                <div class="alert alert-primary mt-3">
                                                    <div class="d-flex">
                                                        <i class="ti ti-calculator me-2 fs-5"></i>
                                                        <div>
                                                            <strong>Formula:</strong> <span id="dilution-formula">Concentration = Drug Amount ÷ Total Volume</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Common Dilution Presets -->
                                <div class="mt-4">
                                    <h6 class="mb-2">Common Dilution Presets:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dilution-preset" 
                                                data-drug="1" data-unit="g" data-method="concentration" data-concentration="100" data-conc-unit="mg">
                                            Ceftriaxone 1g to 100mg/mL
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary dilution-preset" 
                                                data-drug="500" data-unit="mg" data-method="volume" data-volume="5">
                                            Vancomycin 500mg + 5mL
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary dilution-preset" 
                                                data-drug="1" data-unit="mg" data-method="volume" data-volume="10">
                                            Epinephrine 1mg + 10mL (1:10,000)
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary dilution-preset" 
                                                data-drug="10" data-unit="mg" data-method="concentration" data-concentration="1" data-conc-unit="mg">
                                            Midazolam 10mg to 1mg/mL
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning mt-4">
                                    <div class="d-flex">
                                        <i class="ti ti-alert-triangle me-2 fs-5"></i>
                                        <div>
                                            <strong>Clinical Note:</strong> Always check the manufacturer's guidelines for specific reconstitution 
                                            instructions. Some medications may require specific diluents or have solubility limitations.
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Dose Conversion Calculator -->
                <div class="col-12 calculator-section" id="dose-conversion-section" style="display: none;">
                    <div class="card mb-4">
                        <div class="card-header section-header-treatment">
                            <h2 class="h5 mb-0"><i class="ti ti-exchange me-2"></i>Dose Conversion</h2>
                        </div>
                        <div class="card-body">
                            <form id="conversion-form">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="from-value" class="form-label">From</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="from-value" min="0" step="0.001" value="1">
                                                <select class="form-select" id="from-unit" style="max-width: 150px;">
                                                    <option value="g" selected>grams (g)</option>
                                                    <option value="mg">milligrams (mg)</option>
                                                    <option value="mcg">micrograms (mcg)</option>
                                                    <option value="ng">nanograms (ng)</option>
                                                    <option value="mEq">milliequivalents (mEq)</option>
                                                    <option value="mmol">millimoles (mmol)</option>
                                                    <option value="units">units</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="to-unit" class="form-label">To</label>
                                            <div class="input-group">
                                                <select class="form-select" id="to-unit">
                                                    <option value="g">grams (g)</option>
                                                    <option value="mg" selected>milligrams (mg)</option>
                                                    <option value="mcg">micrograms (mcg)</option>
                                                    <option value="ng">nanograms (ng)</option>
                                                    <option value="mEq">milliequivalents (mEq)</option>
                                                    <option value="mmol">millimoles (mmol)</option>
                                                    <option value="units">units</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div id="factor-section" style="display: none;">
                                            <div class="mb-3">
                                                <label for="conversion-factor" class="form-label">Conversion Factor (optional)</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="conversion-factor" min="0" step="0.00001" placeholder="For custom conversions">
                                                    <span class="input-group-text" id="conversion-factor-label">mg/unit</span>
                                                </div>
                                                <div class="form-text">
                                                    For specialized conversions like units to mg. Leave blank for standard metric conversions.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-lg-6">
                                        <div class="card h-100 bg-light">
                                            <div class="card-body">
                                                <h5 class="card-title text-center mb-4">Results</h5>
                                                
                                                <div class="text-center mt-3">
                                                    <h6>Converted Value</h6>
                                                    <p class="display-5 mb-0" id="converted-value">1000</p>
                                                    <p class="text-muted" id="converted-unit">mg</p>
                                                </div>
                                                
                                                <div class="alert alert-primary mt-4">
                                                    <div class="d-flex">
                                                        <i class="ti ti-calculator me-2 fs-5"></i>
                                                        <div>
                                                            <strong>Formula:</strong> <span id="conversion-formula">1 g = 1000 mg</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Common Conversion Presets -->
                                <div class="mt-4">
                                    <h6 class="mb-2">Common Conversion Presets:</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary conversion-preset" 
                                                data-from="1" data-from-unit="g" data-to-unit="mg">
                                            1 gram to milligrams
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary conversion-preset" 
                                                data-from="1" data-from-unit="mg" data-to-unit="mcg">
                                            1 mg to micrograms
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary conversion-preset" 
                                                data-from="1000" data-from-unit="mcg" data-to-unit="mg">
                                            1000 mcg to mg
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary conversion-preset" 
                                                data-from="100" data-from-unit="units" data-to-unit="mg" data-factor="0.0347">
                                            Insulin 100 units to mg
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="alert alert-warning mt-4">
                                    <div class="d-flex">
                                        <i class="ti ti-alert-triangle me-2 fs-5"></i>
                                        <div>
                                            <strong>Clinical Note:</strong> Special conversions like units to mg are medication-specific and 
                                            require a conversion factor. Always verify specialized conversions with reliable references.
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                            <h6>Safety Guidelines:</h6>
                            <ul>
                                <li>Always double-check calculations with a second provider for high-risk medications</li>
                                <li>Use standardized units and avoid unnecessary decimal places</li>
                                <li>For pediatric dosing, verify both the mg/kg dose and the total dose</li>
                                <li>Never exceed maximum recommended doses without physician approval</li>
                                <li>Consider renal/hepatic adjustments for appropriate medications</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Important Conversions:</h6>
                            <ul>
                                <li>1 kilogram (kg) = 2.2 pounds (lb)</li>
                                <li>1 gram (g) = 1,000 milligrams (mg)</li>
                                <li>1 milligram (mg) = 1,000 micrograms (mcg or μg)</li>
                                <li>1 microgram (mcg) = 1,000 nanograms (ng)</li>
                                <li>1 liter (L) = 1,000 milliliters (mL)</li>
                                <li>1 milliliter (mL) = 1 cubic centimeter (cc)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for the calculator -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab switching logic
    const calculatorTypes = document.querySelectorAll('.calculator-type');
    const calculatorSections = document.querySelectorAll('.calculator-section');
    
    calculatorTypes.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            calculatorTypes.forEach(btn => btn.classList.remove('active', 'btn-primary'));
            calculatorTypes.forEach(btn => btn.classList.add('btn-outline-primary'));
            
            // Add active class to clicked button
            this.classList.add('active', 'btn-primary');
            this.classList.remove('btn-outline-primary');
            
            // Hide all calculator sections
            calculatorSections.forEach(section => section.style.display = 'none');
            
            // Show the selected calculator section
            const calculatorType = this.getAttribute('data-type');
            document.getElementById(`${calculatorType}-section`).style.display = 'block';
        });
    });
    
    // ============ WEIGHT-BASED CALCULATOR ============
    
    const patientWeight = document.getElementById('patient-weight');
    const weightUnit = document.getElementById('weight-unit');
    const dosePerWeight = document.getElementById('dose-per-weight');
    const doseUnit = document.getElementById('dose-unit');
    const perWeightUnit = document.getElementById('per-weight-unit');
    const medConcentration = document.getElementById('medication-concentration');
    const concentrationUnit = document.getElementById('concentration-unit');
    const volumeUnit = document.getElementById('volume-unit');
    
    const totalDose = document.getElementById('total-dose');
    const totalDoseUnit = document.getElementById('total-dose-unit');
    const volumeToAdminister = document.getElementById('volume-to-administer');
    const volumeToAdministerUnit = document.getElementById('volume-to-administer-unit');
    const weightBasedFormula = document.getElementById('weight-based-formula');
    
    // Weight-based calculator preset buttons
    const weightPresetButtons = document.querySelectorAll('.weight-med-preset');
    
    function calculateWeightBasedDose() {
        let weight = parseFloat(patientWeight.value);
        const dose = parseFloat(dosePerWeight.value);
        const concentration = parseFloat(medConcentration.value);
        
        // Check if any values are invalid
        if (isNaN(weight) || isNaN(dose)) {
            totalDose.textContent = 'Error';
            volumeToAdminister.textContent = 'Error';
            weightBasedFormula.textContent = 'Invalid input values';
            return;
        }
        
        // Convert weight if needed
        if (weightUnit.value === 'lb' && perWeightUnit.value === 'kg') {
            weight = weight / 2.2; // Convert lb to kg
        } else if (weightUnit.value === 'kg' && perWeightUnit.value === 'lb') {
            weight = weight * 2.2; // Convert kg to lb
        }
        
        // Calculate total dose
        const calculatedDose = weight * dose;
        
        // Update total dose display
        totalDose.textContent = calculatedDose.toFixed(2);
        totalDoseUnit.textContent = doseUnit.value;
        
        // Update formula text
        weightBasedFormula.textContent = `${dose} ${doseUnit.value}/${perWeightUnit.value} × ${weight.toFixed(1)} ${perWeightUnit.value} = ${calculatedDose.toFixed(2)} ${doseUnit.value}`;
        
        // Calculate volume to administer if concentration is provided
        if (!isNaN(concentration) && concentration > 0) {
            const volume = calculatedDose / concentration;
            volumeToAdminister.textContent = volume.toFixed(2);
            volumeToAdministerUnit.textContent = volumeUnit.value;
            
            // Update formula to include volume calculation
            weightBasedFormula.textContent += ` = ${volume.toFixed(2)} ${volumeUnit.value}`;
        } else {
            volumeToAdminister.textContent = 'N/A';
            volumeToAdministerUnit.textContent = volumeUnit.value;
        }
    }
    
    // Add event listeners for weight-based calculator
    [patientWeight, weightUnit, dosePerWeight, doseUnit, perWeightUnit, 
     medConcentration, concentrationUnit, volumeUnit].forEach(element => {
        element.addEventListener('input', calculateWeightBasedDose);
    });
    
    // Set up preset buttons for weight-based calculator
    weightPresetButtons.forEach(button => {
        button.addEventListener('click', function() {
            const presetDose = this.getAttribute('data-dose');
            const presetUnit = this.getAttribute('data-unit');
            const presetConc = this.getAttribute('data-concentration');
            const presetVolUnit = this.getAttribute('data-volunit');
            
            dosePerWeight.value = presetDose;
            doseUnit.value = presetUnit;
            
            if (presetConc) {
                medConcentration.value = presetConc;
            }
            
            if (presetVolUnit) {
                concentrationUnit.value = presetUnit;
            }
            
            calculateWeightBasedDose();
        });
    });
    
    // ============ CONTINUOUS INFUSION CALCULATOR ============
    
    const infusionPatientWeight = document.getElementById('infusion-patient-weight');
    const infusionWeightUnit = document.getElementById('infusion-weight-unit');
    const drugAmount = document.getElementById('drug-amount');
    const drugUnit = document.getElementById('drug-unit');
    const solutionVolume = document.getElementById('solution-volume');
    const solutionUnit = document.getElementById('solution-unit');
    const infusionRate = document.getElementById('infusion-rate');
    const infusionRateUnit = document.getElementById('infusion-rate-unit');
    
    const flowRate = document.getElementById('flow-rate');
    const drugConcentration = document.getElementById('drug-concentration');
    const concentrationResultUnit = document.getElementById('concentration-result-unit');
    const infusionFormula = document.getElementById('infusion-formula');
    
    // Continuous infusion preset buttons
    const infusionPresetButtons = document.querySelectorAll('.infusion-preset');
    
    function calculateInfusion() {
        // Get values
        let weight = parseFloat(infusionPatientWeight.value);
        const amount = parseFloat(drugAmount.value);
        const volume = parseFloat(solutionVolume.value);
        let rate = parseFloat(infusionRate.value);
        const rateUnit = infusionRateUnit.value;
        
        // Check for valid inputs
        if (isNaN(amount) || isNaN(volume) || isNaN(rate) || amount <= 0 || volume <= 0 || rate <= 0) {
            flowRate.textContent = 'Error';
            drugConcentration.textContent = 'Error';
            infusionFormula.textContent = 'Invalid input values';
            return;
        }
        
        // Convert weight if needed
        if (infusionWeightUnit.value === 'lb') {
            weight = weight / 2.2; // Convert lb to kg
        }
        
        // Calculate concentration (drug amount per mL)
        const concentration = amount / volume;
        drugConcentration.textContent = concentration.toFixed(3);
        concentrationResultUnit.textContent = `${drugUnit.value}/mL`;
        
        // Different calculation based on rate unit
        let calculatedFlow = 0;
        let formulaText = '';
        
        // Handle different rate units
        switch(rateUnit) {
            case 'mcg/min':
                // Convert mcg/min to mg/hr if drug unit is mg
                if (drugUnit.value === 'mg') {
                    rate = rate * 60 / 1000; // mcg/min to mg/hr
                    formulaText = `Flow Rate (mL/hr) = (${rate.toFixed(4)} mg/hr × ${volume} mL) ÷ ${amount} mg`;
                } else if (drugUnit.value === 'mcg') {
                    rate = rate * 60; // mcg/min to mcg/hr
                    formulaText = `Flow Rate (mL/hr) = (${rate.toFixed(2)} mcg/hr × ${volume} mL) ÷ ${amount} mcg`;
                }
                calculatedFlow = (rate * volume) / amount;
                break;
                
            case 'mcg/kg/min':
                if (!isNaN(weight) && weight > 0) {
                    if (drugUnit.value === 'mg') {
                        // Convert mcg/kg/min to mg/hr
                        rate = rate * weight * 60 / 1000; // mcg/kg/min to mg/hr
                        formulaText = `Flow Rate (mL/hr) = (${rate.toFixed(4)} mg/hr × ${volume} mL) ÷ ${amount} mg`;
                    } else if (drugUnit.value === 'mcg') {
                        rate = rate * weight * 60; // mcg/kg/min to mcg/hr
                        formulaText = `Flow Rate (mL/hr) = (${rate.toFixed(2)} mcg/hr × ${volume} mL) ÷ ${amount} mcg`;
                    }
                    calculatedFlow = (rate * volume) / amount;
                } else {
                    flowRate.textContent = 'Need weight';
                    infusionFormula.textContent = 'Patient weight required for weight-based dosing';
                    return;
                }
                break;
                
            case 'mg/hr':
                formulaText = `Flow Rate (mL/hr) = (${rate} mg/hr × ${volume} mL) ÷ ${amount} mg`;
                calculatedFlow = (rate * volume) / amount;
                break;
                
            case 'mg/kg/hr':
                if (!isNaN(weight) && weight > 0) {
                    rate = rate * weight; // mg/kg/hr to mg/hr
                    formulaText = `Flow Rate (mL/hr) = (${rate.toFixed(2)} mg/hr × ${volume} mL) ÷ ${amount} mg`;
                    calculatedFlow = (rate * volume) / amount;
                } else {
                    flowRate.textContent = 'Need weight';
                    infusionFormula.textContent = 'Patient weight required for weight-based dosing';
                    return;
                }
                break;
                
            case 'mg/min':
                rate = rate * 60; // mg/min to mg/hr
                formulaText = `Flow Rate (mL/hr) = (${rate} mg/hr × ${volume} mL) ÷ ${amount} mg`;
                calculatedFlow = (rate * volume) / amount;
                break;
        }
        
        // Update the display
        flowRate.textContent = calculatedFlow.toFixed(1);
        infusionFormula.textContent = formulaText;
    }
    
    // Add event listeners for continuous infusion calculator
    [infusionPatientWeight, infusionWeightUnit, drugAmount, drugUnit, solutionVolume, 
     solutionUnit, infusionRate, infusionRateUnit].forEach(element => {
        element.addEventListener('input', calculateInfusion);
    });
    
    // Set up preset buttons for infusion calculator
    infusionPresetButtons.forEach(button => {
        button.addEventListener('click', function() {
            drugAmount.value = this.getAttribute('data-drug');
            drugUnit.value = this.getAttribute('data-drug-unit');
            solutionVolume.value = this.getAttribute('data-volume');
            infusionRate.value = this.getAttribute('data-rate');
            infusionRateUnit.value = this.getAttribute('data-rate-unit');
            
            calculateInfusion();
        });
    });
    
    // ============ IV DRIP RATE CALCULATOR ============
    
    const fluidVolume = document.getElementById('fluid-volume');
    const infusionTime = document.getElementById('infusion-time');
    const timeUnit = document.getElementById('time-unit');
    const dropFactor = document.getElementById('drop-factor');
    
    const flowRateMl = document.getElementById('flow-rate-ml');
    const dripRate = document.getElementById('drip-rate');
    const dripFormula = document.getElementById('drip-formula');
    
    // IV drip calculator preset buttons
    const dripPresetButtons = document.querySelectorAll('.drip-preset');
    
    function calculateDripRate() {
        // Get values
        const volume = parseFloat(fluidVolume.value);
        let time = parseFloat(infusionTime.value);
        const drops = parseInt(dropFactor.value);
        
        // Check for valid inputs
        if (isNaN(volume) || isNaN(time) || isNaN(drops) || volume <= 0 || time <= 0) {
            flowRateMl.textContent = 'Error';
            dripRate.textContent = 'Error';
            dripFormula.textContent = 'Invalid input values';
            return;
        }
        
        // Convert time to minutes if hours selected
        const timeInMinutes = timeUnit.value === 'hr' ? time * 60 : time;
        
        // Calculate flow rate in mL/hr
        const rate = (volume / time) * (timeUnit.value === 'hr' ? 1 : 60);
        
        // Calculate drip rate in drops/min
        const drops_per_min = (volume * drops) / timeInMinutes;
        
        // Update the display
        flowRateMl.textContent = rate.toFixed(1);
        dripRate.textContent = Math.round(drops_per_min);
        
        // Update formula text
        if (timeUnit.value === 'hr') {
            dripFormula.textContent = `Drops/min = (${volume} mL × ${drops} drops/mL) ÷ (${time} hr × 60 min/hr) = ${Math.round(drops_per_min)} drops/min`;
        } else {
            dripFormula.textContent = `Drops/min = (${volume} mL × ${drops} drops/mL) ÷ ${time} min = ${Math.round(drops_per_min)} drops/min`;
        }
    }
    
    // Add event listeners for IV drip calculator
    [fluidVolume, infusionTime, timeUnit, dropFactor].forEach(element => {
        element.addEventListener('input', calculateDripRate);
    });
    
    // Set up preset buttons for drip calculator
    dripPresetButtons.forEach(button => {
        button.addEventListener('click', function() {
            fluidVolume.value = this.getAttribute('data-volume');
            infusionTime.value = this.getAttribute('data-time');
            dropFactor.value = this.getAttribute('data-factor');
            timeUnit.value = 'hr'; // Presets use hours
            
            calculateDripRate();
        });
    });
    
    // ============ DILUTION/RECONSTITUTION CALCULATOR ============
    
    const drugQuantity = document.getElementById('drug-quantity');
    const drugQuantityUnit = document.getElementById('drug-quantity-unit');
    const methodDesiredVolume = document.getElementById('methodDesiredVolume');
    const methodDesiredConcentration = document.getElementById('methodDesiredConcentration');
    const desiredVolumeSection = document.getElementById('desiredVolumeSection');
    const desiredConcentrationSection = document.getElementById('desiredConcentrationSection');
    const diluentVolume = document.getElementById('diluent-volume');
    const desiredConcentration = document.getElementById('desired-concentration');
    const concentrationUnitInput = document.getElementById('concentration-unit-input');
    
    const dilutionVolumeResult = document.getElementById('dilutionVolumeResult');
    const dilutionConcentrationResult = document.getElementById('dilutionConcentrationResult');
    const finalVolume = document.getElementById('final-volume');
    const requiredVolume = document.getElementById('required-volume');
    const finalConcentration = document.getElementById('final-concentration');
    const finalConcentrationUnit = document.getElementById('final-concentration-unit');
    const dilutionFormula = document.getElementById('dilution-formula');
    
    // Dilution calculator preset buttons
    const dilutionPresetButtons = document.querySelectorAll('.dilution-preset');
    
    // Method selection event listeners
    methodDesiredVolume.addEventListener('change', function() {
        if (this.checked) {
            desiredVolumeSection.style.display = 'block';
            desiredConcentrationSection.style.display = 'none';
            dilutionVolumeResult.style.display = 'block';
            dilutionConcentrationResult.style.display = 'none';
        }
        calculateDilution();
    });
    
    methodDesiredConcentration.addEventListener('change', function() {
        if (this.checked) {
            desiredVolumeSection.style.display = 'none';
            desiredConcentrationSection.style.display = 'block';
            dilutionVolumeResult.style.display = 'none';
            dilutionConcentrationResult.style.display = 'block';
        }
        calculateDilution();
    });
    
    function calculateDilution() {
        // Get drug amount
        const amount = parseFloat(drugQuantity.value);
        const unit = drugQuantityUnit.value;
        
        // Check for valid inputs
        if (isNaN(amount) || amount <= 0) {
            finalVolume.textContent = 'Error';
            requiredVolume.textContent = 'Error';
            finalConcentration.textContent = 'Error';
            dilutionFormula.textContent = 'Invalid input values';
            return;
        }
        
        // Determine which method is selected
        if (methodDesiredVolume.checked) {
            // Calculate based on desired volume
            const volume = parseFloat(diluentVolume.value);
            
            if (isNaN(volume) || volume <= 0) {
                finalVolume.textContent = 'Error';
                finalConcentration.textContent = 'Error';
                dilutionFormula.textContent = 'Invalid volume';
                return;
            }
            
            // Calculate final concentration
            const conc = amount / volume;
            
            // Update the display
            finalVolume.textContent = volume.toFixed(1);
            finalConcentration.textContent = conc.toFixed(2);
            finalConcentrationUnit.textContent = `${unit}/mL`;
            
            // Update formula
            dilutionFormula.textContent = `Concentration = ${amount} ${unit} ÷ ${volume} mL = ${conc.toFixed(2)} ${unit}/mL`;
            
        } else {
            // Calculate based on desired concentration
            const desiredConc = parseFloat(desiredConcentration.value);
            const concUnit = concentrationUnitInput.value;
            
            if (isNaN(desiredConc) || desiredConc <= 0) {
                requiredVolume.textContent = 'Error';
                finalConcentration.textContent = 'Error';
                dilutionFormula.textContent = 'Invalid concentration';
                return;
            }
            
            // Calculate required volume
            const reqVolume = amount / desiredConc;
            
            // Update the display
            requiredVolume.textContent = reqVolume.toFixed(1);
            finalConcentration.textContent = desiredConc.toFixed(2);
            finalConcentrationUnit.textContent = `${concUnit}/mL`;
            
            // Update formula
            dilutionFormula.textContent = `Required Volume = ${amount} ${unit} ÷ ${desiredConc} ${concUnit}/mL = ${reqVolume.toFixed(1)} mL`;
        }
    }
    
    // Add event listeners for dilution calculator
    [drugQuantity, drugQuantityUnit, diluentVolume, desiredConcentration, 
     concentrationUnitInput].forEach(element => {
        element.addEventListener('input', calculateDilution);
    });
    
    // Set up preset buttons for dilution calculator
    dilutionPresetButtons.forEach(button => {
        button.addEventListener('click', function() {
            drugQuantity.value = this.getAttribute('data-drug');
            drugQuantityUnit.value = this.getAttribute('data-unit');
            
            const method = this.getAttribute('data-method');
            if (method === 'volume') {
                methodDesiredVolume.checked = true;
                desiredVolumeSection.style.display = 'block';
                desiredConcentrationSection.style.display = 'none';
                dilutionVolumeResult.style.display = 'block';
                dilutionConcentrationResult.style.display = 'none';
                
                diluentVolume.value = this.getAttribute('data-volume');
            } else {
                methodDesiredConcentration.checked = true;
                desiredVolumeSection.style.display = 'none';
                desiredConcentrationSection.style.display = 'block';
                dilutionVolumeResult.style.display = 'none';
                dilutionConcentrationResult.style.display = 'block';
                
                desiredConcentration.value = this.getAttribute('data-concentration');
                concentrationUnitInput.value = this.getAttribute('data-conc-unit');
            }
            
            calculateDilution();
        });
    });
    
    // ============ DOSE CONVERSION CALCULATOR ============
    
    const fromValue = document.getElementById('from-value');
    const fromUnit = document.getElementById('from-unit');
    const toUnit = document.getElementById('to-unit');
    const factorSection = document.getElementById('factor-section');
    const conversionFactor = document.getElementById('conversion-factor');
    const conversionFactorLabel = document.getElementById('conversion-factor-label');
    
    const convertedValue = document.getElementById('converted-value');
    const convertedUnit = document.getElementById('converted-unit');
    const conversionFormula = document.getElementById('conversion-formula');
    
    // Conversion preset buttons
    const conversionPresetButtons = document.querySelectorAll('.conversion-preset');
    
    // Check if conversion needs a factor (like units to mg)
    function checkConversionFactor() {
        const from = fromUnit.value;
        const to = toUnit.value;
        
        // If converting between units and weight units, or vice versa, show the factor section
        if ((from === 'units' && (to === 'g' || to === 'mg' || to === 'mcg' || to === 'ng')) || 
            (to === 'units' && (from === 'g' || from === 'mg' || from === 'mcg' || from === 'ng')) ||
            (from === 'mEq' && (to !== 'mEq' && to !== 'mmol')) ||
            (to === 'mEq' && (from !== 'mEq' && from !== 'mmol')) ||
            (from === 'mmol' && (to !== 'mmol' && to !== 'mEq')) ||
            (to === 'mmol' && (from !== 'mmol' && from !== 'mEq'))) {
            
            factorSection.style.display = 'block';
            conversionFactorLabel.textContent = `${to}/${from}`;
        } else {
            factorSection.style.display = 'none';
            conversionFactor.value = '';
        }
        
        calculateConversion();
    }
    
    function calculateConversion() {
        // Get values
        const value = parseFloat(fromValue.value);
        const from = fromUnit.value;
        const to = toUnit.value;
        
        // Check for valid inputs
        if (isNaN(value) || value < 0) {
            convertedValue.textContent = 'Error';
            conversionFormula.textContent = 'Invalid input values';
            return;
        }
        
        // Standard metric conversions
        const metricConversions = {
            'g': { 'g': 1, 'mg': 1000, 'mcg': 1000000, 'ng': 1000000000 },
            'mg': { 'g': 0.001, 'mg': 1, 'mcg': 1000, 'ng': 1000000 },
            'mcg': { 'g': 0.000001, 'mg': 0.001, 'mcg': 1, 'ng': 1000 },
            'ng': { 'g': 0.000000001, 'mg': 0.000001, 'mcg': 0.001, 'ng': 1 },
            'mEq': { 'mEq': 1, 'mmol': 1 }, // Simplified, actually depends on valence
            'mmol': { 'mEq': 1, 'mmol': 1 } // Simplified, actually depends on valence
        };
        
        let result;
        let formula;
        
        // Factor conversions (like units to mg)
        if (factorSection.style.display === 'block') {
            const factor = parseFloat(conversionFactor.value);
            
            if (!isNaN(factor) && factor > 0) {
                result = value * factor;
                formula = `${value} ${from} × ${factor} ${to}/${from} = ${result.toFixed(4)} ${to}`;
            } else {
                convertedValue.textContent = 'Need factor';
                conversionFormula.textContent = `Conversion from ${from} to ${to} requires a conversion factor`;
                convertedUnit.textContent = to;
                return;
            }
        } 
        // Standard metric conversions
        else if (metricConversions[from] && metricConversions[from][to]) {
            result = value * metricConversions[from][to];
            formula = `${value} ${from} = ${result.toFixed(4)} ${to}`;
            
            // Additional information for standard conversions
            if (from === 'g' && to === 'mg') {
                formula += ' (1 g = 1000 mg)';
            } else if (from === 'mg' && to === 'mcg') {
                formula += ' (1 mg = 1000 mcg)';
            } else if (from === 'mcg' && to === 'ng') {
                formula += ' (1 mcg = 1000 ng)';
            }
        } 
        // Same unit conversion
        else if (from === to) {
            result = value;
            formula = `${value} ${from} = ${value} ${to}`;
        } 
        // Unsupported conversion
        else {
            convertedValue.textContent = 'Unsupported';
            conversionFormula.textContent = `Conversion from ${from} to ${to} requires a specific conversion factor`;
            convertedUnit.textContent = to;
            return;
        }
        
        // Update display
        convertedValue.textContent = result.toFixed(4).replace(/\.?0+$/, ''); // Remove trailing zeros
        convertedUnit.textContent = to;
        conversionFormula.textContent = formula;
    }
    
    // Add event listeners for dose conversion calculator
    [fromValue, conversionFactor].forEach(element => {
        element.addEventListener('input', calculateConversion);
    });
    
    [fromUnit, toUnit].forEach(element => {
        element.addEventListener('change', checkConversionFactor);
    });
    
    // Set up preset buttons for conversion calculator
    conversionPresetButtons.forEach(button => {
        button.addEventListener('click', function() {
            fromValue.value = this.getAttribute('data-from');
            fromUnit.value = this.getAttribute('data-from-unit');
            toUnit.value = this.getAttribute('data-to-unit');
            
            // Check if we need to show factor section
            checkConversionFactor();
            
            // If preset has a factor, set it
            const factor = this.getAttribute('data-factor');
            if (factor) {
                conversionFactor.value = factor;
            }
            
            calculateConversion();
        });
    });
    
    // Run initial calculations
    calculateWeightBasedDose();
    calculateInfusion();
    calculateDripRate();
    calculateDilution();
    checkConversionFactor();
});
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>