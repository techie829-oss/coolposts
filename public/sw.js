const CACHE_NAME = 'linkshare-v1.0.0';
const STATIC_CACHE = 'linkshare-static-v1.0.0';
const DYNAMIC_CACHE = 'linkshare-dynamic-v1.0.0';

// Files to cache immediately
const STATIC_FILES = [
    '/',
    '/css/app.css',
    '/js/app.js',
    '/manifest.json',
    '/offline.html',
    '/icons/icon-192x192.png',
    '/icons/icon-512x512.png'
];

// API endpoints to cache
const API_CACHE = [
    '/api/status',
    '/api/links',
    '/api/analytics',
    '/api/earnings'
];

// Install event - cache static files
self.addEventListener('install', (event) => {
    console.log('Service Worker: Installing...');

    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => {
                console.log('Service Worker: Caching static files');
                return cache.addAll(STATIC_FILES);
            })
            .then(() => {
                console.log('Service Worker: Static files cached');
                return self.skipWaiting();
            })
            .catch((error) => {
                console.error('Service Worker: Error caching static files', error);
            })
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    console.log('Service Worker: Activating...');

    event.waitUntil(
        caches.keys()
            .then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => {
                        if (cacheName !== STATIC_CACHE && cacheName !== DYNAMIC_CACHE) {
                            console.log('Service Worker: Deleting old cache', cacheName);
                            return caches.delete(cacheName);
                        }
                    })
                );
            })
            .then(() => {
                console.log('Service Worker: Activated');
                return self.clients.claim();
            })
    );
});

// Fetch event - serve from cache or network
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }

    // Handle API requests
    if (url.pathname.startsWith('/api/')) {
        event.respondWith(handleApiRequest(request));
        return;
    }

    // Handle static assets
    if (isStaticAsset(request)) {
        event.respondWith(handleStaticAsset(request));
        return;
    }

    // Handle HTML pages
    if (request.headers.get('accept').includes('text/html')) {
        event.respondWith(handleHtmlRequest(request));
        return;
    }

    // Default: network first, fallback to cache
    event.respondWith(
        fetch(request)
            .then((response) => {
                // Cache successful responses
                if (response.status === 200) {
                    const responseClone = response.clone();
                    caches.open(DYNAMIC_CACHE)
                        .then((cache) => {
                            cache.put(request, responseClone);
                        });
                }
                return response;
            })
            .catch(() => {
                // Fallback to cache
                return caches.match(request)
                    .then((response) => {
                        if (response) {
                            return response;
                        }

                        // Return offline page for HTML requests
                        if (request.headers.get('accept').includes('text/html')) {
                            return caches.match('/offline.html');
                        }

                        return new Response('Offline', { status: 503 });
                    });
            })
    );
});

// Handle API requests with cache-first strategy
async function handleApiRequest(request) {
    try {
        // Try cache first
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            // Return cached response and update in background
            fetch(request)
                .then((response) => {
                    if (response.status === 200) {
                        const responseClone = response.clone();
                        caches.open(DYNAMIC_CACHE)
                            .then((cache) => {
                                cache.put(request, responseClone);
                            });
                    }
                })
                .catch(() => {
                    // Ignore fetch errors for background updates
                });

            return cachedResponse;
        }

        // If not in cache, fetch from network
        const response = await fetch(request);
        if (response.status === 200) {
            const responseClone = response.clone();
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, responseClone);
        }

        return response;
    } catch (error) {
        // Return cached response if available
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }

        // Return error response
        return new Response(JSON.stringify({ error: 'Offline' }), {
            status: 503,
            headers: { 'Content-Type': 'application/json' }
        });
    }
}

// Handle static assets with cache-first strategy
async function handleStaticAsset(request) {
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
        return cachedResponse;
    }

    try {
        const response = await fetch(request);
        if (response.status === 200) {
            const responseClone = response.clone();
            const cache = await caches.open(STATIC_CACHE);
            cache.put(request, responseClone);
        }
        return response;
    } catch (error) {
        return new Response('Asset not found', { status: 404 });
    }
}

// Handle HTML requests with network-first strategy
async function handleHtmlRequest(request) {
    try {
        const response = await fetch(request);
        if (response.status === 200) {
            const responseClone = response.clone();
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, responseClone);
        }
        return response;
    } catch (error) {
        const cachedResponse = await caches.match(request);
        if (cachedResponse) {
            return cachedResponse;
        }

        return caches.match('/offline.html');
    }
}

// Check if request is for a static asset
function isStaticAsset(request) {
    const url = new URL(request.url);
    return url.pathname.match(/\.(css|js|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot)$/);
}

// Background sync for offline actions
self.addEventListener('sync', (event) => {
    console.log('Service Worker: Background sync', event.tag);

    if (event.tag === 'background-sync') {
        event.waitUntil(doBackgroundSync());
    }
});

// Handle background sync
async function doBackgroundSync() {
    try {
        // Get pending actions from IndexedDB
        const pendingActions = await getPendingActions();

        for (const action of pendingActions) {
            try {
                await processPendingAction(action);
                await removePendingAction(action.id);
            } catch (error) {
                console.error('Error processing pending action:', error);
            }
        }
    } catch (error) {
        console.error('Background sync error:', error);
    }
}

// Push notification handling
self.addEventListener('push', (event) => {
    console.log('Service Worker: Push notification received');

    const options = {
        body: event.data ? event.data.text() : 'New notification from LinkShare',
        icon: '/icons/icon-192x192.png',
        badge: '/icons/icon-72x72.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
        },
        actions: [
            {
                action: 'explore',
                title: 'View Dashboard',
                icon: '/icons/dashboard-96x96.png'
            },
            {
                action: 'close',
                title: 'Close',
                icon: '/icons/close-96x96.png'
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification('LinkShare', options)
    );
});

// Notification click handling
self.addEventListener('notificationclick', (event) => {
    console.log('Service Worker: Notification clicked');

    event.notification.close();

    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/dashboard')
        );
    } else if (event.action === 'close') {
        // Just close the notification
    } else {
        // Default action - open dashboard
        event.waitUntil(
            clients.openWindow('/dashboard')
        );
    }
});

// IndexedDB operations for offline functionality
async function getPendingActions() {
    // This would interact with IndexedDB to get pending actions
    // For now, return empty array
    return [];
}

async function processPendingAction(action) {
    // Process pending action (e.g., create link, update analytics)
    console.log('Processing pending action:', action);
}

async function removePendingAction(actionId) {
    // Remove processed action from IndexedDB
    console.log('Removing pending action:', actionId);
}

// Message handling for communication with main thread
self.addEventListener('message', (event) => {
    console.log('Service Worker: Message received', event.data);

    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data && event.data.type === 'GET_VERSION') {
        event.ports[0].postMessage({ version: CACHE_NAME });
    }
});

// Error handling
self.addEventListener('error', (event) => {
    console.error('Service Worker: Error', event.error);
});

self.addEventListener('unhandledrejection', (event) => {
    console.error('Service Worker: Unhandled rejection', event.reason);
});
