// Enhanced pwa.js with cross-platform compatibility

document.addEventListener('DOMContentLoaded', function() {
  // Check if service workers are supported
  if ('serviceWorker' in navigator) {
    // Register the enhanced service worker
    navigator.serviceWorker.register('/service-worker.js')
      .then(registration => {
        console.log('Service Worker registered with scope:', registration.scope);
        
        // Check for updates when online
        if (navigator.onLine) {
          registration.update();
        }
        
        // Setup periodic background syncs if supported
        if ('periodicSync' in registration) {
          try {
            registration.periodicSync.register('sync-protocols', {
              minInterval: 24 * 60 * 60 * 1000 // Once per day
            });
            console.log('Periodic sync registered');
          } catch (error) {
            console.error('Periodic background sync could not be registered:', error);
          }
        }
        
        // Manual sync for platforms that don't support periodicSync
        registration.sync.register('sync-protocols').catch(err => {
          console.log('Background sync not supported, will update on page load');
        });
      })
      .catch(error => {
        console.error('Service Worker registration failed:', error);
      });
      
    // Listen for service worker updates
    navigator.serviceWorker.addEventListener('controllerchange', () => {
      console.log('Service Worker controller changed - refreshing content');
      // Only reload if not in the middle of user interaction
      if (document.activeElement.tagName !== 'INPUT' && 
          document.activeElement.tagName !== 'TEXTAREA') {
        window.location.reload();
      }
    });

    // Create unified offline status indicator
    setupOfflineIndicator();
    
    // Create refresh button for all platforms
    createRefreshButton();
  }
  
  // Add "Add to Home Screen" promotion
  setupInstallPrompt();
});

// Function to set up offline status indicator
function setupOfflineIndicator() {
  // Create offline status indicator
  const statusIndicator = document.createElement('div');
  statusIndicator.id = 'offline-status';
  statusIndicator.className = 'offline-indicator';
  statusIndicator.style.display = 'none';
  statusIndicator.innerHTML = '<i class="ti ti-wifi-off me-1"></i> Offline Mode';
  document.body.appendChild(statusIndicator);
  
  // Monitor online/offline status
  window.addEventListener('online', updateOnlineStatus);
  window.addEventListener('offline', updateOnlineStatus);
  
  // Check initial status
  updateOnlineStatus();
  
  // Add CSS for the offline indicator
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
    
    .refresh-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background-color: var(--primary-color);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      border: none;
      cursor: pointer;
      z-index: 1000;
      transition: transform 0.3s ease;
    }
    
    .refresh-button:hover {
      transform: scale(1.1);
    }
    
    .refresh-button i {
      font-size: 1.5rem;
    }
  `;
  document.head.appendChild(style);
}

// Function to update online/offline status
function updateOnlineStatus() {
  const statusIndicator = document.getElementById('offline-status');
  if (!statusIndicator) return;
  
  if (navigator.onLine) {
    statusIndicator.style.display = 'none';
    document.body.classList.remove('offline-mode');
    
    // If we just came back online, trigger a sync
    if (navigator.serviceWorker.controller) {
      navigator.serviceWorker.ready.then(registration => {
        registration.sync.register('sync-protocols')
          .catch(err => console.log('Background sync not available, but online now'));
      });
    }
  } else {
    statusIndicator.style.display = 'block';
    document.body.classList.add('offline-mode');
    statusIndicator.innerHTML = '<i class="ti ti-wifi-off me-1"></i> Offline Mode - ' + 
      '<a href="#" class="text-dark text-decoration-underline" ' +
      'onclick="showOfflineInfo(event)">Using Cached Content</a>';
  }
}

// Create refresh button for all platforms
function createRefreshButton() {
  const button = document.createElement('button');
  button.className = 'refresh-button';
  button.id = 'refresh-cache-button';
  button.innerHTML = '<i class="ti ti-refresh"></i>';
  button.style.display = 'none'; // Hide initially
  
  button.addEventListener('click', async () => {
    if (!navigator.onLine) {
      alert("You are offline. Please connect to the internet to update protocols.");
      return;
    }
    
    // Show loading indicator
    button.innerHTML = '<i class="ti ti-loader"></i>';
    button.style.pointerEvents = 'none';
    
    try {
      // Trigger SW sync
      await navigator.serviceWorker.ready;
      await navigator.serviceWorker.controller.postMessage({
        action: 'cache-current-page'
      });
      
      // Also register for broader sync
      await navigator.serviceWorker.ready.then(registration => {
        return registration.sync.register('sync-protocols');
      });
      
      // Show success briefly
      button.innerHTML = '<i class="ti ti-check"></i>';
      setTimeout(() => {
        button.innerHTML = '<i class="ti ti-refresh"></i>';
        button.style.pointerEvents = 'auto';
      }, 1500);
    } catch (error) {
      console.error('Failed to refresh cache:', error);
      button.innerHTML = '<i class="ti ti-alert-circle"></i>';
      setTimeout(() => {
        button.innerHTML = '<i class="ti ti-refresh"></i>';
        button.style.pointerEvents = 'auto';
      }, 1500);
    }
  });
  
  // Show the button after 5 seconds of inactivity
  setTimeout(() => {
    document.body.appendChild(button);
    button.style.display = 'flex';
    
    // Hide after 10 seconds if not used
    setTimeout(() => {
      if (document.body.contains(button)) {
        button.style.opacity = '0.5';
      }
    }, 5000);
    
    // Show on hover
    button.addEventListener('mouseenter', () => {
      button.style.opacity = '1';
    });
    
    button.addEventListener('mouseleave', () => {
      button.style.opacity = '0.5';
    });
  }, 5000);
}

// Function to set up install prompt
function setupInstallPrompt() {
  let deferredPrompt;
  const addBtn = document.createElement('button');
  addBtn.style.display = 'none';
  addBtn.classList.add('btn', 'btn-primary', 'btn-sm', 'install-btn');
  addBtn.innerHTML = '<i class="ti ti-device-mobile me-1"></i> Install App';
  
  window.addEventListener('beforeinstallprompt', (e) => {
    // Prevent Chrome 67 and earlier from automatically showing the prompt
    e.preventDefault();
    // Stash the event so it can be triggered later
    deferredPrompt = e;
    
    // Show the install button if we're not already installed
    if (!window.matchMedia('(display-mode: standalone)').matches) {
      // Only show after user has interacted with the page
      document.addEventListener('click', function showInstallBanner() {
        const header = document.querySelector('.header .container');
        if (header) {
          const existingPromo = document.querySelector('.install-pwa-promo');
          if (!existingPromo) {
            const promo = document.createElement('div');
            promo.classList.add('install-pwa-promo', 'mt-2', 'p-2', 'bg-light', 'rounded', 'text-center', 'd-flex', 'align-items-center', 'justify-content-between');
            promo.innerHTML = '<span>Install this app for offline access</span>';
            promo.appendChild(addBtn);
            header.appendChild(promo);
            addBtn.style.display = 'block';
          }
        }
        
        document.removeEventListener('click', showInstallBanner);
      }, { once: true });
    }
  });
  
  document.body.appendChild(addBtn);
  
  addBtn.addEventListener('click', async () => {
    if (deferredPrompt) {
      // Show the install prompt
      deferredPrompt.prompt();
      
      // Wait for the user to respond to the prompt
      const { outcome } = await deferredPrompt.userChoice;
      console.log(`User response to the install prompt: ${outcome}`);
      
      // We've used the prompt, and can't use it again, so clear it
      deferredPrompt = null;
      
      // Hide the button
      addBtn.style.display = 'none';
    }
  });
}

// Function to show offline info dialog - add to window for onClick access
window.showOfflineInfo = function(event) {
  event.preventDefault();
  
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