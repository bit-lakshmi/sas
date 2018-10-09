var VERSION = 'v1';

var cacheFirstFiles = [
  './lib/jquery-3.2.1.min.js',
  './lib/materialize.min.css',
  './lib/materialize.min.js',
  './lib/materialdesignicons.min.css',
  './lib/materialdesignicons-webfont.eot',
  './lib/materialdesignicons-webfont.svg',
  './lib/materialdesignicons-webfont.ttf',
  './lib/materialdesignicons-webfont.woff',
  './lib/materialdesignicons-webfont.woff2',
  './lib/Roboto-Bold.woff',
  './lib/Roboto-Bold.woff2',
  './lib/Roboto-Light.woff',
  './lib/Roboto-Light.woff2',
  './lib/Roboto-Medium.woff',
  './lib/Roboto-Medium.woff2',
  './lib/Roboto-Regular.woff',
  './lib/Roboto-Regular.woff2',
  './lib/Roboto-Thin.woff',
  './lib/Roboto-Thin.woff2',
  './imgs/office.jpg',
  './imgs/user.png'
];

var networkFirstFiles = [
  // ADDME: Add paths and URLs to pull from network first. Else fall back to cache if offline. Examples:
  // 'index.html',
  // 'build/build.js',
  // 'css/index.css'
];

// Below is the service worker code.

var cacheFiles = cacheFirstFiles.concat(networkFirstFiles);

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(VERSION).then(cache => {
      return cache.addAll(cacheFiles);
    })
  );
});

self.addEventListener('fetch', event => {
  if (event.request.method !== 'GET') { return; }
  if (networkFirstFiles.indexOf(event.request.url) !== -1) {
    event.respondWith(networkElseCache(event));
  } else if (cacheFirstFiles.indexOf(event.request.url) !== -1) {
    event.respondWith(cacheElseNetwork(event));
  }
  event.respondWith(fetch(event.request));
});

// If cache else network.
// For images and assets that are not critical to be fully up-to-date.
// developers.google.com/web/fundamentals/instant-and-offline/offline-cookbook/
// #cache-falling-back-to-network
function cacheElseNetwork (event) {
  return caches.match(event.request).then(response => {
    function fetchAndCache () {
       return fetch(event.request).then(response => {
        // Update cache.
        caches.open(VERSION).then(cache => cache.put(event.request, response.clone()));
        return response;
      });
    }

    // If not exist in cache, fetch.
    if (!response) { return fetchAndCache(); }

    // If exists in cache, return from cache while updating cache in background.
    fetchAndCache();
    return response;
  });
}

// If network else cache.
// For assets we prefer to be up-to-date (i.e., JavaScript file).
function networkElseCache (event) {
  return caches.match(event.request).then(match => {
    if (!match) { return fetch(event.request); }
    return fetch(event.request).then(response => {
      // Update cache.
      caches.open(VERSION).then(cache => cache.put(event.request, response.clone()));
      return response;
    }) || response;
  });
}
