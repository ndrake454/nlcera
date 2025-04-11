<?php
/**
 * Protocol Content Template
 * Displays the content of a protocol
 * Used by both the public view and admin preview
 * 
 * Place this file in: /includes/protocol_content.php
 */
?>

<div class="protocol-header bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-9">
                <h1 class="mb-1"><?= $protocol['protocol_number'] ?>. <?= $protocol['title'] ?></h1>
                <?php if (!empty($protocol['description'])): ?>
                    <p class="mb-0"><?= $protocol['description'] ?></p>
                <?php endif; ?>
            </div>
            <div class="col-md-3 text-md-end mt-3 mt-md-0">
                <a href="category.php?id=<?= $category['id'] ?>" class="btn btn-light">
                    <i class="ti ti-arrow-left"></i> Back to Protocols
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <?php if (empty($sections)): ?>
        <div class="alert alert-info mt-4">
            This protocol is currently being developed. Check back soon.
        </div>
    <?php else: ?>
        <?php foreach ($sections as $section): ?>
            <?php 
            // Get decision branches if section is a decision
            $branches = [];
            if ($section['section_type'] === 'decision') {
                $branches = get_decision_branches($section['id']);
            }
            
            // Parse skill levels
            $skill_levels = !empty($section['skill_levels']) ? json_decode($section['skill_levels'], true) : [];
            ?>
            
            <?php if ($section['section_type'] === 'red_arrow'): ?>
                <!-- Red Arrow gets special treatment - just render the content directly -->
                <div class="red-arrow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-down-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.5 4.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z"/>
                    </svg>
                </div>
            <?php else: ?>
                <div class="protocol-section">
                    <div class="section-header section-header-<?= $section['section_type'] ?>">
                        <?php if ($section['section_type'] === 'entry_point'): ?>
                            <i class="ti ti-arrow-bar-to-down"></i>
                        <?php elseif ($section['section_type'] === 'treatment'): ?>
                            <i class="ti ti-first-aid-kit"></i>
                        <?php elseif ($section['section_type'] === 'decision'): ?>
                            <i class="ti ti-git-branch"></i>
                        <?php elseif ($section['section_type'] === 'note'): ?>
                            <i class="ti ti-note"></i>
                        <?php elseif ($section['section_type'] === 'reference'): ?>
                            <i class="ti ti-book"></i>
                        <?php elseif ($section['section_type'] === 'indications'): ?>
                            <i class="ti ti-checkbox"></i>
                        <?php elseif ($section['section_type'] === 'contraindications'): ?>
                            <i class="ti ti-ban"></i>
                        <?php elseif ($section['section_type'] === 'side_effects'): ?>
                            <i class="ti ti-alert-triangle"></i>
                        <?php elseif ($section['section_type'] === 'precautions'): ?>
                            <i class="ti ti-shield-check"></i>
                        <?php elseif ($section['section_type'] === 'technique'): ?>
                            <i class="ti ti-tools"></i>
                        <?php endif; ?>
                        
                        <?= $section['title'] ?>
                        
                        <?php if (!empty($skill_levels)): ?>
                            <div class="skill-pills-container">
                                <?php foreach ($skill_levels as $level): ?>
                                    <span class="skill-pill skill-<?= strtolower($level) ?>"><?= $level ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="section-body">
                        <?= $section['content'] ?>
                        
                        <?php if ($section['section_type'] === 'decision' && !empty($branches)): ?>
                            <div class="mt-4">
                                <div class="row">
                                    <?php foreach ($branches as $branch): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="<?= $branch['label'] === 'YES' ? 'decision-yes' : 'decision-no' ?>">
                                                <div class="decision-header">
                                                    <?= $branch['label'] ?>
                                                </div>
                                                <div>
                                                    <?= $branch['outcome'] ?>
                                                    
                                                    <?php if ($branch['target_section_id']): ?>
                                                        <div class="mt-2">
                                                            <a href="#section-<?= $branch['target_section_id'] ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="ti ti-arrow-right"></i> Go to Section
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>