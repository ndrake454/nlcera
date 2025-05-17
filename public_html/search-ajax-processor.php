<?php
/**
 * AJAX Search Processor
 * This processes search requests and returns JSON
 */

// Prevent ANY caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: application/json");

// Include required files
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get search query
$query = isset($_GET['q']) ? trim($_GET['q']) : '';
$response = [
    'success' => true,
    'query' => $query,
    'protocols' => [],
    'sections' => []
];

// Perform search if query is not empty
if (!empty($query)) {
    // Sanitize the query for use in SQL LIKE
    $search_term = '%' . str_replace(['%', '_'], ['\%', '\_'], $query) . '%';
    
    // Search in protocols
    $protocol_results = db_get_rows(
        "SELECT p.*, c.title as category_name, c.category_number 
         FROM protocols p
         JOIN categories c ON p.category_id = c.id
         WHERE (p.title LIKE ? OR p.description LIKE ? OR p.protocol_number LIKE ?)
         AND p.is_active = 1
         ORDER BY p.protocol_number ASC",
        [$search_term, $search_term, $search_term]
    );
    
    $response['protocols'] = $protocol_results;
    
    // Search in protocol sections (content)
    $section_results = db_get_rows(
        "SELECT ps.*, p.id as protocol_id, p.title as protocol_title, p.protocol_number, c.title as category_name
         FROM protocol_sections ps
         JOIN protocols p ON ps.protocol_id = p.id
         JOIN categories c ON p.category_id = c.id
         WHERE (ps.content LIKE ? OR ps.title LIKE ?)
         AND p.is_active = 1
         ORDER BY p.protocol_number ASC, ps.display_order ASC",
        [$search_term, $search_term]
    );
    
    // Process sections to add snippets
    foreach ($section_results as &$section) {
        // Extract a snippet of content that includes the search term
        $content = strip_tags($section['content']);
        $pos = stripos($content, $query);
        if ($pos !== false) {
            $start = max(0, $pos - 50);
            $length = strlen($query) + 100;
            $snippet = substr($content, $start, $length);
            if ($start > 0) $snippet = '...' . $snippet;
            if ($start + $length < strlen($content)) $snippet .= '...';
            
            // Highlight the search term
            $snippet = preg_replace('/(' . preg_quote($query, '/') . ')/i', '<mark>$1</mark>', $snippet);
            
            $section['snippet'] = $snippet;
        }
    }
    
    $response['sections'] = $section_results;
}

// Output the JSON response
echo json_encode($response);