// Service Worker for caching strategies
const CACHE_NAME = 'valorant-management-v1';
const STATIC_CACHE = 'static-v1';
const DYNAMIC_CACHE = 'dynamic-v1';
const IMAGE_CACHE = 'images-v1';

// Assets to cache immediately
const STATIC_ASSETS = [
    '/',
    '/build/manifest.json',
    // Add critical CSS and JS files here after build
];

// Install event - cache static assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE).then((cache) => {
            return cache.addAll(STATIC_ASSETS);
        })
    );
    self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== STATIC_CACHE && 
                        cacheName !== DYNAMIC_CACHE && 
                        cacheName !== IMAGE_CACHE) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    self.clients.claim();
});

// Fetch event - implement caching strategies
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }

    // Skip Chrome extension requests
    if (url.protocol === 'chrome-extension:') {
        return;
    }

    // Handle different types of requests with appropriate strategies
    if (isStaticAsset(request)) {
        event.respondWith(cacheFirstStrategy(request, STATIC_CACHE));
    } else if (isImage(request)) {
        event.respondWith(cacheFirstStrategy(request, IMAGE_CACHE));
    } else if (isAPIRequest(request)) {
        event.respondWith(networkFirstStrategy(request, DYNAMIC_CACHE));
    } else if (isNavigationRequest(request)) {
        event.respondWith(staleWhileRevalidateStrategy(request, DYNAMIC_CACHE));
    } else {
        event.respondWith(networkFirstStrategy(request, DYNAMIC_CACHE));
    }
});

// Cache-first strategy (good for static assets)
async function cacheFirstStrategy(request, cacheName) {
    try {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }

        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(cacheName);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        console.error('Cache-first strategy failed:', error);
        return new Response('Offline', { status: 503 });
    }
}

// Network-first strategy (good for API requests)
async function networkFirstStrategy(request, cacheName) {
    try {
        const networkResponse = await fetch(request);
        if (networkResponse.ok) {
            const cache = await caches.open(cacheName);
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }
        return new Response('Offline', { status: 503 });
    }
}

// Stale-while-revalidate strategy (good for pages)
async function staleWhileRevalidateStrategy(request, cacheName) {
    const cache = await caches.open(cacheName);
    const cachedResponse = await cache.match(request);

    const fetchPromise = fetch(request).then((networkResponse) => {
        if (networkResponse.ok) {
            cache.put(request, networkResponse.clone());
        }
        return networkResponse;
    }).catch(() => {
        // Network failed, return cached version if available
        return cachedResponse;
    });

    return cachedResponse || fetchPromise;
}

// Helper functions
function isStaticAsset(request) {
    const url = new URL(request.url);
    return url.pathname.startsWith('/build/') ||
           url.pathname.endsWith('.css') ||
           url.pathname.endsWith('.js') ||
           url.pathname.endsWith('.woff') ||
           url.pathname.endsWith('.woff2') ||
           url.pathname.endsWith('.ttf');
}

function isImage(request) {
    const url = new URL(request.url);
    return url.pathname.match(/\.(jpg|jpeg|png|gif|webp|svg|ico)$/i);
}

function isAPIRequest(request) {
    const url = new URL(request.url);
    return url.pathname.startsWith('/api/') || 
           url.pathname.startsWith('/ajax/') ||
           request.headers.get('X-Requested-With') === 'XMLHttpRequest';
}

function isNavigationRequest(request) {
    return request.mode === 'navigate' || 
           (request.method === 'GET' && request.headers.get('accept').includes('text/html'));
}

// Background sync for offline actions
self.addEventListener('sync', (event) => {
    if (event.tag === 'background-sync') {
        event.waitUntil(doBackgroundSync());
    }
});

async function doBackgroundSync() {
    // Implement background sync logic here
    console.log('Background sync triggered');
}