<?php
// protocol.php - Displays a single protocol

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$protocol_identifier = filter_input(INPUT_GET, 'id', FILTER_DEFAULT); // Can be ID or Number

if (!$protocol_identifier) {
    header("Location: index.php");
    exit;
}

$protocol = get_protocol($pdo, $protocol_identifier);
$steps = [];

if ($protocol) {
    $page_title = escape($protocol['protocol_number'] ? $protocol['protocol_number'] . '. ' : '') . escape($protocol['title']);
    $steps = get_protocol_steps($pdo, $protocol['protocol_id']);
} else {
    $page_title = "Protocol Not Found";
    // header("HTTP/1.0 404 Not Found");
}

include __DIR__ . '/templates/header.php';
include __DIR__ . '/templates/protocol_view.php'; // Pass $protocol and $steps
include __DIR__ . '/templates/footer.php';

?>