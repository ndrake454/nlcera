<?php
/**
 * Body Surface Area Burn Calculator Tool
 * 
 * Place this file in: /tool_burn_calculator.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'BSA Burn Calculator';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">Body Surface Area Burn Calculator</h1>
                </div>
                <div class="card-body">
                    <p class="lead">Estimate the percentage of total body surface area (TBSA) affected by burns. Select the burn extent for each body region.</p>
                    
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-info-circle me-2 fs-4"></i>
                            <span>This calculator uses the <strong>Rule of Nines</strong> for adults and the <strong>Lund-Browder</strong> method with age adjustments for pediatrics.</span>
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-info" data-info="rule-of-nines">
                                <i class="ti ti-info-circle me-1"></i> About Rule of Nines
                            </button>
                            <button type="button" class="btn btn-sm btn-info ms-2" data-info="burn-assessment">
                                <i class="ti ti-info-circle me-1"></i> Burn Assessment Tips
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Patient Type Selection -->
    <div class="row justify-content-center mb-4">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header section-header-treatment">
                    <h2 class="h5 mb-0"><i class="ti ti-user me-2"></i>Patient Information</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Patient Age Category</label>
                        <div class="btn-group w-100" role="group" aria-label="Patient age category">
                            <input type="radio" class="btn-check" name="ageCategory" id="adult" value="adult" checked>
                            <label class="btn btn-outline-primary" for="adult">Adult</label>
                            
                            <input type="radio" class="btn-check" name="ageCategory" id="child5-15" value="child5-15">
                            <label class="btn btn-outline-primary" for="child5-15">Child 5-15 yrs</label>
                            
                            <input type="radio" class="btn-check" name="ageCategory" id="child1-4" value="child1-4">
                            <label class="btn btn-outline-primary" for="child1-4">Child 1-4 yrs</label>
                            
                            <input type="radio" class="btn-check" name="ageCategory" id="infant" value="infant">
                            <label class="btn btn-outline-primary" for="infant">Infant <1 yr</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Body Regions -->
    <div class="row justify-content-center">
        <div class="col-md-5 mb-4">
            <!-- Head & Neck -->
            <div class="card mb-4">
                <div class="card-header section-header-treatment">
                    <h2 class="h5 mb-0"><i class="ti ti-user-circle me-2"></i>Head & Neck</h2>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-7">
                            <label class="form-label">Anterior Head & Face</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="anteriorHead" id="anteriorHead0" value="0" data-region="anteriorHead" checked>
                                <label class="btn btn-outline-secondary" for="anteriorHead0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="anteriorHead" id="anteriorHead1" value="0.45" data-region="anteriorHead">
                                <label class="btn btn-outline-danger" for="anteriorHead1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="anteriorHead" id="anteriorHead2" value="0.9" data-region="anteriorHead">
                                <label class="btn btn-outline-danger" for="anteriorHead2">Full</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-7">
                            <label class="form-label">Posterior Head</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="posteriorHead" id="posteriorHead0" value="0" data-region="posteriorHead" checked>
                                <label class="btn btn-outline-secondary" for="posteriorHead0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="posteriorHead" id="posteriorHead1" value="0.45" data-region="posteriorHead">
                                <label class="btn btn-outline-danger" for="posteriorHead1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="posteriorHead" id="posteriorHead2" value="0.9" data-region="posteriorHead">
                                <label class="btn btn-outline-danger" for="posteriorHead2">Full</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-7">
                            <label class="form-label">Neck</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="neck" id="neck0" value="0" data-region="neck" checked>
                                <label class="btn btn-outline-secondary" for="neck0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="neck" id="neck1" value="0.5" data-region="neck">
                                <label class="btn btn-outline-danger" for="neck1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="neck" id="neck2" value="1" data-region="neck">
                                <label class="btn btn-outline-danger" for="neck2">Full</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Anterior Torso -->
            <div class="card mb-4">
                <div class="card-header section-header-treatment">
                    <h2 class="h5 mb-0"><i class="ti ti-shirt me-2"></i>Anterior Torso</h2>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-7">
                            <label class="form-label">Anterior Chest</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="anteriorChest" id="anteriorChest0" value="0" data-region="anteriorChest" checked>
                                <label class="btn btn-outline-secondary" for="anteriorChest0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="anteriorChest" id="anteriorChest1" value="4.5" data-region="anteriorChest">
                                <label class="btn btn-outline-danger" for="anteriorChest1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="anteriorChest" id="anteriorChest2" value="9" data-region="anteriorChest">
                                <label class="btn btn-outline-danger" for="anteriorChest2">Full</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-7">
                            <label class="form-label">Anterior Abdomen</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="anteriorAbdomen" id="anteriorAbdomen0" value="0" data-region="anteriorAbdomen" checked>
                                <label class="btn btn-outline-secondary" for="anteriorAbdomen0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="anteriorAbdomen" id="anteriorAbdomen1" value="4.5" data-region="anteriorAbdomen">
                                <label class="btn btn-outline-danger" for="anteriorAbdomen1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="anteriorAbdomen" id="anteriorAbdomen2" value="9" data-region="anteriorAbdomen">
                                <label class="btn btn-outline-danger" for="anteriorAbdomen2">Full</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Upper Extremities -->
            <div class="card mb-4">
                <div class="card-header section-header-treatment">
                    <h2 class="h5 mb-0"><i class="ti ti-hand-finger me-2"></i>Upper Extremities</h2>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-7">
                            <label class="form-label">Right Upper Arm</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="rightUpperArm" id="rightUpperArm0" value="0" data-region="rightUpperArm" checked>
                                <label class="btn btn-outline-secondary" for="rightUpperArm0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="rightUpperArm" id="rightUpperArm1" value="1.5" data-region="rightUpperArm">
                                <label class="btn btn-outline-danger" for="rightUpperArm1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="rightUpperArm" id="rightUpperArm2" value="3" data-region="rightUpperArm">
                                <label class="btn btn-outline-danger" for="rightUpperArm2">Full</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-7">
                            <label class="form-label">Right Lower Arm</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="rightLowerArm" id="rightLowerArm0" value="0" data-region="rightLowerArm" checked>
                                <label class="btn btn-outline-secondary" for="rightLowerArm0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="rightLowerArm" id="rightLowerArm1" value="1.5" data-region="rightLowerArm">
                                <label class="btn btn-outline-danger" for="rightLowerArm1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="rightLowerArm" id="rightLowerArm2" value="3" data-region="rightLowerArm">
                                <label class="btn btn-outline-danger" for="rightLowerArm2">Full</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-7">
                            <label class="form-label">Right Hand</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="rightHand" id="rightHand0" value="0" data-region="rightHand" checked>
                                <label class="btn btn-outline-secondary" for="rightHand0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="rightHand" id="rightHand1" value="0.5" data-region="rightHand">
                                <label class="btn btn-outline-danger" for="rightHand1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="rightHand" id="rightHand2" value="1" data-region="rightHand">
                                <label class="btn btn-outline-danger" for="rightHand2">Full</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-7">
                            <label class="form-label">Left Upper Arm</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="leftUpperArm" id="leftUpperArm0" value="0" data-region="leftUpperArm" checked>
                                <label class="btn btn-outline-secondary" for="leftUpperArm0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="leftUpperArm" id="leftUpperArm1" value="1.5" data-region="leftUpperArm">
                                <label class="btn btn-outline-danger" for="leftUpperArm1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="leftUpperArm" id="leftUpperArm2" value="3" data-region="leftUpperArm">
                                <label class="btn btn-outline-danger" for="leftUpperArm2">Full</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-7">
                            <label class="form-label">Left Lower Arm</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="leftLowerArm" id="leftLowerArm0" value="0" data-region="leftLowerArm" checked>
                                <label class="btn btn-outline-secondary" for="leftLowerArm0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="leftLowerArm" id="leftLowerArm1" value="1.5" data-region="leftLowerArm">
                                <label class="btn btn-outline-danger" for="leftLowerArm1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="leftLowerArm" id="leftLowerArm2" value="3" data-region="leftLowerArm">
                                <label class="btn btn-outline-danger" for="leftLowerArm2">Full</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-7">
                            <label class="form-label">Left Hand</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="leftHand" id="leftHand0" value="0" data-region="leftHand" checked>
                                <label class="btn btn-outline-secondary" for="leftHand0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="leftHand" id="leftHand1" value="0.5" data-region="leftHand">
                                <label class="btn btn-outline-danger" for="leftHand1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="leftHand" id="leftHand2" value="1" data-region="leftHand">
                                <label class="btn btn-outline-danger" for="leftHand2">Full</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-5 mb-4">
            <!-- Posterior Torso -->
            <div class="card mb-4">
                <div class="card-header section-header-treatment">
                    <h2 class="h5 mb-0"><i class="ti ti-shirt me-2"></i>Posterior Torso</h2>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-7">
                            <label class="form-label">Posterior Trunk</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="posteriorTrunk" id="posteriorTrunk0" value="0" data-region="posteriorTrunk" checked>
                                <label class="btn btn-outline-secondary" for="posteriorTrunk0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="posteriorTrunk" id="posteriorTrunk1" value="4.5" data-region="posteriorTrunk">
                                <label class="btn btn-outline-danger" for="posteriorTrunk1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="posteriorTrunk" id="posteriorTrunk2" value="9" data-region="posteriorTrunk">
                                <label class="btn btn-outline-danger" for="posteriorTrunk2">Full</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-7">
                            <label class="form-label">Buttocks</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="buttocks" id="buttocks0" value="0" data-region="buttocks" checked>
                                <label class="btn btn-outline-secondary" for="buttocks0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="buttocks" id="buttocks1" value="4.5" data-region="buttocks">
                                <label class="btn btn-outline-danger" for="buttocks1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="buttocks" id="buttocks2" value="9" data-region="buttocks">
                                <label class="btn btn-outline-danger" for="buttocks2">Full</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Lower Extremities -->
            <div class="card mb-4">
                <div class="card-header section-header-treatment">
                    <h2 class="h5 mb-0"><i class="ti ti-shoe me-2"></i>Lower Extremities</h2>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-7">
                            <label class="form-label">Right Thigh</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="rightThigh" id="rightThigh0" value="0" data-region="rightThigh" checked>
                                <label class="btn btn-outline-secondary" for="rightThigh0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="rightThigh" id="rightThigh1" value="4.5" data-region="rightThigh">
                                <label class="btn btn-outline-danger" for="rightThigh1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="rightThigh" id="rightThigh2" value="9" data-region="rightThigh">
                                <label class="btn btn-outline-danger" for="rightThigh2">Full</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-7">
                            <label class="form-label">Right Lower Leg</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="rightLowerLeg" id="rightLowerLeg0" value="0" data-region="rightLowerLeg" checked>
                                <label class="btn btn-outline-secondary" for="rightLowerLeg0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="rightLowerLeg" id="rightLowerLeg1" value="3" data-region="rightLowerLeg">
                                <label class="btn btn-outline-danger" for="rightLowerLeg1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="rightLowerLeg" id="rightLowerLeg2" value="6" data-region="rightLowerLeg">
                                <label class="btn btn-outline-danger" for="rightLowerLeg2">Full</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-7">
                            <label class="form-label">Right Foot</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="rightFoot" id="rightFoot0" value="0" data-region="rightFoot" checked>
                                <label class="btn btn-outline-secondary" for="rightFoot0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="rightFoot" id="rightFoot1" value="1.75" data-region="rightFoot">
                                <label class="btn btn-outline-danger" for="rightFoot1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="rightFoot" id="rightFoot2" value="3.5" data-region="rightFoot">
                                <label class="btn btn-outline-danger" for="rightFoot2">Full</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-7">
                            <label class="form-label">Left Thigh</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="leftThigh" id="leftThigh0" value="0" data-region="leftThigh" checked>
                                <label class="btn btn-outline-secondary" for="leftThigh0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="leftThigh" id="leftThigh1" value="4.5" data-region="leftThigh">
                                <label class="btn btn-outline-danger" for="leftThigh1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="leftThigh" id="leftThigh2" value="9" data-region="leftThigh">
                                <label class="btn btn-outline-danger" for="leftThigh2">Full</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-7">
                            <label class="form-label">Left Lower Leg</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="leftLowerLeg" id="leftLowerLeg0" value="0" data-region="leftLowerLeg" checked>
                                <label class="btn btn-outline-secondary" for="leftLowerLeg0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="leftLowerLeg" id="leftLowerLeg1" value="3" data-region="leftLowerLeg">
                                <label class="btn btn-outline-danger" for="leftLowerLeg1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="leftLowerLeg" id="leftLowerLeg2" value="6" data-region="leftLowerLeg">
                                <label class="btn btn-outline-danger" for="leftLowerLeg2">Full</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-7">
                            <label class="form-label">Left Foot</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="leftFoot" id="leftFoot0" value="0" data-region="leftFoot" checked>
                                <label class="btn btn-outline-secondary" for="leftFoot0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="leftFoot" id="leftFoot1" value="1.75" data-region="leftFoot">
                                <label class="btn btn-outline-danger" for="leftFoot1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="leftFoot" id="leftFoot2" value="3.5" data-region="leftFoot">
                                <label class="btn btn-outline-danger" for="leftFoot2">Full</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Genitalia -->
            <div class="card mb-4">
                <div class="card-header section-header-treatment">
                    <h2 class="h5 mb-0"><i class="ti ti-first-aid me-2"></i>Genitalia</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-7">
                            <label class="form-label">Genitalia/Perineum</label>
                        </div>
                        <div class="col-5">
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check burn-selector" name="genitalia" id="genitalia0" value="0" data-region="genitalia" checked>
                                <label class="btn btn-outline-secondary" for="genitalia0">None</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="genitalia" id="genitalia1" value="0.5" data-region="genitalia">
                                <label class="btn btn-outline-danger" for="genitalia1">Partial</label>
                                
                                <input type="radio" class="btn-check burn-selector" name="genitalia" id="genitalia2" value="1" data-region="genitalia">
                                <label class="btn btn-outline-danger" for="genitalia2">Full</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Results -->
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0">Total Body Surface Area (TBSA) Results</h2>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div id="burn-breakdown" class="mb-3">
                                <!-- Burn breakdown will be inserted here -->
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="display-2 fw-bold text-danger mb-0" id="total-bsa">0%</div>
                            <div class="text-muted">Total Burn Surface Area</div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-3 mb-0">
                        <div class="d-flex">
                            <div class="me-2"><i class="ti ti-info-circle"></i></div>
                            <div>
                                <p class="mb-1"><strong>Note:</strong> Consider fluid resuscitation for:</p>
                                <ul class="mb-0">
                                    <li>Adult burns &gt;15% TBSA</li>
                                    <li>Pediatric burns &gt;10% TBSA</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4 mb-5">
                <button id="reset-calculator" class="btn btn-outline-secondary">
                    <i class="ti ti-refresh me-2"></i>Reset Calculator
                </button>
                <button id="print-calculator" class="btn btn-outline-primary ms-2">
                    <i class="ti ti-printer me-2"></i>Print Results
                </button>
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
            <div class="modal-body" id="modalContent">
                <!-- Content will be inserted dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for the burn calculator */
.btn-group .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

@media (max-width: 576px) {
    .btn-group .btn {
        padding: 0.25rem;
        font-size: 0.75rem;
    }
    
    .col-7 {
        width: 55%;
    }
    
    .col-5 {
        width: 45%;
    }
}

/* Print styles */
@media print {
    .header, .footer, nav, .btn, button {
        display: none !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        break-inside: avoid;
    }
    
    .card-header {
        background-color: #f8f9fa !important;
        color: #000 !important;
    }
    
    .container {
        width: 100% !important;
        max-width: 100% !important;
    }
    
    #total-bsa {
        font-size: 2rem !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Age category adjustment factors
    const ageFactors = {
        'adult': {
            head: 1,
            thighs: 1,
            lowerLegs: 1
        },
        'child5-15': {
            head: 0.85,
            thighs: 1.05,
            lowerLegs: 1.05
        },
        'child1-4': {
            head: 1.2,
            thighs: 0.9,
            lowerLegs: 0.9
        },
        'infant': {
            head: 1.75,
            thighs: 0.65,
            lowerLegs: 0.65
        }
    };
    
    // Track burn selections
    let burnAreas = {};
    let totalBSA = 0;
    
    // Information modal content
    const infoContent = {
        'rule-of-nines': `
            <h4>Rule of Nines</h4>
            <p>The Rule of Nines is a method used to estimate the percentage of body surface area affected by burns in adults:</p>
            <ul>
                <li><strong>Head and Neck:</strong> 9% (front and back of head 4.5% each)</li>
                <li><strong>Each Arm:</strong> 9% (upper 3%, lower 3%, hand 3%)</li>
                <li><strong>Anterior Trunk:</strong> 18% (chest 9%, abdomen 9%)</li>
                <li><strong>Posterior Trunk:</strong> 18% (back 9%, buttocks 9%)</li>
                <li><strong>Each Leg:</strong> 18% (thigh 9%, lower leg 6%, foot 3%)</li>
                <li><strong>Genitalia:</strong> 1%</li>
            </ul>
            <p>For pediatric patients, these percentages are adjusted based on age because children have proportionally larger heads and smaller extremities than adults.</p>
            <h4>Lund-Browder Chart</h4>
            <p>This is an age-adjusted chart for more accurate assessment of burns in children. Key adjustments:</p>
            <ul>
                <li>Infant head: up to 19% (vs. 9% in adults)</li>
                <li>Child thighs: smaller percentage than adults</li>
                <li>These adjustments are automatically applied in the calculator based on the selected age range</li>
            </ul>
        `,
        'burn-assessment': `
            <h4>Burn Assessment Tips</h4>
            <p>When assessing burns, consider the following:</p>
            <h5>Burn Depth Classification</h5>
            <ul>
                <li><strong>Superficial (1st degree):</strong> Involves only the epidermis. Red, painful, dry, no blisters. (Not included in TBSA calculation)</li>
                <li><strong>Partial Thickness (2nd degree):</strong> Involves epidermis and part of dermis. Painful, red, blisters, moist, blanches with pressure.</li>
                <li><strong>Full Thickness (3rd degree):</strong> Involves entire epidermis and dermis. Leathery, dry, no blanching, may appear white, brown or charred. Not painful due to destruction of nerve endings.</li>
            </ul>
            <h5>Circumferential Burns</h5>
            <p>Burns that extend around the entire circumference of an extremity, chest, or neck require special attention:</p>
            <ul>
                <li>Can act as a tourniquet as edema develops</li>
                <li>May impair circulation or respiration</li>
                <li>May require escharotomy in severe cases</li>
                <li>Monitor distal circulation, sensation, and movement</li>
            </ul>
            <h5>Estimation Techniques</h5>
            <ul>
                <li><strong>Patient's Palm Rule:</strong> The patient's palm (including fingers) represents approximately 1% of their total BSA. This can be helpful for estimating scattered or smaller burns.</li>
                <li>Use the "partial" button for burns affecting about 50% of a region and "full" for burns affecting the entire region.</li>
            </ul>
        `
    };
    
    // Initialize the calculation
    function calculateTotalBSA() {
        let total = 0;
        let breakdown = [];
        let selectedAge = document.querySelector('input[name="ageCategory"]:checked').value;
        
        // Apply the calculation for each body region
        for (let region in burnAreas) {
            let value = parseFloat(burnAreas[region]);
            
            // Apply age-based adjustments
            if (region.includes('Head') && selectedAge !== 'adult') {
                value *= ageFactors[selectedAge].head;
            } else if ((region.includes('Thigh')) && selectedAge !== 'adult') {
                value *= ageFactors[selectedAge].thighs;
            } else if ((region.includes('LowerLeg') || region.includes('Foot')) && selectedAge !== 'adult') {
                value *= ageFactors[selectedAge].lowerLegs;
            }
            
            // Only add to breakdown if there's a burn
            if (value > 0) {
                const regionName = region.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase());
                breakdown.push(`${regionName}: ${value.toFixed(1)}%`);
                total += value;
            }
        }
        
        // Update the UI
        document.getElementById('total-bsa').textContent = total.toFixed(1) + '%';
        
        // Update breakdown
        const breakdownEl = document.getElementById('burn-breakdown');
        if (breakdown.length > 0) {
            const list = breakdown.map(item => `<li>${item}</li>`).join('');
            breakdownEl.innerHTML = `<p class="mb-2">Burn areas:</p><ul class="mb-0">${list}</ul>`;
        } else {
            breakdownEl.innerHTML = '<p class="mb-0">No burns selected yet.</p>';
        }
        
        return total;
    }
    
    // Update calculation when any selector changes
    document.querySelectorAll('.burn-selector').forEach(selector => {
        selector.addEventListener('change', function() {
            const region = this.getAttribute('data-region');
            burnAreas[region] = this.value;
            calculateTotalBSA();
        });
    });
    
    // Update when age category changes
    document.querySelectorAll('input[name="ageCategory"]').forEach(radio => {
        radio.addEventListener('change', function() {
            calculateTotalBSA();
        });
    });
    
    // Reset calculator
    document.getElementById('reset-calculator').addEventListener('click', function() {
        document.querySelectorAll('.burn-selector').forEach(selector => {
            if (selector.id.endsWith('0')) { // Select the "None" option for each region
                selector.checked = true;
            }
        });
        
        // Reset stored burn areas
        burnAreas = {};
        calculateTotalBSA();
    });
    
    // Print results
    document.getElementById('print-calculator').addEventListener('click', function() {
        window.print();
    });
    
    // Info buttons
    document.querySelectorAll('[data-info]').forEach(button => {
        button.addEventListener('click', function() {
            const infoType = this.getAttribute('data-info');
            const title = infoType === 'rule-of-nines' ? 'Rule of Nines & Burn Assessment' : 'Burn Assessment Tips';
            
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalContent').innerHTML = infoContent[infoType];
            
            const modal = new bootstrap.Modal(document.getElementById('infoModal'));
            modal.show();
        });
    });
});
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>