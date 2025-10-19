// Performance monitoring and optimization utilities
export class PerformanceMonitor {
    constructor() {
        this.metrics = {};
        this.init();
    }

    init() {
        // Monitor Core Web Vitals
        this.measureCoreWebVitals();
        
        // Monitor resource loading
        this.monitorResourceLoading();
        
        // Optimize DOM interactions
        this.optimizeDOMInteractions();
        
        // Setup performance observer
        this.setupPerformanceObserver();
    }

    measureCoreWebVitals() {
        // Largest Contentful Paint (LCP)
        if ('PerformanceObserver' in window) {
            const lcpObserver = new PerformanceObserver((entryList) => {
                const entries = entryList.getEntries();
                const lastEntry = entries[entries.length - 1];
                this.metrics.lcp = lastEntry.startTime;
                
                if (lastEntry.startTime > 2500) {
                    console.warn('LCP is poor:', lastEntry.startTime + 'ms');
                }
            });
            
            lcpObserver.observe({ entryTypes: ['largest-contentful-paint'] });
        }

        // First Input Delay (FID) - using First Contentful Paint as proxy
        if ('PerformanceObserver' in window) {
            const fcpObserver = new PerformanceObserver((entryList) => {
                const entries = entryList.getEntries();
                entries.forEach(entry => {
                    if (entry.name === 'first-contentful-paint') {
                        this.metrics.fcp = entry.startTime;
                    }
                });
            });
            
            fcpObserver.observe({ entryTypes: ['paint'] });
        }

        // Cumulative Layout Shift (CLS)
        if ('PerformanceObserver' in window) {
            let clsValue = 0;
            const clsObserver = new PerformanceObserver((entryList) => {
                for (const entry of entryList.getEntries()) {
                    if (!entry.hadRecentInput) {
                        clsValue += entry.value;
                    }
                }
                this.metrics.cls = clsValue;
                
                if (clsValue > 0.1) {
                    console.warn('CLS is poor:', clsValue);
                }
            });
            
            clsObserver.observe({ entryTypes: ['layout-shift'] });
        }
    }

    monitorResourceLoading() {
        window.addEventListener('load', () => {
            // Measure page load time
            const navigation = performance.getEntriesByType('navigation')[0];
            this.metrics.loadTime = navigation.loadEventEnd - navigation.fetchStart;
            
            // Measure resource loading times
            const resources = performance.getEntriesByType('resource');
            const slowResources = resources.filter(resource => 
                resource.duration > 1000 // Resources taking more than 1 second
            );
            
            if (slowResources.length > 0) {
                console.warn('Slow loading resources:', slowResources);
            }
            
            // Log performance metrics
            this.logMetrics();
        });
    }

    optimizeDOMInteractions() {
        // Debounce scroll events
        let scrollTimeout;
        const originalAddEventListener = EventTarget.prototype.addEventListener;
        
        EventTarget.prototype.addEventListener = function(type, listener, options) {
            if (type === 'scroll' && !options?.passive) {
                const debouncedListener = this.debounce(listener, 16); // 60fps
                return originalAddEventListener.call(this, type, debouncedListener, { passive: true, ...options });
            }
            return originalAddEventListener.call(this, type, listener, options);
        };

        // Optimize resize events
        let resizeTimeout;
        window.addEventListener('resize', this.debounce(() => {
            // Trigger custom optimized resize event
            window.dispatchEvent(new CustomEvent('optimizedResize'));
        }, 250), { passive: true });
    }

    setupPerformanceObserver() {
        if ('PerformanceObserver' in window) {
            const observer = new PerformanceObserver((list) => {
                list.getEntries().forEach((entry) => {
                    // Log long tasks (> 50ms)
                    if (entry.entryType === 'longtask') {
                        console.warn('Long task detected:', entry.duration + 'ms');
                    }
                    
                    // Monitor memory usage
                    if (performance.memory) {
                        const memoryInfo = performance.memory;
                        const memoryUsage = memoryInfo.usedJSHeapSize / memoryInfo.totalJSHeapSize;
                        
                        if (memoryUsage > 0.8) {
                            console.warn('High memory usage:', Math.round(memoryUsage * 100) + '%');
                        }
                    }
                });
            });
            
            try {
                observer.observe({ entryTypes: ['longtask', 'measure', 'navigation'] });
            } catch (e) {
                // Some browsers might not support all entry types
                console.log('Performance observer setup with limited support');
            }
        }
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    logMetrics() {
        console.group('Performance Metrics');
        console.log('Load Time:', this.metrics.loadTime + 'ms');
        console.log('LCP:', this.metrics.lcp + 'ms');
        console.log('FCP:', this.metrics.fcp + 'ms');
        console.log('CLS:', this.metrics.cls);
        
        // Performance recommendations
        if (this.metrics.loadTime > 3000) {
            console.warn('💡 Consider optimizing bundle size and lazy loading');
        }
        if (this.metrics.lcp > 2500) {
            console.warn('💡 Consider optimizing largest contentful paint element');
        }
        if (this.metrics.cls > 0.1) {
            console.warn('💡 Consider fixing layout shifts with proper sizing');
        }
        
        console.groupEnd();
    }

    // Utility to measure function performance
    static measure(name, fn) {
        const start = performance.now();
        const result = fn();
        const end = performance.now();
        console.log(`${name} took ${end - start} milliseconds`);
        return result;
    }

    // Utility to detect if user is on a slow connection
    static isSlowConnection() {
        if ('connection' in navigator) {
            const connection = navigator.connection;
            return connection.effectiveType === 'slow-2g' || 
                   connection.effectiveType === '2g' ||
                   connection.saveData === true;
        }
        return false;
    }
}

// Auto-initialize performance monitoring
if (process.env.NODE_ENV !== 'production' || localStorage.getItem('debug-performance')) {
    document.addEventListener('DOMContentLoaded', () => {
        new PerformanceMonitor();
    });
}

// Export for manual usage
window.PerformanceMonitor = PerformanceMonitor;