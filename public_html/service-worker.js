// At the top of service-worker.js
const CACHE_NAME = 'ems-protocols-v2-ios'; // New name forces refresh
const OFFLINE_URL = '/offline.html';

// Assets to cache immediately on service worker install
const PRECACHE_ASSETS = [
  '/',
  '/index.php',
  '/offline.html',
  '/assets/js/pwa.js',
  '/manifest.json',
  
  // Icon assets - Android
  '/assets/icons/android/android-launchericon-512-512.png',
  '/assets/icons/android/android-launchericon-192-192.png',
  '/assets/icons/android/android-launchericon-144-144.png',
  '/assets/icons/android/android-launchericon-96-96.png',
  '/assets/icons/android/android-launchericon-72-72.png',
  '/assets/icons/android/android-launchericon-48-48.png',
  
  // Icon assets - iOS
  '/assets/icons/ios/192.png',
  '/assets/icons/ios/512.png',
  '/assets/icons/ios/180.png',
  '/assets/icons/ios/167.png',
  '/assets/icons/ios/152.png',
  '/assets/icons/ios/32.png',
  '/assets/icons/ios/16.png',
  
  // CSS and JS resources
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'
];

// Cache API content endpoints separately
const API_CACHE_NAME = 'ems-api-cache-v1';
const API_PATHS = [
  '/api/get_section.php',
  '/api/get_branches.php',
  '/api/get_branch.php',
  '/api/get_protocols_list.php'
];

// Install event - cache core assets
self.addEventListener('install', event => {
  console.log('[Service Worker] Installing Service Worker...');
  
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('[Service Worker] Pre-caching assets');
        return cache.addAll(PRECACHE_ASSETS);
      })
      .then(() => {
        // Force activation - especially important for iOS
        return self.skipWaiting();
      })
  );
});

// Activate event - clean up old caches and take control immediately
self.addEventListener('activate', event => {
  console.log('[Service Worker] Activating Service Worker...');
  
  event.waitUntil(
    Promise.all([
      // Clean up old caches
      caches.keys().then(cacheNames => {
        return Promise.all(
          cacheNames.map(cache => {
            if (cache !== CACHE_NAME && cache !== API_CACHE_NAME) {
              console.log('[Service Worker] Clearing old cache:', cache);
              return caches.delete(cache);
            }
          })
        );
      }),
      // Take control of all clients immediately (important for iOS)
      self.clients.claim()
    ])
  );
});

// Helper function to normalize URLs for cache matching
// This helps with iOS Safari's handling of query parameters
function normalizeUrl(url) {
  const urlObj = new URL(url);
  
  // For PHP pages with query params, create a consistent key
  if (urlObj.pathname.endsWith('.php') && urlObj.search) {
    // Extract the page name and main query parameters
    const pageName = urlObj.pathname.split('/').pop();
    const id = urlObj.searchParams.get('id');
    
    if (id) {
      // Normalize to a consistent format
      return `${urlObj.origin}${urlObj.pathname}?id=${id}`;
    }
  }
  
  // Return the original URL for other cases
  return url;
}

// Helper function to find a matching cached response
async function findMatchingResponse(request) {
  const cacheKey = normalizeUrl(request.url);
  const cache = await caches.open(CACHE_NAME);
  
  // First try a direct match
  let response = await cache.match(request);
  if (response) return response;
  
  // If not found and this is a PHP page with query params, try harder
  if (request.url.includes('.php') && request.url.includes('?')) {
    // Get all cached requests and check for matching pathname + query
    const keys = await cache.keys();
    
    // Try to find a close match by URL structure
    const requestUrl = new URL(request.url);
    const requestPath = requestUrl.pathname;
    const requestId = requestUrl.searchParams.get('id');
    
    // Find any key that matches the path and has the same id parameter
    const matchingKey = keys.find(key => {
      const keyUrl = new URL(key.url);
      return keyUrl.pathname === requestPath && 
             keyUrl.searchParams.get('id') === requestId;
    });
    
    if (matchingKey) {
      return cache.match(matchingKey);
    }
  }
  
  return null;
}

// Fetch event - handle network requests with iOS-specific improvements
self.addEventListener('fetch', event => {
  const request = event.request;
  
  // Skip non-GET requests and cross-origin requests (except CDN resources we need)
  if (request.method !== 'GET' || 
      (!request.url.includes(self.location.origin) && 
       !request.url.includes('cdn.jsdelivr.net'))) {
    return;
  }

  // Handle API requests separately
  const url = new URL(request.url);
  if (API_PATHS.some(path => url.pathname.includes(path))) {
    event.respondWith(handleApiRequest(request));
    return;
  }
  
  // Special handling for navigation requests (HTML pages)
  if (request.mode === 'navigate' || (request.headers.get('accept') && request.headers.get('accept').includes('text/html'))) {
    event.respondWith(handleNavigationRequest(request));
    return;
  }

  // For all other requests, use cache-first strategy with iOS optimizations
  event.respondWith(
    (async () => {
      // Check cache first with both direct and normalized matching
      const cachedResponse = await findMatchingResponse(request);
      if (cachedResponse) {
        return cachedResponse;
      }
      
      // If not in cache, try the network
      try {
        const networkResponse = await fetch(request);
        
        // Only cache successful responses
        if (networkResponse.ok) {
          const responseToCache = networkResponse.clone();
          const cache = await caches.open(CACHE_NAME);
          
          // Store in cache using normalized URL as the key
          await cache.put(request, responseToCache);
        }
        
        return networkResponse;
      } catch (error) {
        console.error('[Service Worker] Network request failed:', error);
        
        // For images, return a placeholder
        if (request.destination === 'image') {
          return caches.match('/assets/icons/ios/192.png');
        }
        
        // For other resources, return a simple error response
        return new Response('Resource not available offline', { 
          status: 503, 
          headers: { 'Content-Type': 'text/plain' } 
        });
      }
    })()
  );
});

// Specialized handler for navigation requests with iOS improvements
async function handleNavigationRequest(request) {
  // Try cache first with enhanced matching for iOS
  const cachedResponse = await findMatchingResponse(request);
  if (cachedResponse) {
    console.log('[Service Worker] Serving from cache:', request.url);
    return cachedResponse;
  }
  
  try {
    // If not in cache, try network
    console.log('[Service Worker] Fetching from network:', request.url);
    const networkResponse = await fetch(request);
    
    // Cache the response for future use
    if (networkResponse.ok) {
      const responseToCache = networkResponse.clone();
      const cache = await caches.open(CACHE_NAME);
      await cache.put(request, responseToCache);
    }
    
    return networkResponse;
  } catch (error) {
    console.error('[Service Worker] Navigation request failed:', error);
    
    // If the request is for the homepage, try to serve the cached homepage
    if (request.url.endsWith('/') || request.url.includes('/index.php')) {
      const homeResponse = await caches.match('/index.php');
      if (homeResponse) return homeResponse;
    }
    
    // For protocol pages, try to find a matching protocol in cache
    if (request.url.includes('/protocol.php') || request.url.includes('/category.php')) {
      // Get all cached entries and look for a similar pattern
      const cache = await caches.open(CACHE_NAME);
      const keys = await cache.keys();
      
      // Extract the page type and ID from the URL
      const requestUrl = new URL(request.url);
      const isProtocol = requestUrl.pathname.includes('/protocol.php');
      const requestId = requestUrl.searchParams.get('id');
      
      if (requestId) {
        // Try to find an exact match first
        for (const key of keys) {
          const keyUrl = new URL(key.url);
          const keyPath = keyUrl.pathname;
          const keyId = keyUrl.searchParams.get('id');
          
          if ((isProtocol && keyPath.includes('/protocol.php') && keyId === requestId) ||
              (!isProtocol && keyPath.includes('/category.php') && keyId === requestId)) {
            return cache.match(key);
          }
        }
      }
    }
    
    // Last resort, show offline page
    return caches.match(OFFLINE_URL);
  }
}

// API request handler with iOS optimizations
async function handleApiRequest(request) {
  // Check cache first
  const cachedResponse = await caches.match(request);
  
  try {
    // Try network
    const networkResponse = await fetch(request);
    
    // Cache the fresh response
    if (networkResponse.ok) {
      const responseToCache = networkResponse.clone();
      const cache = await caches.open(API_CACHE_NAME);
      await cache.put(request, responseToCache);
    }
    
    return networkResponse;
  } catch (error) {
    console.error('[Service Worker] API request failed:', error);
    
    // If offline, return cached version if available
    if (cachedResponse) {
      return cachedResponse;
    }
    
    // Return JSON error for API requests
    return new Response(JSON.stringify({ 
      success: false, 
      message: 'You are offline. This API content has not been cached yet.',
      offline: true
    }), {
      status: 503,
      headers: { 'Content-Type': 'application/json' }
    });
  }
}

// Listen for background sync event
self.addEventListener('sync', event => {
  if (event.tag === 'sync-protocols') {
    event.waitUntil(syncProtocols());
  }
});

// Enhanced sync function with better iOS compatibility
async function syncProtocols() {
  console.log('[Service Worker] Syncing protocols data...');
  
  try {
    // Fetch the list of protocols to cache
    const response = await fetch('/api/get_protocols_list.php?sync=1');
    if (!response.ok) throw new Error('Failed to fetch protocols list');
    
    const data = await response.json();
    
    if (!data.success) throw new Error(data.message || 'Unknown error');
    
    // Open the cache
    const cache = await caches.open(CACHE_NAME);
    
    // iOS-friendly protocol caching approach
    const cachePromises = data.protocols.map(async protocol => {
      const url = `/protocol.php?id=${protocol.id}`;
      try {
        const response = await fetch(url);
        if (response.ok) {
          // Cache the protocol page
          await cache.put(new Request(url), response.clone());
          console.log(`[Service Worker] Cached protocol: ${protocol.title}`);
        }
      } catch (error) {
        console.error(`[Service Worker] Failed to cache protocol ${protocol.title}:`, error);
      }
    });
    
    // Also cache all categories (iOS-friendly approach)
    if (data.categories) {
      const categoryPromises = data.categories.map(async category => {
        const url = `/category.php?id=${category.id}`;
        try {
          const response = await fetch(url);
          if (response.ok) {
            // Cache the category page
            await cache.put(new Request(url), response.clone());
            console.log(`[Service Worker] Cached category: ${category.id}`);
          }
        } catch (error) {
          console.error(`[Service Worker] Failed to cache category ${category.id}:`, error);
        }
      });
      
      // Wait for all category promises to resolve
      await Promise.all(categoryPromises);
    }
    
    // Cache the homepage
    try {
      const homeResponse = await fetch('/index.php');
      if (homeResponse.ok) {
        await cache.put(new Request('/index.php'), homeResponse.clone());
        console.log('[Service Worker] Cached homepage');
      }
    } catch (error) {
      console.error('[Service Worker] Failed to cache homepage:', error);
    }
    
    // Wait for all protocol promises to resolve
    await Promise.all(cachePromises);
    
    console.log('[Service Worker] Protocol sync complete');
    
    // Show notification for the update
    return self.registration.showNotification('EMS Protocols Updated', {
      body: `${data.protocols.length} protocols have been updated for offline use`,
      icon: '/assets/icons/android/android-launchericon-192-192.png'
    });
  } catch (error) {
    console.error('[Service Worker] Protocol sync failed:', error);
    throw error;
  }
}