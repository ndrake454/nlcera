<?php
// tools.php - Displays available tools

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$page_title = "Quick Tools";

// In the future, fetch tools from the `tools` database table if you create it
// For now, hardcode the example tools

$tools = [
    ['title' => 'GCS Calculator', 'link' => '#', 'icon' => 'bi-calculator-fill', 'description' => 'Glasgow Coma Scale calculator.'],
    ['title' => 'NIHSS', 'link' => '#', 'icon' => 'bi-brain', 'description' => 'NIH Stroke Scale assessment tool.'],
    ['title' => 'Intubation Checklist', 'link' => '#', 'icon' => 'bi-card-checklist', 'description' => 'Pre-intubation checklist.'],
    // Add more tools here
];


include __DIR__ . '/templates/header.php';
include __DIR__ . '/templates/tools_view.php'; // Pass $tools
include __DIR__ . '/templates/footer.php';

?>