/**
 * iOS Protocol Offline Support (Fixed Version)
 * Provides complete offline access to protocols on iOS
 */
(function() {
  // Only run on iOS devices
  const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
  if (!isIOS) return;
  
  console.log("iOS Offline Support initializing");
  
  // Database constants
  const DB_NAME = 'ems_protocols_db_v2'; // Incremented version to force refresh
  const DB_VERSION = 1;
  const PAGE_STORE = 'protocol_pages';
  const META_STORE = 'metadata';
  let db;
  
  // Initialize the database
  function initDatabase() {
    return new Promise((resolve, reject) => {
      const request = indexedDB.open(DB_NAME, DB_VERSION);
      
      request.onerror = (event) => {
        console.error("Database error:", event.target.error);
        reject("Could not open database");
      };
      
      request.onupgradeneeded = (event) => {
        const db = event.target.result;
        
        // Store for protocol pages
        if (!db.objectStoreNames.contains(PAGE_STORE)) {
          db.createObjectStore(PAGE_STORE, { keyPath: 'key' });
        }
        
        // Store for metadata (like last update time)
        if (!db.objectStoreNames.contains(META_STORE)) {
          db.createObjectStore(META_STORE, { keyPath: 'key' });
        }
      };
      
      request.onsuccess = (event) => {
        db = event.target.result;
        console.log("iOS Protocol Database initialized");
        resolve(db);
      };
    });
  }
  
  // Normalize URL for consistent storage/retrieval
  function normalizeUrl(url) {
    try {
      const urlObj = new URL(url, window.location.origin);
      
      // For protocol pages, create a special key with just the ID
      if (urlObj.pathname.endsWith('/protocol.php')) {
        const id = urlObj.searchParams.get('id');
        if (id) return `protocol:${id}`;
      }
      
      // For category pages, do the same
      if (urlObj.pathname.endsWith('/category.php')) {
        const id = urlObj.searchParams.get('id');
        if (id) return `category:${id}`;
      }
      
      // For other pages, normalize the pathname
      return urlObj.pathname;
      
    } catch (e) {
      console.error("Error normalizing URL", e);
      return url; // Return original if something goes wrong
    }
  }
  
  // Store a page in the database
  function storePage(url, html, title) {
    if (!db) return Promise.reject("Database not initialized");
    
    return new Promise((resolve, reject) => {
      const key = normalizeUrl(url);
      const transaction = db.transaction([PAGE_STORE], 'readwrite');
      const store = transaction.objectStore(PAGE_STORE);
      
      console.log(`Storing page with key: ${key}`);
      
      const pageData = {
        key: key,
        url: url,
        html: html,
        title: title,
        timestamp: new Date().getTime()
      };
      
      const request = store.put(pageData);
      
      request.onsuccess = () => {
        resolve();
      };
      
      request.onerror = (e) => {
        console.error(`Error storing page ${url}:`, e);
        reject(e);
      };
    });
  }
  
  // Get a page from the database by URL
  async function getPageByUrl(url) {
    if (!db) return null;
    
    const key = normalizeUrl(url);
    console.log(`Looking for page with key: ${key}`);
    
    return new Promise((resolve) => {
      const transaction = db.transaction([PAGE_STORE], 'readonly');
      const store = transaction.objectStore(PAGE_STORE);
      const request = store.get(key);
      
      request.onsuccess = (event) => {
        const result = event.target.result;
        if (result) {
          console.log(`Found page with key: ${key}`);
        } else {
          console.log(`No page found with key: ${key}`);
        }
        resolve(result);
      };
      
      request.onerror = () => {
        console.error(`Error retrieving page with key: ${key}`);
        resolve(null);
      };
    });
  }
  
  // Check if we need to update our cache
  async function checkCacheAge() {
    return new Promise((resolve) => {
      if (!db) {
        resolve(true); // No DB yet, definitely need update
        return;
      }
      
      try {
        const transaction = db.transaction([META_STORE], 'readonly');
        const store = transaction.objectStore(META_STORE);
        const request = store.get('lastUpdate');
        
        request.onsuccess = (event) => {
          const lastUpdate = event.target.result;
          if (!lastUpdate) {
            resolve(true); // No last update, definitely need update
            return;
          }
          
          // Check if last update was more than 24 hours ago
          const now = new Date().getTime();
          const oneDayMs = 24 * 60 * 60 * 1000;
          const needsUpdate = (now - lastUpdate.timestamp) > oneDayMs;
          
          resolve(needsUpdate);
        };
        
        request.onerror = () => {
          resolve(true); // Error reading, assume we need update
        };
      } catch (e) {
        console.error("Error checking cache age:", e);
        resolve(true); // Error, assume we need update
      }
    });
  }
  
  // Update the last update timestamp
  function updateLastUpdateTime() {
    if (!db) return Promise.reject("Database not initialized");
    
    return new Promise((resolve, reject) => {
      const transaction = db.transaction([META_STORE], 'readwrite');
      const store = transaction.objectStore(META_STORE);
      
      const now = new Date().getTime();
      store.put({ key: 'lastUpdate', timestamp: now });
      
      transaction.oncomplete = () => {
        console.log("Updated last update time:", new Date(now).toLocaleString());
        resolve();
      };
      
      transaction.onerror = (e) => {
        console.error("Error updating timestamp:", e);
        reject(e);
      };
    });
  }
  
  // Fetch and store a single page
  async function fetchAndStorePage(url) {
    try {
      // Add a cache-busting parameter to ensure fresh content
      const fetchUrl = new URL(url, window.location.origin);
      fetchUrl.searchParams.append('_t', Date.now());
      
      console.log(`Fetching page: ${fetchUrl.toString()}`);
      const response = await fetch(fetchUrl.toString());
      
      if (!response.ok) {
        throw new Error(`Failed to fetch ${url}: ${response.status}`);
      }
      
      const html = await response.text();
      let title = "EMS Protocol";
      
      // Try to extract the title from the HTML
      const titleMatch = html.match(/<title>(.*?)<\/title>/i);
      if (titleMatch && titleMatch[1]) {
        title = titleMatch[1];
      }
      
      // Add our script to the cached HTML so it continues working offline
      const scriptTag = '<script src="/assets/js/ios-offline-fixed.js"></script>';
      const modifiedHtml = html.replace('</head>', scriptTag + '</head>');
      
      await storePage(url, modifiedHtml, title);
      return true;
    } catch (e) {
      console.error(`Failed to fetch and store ${url}:`, e);
      return false;
    }
  }
  
  // Download all protocols
  async function downloadAllProtocols() {
    try {
      // Show loading indicator
      showLoadingIndicator("Preparing offline content...");
      
      // First fetch the list of protocols
      const response = await fetch('/api/get_protocols_list.php?sync=1');
      if (!response.ok) throw new Error("Failed to fetch protocols list");
      
      const data = await response.json();
      if (!data.success) throw new Error(data.message || "Failed to load protocols");
      
      console.log(`Found ${data.protocols.length} protocols to cache`);
      updateLoadingIndicator(`Downloading ${data.protocols.length} protocols...`);
      
      // Store homepage first
      await fetchAndStorePage('/index.php');
      
      // Store all protocols
      let successCount = 0;
      for (let i = 0; i < data.protocols.length; i++) {
        const protocol = data.protocols[i];
        const url = `/protocol.php?id=${protocol.id}`;
        
        updateLoadingIndicator(`Downloading protocols: ${i+1}/${data.protocols.length}`);
        
        const success = await fetchAndStorePage(url);
        if (success) successCount++;
        
        // Small delay to prevent overwhelming the browser
        await new Promise(r => setTimeout(r, 50));
      }
      
      // Store categories if available
      if (data.categories && data.categories.length > 0) {
        updateLoadingIndicator(`Downloading ${data.categories.length} categories...`);
        
        for (let i = 0; i < data.categories.length; i++) {
          const category = data.categories[i];
          const url = `/category.php?id=${category.id}`;
          await fetchAndStorePage(url);
          
          // Small delay
          await new Promise(r => setTimeout(r, 50));
        }
      }
      
      // Update the last update time
      await updateLastUpdateTime();
      
      // Show completion
      updateLoadingIndicator(`Downloaded ${successCount} protocols for offline use`, 3000);
      
      return true;
    } catch (e) {
      console.error("Failed to download protocols:", e);
      updateLoadingIndicator(`Error: ${e.message}`, 3000);
      return false;
    }
  }
  
  // Show loading indicator
  function showLoadingIndicator(message) {
    // Remove existing indicator if any
    const existingIndicator = document.getElementById('ios-loading-indicator');
    if (existingIndicator) existingIndicator.remove();
    
    // Create new indicator
    const indicator = document.createElement('div');
    indicator.id = 'ios-loading-indicator';
    indicator.style.position = 'fixed';
    indicator.style.top = '60px';
    indicator.style.left = '0';
    indicator.style.right = '0';
    indicator.style.backgroundColor = '#17a2b8';
    indicator.style.color = 'white';
    indicator.style.padding = '10px';
    indicator.style.textAlign = 'center';
    indicator.style.zIndex = '9999';
    indicator.style.fontWeight = 'bold';
    indicator.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
    
    indicator.innerHTML = `
      <div>${message}</div>
      <div class="progress" style="height: 5px; margin-top: 5px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated" 
             role="progressbar" style="width: 100%"></div>
      </div>
    `;
    
    document.body.appendChild(indicator);
    return indicator;
  }
  
  // Update loading indicator
  function updateLoadingIndicator(message, autoHideAfter = 0) {
    const indicator = document.getElementById('ios-loading-indicator') || 
                      showLoadingIndicator(message);
    
    const messageDiv = indicator.querySelector('div');
    if (messageDiv) messageDiv.textContent = message;
    
    if (autoHideAfter > 0) {
      setTimeout(() => {
        indicator.remove();
      }, autoHideAfter);
    }
    
    return indicator;
  }
  
  // Show offline indicator
  function showOfflineIndicator() {
    let indicator = document.getElementById('ios-offline-indicator');
    if (!indicator) {
      indicator = document.createElement('div');
      indicator.id = 'ios-offline-indicator';
      indicator.style.position = 'fixed';
      indicator.style.top = '60px';
      indicator.style.left = '0';
      indicator.style.right = '0';
      indicator.style.backgroundColor = '#ffc107';
      indicator.style.color = '#000';
      indicator.style.textAlign = 'center';
      indicator.style.padding = '8px';
      indicator.style.zIndex = '9999';
      indicator.style.fontWeight = 'bold';
      indicator.innerHTML = 'Offline Mode (iOS)';
      document.body.appendChild(indicator);
    }
  }
  
  // Show debug status popup
  function showDebugStatus() {
    if (!db) {
      alert("Database not initialized!");
      return;
    }
    
    const transaction = db.transaction([PAGE_STORE], 'readonly');
    const store = transaction.objectStore(PAGE_STORE);
    const countRequest = store.count();
    
    countRequest.onsuccess = function() {
      const count = countRequest.result;
      
      // Get all keys for debugging
      const getAllRequest = store.getAllKeys();
      getAllRequest.onsuccess = function() {
        const keys = getAllRequest.result;
        const keysList = keys.join('\n');
        
        alert(`Cached pages: ${count}\n\nCached URLs (first 10):\n${keysList.slice(0, 10).join('\n')}`);
      };
    };
  }
  
  // Handle navigation to pages when offline
  function setupOfflineNavigation() {
    // Handle click events on links
    document.addEventListener('click', async function(e) {
      // Find if a link was clicked
      const link = e.target.closest('a');
      if (!link) return;
      
      const href = link.getAttribute('href');
      if (!href) return;
      
      // Skip external links and non-navigation links
      if (href.startsWith('#') || 
          href.startsWith('javascript:') || 
          (href.startsWith('http') && !href.includes(window.location.host))) {
        return;
      }
      
      // Only handle navigation when offline
      if (navigator.onLine) return;
      
      // Prevent the default navigation
      e.preventDefault();
      
      // Build the full URL
      let fullUrl = href;
      if (!href.startsWith('http')) {
        fullUrl = new URL(href, window.location.origin).href;
      }
      
      console.log("Offline navigation to:", fullUrl);
      
      // Try to find this page in our database
      try {
        const page = await getPageByUrl(fullUrl);
        
        if (page) {
          // We found the page in the cache!
          console.log("Found cached page:", page.key);
          
          // Replace the current page with the cached content
          document.open();
          document.write(page.html);
          document.close();
          
          // Make sure history state is updated
          if (window.history) {
            window.history.pushState({}, page.title, page.url);
          }
          
        } else {
          console.warn("Page not found in cache:", fullUrl);
          alert("This page is not available offline. Please connect to the internet to view it.");
        }
      } catch (e) {
        console.error("Error accessing cached page:", e);
        alert("Error loading page: " + e.message);
      }
    });
  }
  
  // Check online/offline status changes
  function setupOnlineStatusHandling() {
    window.addEventListener('online', () => {
      const indicator = document.getElementById('ios-offline-indicator');
      if (indicator) indicator.remove();
    });
    
    window.addEventListener('offline', showOfflineIndicator);
    
    // Check initial status
    if (!navigator.onLine) {
      showOfflineIndicator();
    }
  }
  
  // Add manual refresh control for iOS users
  function addRefreshControl() {
    // Add a refresh button
    const container = document.createElement('div');
    container.id = 'ios-refresh-control';
    container.style.position = 'fixed';
    container.style.bottom = '20px';
    container.style.right = '20px';
    container.style.zIndex = '1000';
    
    const button = document.createElement('button');
    button.className = 'btn btn-primary rounded-circle';
    button.style.width = '50px';
    button.style.height = '50px';
    button.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
    button.innerHTML = '<i class="ti ti-refresh" style="font-size: 1.5rem;"></i>';
    
    button.addEventListener('click', async () => {
      if (!navigator.onLine) {
        alert("You are offline. Please connect to the internet to update protocols.");
        return;
      }
      
      await downloadAllProtocols();
    });
    
    // Add a debug button in development
    const debugButton = document.createElement('button');
    debugButton.className = 'btn btn-info rounded-circle mt-2';
    debugButton.style.width = '50px';
    debugButton.style.height = '50px';
    debugButton.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
    debugButton.style.fontSize = '1.5rem';
    debugButton.innerHTML = '?';
    
    debugButton.addEventListener('click', showDebugStatus);
    
    container.appendChild(button);
    container.appendChild(debugButton);
    document.body.appendChild(container);
  }
  
  // Initialize everything
  async function init() {
    try {
      // Don't initialize again if already running
      if (window.iosOfflineInitialized) return;
      window.iosOfflineInitialized = true;
      
      // Initialize database
      await initDatabase();
      
      // Setup offline navigation
      setupOfflineNavigation();
      
      // Setup online/offline handling
      setupOnlineStatusHandling();
      
      // Add refresh control
      addRefreshControl();
      
      // Check if we need to update
      const needsUpdate = await checkCacheAge();
      
      // If we're online and need to update, download protocols
      if (navigator.onLine && needsUpdate) {
        console.log("iOS Offline Support: Updating protocols");
        await downloadAllProtocols();
      }
      
    } catch (e) {
      console.error("iOS Offline Support initialization failed:", e);
    }
  }
  
  // Wait for page to load then initialize
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();