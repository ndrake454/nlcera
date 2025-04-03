<?php
// admin/templates/category_form_view.php
// Expects $page_title, $category_data array, $errors array, $csrf_token string, $action string ('add' or 'edit')
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?php echo $page_title; ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
         <a href="categories.php?action=list" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to List
        </a>
    </div>
</div>

<?php if (!empty($errors['general'])): ?>
    <div class="alert alert-danger"><?php echo escape($errors['general']); ?></div>
<?php endif; ?>


<form action="categories.php" method="post">
    <?php // Include hidden field for category ID when editing ?>
    <?php if ($action === 'edit' && !empty($category_data['category_id'])): ?>
        <input type="hidden" name="category_id" value="<?php echo (int)$category_data['category_id']; ?>">
    <?php endif; ?>

     <?php // CSRF Token ?>
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

    <div class="row">
        <div class="col-md-8">
             <div class="mb-3">
                 <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                 <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo escape($category_data['name'] ?? ''); ?>" required>
                 <?php if (isset($errors['name'])): ?>
                     <div class="invalid-feedback"><?php echo escape($errors['name']); ?></div>
                 <?php endif; ?>
             </div>

            <div class="mb-3">
                 <label for="category_number" class="form-label">Category Number (Optional, 4 digits)</label>
                 <input type="text" class="form-control <?php echo isset($errors['category_number']) ? 'is-invalid' : ''; ?>" id="category_number" name="category_number" value="<?php echo escape($category_data['category_number'] ?? ''); ?>" pattern="\d{4}" title="Enter exactly 4 digits or leave blank">
                <div class="form-text">Leave blank if this category doesn't have a number (e.g., Appendices).</div>
                 <?php if (isset($errors['category_number'])): ?>
                     <div class="invalid-feedback"><?php echo escape($errors['category_number']); ?></div>
                 <?php endif; ?>
             </div>

            <div class="mb-3">
                 <label for="description" class="form-label">Description (Optional)</label>
                 <textarea class="form-control" id="description" name="description" rows="3"><?php echo escape($category_data['description'] ?? ''); ?></textarea>
             </div>

            <div class="mb-3">
                <label for="icon_class" class="form-label">Icon Class (e.g., bi-heart-fill) <span class="text-danger">*</span></label>
                <div class="input-group">
                     <span class="input-group-text"><i id="icon-preview" class="<?php echo escape($category_data['icon_class'] ?? 'bi-archive-fill'); ?>"></i></span>
                     <input type="text" class="form-control <?php echo isset($errors['icon_class']) ? 'is-invalid' : ''; ?>" id="icon_class" name="icon_class" value="<?php echo escape($category_data['icon_class'] ?? 'bi-archive-fill'); ?>" required list="common-icons">
                </div>
                <div class="form-text">Find icons at <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a>. Use the full class name.</div>
                <?php if (isset($errors['icon_class'])): ?>
                     <div class="invalid-feedback"><?php echo escape($errors['icon_class']); ?></div>
                 <?php endif; ?>

                 <?php // Optional: Datalist for common icons ?>
                 <datalist id="common-icons">
                     <option value="bi-archive-fill">
                     <option value="bi-briefcase-fill">
                     <option value="bi-bandaid-fill">
                     <option value="bi-globe">
                     <option value="bi-lungs-fill">
                     <option value="bi-heart-pulse-fill">
                     <option value="bi-clipboard2-pulse-fill">
                     <option value="bi-thermometer-half">
                     <option value="bi-person-badge-fill">
                     <option value="bi-person-hearts">
                     <option value="bi-hospital-fill">
                     <option value="bi-capsule-pill">
                     <option value="bi-people-fill">
                     <option value="bi-speedometer2">
                     <option value="bi-journal-text">
                     <option value="bi-tools">
                     <option value="bi-calculator-fill">
                     <option value="bi-card-checklist">
                 </datalist>
             </div>

             <button type="submit" class="btn btn-primary">
                 <i class="bi bi-check-circle-fill me-1"></i>
                 <?php echo ($action === 'edit') ? 'Update Category' : 'Add Category'; ?>
             </button>
             <a href="categories.php?action=list" class="btn btn-secondary">Cancel</a>

        </div>
    </div>

</form>

<script>
    // Simple live icon preview
    const iconInput = document.getElementById('icon_class');
    const iconPreview = document.getElementById('icon-preview');
    if (iconInput && iconPreview) {
        iconInput.addEventListener('input', function() {
            // Basic sanitization: remove anything not typical in a class name
            const safeClass = this.value.replace(/[^a-zA-Z0-9\-\s]/g, '');
            // Update preview (remove old bi-* classes first)
            const existingClasses = Array.from(iconPreview.classList);
            existingClasses.forEach(cls => {
                if(cls.startsWith('bi-')) {
                    iconPreview.classList.remove(cls);
                }
            });
            // Add new class if it seems valid
            if (safeClass.startsWith('bi-')) {
                 iconPreview.classList.add(safeClass);
            } else {
                iconPreview.classList.add('bi-question-circle'); // Default if invalid format
            }
        });
    }
</script>