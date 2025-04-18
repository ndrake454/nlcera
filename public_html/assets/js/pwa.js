// PWA Initialization Script
document.addEventListener('DOMContentLoaded', function() {
  // Check if service workers are supported
  if ('serviceWorker' in navigator) {
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
      })
      .catch(error => {
        console.error('Service Worker registration failed:', error);
      });
      
    // Listen for service worker updates
    navigator.serviceWorker.addEventListener('controllerchange', () => {
      console.log('Service Worker controller changed - reloading page for fresh content');
      window.location.reload();
    });
    
    // Prefetch key resources for offline use when page loads (silently in background)
    window.addEventListener('load', () => {
      if (navigator.onLine) {
        setTimeout(() => {
          // Prefetch index and key pages
          caches.open('ems-protocols-v1').then(cache => {
            cache.add('/index.php');
            console.log('Prefetched homepage for offline use');
          }).catch(err => {
            console.error('Failed to prefetch homepage:', err);
          });
        }, 3000); // Wait 3 seconds after page load to avoid competing for resources
      }
    });
  }
  
  // Add "Add to Home Screen" promotion for mobile users
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
  
  // Setup offline/online handlers
  setupOfflineHandling();
});

// Function to set up offline detection and handling
function setupOfflineHandling() {
  // Check if we're running in standalone mode (installed as PWA)
  const isStandalone = window.matchMedia('(display-mode: standalone)').matches || 
                       window.navigator.standalone || 
                       document.referrer.includes('android-app://');
  
  // Create offline status indicator
  const statusIndicator = document.createElement('div');
  statusIndicator.id = 'offline-status';
  statusIndicator.classList.add('offline-indicator');
  statusIndicator.style.display = 'none';
  statusIndicator.innerHTML = '<i class="ti ti-wifi-off me-1"></i> Offline Mode';
  document.body.appendChild(statusIndicator);
  
  // Monitor online/offline status
  window.addEventListener('online', updateOnlineStatus);
  window.addEventListener('offline', updateOnlineStatus);
  
  // Check initial status
  updateOnlineStatus();
  
  // Function to update online/offline status indicator
  function updateOnlineStatus() {
    const statusIndicator = document.getElementById('offline-status');
    if (!statusIndicator) return;
    
    if (navigator.onLine) {
      statusIndicator.style.display = 'none';
      
      // If we just came back online, trigger a sync
      if (navigator.serviceWorker.controller) {
        navigator.serviceWorker.ready.then(registration => {
          registration.sync.register('sync-protocols')
            .then(() => console.log('Background sync registered!'))
            .catch(err => console.error('Background sync registration failed:', err));
        });
      }
      
      // Remove offline class from body if it exists
      document.body.classList.remove('offline-mode');
    } else {
      statusIndicator.style.display = 'block';
      
      // Add offline class to body for potential styling
      document.body.classList.add('offline-mode');
      
      // If app is in standalone mode, show a more detailed offline message
      if (isStandalone) {
        statusIndicator.innerHTML = '<i class="ti ti-wifi-off me-1"></i> Offline Mode - ' + 
          '<a href="#" class="text-white text-decoration-underline" ' +
          'onclick="showOfflineInfo(event)">Using Cached Content</a>';
      }
    }
  }
  
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
    }
    
    .install-pwa-promo {
      background: linear-gradient(to right, #e8f4f8, #ffffff);
      border-left: 4px solid #106e9e;
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(16, 110, 158, 0.4);
      }
      70% {
        box-shadow: 0 0 0 10px rgba(16, 110, 158, 0);
      }
      100% {
        box-shadow: 0 0 0 0 rgba(16, 110, 158, 0);
      }
    }
    
    /* Subtle visual indicator for offline mode */
    body.offline-mode .protocol-section {
      border-left: 4px solid #ffc107 !important;
    }
    
    /* Add a subtle badge to cached content when offline */
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

// Function to show offline info dialog 
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