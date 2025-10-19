import './bootstrap';
import './image-optimizer';
import './performance-monitor';
import './service-worker-register';

// Lazy load Alpine.js only when needed
const initAlpine = async () => {
    const { default: Alpine } = await import('alpinejs');
    window.Alpine = Alpine;
    Alpine.start();
};

// Performance-aware Alpine initialization
const initializeAlpineWhenNeeded = () => {
    // Check if Alpine components exist on the page before loading
    if (document.querySelector('[x-data]') || document.querySelector('[x-show]') || document.querySelector('[x-if]')) {
        // Check for slow connection and defer if needed
        if (window.PerformanceMonitor?.isSlowConnection()) {
            // Defer Alpine loading on slow connections
            setTimeout(initAlpine, 1000);
        } else {
            initAlpine();
        }
    }
};

// Initialize immediately if components are present
initializeAlpineWhenNeeded();

// Also check after DOM is ready
document.addEventListener('DOMContentLoaded', initializeAlpineWhenNeeded);