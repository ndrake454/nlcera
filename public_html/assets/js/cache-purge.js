// cache-purge.js - Place this in your /assets/js/ directory

// Self-executing function to avoid global scope pollution
(async function() {
  console.log('Running PWA cache purge script');
  
  // Display status message
  function showStatus(message, isError = false) {
    // Create status element if it doesn't exist
    if (!document.getElementById('cache-purge-status')) {
      const statusElement = document.createElement('div');
      statusElement.id = 'cache-purge-status';
      statusElement.style.position = 'fixed';
      statusElement.style.top = '20px';
      statusElement.style.left = '20px';
      statusElement.style.right = '20px';
      statusElement.style.padding = '15px';
      statusElement.style.backgroundColor = '#ffc107';
      statusElement.style.color = '#000';
      statusElement.style.borderRadius = '5px';
      statusElement.style.boxShadow = '0 2px 10px rgba(0,0,0,0.2)';
      statusElement.style.zIndex = '9999';
      statusElement.style.textAlign = 'center';
      document.body.appendChild(statusElement);
    }
    
    const statusElement = document.getElementById('cache-purge-status');
    statusElement.textContent = message;
    
    if (isError) {
      statusElement.style.backgroundColor = '#dc3545';
      statusElement.style.color = '#fff';
    }
  }
  
  try {
    showStatus('Purging service worker caches...');
    
    // Step 1: Clear all caches
    if ('caches' in window) {
      const cacheNames = await caches.keys();
      console.log(`Found ${cacheNames.length} caches to clear`);
      
      for (const cacheName of cacheNames) {
        await caches.delete(cacheName);
        console.log(`Cleared cache: ${cacheName}`);
      }
      
      showStatus(`Cleared ${cacheNames.length} caches successfully.`);
    } else {
      showStatus('Cache API not supported in this browser.', true);
      return;
    }
    
    // Step 2: Unregister all service workers
    if ('serviceWorker' in navigator) {
      const registrations = await navigator.serviceWorker.getRegistrations();
      console.log(`Found ${registrations.length} service worker registrations`);
      
      for (const registration of registrations) {
        const unregistered = await registration.unregister();
        if (unregistered) {
          console.log(`Unregistered service worker: ${registration.scope}`);
        } else {
          console.log(`Failed to unregister service worker: ${registration.scope}`);
        }
      }
      
      showStatus(`Cleared all caches and unregistered ${registrations.length} service workers. Reloading in 3 seconds...`);
    } else {
      showStatus('Service Worker API not supported in this browser.', true);
      return;
    }
    
    // Step 3: Reload the page with cache bypass flags
    setTimeout(() => {
      // Use cache busting parameter to ensure fresh content
      const url = new URL(window.location.href);
      url.searchParams.set('_purge', Date.now());
      
      // Force reload with cache clearing
      window.location.replace(url.toString());
    }, 3000);
    
  } catch (error) {
    console.error('Error during cache purge:', error);
    showStatus(`Error clearing caches: ${error.message}`, true);
  }
})();