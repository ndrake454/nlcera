   <!-- Page content ends here -->
</div><!-- /.container -->

<footer class="footer mt-auto py-3 bg-dark text-light">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h5><?php echo escape(SITE_NAME); ?></h5>
                <p class="text-muted small">Serving our communities with evidence-based prehospital care.</p>
                 <p class="text-muted small">Last Updated: <?php echo date('F d, Y'); // Update this dynamically later if needed ?></p>
            </div>
            <div class="col-md-4 mb-3">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo BASE_URL; ?>/" class="link-light text-decoration-none">Protocols</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/category.php?id=9000" class="link-light text-decoration-none">Medications</a></li>
                     <li><a href="<?php echo BASE_URL; ?>/tools.php" class="link-light text-decoration-none">Tools</a></li>
                    <li><a href="#" class="link-light text-decoration-none">Contact Medical Direction</a></li>
                    <li><a href="#" class="link-light text-decoration-none">Protocol Feedback</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-3">
                <h5>Protocol Resources</h5>
                <ul class="list-unstyled">
                    <li><a href="#" class="link-light text-decoration-none">Offline PDF</a></li>
                    <li><a href="#" class="link-light text-decoration-none">Pocket Guide</a></li>
                    <li><a href="#" class="link-light text-decoration-none">Mobile App</a></li>
                     <li><a href="<?php echo BASE_URL; ?>/admin/" class="link-light text-decoration-none">Admin Login</a></li>
                </ul>
            </div>
        </div>
        <hr>
        <p class="text-center text-muted small">© <?php echo date('Y'); ?> <?php echo escape(SITE_NAME); ?>. All rights reserved.</p>
    </div>
</footer>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
<!-- Custom JS -->
<script src="<?php echo BASE_URL; ?>/assets/js/script.js"></script>
</body>
</html>