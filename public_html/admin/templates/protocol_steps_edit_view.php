<?php
// admin/templates/protocol_steps_edit_view.php
// Expects $protocol, $steps, $page_title, $csrf_token passed from protocol_steps.php
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo htmlspecialchars($page_title); ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" id="save-steps-btn" class="btn btn-success me-2">
            <i class="bi bi-save-fill me-1"></i> Save All Steps
        </button>
         <a href="protocols.php?action=list" class="btn btn-sm btn-outline-secondary me-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Protocol List
        </a>
        <a href="protocols.php?action=edit&id=<?php echo $protocol['protocol_id']; ?>" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-pencil me-1"></i> Edit Protocol Info
        </a>
    </div>
</div>

<div class="alert alert-info d-flex align-items-center" role="alert">
  <i class="bi bi-info-circle-fill flex-shrink-0 me-2"></i>
  <div>
    Use the "Step Order" field (in the <i class="bi bi-gear-fill"></i> settings) to define the sequence of steps within each level or branch (lower numbers appear first). Use the "+ Add Step" buttons to create steps in the desired location. Remember to click "Save All Steps".
  </div>
</div>

<div class="row">
    <div class="col-12">
        <button type="button" id="add-root-step-btn" class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle-fill me-1"></i> Add New Top-Level Step
        </button>

        <div id="protocol-steps-editor" class="protocol-steps-editor-container border rounded">
            <?php /* Steps will be rendered here by JavaScript, now with indentation */ ?>
            <div class="p-3"> <?php /* Add padding for content */ ?>
                <p class="text-muted text-center mb-0" id="loading-placeholder">Loading Steps...</p>
            </div>
        </div>

        <!-- Hidden container for initial step data -->
        <div id="initial-steps-data" data-steps="<?php echo htmlspecialchars(json_encode($steps, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_INVALID_UTF8_SUBSTITUTE), ENT_QUOTES, 'UTF-8'); ?>" style="display: none;"></div>

    </div>
</div>

<!-- Step Template (Hidden, used by JavaScript to clone new steps) -->
<div id="step-template" style="display: none;">
     <div class="protocol-step-item card mb-3 border" data-step-id="NEW_STEP_ID" data-parent-id="" data-condition="" style="margin-left: 0px;"> <?php /* Indentation will be added dynamically */?>
         <div class="card-header step-header d-flex justify-content-between align-items-center p-2">
            <div>
                <?php /* Removed drag handle */ ?>
                <span class="step-icon-display me-1"><i class="bi-archive-fill"></i></span>
                <input type="text" class="form-control-sm step-title-input d-inline-block w-auto" placeholder="Step Title (Optional)" name="steps[NEW_STEP_ID][title]" value="">
             </div>
             <div>
                <button type="button" class="btn btn-sm btn-outline-secondary step-settings-toggle me-1" title="Configure Step"><i class="bi bi-gear-fill"></i></button>
                <button type="button" class="btn btn-sm btn-danger step-delete-btn" title="Delete Step"><i class="bi bi-trash-fill"></i></button>
            </div>
         </div>
         <div class="card-body step-content-area p-2">
             <textarea class="step-content-editor" name="steps[NEW_STEP_ID][content]"></textarea> <!-- TinyMCE will target this -->
             <div class="step-modal-trigger-display mt-2">
                <?php /* Placeholder for modal trigger button preview */ ?>
             </div>
             <div class="step-children-container ps-3 mt-2"> <?php /* Simple container, no sortable class */ ?>
                <?php /* Nested steps/branches go here */ ?>
             </div>
             <div class="step-add-child-area mt-2"> <?php /* Removed margin specific to drag handle */ ?>
                 <button type="button" class="btn btn-sm btn-outline-primary step-add-child-btn">Add Nested Step</button>
                 <?php /* Buttons for adding YES/NO branches will appear here conditionally */ ?>
             </div>
         </div>
         <div class="card-footer step-settings-panel bg-light border-top p-3" style="display: none;">
             <h6>Step Configuration</h6>
             <div class="row g-3">
                  <div class="col-md-3">
                     <label class="form-label">Step Order:</label>
                     <input type="number" class="form-control form-control-sm step-order-input" name="steps[NEW_STEP_ID][step_order]" value="0" min="0" title="Order within this level/branch (lower numbers first)">
                 </div>
                 <div class="col-md-3">
                     <label class="form-label">Step Type:</label>
                     <select class="form-select form-select-sm step-type-select" name="steps[NEW_STEP_ID][step_type]">
                        <option value="INFO">Info / General</option>
                        <option value="ACTION">Action / Treatment</option>
                        <option value="ENTRY">Entry Point / Presentation</option>
                        <option value="QUESTION_YES_NO">Question (Yes/No Branch)</option>
                        <option value="OUTCOME">Outcome / Destination</option>
                        <option value="REFERENCE">Reference Link</option>
                     </select>
                 </div>
                  <div class="col-md-3">
                     <label class="form-label">Color:</label>
                     <select class="form-select form-select-sm step-color-select" name="steps[NEW_STEP_ID][step_color]">
                         <option value="default">Default</option>
                         <option value="blue">Blue</option>
                         <option value="green">Green</option>
                         <option value="yellow">Yellow</option>
                         <option value="red">Red</option>
                         <option value="gray">Gray</option>
                     </select>
                 </div>
                 <div class="col-md-3">
                     <label class="form-label">Icon Class:</label>
                     <input type="text" class="form-control form-control-sm step-icon-input" placeholder="e.g., bi-info-circle-fill" name="steps[NEW_STEP_ID][icon_class]" value="bi-archive-fill">
                     <div class="form-text small"><a href="https://icons.getbootstrap.com/" target="_blank">Icon reference</a></div>
                 </div>
                 <div class="col-12">
                     <label class="form-label">Provider Levels:</label>
                     <div class="d-flex flex-wrap gap-2">
                         <?php
                         $levels = ['emr' => 'EMR', 'emt' => 'EMT', 'emtiv' => 'EMT-IV', 'aemt' => 'AEMT', 'intermediate' => 'Intermediate', 'paramedic' => 'Paramedic'];
                         foreach ($levels as $key => $label): ?>
                         <div class="form-check form-check-inline">
                            <input class="form-check-input step-provider-level" type="checkbox" id="level_<?php echo $key; ?>_NEW_STEP_ID" name="steps[NEW_STEP_ID][provider_level_<?php echo $key; ?>]" value="1">
                            <label class="form-check-label small" for="level_<?php echo $key; ?>_NEW_STEP_ID"><?php echo $label; ?></label>
                         </div>
                         <?php endforeach; ?>
                     </div>
                 </div>
                 <div class="col-12">
                    <hr>
                     <h6>Pop-up Modal (Optional)</h6>
                     <div class="form-check mb-2">
                        <input class="form-check-input step-modal-enable" type="checkbox" id="modal_enable_NEW_STEP_ID" name="steps[NEW_STEP_ID][modal_enabled]" value="1">
                        <label class="form-check-label" for="modal_enable_NEW_STEP_ID">Enable Pop-up Modal for this step?</label>
                     </div>
                     <div class="step-modal-fields" style="display: none;">
                         <div class="mb-2">
                            <label class="form-label small">Modal Trigger Button Text:</label>
                            <input type="text" class="form-control form-control-sm step-modal-trigger" placeholder="e.g., Consider Causes" name="steps[NEW_STEP_ID][modal_trigger_text]">
                         </div>
                         <div class="mb-2">
                            <label class="form-label small">Modal Window Title:</label>
                            <input type="text" class="form-control form-control-sm step-modal-title" placeholder="e.g., Differential Diagnosis" name="steps[NEW_STEP_ID][modal_title]">
                         </div>
                         <div class="mb-2">
                             <label class="form-label small">Modal Content:</label>
                             <textarea class="form-control form-control-sm step-modal-content" rows="5" name="steps[NEW_STEP_ID][modal_content]"></textarea>
                         </div>
                     </div>
                 </div>
                 <div class="col-12">
                    <hr>
                     <label class="form-label">Advanced (Read Only Info):</label>
                     <div class="small text-muted">
                        Step ID: <code class="step-debug-id">NEW_STEP_ID</code> | Parent ID: <code class="step-debug-parent-id">NULL</code> | Condition: <code class="step-debug-condition">N/A</code>
                     </div>
                 </div>
             </div>
         </div>
     </div>
</div>

<?php /* Removed SortableJS include */ ?>
<!-- Include TinyMCE via CDN -->
<script src="https://cdn.tiny.cloud/1/-/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> <?php /* Replace with your actual API key */ ?>


<script>
    // --- CORE JAVASCRIPT FOR EDITOR (Numerical Order Version) ---
    document.addEventListener('DOMContentLoaded', () => {
        const protocolId = <?php echo json_encode($protocol['protocol_id']); ?>;
        const csrfToken = <?php echo json_encode($csrf_token); ?>;
        const saveUrl = 'ajax_save_steps.php';

        // --- Get Element References with Logging ---
        const editorContainer = document.getElementById('protocol-steps-editor');
        console.log('Element check - editorContainer:', editorContainer ? 'FOUND' : 'NULL');

        const stepTemplateElement = document.getElementById('step-template'); // Get element first
        console.log('Element check - stepTemplateElement:', stepTemplateElement ? 'FOUND' : 'NULL');
        const stepTemplateHtml = stepTemplateElement ? stepTemplateElement.innerHTML : null; // Get innerHTML only if element exists
        if (!stepTemplateHtml) console.error('Failed to get stepTemplateHtml!');

        const loadingPlaceholder = document.getElementById('loading-placeholder');
        console.log('Element check - loadingPlaceholder:', loadingPlaceholder ? 'FOUND' : 'NULL');

        const addRootStepBtn = document.getElementById('add-root-step-btn');
        console.log('Element check - addRootStepBtn:', addRootStepBtn ? 'FOUND' : 'NULL');

        const saveStepsBtn = document.getElementById('save-steps-btn');
        console.log('Element check - saveStepsBtn:', saveStepsBtn ? 'FOUND' : 'NULL');

        const stepsDataElement = document.getElementById('initial-steps-data'); // Moved check here
        console.log('Element check - stepsDataElement:', stepsDataElement ? 'FOUND' : 'NULL');
        // --- End Get Element References ---


        let newStepCounter = 0;
        let tinymceInstances = {};

        // --- Get initial steps data from the data attribute ---
        let initialStepsData = [];
        // Use the already fetched stepsDataElement
        if (stepsDataElement && stepsDataElement.dataset.steps) {
            try {
                initialStepsData = JSON.parse(stepsDataElement.dataset.steps);
                if (!Array.isArray(initialStepsData)) {
                    console.warn("Parsed steps data is not an array, defaulting to empty.");
                    initialStepsData = [];
                }
            } catch (e) {
                console.error("Failed to parse initial steps data from data attribute:", e);
                if(loadingPlaceholder) loadingPlaceholder.textContent = 'Error: Could not load initial step data. Invalid format.';
                // Don't return yet, check if essential elements exist first
                // return;
            }
        } else {
             console.warn("Initial steps data container or data attribute not found.");
             // If container exists but data is missing, still might proceed if other elements are okay
        }
        // --- End Data Retrieval ---

        console.log("Step Editor Initializing (Numerical Order)...");
        // console.log("Protocol ID:", protocolId); // Already logged indirectly
        // console.log("Initial Steps Data (Parsed):", initialStepsData);


        // --- Check if Essential Elements Were Found ---
        if (!editorContainer || !stepTemplateHtml || !addRootStepBtn || !saveStepsBtn || !stepsDataElement ) {
             console.error("One or more essential editor HTML elements were not found! Check IDs in HTML and JS.");
             if(loadingPlaceholder) loadingPlaceholder.textContent = 'Error: Essential Editor UI components missing. Cannot initialize.';
             return; // Stop initialization if critical elements are missing
         }
        // --- End Essential Elements Check ---


        if(loadingPlaceholder) loadingPlaceholder.style.display = 'none'; // Hide loading placeholder only if found


        // --- TinyMCE Initialization ---
        const initTinyMCE = (textareaId) => {
             if (tinymce.get(textareaId)) {
                 tinymce.get(textareaId).remove();
             }
             delete tinymceInstances[textareaId];

             tinymce.init({
                selector: `#${textareaId}`,
                height: 150, menubar: false,
                plugins: 'lists link autolink autoresize wordcount',
                toolbar: 'undo redo | bold italic | bullist numlist | link',
                autoresize_bottom_margin: 15,
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
                setup: function(editor) { tinymceInstances[textareaId] = editor; }
            });
        };

        // --- Step Rendering ---
        const renderSingleStepAndChildren = (stepData, parentElement, allStepsMap, depth = 0) => {
            const stepId = stepData.step_id || `new_${++newStepCounter}`;
            stepData.step_id = stepId;

            let stepHtml = stepTemplateHtml.replace(/NEW_STEP_ID/g, stepId);
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = stepHtml;
            const stepElement = tempDiv.firstElementChild;
            if (!stepElement) { console.error("Failed create element ID:", stepId); return; }

            stepElement.dataset.stepId = stepId;
            stepElement.dataset.parentId = stepData.parent_step_id || '';
            stepElement.dataset.condition = stepData.condition_from_parent || '';
            stepElement.style.marginLeft = `${depth * 30}px`;

            stepElement.querySelector('.step-order-input').value = stepData.step_order ?? 0;
            stepElement.querySelector('.step-title-input').value = stepData.title || '';
            stepElement.querySelector('.step-type-select').value = stepData.step_type || 'INFO';
            stepElement.querySelector('.step-color-select').value = stepData.step_color || 'default';
            stepElement.querySelector('.step-icon-input').value = stepData.icon_class || 'bi-archive-fill';
            stepElement.querySelector('.step-icon-display i').className = stepData.icon_class || 'bi-archive-fill';

            const levels = ['emr', 'emt', 'emtiv', 'aemt', 'intermediate', 'paramedic'];
            levels.forEach(level => {
                const checkbox = stepElement.querySelector(`#level_${level}_${stepId}`);
                if (checkbox) checkbox.checked = !!stepData[`provider_level_${level}`];
            });

            const modalEnableCheckbox = stepElement.querySelector('.step-modal-enable');
            const modalFieldsContainer = stepElement.querySelector('.step-modal-fields');
            const modalTriggerInput = stepElement.querySelector('.step-modal-trigger');
            const modalTitleInput = stepElement.querySelector('.step-modal-title');
            const modalContentTextarea = stepElement.querySelector('.step-modal-content');
            const modalTriggerDisplay = stepElement.querySelector('.step-modal-trigger-display');
            const hasModal = !!stepData.modal_trigger_text || !!stepData.modal_title || !!stepData.modal_content;
            modalEnableCheckbox.checked = hasModal;
            modalFieldsContainer.style.display = hasModal ? 'block' : 'none';
            modalTriggerInput.value = stepData.modal_trigger_text || '';
            modalTitleInput.value = stepData.modal_title || '';
            modalContentTextarea.value = stepData.modal_content || '';
            if (hasModal && stepData.modal_trigger_text) {
                modalTriggerDisplay.innerHTML = `<button type="button" class="btn btn-sm btn-outline-info mt-2" disabled><i class="bi bi-info-circle me-1"></i> ${escapeHtml(stepData.modal_trigger_text)}</button>`;
            } else if (hasModal) {
                 modalTriggerDisplay.innerHTML = `<button type="button" class="btn btn-sm btn-outline-secondary mt-2" disabled><i class="bi bi-info-circle me-1"></i> (Modal Enabled)</button>`;
            } else { modalTriggerDisplay.innerHTML = ''; }

             const settingsPanel = stepElement.querySelector('.step-settings-panel');
             if (settingsPanel) {
                 settingsPanel.querySelector('.step-debug-id').textContent = stepId;
                 settingsPanel.querySelector('.step-debug-parent-id').textContent = stepData.parent_step_id || 'NULL';
                 settingsPanel.querySelector('.step-debug-condition').textContent = stepData.condition_from_parent || 'N/A';
             }

            parentElement.appendChild(stepElement);

            const contentTextarea = stepElement.querySelector('.step-content-editor');
            const contentTextareaId = `content_editor_${stepId}`;
            contentTextarea.id = contentTextareaId;
            contentTextarea.value = stepData.content || '';
            initTinyMCE(contentTextareaId);

             const childrenContainer = stepElement.querySelector('.step-children-container');
             const addChildArea = stepElement.querySelector('.step-add-child-area');

             if (stepData.step_type === 'QUESTION_YES_NO') {
                 addChildArea.innerHTML = '';
                 const branchesHtml = `
                     <div class="row step-conditional-branches mt-2">
                         <div class="col-md-6 branch-yes mb-2">
                             <div class="branch-label text-success border-success"><i class="bi bi-check-circle-fill"></i> YES Branch</div>
                             <div class="branch-container border border-success rounded p-2" data-condition="YES"></div>
                             <button type="button" class="btn btn-sm btn-outline-success mt-2 step-add-branch-btn" data-condition="YES">Add Step to YES</button>
                         </div>
                         <div class="col-md-6 branch-no mb-2">
                             <div class="branch-label text-danger border-danger"><i class="bi bi-x-circle-fill"></i> NO Branch</div>
                             <div class="branch-container border border-danger rounded p-2" data-condition="NO"></div>
                             <button type="button" class="btn btn-sm btn-outline-danger mt-2 step-add-branch-btn" data-condition="NO">Add Step to NO</button>
                         </div>
                     </div>`;
                 childrenContainer.insertAdjacentHTML('afterend', branchesHtml);

                 const yesChildren = allStepsMap.get(stepId)?.filter(s => s.condition_from_parent === 'YES') || [];
                 const yesContainer = stepElement.querySelector('.branch-container[data-condition="YES"]');
                 yesChildren.sort((a, b) => (a.step_order ?? 0) - (b.step_order ?? 0)).forEach(child => renderSingleStepAndChildren(child, yesContainer, allStepsMap, depth + 1));

                 const noChildren = allStepsMap.get(stepId)?.filter(s => s.condition_from_parent === 'NO') || [];
                 const noContainer = stepElement.querySelector('.branch-container[data-condition="NO"]');
                 noChildren.sort((a, b) => (a.step_order ?? 0) - (b.step_order ?? 0)).forEach(child => renderSingleStepAndChildren(child, noContainer, allStepsMap, depth + 1));

             } else {
                 const children = allStepsMap.get(stepId)?.filter(s => !s.condition_from_parent) || [];
                 children.sort((a, b) => (a.step_order ?? 0) - (b.step_order ?? 0)).forEach(child => renderSingleStepAndChildren(child, childrenContainer, allStepsMap, depth + 1));
             }
        };

        // Main function to build the entire editor UI
        const buildEditorUI = (stepsData) => {
            const actualEditorTarget = editorContainer.querySelector('.p-3') || editorContainer;
            actualEditorTarget.innerHTML = '';

            const stepsMap = new Map();
            stepsData.forEach(step => {
                const parentKey = step.parent_step_id === null ? 'root' : step.parent_step_id;
                if (!stepsMap.has(parentKey)) stepsMap.set(parentKey, []);
                stepsMap.get(parentKey).push(step);
            });

            const rootSteps = stepsMap.get('root') || [];
             if (rootSteps.length === 0 && stepsData.length === 0) {
                 actualEditorTarget.innerHTML = '<p class="text-muted text-center mb-0">No steps defined yet. Click "Add New Top-Level Step" to begin.</p>';
             } else {
                 rootSteps.sort((a, b) => (a.step_order ?? 0) - (b.step_order ?? 0)).forEach(step => renderSingleStepAndChildren(step, actualEditorTarget, stepsMap, 0));
             }
             // console.log("Initial rendering complete (Numerical Order).");
        };


         // Initial build
         buildEditorUI(initialStepsData);


        // --- Function to collect data from DOM ---
        const collectStepDataFromDOM = (containerElement) => {
            let steps = [];
            const stepElements = containerElement.querySelectorAll(':scope > .protocol-step-item');

            stepElements.forEach((stepElement) => {
                const stepId = stepElement.dataset.stepId;
                // IMPORTANT: Determine parent based on the *actual parent element* in the DOM structure
                const parentList = stepElement.parentElement;
                let parentId = null;
                let condition = null;

                 if (parentList.matches('.step-children-container')) {
                     parentId = parentList.closest('.protocol-step-item')?.dataset.stepId;
                 } else if (parentList.matches('.branch-container')) {
                     parentId = parentList.closest('.protocol-step-item')?.dataset.stepId;
                     condition = parentList.dataset.condition;
                 } // else it's a root step, parentId remains null

                const stepData = {
                    step_id: stepId.startsWith('new_') ? null : stepId,
                    temp_id: stepId.startsWith('new_') ? stepId : null,
                    protocol_id: protocolId,
                    parent_step_id: parentId, // Use parent ID determined from DOM
                    condition_from_parent: condition, // Use condition determined from DOM
                    step_order: parseInt(stepElement.querySelector('.step-order-input')?.value ?? '0', 10),
                    step_type: stepElement.querySelector('.step-type-select')?.value ?? 'INFO',
                    title: stepElement.querySelector('.step-title-input')?.value ?? '',
                    content: tinymce.get(`content_editor_${stepId}`)?.getContent({format: 'raw'}) ?? '',
                    step_color: stepElement.querySelector('.step-color-select')?.value ?? 'default',
                    icon_class: stepElement.querySelector('.step-icon-input')?.value || null,
                    provider_level_emr: stepElement.querySelector(`#level_emr_${stepId}`)?.checked ? 1 : 0,
                    provider_level_emt: stepElement.querySelector(`#level_emt_${stepId}`)?.checked ? 1 : 0,
                    provider_level_emtiv: stepElement.querySelector(`#level_emtiv_${stepId}`)?.checked ? 1 : 0,
                    provider_level_aemt: stepElement.querySelector(`#level_aemt_${stepId}`)?.checked ? 1 : 0,
                    provider_level_intermediate: stepElement.querySelector(`#level_intermediate_${stepId}`)?.checked ? 1 : 0,
                    provider_level_paramedic: stepElement.querySelector(`#level_paramedic_${stepId}`)?.checked ? 1 : 0,
                    modal_title: null, modal_trigger_text: null, modal_content: null
                };

                if (stepElement.querySelector('.step-modal-enable')?.checked) {
                    stepData.modal_trigger_text = stepElement.querySelector('.step-modal-trigger')?.value || null;
                    stepData.modal_title = stepElement.querySelector('.step-modal-title')?.value || null;
                    stepData.modal_content = stepElement.querySelector('.step-modal-content')?.value || null;
                }

                steps.push(stepData); // Add current step

                // --- Recursively collect children ---
                const childrenContainer = stepElement.querySelector('.step-children-container');
                if (childrenContainer) { steps = steps.concat(collectStepDataFromDOM(childrenContainer)); }
                const yesContainer = stepElement.querySelector('.branch-container[data-condition="YES"]');
                if (yesContainer) { steps = steps.concat(collectStepDataFromDOM(yesContainer)); }
                const noContainer = stepElement.querySelector('.branch-container[data-condition="NO"]');
                if (noContainer) { steps = steps.concat(collectStepDataFromDOM(noContainer)); }
            });
            return steps;
        };


        // --- Event Listeners ---
         // Add Root Step Button
         addRootStepBtn.addEventListener('click', () => {
             const actualEditorTarget = editorContainer.querySelector('.p-3') || editorContainer;
             const placeholderMsg = actualEditorTarget.querySelector('p.text-muted.text-center');
             if (placeholderMsg) placeholderMsg.remove();
             newStepCounter++; const newStepId = `new_${newStepCounter}`;
             const currentRootStepCount = actualEditorTarget.querySelectorAll(':scope > .protocol-step-item').length;
             const newStepData = { step_id: newStepId, protocol_id: protocolId, step_order: currentRootStepCount * 10, parent_step_id: null, condition_from_parent: null, step_type: 'INFO', title: '', content: '', provider_level_emr: 1, provider_level_emt: 1, provider_level_emtiv: 1, provider_level_aemt: 1, provider_level_intermediate: 1, provider_level_paramedic: 1, step_icon: 'bi-archive-fill', step_color: 'default', modal_title: null, modal_trigger_text: null, modal_content: null };
             renderSingleStepAndChildren(newStepData, actualEditorTarget, new Map(), 0);
        });

        // Save Steps Button
        saveStepsBtn.addEventListener('click', () => {
            console.log("Save steps clicked");
            saveStepsBtn.disabled = true;
            saveStepsBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></i> Saving...';
            tinymce.triggerSave();

            // 1. Collect data
            const stepsData = collectStepDataFromDOM(editorContainer.querySelector('.p-3') || editorContainer);
            // console.log("Data to save:", JSON.stringify(stepsData, null, 2)); // Verbose log

            // 2. Prepare payload
            const payload = {
                protocol_id: protocolId,
                steps: stepsData,
                csrf_token: csrfToken // Ensure this variable is correctly defined in the outer scope
            };
            console.log("Payload being sent:", payload); // Check payload in console

            // 3. Send data via fetch
            fetch(saveUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(errData => { throw new Error(errData.message || `HTTP error! Status: ${response.status}`); })
                           .catch(() => { throw new Error(`Request failed with status ${response.status} - ${response.statusText}`); }); // Add statusText
                }
                return response.json();
            })
            .then(data => {
                console.log('Save response:', data);
                if (data.success) {
                    const successAlert = `<div class="alert alert-success alert-dismissible fade show fixed-top m-3" role="alert" style="z-index: 2000;">
                                            ${escapeHtml(data.message || 'Steps saved successfully!')}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                          </div>`;
                    document.body.insertAdjacentHTML('beforeend', successAlert);
                    setTimeout(() => window.location.reload(), 1500); // Reload after showing message
                } else {
                     console.error('Save failed:', data.message);
                     alert(`Error saving steps: ${data.message || 'Unknown server error.'}`);
                     saveStepsBtn.disabled = false; // Re-enable only on error
                     saveStepsBtn.innerHTML = '<i class="bi bi-save-fill me-1"></i> Save All Steps';
                }
            })
            .catch(error => {
                console.error('Error during save request:', error);
                alert(`An error occurred while saving: ${error.message}`);
                saveStepsBtn.disabled = false; // Re-enable on error
                saveStepsBtn.innerHTML = '<i class="bi bi-save-fill me-1"></i> Save All Steps';
            });
        }); // End Save Button listener


        // Event Delegation for dynamic elements (clicks)
        editorContainer.addEventListener('click', (event) => {
             const settingsToggle = event.target.closest('.step-settings-toggle');
             const deleteBtn = event.target.closest('.step-delete-btn');
             const addChildBtn = event.target.closest('.step-add-child-btn');
             const addBranchBtn = event.target.matches('.step-add-branch-btn') ? event.target : null;
             const modalEnableCheck = event.target.matches('.step-modal-enable') ? event.target : null;

             // Toggle Settings Panel
             if (settingsToggle) {
                 const stepItem = settingsToggle.closest('.protocol-step-item');
                 const settingsPanel = stepItem.querySelector('.step-settings-panel');
                 if (settingsPanel) settingsPanel.style.display = settingsPanel.style.display === 'none' ? 'block' : 'none';
             }
            // Delete Step Button
             else if (deleteBtn) {
                 if (confirm('Are you sure you want to delete this step and any steps nested within it?')) {
                     const stepItem = deleteBtn.closest('.protocol-step-item');
                     const editorTextarea = stepItem.querySelector('.step-content-editor');
                     if (editorTextarea && editorTextarea.id && tinymceInstances[editorTextarea.id]) {
                          tinymce.get(editorTextarea.id)?.remove();
                          delete tinymceInstances[editorTextarea.id];
                     }
                     stepItem.remove();
                     console.log("Step deleted from UI");
                 }
             }
            // Add Child/Nested Step Button
            else if (addChildBtn) {
                 const parentStepItem = addChildBtn.closest('.protocol-step-item');
                 const childrenContainer = parentStepItem.querySelector('.step-children-container');
                 const parentStepId = parentStepItem.dataset.stepId;
                 const currentDepth = parseInt(parentStepItem.style.marginLeft || '0px', 10) / 30;
                 newStepCounter++; const newStepId = `new_${newStepCounter}`;
                 const childStepCount = childrenContainer.querySelectorAll(':scope > .protocol-step-item').length;
                 const newStepData = { step_id: newStepId, protocol_id: protocolId, step_order: childStepCount * 10, parent_step_id: parentStepId, condition_from_parent: null, step_type: 'INFO', title: '', content: '', provider_level_emr: 1, provider_level_emt: 1, provider_level_emtiv: 1, provider_level_aemt: 1, provider_level_intermediate: 1, provider_level_paramedic: 1, step_icon: 'bi-archive-fill', step_color: 'default', modal_title: null, modal_trigger_text: null, modal_content: null };
                 renderSingleStepAndChildren(newStepData, childrenContainer, new Map(), currentDepth + 1);
            }
             // Add Step to YES/NO Branch Button
            else if (addBranchBtn) {
                 const parentStepItem = addBranchBtn.closest('.protocol-step-item');
                 const parentStepId = parentStepItem.dataset.stepId;
                 const condition = addBranchBtn.dataset.condition;
                 const branchContainer = parentStepItem.querySelector(`.branch-container[data-condition="${condition}"]`);
                 const currentDepth = parseInt(parentStepItem.style.marginLeft || '0px', 10) / 30;
                 newStepCounter++; const newStepId = `new_${newStepCounter}`;
                 const branchStepCount = branchContainer.querySelectorAll(':scope > .protocol-step-item').length;
                 const newStepData = { step_id: newStepId, protocol_id: protocolId, step_order: branchStepCount * 10, parent_step_id: parentStepId, condition_from_parent: condition, step_type: 'INFO', title: '', content: '', provider_level_emr: 1, provider_level_emt: 1, provider_level_emtiv: 1, provider_level_aemt: 1, provider_level_intermediate: 1, provider_level_paramedic: 1, step_icon: 'bi-archive-fill', step_color: 'default', modal_title: null, modal_trigger_text: null, modal_content: null };
                 renderSingleStepAndChildren(newStepData, branchContainer, new Map(), currentDepth + 1);
             }
             // Enable/Disable Modal Fields
             else if (modalEnableCheck) {
                 const stepItem = modalEnableCheck.closest('.protocol-step-item');
                 const modalFields = stepItem.querySelector('.step-modal-fields');
                 modalFields.style.display = modalEnableCheck.checked ? 'block' : 'none';
                 const triggerInput = stepItem.querySelector('.step-modal-trigger');
                 const triggerDisplay = stepItem.querySelector('.step-modal-trigger-display');
                 if (modalEnableCheck.checked && triggerInput.value) {
                    triggerDisplay.innerHTML = `<button type="button" class="btn btn-sm btn-outline-info mt-2" disabled><i class="bi bi-info-circle me-1"></i> ${escapeHtml(triggerInput.value)}</button>`;
                 } else if (modalEnableCheck.checked) {
                    triggerDisplay.innerHTML = `<button type="button" class="btn btn-sm btn-outline-secondary mt-2" disabled><i class="bi bi-info-circle me-1"></i> (Modal Enabled)</button>`;
                 } else { triggerDisplay.innerHTML = ''; }
             }
        });

        // Event delegation for INPUT changes (modal trigger, icon)
        editorContainer.addEventListener('input', (event) => {
            const modalTrigger = event.target.matches('.step-modal-trigger') ? event.target : null;
            const iconInput = event.target.matches('.step-icon-input') ? event.target : null;

            if (modalTrigger) {
                const stepItem = modalTrigger.closest('.protocol-step-item');
                const triggerDisplay = stepItem.querySelector('.step-modal-trigger-display');
                const enableCheckbox = stepItem.querySelector('.step-modal-enable');
                if (enableCheckbox.checked && modalTrigger.value) {
                     triggerDisplay.innerHTML = `<button type="button" class="btn btn-sm btn-outline-info mt-2" disabled><i class="bi bi-info-circle me-1"></i> ${escapeHtml(modalTrigger.value)}</button>`;
                 } else if (enableCheckbox.checked) {
                     triggerDisplay.innerHTML = `<button type="button" class="btn btn-sm btn-outline-secondary mt-2" disabled><i class="bi bi-info-circle me-1"></i> (Set Button Text)</button>`;
                 } else { triggerDisplay.innerHTML = ''; }
            }
            else if (iconInput) {
                 const stepItem = iconInput.closest('.protocol-step-item');
                 const iconDisplay = stepItem.querySelector('.step-icon-display i');
                  const safeClass = iconInput.value.replace(/[^a-zA-Z0-9\-\s]/g, '');
                 const existingClasses = Array.from(iconDisplay.classList);
                 existingClasses.forEach(cls => { if(cls.startsWith('bi-')) iconDisplay.classList.remove(cls); });
                 if (safeClass.startsWith('bi-')) iconDisplay.classList.add(safeClass);
                 else iconDisplay.classList.add('bi-question-circle');
             }
        });

         // Basic HTML escaping function (Corrected Version)
         function escapeHtml(unsafe) {
            if (typeof unsafe !== 'string') return '';
            return unsafe
                 .replace(/&/g, "&")
                 .replace(/</g, "<")
                 .replace(/>/g, ">")
                 .replace(/"/g, "&quot;")
                 .replace(/'/g, "'");
         }

         console.log("Event listeners attached (Numerical Order).");

    }); // End DOMContentLoaded

</script>

<style>
    /* Basic styling for the editor elements */
    .protocol-steps-editor-container .card-header { background-color: #e9ecef; }
    .step-children-container { /* No special styles needed now */ }
    .branch-container { background-color: rgba(0,0,0,0.02); min-height: 40px; }
    .branch-label { font-weight: bold; display: inline-block; padding: 0.25rem 0.5rem; border-radius: 0.25rem; margin-bottom: 0.5rem !important;}
    .branch-label.text-success { background-color: #d1e7dd; border: 1px solid #a3cfbb !important;}
    .branch-label.text-danger { background-color: #f8d7da; border: 1px solid #f1aeb5 !important;}

     /* Add styles based on step_color later if desired */
</style>