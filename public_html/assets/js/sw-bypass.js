// Modified service-worker.js with aggressive cache busting
// At the top of service-worker.js
const CACHE_NAME = 'ems-protocols-v2-' + Date.now(); // Force a new cache name every time
const OFFLINE_URL = '/offline.html';

// Log to console for debugging
console.log('Service Worker initializing with cache:', CACHE_NAME);

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
const API_CACHE_NAME = 'ems-api-cache-v1-' + Date.now();
const API_PATHS = [
  '/api/get_section.php',
  '/api/get_branches.php',
  '/api/get_branch.php',
  '/api/get_protocols_list.php'
];

// Add timestamps to URLs to force reload
function addTimestamp(url) {
  try {
    const urlObj = new URL(url, self.location.origin);
    urlObj.searchParams.set('_sw', Date.now());
    return urlObj.toString();
  } catch (e) {
    return url;
  }
}

// Install event - clear previous caches and cache core assets
self.addEventListener('install', event => {
  console.log('[Service Worker] Installing new Service Worker...');
  
  event.waitUntil(
    caches.keys().then(cacheNames => {
      // Delete all existing caches
      return Promise.all(
        cacheNames.map(cacheName => {
          console.log('[Service Worker] Deleting old cache:', cacheName);
          return caches.delete(cacheName);
        })
      ).then(() => {
        // Now cache the new assets
        console.log('[Service Worker] Pre-caching assets with timestamp parameters');
        return caches.open(CACHE_NAME).then(cache => {
          // Add timestamps to all URLs to prevent caching
          const timestampedAssets = PRECACHE_ASSETS.map(url => {
            // Don't add timestamps to external CDN resources
            if (url.includes('cdn.jsdelivr.net')) {
              return url;
            }
            return addTimestamp(url);
          });
          return cache.addAll(timestampedAssets);
        });
      }).then(() => {
        // Force activation
        console.log('[Service Worker] Skipping waiting to activate immediately');
        return self.skipWaiting();
      });
    })
  );
});

// Activate event - clean up old caches and take control immediately
self.addEventListener('activate', event => {
  console.log('[Service Worker] Activating Service Worker...');
  
  event.waitUntil(
    Promise.all([
      // Take control of all clients immediately (important for iOS)
      self.clients.claim().then(() => {
        console.log('[Service Worker] Claimed all clients');
      })
    ])
  );
});

// Always use network-first strategy for search.php and admin pages
self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);
  
  // Always get search.php and admin pages from network
  if (url.pathname.endsWith('/search.php') || url.pathname.includes('/admin/')) {
    console.log('[Service Worker] Network-first for:', url.pathname);
    
    event.respondWith(
      fetch(event.request)
        .catch(error => {
          console.error('[Service Worker] Network fetch failed for:', url.pathname, error);
          return caches.match('/offline.html');
        })
    );
    return;
  }
  
  // For all other requests, try network then cache
  event.respondWith(
    fetch(event.request)
      .then(response => {
        // Cache the response for future use
        const responseToCache = response.clone();
        caches.open(CACHE_NAME).then(cache => {
          cache.put(event.request, responseToCache);
        });
        return response;
      })
      .catch(error => {
        console.log('[Service Worker] Falling back to cache for:', url.pathname);
        return caches.match(event.request)
          .then(cachedResponse => {
            if (cachedResponse) {
              return cachedResponse;
            }
            // If not in cache, try the offline page
            return caches.match('/offline.html');
          });
      })
  );
});

// Listen for messages from the main thread
self.addEventListener('message', event => {
  if (event.data && event.data.action === 'skipWaiting') {
    self.skipWaiting();
  }
});