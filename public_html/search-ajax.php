<?php
// Prevent ANY caching - both browser and service worker
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header('Content-Type: text/html; charset=utf-8');

// Include required files
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

// Set page title and active tab
$page_title = 'AJAX Search';
$active_tab = '';

// Get the timestamp for cache busting
$timestamp = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Northern Colorado EMS Protocols</title>
    
    <!-- No service worker -->
    <script>
    // Attempt to unregister service worker for this page only
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistrations().then(registrations => {
            for (let registration of registrations) {
                registration.unregister().then(success => {
                    console.log('Service worker unregistered for search page');
                });
            }
        });
    }
    </script>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css">
    <style>
        :root {
            --primary-color: #106e9e;
            --secondary-color: #0c5578;
        }
        
        body {
            background-color: #E5E4E2;
            padding-top: 122px;
        }
        
        .header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem 0;
        }
        
        .header a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
        }
        
        .header a:hover {
            color: white;
        }
        
        .header-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .protocol-list-item {
            border: 3px solid var(--primary-color);
            border-radius: 0.75rem;
            transition: all 0.3s ease;
            background-color: #fff;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 1rem !important;
            padding: 1.25rem !important;
            display: block;
            color: inherit;
            text-decoration: none;
        }
        
        .protocol-list-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.12);
            background-color: #f8fbff;
            text-decoration: none;
            color: inherit;
        }
        
        #loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: var(--primary-color);
            padding: 0.75rem 1rem;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            font-weight: 500;
            border-bottom: 3px solid var(--primary-color);
            background-color: transparent;
        }
        
        .footer {
            background-color: #343a40;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header-wrapper">
        <header class="header">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0">
                            <a href="index.php">Colorado</a>
                        </h1>
                        <p class="mb-0">Prehospital Protocols</p>
                    </div>
                    <div>
                        <a href="search-ajax.php" class="btn btn-light btn-search">
                            <i class="ti ti-search"></i>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Navigation -->
        <nav class="bg-white shadow-sm">
            <div class="container">
                <ul class="nav nav-tabs border-bottom-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="ti ti-file-text me-1"></i> Protocols
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="medications.php">
                            <i class="ti ti-pill me-1"></i> Meds
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tools.php">
                            <i class="ti ti-tools me-1"></i> Tools
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="py-4">
        <div class="container">
            <header class="mb-4">
                <h1>Search Results</h1>
                <p class="lead">Search feature requires online connectivity.</p>
            </header>

            <div class="mb-4">
                <div class="d-flex">
                    <input type="text" id="search-input" class="form-control me-2" placeholder="Search protocols..." autofocus>
                    <button type="button" id="search-button" class="btn btn-primary">
                        <i class="ti ti-search"></i> Search
                    </button>
                </div>
            </div>
            
            <div id="loading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Searching...</p>
            </div>

            <div id="search-results">
                <div class="alert alert-info">
                    Enter a search term above to find protocols.
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i> Back to Protocols
                </a>
            </div>
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
                        <li><a href="about.php" class="text-white">About</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-4 pt-4 border-top border-secondary text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> Northern Colorado EMS. All rights reserved.</p>
                <p class="small mb-0">Last updated: <?= date('F d, Y') ?></p>
                <p class="small mb-0">Version: <?= $timestamp ?></p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const searchResults = document.getElementById('search-results');
        const loadingElement = document.getElementById('loading');
        
        // Function to perform search via AJAX
        function performSearch() {
            const query = searchInput.value.trim();
            
            if (!query) {
                searchResults.innerHTML = `
                    <div class="alert alert-info">
                        Enter a search term above to find protocols.
                    </div>
                `;
                return;
            }
            
            // Show loading spinner
            loadingElement.style.display = 'block';
            searchResults.style.display = 'none';
            
            // Create a timestamp to prevent caching
            const timestamp = new Date().getTime();
            
            // Make AJAX request
            fetch(`search-ajax-processor.php?q=${encodeURIComponent(query)}&_t=${timestamp}`, {
                method: 'GET',
                headers: {
                    'Cache-Control': 'no-cache, no-store, must-revalidate',
                    'Pragma': 'no-cache',
                    'Expires': '0'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Hide loading spinner
                loadingElement.style.display = 'none';
                searchResults.style.display = 'block';
                
                if (!data.protocols || (data.protocols.length === 0 && data.sections.length === 0)) {
                    searchResults.innerHTML = `
                        <div class="alert alert-warning">
                            No results found for <strong>${query}</strong>.
                        </div>
                    `;
                    return;
                }
                
                // Build results HTML
                let resultsHtml = '';
                
                // Protocol matches
                if (data.protocols && data.protocols.length > 0) {
                    resultsHtml += `
                        <h4>Protocol Matches (${data.protocols.length})</h4>
                        <div class="list-group mb-4">
                    `;
                    
                    data.protocols.forEach(protocol => {
                        resultsHtml += `
                            <a href="protocol.php?id=${protocol.id}&_t=${timestamp}" 
                               class="list-group-item list-group-item-action protocol-list-item p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-1">${protocol.protocol_number}. ${protocol.title}</h5>
                                    <span class="badge bg-primary rounded-pill">
                                        <i class="ti ti-arrow-right"></i>
                                    </span>
                                </div>
                                <div class="mb-1 text-muted">
                                    Category: ${protocol.category_number}. ${protocol.category_name}
                                </div>
                                ${protocol.description ? `<p class="mb-1">${protocol.description}</p>` : ''}
                            </a>
                        `;
                    });
                    
                    resultsHtml += '</div>';
                }
                
                // Section matches
                if (data.sections && data.sections.length > 0) {
                    resultsHtml += `
                        <h4>Section Matches (${data.sections.length})</h4>
                        <div class="list-group">
                    `;
                    
                    data.sections.forEach(section => {
                        let snippet = '';
                        if (section.snippet) {
                            snippet = `<p class="mb-1">${section.snippet}</p>`;
                        }
                        
                        resultsHtml += `
                            <a href="protocol.php?id=${section.protocol_id}&_t=${timestamp}" 
                               class="list-group-item list-group-item-action protocol-list-item p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-1">${section.protocol_number}. ${section.protocol_title}</h5>
                                    <span class="badge bg-primary rounded-pill">
                                        <i class="ti ti-arrow-right"></i>
                                    </span>
                                </div>
                                <div class="mb-1 text-muted">
                                    Category: ${section.category_name} | Section: ${section.title}
                                </div>
                                ${snippet}
                            </a>
                        `;
                    });
                    
                    resultsHtml += '</div>';
                }
                
                searchResults.innerHTML = resultsHtml;
            })
            .catch(error => {
                console.error('Search error:', error);
                loadingElement.style.display = 'none';
                searchResults.style.display = 'block';
                searchResults.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="ti ti-alert-triangle me-2"></i>
                        Error performing search. Please try again.
                    </div>
                `;
            });
        }
        
        // Event listeners
        searchButton.addEventListener('click', performSearch);
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    });
    </script>
</body>
</html>