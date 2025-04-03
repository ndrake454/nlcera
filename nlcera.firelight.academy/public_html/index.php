<?php
// index.php - Main entry point & router (Frontend Homepage)

// Include Database Configuration and Connection
require_once __DIR__ . '/includes/db.php'; // Provides $pdo

// *** ADDED: Include functions file ***
require_once __DIR__ . '/includes/functions.php'; // Provides get_all_categories() and escape()

// --- Get Data for Homepage ---
$page_title = 'Protocol Categories'; // Set the page title for the header
$categories = []; // Initialize empty array

// Fetch categories from the database
// Check if $pdo was successfully created in db.php
if (isset($pdo)) {
    $categories = get_all_categories($pdo);
} else {
    // Handle case where DB connection failed earlier
    // You might set a flash message or just show an empty state
    error_log("Database connection (\$pdo) not available in index.php");
}

// --- Render Page ---
// Include Header
include __DIR__ . '/templates/header.php'; // Uses $page_title

// Include the specific view for the homepage (displays categories)
include __DIR__ . '/templates/home_view.php'; // Uses $categories

// Include Footer
include __DIR__ . '/templates/footer.php';

?>