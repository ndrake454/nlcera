<?php
// admin/protocol_steps.php - Controller for Editing Protocol Steps

require_once __DIR__ . '/includes/auth_check.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php'; // escape() if needed

// --- CSRF Token ---
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];
// --- End CSRF ---

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING) ?? 'edit'; // Default is edit
$protocol_id = filter_input(INPUT_GET, 'protocol_id', FILTER_VALIDATE_INT);

$protocol = null;
$steps = [];
$page_title = "Edit Protocol Steps";
$errors = [];

if (!$protocol_id) {
    $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'No Protocol ID provided.'];
    header('Location: protocols.php');
    exit;
}

// Fetch Protocol Info
try {
    $stmt = $pdo->prepare("SELECT protocol_id, title, protocol_number FROM protocols WHERE protocol_id = ?");
    $stmt->execute([$protocol_id]);
    $protocol = $stmt->fetch();

    if (!$protocol) {
        $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Protocol not found.'];
        header('Location: protocols.php');
        exit;
    }
    $page_title = "Edit Steps: " . escape($protocol['protocol_number'] ? $protocol['protocol_number'].'. ' : '') . escape($protocol['title']);

    // Fetch existing steps for this protocol, ordered primarily by parent, then order
    // Fetching flat is easier, JavaScript will handle nesting representation
    $stmt_steps = $pdo->prepare("SELECT * FROM protocol_steps WHERE protocol_id = ? ORDER BY parent_step_id ASC, step_order ASC");
    $stmt_steps->execute([$protocol_id]);
    $steps = $stmt_steps->fetchAll();

} catch (PDOException $e) {
    error_log("Error fetching protocol/steps for editor: " . $e->getMessage());
    $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Error loading protocol data.'];
    header('Location: protocols.php'); // Redirect back if loading fails
    exit;
}

// --- Include Header ---
// We pass $protocol and $steps to the view via variables
include __DIR__ . '/templates/admin_header.php';

// --- Include the view ---
include __DIR__ . '/templates/protocol_steps_edit_view.php';

// --- Include Footer ---
include __DIR__ . '/templates/admin_footer.php';

?>