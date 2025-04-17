<?php
/**
 * Search Page
 * 
 * Place this file in: /search.php
 */

// Include required files
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Get search query
$query = isset($_GET['q']) ? trim($_GET['q']) : '';

// Set page title and active tab
$page_title = 'Search Results';
$active_tab = '';

// Initialize results
$results = [];

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
    
    // Combine results
    $results = [
        'protocols' => $protocol_results,
        'sections' => $section_results
    ];
}

// Include header
include 'includes/frontend_header.php';
?>

<header class="mb-4">
    <h1>Search Results</h1>
    <p class="lead">
        <?php if (empty($query)): ?>
            Please enter a search term
        <?php else: ?>
            Results for: <strong><?= htmlspecialchars($query) ?></strong>
        <?php endif; ?>
    </p>
</header>

<div class="mb-4">
    <form action="search.php" method="GET" class="d-flex">
        <input type="text" name="q" class="form-control me-2" placeholder="Search protocols..." value="<?= htmlspecialchars($query) ?>">
        <button type="submit" class="btn btn-primary">
            <i class="ti ti-search"></i> Search
        </button>
    </form>
</div>

<?php if (empty($query)): ?>
    <div class="alert alert-info">
        Enter a search term above to find protocols.
    </div>
<?php elseif (empty($results['protocols']) && empty($results['sections'])): ?>
    <div class="alert alert-warning">
        No results found for <strong><?= htmlspecialchars($query) ?></strong>.
    </div>
<?php else: ?>
    <!-- Protocol Matches -->
    <?php if (!empty($results['protocols'])): ?>
        <h4>Protocol Matches (<?= count($results['protocols']) ?>)</h4>
        <div class="list-group mb-4">
            <?php foreach ($results['protocols'] as $protocol): ?>
                <a href="protocol.php?id=<?= $protocol['id'] ?>" class="list-group-item list-group-item-action protocol-list-item p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-1"><?= $protocol['protocol_number'] ?>. <?= $protocol['title'] ?></h5>
                        <span class="badge bg-primary rounded-pill">
                            <i class="ti ti-arrow-right"></i>
                        </span>
                    </div>
                    <div class="mb-1 text-muted">
                        Category: <?= $protocol['category_number'] ?>. <?= $protocol['category_name'] ?>
                    </div>
                    <?php if (!empty($protocol['description'])): ?>
                        <p class="mb-1"><?= $protocol['description'] ?></p>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <!-- Section Content Matches -->
    <?php if (!empty($results['sections'])): ?>
        <h4>Section Matches (<?= count($results['sections']) ?>)</h4>
        <div class="list-group">
            <?php foreach ($results['sections'] as $section): ?>
                <a href="protocol.php?id=<?= $section['protocol_id'] ?>" class="list-group-item list-group-item-action protocol-list-item p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-1"><?= $section['protocol_number'] ?>. <?= $section['protocol_title'] ?></h5>
                        <span class="badge bg-primary rounded-pill">
                            <i class="ti ti-arrow-right"></i>
                        </span>
                    </div>
                    <div class="mb-1 text-muted">
                        Category: <?= $section['category_name'] ?> | Section: <?= $section['title'] ?>
                    </div>
                    <?php 
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
                        
                        echo "<p class=\"mb-1\">$snippet</p>";
                    }
                    ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php
// Include footer
include 'includes/frontend_footer.php';
?>