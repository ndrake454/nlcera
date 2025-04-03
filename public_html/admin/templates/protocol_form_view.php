<?php
// admin/templates/protocol_form_view.php
// Expects $page_title, $protocol_data array, $categories array, $errors array, $csrf_token string, $action string ('add' or 'edit')
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo $page_title; ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
         <a href="protocols.php?action=list" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Protocol List
        </a>
    </div>
</div>

<?php if (!empty($errors['general'])): ?>
    <div class="alert alert-danger"><?php echo escape($errors['general']); ?></div>
<?php endif; ?>

<form action="protocols.php" method="post">
    <?php // Include hidden field for protocol ID when editing ?>
    <?php if ($action === 'edit' && !empty($protocol_data['protocol_id'])): ?>
        <input type="hidden" name="protocol_id" value="<?php echo (int)$protocol_data['protocol_id']; ?>">
    <?php endif; ?>

     <?php // CSRF Token ?>
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

    <div class="row">
        <div class="col-md-8">
             <div class="mb-3">
                 <label for="title" class="form-label">Protocol Title <span class="text-danger">*</span></label>
                 <input type="text" class="form-control <?php echo isset($errors['title']) ? 'is-invalid' : ''; ?>" id="title" name="title" value="<?php echo escape($protocol_data['title'] ?? ''); ?>" required>
                 <?php if (isset($errors['title'])): ?>
                     <div class="invalid-feedback"><?php echo escape($errors['title']); ?></div>
                 <?php endif; ?>
             </div>

            <div class="mb-3">
                 <label for="protocol_number" class="form-label">Protocol Number (Optional, 4 digits)</label>
                 <input type="text" class="form-control <?php echo isset($errors['protocol_number']) ? 'is-invalid' : ''; ?>" id="protocol_number" name="protocol_number" value="<?php echo escape($protocol_data['protocol_number'] ?? ''); ?>" pattern="\d{4}" title="Enter exactly 4 digits or leave blank">
                <div class="form-text">Unique 4-digit number, or leave blank.</div>
                 <?php if (isset($errors['protocol_number'])): ?>
                     <div class="invalid-feedback"><?php echo escape($errors['protocol_number']); ?></div>
                 <?php endif; ?>
             </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Category (Optional)</label>
                <select class="form-select <?php echo isset($errors['category_id']) ? 'is-invalid' : ''; ?>" id="category_id" name="category_id">
                    <option value="">-- Select Category --</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo $cat['category_id']; ?>"
                                <?php echo (isset($protocol_data['category_id']) && $protocol_data['category_id'] == $cat['category_id']) ? 'selected' : ''; ?>>
                                <?php echo escape($cat['category_number'] ? $cat['category_number'].'. ' : '') . escape($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                     <?php else: ?>
                         <option value="" disabled>No categories available</option>
                    <?php endif; ?>
                </select>
                 <?php if (isset($errors['category_id'])): ?>
                     <div class="invalid-feedback"><?php echo escape($errors['category_id']); ?></div>
                 <?php endif; ?>
                 <div class="form-text">Assign this protocol to a category.</div>
            </div>

             <button type="submit" class="btn btn-primary">
                 <i class="bi bi-check-circle-fill me-1"></i>
                 <?php echo ($action === 'edit') ? 'Update Protocol Info' : 'Add Protocol & Continue'; ?>
             </button>
             <a href="protocols.php?action=list" class="btn btn-secondary">Cancel</a>

        </div>
        <div class="col-md-4">
            <?php if ($action === 'edit' && !empty($protocol_data['protocol_id'])): ?>
                 <div class="card mt-4 mt-md-0">
                     <div class="card-header">Manage Steps</div>
                     <div class="card-body">
                         <p>Edit the steps and flow for this protocol.</p>
                         <a href="protocol_steps.php?action=edit&protocol_id=<?php echo (int)$protocol_data['protocol_id']; ?>" class="btn btn-warning w-100">
                             <i class="bi bi-list-nested"></i> Edit Protocol Steps
                         </a>
                         <hr>
                         <p class="small text-muted">Created: <?php echo date('Y-m-d H:i', strtotime($protocol_data['created_at'])); ?></p>
                         <p class="small text-muted">Last Updated: <?php echo date('Y-m-d H:i', strtotime($protocol_data['last_updated'])); ?></p>
                     </div>
                 </div>
             <?php endif; ?>
        </div>
    </div>

</form>