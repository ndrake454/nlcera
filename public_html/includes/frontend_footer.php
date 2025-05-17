</div>
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Northern Colorado EMS</h5>
                    <p>Serving our communities with evidence-based prehospital care.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://nlcera.firelight.academy/index.php" class="text-white">Protocols</a></li>
                        <li><a href="https://nlcera.firelight.academy/medications.php" class="text-white">Medications</a></li>
                        <li><a href="https://nlcera.firelight.academy/tools.php" class="text-white">Tools</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Resources</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://nlcera.firelight.academy/about.php" class="text-white">About</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-4 pt-4 border-top border-secondary text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> Northern Colorado EMS. All rights reserved.</p>
                <p class="small mb-0">Last updated: <?= date('F d, Y') ?></p>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
 
    <!-- Initialize tooltips and modals -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover focus'
            });
        });
        
        // Initialize all modals
        var modals = document.querySelectorAll('.info-modal');
        modals.forEach(function(modalEl) {
            new bootstrap.Modal(modalEl);
        });
        
        // Handle info button clicks
        var infoButtons = document.querySelectorAll('.info-button');
        infoButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                var targetId = this.getAttribute('data-bs-target');
                var modalElement = document.querySelector(targetId);
                var modal = bootstrap.Modal.getInstance(modalElement);
                if (!modal) {
                    modal = new bootstrap.Modal(modalElement);
                }
                modal.show();
            });
        });
    });
    </script>

    <!-- Smooth Scrolling for Section Links - THIS IS ALWAYS ACTIVE -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Custom smooth scroll function with adjustable duration
        function smoothScrollTo(targetPosition, duration = 1000) {
            const startPosition = window.pageYOffset;
            const distance = targetPosition - startPosition;
            let startTime = null;
            
            function animation(currentTime) {
                if (startTime === null) startTime = currentTime;
                const timeElapsed = currentTime - startTime;
                const scrollProgress = Math.min(timeElapsed / duration, 1);
                
                // Easing function for smoother acceleration/deceleration
                const ease = function(t) {
                    return t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t;
                };
                
                window.scrollTo(0, startPosition + distance * ease(scrollProgress));
                
                if (timeElapsed < duration) {
                    requestAnimationFrame(animation);
                }
            }
            
            requestAnimationFrame(animation);
        }

        // Handle clicks on section links
        document.querySelectorAll('a[href^="#section-"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                
                if (targetElement) {
                    // Get the height of any fixed headers
                    const headerHeight = document.querySelector('.header-wrapper')?.offsetHeight || 0;
                    
                    // Calculate position with offset
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerHeight - 20; // 20px extra padding
                    
                    // Custom smooth scroll with 1200ms duration (slower)
                    smoothScrollTo(offsetPosition, 1200);
                }
            });
        });
        
        // Also handle direct hash links when page loads
        if (window.location.hash && window.location.hash.startsWith('#section-')) {
            setTimeout(function() {
                const targetElement = document.querySelector(window.location.hash);
                if (targetElement) {
                    const headerHeight = document.querySelector('.header-wrapper')?.offsetHeight || 0;
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerHeight - 20;
                    
                    smoothScrollTo(offsetPosition, 1200);
                }
            }, 100); // Small delay to ensure DOM is ready
        }
    });
    </script>

    <?php if (isset($_GET['debug']) && $_GET['debug'] === '1'): ?>
    <!-- CSS Debugger - ONLY RUNS IN DEBUG MODE -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Starting CSS debugging...");
        
        // Check all section headers
        document.querySelectorAll('.section-header').forEach(function(header) {
            console.log("Header classes:", header.className);
            console.log("Computed style:", getComputedStyle(header).backgroundColor, getComputedStyle(header).borderLeftColor);
            
            // Add debug info to the header
            var debugSpan = document.createElement('span');
            debugSpan.style.float = 'right';
            debugSpan.style.fontSize = '10px';
            debugSpan.textContent = '[Class: ' + header.className + ']';
            header.appendChild(debugSpan);
        });
    });
    </script>
    <?php endif; ?>
</body>
</html>