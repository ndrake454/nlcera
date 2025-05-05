<?php
/**
 * Combined DKA/HHS Insulin & Electrolyte Management Tool (v2.1)
 * Calculates insulin rate, corrected sodium, and fluid/potassium recommendations.
 * Electrolyte/Fluid logic primarily active when BG < 250 mg/dL.
 * Prominent display of current insulin rate.
 * Based on UCHealth Adult DKA/HHS Order Set (Go Live April 16, 2024, Revised 1/24/25)
 *
 * Place this file in: /tools/dka-hhs-manager.php (or similar)
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'DKA/HHS Management Tool';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0 d-flex align-items-center">
                        <i class="ti ti-dashboard me-2"></i> DKA/HHS Management Tool
                    </h1>
                </div>
                <div class="card-body">
                    <p class="lead mb-0">Unified tool for insulin infusion, corrected sodium, and fluid/potassium recommendations (electrolyte details emphasized when BG < 250).</p>
                </div>
            </div>

            <!-- Patient Selection Modal (No changes needed here) -->
            <div class="modal fade" id="patientSelectionModal" tabindex="-1" data-bs-backdrop="static" data-backdrop="static">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white"><h5 class="modal-title">Patient Selection</h5></div>
                        <div class="modal-body">
                            <div id="existingPatientSection" style="display: none;">
                                <div class="alert alert-info mb-3"><p class="mb-0"><i class="ti ti-info-circle me-2"></i> Previous patient data detected. Continue or start new?</p></div>
                                <div class="card mb-3"><div class="card-body">
                                        <h6 class="mb-2">Previous Patient Summary</h6>
                                        <div class="row">
                                            <div class="col-6 mb-2"><div class="small text-muted">Condition</div><div class="fw-bold" id="modalPatientType"></div></div>
                                            <div class="col-6 mb-2"><div class="small text-muted">Weight</div><div class="fw-bold"><span id="modalWeight"></span> kg</div></div>
                                            <div class="col-6 mb-2"><div class="small text-muted">Last BG</div><div class="fw-bold"><span id="modalLastBG"></span> mg/dL</div></div>
                                            <div class="col-6 mb-2"><div class="small text-muted">Last K+</div><div class="fw-bold"><span id="modalLastK"></span> mEq/L</div></div>
                                            <div class="col-6 mb-2"><div class="small text-muted">Insulin Rate</div><div class="fw-bold"><span id="modalCurrentRate"></span> u/hr</div></div>
                                            <div class="col-6 mb-2"><div class="small text-muted">Fluid Rec.</div><div class="fw-bold" id="modalCurrentFluid"></div></div>
                                            <div class="col-12"><div class="small text-muted">Last Updated</div><div class="fw-bold"><span id="modalLastUpdated"></span></div></div>
                                        </div>
                                </div></div>
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-primary btn-lg mb-2" id="continuePreviousPatientBtn"><i class="ti ti-user-check me-2"></i>Continue Previous Patient</button>
                                    <button type="button" class="btn btn-outline-secondary btn-lg" id="startNewPatientBtn"><i class="ti ti-user-plus me-2"></i>Start New Patient</button>
                                </div>
                            </div>
                            <div id="noExistingPatientSection" style="display: none;">
                                <div class="text-center my-4">
                                    <i class="ti ti-user-plus text-primary" style="font-size: 4rem;"></i><h5 class="mt-3">No Previous Patient Data</h5>
                                    <p class="text-muted">Let's start with a new patient assessment.</p>
                                    <div class="d-grid"><button type="button" class="btn btn-primary btn-lg" id="startFreshPatientBtn"><i class="ti ti-user-plus me-2"></i>Start New Patient</button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 1: Initial Patient Information (No major changes) -->
            <div class="card mb-4" id="initial-info-card">
                <div class="card-header section-header-treatment"><h2 class="h5 mb-0"><i class="ti ti-user-plus me-2"></i>Initial Patient Information</h2></div>
                <div class="card-body">
                    <form id="patientInfoForm">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0"><label class="form-label">Patient Condition</label><div class="d-flex"><div class="form-check me-3"><input class="form-check-input" type="radio" name="patientType" id="typeDKA" value="dka" checked><label class="form-check-label" for="typeDKA">DKA</label></div><div class="form-check"><input class="form-check-input" type="radio" name="patientType" id="typeHHS" value="hhs"><label class="form-check-label" for="typeHHS">HHS</label></div></div></div>
                            <div class="col-md-6"><label for="weight" class="form-label">Patient Weight (kg)</label><input type="number" inputmode="decimal" class="form-control form-control-lg" id="weight" name="weight" placeholder="Enter weight in kg" required><div class="invalid-feedback" id="weightError"></div></div>
                        </div>
                        <h3 class="h6 mt-4 mb-3">Initial Lab Values</h3>
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3 mb-md-0"><label for="initialBG" class="form-label">Blood Glucose (mg/dL)</label><input type="number" inputmode="numeric" class="form-control form-control-lg" id="initialBG" name="initialBG" placeholder="Initial BG" required><div class="invalid-feedback" id="initialBGError"></div></div>
                            <div class="col-md-4 mb-3 mb-md-0"><label for="initialSodium" class="form-label">Measured Sodium (mEq/L)</label><input type="number" inputmode="decimal" class="form-control form-control-lg" id="initialSodium" name="initialSodium" placeholder="Optional Initial Na+"><div class="invalid-feedback" id="initialSodiumError"></div></div>
                            <div class="col-md-4"><label for="initialPotassium" class="form-label">Serum Potassium (mEq/L)</label><input type="number" inputmode="decimal" class="form-control form-control-lg" id="initialPotassium" name="initialPotassium" placeholder="Optional Initial K+" step="0.1"><div class="invalid-feedback" id="initialPotassiumError"></div></div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6"><label for="initialInsulinRate" class="form-label">Initial Insulin Rate (units/hr)</label><input type="number" inputmode="decimal" class="form-control form-control-lg" id="initialInsulinRate" name="initialInsulinRate" placeholder="Optional: If already started" step="0.1"><div class="invalid-feedback" id="initialInsulinRateError"></div><div class="form-text">Leave blank to calculate based on protocol.</div></div>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="button" class="btn btn-secondary btn-lg me-md-2" id="resetPatientButton"><i class="ti ti-refresh me-1"></i> Reset</button>
                            <button type="button" class="btn btn-primary btn-lg" id="startMonitoringButton"><i class="ti ti-play me-1"></i> Start Monitoring</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Section 2: Patient Summary (shown after first calculation) -->
            <div class="card mb-4" id="patient-summary-card" style="display: none;">
                <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0"><i class="ti ti-user-circle me-2"></i>Patient Summary</h2>
                        <button type="button" class="btn btn-sm btn-light" id="editPatientButton"><i class="ti ti-edit me-1"></i> Edit Initial Info</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 col-6 mb-2"><p class="mb-1"><strong>Condition:</strong> <span id="summaryPatientType"></span></p></div>
                        <div class="col-md-4 col-6 mb-2"><p class="mb-1"><strong>Weight:</strong> <span id="summaryWeight"></span> kg</p></div>
                        <div class="col-md-4 col-12 mb-2"><p class="mb-0"><strong>Last Fluid Rec:</strong> <span id="summaryCurrentFluid"></span></p></div>
                    </div>
                </div>
            </div>

            <!-- Section 2.5: Current Insulin Rate Display -->
            <div class="card mb-4 text-center" id="current-rate-card" style="display: none;">
                 <div class="card-header section-header-treatment">
                     <h2 class="h5 mb-0"><i class="ti ti-syringe me-2"></i>Current Insulin Infusion Rate</h2>
                 </div>
                 <div class="card-body py-4">
                     <div class="display-1 fw-bold text-primary" id="displayCurrentInsulinRate">---</div>
                     <div class="fs-4 text-muted">units/hr</div>
                     <div id="insulinHoldAlert" class="alert alert-danger mt-3" style="display: none;">
                         <i class="ti ti-alert-triangle me-2"></i> <strong>INSULIN HELD (K+ < 3.3 mEq/L)</strong>
                     </div>
                 </div>
            </div>

            <!-- Section 3: New Check Input (Updated for recommended vs required fields) -->
            <div class="card mb-4" id="check-input-card" style="display: none;">
                <div class="card-header section-header-treatment"><h2 class="h5 mb-0"><i class="ti ti-device-analytics me-2"></i>New Check Information</h2></div>
                <div class="card-body">
                    <form id="checkForm">
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="currentBG" class="form-label">Current BG (mg/dL)</label>
                                <input type="number" inputmode="numeric" class="form-control form-control-lg" id="currentBG" name="currentBG" placeholder="Enter current BG" required>
                                <div class="invalid-feedback" id="currentBGError"></div>
                                <div class="form-text">Previous: <span id="previousBGDisplay">-</span></div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label for="currentSodium" class="form-label">Measured Sodium (mEq/L)</label>
                                <input type="number" inputmode="decimal" class="form-control form-control-lg" id="currentSodium" name="currentSodium" placeholder="Enter current Na+">
                                <div class="invalid-feedback" id="currentSodiumError"></div>
                                <div class="form-text">Previous: <span id="previousSodiumDisplay">-</span> <span id="sodiumRecommendedNote" class="text-warning" style="display:none;">(Highly Recommended)</span></div>
                            </div>
                            <div class="col-md-4">
                                <label for="currentPotassium" class="form-label">Serum Potassium (mEq/L)</label>
                                <input type="number" inputmode="decimal" class="form-control form-control-lg" id="currentPotassium" name="currentPotassium" placeholder="Enter current K+" step="0.1">
                                <div class="invalid-feedback" id="currentPotassiumError"></div>
                                <div class="form-text">Previous: <span id="previousPotassiumDisplay">-</span> <span id="potassiumRecommendedNote" class="text-warning" style="display:none;">(Highly Recommended)</span></div>
                            </div>
                        </div>
                        <!-- New alert for BG < 250 without electrolytes -->
                        <div class="alert alert-warning mt-3" id="electrolytesRecommendedAlert" style="display:none;">
                            <i class="ti ti-alert-triangle me-2"></i> <strong>BG < 250 mg/dL:</strong> Na+ and K+ values are highly recommended at this stage for optimal fluid management and safe insulin administration. If not immediately available, consider obtaining labs ASAP.
                        </div>
                        <div class="d-grid mt-4">
                            <button type="button" class="btn btn-primary btn-lg" id="calculateButton"><i class="ti ti-calculator me-1"></i> Calculate Recommendations</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Section 4: Results & Confirmation -->
            <div class="card mb-4" id="result-card" style="display: none;">
                <div class="card-header bg-primary text-white"><h2 class="h5 mb-0"><i class="ti ti-clipboard-check me-2"></i>Recommendations</h2></div>
                <div class="card-body">
                    <!-- Calculated BG Display -->
                     <div class="card bg-light mb-4">
                         <div class="card-body text-center">
                             <h4 class="h6 text-muted mb-1">Current Blood Glucose</h4>
                             <div class="d-flex justify-content-center align-items-center">
                                <div class="display-5 fw-bold me-3" id="resultBG">-</div>
                                <div class="badge fs-6" id="severityBadge"></div>
                             </div>
                         </div>
                     </div>

                     <!-- Missing Electrolytes Warning (New) -->
                     <div class="alert alert-warning mb-4" id="missingElectrolytesWarning" style="display: none;">
                         <i class="ti ti-alert-triangle me-2"></i>
                         <strong>Electrolyte values not provided:</strong> For optimal management, consider obtaining Na+ and K+ values as soon as possible. Recommendations below are based on limited data.
                     </div>

                     <!-- Insulin Recommendation -->
                     <div class="card mb-4">
                        <div class="card-header bg-light"><h3 class="h6 mb-0"><i class="ti ti-syringe me-1"></i> Insulin Recommendation</h3></div>
                        <div class="card-body">
                            <div class="alert mb-3" id="insulinAlert"><h5 class="alert-heading mb-0" id="insulinRecommendationText"></h5></div>
                            <h4 class="h6">Action Steps:</h4>
                            <ul class="list-group list-group-flush mb-0 small" id="insulinActionStepsList"></ul>
                        </div>
                     </div>

                    <!-- Fluid Recommendation (Conditional Detail) -->
                    <div class="card mb-4">
                        <div class="card-header bg-light"><h3 class="h6 mb-0"><i class="ti ti-droplet me-1"></i> Fluid Recommendation</h3></div>
                        <div class="card-body">
                             <div id="fluidRecGeneral" style="display: none;">
                                 <p class="lead fw-bold" id="fluidRecommendationGeneralText"></p>
                                 <p class="small text-muted">Detailed fluid recommendation based on electrolytes will be provided when BG < 250 mg/dL and electrolyte values are available.</p>
                             </div>
                             <div id="fluidRecDetailed" style="display: none;">
                                 <div class="row mb-3">
                                     <div class="col-md-6 mb-3 mb-md-0">
                                         <div class="card bg-light h-100">
                                            <div class="card-body text-center">
                                                <h4 class="h6 text-muted mb-1">Corrected Sodium</h4>
                                                <div class="fs-4 fw-bold" id="resultCorrectedSodium">-</div>
                                                <div class="small text-muted mt-1" id="sodiumInterpretation"></div>
                                            </div>
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                          <div class="card bg-light h-100">
                                            <div class="card-body text-center">
                                                <h4 class="h6 text-muted mb-1">Serum Potassium Status</h4>
                                                <div class="fs-4 fw-bold" id="resultPotassium">-</div>
                                                <div class="alert mt-2 mb-0 py-1 small" id="potassiumLevelAlert"><span id="potassiumInterpretation"></span></div>
                                            </div>
                                          </div>
                                     </div>
                                 </div>
                                 <h4 class="h6">Recommended IV Fluid:</h4>
                                 <p class="lead fw-bold" id="fluidRecommendationDetailedText"></p>
                                 <h4 class="h6 mt-3">Action Steps / Considerations:</h4>
                                 <ul class="list-group list-group-flush mb-0 small" id="fluidActionStepsList"></ul>
                             </div>
                        </div>
                    </div>

                    <!-- Dextrose Warning -->
                    <div class="alert alert-warning mb-4" id="dextroseWarning" style="display: none;">
                        <i class="ti ti-alert-triangle me-1"></i>
                        <strong>BG < 250 mg/dL:</strong> Ensure appropriate D5-containing fluid is running (typically D5NS w/ 20 mEq KCl @ 150 mL/hr per protocol, verify institutional guidelines).
                    </div>

                    <!-- Rate Confirmation Section -->
                    <div class="card mb-4" id="rate-confirmation-card">
                        <div class="card-header bg-light"><h3 class="h6 mb-0">Confirm Insulin Rate Change</h3></div>
                        <div class="card-body">
                             <div class="row align-items-center">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label">Current Rate</label>
                                    <div class="input-group"><input type="text" class="form-control form-control-lg" id="currentRateDisplay" value="-" readonly><span class="input-group-text">u/hr</span></div>
                                </div>
                                <div class="col-md-6">
                                     <label class="form-label">New Rate <span id="recommendedRateLabel"></span></label>
                                    <div class="input-group"><input type="number" inputmode="decimal" class="form-control form-control-lg" id="newRateInput" step="0.1" min="0"><span class="input-group-text">u/hr</span></div>
                                    <div class="form-text">Enter actual rate set or use recommended.</div>
                                </div>
                            </div>
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                                <button type="button" class="btn btn-outline-secondary btn-lg me-md-2" id="useRecommendedRateButton"><i class="ti ti-check me-1"></i> Use Recommended</button>
                                <button type="button" class="btn btn-primary btn-lg" id="confirmRateButton"><i class="ti ti-device-floppy me-1"></i> Confirm & Log Check</button>
                            </div>
                        </div>
                    </div>

                    <!-- After rate is confirmed -->
                    <div class="text-center mt-4" id="rateConfirmedMessage" style="display: none;">
                        <div class="alert alert-success"><i class="ti ti-checks me-2"></i> Recommendations Logged. Ready for next check.</div>
                        <div class="d-grid mt-3"><button type="button" class="btn btn-primary btn-lg" id="newCheckButton"><i class="ti ti-clipboard-plus me-1"></i> Record New Check Data</button></div>
                    </div>
                </div>
            </div>

            <!-- Section 5: History (No major changes) -->
            <div class="card mb-4" id="history-card" style="display: none;">
                 <div class="card-header section-header-reference"><h2 class="h5 mb-0"><i class="ti ti-history me-2"></i>Management History</h2></div>
                <div class="card-body">
                    <div class="alert alert-info" id="no-history-alert" style="display: none;"><i class="ti ti-info-circle me-2"></i> No history logged yet.</div>
                    <div id="has-history-content" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm">
                                <thead><tr><th>Time</th><th>BG</th><th>Na+</th><th>K+</th><th>Corr Na+</th><th>Insulin (u/hr)</th><th>Insulin Δ</th><th>Fluid Rec.</th><th>Notes</th></tr></thead>
                                <tbody id="history-table-body"></tbody>
                            </table>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3"><button type="button" class="btn btn-outline-danger me-md-2" id="clearHistoryButton"><i class="ti ti-trash me-1"></i> Clear History</button></div>
                    </div>
                </div>
            </div>

            <!-- Section 6: Protocol Information (No changes needed) -->
            <div class="card mb-4">
                 <div class="card-header section-header-reference"><h2 class="h5 mb-0"><i class="ti ti-file-text me-2"></i>Protocol Reference</h2></div>
                <div class="card-body">
                     <h3 class="h6 mb-3">DKA/HHS Initial Insulin Infusion Rate</h3>
                    <div class="row mb-4"> <div class="col-md-6 mb-3 mb-md-0"> <div class="card h-100"> <div class="card-header">DKA (rate dependent on blood glucose)</div> <div class="card-body"> <ul class="list-unstyled"> <li><strong>BG < 250 mg/dL:</strong> 0.05 units/kg/hr</li> <li><strong>BG ≥ 250 mg/dL:</strong> 0.1 units/kg/hr</li> </ul> <small class="text-muted">Round actual dose in units/hr to nearest lower whole unit initially if needed per local policy.</small> </div> </div> </div> <div class="col-md-6"> <div class="card h-100"> <div class="card-header">HHS (flat starting rate)</div> <div class="card-body"> <ul class="list-unstyled"> <li><strong>Any BG value:</strong> 0.05 units/kg/hr</li> </ul> </div> </div> </div> </div>
                    <h3 class="h6 mb-3">DKA/HHS Intravenous Insulin Infusion Titration Table</h3> <p class="small text-muted mb-3">Use for all HHS patients AND DKA patients with BG LESS than 250 mg/dL.</p> <div class="table-responsive mb-4"> <table class="table table-bordered table-sm"> <thead class="table-primary"> <tr><th>Current Blood Glucose</th><th>Recommended Insulin Infusion Rate Change</th></tr> </thead> <tbody> <tr class="table-danger"><td>Less than 70 mg/dL</td><td>Implement Hypoglycemia Orders. STOP insulin infusion and notify provider. Consider D50 push and restart infusion at 0.5 units/hr when BG ≥ 140 mg/dL.</td></tr> <tr><td>70-119 mg/dL</td><td>Decrease infusion rate to 0.5 units/hr if not already at that rate.</td></tr> <tr><td>120-149 mg/dL</td><td>Change infusion rate to 1 unit/hr unless already at 1 unit/hr or lower.</td></tr> <tr class="table-success"><td>150-199 mg/dL (Goal)</td><td>Decrease infusion rate by 2 units/hr (but no lower than 0.5 units/hr).</td></tr> <tr><td>200-299 mg/dL</td><td>No change.</td></tr> <tr class="table-warning"><td>300 mg/dL or greater</td><td>Increase infusion rate by 1 unit/hr. Contact provider to determine if decreasing the rate of dextrose containing fluid is appropriate.</td></tr> </tbody> </table> </div> <div class="alert alert-info mb-4"> <strong>DKA Patients with BG ≥ 250 mg/dL:</strong> If BG has not dropped by at least 50 mg/dL from the previous hour, increase infusion rate by 1 unit/hr. When BG falls BELOW 250 mg/dL, decrease rate to 0.05 units/kg/hr, AND then follow the Insulin Infusion Titration Table above. </div>
                    <h3 class="h6 mb-3">Corrected Sodium Calculation</h3> <div class="card bg-light p-3 mb-4"> <strong>Katz Formula:</strong> Corrected Na<sup>+</sup> = Measured Na<sup>+</sup> + 0.016 × (Glucose - 100) </div>
                    <h3 class="h6 mb-3">Potassium Management Guidelines</h3> <div class="table-responsive mb-4"> <table class="table table-bordered table-sm"> <thead class="table-primary"><tr><th>Serum K+ (mEq/L)</th><th>Recommendation</th></tr></thead> <tbody> <tr class="table-danger"><td>< 3.3</td><td>Hold insulin until K+ > 3.3 mEq/L. Give 20-40 mEq/hr KCl IV. Consider oral K+. Reassess K+ q2h.</td></tr> <tr class="table-warning"><td>3.3 - 4.0</td><td>Infuse 20-30 mEq KCl per liter of IV fluid (Target 10-20 mEq/hr). Reassess K+ q2-4h.</td></tr> <tr class="table-success"><td>4.0 - 5.0</td><td>Add 10-20 mEq KCl per liter of IV fluid. Reassess K+ q4h.</td></tr> <tr><td>5.0 - 6.0</td><td>Do not add KCl to IV fluids. Reassess K+ q2-4h (expect K+ to fall).</td></tr> <tr class="table-danger"><td>> 6.0</td><td>Do not add KCl to IV fluids. Consider hyperkalemia management if ECG changes. Reassess K+ q2h.</td></tr> </tbody> </table> </div>
                    <h3 class="h6 mb-3">IV Fluid Recommendations (When BG ≥ 250 mg/dL - Initial Phase)</h3> <div class="row mb-4"> <div class="col-md-6 mb-3"><div class="card h-100"><div class="card-body"><h5 class="card-title small">Corr Na+ ≥ 140 & K+ ≥ 5</h5><p class="card-text fw-bold">½ NS @ 150 mL/hr</p></div></div></div> <div class="col-md-6 mb-3"><div class="card h-100"><div class="card-body"><h5 class="card-title small">Corr Na+ ≥ 140 & K+ < 5</h5><p class="card-text fw-bold">½ NS w/ 20 mEq KCl/L @ 150 mL/hr</p></div></div></div> <div class="col-md-6 mb-3"><div class="card h-100"><div class="card-body"><h5 class="card-title small">Corr Na+ < 140 & K+ ≥ 5</h5><p class="card-text fw-bold">NS @ 150 mL/hr</p></div></div></div> <div class="col-md-6 mb-3"><div class="card h-100"><div class="card-body"><h5 class="card-title small">Corr Na+ < 140 & K+ < 5</h5><p class="card-text fw-bold">NS w/ 20 mEq KCl/L @ 150 mL/hr</p></div></div></div> </div>
                    <div class="alert alert-warning"> <h5><i class="ti ti-alert-triangle me-2"></i>Important Notes & Overrides</h5> <ul class="mb-0"> <li><strong>When BG < 250 mg/dL:</strong> Change fluid to D5NS with 20 mEq of KCl running at 150mL/hr (Verify institutional policy). Electrolyte-based adjustments (NS vs 1/2NS) are generally less critical once dextrose is added, but monitor Na+ trends.</li> <li><strong>If K+ < 3.3 mEq/L:</strong> HOLD INSULIN until K+ > 3.3 mEq/L and repleted.</li> <li>If insulin infusion rate reaches 21.5 units/hr (Verify limit), notify provider.</li> <li>If blood sugar drops > 100 mg/dL in 30 minutes, down-titrate per table and monitor closely.</li> <li>If pH < 7, provider may consider 1 amp Sodium Bicarbonate.</li> <li>IV insulin infusions should run in a DEDICATED PRIMARY LINE if possible.</li> <li>Monitor for signs of cerebral edema, especially in pediatric patients or young adults.</li> </ul> </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
/* General Styles */
body { background-color: #f8f9fa; }
.card { border: none; }
.section-header-treatment { background-color: #e7f1ff; border-left: 5px solid #0d6efd; }
.section-header-reference { background-color: #f8f9fa; border-left: 5px solid #6c757d; }
.form-text span { font-weight: bold; color: #495057; }
#current-rate-card .display-1 { font-size: 4.5rem; line-height: 1; }

/* Validation & History */
.is-invalid { border-color: #dc3545; }
.invalid-feedback { display: block; }
.rate-increase { color: #dc3545; }
.rate-decrease { color: #198754; }
.rate-unchanged { color: #6c757d; }
.table-sm th, .table-sm td { padding: 0.4rem; font-size: 0.9rem; }

/* Alert Styling */
#insulinAlert.alert-danger, #potassiumLevelAlert.alert-danger { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; }
#insulinAlert.alert-warning, #potassiumLevelAlert.alert-warning { background-color: #fff3cd; color: #856404; border-color: #ffeeba; }
#insulinAlert.alert-info, #potassiumLevelAlert.alert-info { background-color: #d1ecf1; color: #0c5460; border-color: #bee5eb; }
#insulinAlert.alert-success, #potassiumLevelAlert.alert-success { background-color: #d4edda; color: #155724; border-color: #c3e6cb; }
#result-card .fs-6 { font-size: 1rem !important; } /* Ensure badge text is readable */

/* New styles for recommended fields */
#sodiumRecommendedNote, #potassiumRecommendedNote { font-weight: bold; }
.highly-recommended { border-color: #ffc107; }

/* Mobile optimizations */
@media (max-width: 768px) {
    .card-body { padding: 1rem; }
    .form-control-lg { height: auto; font-size: 1rem; padding: 0.5rem 0.75rem; }
    .btn-lg { font-size: 1rem; padding: 0.6rem 1rem; }
    .display-5 { font-size: 1.75rem; }
    #current-rate-card .display-1 { font-size: 3.5rem; }
    .table-sm { font-size: 0.85rem; }
    .d-md-flex .btn { margin-right: 0 !important; margin-bottom: 0.5rem; }
}

/* Print-specific styles (Mostly unchanged) */
@media print {
     header, footer, .navbar, nav, button, .btn, #patientSelectionModal, #editPatientButton, #rate-confirmation-card, #rateConfirmedMessage button, #clearHistoryButton { display: none !important; }
     body { padding: 0 !important; margin: 0 !important; background-color: #fff !important; }
     .container { max-width: 100% !important; padding: 0 !important; }
     .card { break-inside: avoid; border: 1px solid #ddd !important; margin-bottom: 15px !important; box-shadow: none !important; }
     .card-header { background-color: #f1f1f1 !important; color: #000 !important; border-bottom: 1px solid #ddd !important; }
     .card-header.bg-primary, .card-header.bg-info { background-color: #e9ecef !important; color: #000 !important; }
     #initial-info-card, #check-input-card { display: none !important; }
     #patient-summary-card, #current-rate-card, #result-card, #history-card { display: block !important; } /* Ensure rate card prints */
     .alert { border: 1px solid transparent; padding: 0.75rem 1.25rem; }
     .alert-danger { background-color: #f8d7da !important; color: #721c24 !important; border-color: #f5c6cb !important; }
     .alert-warning { background-color: #fff3cd !important; color: #856404 !important; border-color: #ffeeba !important; }
     .alert-success { background-color: #d4edda !important; color: #155724 !important; border-color: #c3e6cb !important; }
     .alert-info { background-color: #d1ecf1 !important; color: #0c5460 !important; border-color: #bee5eb !important; }
     .table, .table-bordered td, .table-bordered th { border: 1px solid #dee2e6 !important; }
     .table-responsive { overflow-x: visible !important; }
     #current-rate-card .display-1 { font-size: 4rem !important; } /* Make rate prominent in print */
}
</style>

<!-- Include jQuery if not already loaded -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Include Bootstrap JS if needed -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log("DKA/HHS Manager Initializing (v2.1)");

    // State Management
    let appState = {
        patientInfo: { patientType: 'dka', weight: null, initialBG: null, initialSodium: null, initialPotassium: null },
        currentInsulinRate: 0, currentFluidRecommendation: 'N/A', history: [], monitoring: false,
        lastBG: null, lastSodium: null, lastPotassium: null, lastCorrectedSodium: null,
        recommendedInsulinRate: 0, holdInsulinFlag: false, recommendedFluid: 'N/A', lastUpdated: null
    };

    // --- DOM Element References ---
    const initialInfoCard = document.getElementById('initial-info-card');
    const patientSummaryCard = document.getElementById('patient-summary-card');
    const currentRateCard = document.getElementById('current-rate-card');
    const checkInputCard = document.getElementById('check-input-card');
    const resultCard = document.getElementById('result-card');
    const historyCard = document.getElementById('history-card');
    const rateConfirmationCard = document.getElementById('rate-confirmation-card');
    const rateConfirmedMessage = document.getElementById('rateConfirmedMessage');
    const patientInfoForm = document.getElementById('patientInfoForm');
    const checkForm = document.getElementById('checkForm');
    const historyTableBody = document.getElementById('history-table-body');
    const noHistoryAlert = document.getElementById('no-history-alert');
    const hasHistoryContent = document.getElementById('has-history-content');
    const insulinHoldAlert = document.getElementById('insulinHoldAlert');
    const electrolytesRecommendedAlert = document.getElementById('electrolytesRecommendedAlert');
    const missingElectrolytesWarning = document.getElementById('missingElectrolytesWarning');

    // --- Bootstrap Modal (Unchanged) ---
    let patientSelectionModal = null;
    const modalElement = document.getElementById('patientSelectionModal');
    if (modalElement && typeof bootstrap !== 'undefined') {
        patientSelectionModal = new bootstrap.Modal(modalElement);
    } else { console.warn("Bootstrap modal JS not found or modal element missing."); }

    // --- Helper Functions (Unchanged) ---
    const formatTime = (timestamp) => timestamp ? new Date(timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }) : '-';
    const getValue = (id) => document.getElementById(id)?.value?.trim() || '';
    const setValue = (id, value) => { const el = document.getElementById(id); if (el) el.value = value; };
    const setContent = (id, content) => { const el = document.getElementById(id); if (el) el.innerHTML = content; };
    const toggleDisplay = (element, show) => element.style.display = show ? 'block' : 'none';
    const toggleClass = (id, className, add) => document.getElementById(id)?.classList.toggle(className, add);
    const getCheckedRadioValue = (name) => document.querySelector(`input[name="${name}"]:checked`)?.value || null;
    const round = (num, places = 1) => num === null || isNaN(num) ? null : Math.round(num * (10**places)) / (10**places);

    // --- Validation Functions ---
    const clearErrors = () => {
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        toggleDisplay(document.getElementById('sodiumRecommendedNote'), false); // Hide recommended notes
        toggleDisplay(document.getElementById('potassiumRecommendedNote'), false);
        toggleDisplay(electrolytesRecommendedAlert, false); // Hide alert
    };

    const showError = (id, message) => {
        toggleClass(id, 'is-invalid', true);
        setContent(`${id}Error`, message);
    };

    // Updated Initial Validation (Na/K always optional initially)
    const validateInitialInfo = () => {
        clearErrors();
        let isValid = true;
        const weight = getValue('weight');
        const initialBG = getValue('initialBG');
        const initialSodium = getValue('initialSodium');
        const initialPotassium = getValue('initialPotassium');
        const initialInsulinRate = getValue('initialInsulinRate');

        if (!weight || isNaN(weight) || weight <= 0) { showError('weight', 'Valid weight (kg) is required.'); isValid = false; }
        if (!initialBG || isNaN(initialBG) || initialBG < 0) { showError('initialBG', 'Valid initial BG is required.'); isValid = false; }
        if (initialSodium && (isNaN(initialSodium) || initialSodium <= 0)) { showError('initialSodium', 'Enter a valid sodium value if available.'); isValid = false; }
        if (initialPotassium && (isNaN(initialPotassium) || initialPotassium < 0)) { showError('initialPotassium', 'Enter a valid potassium value if available.'); isValid = false; }
         if (initialInsulinRate && (isNaN(initialInsulinRate) || initialInsulinRate < 0)) { showError('initialInsulinRate', 'Enter a valid rate or leave blank.'); isValid = false; }
        return isValid;
    };

    // Updated Check Validation (Na/K now always optional)
    const validateCheckInfo = () => {
        clearErrors();
        let isValid = true;
        const currentBGStr = getValue('currentBG');
        const currentSodium = getValue('currentSodium');
        const currentPotassium = getValue('currentPotassium');

        if (!currentBGStr || isNaN(currentBGStr) || currentBGStr < 0) {
            showError('currentBG', 'Valid current BG is required.');
            isValid = false;
        } else {
            const currentBG = parseInt(currentBGStr);
             // For BG < 250, show "Recommended" notes but don't require values
             if (currentBG < 250) {
                 toggleDisplay(document.getElementById('sodiumRecommendedNote'), true);
                 toggleDisplay(document.getElementById('potassiumRecommendedNote'), true);
                 toggleDisplay(electrolytesRecommendedAlert, true);
                 
                 // Add visual indication but don't make required
                 if (!currentSodium || currentSodium === '') {
                     document.getElementById('currentSodium').classList.add('highly-recommended');
                 }
                 if (!currentPotassium || currentPotassium === '') {
                     document.getElementById('currentPotassium').classList.add('highly-recommended');
                 }
             }
             
             // Validate if values provided (for any BG level)
             if (currentSodium && (isNaN(currentSodium) || currentSodium <= 0)) {
                 showError('currentSodium', 'Enter a valid sodium value if available.');
                 isValid = false;
             }
             if (currentPotassium && (isNaN(currentPotassium) || currentPotassium < 0)) {
                 showError('currentPotassium', 'Enter a valid potassium value if available.');
                 isValid = false;
             }
        }
        return isValid;
    };

    // --- Core Calculation Logic ---

    const calculateCorrectedSodium = (measuredSodium, glucose) => { // Unchanged
        if (measuredSodium === null || glucose === null || isNaN(measuredSodium) || isNaN(glucose)) return null;
        return round(measuredSodium + 0.016 * (Math.max(0, glucose - 100)), 1);
    };

    const getPotassiumStatus = (k) => { // Unchanged
        if (k === null || isNaN(k)) return { level: 'unknown', alertClass: 'alert-secondary', text: 'Potassium level unknown', actions: ['Obtain serum K+ level.'] };
        if (k < 3.3) return { level: 'severe_hypo', alertClass: 'alert-danger', text: 'Severe Hypokalemia (< 3.3)', actions: ['Hold Insulin!', 'Give 20-40 mEq/hr KCl IV.', 'Consider oral K+.', 'Reassess K+ q2h.'] };
        if (k < 4.0) return { level: 'mild_hypo', alertClass: 'alert-warning', text: 'Mild Hypokalemia (3.3-3.9)', actions: ['Infuse 20-30 mEq KCl/L in fluid.', 'Target 10-20 mEq/hr.', 'Reassess K+ q2-4h.'] };
        if (k <= 5.0) return { level: 'normal', alertClass: 'alert-success', text: 'Normal Potassium (4.0-5.0)', actions: ['Add 10-20 mEq KCl/L in fluid.', 'Reassess K+ q4h.'] };
        if (k <= 6.0) return { level: 'mild_hyper', alertClass: 'alert-info', text: 'Mild Hyperkalemia (5.1-6.0)', actions: ['Do not add KCl.', 'Reassess K+ q2-4h (expect fall).'] };
        return { level: 'severe_hyper', alertClass: 'alert-danger', text: 'Hyperkalemia (> 6.0)', actions: ['Do not add KCl.', 'Consider hyperkalemia Rx if ECG changes.', 'Reassess K+ q2h.'] };
    };

    // UPDATED Fluid Recommendation Logic to handle missing values
    const calculateFluidRecommendation = (corrSodium, serumK, currentBG) => {
        // --- BG >= 250: Generic Fluid Guidance ---
        if (currentBG === null || isNaN(currentBG) || currentBG >= 250) {
             let initialGuidance = "NS or ½NS based on initial Corrected Na+ & volume status";
             // Provide slightly more specific initial guidance if initial Na was recorded
              if (appState.lastCorrectedSodium !== null) {
                  initialGuidance = appState.lastCorrectedSodium >= 140 ? "½NS likely appropriate (unless severe volume depletion)" : "NS likely appropriate";
              }
              return {
                  fluid: initialGuidance,
                  detailed: false, // Flag to indicate this is generic
                  actions: ["Assess volume status.", "Continue appropriate resuscitation fluid (NS or ½NS).", "Monitor electrolytes regularly.", "Fluid strategy will change when BG < 250."]
              };
         }

         // --- BG < 250: Check for missing values ---
         else {
            // If Na+ and K+ are missing, provide limited guidance
            if ((serumK === null || isNaN(serumK)) && (corrSodium === null || isNaN(corrSodium))) {
                return {
                    fluid: "D5NS @ 150 mL/hr (with KCl as indicated once K+ known)",
                    detailed: false,
                    actions: [
                        "BG < 250 mg/dL: Change to D5-containing fluid.",
                        "STRONGLY CONSIDER obtaining Na+ and K+ values for optimal management.",
                        "Without K+ data, insulin safety cannot be fully assessed.",
                        "Unknown electrolyte status limits fluid optimization."
                    ]
                };
            }
            // If only Na+ is missing
            else if (corrSodium === null || isNaN(corrSodium)) {
                const kStatus = getPotassiumStatus(serumK);
                let fluid = "D5NS @ 150 mL/hr";
                let actions = [
                    "BG < 250 mg/dL: Change to D5-containing fluid.",
                    "Unable to calculate corrected Na+, consider obtaining sodium level."
                ];
                
                // Check if potassium indicates we should avoid adding KCl
                if (kStatus.level === 'mild_hyper' || kStatus.level === 'severe_hyper') {
                    fluid = "D5NS (No KCl) @ 150 mL/hr";
                    actions.push("K+ > 5.0: Holding KCl in D5NS.");
                } else if (kStatus.level === 'normal' || kStatus.level === 'mild_hypo') {
                    fluid = "D5NS w/ 20 mEq KCl/L @ 150 mL/hr";
                    if (kStatus.level === 'mild_hypo') {
                        actions.push("K+ 3.3-4.0: Consider 30 mEq KCl/L if needed.");
                    }
                }
                
                // Add K+ status actions
                actions.push(`--- (${kStatus.text}) ---`);
                actions.push(...kStatus.actions);
                
                return { fluid, detailed: (kStatus.level !== 'unknown'), actions };
            }
            // If only K+ is missing
            else if (serumK === null || isNaN(serumK)) {
                let naInterp = corrSodium < 135 ? 'Hyponatremia' : (corrSodium <= 145 ? 'Normal' : 'Hypernatremia');
                let fluid = "D5NS @ 150 mL/hr";
                let actions = [
                    "BG < 250 mg/dL: Change to D5-containing fluid.",
                    `Corrected Na+ ${corrSodium} (${naInterp})`,
                    "STRONGLY CONSIDER obtaining K+ value for insulin safety.",
                    "Unable to assess potassium status - critical for insulin safety.",
                    "Once K+ is known, update KCl additions appropriately."
                ];
                
                return { fluid, detailed: true, actions };
            }
            // Both Na+ and K+ are available - provide detailed recommendations
            else {
                let fluid = "";
                let actions = [];
                const kStatus = getPotassiumStatus(serumK);

                // Default for BG < 250 per protocol
                fluid = "D5NS w/ 20 mEq KCl/L @ 150 mL/hr"; 
                actions.push("BG < 250 mg/dL: Change to D5NS with KCl per protocol.");
                actions.push("Monitor BG closely, adjust insulin per titration table.");

                if (kStatus.level === 'severe_hypo') {
                    actions.push("Ensure K+ is > 3.3 before continuing insulin.");
                    actions.push("Consider supplemental IV K+ in addition to fluid if needed.");
                } else if (kStatus.level === 'mild_hyper' || kStatus.level === 'severe_hyper') {
                    fluid = "D5NS (No KCl) @ 150 mL/hr"; 
                    actions.push("K+ > 5.0: Holding KCl in D5NS initially.");
                    actions.push("Monitor K+ q2-4h, add KCl later if K+ drops into normal range.");
                } else {
                    actions.push("Continue standard K+ supplementation (20 mEq/L) in D5NS.");
                    if (kStatus.level === 'mild_hypo') actions.push("May need 30 mEq/L or additional K+ if level remains low, monitor closely.");
                }

                // Include general K status actions
                actions.push(`--- (${kStatus.text}) ---`);
                actions.push(...kStatus.actions);

                return { fluid, detailed: true, actions };
            }
        }
    };

    // Insulin Calculation Logic (Updated to handle missing K+ more explicitly)
    const calculateInsulinRate = (bg, prevBG, currentRate, weight, patientType, serumK) => {
        // CRITICAL SAFETY CHECK FIRST: Hold if K+ < 3.3
         if (serumK !== null && !isNaN(serumK) && serumK < 3.3) {
            return { 
                newRate: 0, // Force rate to 0
                recommendation: "HOLD INSULIN",
                actions: ["Serum K+ < 3.3 mEq/L.", "Hold insulin infusion.", "Aggressively replete potassium per protocol.", "Recheck K+ q2h."],
                holdInsulin: true,
                kStatus: 'danger'
            };
        }

        // Prepare for missing K+ warning
        let kStatusMsg = '';
        let kStatusClass = '';
        
        if (serumK === null || isNaN(serumK)) {
            if (bg < 250) {
                kStatusMsg = 'Unknown K+ status - exercise caution';
                kStatusClass = 'warning';
            }
        }

        // Proceed with normal calculation if K+ is okay or unknown (with warning)
        let newRate = currentRate;
        let recommendation = `Maintain current rate: ${round(currentRate)} u/hr`;
        let actions = ["No change indicated based on current BG."];
        let holdInsulin = false; // Reset hold flag if K+ >= 3.3

        const kStatus = getPotassiumStatus(serumK);

        if (patientType === 'dka') {
             if (bg >= 250) {
                 if (prevBG !== null && prevBG !== bg && (prevBG - bg < 50)) {
                     newRate = currentRate + 1;
                     recommendation = `Increase rate by 1 u/hr to ${round(newRate)} u/hr`;
                     actions = ["BG ≥ 250.", "BG drop < 50 mg/dL/hr.", "Increase rate by 1 unit/hr."];
                 } else {
                     newRate = currentRate;
                     recommendation = `Continue rate: ${round(newRate)} u/hr`;
                     actions = ["BG ≥ 250.", (prevBG ? "Adequate BG drop (≥ 50 mg/dL/hr) or no change needed." : "Initial rate or insufficient data.")];
                 }
             } else { // DKA with BG < 250 - Use Titration Table
                 if (prevBG !== null && prevBG >= 250) { // First drop below 250
                     newRate = round(0.05 * weight);
                     recommendation = `Decrease rate to 0.05 u/kg/hr (${round(newRate)} u/hr)`;
                     actions = ["BG dropped < 250.", "Decrease insulin to 0.05 u/kg/hr.", "Ensure D5-containing fluid running."];
                 } else { // Already < 250 or initial BG < 250
                    ({ newRate, recommendation, actions, holdInsulin } = applyTitrationTable(bg, currentRate)); // Apply table
                 }
             }
        } else { // HHS Logic - Always Use Titration Table once started
            ({ newRate, recommendation, actions, holdInsulin } = applyTitrationTable(bg, currentRate));
        }

        // Ensure rate is not negative and rounded
        newRate = Math.max(0, round(newRate));

         // If K+ is unknown and BG < 250, add a strong warning
         if (serumK === null || isNaN(serumK)) {
             if (bg < 250) {
                 actions.unshift("⚠️ K+ UNKNOWN - CAUTION: Consider obtaining K+ ASAP to ensure insulin safety.");
                 actions.push("Exercise caution with insulin administration without known K+ status.");
                 actions.push("Insulin can lower serum K+, which could potentially be dangerous if K+ is already low.");
             } else {
                actions.push("Consider obtaining K+ at next lab check, especially as BG approaches 250 mg/dL.");
             }
         }
         // Add K+ actions if available and not holding insulin
         else if (!holdInsulin && kStatus.actions.length > 0 && kStatus.level !== 'unknown' && kStatus.level !== 'severe_hypo') {
             actions.push(`--- Potassium Considerations (${kStatus.text}) ---`);
             actions.push(...kStatus.actions.filter(a => !a.startsWith("Hold Insulin"))); // Don't repeat hold insulin action
         }


        return { 
            newRate, 
            recommendation, 
            actions, 
            holdInsulin, 
            kStatusMsg, 
            kStatusClass 
        };
    };

    // Apply Titration Table Function (Unchanged)
    const applyTitrationTable = (bg, currentRate) => {
        let newRate = currentRate; let recommendation = `Continue: ${round(currentRate)} u/hr`; let actions = ["No change per table."]; let holdInsulin = false;
        if (bg < 70) { newRate = 0; recommendation = "STOP Insulin"; actions = ["BG < 70.", "STOP insulin.", "Hypoglycemia Orders.", "Notify provider.", "Consider D50.", "Restart 0.5 u/hr when BG ≥ 140."]; holdInsulin = true; }
        else if (bg <= 119) { newRate = 0.5; recommendation = `Decrease to 0.5 u/hr`; actions = ["BG 70-119.", "Rate to 0.5 u/hr (if not already)."]; }
        else if (bg <= 149) { if (currentRate > 1) { newRate = 1; recommendation = `Decrease to 1 u/hr`; actions = ["BG 120-149.", "Rate to 1 u/hr."]; } else { newRate = currentRate; recommendation = `Continue: ${round(newRate)} u/hr`; actions = ["BG 120-149.", "Rate already ≤ 1 u/hr, no change."]; } }
        else if (bg <= 199) { newRate = Math.max(0.5, currentRate - 2); recommendation = `Decrease by 2 u/hr to ${round(newRate)} u/hr`; actions = ["BG 150-199 (Goal).", "Decrease rate by 2 u/hr (min 0.5)."]; }
        else if (bg <= 299) { newRate = currentRate; recommendation = `No change: ${round(newRate)} u/hr`; actions = ["BG 200-299.", "No change."]; }
        else { newRate = currentRate + 1; recommendation = `Increase by 1 u/hr to ${round(newRate)} u/hr`; actions = ["BG ≥ 300.", "Increase rate by 1 u/hr.", "Consider if ↓ D5 rate needed (provider)."]; }
        return { newRate: round(Math.max(0, newRate)), recommendation, actions, holdInsulin };
    };

    // --- UI Update Functions ---

    // Update the new prominent rate display
    const updateCurrentRateDisplay = () => {
        const rate = round(appState.currentInsulinRate);
        setContent('displayCurrentInsulinRate', rate ?? '---');
        // Show/hide the HOLD alert in the rate card based on actual current rate being 0 AND the hold flag being set
        toggleDisplay(insulinHoldAlert, appState.holdInsulinFlag && appState.currentInsulinRate === 0);
    };

    const updatePatientSummary = () => { // Removed rate from here
        setContent('summaryPatientType', appState.patientInfo.patientType.toUpperCase());
        setContent('summaryWeight', appState.patientInfo.weight ?? '-');
        setContent('summaryCurrentFluid', appState.currentFluidRecommendation || 'N/A');
    };

    const setupMonitoringView = () => {
        toggleDisplay(initialInfoCard, false);
        toggleDisplay(patientSummaryCard, true);
        toggleDisplay(currentRateCard, true); // Show the rate card
        toggleDisplay(checkInputCard, true);
        toggleDisplay(resultCard, false);
        toggleDisplay(historyCard, true);
        toggleDisplay(rateConfirmedMessage, false);

        updatePatientSummary();
        updateCurrentRateDisplay(); // Update prominent rate
        updateHistoryTab();

        setContent('previousBGDisplay', appState.lastBG ?? '-');
        setContent('previousSodiumDisplay', appState.lastSodium ?? '-');
        setContent('previousPotassiumDisplay', appState.lastPotassium ?? '-');
        setValue('currentBG', '');
        setValue('currentSodium', '');
        setValue('currentPotassium', '');
    };

    // Updated Results Display Logic
    const displayResults = (data) => {
        toggleDisplay(checkInputCard, false);
        toggleDisplay(resultCard, true);
        toggleDisplay(rateConfirmationCard, !data.insulin.holdInsulin);
        toggleDisplay(rateConfirmedMessage, false);

        // Show or hide missing electrolytes warning
        const bgBelow250 = data.currentBG !== null && data.currentBG < 250;
        const hasMissingElectrolytes = (data.currentSodium === null || data.currentPotassium === null) && bgBelow250;
        toggleDisplay(missingElectrolytesWarning, hasMissingElectrolytes);

        // --- Populate BG Info ---
        setContent('resultBG', data.currentBG ?? '-');
        let severityLevel = '', severityClass = 'bg-secondary';
        if (data.currentBG !== null) {
            if (data.currentBG < 70) { severityLevel = 'Critical Low'; severityClass = 'bg-danger text-white'; }
            else if (data.currentBG < 150) { severityLevel = 'Low/Normal'; severityClass = 'bg-info text-dark'; }
            else if (data.currentBG < 200) { severityLevel = 'Goal Range'; severityClass = 'bg-success text-white'; }
            else if (data.currentBG < 250) { severityLevel = 'Mildly Elevated'; severityClass = 'bg-warning text-dark'; } // Added distinction < 250
            else if (data.currentBG < 300) { severityLevel = 'Elevated'; severityClass = 'bg-warning text-dark'; }
             else { severityLevel = 'Very High'; severityClass = 'bg-danger text-white'; }
        }
        setContent('severityBadge', severityLevel);
        document.getElementById('severityBadge').className = `badge fs-6 ${severityClass}`;

        // --- Populate Insulin Recommendations ---
        setContent('insulinRecommendationText', data.insulin.recommendation);
        document.getElementById('insulinAlert').className = `alert ${data.insulin.holdInsulin ? 'alert-danger' : 'alert-info'}`;
        const insulinList = document.getElementById('insulinActionStepsList');
        insulinList.innerHTML = '';
        data.insulin.actions.forEach(action => { const li = document.createElement('li'); li.className = 'list-group-item'; li.textContent = action; insulinList.appendChild(li); });

        // --- Populate Fluid Recommendations (Conditional Detail) ---
        if (data.fluid.detailed) { // BG < 250 and detailed recommendation available
             toggleDisplay(document.getElementById('fluidRecGeneral'), false);
             toggleDisplay(document.getElementById('fluidRecDetailed'), true);

             setContent('resultCorrectedSodium', data.correctedSodium ?? '-');
             setContent('resultPotassium', data.currentPotassium ?? '-');

             let naInterp = '-';
             if (data.correctedSodium !== null) { naInterp = data.correctedSodium < 135 ? 'Hyponatremia' : (data.correctedSodium <= 145 ? 'Normal' : 'Hypernatremia'); }
             setContent('sodiumInterpretation', naInterp);

             setContent('potassiumInterpretation', data.potassiumStatus.text);
             document.getElementById('potassiumLevelAlert').className = `alert mt-2 mb-0 py-1 small ${data.potassiumStatus.alertClass}`;

             setContent('fluidRecommendationDetailedText', data.fluid.fluid);
             const fluidList = document.getElementById('fluidActionStepsList');
             fluidList.innerHTML = '';
             data.fluid.actions.forEach(action => { const li = document.createElement('li'); li.className = 'list-group-item'; li.textContent = action; fluidList.appendChild(li); });

         } else { // BG >= 250 or missing Na/K when needed
             toggleDisplay(document.getElementById('fluidRecGeneral'), true);
             toggleDisplay(document.getElementById('fluidRecDetailed'), false);
             setContent('fluidRecommendationGeneralText', data.fluid.fluid); // Show generic fluid text
         }

        // --- Dextrose Warning ---
        toggleDisplay(document.getElementById('dextroseWarning'), data.currentBG !== null && data.currentBG < 250);

        // --- Rate Confirmation Section ---
        if (!data.insulin.holdInsulin) {
             setValue('currentRateDisplay', round(appState.currentInsulinRate));
             setValue('newRateInput', data.insulin.newRate);
             setContent('recommendedRateLabel', `(Recommended: ${data.insulin.newRate} u/hr)`);
        }
    };

    // History Update (Unchanged)
    const updateHistoryTab = () => {
        if (!appState.history || appState.history.length === 0) { toggleDisplay(noHistoryAlert, true); toggleDisplay(hasHistoryContent, false); return; }
        toggleDisplay(noHistoryAlert, false); toggleDisplay(hasHistoryContent, true); historyTableBody.innerHTML = '';
        [...appState.history].reverse().forEach(entry => {
            const row = historyTableBody.insertRow();
            let changeIcon = '', changeClass = '';
            if (entry.prevInsulinRate !== null) { const diff = entry.insulinRate - entry.prevInsulinRate; if (diff > 0) { changeIcon = '<i class="ti ti-arrow-up"></i>'; changeClass = 'rate-increase'; } else if (diff < 0) { changeIcon = '<i class="ti ti-arrow-down"></i>'; changeClass = 'rate-decrease'; } else { changeIcon = '<i class="ti ti-minus"></i>'; changeClass = 'rate-unchanged'; } }
            row.innerHTML = `<td>${formatTime(entry.timestamp)}</td><td>${entry.bg ?? '-'}</td><td>${entry.measuredSodium ?? '-'}</td><td>${entry.serumPotassium ?? '-'}</td><td>${entry.correctedSodium ?? '-'}</td><td>${round(entry.insulinRate) ?? '-'} ${entry.holdInsulin ? '<span class="badge bg-danger ms-1">HELD</span>' : ''}</td><td class="${changeClass}">${changeIcon}</td><td>${entry.fluidRecommendation || '-'}</td><td>${entry.note || ''}</td>`;
        });
    };

    // --- State Persistence (Unchanged) ---
    const saveAppState = () => { try { appState.lastUpdated = Date.now(); localStorage.setItem('dkaHhsManagerStateV2', JSON.stringify(appState)); console.log("App State Saved", appState); } catch (e) { console.error("Error saving state:", e); alert("Warning: Could not save patient data."); } };
    const loadAppState = () => { const saved = localStorage.getItem('dkaHhsManagerStateV2'); if (saved) { try { appState = { ...appState, ...JSON.parse(saved) }; console.log("App State Loaded", appState); return true; } catch (e) { console.error("Error loading state:", e); localStorage.removeItem('dkaHhsManagerStateV2'); return false; } } return false; };
    const resetAppState = (showModal = false) => { console.log("Resetting application state"); localStorage.removeItem('dkaHhsManagerStateV2'); appState = { patientInfo: { patientType: 'dka', weight: null, initialBG: null, initialSodium: null, initialPotassium: null }, currentInsulinRate: 0, currentFluidRecommendation: 'N/A', history: [], monitoring: false, lastBG: null, lastSodium: null, lastPotassium: null, lastCorrectedSodium: null, recommendedInsulinRate: 0, holdInsulinFlag: false, recommendedFluid: 'N/A', lastUpdated: null }; patientInfoForm.reset(); checkForm.reset(); clearErrors(); toggleDisplay(initialInfoCard, true); toggleDisplay(patientSummaryCard, false); toggleDisplay(currentRateCard, false); toggleDisplay(checkInputCard, false); toggleDisplay(resultCard, false); toggleDisplay(historyCard, false); updateHistoryTab(); if (showModal && patientSelectionModal) patientSelectionModal.hide(); };

    // --- Modal Logic (Updated Summary) ---
    const showPatientSelectionModal = () => { /* ... modal show logic ... */
        if (loadAppState() && appState.monitoring && appState.history.length > 0) {
            setContent('modalPatientType', appState.patientInfo.patientType.toUpperCase()); setContent('modalWeight', appState.patientInfo.weight ?? '-'); setContent('modalLastBG', appState.lastBG ?? '-'); setContent('modalLastK', appState.lastPotassium ?? '-'); setContent('modalCurrentRate', round(appState.currentInsulinRate) ?? '-'); setContent('modalCurrentFluid', appState.currentFluidRecommendation || 'N/A'); setContent('modalLastUpdated', appState.lastUpdated ? new Date(appState.lastUpdated).toLocaleString() : 'N/A');
            toggleDisplay(document.getElementById('existingPatientSection'), true); toggleDisplay(document.getElementById('noExistingPatientSection'), false);
        } else { toggleDisplay(document.getElementById('existingPatientSection'), false); toggleDisplay(document.getElementById('noExistingPatientSection'), true); if (!appState.monitoring) resetAppState(); }
        if (patientSelectionModal) patientSelectionModal.show(); else initializeAppState(false);
    };
    const initializeAppState = (loadedSuccessfully) => { if (loadedSuccessfully && appState.monitoring) { setupMonitoringView(); } else { resetAppState(); } };

    // --- Event Listeners ---

    // Modal Buttons (Unchanged)
    document.getElementById('continuePreviousPatientBtn').addEventListener('click', () => { if (patientSelectionModal) patientSelectionModal.hide(); initializeAppState(true); });
    document.getElementById('startNewPatientBtn').addEventListener('click', () => resetAppState(true));
    document.getElementById('startFreshPatientBtn').addEventListener('click', () => resetAppState(true));

    // Initial Form Buttons (Logic updated for initial fluid/hold check)
     document.getElementById('resetPatientButton').addEventListener('click', () => { patientInfoForm.reset(); clearErrors(); });
     document.getElementById('startMonitoringButton').addEventListener('click', () => {
        if (validateInitialInfo()) {
            appState.patientInfo = { patientType: getCheckedRadioValue('patientType'), weight: parseFloat(getValue('weight')), initialBG: parseInt(getValue('initialBG')), initialSodium: getValue('initialSodium') ? parseFloat(getValue('initialSodium')) : null, initialPotassium: getValue('initialPotassium') ? parseFloat(getValue('initialPotassium')) : null };
            appState.lastBG = appState.patientInfo.initialBG; appState.lastSodium = appState.patientInfo.initialSodium; appState.lastPotassium = appState.patientInfo.initialPotassium;
            appState.lastCorrectedSodium = calculateCorrectedSodium(appState.lastSodium, appState.lastBG);

            // Initial Insulin Rate
            const providedInitialRate = getValue('initialInsulinRate'); let initialRate = 0;
            if (providedInitialRate && !isNaN(providedInitialRate) && providedInitialRate >= 0) { initialRate = parseFloat(providedInitialRate); }
            else { initialRate = (appState.patientInfo.patientType === 'dka' && appState.lastBG >= 250) ? (0.1 * appState.patientInfo.weight) : (0.05 * appState.patientInfo.weight); }
            appState.currentInsulinRate = round(initialRate); appState.recommendedInsulinRate = appState.currentInsulinRate;

            // Check for initial hold
             appState.holdInsulinFlag = appState.lastPotassium !== null && appState.lastPotassium < 3.3;
             if (appState.holdInsulinFlag) { appState.currentInsulinRate = 0; console.warn("Initial K+ < 3.3 - Insulin Held"); }

             // Initial Fluid Recommendation (use new function - likely generic)
             const initialFluidRec = calculateFluidRecommendation(appState.lastCorrectedSodium, appState.lastPotassium, appState.lastBG);
             appState.currentFluidRecommendation = initialFluidRec.fluid;

             // Initial History
             appState.history = [];
             const initialEntry = { timestamp: Date.now(), bg: appState.lastBG, measuredSodium: appState.lastSodium, serumPotassium: appState.lastPotassium, correctedSodium: appState.lastCorrectedSodium, insulinRate: appState.currentInsulinRate, prevInsulinRate: null, holdInsulin: appState.holdInsulinFlag, fluidRecommendation: appState.currentFluidRecommendation, note: `Initial Assessment. ${providedInitialRate ? 'Rate provided.' : 'Rate calculated.'} ${appState.holdInsulinFlag ? 'Insulin Held (K+ < 3.3).' : ''}` };
             appState.history.push(initialEntry);

             appState.monitoring = true; saveAppState(); setupMonitoringView();
        }
    });

     // Edit Button (Logic to hide new rate card)
     document.getElementById('editPatientButton').addEventListener('click', () => {
         toggleDisplay(initialInfoCard, true); toggleDisplay(patientSummaryCard, false); toggleDisplay(currentRateCard, false); toggleDisplay(checkInputCard, false); toggleDisplay(resultCard, false); toggleDisplay(historyCard, false);
         document.getElementById(appState.patientInfo.patientType === 'dka' ? 'typeDKA' : 'typeHHS').checked = true;
         setValue('weight', appState.patientInfo.weight); setValue('initialBG', appState.patientInfo.initialBG); setValue('initialSodium', appState.patientInfo.initialSodium); setValue('initialPotassium', appState.patientInfo.initialPotassium); setValue('initialInsulinRate', '');
     });

    // Calculate Recommendations Button (Updated to handle conditional Na/K)
     document.getElementById('calculateButton').addEventListener('click', () => {
        if (validateCheckInfo()) { // Validation now checks BG but makes Na/K optional
            const currentBG = parseInt(getValue('currentBG'));
            const currentSodium = getValue('currentSodium') ? parseFloat(getValue('currentSodium')) : null; // Allow null
            const currentPotassium = getValue('currentPotassium') ? parseFloat(getValue('currentPotassium')) : null; // Allow null
            const prevBG = appState.lastBG;

            const correctedSodium = calculateCorrectedSodium(currentSodium, currentBG);
            const potassiumStatus = getPotassiumStatus(currentPotassium);
            // Fluid rec depends on BG level & available values
            const fluidRec = calculateFluidRecommendation(correctedSodium, currentPotassium, currentBG);
            // Insulin rec - can proceed without K+ but with warnings
            const insulinRec = calculateInsulinRate(currentBG, prevBG, appState.currentInsulinRate, appState.patientInfo.weight, appState.patientInfo.patientType, currentPotassium);

            appState.recommendedInsulinRate = insulinRec.newRate;
            appState.holdInsulinFlag = insulinRec.holdInsulin;
            appState.recommendedFluid = fluidRec.fluid;

            displayResults({ 
                currentBG, 
                currentSodium, 
                currentPotassium, 
                correctedSodium, 
                potassiumStatus, 
                fluid: fluidRec, 
                insulin: insulinRec 
            });
        }
     });

    // Use Recommended Rate Button (Unchanged)
    document.getElementById('useRecommendedRateButton').addEventListener('click', () => { setValue('newRateInput', appState.recommendedInsulinRate); });

    // Confirm Rate Button (Updated logic for history)
     document.getElementById('confirmRateButton').addEventListener('click', () => {
         const newRateInputVal = getValue('newRateInput');
         if (newRateInputVal === '' || isNaN(newRateInputVal) || newRateInputVal < 0) { alert('Please enter a valid insulin rate (or 0).'); return; }
         const confirmedRate = round(parseFloat(newRateInputVal));
         const currentBG = parseInt(getValue('currentBG'));
         const currentSodium = getValue('currentSodium') ? parseFloat(getValue('currentSodium')) : null;
         const currentPotassium = getValue('currentPotassium') ? parseFloat(getValue('currentPotassium')) : null;

         let note = '';
         if (appState.holdInsulinFlag) { note = 'Insulin Held (K+ < 3.3).'; if (confirmedRate !== 0) { alert("WARNING: Insulin should be held (rate 0) when K+ < 3.3. Confirming non-zero rate."); note += ` Confirmed rate: ${confirmedRate} u/hr (Override).`; } }
         else if (confirmedRate !== appState.recommendedInsulinRate) { note = `Manual rate set (Recommended: ${appState.recommendedInsulinRate} u/hr).`; }
         
         // Add note if electrolytes are missing when BG < 250
         if (currentBG < 250) {
             if (currentSodium === null && currentPotassium === null) {
                 note += " Na+/K+ unavailable - limited guidance.";
             } else if (currentSodium === null) {
                 note += " Na+ unavailable.";
             } else if (currentPotassium === null) {
                 note += " K+ unavailable - exercise caution.";
             }
         }

         const prevInsulinRate = appState.currentInsulinRate;
         appState.currentInsulinRate = confirmedRate;
         // Recommended fluid was already calculated based on BG level and stored in appState
         appState.currentFluidRecommendation = appState.recommendedFluid;

         appState.lastBG = currentBG; appState.lastSodium = currentSodium; appState.lastPotassium = currentPotassium;
         appState.lastCorrectedSodium = calculateCorrectedSodium(currentSodium, currentBG);

         const historyEntry = { timestamp: Date.now(), bg: currentBG, measuredSodium: currentSodium, serumPotassium: currentPotassium, correctedSodium: appState.lastCorrectedSodium, insulinRate: confirmedRate, prevInsulinRate: prevInsulinRate, holdInsulin: appState.holdInsulinFlag && confirmedRate === 0, fluidRecommendation: appState.currentFluidRecommendation, note: note };
         appState.history.push(historyEntry);

         saveAppState();
         updatePatientSummary();
         updateCurrentRateDisplay(); // Update prominent rate display
         updateHistoryTab();

         toggleDisplay(rateConfirmationCard, false);
         toggleDisplay(rateConfirmedMessage, true);
     });

    // New Check Button (Unchanged)
    document.getElementById('newCheckButton').addEventListener('click', () => {
        toggleDisplay(resultCard, false); toggleDisplay(checkInputCard, true);
        setContent('previousBGDisplay', appState.lastBG ?? '-'); setContent('previousSodiumDisplay', appState.lastSodium ?? '-'); setContent('previousPotassiumDisplay', appState.lastPotassium ?? '-');
        setValue('currentBG', ''); setValue('currentSodium', ''); setValue('currentPotassium', '');
        checkForm.reset(); clearErrors();
    });

     // Clear History Button (Unchanged)
     document.getElementById('clearHistoryButton').addEventListener('click', () => { if (confirm('Are you sure?')) { appState.history = []; updateHistoryTab(); saveAppState(); } });

    // --- Initial Load ---
    showPatientSelectionModal();

}); // End DOMContentLoaded
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>