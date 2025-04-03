<?php
// includes/functions.php - Helper Functions

// Ensure DB connection is available if not already included elsewhere
// require_once __DIR__ . '/db.php'; // Might be included by the calling script already

/**
 * Sanitizes output to prevent XSS attacks.
 *
 * @param string|null $string The string to sanitize.
 * @return string The sanitized string.
 */
function escape(?string $string): string {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Fetches all protocol categories.
 *
 * @param PDO $pdo PDO database connection instance.
 * @return array Array of categories.
 */
function get_all_categories(PDO $pdo): array {
    // Ensure PDO is available
     if (!$pdo) {
        error_log("PDO object is null in get_all_categories");
        return []; // Return empty array if DB connection failed
     }
    try {
        $stmt = $pdo->query("SELECT * FROM categories ORDER BY category_number ASC, name ASC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Database Error in get_all_categories: " . $e->getMessage());
        return []; // Return empty on error
    }
}

/**
 * Fetches a single protocol by its ID or Number.
 *
 * @param PDO $pdo PDO database connection instance.
 * @param int|string $identifier Protocol ID (int) or Protocol Number (string).
 * @return array|false Protocol data or false if not found.
 */
function get_protocol(PDO $pdo, $identifier) {
     // Ensure PDO is available
     if (!$pdo) {
        error_log("PDO object is null in get_protocol");
        return false;
     }
     try {
        $sql = "SELECT p.*, c.name as category_name FROM protocols p LEFT JOIN categories c ON p.category_id = c.category_id WHERE ";
        if (is_numeric($identifier)) {
            $sql .= "p.protocol_id = ?";
        } else {
            $sql .= "p.protocol_number = ?";
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$identifier]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Database Error in get_protocol: " . $e->getMessage());
        return false; // Return false on error
    }
}

/**
 * Fetches all steps for a given protocol, ordered correctly for initial processing.
 *
 * @param PDO $pdo PDO database connection instance.
 * @param int $protocol_id The ID of the protocol.
 * @return array Array of protocol steps.
 */
function get_protocol_steps(PDO $pdo, int $protocol_id): array {
     // Ensure PDO is available
     if (!$pdo) {
        error_log("PDO object is null in get_protocol_steps for protocol ID: " . $protocol_id);
        return [];
     }
    try {
        // Fetch all steps at once. Order by parent, then explicit order.
        $stmt = $pdo->prepare("SELECT * FROM protocol_steps WHERE protocol_id = ? ORDER BY parent_step_id ASC, step_order ASC");
        $stmt->execute([$protocol_id]);
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Database Error in get_protocol_steps: " . $e->getMessage());
        return []; // Return empty on error
    }
}


/**
 * Renders the HTML for a single step block.
 * Used by the recursive rendering functions.
 *
 * @param array $step The step data array.
 * @param int $level The current nesting level.
 * @param int|null $step_number The sequential number for this step at its level (optional).
 * @return string HTML for the step block.
 */
function render_step_html(array $step, int $level, ?int $step_number = null): string {
    $output = '';
    $is_question = $step['step_type'] === 'QUESTION_YES_NO';
    $is_special = in_array($step['step_type'], ['ENTRY', 'OUTCOME', 'REFERENCE', 'QUESTION_YES_NO']);
    $color_class = 'step-color-' . strtolower(escape($step['step_color'] ?? 'default'));

    $output .= '<div class="protocol-step level-' . $level . ' type-' . strtolower(escape($step['step_type'])) . ' ' . $color_class . '" data-step-id="' . escape($step['step_id']) . '">';

    // --- Step Header ---
    $output .= '<div class="step-header">';
    if ($step_number !== null && !$is_special) { // Only show number if provided and not special type
         $output .= '<span class="step-number">' . $step_number . '</span> ';
    }
    if (!empty($step['icon_class'])) { $output .= '<i class="' . escape($step['icon_class']) . ' me-2 step-icon"></i>'; }
    if (!empty($step['title'])) { $output .= '<span class="step-title">' . escape($step['title']) . '</span>'; }
    // Provider Levels (pushed right)
    $output .= '<span class="provider-levels ms-auto">';
    if(!empty($step['provider_level_emr'])) $output .= '<span class="badge bg-secondary me-1">EMR</span>';
    if(!empty($step['provider_level_emt'])) $output .= '<span class="badge bg-warning text-dark me-1">EMT</span>';
    if(!empty($step['provider_level_emtiv'])) $output .= '<span class="badge bg-purple me-1">EMT-IV</span>';
    if(!empty($step['provider_level_aemt'])) $output .= '<span class="badge bg-success me-1">AEMT</span>';
    if(!empty($step['provider_level_intermediate'])) $output .= '<span class="badge bg-orange me-1">Intermediate</span>';
    if(!empty($step['provider_level_paramedic'])) $output .= '<span class="badge bg-info me-1">Paramedic</span>';
    $output .= '</span></div>'; // End Header

    // --- Step Content ---
    $output .= '<div class="step-content">';
    if (!empty($step['content'])) {
        // WARNING: Assumes HTML content is safe (sanitized on input if using WYSIWYG)
        // Consider using HTMLPurifier library here for production safety
        // Example:
        // $config = HTMLPurifier_Config::createDefault();
        // $purifier = new HTMLPurifier($config);
        // $safe_html = $purifier->purify($step['content']);
        // $output .= $safe_html;
         $output .= $step['content']; // Outputting directly for now
    }
    $output .= '</div>';

    // --- Modal Trigger Button ---
     if (!empty($step['modal_trigger_text'])) {
         $output .= '<div class="mt-2">';
         $output .= '<button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modal-step-' . escape($step['step_id']) . '">';
         $output .= '<i class="bi bi-info-circle me-1"></i> ' . escape($step['modal_trigger_text']);
         $output .= '</button></div>';
     }

     // --- Considerations/Usage Info ---
    if (!empty($step['considerations'])) { $output .= '<div class="step-considerations text-muted small mt-1"><em>' . escape($step['considerations']) . '</em></div>'; }
    if (!empty($step['usage_info'])) { $output .= '<div class="step-usage-info text-muted small mt-1"><em>' . escape($step['usage_info']) . '</em></div>'; }

    // --- Placeholder for children HTML ---
    // This will be replaced by the recursive function
    $output .= '%%CHILDREN_HTML_PLACEHOLDER%%';

    $output .= '</div>'; // End protocol-step div
    return $output;
}


/**
 * Recursively renders protocol steps, handling nesting and branching.
 *
 * @param array $all_steps Reference to the flat list of all steps (can be modified to mark processed).
 * @param int|null $parent_id The ID of the parent step to render children for (null for root).
 * @param int $level Current nesting level.
 * @param int &$step_counter Reference to the counter for non-special steps at the current parent level.
 * @return string HTML output for the steps at this level and below.
 */
function render_protocol_steps_recursive(array &$all_steps, ?int $parent_id, int $level, int &$step_counter): string {
    $output = '';

    // Find direct children for this parent ID that are NOT conditional branches
    $children_keys = array_keys(array_filter($all_steps, fn($step) => !isset($step['_processed']) && $step['parent_step_id'] == $parent_id && $step['condition_from_parent'] === null));
    // Sort keys based on step_order
    // ** CORRECTED: Removed invalid 'use ($all_steps)' **
    usort($children_keys, fn($a_key, $b_key) => ($all_steps[$a_key]['step_order'] ?? 0) <=> ($all_steps[$b_key]['step_order'] ?? 0));

    foreach ($children_keys as $key) {
        // Prevent infinite loops if data is corrupt, check if already processed
        if (isset($all_steps[$key]['_processed'])) {
            continue;
        }
        $step = $all_steps[$key];
        $all_steps[$key]['_processed'] = true; // Mark as processed

        $is_question = $step['step_type'] === 'QUESTION_YES_NO';
        $is_special = in_array($step['step_type'], ['ENTRY', 'OUTCOME', 'REFERENCE', 'QUESTION_YES_NO']);
        $current_step_number = null;
        if (!$is_special) {
            $step_counter++; // Increment counter only for regular steps in this level
            $current_step_number = $step_counter;
        }

        // Render the current step's basic HTML
        $step_html = render_step_html($step, $level, $current_step_number);

        // --- Handle Children and Branches ---
        $children_html = '';
        $branch_step_counter = 0; // Counter for steps within branches

        if ($is_question) {
            // Handle YES/NO Branches
            $yes_branch_html = render_branch_children($all_steps, $step['step_id'], 'YES', $level + 1, $branch_step_counter); // Pass counter
            $no_branch_html = render_branch_children($all_steps, $step['step_id'], 'NO', $level + 1, $branch_step_counter); // Pass counter

            if (!empty($yes_branch_html) || !empty($no_branch_html)) {
                $children_html .= '<div class="step-conditional-branches row mt-3">';
                $children_html .= '<div class="col-md-6 branch-yes"><div class="branch-label branch-label-yes"><i class="bi bi-check-circle-fill"></i> YES</div><div class="branch-content">' . (!empty($yes_branch_html) ? $yes_branch_html : '<p class="text-muted small fst-italic px-2">No steps for YES path.</p>') . '</div></div>';
                $children_html .= '<div class="col-md-6 branch-no"><div class="branch-label branch-label-no"><i class="bi bi-x-circle-fill"></i> NO</div><div class="branch-content">' . (!empty($no_branch_html) ? $no_branch_html : '<p class="text-muted small fst-italic px-2">No steps for NO path.</p>') . '</div></div>';
                $children_html .= '</div>';
            }
        } else {
             // Render standard children recursively for non-question steps
             // Create a separate counter for the next level down to avoid conflicts
             $child_step_counter = 0;
             $children_html .= render_protocol_steps_recursive($all_steps, $step['step_id'], $level + 1, $child_step_counter); // Pass NEW counter
        }

        // Replace placeholder with actual children/branch HTML
        $output .= str_replace('%%CHILDREN_HTML_PLACEHOLDER%%', $children_html, $step_html);

    } // End foreach

    return $output;
}

/**
 * Helper function to render children specifically for a YES or NO branch.
 *
 * @param array $all_steps Reference to the flat list of all steps.
 * @param int $parent_step_id The ID of the parent question step.
 * @param string $condition 'YES' or 'NO'.
 * @param int $level The nesting level for these branch steps.
 * @param int &$branch_counter Reference to counter for steps within this specific branch rendering call.
 * @return string HTML output for the branch steps.
 */
function render_branch_children(array &$all_steps, int $parent_step_id, string $condition, int $level, int &$branch_counter): string {
    $branch_output = '';

    // Find direct children for this branch
    $branch_children_keys = array_keys(array_filter($all_steps, fn($step) => !isset($step['_processed']) && $step['parent_step_id'] == $parent_step_id && $step['condition_from_parent'] === $condition));
    // Sort keys based on step_order
    // ** CORRECTED: Removed invalid 'use ($all_steps)' **
    usort($branch_children_keys, fn($a_key, $b_key) => ($all_steps[$a_key]['step_order'] ?? 0) <=> ($all_steps[$b_key]['step_order'] ?? 0));

    foreach ($branch_children_keys as $key) {
         // Prevent infinite loops if data is corrupt
         if (isset($all_steps[$key]['_processed'])) {
             continue;
         }
        $child_step = $all_steps[$key];
        $all_steps[$key]['_processed'] = true; // Mark as processed

        $is_special = in_array($child_step['step_type'], ['ENTRY', 'OUTCOME', 'REFERENCE', 'QUESTION_YES_NO']);
        $current_step_number = null;
        if (!$is_special) {
            $branch_counter++; // Use branch counter
            $current_step_number = $branch_counter;
        }

        $step_html = render_step_html($child_step, $level, $current_step_number); // Render the step itself

        // Now render its standard children or nested question branches
        $sub_children_html = '';
        $sub_branch_counter = 0; // Counter for sub-levels within this specific branch child

        if ($child_step['step_type'] === 'QUESTION_YES_NO') {
            // Handle nested question within branch
            $nested_yes_html = render_branch_children($all_steps, $child_step['step_id'], 'YES', $level + 1, $sub_branch_counter);
            $nested_no_html = render_branch_children($all_steps, $child_step['step_id'], 'NO', $level + 1, $sub_branch_counter);
             if (!empty($nested_yes_html) || !empty($nested_no_html)) {
                $sub_children_html .= '<div class="step-conditional-branches row mt-3">';
                $sub_children_html .= '<div class="col-md-6 branch-yes"><div class="branch-label branch-label-yes"><i class="bi bi-check-circle-fill"></i> YES</div><div class="branch-content">' . (!empty($nested_yes_html) ? $nested_yes_html : '<p class="text-muted small fst-italic px-2">No steps for YES path.</p>') . '</div></div>';
                $sub_children_html .= '<div class="col-md-6 branch-no"><div class="branch-label branch-label-no"><i class="bi bi-x-circle-fill"></i> NO</div><div class="branch-content">' . (!empty($nested_no_html) ? $nested_no_html : '<p class="text-muted small fst-italic px-2">No steps for NO path.</p>') . '</div></div>';
                $sub_children_html .= '</div>';
             }
        } else {
            // Render standard children of this branch step using main recursive function
            // Important: Pass a *new* counter for the sublevel to avoid messing up current branch numbering
            $sub_level_counter = 0;
            $sub_children_html .= render_protocol_steps_recursive($all_steps, $child_step['step_id'], $level + 1, $sub_level_counter);
        }

         $branch_output .= str_replace('%%CHILDREN_HTML_PLACEHOLDER%%', $sub_children_html, $step_html);
    }
    return $branch_output;
}


/**
 * Top-level function called by the view template to render the entire protocol display.
 *
 * @param array $all_steps Flat list of all steps, fetched from DB.
 * @return string Complete HTML for the protocol steps and modals.
 */
function render_protocol_display(array $all_steps): string {
     if (empty($all_steps)) {
        return "<p>No steps defined for this protocol yet.</p>";
     }

     // Create a working copy to add temporary processing flags
     $working_steps = $all_steps;
     foreach ($working_steps as $k => $v) {
         unset($working_steps[$k]['_processed']); // Ensure flag isn't set initially
     }


     // Initialize master step counter (passed by reference)
     $step_counter = 0;

     // Start rendering from root (parent_id = null)
     $html_output = render_protocol_steps_recursive($working_steps, null, 0, $step_counter);

     // Render modals after all steps
     $html_output .= render_protocol_modals($all_steps); // Use original array for modals

     return $html_output;
}


/**
 * Renders all necessary Bootstrap modals for steps within a protocol.
 * Should be called ONCE after all steps have been rendered.
 *
 * @param array $all_steps Flat list of all steps for the protocol.
 * @return string HTML output for all modals.
 */
function render_protocol_modals(array $all_steps): string {
    $modal_output = '';
    foreach ($all_steps as $step) {
        // Check if this step has modal content AND a trigger text
        if (!empty($step['modal_trigger_text']) && (!empty($step['modal_title']) || !empty($step['modal_content']))) {
            $modal_id = 'modal-step-' . escape($step['step_id']);
            $modal_label_id = 'modalLabel-step-' . escape($step['step_id']);

            $modal_output .= '<div class="modal fade" id="' . $modal_id . '" tabindex="-1" aria-labelledby="' . $modal_label_id . '" aria-hidden="true">';
            $modal_output .= '  <div class="modal-dialog modal-lg modal-dialog-scrollable">'; // Added scrollable
            $modal_output .= '    <div class="modal-content">';
            $modal_output .= '      <div class="modal-header">';
            $modal_output .= '        <h5 class="modal-title" id="' . $modal_label_id . '">' . escape($step['modal_title'] ?? 'Details') . '</h5>';
            $modal_output .= '        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
            $modal_output .= '      </div>';
            $modal_output .= '      <div class="modal-body">';
            // WARNING: Assumes modal HTML content is safe
             // Consider using HTMLPurifier library here for production safety
             // $config = HTMLPurifier_Config::createDefault(); ... $purifier = new HTMLPurifier($config);
             // $safe_modal_html = $purifier->purify($step['modal_content'] ?? '');
             // $modal_output .= $safe_modal_html ?: '<p>No additional details provided.</p>';
            $modal_output .= $step['modal_content'] ?? '<p>No additional details provided.</p>'; // Outputting directly
            $modal_output .= '      </div>';
            $modal_output .= '      <div class="modal-footer">';
            $modal_output .= '        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>';
            $modal_output .= '      </div>';
            $modal_output .= '    </div>';
            $modal_output .= '  </div>';
            $modal_output .= '</div>';
        }
    }
    return $modal_output;
}


// Add any other necessary functions below

?>