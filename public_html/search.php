<?php
// search.php - Handles search queries

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$query = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_STRING);
$results = [];
$page_title = "Search Results";

if ($query && !empty(trim($query))) {
    $page_title = "Search Results for '" . escape($query) . "'";
    $search_term = '%' . $query . '%';

    // Search in protocol titles and numbers
    // Can be expanded to search step content (might be slow on large datasets without full-text indexing)
    $stmt = $pdo->prepare("SELECT p.protocol_id, p.protocol_number, p.title, c.name as category_name, c.category_id
                           FROM protocols p
                           LEFT JOIN categories c ON p.category_id = c.category_id
                           WHERE p.title LIKE ? OR p.protocol_number LIKE ?
                           ORDER BY p.protocol_number ASC, p.title ASC");
    $stmt->execute([$search_term, $search_term]);
    $results = $stmt->fetchAll();
} else {
     // Optionally redirect back or show a message if query is empty
     // header("Location: index.php");
     // exit;
}


include __DIR__ . '/templates/header.php';
include __DIR__ . '/templates/search_results_view.php'; // Pass $query and $results
include __DIR__ . '/templates/footer.php';

?>