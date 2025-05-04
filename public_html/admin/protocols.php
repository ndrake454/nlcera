<?php
/**
 * Protocol Management Page (Updated for Draw.io)
 * 
 * Place this file in: /admin/protocols.php
 */

// Include required files
require_once '../includes/config.php';
require_once '../includes/db.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

// Require login
require_login();

// Set page title
$page_title = 'Protocols';

// Get selected category filter
$category_filter = isset($_GET['category']) ? intval($_GET['category']) : 0;

// Get all categories for filter dropdown
$categories = get_all_categories();

// Get protocols with category information
$params = [];
$sql = "SELECT p.*, c.title as category_name, c.category_number, u.full_name as updated_by_name,
         (SELECT COUNT(*) FROM protocol_diagrams WHERE protocol_id = p.id) as has_diagram
         FROM protocols p
         JOIN categories c ON p.category_id = c.id
         JOIN users u ON p.updated_by = u.id";

if ($category_filter > 0) {
    $sql .= " WHERE p.category_id = ?";
    $params[] = $category_filter;
}

$sql .= " ORDER BY p.protocol_number ASC";

$protocols = db_get_rows($sql, $params);

// Include header
include 'includes/header.php';
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Protocol Management</h5>
        <div>
            <a href="templates.php" class="btn btn-outline-primary me-2">
                <i class="ti ti-template"></i> Manage Templates
            </a>
            <a href="protocol_add.php" class="btn btn-primary">
                <i class="ti ti-plus"></i> Add Protocol
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <!-- Category Filter -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" class="d-flex">
                    <select class="form-select me-2" name="category" onchange="this.form.submit()">
                        <option value="0">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>" <?= $category_filter == $category['id'] ? 'selected' : '' ?>>
                                <?= $category['category_number'] ?>. <?= $category['title'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($category_filter > 0): ?>
                        <a href="protocols.php" class="btn btn-outline-secondary">Clear Filter</a>
                    <?php endif; ?>
                </form>
            </div>
            <div class="col-md-6 text-end">
                <span class="text-muted"><?= count($protocols) ?> protocols found</span>
            </div>
        </div>
        
        <?php if (empty($protocols)): ?>
            <div class="alert alert-info">
                No protocols found. <?= $category_filter > 0 ? 'Try another category or clear the filter.' : 'Create your first protocol to get started.' ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Diagram</th>
                            <th>Last Updated</th>
                            <th>Updated By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($protocols as $protocol): ?>
                            <tr>
                                <td><?= $protocol['protocol_number'] ?></td>
                                <td><?= $protocol['title'] ?></td>
                                <td><?= $protocol['category_number'] ?>. <?= $protocol['category_name'] ?></td>
                                <td>
                                    <?php if ($protocol['is_active']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($protocol['has_diagram'] > 0): ?>
                                        <span class="badge bg-info"><i class="ti ti-check"></i> Created</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning"><i class="ti ti-minus"></i> Not Created</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= format_datetime($protocol['updated_at']) ?></td>
                                <td><?= $protocol['updated_by_name'] ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="protocol_edit.php?id=<?= $protocol['id'] ?>" class="btn btn-outline-primary">
                                            <i class="ti ti-edit"></i> Edit Info
                                        </a>
                                        <a href="protocol_edit_drawio.php?id=<?= $protocol['id'] ?>" class="btn btn-outline-success">
                                            <i class="ti ti-chart-line"></i> Edit Diagram
                                        </a>
                                        <a href="../protocol.php?id=<?= $protocol['id'] ?>" target="_blank" class="btn btn-outline-secondary">
                                            <i class="ti ti-eye"></i> View
                                        </a>
                                        <a href="protocol_delete.php?id=<?= $protocol['id'] ?>" class="btn btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete this protocol?')">
                                            <i class="ti ti-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Include footer
include 'includes/footer.php';
?>