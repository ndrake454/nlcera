<?php
/**
 * Common Functions
 * Shared utility functions for the application
 * 
 * Place this file in: /includes/functions.php
 */

require_once 'config.php';
require_once 'db.php';

/**
 * Sanitize input data
 * 
 * @param string $input Input to sanitize
 * @return string Sanitized input
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Get all protocol categories
 * 
 * @param bool $includeInactive Whether to include inactive categories
 * @return array Array of categories
 */
function get_all_categories($includeInactive = false) {
    return db_get_rows(
        "SELECT * FROM categories ORDER BY display_order ASC"
    );
}

/**
 * Get a single category by ID
 * 
 * @param int $id Category ID
 * @return array|null Category data or null if not found
 */
function get_category($id) {
    return db_get_row(
        "SELECT * FROM categories WHERE id = ?",
        [$id]
    );
}

/**
 * Get all protocols for a category
 * 
 * @param int $categoryId Category ID
 * @param bool $includeInactive Whether to include inactive protocols
 * @return array Array of protocols
 */
function get_category_protocols($categoryId, $includeInactive = false) {
    $sql = "SELECT * FROM protocols WHERE category_id = ?";
    
    if (!$includeInactive) {
        $sql .= " AND is_active = 1";
    }
    
    $sql .= " ORDER BY protocol_number ASC";
    
    return db_get_rows($sql, [$categoryId]);
}

/**
 * Get a single protocol by ID
 * 
 * @param int $id Protocol ID
 * @return array|null Protocol data or null if not found
 */
function get_protocol($id) {
    return db_get_row(
        "SELECT p.*, c.title as category_name, c.category_number 
         FROM protocols p 
         JOIN categories c ON p.category_id = c.id 
         WHERE p.id = ?",
        [$id]
    );
}

/**
 * Get a single protocol by number
 * 
 * @param string $number Protocol number (e.g., "2030")
 * @return array|null Protocol data or null if not found
 */
function get_protocol_by_number($number) {
    return db_get_row(
        "SELECT p.*, c.title as category_name, c.category_number 
         FROM protocols p 
         JOIN categories c ON p.category_id = c.id 
         WHERE p.protocol_number = ?",
        [$number]
    );
}

/**
 * Get all sections for a protocol
 * 
 * @param int $protocolId Protocol ID
 * @return array Array of protocol sections
 */
function get_protocol_sections($protocolId) {
    return db_get_rows(
        "SELECT * FROM protocol_sections 
         WHERE protocol_id = ? 
         ORDER BY display_order ASC",
        [$protocolId]
    );
}

/**
 * Get all decision branches for a section
 * 
 * @param int $sectionId Section ID
 * @return array Array of decision branches
 */
function get_decision_branches($sectionId) {
    return db_get_rows(
        "SELECT * FROM decision_branches 
         WHERE section_id = ? 
         ORDER BY display_order ASC",
        [$sectionId]
    );
}

/**
 * Get all component templates
 * 
 * @param string|null $sectionType Filter by section type
 * @return array Array of component templates
 */
function get_component_templates($sectionType = null) {
    $sql = "SELECT * FROM component_templates";
    $params = [];
    
    if ($sectionType) {
        $sql .= " WHERE section_type = ?";
        $params[] = $sectionType;
    }
    
    $sql .= " ORDER BY title ASC";
    
    return db_get_rows($sql, $params);
}

/**
 * Format date and time
 * 
 * @param string $datetime MySQL datetime string
 * @param string $format PHP date format
 * @return string Formatted date
 */
function format_datetime($datetime, $format = 'F j, Y g:i a') {
    return date($format, strtotime($datetime));
}

/**
 * Generate a slug from a string
 * 
 * @param string $string Input string
 * @return string URL-friendly slug
 */
function slugify($string) {
    // Replace non-alphanumeric characters with hyphens
    $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower(trim($string)));
    // Remove leading/trailing hyphens
    return trim($slug, '-');
}

/**
 * Check if a string starts with a specific substring
 * 
 * @param string $haystack String to search in
 * @param string $needle String to search for
 * @return bool True if haystack starts with needle
 */
function starts_with($haystack, $needle) {
    return substr($haystack, 0, strlen($needle)) === $needle;
}
?>