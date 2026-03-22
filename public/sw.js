const CACHE_NAME = 'gestaller-v1';
const OFFLINE_URL = '/login';

// Assets to cache for offline shell
const PRECACHE = [
    '/vendor/bootstrap/css/bootstrap.min.css',
    '/vendor/bootstrap/js/bootstrap.bundle.min.js',
    '/vendor/bootstrap-icons/bootstrap-icons.css',
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => cache.addAll(PRECACHE))
    );
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys =>
            Promise.all(keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k)))
        )
    );
    self.clients.claim();
});

self.addEventListener('fetch', event => {
    // Network-first for HTML pages, cache-first for assets
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request).catch(() => caches.match(OFFLINE_URL))
        );
    } else {
        event.respondWith(
            caches.match(event.request).then(cached => cached || fetch(event.request))
        );
    }
});
