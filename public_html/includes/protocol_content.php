<?php
/**
 * Protocol Content Template - FIXED VERSION
 */
?>

<div class="protocol-header bg-primary text-white">

</div>

<div class="container">
    <?php if (empty($sections)): ?>
        <div class="alert alert-info mt-4">
            This protocol is currently being developed. Check back soon.
        </div>
    <?php else: ?>
        <?php 
        // Initialize variable to track the last section type
        $last_section_type = '';
        $join_next = false;
        ?>
        
        <?php foreach ($sections as $section): ?>
            <?php 
            // Check if this section should be joined with the previous one
            $join_next = ($last_section_type === 'join');
            
            // Get decision branches if section is a decision
            $branches = [];
            if ($section['section_type'] === 'decision') {
                $branches = get_decision_branches($section['id']);
            }
            
            // Parse skill levels
            $skill_levels = !empty($section['skill_levels']) ? json_decode($section['skill_levels'], true) : [];
            
            // THIS IS THE KEY FIX: Determine section type based on title if type is missing
            $section_type = !empty($section['section_type']) ? $section['section_type'] : 'note';
            
            // Always check title to ensure consistent type mapping
            $title = strtolower($section['title']);
            if (strpos($title, 'indication') !== false && strpos($title, 'contra') === false) {
                $section_type = 'indications';
            } else if (strpos($title, 'contraindication') !== false) {
                $section_type = 'contraindications';
            } else if (strpos($title, 'side effect') !== false) {
                $section_type = 'side_effects';
            } else if (strpos($title, 'precaution') !== false) {
                $section_type = 'precautions';
            } else if (strpos($title, 'technique') !== false) {
                $section_type = 'technique';
            } else if (strpos($title, 'direction') !== false) {
                $section_type = 'red_arrow';
            } else if (strpos($title, 'green arrow') !== false || strpos($title, 'continue') !== false) {
                $section_type = 'green_arrow';
            } else if (strpos($title, 'join') !== false) {
                $section_type = 'join';
            } else if (strpos($title, 'flowchart') !== false || strpos($title, 'diagram') !== false) {
                $section_type = 'diagram';
            } else if (!empty($section['section_type'])) {
                $section_type = $section['section_type'];
            }
            ?>
            
            <?php if ($section_type === 'red_arrow'): ?>
                <!-- Red Arrow gets special treatment - just render the content directly -->
                <div class="red-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
                    </svg>
                </div>
            <?php elseif ($section_type === 'green_arrow'): ?>
                <!-- Green Arrow gets special treatment too -->
                <div class="green-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
                    </svg>
                </div>
            <?php elseif ($section_type === 'join'): ?>
                <!-- Join component eliminates space between sections -->
                <div class="join-marker"></div>
            <?php else: ?>
                <div class="protocol-section <?= !empty($section['contact_base']) ? 'contact-base-required' : '' ?> <?= $join_next ? 'joined-section' : '' ?>" data-section-type="<?= $section_type ?>" id="section-<?= $section['id'] ?>">
                    <div class="section-header section-header-<?= $section_type ?>">
                        <?php if ($section_type === 'entry_point'): ?>
                            <i class="ti ti-arrow-bar-to-down"></i>
                        <?php elseif ($section_type === 'treatment'): ?>
                            <i class="ti ti-first-aid-kit"></i>
                        <?php elseif ($section_type === 'decision'): ?>
                            <i class="ti ti-git-branch"></i>
                        <?php elseif ($section_type === 'note'): ?>
                            <i class="ti ti-note"></i>
                        <?php elseif ($section_type === 'reference'): ?>
                            <i class="ti ti-book"></i>
                        <?php elseif ($section_type === 'indications'): ?>
                            <i class="ti ti-checkbox"></i>
                        <?php elseif ($section_type === 'contraindications'): ?>
                            <i class="ti ti-ban"></i>
                        <?php elseif ($section_type === 'side_effects'): ?>
                            <i class="ti ti-alert-triangle"></i>
                        <?php elseif ($section_type === 'precautions'): ?>
                            <i class="ti ti-shield-check"></i>
                        <?php elseif ($section_type === 'technique'): ?>
                            <i class="ti ti-tools"></i>
                        <?php elseif ($section_type === 'diagram'): ?>
                            <i class="ti ti-chart-arrows"></i>
                        <?php else: ?>
                            <i class="ti ti-note"></i>
                        <?php endif; ?>
                        
                        <?= $section['title'] ?>
                        <?php if (!empty($section['contact_base'])): ?>
                            <span class="contact-base-badge ms-2">
                                <i class="ti ti-phone-call"></i> Contact Base
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($skill_levels)): ?>
                            <div class="skill-pills-container">
                                <?php foreach ($skill_levels as $level): ?>
                                    <span class="skill-pill skill-<?= strtolower($level) ?>"><?= $level ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="section-body">
                        <?php if ($section_type === 'diagram'): ?>
                            <!-- Render diagram with viewer -->
                            <div class="diagram-container">
                                <div class="diagram-xml" style="display:none;"><?= htmlspecialchars($section['content']) ?></div>
                                <div class="diagram-placeholder">
                                    <i class="ti ti-chart-arrows" style="font-size: 3rem; color: #3572b0;"></i>
                                    <p class="mt-2">Interactive Flowchart</p>
                                    <p class="small text-muted">Loading diagram...</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <?= $section['content'] ?>
                        <?php endif; ?>
                        
                        <?php if ($section_type === 'decision' && !empty($branches)): ?>
                            <div class="mt-4">
                                <div class="row">
                                    <?php foreach ($branches as $branch): ?>
                                        <div class="col-md-6 mb-3">
                                            <?php
                                            // Determine which class to apply based on branch label
                                            $branchClass = '';
                                            if ($branch['label'] === 'YES') {
                                                $branchClass = 'decision-yes';
                                            } elseif ($branch['label'] === 'NO') {
                                                $branchClass = 'decision-no';
                                            } elseif ($branch['label'] === 'OTHER') {
                                                $branchClass = 'decision-other';
                                            } else {
                                                // Fallback for any other labels
                                                $branchClass = 'decision-no';
                                            }

                                            // Check if we need to make this a clickable link
                                            $hasTarget = !empty($branch['target_section_id']);
                                            ?>
                                            
                                            <?php if ($hasTarget): ?>
                                                <a href="#section-<?= $branch['target_section_id'] ?>" class="text-decoration-none decision-link">
                                            <?php endif; ?>
                                            
                                            <div class="<?= $branchClass ?> <?= $hasTarget ? 'decision-clickable' : '' ?>">
                                                <div class="decision-header">
                                                    <?= $branch['label'] ?>
                                                    <?php if ($hasTarget): ?>
                                                        <i class="ti ti-arrow-right float-end"></i>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <?= $branch['outcome'] ?>
                                                </div>
                                            </div>
                                            
                                            <?php if ($hasTarget): ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php 
            // Store this section type for the next iteration
            $last_section_type = $section_type; 
            ?>
            
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Script to initialize diagram viewers -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Find all diagram containers and initialize them
    function initializeDiagrams() {
        // Find all diagram containers
        document.querySelectorAll('.diagram-container').forEach(function(container) {
            const xmlElement = container.querySelector('.diagram-xml');
            if (xmlElement && typeof GraphViewer !== 'undefined') {
                const xml = xmlElement.textContent;
                
                // Create a viewer
                const viewer = document.createElement('div');
                viewer.style.width = '100%';
                viewer.style.height = '400px';
                container.appendChild(viewer);
                
                // Initialize the viewer
                try {
                    GraphViewer.createViewerForElement(viewer, {
                        highlight: '#3572b0',
                        border: 2,
                        lightbox: true,
                        nav: true,
                        zoom: true,
                        xml: xml,
                        toolbar: 'zoom layers lightbox'
                    });
                    
                    // Hide the placeholder once loaded
                    const placeholder = container.querySelector('.diagram-placeholder');
                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }
                } catch (e) {
                    console.error('Error initializing diagram viewer:', e);
                    container.innerHTML += '<div class="alert alert-danger">Failed to load diagram. <button class="btn btn-sm btn-primary reload-diagram-btn">Reload</button></div>';
                }
            }
        });
    }
    
    // Add event listener for the reload button
    document.addEventListener('click', function(event) {
        if (event.target.matches('.reload-diagram-btn')) {
            initializeDiagrams();
        }
    });
    
    // Initialize diagrams after a short delay to ensure scripts are loaded
    setTimeout(initializeDiagrams, 500);
});
</script>