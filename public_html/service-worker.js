// Enhanced service-worker.js that works consistently across Android and iOS

const CACHE_NAME = 'ems-protocols-v3'; // Incremented version
const OFFLINE_URL = '/offline.html';
const DB_NAME = 'ems-protocols-db';
const DB_VERSION = 1;

// Assets to cache immediately on service worker install
const PRECACHE_ASSETS = [
  '/',
  '/index.php',
  '/offline.html',
  '/assets/js/pwa.js',
  '/manifest.json',
  '/assets/icons/android/android-launchericon-192-192.png',
  '/assets/icons/ios/192.png',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css',
  'https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.30.0/tabler-icons.min.css',
  'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js'
];

// Initialize IndexedDB for more reliable storage
let db;

// Initialize indexedDB for enhanced storage
function initDatabase() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open(DB_NAME, DB_VERSION);
    
    request.onerror = event => {
      console.error("Database error:", event.target.error);
      reject("Could not open database");
    };
    
    request.onupgradeneeded = event => {
      const db = event.target.result;
      
      // Store for protocol pages
      if (!db.objectStoreNames.contains('protocol_pages')) {
        db.createObjectStore('protocol_pages', { keyPath: 'key' });
      }
      
      // Store for metadata
      if (!db.objectStoreNames.contains('metadata')) {
        db.createObjectStore('metadata', { keyPath: 'key' });
      }
    };
    
    request.onsuccess = event => {
      db = event.target.result;
      console.log("Protocol Database initialized");
      resolve(db);
    };
  });
}

// Normalize URL for consistent storage/retrieval
function normalizeUrl(url) {
  try {
    const urlObj = new URL(url, self.location.origin);
    
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

// Store in IndexedDB for reliability
async function storeInDB(url, response) {
  if (!db) return false;
  
  try {
    const key = normalizeUrl(url);
    const clone = response.clone();
    const html = await clone.text();
    
    const transaction = db.transaction(['protocol_pages'], 'readwrite');
    const store = transaction.objectStore('protocol_pages');
    
    const pageData = {
      key: key,
      url: url,
      html: html,
      timestamp: new Date().getTime()
    };
    
    return new Promise((resolve, reject) => {
      const request = store.put(pageData);
      
      request.onsuccess = () => {
        console.log(`Stored in DB: ${key}`);
        resolve(true);
      };
      
      request.onerror = (e) => {
        console.error(`Error storing in DB: ${key}`, e);
        resolve(false);
      };
    });
  } catch (e) {
    console.error("Failed to store in DB:", e);
    return false;
  }
}

// Get page from IndexedDB
async function getFromDB(url) {
  if (!db) return null;
  
  const key = normalizeUrl(url);
  
  return new Promise((resolve) => {
    const transaction = db.transaction(['protocol_pages'], 'readonly');
    const store = transaction.objectStore('protocol_pages');
    const request = store.get(key);
    
    request.onsuccess = (event) => {
      resolve(event.target.result);
    };
    
    request.onerror = () => {
      console.error(`Error retrieving from DB: ${key}`);
      resolve(null);
    };
  });
}

// Install event - cache core assets and initialize DB
self.addEventListener('install', event => {
  console.log('[Service Worker] Installing Service Worker...');
  
  event.waitUntil(
    Promise.all([
      // Initialize database
      initDatabase(),
      
      // Cache precache assets
      caches.open(CACHE_NAME)
        .then(cache => {
          console.log('[Service Worker] Pre-caching assets');
          return cache.addAll(PRECACHE_ASSETS);
        })
    ])
    .then(() => {
      // Force activation
      return self.skipWaiting();
    })
  );
});

// Activate event - clean up old caches and take control
self.addEventListener('activate', event => {
  console.log('[Service Worker] Activating Service Worker...');
  
  event.waitUntil(
    Promise.all([
      // Clean up old caches
      caches.keys().then(cacheNames => {
        return Promise.all(
          cacheNames.map(cache => {
            if (cache !== CACHE_NAME) {
              console.log('[Service Worker] Clearing old cache:', cache);
              return caches.delete(cache);
            }
          })
        );
      }),
      // Take control of all clients immediately
      self.clients.claim()
    ])
  );
});

// Enhanced fetch handler with improved offline support
self.addEventListener('fetch', event => {
  // Skip non-GET requests and cross-origin requests
  if (event.request.method !== 'GET' || 
      (!event.request.url.includes(self.location.origin) && 
       !event.request.url.includes('cdn.jsdelivr.net'))) {
    return;
  }

  // Handle navigation requests specially (HTML pages)
  if (event.request.mode === 'navigate' || 
      (event.request.headers.get('accept') && 
       event.request.headers.get('accept').includes('text/html'))) {
    
    event.respondWith(
      (async () => {
        // Try the network first for fresh content
        try {
          const networkResponse = await fetch(event.request);
          
          // Save successful responses in both cache and IndexedDB for redundancy
          if (networkResponse.ok) {
            const clone = networkResponse.clone();
            
            // Store in regular cache
            const cache = await caches.open(CACHE_NAME);
            cache.put(event.request, networkResponse.clone());
            
            // Also store in IndexedDB for better reliability
            storeInDB(event.request.url, clone);
          }
          
          return networkResponse;
        } catch (error) {
          console.log('[Service Worker] Network request failed, trying cache...');
          
          // First check the cache
          const cachedResponse = await caches.match(event.request);
          if (cachedResponse) {
            return cachedResponse;
          }
          
          // If not in cache, try IndexedDB
          const dbResponse = await getFromDB(event.request.url);
          if (dbResponse) {
            // Create a new response from the stored HTML
            return new Response(dbResponse.html, {
              headers: { 'Content-Type': 'text/html' }
            });
          }
          
          // If specific type of page, try to find similar in DB
          const url = new URL(event.request.url);
          if (url.pathname.endsWith('/protocol.php') || url.pathname.endsWith('/category.php')) {
            const id = url.searchParams.get('id');
            if (id) {
              // The normalized key wasn't found - check other entries
              // by listing all stored pages
              const pageType = url.pathname.endsWith('/protocol.php') ? 'protocol' : 'category';
              const dbKey = `${pageType}:${id}`;
              
              const dbPage = await new Promise(resolve => {
                const transaction = db.transaction(['protocol_pages'], 'readonly');
                const store = transaction.objectStore('protocol_pages');
                const request = store.get(dbKey);
                
                request.onsuccess = (event) => {
                  resolve(event.target.result);
                };
                
                request.onerror = () => {
                  resolve(null);
                };
              });
              
              if (dbPage) {
                return new Response(dbPage.html, {
                  headers: { 'Content-Type': 'text/html' }
                });
              }
            }
          }
          
          // If all else fails, return the offline page
          console.log('[Service Worker] Serving offline page');
          return caches.match(OFFLINE_URL);
        }
      })()
    );
  } else {
    // For all other requests (CSS, JS, images, etc.)
    event.respondWith(
      (async () => {
        // Check cache first for non-HTML resources
        const cachedResponse = await caches.match(event.request);
        if (cachedResponse) {
          return cachedResponse;
        }
        
        // If not in cache, try the network
        try {
          const networkResponse = await fetch(event.request);
          
          // Cache successful responses
          if (networkResponse.ok) {
            const cache = await caches.open(CACHE_NAME);
            cache.put(event.request, networkResponse.clone());
          }
          
          return networkResponse;
        } catch (error) {
          console.error('[Service Worker] Failed to fetch resource:', error);
          
          // For images, return a placeholder
          if (event.request.destination === 'image') {
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
  }
});

// Background sync for updating protocols
self.addEventListener('sync', event => {
  if (event.tag === 'sync-protocols') {
    event.waitUntil(syncProtocols());
  }
});

// Enhanced sync function
async function syncProtocols() {
  console.log('[Service Worker] Syncing protocols data...');
  
  try {
    // Fetch the list of protocols to cache
    const response = await fetch('/api/get_protocols_list.php?sync=1', {
      cache: 'no-store' // Always get fresh data
    });
    
    if (!response.ok) throw new Error('Failed to fetch protocols list');
    
    const data = await response.json();
    
    if (!data.success) throw new Error(data.message || 'Unknown error');
    
    // Open the cache
    const cache = await caches.open(CACHE_NAME);
    
    // Cache the homepage first
    await fetch('/index.php', {cache: 'reload'})
      .then(response => {
        // Cache in both systems
        cache.put(new Request('/index.php'), response.clone());
        return storeInDB('/index.php', response);
      })
      .catch(err => console.error('Failed to cache homepage:', err));
    
    // Cache all protocols
    const protocolPromises = data.protocols.map(async protocol => {
      const url = `/protocol.php?id=${protocol.id}`;
      try {
        const response = await fetch(url, {cache: 'reload'});
        if (response.ok) {
          // Cache in both systems
          cache.put(new Request(url), response.clone());
          await storeInDB(url, response);
          console.log(`[Service Worker] Cached protocol: ${protocol.title}`);
        }
      } catch (error) {
        console.error(`[Service Worker] Failed to cache protocol ${protocol.title}:`, error);
      }
    });
    
    // Cache all categories
    const categoryPromises = data.categories.map(async category => {
      const url = `/category.php?id=${category.id}`;
      try {
        const response = await fetch(url, {cache: 'reload'});
        if (response.ok) {
          // Cache in both systems
          cache.put(new Request(url), response.clone());
          await storeInDB(url, response);
          console.log(`[Service Worker] Cached category: ${category.id}`);
        }
      } catch (error) {
        console.error(`[Service Worker] Failed to cache category ${category.id}:`, error);
      }
    });
    
    // Wait for all promises to resolve
    await Promise.all([...protocolPromises, ...categoryPromises]);
    
    // Update metadata
    if (db) {
      const transaction = db.transaction(['metadata'], 'readwrite');
      const store = transaction.objectStore('metadata');
      store.put({ key: 'lastUpdate', timestamp: Date.now() });
    }
    
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