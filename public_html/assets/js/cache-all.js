/**
 * Manual Cache All Script
 * This script will force-cache all protocol content for offline use
 * Include this on a page that an administrator can access to trigger a full cache
 */

// Function to cache all protocols and site content
async function cacheAllContent() {
  try {
    const statusElement = document.getElementById('cache-status');
    if (!statusElement) return;
    
    // Check if service worker and caches are available
    if (!('serviceWorker' in navigator) || !('caches' in window)) {
      statusElement.innerHTML = `<div class="alert alert-danger">
        Your browser doesn't support PWA caching features.
      </div>`;
      return;
    }
    
    // Update status
    statusElement.innerHTML = `<div class="alert alert-info">
      <div class="d-flex align-items-center">
        <div class="spinner-border spinner-border-sm me-2" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <div>Fetching protocol list...</div>
      </div>
    </div>`;
    
    // Fetch the list of all protocols and categories
    const response = await fetch('/api/get_protocols_list.php?sync=1');
    const data = await response.json();
    
    if (!data.success) {
      throw new Error(data.message || 'Failed to fetch protocols list');
    }
    
    // Update status with count
    statusElement.innerHTML = `<div class="alert alert-info">
      <div class="d-flex align-items-center">
        <div class="spinner-border spinner-border-sm me-2" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <div>Found ${data.protocols.length} protocols. Starting cache process...</div>
      </div>
    </div>`;
    
    // Start caching process
    let successCount = 0;
    let errorCount = 0;
    
    // Create a log element to show progress
    const logElement = document.createElement('div');
    logElement.className = 'mt-3 small bg-light p-3 rounded';
    logElement.style.maxHeight = '200px';
    logElement.style.overflowY = 'auto';
    statusElement.appendChild(logElement);
    
    // Cache homepage first
    try {
      logElement.innerHTML += `<div>Caching homepage...</div>`;
      await fetch('/index.php', { cache: 'reload' });
      successCount++;
      logElement.innerHTML += `<div class="text-success">✓ Homepage cached successfully</div>`;
    } catch (error) {
      errorCount++;
      logElement.innerHTML += `<div class="text-danger">✗ Failed to cache homepage: ${error.message}</div>`;
    }
    
    // Cache each protocol
    for (let i = 0; i < data.protocols.length; i++) {
      const protocol = data.protocols[i];
      
      // Update progress
      const progress = Math.round(((i + 1) / data.protocols.length) * 100);
      statusElement.innerHTML = `<div class="alert alert-info">
        <div>Caching protocols: ${i + 1} of ${data.protocols.length} (${progress}%)</div>
        <div class="progress mt-2" style="height: 10px;">
          <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
               style="width: ${progress}%" aria-valuenow="${progress}" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>`;
      
      try {
        // Force reload to ensure fresh content is cached
        await fetch(`/protocol.php?id=${protocol.id}`, { cache: 'reload' });
        successCount++;
        logElement.innerHTML += `<div class="text-success">✓ ${protocol.protocol_number}: ${protocol.title}</div>`;
        // Scroll log to bottom
        logElement.scrollTop = logElement.scrollHeight;
      } catch (error) {
        errorCount++;
        logElement.innerHTML += `<div class="text-danger">✗ Failed to cache ${protocol.title}: ${error.message}</div>`;
      }
      
      // Small delay to prevent overwhelming the server
      await new Promise(resolve => setTimeout(resolve, 100));
    }
    
    // Cache all categories
    if (data.categories && data.categories.length > 0) {
      statusElement.innerHTML = `<div class="alert alert-info">
        <div>Caching categories...</div>
        <div class="progress mt-2" style="height: 10px;">
          <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" 
               style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>`;
      
      for (const category of data.categories) {
        try {
          await fetch(`/category.php?id=${category.id}`, { cache: 'reload' });
          successCount++;
          logElement.innerHTML += `<div class="text-success">✓ Category: ${category.title || category.id}</div>`;
        } catch (error) {
          errorCount++;
          logElement.innerHTML += `<div class="text-danger">✗ Failed to cache category ${category.id}: ${error.message}</div>`;
        }
      }
    }
    
    // Final status update
    statusElement.innerHTML = `<div class="alert alert-success">
      <h5>Caching Complete!</h5>
      <p>Successfully cached ${successCount} pages.</p>
      ${errorCount > 0 ? `<p class="text-danger">Failed to cache ${errorCount} pages.</p>` : ''}
      <p>Your app should now work offline for all cached protocols.</p>
      <button onclick="window.location.reload()" class="btn btn-primary btn-sm mt-2">Refresh Page</button>
    </div>`;
    
    // Keep the log for review
    statusElement.appendChild(logElement);
    
    // Trigger a sync event in the service worker
    if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
      navigator.serviceWorker.ready.then(registration => {
        registration.sync.register('sync-protocols');
      });
    }
    
  } catch (error) {
    console.error('Error caching content:', error);
    const statusElement = document.getElementById('cache-status');
    if (statusElement) {
      statusElement.innerHTML = `<div class="alert alert-danger">
        <h5>Caching Failed</h5>
        <p>${error.message}</p>
        <button onclick="cacheAllContent()" class="btn btn-primary btn-sm mt-2">Try Again</button>
      </div>`;
    }
  }
}

// Add event listener to button if it exists
document.addEventListener('DOMContentLoaded', function() {
  const cacheButton = document.getElementById('cache-all-button');
  if (cacheButton) {
    cacheButton.addEventListener('click', cacheAllContent);
  }
});