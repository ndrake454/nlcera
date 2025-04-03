<?php
// includes/functions.php - Helper Functions

// Ensure DB connection is available if not already included elsewhere
// require_once __DIR__ . '/db.php';

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
 * Note: Final visual sorting happens during rendering.
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
 * Renders the protocol steps recursively, handling nesting and branching.
 *
 * @param array $all_steps Flat list of all steps for the protocol, pre-fetched and ordered by step_order.
 * @param int|null $parent_id The ID of the parent step to render children for (null for root).
 * @param int $level Current nesting level (for indentation/styling).
 * @return string HTML output for the steps.
 */
function render_protocol_steps(array $all_steps, ?int $parent_id = null, int $level = 0): string {
    $output = '';
    $step_counter = 0; // Simple counter for non-question/outcome steps at the current level

    // Filter steps for the current parent and WITHOUT a specific condition (YES/NO handled separately)
    $current_level_steps = array_filter($all_steps, function($step) use ($parent_id) {
        // Ensure parent_id comparison handles null correctly
        return ($step['parent_step_id'] === $parent_id) && ($step['condition_from_parent'] === null);
    });


    // Sort steps at the current level by their order
    usort($current_level_steps, fn($a, $b) => ($a['step_order'] ?? 0) <=> ($b['step_order'] ?? 0));

    foreach ($current_level_steps as $step) {
        $step_counter++;
        $is_question = $step['step_type'] === 'QUESTION_YES_NO';
        $is_outcome = $step['step_type'] === 'OUTCOME';
        $is_entry = $step['step_type'] === 'ENTRY';
        $is_reference = $step['step_type'] === 'REFERENCE';

        // --- Start Step Element ---
        // Add step color class if available
        $color_class = 'step-color-' . strtolower(escape($step['step_color'] ?? 'default'));
        $output .= '<div class="protocol-step level-' . $level . ' type-' . strtolower(escape($step['step_type'])) . ' ' . $color_class . '" data-step-id="' . escape($step['step_id']) . '">';

        // --- Step Header ---
        $output .= '<div class="step-header">';
        // Add step number only if it's an actionable/info step within a flow (not entry/outcome/question/ref)
        if (!$is_entry && !$is_outcome && !$is_question && !$is_reference) {
             $output .= '<span class="step-number">' . $step_counter . '</span> ';
        }
        // Add Icon
        if (!empty($step['icon_class'])) {
             $output .= '<i class="' . escape($step['icon_class']) . ' me-2 step-icon"></i>';
        }
        // Add Title
        if (!empty($step['title'])) {
            $output .= '<span class="step-title">' . escape($step['title']) . '</span>';
        }
        // Add Provider Levels
         $output .= '<span class="provider-levels ms-auto">'; // Use ms-auto to push to right
         if(!empty($step['provider_level_emr'])) $output .= '<span class="badge bg-secondary me-1">EMR</span>';
         if(!empty($step['provider_level_emt'])) $output .= '<span class="badge bg-warning text-dark me-1">EMT</span>';
         if(!empty($step['provider_level_emtiv'])) $output .= '<span class="badge bg-purple me-1">EMT-IV</span>'; // Added custom color class potentially
         if(!empty($step['provider_level_aemt'])) $output .= '<span class="badge bg-success me-1">AEMT</span>'; // Adjusted colors potentially
         if(!empty($step['provider_level_intermediate'])) $output .= '<span class="badge bg-orange me-1">Intermediate</span>'; // Added custom color class potentially
         if(!empty($step['provider_level_paramedic'])) $output .= '<span class="badge bg-info me-1">Paramedic</span>'; // Adjusted colors potentially
         $output .= '</span>';
        $output .= '</div>'; // end step-header

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
        $output .= '</div>'; // end step-content

         // --- Modal Trigger Button ---
         if (!empty($step['modal_trigger_text'])) {
             $output .= '<div class="mt-2">';
             $output .= '<button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modal-step-' . escape($step['step_id']) . '">';
             $output .= '<i class="bi bi-info-circle me-1"></i> ' . escape($step['modal_trigger_text']);
             $output .= '</button>';
             $output .= '</div>';
         }

         // --- Considerations/Usage Info ---
        if (!empty($step['considerations'])) {
            $output .= '<div class="step-considerations text-muted small mt-1"><em>' . escape($step['considerations']) . '</em></div>';
        }
         if (!empty($step['usage_info'])) {
             $output .= '<div class="step-usage-info text-muted small mt-1"><em>' . escape($step['usage_info']) . '</em></div>';
         }

        // --- Recursive Call for Standard Children ---
        // Find and render direct children (not resulting from YES/NO)
         $output .= render_protocol_steps($all_steps, $step['step_id'], $level + 1);

        // --- Conditional Branching (YES/NO) ---
        if ($is_question) {
            $yes_branch_html = '';
            $no_branch_html = '';

            // Find direct children for the YES branch
            $yes_children = array_filter($all_steps, fn($child) => $child['parent_step_id'] == $step['step_id'] && $child['condition_from_parent'] === 'YES');
            usort($yes_children, fn($a, $b) => ($a['step_order'] ?? 0) <=> ($b['step_order'] ?? 0));
            foreach ($yes_children as $yes_child) {
                // Render this child and its non-conditional descendants recursively
                $yes_branch_html .= render_single_step_recursive($all_steps, $yes_child, $level + 1);
            }

            // Find direct children for the NO branch
            $no_children = array_filter($all_steps, fn($child) => $child['parent_step_id'] == $step['step_id'] && $child['condition_from_parent'] === 'NO');
            usort($no_children, fn($a, $b) => ($a['step_order'] ?? 0) <=> ($b['step_order'] ?? 0));
             foreach ($no_children as $no_child) {
                 // Render this child and its non-conditional descendants recursively
                 $no_branch_html .= render_single_step_recursive($all_steps, $no_child, $level + 1);
             }

            // Output the branching structure if either branch has content
            if (!empty($yes_branch_html) || !empty($no_branch_html)) {
                $output .= '<div class="step-conditional-branches row mt-3">'; // Added mt-3
                // YES Column
                $output .= '<div class="col-md-6 branch-yes">';
                $output .= '<div class="branch-label branch-label-yes"><i class="bi bi-check-circle-fill"></i> YES</div>';
                $output .= '<div class="branch-content">' . (!empty($yes_branch_html) ? $yes_branch_html : '<p class="text-muted small fst-italic px-2">No steps for YES path.</p>') . '</div>'; // Added padding
                $output .= '</div>'; // end branch-yes

                // NO Column
                $output .= '<div class="col-md-6 branch-no">';
                $output .= '<div class="branch-label branch-label-no"><i class="bi bi-x-circle-fill"></i> NO</div>';
                 $output .= '<div class="branch-content">' . (!empty($no_branch_html) ? $no_branch_html : '<p class="text-muted small fst-italic px-2">No steps for NO path.</p>') . '</div>'; // Added padding
                $output .= '</div>'; // end branch-no
                $output .= '</div>'; // end step-conditional-branches
            }
        }

        $output .= '</div>'; // --- End Step Element ---

    } // End foreach loop for current level steps

    return $output;
}


/**
 * Helper function to render a single step and its non-conditional children recursively.
 * Used specifically for rendering within YES/NO branches.
 *
 * @param array $all_steps Flat list of all steps for the protocol.
 * @param array $step The current step data to render.
 * @param int $level Current nesting level.
 * @return string HTML output for the step and its non-conditional children.
 */
function render_single_step_recursive(array $all_steps, array $step, int $level): string {
     $output = '';
     $step_counter = 0; // Counter within this specific recursive call

     $is_question = $step['step_type'] === 'QUESTION_YES_NO';
     $is_outcome = $step['step_type'] === 'OUTCOME';
     $is_entry = $step['step_type'] === 'ENTRY';
     $is_reference = $step['step_type'] === 'REFERENCE';

     // --- Render the current step itself ---
     $color_class = 'step-color-' . strtolower(escape($step['step_color'] ?? 'default'));
     $output .= '<div class="protocol-step level-' . $level . ' type-' . strtolower(escape($step['step_type'])) . ' ' . $color_class . '" data-step-id="' . escape($step['step_id']) . '">';

     // --- Step Header ---
     $output .= '<div class="step-header">';
     if (!$is_entry && !$is_outcome && !$is_question && !$is_reference) {
         $step_counter++;
         $output .= '<span class="step-number">' . $step_counter . '</span> ';
     }
     if (!empty($step['icon_class'])) {
          $output .= '<i class="' . escape($step['icon_class']) . ' me-2 step-icon"></i>';
     }
     if (!empty($step['title'])) {
         $output .= '<span class="step-title">' . escape($step['title']) . '</span>';
     }
      $output .= '<span class="provider-levels ms-auto">';
      if(!empty($step['provider_level_emr'])) $output .= '<span class="badge bg-secondary me-1">EMR</span>';
      if(!empty($step['provider_level_emt'])) $output .= '<span class="badge bg-warning text-dark me-1">EMT</span>';
      if(!empty($step['provider_level_emtiv'])) $output .= '<span class="badge bg-purple me-1">EMT-IV</span>';
      if(!empty($step['provider_level_aemt'])) $output .= '<span class="badge bg-success me-1">AEMT</span>';
      if(!empty($step['provider_level_intermediate'])) $output .= '<span class="badge bg-orange me-1">Intermediate</span>';
      if(!empty($step['provider_level_paramedic'])) $output .= '<span class="badge bg-info me-1">Paramedic</span>';
      $output .= '</span>';
     $output .= '</div>'; // end step-header

     // --- Step Content ---
     $output .= '<div class="step-content">';
     if (!empty($step['content'])) {
         // WARNING: Assumes HTML is safe
         $output .= $step['content'];
     }
     $output .= '</div>'; // end step-content

      // --- Modal Trigger Button ---
      if (!empty($step['modal_trigger_text'])) {
          $output .= '<div class="mt-2">';
          $output .= '<button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#modal-step-' . escape($step['step_id']) . '">';
          $output .= '<i class="bi bi-info-circle me-1"></i> ' . escape($step['modal_trigger_text']);
          $output .= '</button>';
          $output .= '</div>';
      }

      // --- Considerations/Usage Info ---
     if (!empty($step['considerations'])) {
         $output .= '<div class="step-considerations text-muted small mt-1"><em>' . escape($step['considerations']) . '</em></div>';
     }
      if (!empty($step['usage_info'])) {
          $output .= '<div class="step-usage-info text-muted small mt-1"><em>' . escape($step['usage_info']) . '</em></div>';
      }

      // --- Recursive Call for NON-CONDITIONAL Children ---
      // This is the key difference: use the main function again to handle all children correctly
      $output .= render_protocol_steps($all_steps, $step['step_id'], $level + 1);

       // --- Handle Nested Questions within Branches ---
       // This logic is now effectively handled by the recursive call to render_protocol_steps above,
       // so the specific nested question block here can be removed or simplified if the main function handles it.
       // Let's remove it to avoid redundancy and potential conflicts.
       /*
       if ($is_question) {
           // ... [Removed redundant nested question handling block] ...
       }
       */

     $output .= '</div>'; // --- End Step Element ---
     return $output;
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


// Add any other necessary functions below (e.g., if you create admin functions later)

?>