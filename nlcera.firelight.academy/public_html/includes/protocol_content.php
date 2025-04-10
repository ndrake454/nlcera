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
            <div class="protocol-section">
                <div class="section-header section-header-<?= $section['section_type'] ?>">
                    <div class="section-header-title">
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
                        <?php endif; ?>
                        
                        <?= $section['title'] ?>
                    </div>
                    
                                        <?php if (!empty($skill_levels)): ?>
                        <div class="skill-pills-container">
                            <?php 
                            // Define full names for tooltips
                            $fullNames = [
                                'EMR' => 'Emergency Medical Responder',
                                'EMT' => 'Emergency Medical Technician',
                                'EMT-A' => 'Emergency Medical Technician - Advanced',
                                'EMT-I' => 'Emergency Medical Technician - Intermediate',
                                'EMT-P' => 'Paramedic',
                                'EMT-CC' => 'Critical Care Paramedic',
                                'RN' => 'Registered Nurse',
                                // Legacy support
                                'AEMT' => 'Advanced Emergency Medical Technician',
                                'Intermediate' => 'Emergency Medical Technician - Intermediate',
                                'Paramedic' => 'Emergency Medical Technician - Paramedic'
                            ];
                            
                            foreach ($skill_levels as $level): 
                                $cssClass = strtolower(str_replace('-', '-', $level));
                                $tooltipText = isset($fullNames[$level]) ? $fullNames[$level] : $level;
                            ?>
                                <span class="skill-pill skill-<?= $cssClass ?>" 
                                      data-bs-toggle="tooltip" 
                                      data-bs-placement="top" 
                                      title="<?= $tooltipText ?>"><?= $level ?></span>
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
        <?php endforeach; ?>
    <?php endif; ?>
</div>