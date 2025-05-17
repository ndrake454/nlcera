/**
 * Cross-platform offline support integration
 * Provides unified offline experience for both iOS and Android
 * 
 * Place this file in: /assets/js/offline-support.js
 */

(function() {
  console.log("Cross-platform offline support initializing");
  
  // Detect platform
  const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
  const isAndroid = /Android/.test(navigator.userAgent);
  
  // Check if running in standalone mode (installed as PWA)
  const isStandalone = window.matchMedia('(display-mode: standalone)').matches || 
                       window.navigator.standalone || 
                       document.referrer.includes('android-app://');
  
  // Initialize based on platform
  if (isIOS) {
    console.log("iOS device detected, using enhanced offline support");
    // The iOS script will be loaded separately via the script tag
    // in frontend_header.php for backward compatibility
  } else {
    console.log("Non-iOS device detected, using standard service worker");
    // The standard PWA implementation will handle this
  }
  
  // Add shared offline indicator for consistency
  setupOfflineIndicator();
  
  // Create visible sync button for all platforms
  document.addEventListener('DOMContentLoaded', function() {
    // Only show the button if we're in a PWA or if the device is mobile
    const isPWA = window.matchMedia('(display-mode: standalone)').matches || 
                  window.navigator.standalone;
    const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
    
    if (isPWA || isMobile) {
      createVisibleSyncButton();
    }
  });
  
  // Function to set up offline status indicator
  function setupOfflineIndicator() {
    // Create offline status indicator if it doesn't exist
    let statusIndicator = document.getElementById('offline-status');
    if (!statusIndicator) {
      statusIndicator = document.createElement('div');
      statusIndicator.id = 'offline-status';
      statusIndicator.className = 'offline-indicator';
      statusIndicator.style.display = 'none';
      statusIndicator.innerHTML = '<i class="ti ti-wifi-off me-1"></i> Offline Mode';
      document.body.appendChild(statusIndicator);
      
      // Add CSS
      const style = document.createElement('style');
      style.textContent = `
        .offline-indicator {
          position: fixed;
          top: 60px;
          left: 0;
          right: 0;
          background-color: #ffc107;
          color: #000;
          text-align: center;
          padding: 8px;
          z-index: 9999;
          font-weight: bold;
          box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        body.offline-mode .protocol-section {
          border-left: 4px solid #ffc107 !important;
        }
        
        body.offline-mode .protocol-section::after {
          content: 'Cached';
          position: absolute;
          top: 0;
          right: 0;
          background-color: #ffc107;
          color: #000;
          font-size: 0.6rem;
          padding: 0.1rem 0.3rem;
          border-radius: 0 0 0 0.3rem;
        }
      `;
      document.head.appendChild(style);
    }
    
    // Set up online/offline event handlers
    window.addEventListener('online', updateOfflineIndicator);
    window.addEventListener('offline', updateOfflineIndicator);
    
    // Initial check
    updateOfflineIndicator();
  }
  
  // Create visible sync button for all platforms
  function createVisibleSyncButton() {
    // Create the button element
    const button = document.createElement('button');
    button.id = 'manual-sync-button';
    button.className = 'sync-fab';
    button.innerHTML = '<i class="ti ti-refresh"></i>';
    button.title = 'Update offline content';
    
    // Add CSS styles for the button
    const style = document.createElement('style');
    style.textContent = `
      .sync-fab {
        position: fixed !important;
        right: 20px !important;
        bottom: 20px !important;
        width: 56px !important;
        height: 56px !important;
        border-radius: 50% !important;
        background-color: var(--primary-color, #106e9e) !important;
        color: white !important;
        border: none !important;
        box-shadow: 0 3px 5px rgba(0, 0, 0, 0.3) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        z-index: 9999 !important;
        transition: transform 0.2s, background-color 0.2s;
      }
      
      .sync-fab:hover {
        transform: scale(1.1);
      }
      
      .sync-fab:active {
        transform: scale(0.95);
      }
      
      .sync-fab i {
        font-size: 24px;
      }
      
      .sync-fab.syncing i {
        animation: spin 1s linear infinite;
      }
      
      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
      
      .sync-notification {
        position: fixed;
        bottom: 80px;
        right: 20px;
        padding: 10px 15px;
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        border-radius: 4px;
        font-size: 14px;
        z-index: 1001;
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.3s, transform 0.3s;
      }
      
      .sync-notification.show {
        opacity: 1;
        transform: translateY(0);
      }
    `;
    document.head.appendChild(style);
    
    // Flag to prevent multiple simultaneous syncs
    let isSyncing = false;
    
    // Add click event handler
    button.addEventListener('click', async function() {
      // Prevent multiple syncs
      if (isSyncing) return;
      isSyncing = true;
      
      // Show syncing state
      this.classList.add('syncing');
      
      // Clear any existing notifications
      const existingNotifications = document.querySelectorAll('.sync-notification');
      existingNotifications.forEach(notification => notification.remove());
      
      // Show notification
      const notification = showSyncNotification('Syncing protocols...');
      
      try {
        if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
          // First check if online
          if (!navigator.onLine) {
            notification.remove();
            showSyncNotification('You are offline. Connect to update protocols.', 3000);
            this.classList.remove('syncing');
            isSyncing = false;
            return;
          }
          
          // Try to register a sync
          const registration = await navigator.serviceWorker.ready;
          await registration.sync.register('sync-protocols');
          
          // Also force a reload of the current page to get fresh content
          const currentPageUrl = window.location.href;
          const response = await fetch(currentPageUrl, { cache: 'reload' });
          
          // Show success notification (after removing the previous one)
          notification.remove();
          showSyncNotification('Protocols synced successfully!', 3000);
        } else {
          // Fallback for browsers without service worker support
          notification.remove();
          showSyncNotification('Updating current page...', 2000);
          setTimeout(() => window.location.reload(), 2000);
        }
      } catch (error) {
        console.error('Sync failed:', error);
        notification.remove();
        showSyncNotification('Sync failed. Try again later.', 3000);
      } finally {
        // Remove syncing state
        setTimeout(() => {
          this.classList.remove('syncing');
          isSyncing = false;
        }, 2000);
      }
    });
    
    // Add the button to the page
    document.body.appendChild(button);
  }
  
  // Track active notification
  let activeNotification = null;
  
  // Show a notification to the user
  function showSyncNotification(message, duration = 0) {
    // Remove any existing notification
    if (activeNotification) {
      activeNotification.remove();
      activeNotification = null;
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'sync-notification';
    notification.textContent = message;
    document.body.appendChild(notification);
    activeNotification = notification;
    
    // Show notification
    setTimeout(() => {
      notification.classList.add('show');
    }, 10);
    
    // Hide after duration if specified
    if (duration > 0) {
      setTimeout(() => {
        // Only remove if this is still the active notification
        if (activeNotification === notification) {
          notification.classList.remove('show');
          // Remove after animation
          setTimeout(() => {
            if (document.body.contains(notification)) {
              notification.remove();
            }
            if (activeNotification === notification) {
              activeNotification = null;
            }
          }, 300);
        }
      }, duration);
    }
    
    return notification;
  }
  
  // Update the offline indicator based on connection status
  function updateOfflineIndicator() {
    const statusIndicator = document.getElementById('offline-status');
    if (!statusIndicator) return;
    
    if (navigator.onLine) {
      statusIndicator.style.display = 'none';
      document.body.classList.remove('offline-mode');
    } else {
      statusIndicator.style.display = 'block';
      document.body.classList.add('offline-mode');
      
      // Add more detailed info for standalone mode
      if (isStandalone) {
        statusIndicator.innerHTML = '<i class="ti ti-wifi-off me-1"></i> Offline Mode - ' + 
          '<a href="#" class="text-dark text-decoration-underline" ' +
          'onclick="showOfflineInfo(event)">Using Cached Content</a>';
      }
    }
  }
  
  // Add this function to window object for easy access
  window.updateOfflineIndicator = updateOfflineIndicator;
  
  // If not already defined, add the showOfflineInfo function to window
  if (typeof window.showOfflineInfo !== 'function') {
    window.showOfflineInfo = function(event) {
      event.preventDefault();
      
      // Check if we have Bootstrap modal available
      if (typeof bootstrap === 'undefined' || typeof bootstrap.Modal === 'undefined') {
        alert("You are currently viewing cached content while offline.");
        return;
      }
      
      // Check if the modal already exists
      let modal = document.getElementById('offline-info-modal');
      
      if (!modal) {
        // Create modal if it doesn't exist
        modal = document.createElement('div');
        modal.id = 'offline-info-modal';
        modal.className = 'modal fade';
        modal.setAttribute('tabindex', '-1');
        
        modal.innerHTML = `
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Offline Mode Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p>You are currently viewing cached content while offline.</p>
                <p>The following types of content are available offline:</p>
                <ul>
                  <li>All previously visited protocol pages</li>
                  <li>All previously visited category pages</li>
                  <li>The homepage and essential site assets</li>
                </ul>
                <p>Content that you haven't previously visited while online may not be available.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        `;
        
        document.body.appendChild(modal);
      }
      
      // Show the modal
      const bsModal = new bootstrap.Modal(modal);
      bsModal.show();
    };
  }
  
  // Handle cache updates for the current page
  async function updateCurrentPageCache() {
    if (!navigator.serviceWorker || !navigator.serviceWorker.controller) return;
    
    try {
      // Reload the current page from network
      const response = await fetch(window.location.href, { cache: 'reload' });
      if (!response.ok) throw new Error('Failed to fetch page');
      
      // Get the page content
      const content = await response.text();
      
      // Send a message to the service worker to update the cache
      navigator.serviceWorker.controller.postMessage({
        type: 'UPDATE_PAGE_CACHE',
        url: window.location.href,
        content: content
      });
      
      console.log('Current page cache updated');
    } catch (error) {
      console.error('Failed to update page cache:', error);
    }
  }
  
  // Handle periodic cache updates
  function setupPeriodicCacheUpdate() {
    // Update cache after session is idle for a while
    let idleTimer;
    function resetIdleTimer() {
      clearTimeout(idleTimer);
      idleTimer = setTimeout(() => {
        if (navigator.onLine) {
          console.log('User idle, updating cache');
          updateCurrentPageCache();
        }
      }, 60000); // 1 minute of inactivity
    }
    
    // Reset timer on user activity
    ['mousemove', 'keydown', 'touchstart', 'scroll'].forEach(event => {
      document.addEventListener(event, resetIdleTimer, { passive: true });
    });
    
    // Initial timer
    resetIdleTimer();
  }
  
  // Setup periodic cache updates
  if ('serviceWorker' in navigator) {
    document.addEventListener('DOMContentLoaded', setupPeriodicCacheUpdate);
  }
  
  // Initialize IndexedDB for additional storage if needed
  async function initializeIndexedDB() {
    return new Promise((resolve, reject) => {
      // Check if we already have IndexedDB handling via service worker
      if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
        // We'll let the service worker handle DB initialization
        resolve(null);
        return;
      }
      
      // Fallback initialization for browsers without service worker
      const request = indexedDB.open('ems-protocols-db', 1);
      
      request.onupgradeneeded = (event) => {
        const db = event.target.result;
        
        // Store for protocol pages if it doesn't exist
        if (!db.objectStoreNames.contains('protocol_pages')) {
          db.createObjectStore('protocol_pages', { keyPath: 'key' });
        }
        
        // Store for metadata if it doesn't exist
        if (!db.objectStoreNames.contains('metadata')) {
          db.createObjectStore('metadata', { keyPath: 'key' });
        }
      };
      
      request.onsuccess = (event) => {
        const db = event.target.result;
        console.log('IndexedDB initialized successfully');
        resolve(db);
      };
      
      request.onerror = (event) => {
        console.error('IndexedDB initialization failed:', event.target.error);
        reject(event.target.error);
      };
    });
  }
  
  // Initialize IndexedDB if available
  if ('indexedDB' in window) {
    initializeIndexedDB().catch(error => {
      console.warn('Could not initialize IndexedDB:', error);
    });
  }
})();