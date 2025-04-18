<?php
/**
 * PWA Diagnostics Page
 * 
 * Place this file in your public_html directory
 */

// Include header basics without including the entire header
$pageTitle = "PWA Diagnostics";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css">
    
    <style>
        :root {
            --primary-color: #106e9e;
        }
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        .result-box {
            background-color: #f0f0f0;
            border-radius: 5px;
            padding: 10px;
            margin-top: 10px;
            max-height: 300px;
            overflow-y: auto;
            white-space: pre-wrap;
            font-family: monospace;
            font-size: 12px;
        }
        .header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .test-card {
            margin-bottom: 20px;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .warning {
            color: orange;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PWA Diagnostics</h1>
        <p>This page helps diagnose PWA and service worker issues on iOS devices</p>
    </div>
    
    <div class="container">
        <div class="card test-card">
            <div class="card-header">
                <h5>Basic Diagnostics</h5>
            </div>
            <div class="card-body">
                <p>Running basic PWA diagnostics...</p>
                
                <button id="run-diagnostics" class="btn btn-primary mb-3">
                    <i class="ti ti-device-analytics"></i> Run Diagnostics
                </button>
                
                <div id="basic-results" class="result-box">Click "Run Diagnostics" to start...</div>
            </div>
        </div>
        
        <div class="card test-card">
            <div class="card-header">
                <h5>Cache Inspection</h5>
            </div>
            <div class="card-body">
                <p>See what's currently in your cache storage:</p>
                
                <button id="inspect-cache" class="btn btn-info mb-3">
                    <i class="ti ti-database"></i> Inspect Cache
                </button>
                
                <div id="cache-results" class="result-box">Click "Inspect Cache" to view cache contents...</div>
            </div>
        </div>
        
        <div class="card test-card">
            <div class="card-header">
                <h5>Service Worker Test</h5>
            </div>
            <div class="card-body">
                <p>Test if service worker is functioning correctly:</p>
                
                <button id="test-sw" class="btn btn-success mb-3">
                    <i class="ti ti-test-pipe"></i> Test Service Worker
                </button>
                
                <div id="sw-results" class="result-box">Click "Test Service Worker" to begin...</div>
            </div>
        </div>
        
        <div class="card test-card">
            <div class="card-header">
                <h5>Fix iOS Issues</h5>
            </div>
            <div class="card-body">
                <p>Try these solutions to fix iOS PWA offline issues:</p>
                
                <div class="mb-3">
                    <button id="install-basic-sw" class="btn btn-warning mb-2">
                        <i class="ti ti-refresh"></i> Install Basic Service Worker
                    </button>
                    <div class="small text-muted">Replaces your current service worker with a simpler, iOS-compatible version.</div>
                </div>
                
                <div class="mb-3">
                    <button id="clear-cache" class="btn btn-danger mb-2">
                        <i class="ti ti-trash"></i> Clear Cache Storage
                    </button>
                    <div class="small text-muted">Removes all cached content to start fresh.</div>
                </div>
                
                <div class="mb-3">
                    <button id="cache-pages" class="btn btn-success mb-2">
                        <i class="ti ti-download"></i> Cache Key Pages
                    </button>
                    <div class="small text-muted">Fetches and caches only essential pages in a way that works on iOS.</div>
                </div>
                
                <div id="fix-results" class="result-box">Select a fix option to begin...</div>
            </div>
        </div>
        
        <div class="card test-card">
            <div class="card-header">
                <h5>iOS-Specific Information</h5>
            </div>
            <div class="card-body">
                <p>iOS PWA information:</p>
                <ul>
                    <li>Browser: <span id="browser-info">Detecting...</span></li>
                    <li>Running as PWA: <span id="is-pwa">Detecting...</span></li>
                    <li>iOS Version: <span id="ios-version">Detecting...</span></li>
                    <li>WebKit Version: <span id="webkit-version">Detecting...</span></li>
                </ul>
                
                <div class="alert alert-info">
                    <p><strong>Note:</strong> Even Chrome on iOS uses the Safari WebKit engine under the hood due to Apple's restrictions.</p>
                </div>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <a href="index.php" class="btn btn-outline-primary">
                <i class="ti ti-home"></i> Return to Homepage
            </a>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Detect browser and device info
            detectEnvironment();
            
            // Set up button listeners
            document.getElementById('run-diagnostics').addEventListener('click', runDiagnostics);
            document.getElementById('inspect-cache').addEventListener('click', inspectCache);
            document.getElementById('test-sw').addEventListener('click', testServiceWorker);
            document.getElementById('install-basic-sw').addEventListener('click', installBasicServiceWorker);
            document.getElementById('clear-cache').addEventListener('click', clearCache);
            document.getElementById('cache-pages').addEventListener('click', cacheKeyPages);
        });
        
        function detectEnvironment() {
            const browserInfo = document.getElementById('browser-info');
            const isPwa = document.getElementById('is-pwa');
            const iosVersion = document.getElementById('ios-version');
            const webkitVersion = document.getElementById('webkit-version');
            
            // Detect browser
            const ua = window.navigator.userAgent;
            if (ua.includes('CriOS')) {
                browserInfo.textContent = 'Chrome for iOS';
            } else if (ua.includes('EdgiOS')) {
                browserInfo.textContent = 'Edge for iOS';
            } else if (ua.includes('FxiOS')) {
                browserInfo.textContent = 'Firefox for iOS';
            } else if (ua.includes('Safari') && !ua.includes('Chrome')) {
                browserInfo.textContent = 'Safari';
            } else {
                browserInfo.textContent = 'Unknown';
            }
            
            // Detect if running as PWA
            if (window.navigator.standalone || window.matchMedia('(display-mode: standalone)').matches) {
                isPwa.textContent = 'Yes';
                isPwa.className = 'success';
            } else {
                isPwa.textContent = 'No - running in browser';
                isPwa.className = 'warning';
            }
            
            // Detect iOS version
            const iosMatch = ua.match(/OS (\d+)_(\d+)_?(\d+)?/);
            if (iosMatch) {
                iosVersion.textContent = `${iosMatch[1]}.${iosMatch[2]}${iosMatch[3] ? `.${iosMatch[3]}` : ''}`;
            } else {
                iosVersion.textContent = 'Not iOS or not detectable';
            }
            
            // Try to detect WebKit version
            const webkitMatch = ua.match(/AppleWebKit\/([0-9.]+)/);
            if (webkitMatch) {
                webkitVersion.textContent = webkitMatch[1];
            } else {
                webkitVersion.textContent = 'Not detectable';
            }
        }
        
        async function runDiagnostics() {
            const resultsElement = document.getElementById('basic-results');
            resultsElement.innerHTML = 'Running diagnostics...\n';
            
            let results = '';
            
            // Check if service worker is supported
            if ('serviceWorker' in navigator) {
                results += '✅ Service Worker API is supported\n';
            } else {
                results += '❌ Service Worker API is NOT supported\n';
                resultsElement.innerHTML = results;
                return;
            }
            
            // Check if Cache API is supported
            if ('caches' in window) {
                results += '✅ Cache API is supported\n';
            } else {
                results += '❌ Cache API is NOT supported\n';
                resultsElement.innerHTML = results;
                return;
            }
            
            // Check if service worker is registered
            try {
                const registrations = await navigator.serviceWorker.getRegistrations();
                if (registrations.length > 0) {
                    results += `✅ ${registrations.length} Service Worker(s) registered\n`;
                    
                    registrations.forEach((reg, i) => {
                        results += `   SW #${i+1} scope: ${reg.scope}\n`;
                        results += `   SW #${i+1} updating: ${reg.updating ? 'yes' : 'no'}\n`;
                        results += `   SW #${i+1} waiting: ${reg.waiting ? 'yes' : 'no'}\n`;
                        results += `   SW #${i+1} active: ${reg.active ? 'yes' : 'no'}\n`;
                    });
                } else {
                    results += '❌ No Service Workers registered\n';
                }
            } catch (error) {
                results += `❌ Error checking Service Worker registration: ${error}\n`;
            }
            
            // Check online status
            results += `✅ Online status: ${navigator.onLine ? 'online' : 'offline'}\n`;
            
            // Check if manifest is present
            try {
                const manifestLinks = document.querySelectorAll('link[rel="manifest"]');
                if (manifestLinks.length > 0) {
                    results += '✅ Web App Manifest link found\n';
                } else {
                    results += '❌ No Web App Manifest link found\n';
                }
            } catch (error) {
                results += `❌ Error checking manifest: ${error}\n`;
            }
            
            // Check if app can be installed
            if ('BeforeInstallPromptEvent' in window) {
                results += '✅ App can be installed (BeforeInstallPromptEvent supported)\n';
            } else {
                results += '❓ BeforeInstallPromptEvent not supported - may not be installable via button\n';
            }
            
            resultsElement.innerHTML = results;
        }
        
        async function inspectCache() {
            const resultsElement = document.getElementById('cache-results');
            resultsElement.innerHTML = 'Inspecting cache...\n';
            
            try {
                if (!('caches' in window)) {
                    resultsElement.innerHTML = '❌ Cache API not supported in this browser';
                    return;
                }
                
                const cacheNames = await window.caches.keys();
                if (cacheNames.length === 0) {
                    resultsElement.innerHTML = 'No caches found.';
                    return;
                }
                
                let results = `Found ${cacheNames.length} cache(s):\n\n`;
                
                for (const cacheName of cacheNames) {
                    results += `CACHE: ${cacheName}\n`;
                    results += `------------------------\n`;
                    
                    const cache = await caches.open(cacheName);
                    const requests = await cache.keys();
                    
                    if (requests.length === 0) {
                        results += '(empty)\n\n';
                        continue;
                    }
                    
                    for (const request of requests) {
                        // Get just the pathname and any query params
                        const url = new URL(request.url);
                        const path = url.pathname + url.search;
                        results += `${path}\n`;
                    }
                    
                    results += `\nTotal items: ${requests.length}\n\n`;
                }
                
                resultsElement.innerHTML = results;
            } catch (error) {
                resultsElement.innerHTML = `Error inspecting cache: ${error.message}`;
            }
        }
        
        async function testServiceWorker() {
            const resultsElement = document.getElementById('sw-results');
            resultsElement.innerHTML = 'Testing service worker...\n';
            
            try {
                if (!('serviceWorker' in navigator)) {
                    resultsElement.innerHTML = '❌ Service Worker API not supported';
                    return;
                }
                
                // Test 1: Check if service worker is registered
                const registrations = await navigator.serviceWorker.getRegistrations();
                if (registrations.length === 0) {
                    resultsElement.innerHTML = '❌ No service worker registered';
                    return;
                }
                
                let results = `✅ Found ${registrations.length} service worker registration(s)\n`;
                
                // Test 2: Try to send a message to the service worker and get a response
                results += '\nTesting communication with service worker...\n';
                
                if (!navigator.serviceWorker.controller) {
                    results += '❌ No controlling service worker found\n';
                } else {
                    // Create a message channel for two-way communication
                    const messageChannel = new MessageChannel();
                    let receivedResponse = false;
                    
                    // Set up a promise that will resolve when we get a response
                    const messagePromise = new Promise((resolve, reject) => {
                        // Set timeout
                        const timeout = setTimeout(() => {
                            if (!receivedResponse) {
                                reject(new Error('Timed out waiting for service worker response'));
                            }
                        }, 3000);
                        
                        // Listen for response
                        messageChannel.port1.onmessage = (event) => {
                            receivedResponse = true;
                            clearTimeout(timeout);
                            resolve(event.data);
                        };
                    });
                    
                    // Send the message
                    navigator.serviceWorker.controller.postMessage({
                        type: 'PING',
                        timestamp: Date.now()
                    }, [messageChannel.port2]);
                    
                    // Wait for the response or timeout
                    try {
                        results += '  Sending ping to service worker...\n';
                        const response = await messagePromise;
                        results += `  ✅ Got response: ${JSON.stringify(response)}\n`;
                    } catch (error) {
                        results += `  ⚠️ ${error.message} - Service worker might not be responding to messages\n`;
                        results += '  ℹ️ This is normal if your service worker doesn\'t handle messages\n';
                    }
                }
                
                // Test 3: Offline capability
                results += '\nTesting offline capability...\n';
                
                // First, check if we're already offline
                if (!navigator.onLine) {
                    results += '  ⚠️ Device is currently offline\n';
                    results += '  ℹ️ If you can see this message, basic offline functionality is working\n';
                } else {
                    results += '  ℹ️ Device is currently online\n';
                    results += '  ℹ️ To test offline functionality, put your device in airplane mode\n';
                }
                
                // Check if we have any cached pages
                const mainCache = await caches.open('ems-protocols-v1');
                const cachedRequests = await mainCache.keys();
                
                results += `\n✅ Found ${cachedRequests.length} items in main cache\n`;
                
                if (cachedRequests.length > 0) {
                    // Check if we have the homepage cached
                    const homepageCached = cachedRequests.some(request => 
                        request.url.includes('/index.php') || 
                        request.url.endsWith('/'));
                    
                    if (homepageCached) {
                        results += '  ✅ Homepage is cached\n';
                    } else {
                        results += '  ⚠️ Homepage does not appear to be cached\n';
                    }
                    
                    // Check if we have any protocol pages cached
                    const protocolsCached = cachedRequests.filter(request => 
                        request.url.includes('/protocol.php'));
                    
                    if (protocolsCached.length > 0) {
                        results += `  ✅ ${protocolsCached.length} protocol pages are cached\n`;
                    } else {
                        results += '  ⚠️ No protocol pages appear to be cached\n';
                    }
                    
                    // Check if we have any category pages cached
                    const categoriesCached = cachedRequests.filter(request => 
                        request.url.includes('/category.php'));
                    
                    if (categoriesCached.length > 0) {
                        results += `  ✅ ${categoriesCached.length} category pages are cached\n`;
                    } else {
                        results += '  ⚠️ No category pages appear to be cached\n';
                    }
                }
                
                resultsElement.innerHTML = results;
            } catch (error) {
                resultsElement.innerHTML = `Error testing service worker: ${error.message}\n\n${error.stack}`;
            }
        }
        
        async function installBasicServiceWorker() {
            const resultsElement = document.getElementById('fix-results');
            resultsElement.innerHTML = 'Installing iOS-compatible service worker...\n';
            
            try {
                if (!('serviceWorker' in navigator)) {
                    resultsElement.innerHTML = '❌ Service Worker API not supported';
                    return;
                }
                
                // Unregister existing service workers
                const registrations = await navigator.serviceWorker.getRegistrations();
                for (const registration of registrations) {
                    await registration.unregister();
                    resultsElement.innerHTML += `Unregistered service worker with scope: ${registration.scope}\n`;
                }
                
                // Fetch the basic iOS service worker script
                const response = await fetch('basic-ios-sw.js');
                
                if (!response.ok) {
                    resultsElement.innerHTML += '❌ Failed to fetch basic iOS service worker script\n';
                    resultsElement.innerHTML += 'Please make sure basic-ios-sw.js exists in your root directory\n';
                    return;
                }
                
                // Register the new service worker
                const registration = await navigator.serviceWorker.register('/basic-ios-sw.js');
                
                resultsElement.innerHTML += `✅ Successfully registered basic iOS service worker with scope: ${registration.scope}\n`;
                resultsElement.innerHTML += 'Please reload the page to activate the new service worker\n';
                
                // Add a reload button
                const reloadBtn = document.createElement('button');
                reloadBtn.className = 'btn btn-sm btn-primary mt-3';
                reloadBtn.innerHTML = '<i class="ti ti-refresh"></i> Reload Page';
                reloadBtn.onclick = () => window.location.reload();
                resultsElement.appendChild(reloadBtn);
                
            } catch (error) {
                resultsElement.innerHTML += `❌ Error installing service worker: ${error.message}\n`;
            }
        }
        
        async function clearCache() {
            const resultsElement = document.getElementById('fix-results');
            resultsElement.innerHTML = 'Clearing cache storage...\n';
            
            try {
                if (!('caches' in window)) {
                    resultsElement.innerHTML = '❌ Cache API not supported';
                    return;
                }
                
                const cacheNames = await caches.keys();
                
                if (cacheNames.length === 0) {
                    resultsElement.innerHTML += 'No caches found to clear.\n';
                    return;
                }
                
                let count = 0;
                for (const cacheName of cacheNames) {
                    await caches.delete(cacheName);
                    count++;
                    resultsElement.innerHTML += `Deleted cache: ${cacheName}\n`;
                }
                
                resultsElement.innerHTML += `✅ Successfully cleared ${count} cache(s)\n`;
                resultsElement.innerHTML += 'You can now re-cache content for offline use\n';
                
            } catch (error) {
                resultsElement.innerHTML += `❌ Error clearing cache: ${error.message}\n`;
            }
        }
        
        async function cacheKeyPages() {
            const resultsElement = document.getElementById('fix-results');
            resultsElement.innerHTML = 'Caching key pages for offline use...\n';
            
            try {
                if (!('caches' in window)) {
                    resultsElement.innerHTML = '❌ Cache API not supported';
                    return;
                }
                
                // Get or create the main cache
                const cache = await caches.open('ems-protocols-v1');
                
                // First, cache the homepage
                resultsElement.innerHTML += 'Caching homepage...\n';
                
                try {
                    const homeResponse = await fetch('/index.php', { cache: 'no-store' });
                    await cache.put('/index.php', homeResponse);
                    resultsElement.innerHTML += '✅ Homepage cached\n';
                } catch (error) {
                    resultsElement.innerHTML += `❌ Failed to cache homepage: ${error.message}\n`;
                }
                
                // Now fetch the list of protocols and categories to cache
                resultsElement.innerHTML += 'Fetching protocol list...\n';
                
                try {
                    const listResponse = await fetch('/api/get_protocols_list.php?sync=1');
                    
                    if (!listResponse.ok) {
                        throw new Error(`HTTP error ${listResponse.status}`);
                    }
                    
                    const data = await listResponse.json();
                    
                    if (!data.success) {
                        throw new Error(data.message || 'Unknown error');
                    }
                    
                    resultsElement.innerHTML += `Found ${data.protocols.length} protocols and ${data.categories.length} categories\n`;
                    
                    // Cache each protocol
                    for (let i = 0; i < data.protocols.length; i++) {
                        const protocol = data.protocols[i];
                        const url = `/protocol.php?id=${protocol.id}`;
                        
                        try {
                            resultsElement.innerHTML += `Caching protocol ${i+1}/${data.protocols.length}: ${protocol.title}...\n`;
                            
                            const response = await fetch(url, { cache: 'no-store' });
                            
                            if (!response.ok) {
                                throw new Error(`HTTP error ${response.status}`);
                            }
                            
                            await cache.put(url, response);
                        } catch (error) {
                            resultsElement.innerHTML += `  ❌ Failed: ${error.message}\n`;
                        }
                    }
                    
                    // Cache each category
                    for (let i = 0; i < data.categories.length; i++) {
                        const category = data.categories[i];
                        const url = `/category.php?id=${category.id}`;
                        
                        try {
                            resultsElement.innerHTML += `Caching category ${i+1}/${data.categories.length}...\n`;
                            
                            const response = await fetch(url, { cache: 'no-store' });
                            
                            if (!response.ok) {
                                throw new Error(`HTTP error ${response.status}`);
                            }
                            
                            await cache.put(url, response);
                        } catch (error) {
                            resultsElement.innerHTML += `  ❌ Failed: ${error.message}\n`;
                        }
                    }
                    
                    resultsElement.innerHTML += `✅ Successfully cached key pages\n`;
                    resultsElement.innerHTML += 'You should now be able to use the app offline\n';
                    
                } catch (error) {
                    resultsElement.innerHTML += `❌ Error fetching protocol list: ${error.message}\n`;
                }
                
            } catch (error) {
                resultsElement.innerHTML += `❌ Error caching pages: ${error.message}\n`;
            }
        }
    </script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>