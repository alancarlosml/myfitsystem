// Service Worker para MyFit System - PWA
const CACHE_NAME = 'myfit-v1.0.1';

// URLs estáticas que sempre existem - cache seguro
const CACHE_URLS = [
    '/favicon/android-icon-36x36.png',
    '/favicon/android-icon-48x48.png',
    '/favicon/android-icon-72x72.png',
    '/favicon/android-icon-96x96.png',
    '/favicon/android-icon-144x144.png',
    '/favicon/android-icon-192x192.png',
    '/favicon/android-icon-512x512.png',
    '/offline.html',
];

// Instalação do Service Worker - cache inteligente
self.addEventListener('install', (event) => {
    console.log('Service Worker instalado');
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                // Cache apenas URLs que existem - uma de cada vez para evitar falha
                const cachePromises = CACHE_URLS.map(url => {
                    return cache.add(url).catch(error => {
                        console.warn(`Falhou ao cachear ${url}:`, error);
                        return Promise.resolve(); // Não falha o processo
                    });
                });

                return Promise.all(cachePromises);
            })
            .then(() => {
                console.log('Cache inicial concluído com sucesso');
                return self.skipWaiting();
            })
    );
});

// Ativação do Service Worker
self.addEventListener('activate', (event) => {
    console.log('Service Worker ativado');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Deletando cache antigo:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Interceptando requisições
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Cache First Strategy para assets estáticos
    if (CACHE_URLS.some(cacheUrl => request.url.includes(cacheUrl)) ||
        request.destination === 'style' ||
        request.destination === 'script' ||
        request.destination === 'image') {
        event.respondWith(
            caches.match(request)
                .then((response) => {
                    return response || fetch(request)
                        .then((response) => {
                            return caches.open(CACHE_NAME)
                                .then((cache) => {
                                    cache.put(request, response.clone());
                                    return response;
                                });
                        });
                })
        );
    } else {
        // Network First Strategy para páginas dinâmicas
        event.respondWith(
            fetch(request)
                .then((response) => {
                    if (response.status === 200) {
                        return caches.open(CACHE_NAME)
                            .then((cache) => {
                                cache.put(request, response.clone());
                                return response;
                            });
                    }
                    return response;
                })
                .catch(() => {
                    return caches.match(request)
                        .then((response) => {
                            if (response) {
                                return response;
                            }
                            // Fallback para offline
                            if (request.mode === 'navigate') {
                                return caches.match('/offline.html');
                            }
                        });
                })
        );
    }
});

// Notificações push (opcional)
self.addEventListener('push', (event) => {
    if (!event.data) return;

    const data = event.data.json();
    const options = {
        body: data.body,
        icon: '/favicon/android-icon-192x192.png',
        badge: '/favicon/android-icon-96x96.png',
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: data.primaryKey
        },
        actions: [
            {
                action: 'view',
                title: 'Ver',
            },
            {
                action: 'close',
                title: 'Fechar',
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

// Click notificações
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    if (event.action === 'view') {
        event.waitUntil(
            clients.openWindow(event.notification.data.url || '/app/dashboard')
        );
    }
});

// Background sync (para sincronizar dados quando offline)
self.addEventListener('sync', (event) => {
    if (event.tag === 'background-sync') {
        event.waitUntil(doBackgroundSync());
    }
});

function doBackgroundSync() {
    // Implementar sincronização em background
    console.log('Executando sincronização em background');

    const promises = [];

    // Aqui você poderia:
    // - Sincronizar formulários pendentes
    // - Atualizar dados de cache offline
    // - Enviar analytics pendentes

    return Promise.all(promises);
}

// Periodic sync (Chrome 80+, opcional)
self.addEventListener('periodicsync', (event) => {
    if (event.tag === 'content-sync') {
        event.waitUntil(syncContent());
    }
});

function syncContent() {
    // Sincronizar conteúdo em intervalos regulares
    console.log('Sincronização periódica de conteúdo');
}
