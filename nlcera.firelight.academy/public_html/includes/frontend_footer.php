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
                        <li><a href="index.php" class="text-white">Protocols</a></li>
                        <li><a href="medications.php" class="text-white">Medications</a></li>
                        <li><a href="tools.php" class="text-white">Tools</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Resources</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white">Offline PDF</a></li>
                        <li><a href="#" class="text-white">Pocket Guide</a></li>
                        <li><a href="#" class="text-white">Mobile App</a></li>
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

</body>
</html>