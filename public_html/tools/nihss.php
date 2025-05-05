<?php
/**
 * NIHSS Calculator Tool
 * 
 * Place this file in: /tool_nihss.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Set page title and active tab
$page_title = 'NIHSS Calculator';
$active_tab = 'tools';

// Include header
include '../includes/frontend_header.php';
?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h1 class="h3 mb-0">NIH Stroke Scale Calculator</h1>
                </div>
                <div class="card-body">
                    <p class="lead">Use this calculator to determine the NIH Stroke Scale score. Click on the <i class="ti ti-info-circle"></i> icons for detailed information about how to perform each assessment.</p>
                    
                    <div class="alert alert-info">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-info-circle fs-4 me-3"></i>
                            <div>
                                <strong>Total Score Interpretation:</strong><br>
                                0: No stroke symptoms<br>
                                1-4: Minor stroke<br>
                                5-15: Moderate stroke<br>
                                16-20: Moderate to severe stroke<br>
                                21-42: Severe stroke
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Results Display (initially hidden) -->
            <div id="results-container" class="card mb-4 d-none">
                <div class="card-header bg-success text-white">
                    <h2 class="h5 mb-0">NIHSS Results</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-center mb-4">
                                <div id="score-circle" class="d-flex align-items-center justify-content-center rounded-circle bg-primary text-white" style="width: 120px; height: 120px;">
                                    <span id="total-score" class="display-4">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h6 text-muted">Stroke Severity</h3>
                            <h3 id="severity-text" class="h4 mb-3">No Stroke Symptoms</h3>
                            <p id="score-interpretation" class="mb-0">Score interpretation will appear here.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <form id="nihss-calculator">
                <!-- 1. Level of Consciousness -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">1. Level of Consciousness</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>The investigator must choose a response if a full evaluation is prevented by such obstacles as an endotracheal tube, language barrier, orotracheal trauma/bandages. A 3 is scored only if the patient makes no movement (other than reflexive posturing) in response to noxious stimulation.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="loc" data-value="0">
                                0: Alert
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="loc" data-value="1">
                                1: Not alert, arousable
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="loc" data-value="2">
                                2: Not alert, requires stimulation
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="loc" data-value="3">
                                3: Unresponsive
                            </button>
                        </div>
                        <input type="hidden" name="loc" id="loc" value="">
                    </div>
                </div>
                
                <!-- 2. LOC Questions -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">2. LOC Questions</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>Ask the patient the month and their age. Score 0 for both correct, 1 for one correct, 2 for none correct. Only the initial answer is graded. Aphasic and stuporous patients who do not comprehend the questions will score 2. Patients unable to speak because of endotracheal intubation, orotracheal trauma, severe dysarthria from any cause, language barrier, or any other problem not secondary to aphasia are given a 1.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="loc_questions" data-value="0">
                                0: Answers both correctly
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="loc_questions" data-value="1">
                                1: Answers one correctly
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="loc_questions" data-value="2">
                                2: Answers none correctly
                            </button>
                        </div>
                        <input type="hidden" name="loc_questions" id="loc_questions" value="">
                    </div>
                </div>
                
                <!-- 3. LOC Commands -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">3. LOC Commands</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>Ask the patient to open and close the eyes and then to grip and release the non-paretic hand. Substitute another one-step command if the hands cannot be used. Score 0 for correctly performing both tasks, 1 for performing one task correctly, 2 for performing neither task correctly. If patient does not respond to command, demonstrate the task.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="loc_commands" data-value="0">
                                0: Performs both tasks correctly
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="loc_commands" data-value="1">
                                1: Performs one task correctly
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="loc_commands" data-value="2">
                                2: Performs neither task correctly
                            </button>
                        </div>
                        <input type="hidden" name="loc_commands" id="loc_commands" value="">
                    </div>
                </div>
                
                <!-- 4. Best Gaze -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">4. Best Gaze</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>Test horizontal eye movements by voluntary or reflexive (oculocephalic) activity. Score 1 only if there is a forced deviation or total gaze paresis not overcome by the oculocephalic maneuver. Score 0 if patient has conjugate deviation of eyes that can be overcome by voluntary or reflexive activity.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="best_gaze" data-value="0">
                                0: Normal
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="best_gaze" data-value="1">
                                1: Partial gaze palsy
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="best_gaze" data-value="2">
                                2: Forced deviation/total gaze paresis
                            </button>
                        </div>
                        <input type="hidden" name="best_gaze" id="best_gaze" value="">
                    </div>
                </div>
                
                <!-- 5. Visual Fields -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">5. Visual Fields</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>Test visual fields by confrontation. Score 1 only if there is a clear-cut asymmetry including quadrantanopia. Score 2 if there is complete hemianopia. If patient has blindness from any cause, score 3. Double simultaneous stimulation is performed in this part of the exam; if there is extinction, patient receives a 1 and the results are used to respond to item 11.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="visual" data-value="0">
                                0: No visual loss
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="visual" data-value="1">
                                1: Partial hemianopia
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="visual" data-value="2">
                                2: Complete hemianopia
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="visual" data-value="3">
                                3: Bilateral hemianopia
                            </button>
                        </div>
                        <input type="hidden" name="visual" id="visual" value="">
                    </div>
                </div>
                
                <!-- 6. Facial Palsy -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">6. Facial Palsy</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>Ask or use pantomime to encourage the patient to show teeth or raise eyebrows and close eyes. Score symmetry of grimace in response to noxious stimuli in the poorly responsive or non-comprehending patient. If facial trauma/bandages, orotracheal tube, tape or other physical barriers obscure the face, these should be removed to the extent possible.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="facial_palsy" data-value="0">
                                0: Normal symmetrical movement
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="facial_palsy" data-value="1">
                                1: Minor paralysis
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="facial_palsy" data-value="2">
                                2: Partial paralysis
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="facial_palsy" data-value="3">
                                3: Complete paralysis
                            </button>
                        </div>
                        <input type="hidden" name="facial_palsy" id="facial_palsy" value="">
                    </div>
                </div>
                
                <!-- 7a. Motor Arm - Left -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">7a. Motor Arm - Left</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>The limb is placed in the appropriate position: extend the arms (palms down) 90 degrees (if sitting) or 45 degrees (if supine). Drift is scored if the arm falls before 10 seconds. The aphasic patient is encouraged using urgency in the voice and pantomime, but not noxious stimulation. Each limb is tested in turn, beginning with the non-paretic arm. Only in the case of amputation or joint fusion at the shoulder, the examiner should record the score as untestable (UN), and clearly write the explanation for this choice.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_arm_left" data-value="0">
                                0: No drift
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_arm_left" data-value="1">
                                1: Drift
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_arm_left" data-value="2">
                                2: Some effort against gravity
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_arm_left" data-value="3">
                                3: No effort against gravity
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_arm_left" data-value="4">
                                4: No movement
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_arm_left" data-value="UN">
                                UN: Amputation/joint fusion
                            </button>
                        </div>
                        <input type="hidden" name="motor_arm_left" id="motor_arm_left" value="">
                    </div>
                </div>
                
                <!-- 7b. Motor Arm - Right -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">7b. Motor Arm - Right</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>The limb is placed in the appropriate position: extend the arms (palms down) 90 degrees (if sitting) or 45 degrees (if supine). Drift is scored if the arm falls before 10 seconds. The aphasic patient is encouraged using urgency in the voice and pantomime, but not noxious stimulation. Each limb is tested in turn, beginning with the non-paretic arm. Only in the case of amputation or joint fusion at the shoulder, the examiner should record the score as untestable (UN), and clearly write the explanation for this choice.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_arm_right" data-value="0">
                                0: No drift
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_arm_right" data-value="1">
                                1: Drift
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_arm_right" data-value="2">
                                2: Some effort against gravity
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_arm_right" data-value="3">
                                3: No effort against gravity
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_arm_right" data-value="4">
                                4: No movement
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_arm_right" data-value="UN">
                                UN: Amputation/joint fusion
                            </button>
                        </div>
                        <input type="hidden" name="motor_arm_right" id="motor_arm_right" value="">
                    </div>
                </div>
                
                <!-- 8a. Motor Leg - Left -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">8a. Motor Leg - Left</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>The limb is placed in the appropriate position: hold the leg at 30 degrees (always tested supine). Drift is scored if the leg falls before 5 seconds. The aphasic patient is encouraged using urgency in the voice and pantomime, but not noxious stimulation. Each limb is tested in turn, beginning with the non-paretic leg. Only in the case of amputation or joint fusion at the hip, the examiner should record the score as untestable (UN), and clearly write the explanation for this choice.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_leg_left" data-value="0">
                                0: No drift
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_leg_left" data-value="1">
                                1: Drift
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_leg_left" data-value="2">
                                2: Some effort against gravity
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_leg_left" data-value="3">
                                3: No effort against gravity
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_leg_left" data-value="4">
                                4: No movement
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_leg_left" data-value="UN">
                                UN: Amputation/joint fusion
                            </button>
                        </div>
                        <input type="hidden" name="motor_leg_left" id="motor_leg_left" value="">
                    </div>
                </div>
                
                <!-- 8b. Motor Leg - Right -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">8b. Motor Leg - Right</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>The limb is placed in the appropriate position: hold the leg at 30 degrees (always tested supine). Drift is scored if the leg falls before 5 seconds. The aphasic patient is encouraged using urgency in the voice and pantomime, but not noxious stimulation. Each limb is tested in turn, beginning with the non-paretic leg. Only in the case of amputation or joint fusion at the hip, the examiner should record the score as untestable (UN), and clearly write the explanation for this choice.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_leg_right" data-value="0">
                                0: No drift
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_leg_right" data-value="1">
                                1: Drift
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_leg_right" data-value="2">
                                2: Some effort against gravity
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_leg_right" data-value="3">
                                3: No effort against gravity
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_leg_right" data-value="4">
                                4: No movement
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="motor_leg_right" data-value="UN">
                                UN: Amputation/joint fusion
                            </button>
                        </div>
                        <input type="hidden" name="motor_leg_right" id="motor_leg_right" value="">
                    </div>
                </div>
                
                <!-- 9. Limb Ataxia -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">9. Limb Ataxia</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>This item is aimed at finding evidence of a unilateral cerebellar lesion. Test with eyes open. In case of visual defect, ensure testing is done in intact visual field. The finger-nose-finger and heel-shin tests are performed on both sides, and ataxia is scored only if present out of proportion to weakness. Ataxia is absent in the patient who cannot understand or is paralyzed. Only in the case of amputation or joint fusion, the examiner should record the score as untestable (UN), and clearly write the explanation for this choice.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="limb_ataxia" data-value="0">
                                0: Absent
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="limb_ataxia" data-value="1">
                                1: Present in one limb
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="limb_ataxia" data-value="2">
                                2: Present in two limbs
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="limb_ataxia" data-value="UN">
                                UN: Amputation/joint fusion
                            </button>
                        </div>
                        <input type="hidden" name="limb_ataxia" id="limb_ataxia" value="">
                    </div>
                </div>
                
                <!-- 10. Sensory -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">10. Sensory</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>Sensation or grimace to pinprick when tested, or withdrawal from noxious stimulus in the obtunded or aphasic patient. Only sensory loss attributed to stroke is scored as abnormal and the examiner should test as many body areas (arms [not hands], legs, trunk, face) as needed to accurately check for hemisensory loss. A score of 2, 'severe or total sensory loss,' should only be given when a severe or total loss of sensation can be clearly demonstrated. Stuporous and aphasic patients will, therefore, probably score 1 or 0.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="sensory" data-value="0">
                                0: Normal
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="sensory" data-value="1">
                                1: Mild to moderate loss
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="sensory" data-value="2">
                                2: Severe to total loss
                            </button>
                        </div>
                        <input type="hidden" name="sensory" id="sensory" value="">
                    </div>
                </div>
                
                <!-- 11. Best Language -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">11. Best Language</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>A great deal of information about comprehension will be obtained during the preceding sections of the examination. For this scale item, the patient is asked to describe what is happening in the attached picture, to name the items on the attached naming sheet and to read from the attached list of sentences. Comprehension is judged from responses here, as well as to all of the commands in the preceding general neurological exam. If visual loss interferes with the tests, ask the patient to identify objects placed in the hand, repeat, and produce speech. The intubated patient should be asked to write. The patient in a coma (item 1a=3) will automatically score 3 on this item. The examiner must choose a score for the patient with stupor or limited cooperation, but a score of 3 should be used only if the patient is mute and follows no one-step commands.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="best_language" data-value="0">
                                0: No aphasia
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="best_language" data-value="1">
                                1: Mild to moderate aphasia
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="best_language" data-value="2">
                                2: Severe aphasia
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="best_language" data-value="3">
                                3: Mute, global aphasia
                            </button>
                        </div>
                        <input type="hidden" name="best_language" id="best_language" value="">
                    </div>
                </div>
                
                <!-- 12. Dysarthria -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">12. Dysarthria</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>If patient is thought to be normal, an adequate sample of speech must be obtained by asking patient to read or repeat words from the attached list. If the patient has severe aphasia, the clarity of articulation of spontaneous speech can be rated. Only if the patient is intubated or has other physical barriers to producing speech, the examiner should record the score as untestable (UN), and clearly write an explanation for this choice. Do not tell the patient why he or she is being tested.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="dysarthria" data-value="0">
                                0: Normal
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="dysarthria" data-value="1">
                                1: Mild to moderate
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="dysarthria" data-value="2">
                                2: Severe
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="dysarthria" data-value="UN">
                                UN: Intubated/other barrier
                            </button>
                        </div>
                        <input type="hidden" name="dysarthria" id="dysarthria" value="">
                    </div>
                </div>
                
                <!-- 13. Extinction and Inattention -->
                <div class="card mb-4">
                    <div class="card-header section-header-note">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h5 mb-0">13. Extinction and Inattention</h2>
                            <button type="button" class="info-btn" data-info="<strong>Assessment Instructions:</strong><br>Sufficient information to identify neglect may be obtained during the prior testing. If the patient has a severe visual loss preventing visual double simultaneous stimulation, and the cutaneous stimuli are normal, the score is normal. If the patient has aphasia but does appear to attend to both sides, the score is normal. The presence of visual spatial neglect or anosognosia may also be taken as evidence of abnormality. Since the abnormality is scored only if present, the item is never untestable.">
                                <i class="ti ti-info-circle"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2 justify-content-center">
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="extinction" data-value="0">
                                0: No abnormality
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="extinction" data-value="1">
                                1: Inattention/extinction to one side
                            </button>
                            <button type="button" class="btn btn-outline-primary score-btn" data-question="extinction" data-value="2">
                                2: Severe hemi-inattention/extinction
                            </button>
                        </div>
                        <input type="hidden" name="extinction" id="extinction" value="">
                    </div>
                </div>
                
                <div class="text-center mt-4 mb-5">
                    <button type="button" id="calculate-score" class="btn btn-lg btn-primary">
                        <i class="ti ti-calculator me-2"></i>Calculate NIHSS Score
                    </button>
                    <button type="button" id="reset-form" class="btn btn-lg btn-outline-secondary ms-2">
                        <i class="ti ti-refresh me-2"></i>Reset Form
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Window for Detailed Information -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Assessment Instructions</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="modalInfo">Detailed instructions will appear here.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for the NIHSS calculator */
.score-btn {
    min-width: 150px;
    margin-bottom: 5px;
    text-align: left;
    border-width: 2px;
}

.score-btn.selected {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
    font-weight: 500;
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
    flex-shrink: 0;
}

.info-btn:hover {
    transform: scale(1.1);
    background-color: var(--secondary-color);
}

/* Severity color coding */
.severity-none {
    color: #28a745;
}

.severity-minor {
    color: #17a2b8;
}

.severity-moderate {
    color: #fd7e14;
}

.severity-moderate-severe {
    color: #dc3545;
}

.severity-severe {
    color: #721c24;
}

/* Score circle color coding */
.score-low {
    background-color: #28a745 !important;
}

.score-mild {
    background-color: #17a2b8 !important;
}

.score-moderate {
    background-color: #fd7e14 !important;
}

.score-high {
    background-color: #dc3545 !important;
}

.score-severe {
    background-color: #721c24 !important;
}

/* Print-specific styles */
@media print {
    .info-btn, #calculate-score, #reset-form, .navbar, footer {
        display: none !important;
    }
    
    .card {
        break-inside: avoid;
        border: 1px solid #ddd !important;
        margin-bottom: 10px !important;
    }
    
    .card-header {
        background-color: #f1f1f1 !important;
        color: #000 !important;
    }
    
    .score-btn {
        display: none !important;
    }
    
    .score-btn.selected {
        display: block !important;
        background-color: #f1f1f1 !important;
        color: #000 !important;
        border: 1px solid #ddd !important;
    }
    
    .container {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Scoring system reference
    const nihssFields = [
        'loc', 'loc_questions', 'loc_commands', 'best_gaze', 'visual', 
        'facial_palsy', 'motor_arm_left', 'motor_arm_right', 'motor_leg_left', 
        'motor_leg_right', 'limb_ataxia', 'sensory', 'best_language', 
        'dysarthria', 'extinction'
    ];
    
    // Handle score button selection
    const scoreButtons = document.querySelectorAll('.score-btn');
    scoreButtons.forEach(button => {
        button.addEventListener('click', function() {
            const question = this.getAttribute('data-question');
            const value = this.getAttribute('data-value');
            
            // Update the hidden input
            document.getElementById(question).value = value;
            
            // Update button styling
            document.querySelectorAll(`.score-btn[data-question="${question}"]`).forEach(btn => {
                btn.classList.remove('selected');
            });
            this.classList.add('selected');
            
            // Check if all fields are completed
            checkCompletion();
        });
    });
    
    // Show modal when info button is clicked
    const infoButtons = document.querySelectorAll('.info-btn');
    infoButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent any default behavior
            
            const infoHtml = this.getAttribute('data-info');
            
            // Set modal content
            document.getElementById('modalInfo').innerHTML = infoHtml;
            
            // Show modal using Bootstrap's API
            const modal = new bootstrap.Modal(document.getElementById('infoModal'));
            modal.show();
        });
    });
    
    // Calculate NIHSS score
    document.getElementById('calculate-score').addEventListener('click', function() {
        calculateScore();
    });
    
    // Reset form
    document.getElementById('reset-form').addEventListener('click', function() {
        resetForm();
    });
    
    // Check if all fields are completed
    function checkCompletion() {
        let allCompleted = true;
        
        nihssFields.forEach(field => {
            const value = document.getElementById(field).value;
            if (value === '') {
                allCompleted = false;
            }
        });
        
        if (allCompleted) {
            calculateScore();
        }
    }
    
    // Calculate the total NIHSS score
    function calculateScore() {
        let totalScore = 0;
        let missingFields = [];
        let untestableFields = 0;
        let maxPossibleScore = 42;
        
        nihssFields.forEach(field => {
            const value = document.getElementById(field).value;
            
            if (value === '') {
                missingFields.push(field);
            } else if (value === 'UN') {
                untestableFields++;
                // Reduce maximum possible score based on which field is untestable
                if (field === 'motor_arm_left' || field === 'motor_arm_right') {
                    maxPossibleScore -= 4;
                } else if (field === 'motor_leg_left' || field === 'motor_leg_right') {
                    maxPossibleScore -= 4;
                } else if (field === 'limb_ataxia') {
                    maxPossibleScore -= 2;
                } else if (field === 'dysarthria') {
                    maxPossibleScore -= 2;
                }
            } else {
                totalScore += parseInt(value);
            }
        });
        
        if (missingFields.length > 0) {
            // Alert user about missing fields
            const missingFieldNames = missingFields.map(field => {
                return field.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            });
            
            alert(`Please complete the following fields: ${missingFieldNames.join(', ')}`);
            return;
        }
        
        // Calculate severity based on total score
        let severity = '';
        let severityClass = '';
        let scoreCircleClass = '';
        let interpretation = '';
        
        if (totalScore === 0) {
            severity = 'No Stroke Symptoms';
            severityClass = 'severity-none';
            scoreCircleClass = 'score-low';
            interpretation = 'Patient shows no measurable stroke deficit.';
        } else if (totalScore >= 1 && totalScore <= 4) {
            severity = 'Minor Stroke';
            severityClass = 'severity-minor';
            scoreCircleClass = 'score-mild';
            interpretation = 'Patient has mild stroke symptoms.';
        } else if (totalScore >= 5 && totalScore <= 15) {
            severity = 'Moderate Stroke';
            severityClass = 'severity-moderate';
            scoreCircleClass = 'score-moderate';
            interpretation = 'Patient has moderate stroke severity.';
        } else if (totalScore >= 16 && totalScore <= 20) {
            severity = 'Moderate to Severe Stroke';
            severityClass = 'severity-moderate-severe';
            scoreCircleClass = 'score-high';
            interpretation = 'Patient has moderate to severe stroke.';
        } else {
            severity = 'Severe Stroke';
            severityClass = 'severity-severe';
            scoreCircleClass = 'score-severe';
            interpretation = 'Patient has severe stroke with significant deficits.';
        }
        
        // Update results
        document.getElementById('total-score').textContent = totalScore;
        document.getElementById('score-circle').className = document.getElementById('score-circle').className.replace(/score-\w+/g, '') + ' ' + scoreCircleClass;
        document.getElementById('severity-text').textContent = severity;
        document.getElementById('severity-text').className = document.getElementById('severity-text').className.replace(/severity-\w+(-\w+)?/g, '') + ' ' + severityClass;
        document.getElementById('score-interpretation').textContent = interpretation;
        
        // Show results
        document.getElementById('results-container').classList.remove('d-none');
        
        // Scroll to results
        document.getElementById('results-container').scrollIntoView({ behavior: 'smooth' });
    }
    
    // Reset the form
    function resetForm() {
        // Hide results
        document.getElementById('results-container').classList.add('d-none');
        
        // Reset hidden inputs
        nihssFields.forEach(field => {
            document.getElementById(field).value = '';
        });
        
        // Reset button styling
        document.querySelectorAll('.score-btn').forEach(btn => {
            btn.classList.remove('selected');
        });
    }
    
    // Initialize from local storage if available
    function loadFromLocalStorage() {
        const savedData = localStorage.getItem('nihssCalculator');
        if (savedData) {
            const data = JSON.parse(savedData);
            
            // Set values and button styles
            nihssFields.forEach(field => {
                if (data[field]) {
                    document.getElementById(field).value = data[field];
                    
                    // Find and select the button
                    const button = document.querySelector(`.score-btn[data-question="${field}"][data-value="${data[field]}"]`);
                    if (button) {
                        button.classList.add('selected');
                    }
                }
            });
            
            // Check if all fields are completed to show results
            checkCompletion();
        }
    }
    
    // Save to local storage
    function saveToLocalStorage() {
        const data = {};
        
        nihssFields.forEach(field => {
            data[field] = document.getElementById(field).value;
        });
        
        localStorage.setItem('nihssCalculator', JSON.stringify(data));
    }
    
    // Add event listeners for local storage
    scoreButtons.forEach(button => {
        button.addEventListener('click', saveToLocalStorage);
    });
    
    // Load saved data
    loadFromLocalStorage();
});
</script>

<?php
// Include footer
include '../includes/frontend_footer.php';
?>