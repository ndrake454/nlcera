<?php
// admin/ajax_save_steps.php - Handles saving the protocol step structure

// Set header BEFORE any output
header('Content-Type: application/json');

// Use output buffering to catch accidental whitespace or errors before JSON
ob_start();

require_once __DIR__ . '/includes/auth_check.php'; // Auth check first
require_once __DIR__ . '/../includes/db.php';
// require_once __DIR__ . '/../includes/functions.php'; // Not needed here currently

$response = ['success' => false, 'message' => 'An unknown error occurred.'];

// --- Get Data ---
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
     $response['message'] = 'Invalid request method.';
     ob_end_clean(); // Clean buffer before outputting JSON
     echo json_encode($response);
     exit;
}

// Assuming data is sent as JSON in the request body
$json_data = file_get_contents('php://input');
// error_log("AJAX Raw Body: " . $json_data); // Uncomment for deep debugging

$data = json_decode($json_data, true); // Decode as associative array

if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
     error_log("AJAX JSON Decode Error: " . json_last_error_msg() . " | Raw Data: " . $json_data);
     $response['message'] = 'Error decoding request data: ' . json_last_error_msg();
     ob_end_clean();
     echo json_encode($response);
     exit;
}
// error_log("AJAX Decoded Data: " . print_r($data, true)); // Uncomment for deep debugging


$protocol_id = $data['protocol_id'] ?? null;
$steps_structure = $data['steps'] ?? null; // This should be a flat array now
$received_token = $data['csrf_token'] ?? null; // Extract CSRF from payload


// --- CSRF Check ---
// error_log("AJAX Save: Session Token = " . ($_SESSION['csrf_token'] ?? 'NOT SET')); // Uncomment for deep debugging
// error_log("AJAX Save: Received Token = " . ($received_token ?? 'NOT RECEIVED')); // Uncomment for deep debugging
if (empty($_SESSION['csrf_token']) || !is_string($received_token) || !hash_equals($_SESSION['csrf_token'], $received_token)) {
    $response['message'] = 'Invalid security token.';
    error_log("CSRF Check Failed: Session=" . ($_SESSION['csrf_token'] ?? 'N/A') . " | Received=" . ($received_token ?? 'N/A')); // Log failure details
    ob_end_clean();
    echo json_encode($response);
    exit;
}
// --- End CSRF Check ---


if (!filter_var($protocol_id, FILTER_VALIDATE_INT)) {
     $response['message'] = 'Invalid Protocol ID provided.';
     ob_end_clean();
     echo json_encode($response);
     exit;
}

if ($steps_structure === null || !is_array($steps_structure)) {
     // Allow saving an empty structure (deletes all steps)
     if (is_array($steps_structure) && empty($steps_structure)) {
         // Proceed to delete steps
     } else {
        $response['message'] = 'Invalid or missing steps data structure.';
        ob_end_clean();
        echo json_encode($response);
        exit;
     }
}


// --- Saving Logic ---
// Use $pdo which should be defined in db.php
if (!isset($pdo) || !$pdo instanceof PDO) {
    $response['message'] = 'Database connection is not available.';
    error_log("CRITICAL: PDO object not available in ajax_save_steps.php");
    ob_end_clean();
    echo json_encode($response);
    exit;
}

$pdo->beginTransaction(); // Start database transaction

try {
    // 1. Delete all existing steps for this protocol
    $stmt_delete = $pdo->prepare("DELETE FROM protocol_steps WHERE protocol_id = ?");
    $stmt_delete->execute([$protocol_id]);

    // Only proceed if there are steps to insert
    if (!empty($steps_structure)) {

        // 2. Prepare the INSERT statement
        // Ensure column names exactly match your DB table structure
        $sql_insert = "INSERT INTO protocol_steps (
                        protocol_id, parent_step_id, condition_from_parent, step_order, step_type,
                        title, content, step_color, icon_class,
                        provider_level_emr, provider_level_emt, provider_level_emtiv, provider_level_aemt, provider_level_intermediate, provider_level_paramedic,
                        modal_title, modal_trigger_text, modal_content
                    ) VALUES (
                        :protocol_id, :parent_step_id, :condition_from_parent, :step_order, :step_type,
                        :title, :content, :step_color, :icon_class,
                        :prov_emr, :prov_emt, :prov_emtiv, :prov_aemt, :prov_inter, :prov_para,
                        :modal_title, :modal_trigger, :modal_content
                    )";
        $stmt_insert = $pdo->prepare($sql_insert);

        // --- Build Tree and Insert Recursively (More Robust) ---
        $steps_by_temp_id = []; // Map temp_id/step_id to step data object
        $tree = [];              // Tree structure (children array for each node)
        $root_steps = [];        // Steps with no parent

        // Pass 1: Index steps by temp_id/step_id and identify root steps/build parent->children map
        foreach ($steps_structure as $step) {
             // Use temp_id if it exists (new step), otherwise use existing step_id
             $current_id_key = $step['temp_id'] ?: $step['step_id'];
             if (!$current_id_key) {
                 error_log("Step data missing ID or Temp ID: " . print_r($step, true));
                 continue; // Skip malformed step
             }
             $steps_by_temp_id[$current_id_key] = $step; // Index by the ID JS knows

             $parent_id_key = $step['parent_step_id']; // This is the parent's temp_id or DB ID or null

             if ($parent_id_key === null || $parent_id_key === '') {
                 $root_steps[] = $current_id_key; // Add temp_id/step_id to root list
             } else {
                 // Initialize children array for parent if it doesn't exist
                 if (!isset($tree[$parent_id_key])) {
                     $tree[$parent_id_key] = ['children' => []];
                 }
                  // Add current step's temp_id/step_id under its parent's temp_id/step_id
                 $tree[$parent_id_key]['children'][] = $current_id_key;
             }
        }

        // Create mapping for temporary IDs to new database IDs
        $temp_id_map = [];
        // Global variable to keep track of order within siblings during recursion
        $step_order_counter = 0;

        // Pass 2: Recursive function to insert steps depth-first
        function insertStepsFromTree(
            $step_id_or_temp_id,      // The ID/TempID of the step to insert
            $condition,               // The condition ('YES', 'NO', or null) from parent
            $parent_db_id,            // The ACTUAL database ID of the parent (null for root)
            array &$steps_by_temp_id, // Reference to the map of all step data
            array &$tree,             // Reference to the parent->children structure map
            PDOStatement &$stmt_insert,// Reference to the prepared statement
            array &$temp_id_map,      // Reference to the temp ID -> DB ID map
            int $current_protocol_id, // The protocol ID we're working on
            PDO $pdo                  // The PDO connection object
         ) {
             global $step_order_counter; // Use global counter

             // Get the actual step data using the temp_id/step_id
             if (!isset($steps_by_temp_id[$step_id_or_temp_id])) {
                  error_log("Data not found for step ID/TempID: " . $step_id_or_temp_id);
                  return; // Skip if data is missing
             }
             $step = $steps_by_temp_id[$step_id_or_temp_id];

             // --- Sanitize/Validate Step Data ---
             // Use the order value provided by JS, otherwise default to 0
             $step_order = filter_var($step['step_order'] ?? 0, FILTER_VALIDATE_INT);
             $step_order = ($step_order === false || $step_order < 0) ? 0 : $step_order;

             $allowed_types = ['INFO', 'ACTION', 'ENTRY', 'QUESTION_YES_NO', 'OUTCOME', 'REFERENCE'];
             $step_type = in_array($step['step_type'] ?? 'INFO', $allowed_types) ? $step['step_type'] : 'INFO';
             $step_color = preg_match('/^[a-zA-Z]+$/', $step['step_color'] ?? '') ? $step['step_color'] : 'default';
             $icon_class = (!empty($step['icon_class']) && preg_match('/^bi-[a-z0-9\-]+$/i', $step['icon_class'])) ? $step['icon_class'] : null;
             // --- End Sanitize ---

             // Execute Insert
             $stmt_insert->execute([
                 ':protocol_id' => $current_protocol_id,
                 ':parent_step_id' => $parent_db_id, // Parent's actual DB ID passed down
                 ':condition_from_parent' => ($condition === 'YES' || $condition === 'NO') ? $condition : null, // Condition passed down
                 ':step_order' => $step_order,
                 ':step_type' => $step_type,
                 ':title' => $step['title'] ?: null,
                 ':content' => $step['content'] ?: null,
                 ':step_color' => $step_color,
                 ':icon_class' => $icon_class,
                 ':prov_emr' => !empty($step['provider_level_emr']) ? 1 : 0,
                 ':prov_emt' => !empty($step['provider_level_emt']) ? 1 : 0,
                 ':prov_emtiv' => !empty($step['provider_level_emtiv']) ? 1 : 0,
                 ':prov_aemt' => !empty($step['provider_level_aemt']) ? 1 : 0,
                 ':prov_inter' => !empty($step['provider_level_intermediate']) ? 1 : 0,
                 ':prov_para' => !empty($step['provider_level_paramedic']) ? 1 : 0,
                 ':modal_title' => $step['modal_title'] ?: null,
                 ':modal_trigger' => $step['modal_trigger_text'] ?: null,
                 ':modal_content' => $step['modal_content'] ?: null,
             ]);

             $new_db_id = $pdo->lastInsertId();

             // If this was a new step (had a temp_id), map its temp ID to the new DB ID
             if (!empty($step['temp_id'])) {
                 $temp_id_map[$step['temp_id']] = $new_db_id;
             }

             // Find and process children for this step
             $children_ids = $tree[$step_id_or_temp_id]['children'] ?? [];
             if (!empty($children_ids)) {
                 // Sort children based on their original step_order from JS payload
                 usort($children_ids, function($a_id, $b_id) use ($steps_by_temp_id) {
                     $order_a = $steps_by_temp_id[$a_id]['step_order'] ?? 0;
                     $order_b = $steps_by_temp_id[$b_id]['step_order'] ?? 0;
                     return $order_a <=> $order_b;
                 });

                 // Process children recursively
                 foreach ($children_ids as $child_id) {
                     // Get condition for the child from its data object
                     $child_condition = $steps_by_temp_id[$child_id]['condition_from_parent'] ?? null;
                     // The parent ID for the child is the DB ID we just inserted ($new_db_id)
                     insertStepsFromTree(
                         $child_id,
                         $child_condition,
                         $new_db_id, // Pass the new DB ID as the parent for the children
                         $steps_by_temp_id,
                         $tree,
                         $stmt_insert,
                         $temp_id_map,
                         $current_protocol_id,
                         $pdo
                     );
                 }
             }
        } // --- End of insertStepsFromTree function ---


        // Pass 3: Initiate the recursive insertion starting with root steps
        // Sort root steps based on their original step_order from JS payload
        usort($root_steps, function($a_id, $b_id) use ($steps_by_temp_id) {
             $order_a = $steps_by_temp_id[$a_id]['step_order'] ?? 0;
             $order_b = $steps_by_temp_id[$b_id]['step_order'] ?? 0;
             return $order_a <=> $order_b;
         });

        // Loop through sorted root steps and start the insertion process
        foreach ($root_steps as $root_step_id_key) {
            insertStepsFromTree(
                $root_step_id_key, // The temp_id or step_id of the root step
                null,             // Root steps have null condition
                null,             // Root steps have null parent DB ID
                $steps_by_temp_id,
                $tree,
                $stmt_insert,
                $temp_id_map,
                $protocol_id,     // Pass the correct protocol ID
                $pdo
             );
        }
        // --- End Build Tree and Insert Recursively ---

    } // End check if steps_structure not empty

    // If we got here without errors, commit the transaction
    $pdo->commit();
    $response['success'] = true;
    $response['message'] = 'Protocol steps saved successfully.';

} catch (Exception $e) { // Catch PDOExceptions or our manual Exceptions
    if ($pdo->inTransaction()) {
        $pdo->rollBack(); // Roll back changes on any error
    }
    $response['message'] = 'Error saving steps: ' . $e->getMessage();
    http_response_code(500); // Set appropriate error status code for client side
    error_log("Protocol Step Save Error (Protocol ID: {$protocol_id}): " . $e->getMessage()); // Log detailed error for server admin
}

// --- End of Saving Logic ---

ob_end_clean(); // Clean buffer before outputting final JSON
echo json_encode($response);
exit;

?>