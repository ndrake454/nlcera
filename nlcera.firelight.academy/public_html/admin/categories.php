<?php
// admin/categories.php - Manage Protocol Categories

require_once __DIR__ . '/includes/auth_check.php'; // Ensure user is logged in
require_once __DIR__ . '/../includes/db.php';      // Database connection ($pdo)
require_once __DIR__ . '/../includes/functions.php'; // escape() function

// --- Simple CSRF Token Generation/Validation ---
// In a real app, use a more robust library
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
// --- End CSRF ---


$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?? 'list'; // Default action
$category_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$page_title = "Manage Categories"; // Default title
$errors = []; // Store validation errors
$category_data = [ // Default data for form
    'category_id' => null,
    'category_number' => '',
    'name' => '',
    'description' => '',
    'icon_class' => 'bi-archive-fill' // Default icon
];

// --- Handle POST requests (Add/Edit submissions) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
         $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'CSRF token mismatch. Action aborted.'];
         header('Location: categories.php?action=list');
         exit;
    }

    // Sanitize and retrieve form data
    $category_data['category_id'] = filter_input(INPUT_POST, 'category_id', FILTER_VALIDATE_INT); // Hidden field for edits
    $category_data['category_number'] = trim(filter_input(INPUT_POST, 'category_number', FILTER_SANITIZE_STRING));
    $category_data['name'] = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING));
    $category_data['description'] = trim(filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING));
    $category_data['icon_class'] = trim(filter_input(INPUT_POST, 'icon_class', FILTER_SANITIZE_STRING));

    // Basic Validation
    if (empty($category_data['name'])) {
        $errors['name'] = "Category name is required.";
    }
    // Optional: Validate category_number format if needed (e.g., must be 4 digits or empty)
    if (!empty($category_data['category_number']) && !preg_match('/^\d{4}$/', $category_data['category_number'])) {
        $errors['category_number'] = "Category number must be exactly 4 digits (or leave blank).";
    }
     // Optional: Basic icon class validation
     if (empty($category_data['icon_class']) || !preg_match('/^bi-[a-z0-9-]+$/i', $category_data['icon_class'])) {
         $errors['icon_class'] = "Invalid icon class format (should start with 'bi-').";
     }


    if (empty($errors)) {
        // --- Save to Database ---
        try {
            if ($category_data['category_id']) {
                // --- Update Existing Category ---
                 $page_title = "Edit Category"; // Set title for form view on error
                 $action = 'edit'; // Stay on edit form if errors
                $sql = "UPDATE categories SET category_number = :num, name = :name, description = :desc, icon_class = :icon WHERE category_id = :id";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':num' => !empty($category_data['category_number']) ? $category_data['category_number'] : null, // Store NULL if empty
                    ':name' => $category_data['name'],
                    ':desc' => $category_data['description'],
                    ':icon' => $category_data['icon_class'],
                    ':id' => $category_data['category_id']
                ]);
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Category updated successfully.'];

            } else {
                // --- Add New Category ---
                 $page_title = "Add Category"; // Set title for form view on error
                 $action = 'add'; // Stay on add form if errors
                $sql = "INSERT INTO categories (category_number, name, description, icon_class) VALUES (:num, :name, :desc, :icon)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                     ':num' => !empty($category_data['category_number']) ? $category_data['category_number'] : null,
                     ':name' => $category_data['name'],
                     ':desc' => $category_data['description'],
                     ':icon' => $category_data['icon_class']
                ]);
                 $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Category added successfully.'];
            }
            // Redirect to list view on success
            header('Location: categories.php?action=list');
            exit;

        } catch (PDOException $e) {
             error_log("Category Save Error: " . $e->getMessage());
             // Check for unique constraint violation (duplicate category number)
             if ($e->getCode() == '23000') { // SQLSTATE 23000: Integrity constraint violation
                 if (strpos($e->getMessage(), 'categories.category_number') !== false) {
                      $errors['category_number'] = "This category number is already in use. Please choose another or leave blank.";
                 } else {
                      $errors['general'] = "Database error: Could not save category due to a constraint violation. Please check your input.";
                 }

             } else {
                $errors['general'] = "Database error saving category: " . $e->getMessage(); // Show generic error in production
             }
             // Don't redirect, show the form again with errors and original data
        }
    } else {
         // Errors occurred, stay on the form page
         if ($category_data['category_id']) {
             $page_title = "Edit Category";
             $action = 'edit';
         } else {
             $page_title = "Add Category";
             $action = 'add';
         }
    }
}


// --- Handle GET requests ---
elseif ($action === 'edit' && $category_id) {
    // --- Show Edit Form ---
    $page_title = "Edit Category";
    try {
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
        $stmt->execute([$category_id]);
        $category_data = $stmt->fetch();
        if (!$category_data) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Category not found.'];
            header('Location: categories.php?action=list');
            exit;
        }
    } catch (PDOException $e) {
         error_log("Category Fetch Error: " . $e->getMessage());
         $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Error fetching category data.'];
         header('Location: categories.php?action=list');
         exit;
    }
    // Prepare to display the form template
    $view_to_include = __DIR__ . '/templates/category_form_view.php';

} elseif ($action === 'add') {
    // --- Show Add Form ---
    $page_title = "Add New Category";
    // $category_data already holds defaults
    // Prepare to display the form template
    $view_to_include = __DIR__ . '/templates/category_form_view.php';

} elseif ($action === 'delete' && $category_id) {
     // --- Process Delete Request ---
     // Verify CSRF token from GET request
     if (!isset($_GET['token']) || !verify_csrf_token($_GET['token'])) {
         $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Invalid security token. Deletion aborted.'];
     } else {
        try {
            // Optional: Check if protocols are using this category first
            $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM protocols WHERE category_id = ?");
            $stmt_check->execute([$category_id]);
            $protocol_count = $stmt_check->fetchColumn();

            if ($protocol_count > 0) {
                 $_SESSION['flash_message'] = ['type' => 'danger', 'text' => "Cannot delete category: {$protocol_count} protocol(s) are still assigned to it."];
            } else {
                $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
                $stmt->execute([$category_id]);
                if ($stmt->rowCount() > 0) {
                    $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Category deleted successfully.'];
                } else {
                    $_SESSION['flash_message'] = ['type' => 'warning', 'text' => 'Category not found or already deleted.'];
                }
            }
        } catch (PDOException $e) {
            error_log("Category Delete Error: " . $e->getMessage());
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Error deleting category. Check logs.'];
        }
     }
     // Redirect back to the list view regardless of outcome
     header('Location: categories.php?action=list');
     exit;

} else { // Default: 'list' action
    // --- Show List View ---
    $page_title = "Manage Categories";
    $categories = [];
    try {
        // Fetch all categories, ordered
        $stmt = $pdo->query("SELECT * FROM categories ORDER BY category_number ASC, name ASC");
        $categories = $stmt->fetchAll();
    } catch (PDOException $e) {
         error_log("Category List Fetch Error: " . $e->getMessage());
         $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Error fetching category list.'];
         // Show an empty list or error message in the view
    }
    // Prepare to display the list template
    $view_to_include = __DIR__ . '/templates/category_list_view.php';
}

// --- Include Header ---
include __DIR__ . '/templates/admin_header.php';

// --- Include the determined view ---
if (isset($view_to_include) && file_exists($view_to_include)) {
    include $view_to_include; // This view will use $categories, $category_data, $errors, $csrf_token etc.
} else {
    echo "<div class='alert alert-danger'>Error: View file not found for action '{$action}'.</div>";
}

// --- Include Footer ---
include __DIR__ . '/templates/admin_footer.php';
?>