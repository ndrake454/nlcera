/**
 * Recursively renders protocol steps, handling nesting and branching.
 *
 * @param array $all_steps Reference to the flat list of all steps (can be modified to mark processed).
 * @param int|null $parent_id The ID of the parent step to render children for (null for root).
 * @param int $level Current nesting level.
 * @return string HTML output for the steps at this level and below.
 */
function render_protocol_steps_recursive(array &$all_steps, ?int $parent_id, int $level): string {
    $output = '';
    $step_counter = 0; // Counter specific to this level and parent

    // Find direct children for this parent ID that are NOT conditional branches
    $children_keys = array_keys(array_filter($all_steps, fn($step) => !isset($step['_processed']) && $step['parent_step_id'] == $parent_id && $step['condition_from_parent'] === null));
    // Sort keys based on step_order
    usort($children_keys, fn($a_key, $b_key) => ($all_steps[$a_key]['step_order'] ?? 0) <=> ($all_steps[$b_key]['step_order'] ?? 0));

    foreach ($children_keys as $key) {
        if (isset($all_steps[$key]['_processed'])) { continue; } // Skip if somehow already processed
        $step = $all_steps[$key];
        $all_steps[$key]['_processed'] = true; // Mark as processed

        $is_question = $step['step_type'] === 'QUESTION_YES_NO';
        $is_special = in_array($step['step_type'], ['ENTRY', 'OUTCOME', 'REFERENCE', 'QUESTION_YES_NO']);
        $current_step_number = null;
        if (!$is_special) {
            $step_counter++; // Increment counter only for regular steps in this level
            $current_step_number = $step_counter;
        }

        $step_html = render_step_html($step, $level, $current_step_number); // Render the step shell

        // --- Handle Children and Branches ---
        $children_html = '';
        if ($is_question) {
            // Handle YES/NO Branches
            $branch_counter_yes = 0; // Independent counter for YES branch
            $branch_counter_no = 0;  // Independent counter for NO branch
            $yes_branch_html = render_branch_children($all_steps, $step['step_id'], 'YES', $level + 1, $branch_counter_yes);
            $no_branch_html = render_branch_children($all_steps, $step['step_id'], 'NO', $level + 1, $branch_counter_no);

            if (!empty($yes_branch_html) || !empty($no_branch_html)) {
                $children_html .= '<div class="step-conditional-branches row mt-3">';
                $children_html .= '<div class="col-md-6 branch-yes"><div class="branch-label branch-label-yes"><i class="bi bi-check-circle-fill"></i> YES</div><div class="branch-content">' . (!empty($yes_branch_html) ? $yes_branch_html : '<p class="text-muted small fst-italic px-2">No steps for YES path.</p>') . '</div></div>';
                $children_html .= '<div class="col-md-6 branch-no"><div class="branch-label branch-label-no"><i class="bi bi-x-circle-fill"></i> NO</div><div class="branch-content">' . (!empty($no_branch_html) ? $no_branch_html : '<p class="text-muted small fst-italic px-2">No steps for NO path.</p>') . '</div></div>';
                $children_html .= '</div>';
            }
        } else {
             // Render standard children recursively for non-question steps
             $children_html .= render_protocol_steps_recursive($all_steps, $step['step_id'], $level + 1); // Start recursion for children
        }

        // Replace placeholder with actual children/branch HTML
        $output .= str_replace('%%CHILDREN_HTML_PLACEHOLDER%%', $children_html, $step_html);

    } // End foreach

    return $output;
}

/**
 * Helper function to render children specifically for a YES or NO branch.
 * This function now acts similarly to the main recursive function but for a specific branch.
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
    usort($branch_children_keys, fn($a_key, $b_key) => ($all_steps[$a_key]['step_order'] ?? 0) <=> ($all_steps[$b_key]['step_order'] ?? 0));

    foreach ($branch_children_keys as $key) {
        if (isset($all_steps[$key]['_processed'])) { continue; }
        $child_step = $all_steps[$key];
        $all_steps[$key]['_processed'] = true; // Mark as processed

        $is_special = in_array($child_step['step_type'], ['ENTRY', 'OUTCOME', 'REFERENCE', 'QUESTION_YES_NO']);
        $current_step_number = null;
        if (!$is_special) {
            $branch_counter++; // Use branch counter
            $current_step_number = $branch_counter;
        }

        $step_html = render_step_html($child_step, $level, $current_step_number); // Render the step itself

        // Render its standard children OR nested question branches using the *main* recursive function
        $sub_children_html = render_protocol_steps_recursive($all_steps, $child_step['step_id'], $level + 1); // Use main recursive function

         $branch_output .= str_replace('%%CHILDREN_HTML_PLACEHOLDER%%', $sub_children_html, $step_html);
    }
    return $branch_output;
}