// Service Worker Registration and Management
export class ServiceWorkerManager {
    constructor() {
        this.swRegistration = null;
        this.init();
    }

    async init() {
        if ('serviceWorker' in navigator) {
            try {
                await this.registerServiceWorker();
                this.setupUpdateNotifications();
                this.setupBackgroundSync();
            } catch (error) {
                console.warn('Service Worker registration failed:', error);
            }
        }
    }

    async registerServiceWorker() {
        try {
            this.swRegistration = await navigator.serviceWorker.register('/sw.js', {
                scope: '/'
            });

            console.log('Service Worker registered successfully');

            // Handle service worker updates
            this.swRegistration.addEventListener('updatefound', () => {
                const newWorker = this.swRegistration.installing;
                
                newWorker.addEventListener('statechange', () => {
                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                        // New service worker is available
                        this.showUpdateNotification();
                    }
                });
            });

        } catch (error) {
            console.error('Service Worker registration failed:', error);
            throw error;
        }
    }

    setupUpdateNotifications() {
        // Listen for service worker updates
        navigator.serviceWorker.addEventListener('controllerchange', () => {
            // Service worker has been updated and is now controlling the page
            window.location.reload();
        });
    }

    setupBackgroundSync() {
        // Register background sync for offline actions
        if ('serviceWorker' in navigator && 'sync' in window.ServiceWorkerRegistration.prototype) {
            navigator.serviceWorker.ready.then((registration) => {
                // Register for background sync when needed
                this.backgroundSyncRegistration = registration;
            });
        }
    }

    showUpdateNotification() {
        // Create a simple update notification
        const notification = document.createElement('div');
        notification.innerHTML = `
            <div style="
                position: fixed;
                top: 20px;
                right: 20px;
                background: #4f46e5;
                color: white;
                padding: 16px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                max-width: 300px;
                font-family: system-ui, sans-serif;
            ">
                <div style="font-weight: 600; margin-bottom: 8px;">
                    App Update Available
                </div>
                <div style="font-size: 14px; margin-bottom: 12px;">
                    A new version is available. Refresh to get the latest features.
                </div>
                <button onclick="window.location.reload()" style="
                    background: white;
                    color: #4f46e5;
                    border: none;
                    padding: 8px 16px;
                    border-radius: 4px;
                    font-weight: 600;
                    cursor: pointer;
                    margin-right: 8px;
                ">
                    Refresh
                </button>
                <button onclick="this.parentElement.parentElement.remove()" style="
                    background: transparent;
                    color: white;
                    border: 1px solid rgba(255,255,255,0.3);
                    padding: 8px 16px;
                    border-radius: 4px;
                    cursor: pointer;
                ">
                    Later
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Auto-remove after 10 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 10000);
    }

    // Method to trigger background sync
    async triggerBackgroundSync(tag = 'background-sync') {
        if (this.backgroundSyncRegistration) {
            try {
                await this.backgroundSyncRegistration.sync.register(tag);
                console.log('Background sync registered');
            } catch (error) {
                console.error('Background sync registration failed:', error);
            }
        }
    }

    // Method to check if app is running in standalone mode (PWA)
    static isStandalone() {
        return window.matchMedia('(display-mode: standalone)').matches ||
               window.navigator.standalone === true;
    }

    // Method to prompt for app installation
    static setupInstallPrompt() {
        let deferredPrompt;

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            // Show install button/banner
            const installButton = document.getElementById('install-app-button');
            if (installButton) {
                installButton.style.display = 'block';
                installButton.addEventListener('click', async () => {
                    if (deferredPrompt) {
                        deferredPrompt.prompt();
                        const { outcome } = await deferredPrompt.userChoice;
                        console.log(`User ${outcome} the install prompt`);
                        deferredPrompt = null;
                        installButton.style.display = 'none';
                    }
                });
            }
        });

        window.addEventListener('appinstalled', () => {
            console.log('App was installed');
            deferredPrompt = null;
        });
    }
}

// Auto-initialize service worker
document.addEventListener('DOMContentLoaded', () => {
    new ServiceWorkerManager();
    ServiceWorkerManager.setupInstallPrompt();
});

// Export for manual usage
window.ServiceWorkerManager = ServiceWorkerManager;