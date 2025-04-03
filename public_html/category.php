<?php
// category.php - Displays protocols within a category

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$category_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$category_number = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING); // Allow getting by number too

if (!$category_id && !$category_number) {
    header("Location: index.php"); // Redirect if no ID/Number provided
    exit;
}

$category = null;
$protocols = [];

// Fetch category info
if ($category_id) {
     $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_id = ?");
     $stmt->execute([$category_id]);
     $category = $stmt->fetch();
} elseif ($category_number) {
     $stmt = $pdo->prepare("SELECT * FROM categories WHERE category_number = ?");
     $stmt->execute([$category_number]);
     $category = $stmt->fetch();
     if ($category) $category_id = $category['category_id']; // Get the ID if found by number
}


if ($category) {
    $page_title = escape($category['name']) . " Protocols";
    // Fetch protocols for this category
    $stmt = $pdo->prepare("SELECT protocol_id, protocol_number, title FROM protocols WHERE category_id = ? ORDER BY protocol_number ASC, title ASC");
    $stmt->execute([$category['category_id']]);
    $protocols = $stmt->fetchAll();
} else {
     $page_title = "Category Not Found";
     // Optional: Set a 404 header
     // header("HTTP/1.0 404 Not Found");
}


include __DIR__ . '/templates/header.php';
include __DIR__ . '/templates/category_view.php'; // Pass $category and $protocols
include __DIR__ . '/templates/footer.php';

?>