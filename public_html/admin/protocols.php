<?php
// admin/protocols.php - Manage Protocols (Basic CRUD)

require_once __DIR__ . '/includes/auth_check.php'; // Ensure user is logged in
require_once __DIR__ . '/../includes/db.php';      // Database connection ($pdo)
require_once __DIR__ . '/../includes/functions.php'; // escape() function

// --- CSRF Token ---
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
// --- End CSRF ---


$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?? 'list'; // Default action
$protocol_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$page_title = "Manage Protocols"; // Default title
$errors = []; // Store validation errors
$protocol_data = [ // Default data for form
    'protocol_id' => null,
    'protocol_number' => '',
    'title' => '',
    'category_id' => null,
];
$categories = []; // To store categories for dropdown

// Fetch categories for the form dropdown (needed for add/edit)
if ($action === 'add' || $action === 'edit') {
    try {
        $categories = $pdo->query("SELECT category_id, name, category_number FROM categories ORDER BY category_number ASC, name ASC")->fetchAll();
    } catch (PDOException $e) {
        error_log("Category Fetch for Protocol Form Error: " . $e->getMessage());
        $errors['general'] = "Could not load categories for the form.";
        // Allow form to load but show error
    }
}


// --- Handle POST requests (Add/Edit submissions) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
         $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'CSRF token mismatch. Action aborted.'];
         header('Location: protocols.php?action=list');
         exit;
    }

    // Sanitize and retrieve form data
    $protocol_data['protocol_id'] = filter_input(INPUT_POST, 'protocol_id', FILTER_VALIDATE_INT); // Hidden field for edits
    $protocol_data['protocol_number'] = trim(filter_input(INPUT_POST, 'protocol_number', FILTER_SANITIZE_STRING));
    $protocol_data['title'] = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING));
    // Get category_id, allow it to be empty/null
    $category_id_input = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT);
    $protocol_data['category_id'] = ($category_id_input > 0) ? $category_id_input : null;


    // Basic Validation
    if (empty($protocol_data['title'])) {
        $errors['title'] = "Protocol title is required.";
    }
    // Optional: Validate protocol_number format if needed (e.g., 4 digits or specific pattern)
    if (!empty($protocol_data['protocol_number']) && !preg_match('/^\d{4}$/', $protocol_data['protocol_number'])) {
         $errors['protocol_number'] = "Protocol number must be exactly 4 digits (or leave blank).";
    }

    if (empty($errors)) {
        // --- Save to Database ---
        try {
            if ($protocol_data['protocol_id']) {
                // --- Update Existing Protocol ---
                $action = 'edit'; // Stay on edit form if errors happen below
                $page_title = "Edit Protocol";

                $sql = "UPDATE protocols SET protocol_number = :num, title = :title, category_id = :cat_id WHERE protocol_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':num' => !empty($protocol_data['protocol_number']) ? $protocol_data['protocol_number'] : null,
                    ':title' => $protocol_data['title'],
                    ':cat_id' => $protocol_data['category_id'], // Can be null
                    ':id' => $protocol_data['protocol_id']
                ]);
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Protocol updated successfully.'];
                // Redirect to list view or potentially stay to edit steps later
                 header('Location: protocols.php?action=list'); // Redirect to list for now
                 exit;

            } else {
                // --- Add New Protocol ---
                $action = 'add'; // Stay on add form if errors happen below
                 $page_title = "Add Protocol";

                $sql = "INSERT INTO protocols (protocol_number, title, category_id) VALUES (:num, :title, :cat_id)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':num' => !empty($protocol_data['protocol_number']) ? $protocol_data['protocol_number'] : null,
                    ':title' => $protocol_data['title'],
                    ':cat_id' => $protocol_data['category_id'] // Can be null
                ]);
                $new_protocol_id = $pdo->lastInsertId(); // Get the ID of the new protocol
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Protocol added successfully.'];
                // Redirect to list or maybe directly to edit steps for the new protocol?
                // header('Location: protocols.php?action=edit_steps&id=' . $new_protocol_id); // Example for later
                 header('Location: protocols.php?action=list'); // Redirect to list for now
                 exit;
            }


        } catch (PDOException $e) {
             error_log("Protocol Save Error: " . $e->getMessage());
             // Check for unique constraint violation (duplicate protocol number)
             if ($e->getCode() == '23000') { // SQLSTATE 23000: Integrity constraint violation
                  if (strpos(strtolower($e->getMessage()), 'duplicate entry') !== false && strpos($e->getMessage(), 'protocol_number') !== false) {
                      $errors['protocol_number'] = "This protocol number is already in use. Please choose another or leave blank.";
                 } else {
                      $errors['general'] = "Database error: Could not save protocol due to a constraint violation. Please check your input.";
                 }
             } else {
                $errors['general'] = "Database error saving protocol: " . $e->getMessage();
             }
             // Don't redirect, show the form again with errors and original data
        }
    } else {
         // Errors occurred, stay on the form page
         if ($protocol_data['protocol_id']) {
             $page_title = "Edit Protocol";
             $action = 'edit';
         } else {
             $page_title = "Add Protocol";
             $action = 'add';
         }
    }
}


// --- Handle GET requests ---
elseif ($action === 'edit' && $protocol_id) {
    // --- Show Edit Form ---
    $page_title = "Edit Protocol";
    try {
        $stmt = $pdo->prepare("SELECT * FROM protocols WHERE protocol_id = ?");
        $stmt->execute([$protocol_id]);
        $protocol_data = $stmt->fetch();
        if (!$protocol_data) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Protocol not found.'];
            header('Location: protocols.php?action=list');
            exit;
        }
    } catch (PDOException $e) {
         error_log("Protocol Fetch Error: " . $e->getMessage());
         $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Error fetching protocol data.'];
         header('Location: protocols.php?action=list');
         exit;
    }
    // Prepare to display the form template
    $view_to_include = __DIR__ . '/templates/protocol_form_view.php';

} elseif ($action === 'add') {
    // --- Show Add Form ---
    $page_title = "Add New Protocol";
    // $protocol_data already holds defaults
    // $categories already fetched above
    // Prepare to display the form template
    $view_to_include = __DIR__ . '/templates/protocol_form_view.php';

} elseif ($action === 'delete' && $protocol_id) {
     // --- Process Delete Request ---
     // Verify CSRF token from GET request
     if (!isset($_GET['token']) || !verify_csrf_token($_GET['token'])) {
         $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Invalid security token. Deletion aborted.'];
     } else {
        try {
            // The ON DELETE CASCADE constraint on protocol_steps handles step deletion automatically
            $stmt = $pdo->prepare("DELETE FROM protocols WHERE protocol_id = ?");
            $stmt->execute([$protocol_id]);
            if ($stmt->rowCount() > 0) {
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Protocol and all its associated steps deleted successfully.'];
            } else {
                $_SESSION['flash_message'] = ['type' => 'warning', 'text' => 'Protocol not found or already deleted.'];
            }
        } catch (PDOException $e) {
            error_log("Protocol Delete Error: " . $e->getMessage());
            // Catch potential foreign key constraint errors if cascade wasn't set up right, though unlikely
             $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Error deleting protocol. Check logs.'];
        }
     }
     // Redirect back to the list view regardless of outcome
     header('Location: protocols.php?action=list');
     exit;

} else { // Default: 'list' action
    // --- Show List View ---
    $page_title = "Manage Protocols";
    $protocols = [];
    try {
        // Fetch all protocols with category name, ordered
        $sql = "SELECT p.*, c.name as category_name
                FROM protocols p
                LEFT JOIN categories c ON p.category_id = c.category_id
                ORDER BY p.protocol_number ASC, p.title ASC";
        $stmt = $pdo->query($sql);
        $protocols = $stmt->fetchAll();
    } catch (PDOException $e) {
         error_log("Protocol List Fetch Error: " . $e->getMessage());
         $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Error fetching protocol list.'];
         // Show an empty list or error message in the view
    }
    // Prepare to display the list template
    $view_to_include = __DIR__ . '/templates/protocol_list_view.php';
}

// --- Include Header ---
include __DIR__ . '/templates/admin_header.php';

// --- Include the determined view ---
if (isset($view_to_include) && file_exists($view_to_include)) {
    include $view_to_include; // Uses $protocols, $protocol_data, $categories, $errors, $csrf_token etc.
} else {
    echo "<div class='alert alert-danger'>Error: View file not found for action '{$action}'.</div>";
}

// --- Include Footer ---
include __DIR__ . '/templates/admin_footer.php';
?>