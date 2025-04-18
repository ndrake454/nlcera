</div> <!-- End Main Content Container -->
    </div> <!-- End Admin Content -->
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Common Admin JavaScript -->
    <script>
    (function() {
        // Initialize Bootstrap tooltips
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
        
        // Initialize any Quill editors with the .quill-editor class
        const editorElements = document.querySelectorAll('.quill-editor');
        if (editorElements.length > 0) {
            editorElements.forEach(editor => {
                new Quill(editor, {
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            ['bold', 'italic', 'underline', 'strike'],
                            [{ 'align': [] }],
                            ['blockquote', 'code-block'],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            ['link', 'image'],
                            ['clean']
                        ]
                    },
                    placeholder: 'Compose text...',
                    theme: 'snow'
                });
            });
        }
    })();
    </script>
</body>
</html>