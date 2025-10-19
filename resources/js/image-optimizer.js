// Image optimization and lazy loading utilities
export class ImageOptimizer {
    constructor() {
        this.observer = null;
        this.init();
    }

    init() {
        // Initialize intersection observer for lazy loading
        if ('IntersectionObserver' in window) {
            this.observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.loadImage(entry.target);
                            this.observer.unobserve(entry.target);
                        }
                    });
                },
                {
                    rootMargin: '50px 0px',
                    threshold: 0.01
                }
            );
        }

        // Setup lazy loading for existing images
        this.setupLazyLoading();
        
        // Setup responsive images
        this.setupResponsiveImages();
    }

    setupLazyLoading() {
        const lazyImages = document.querySelectorAll('img[data-src]');
        
        if (this.observer) {
            lazyImages.forEach(img => {
                img.classList.add('lazy-img');
                this.observer.observe(img);
            });
        } else {
            // Fallback for browsers without IntersectionObserver
            lazyImages.forEach(img => this.loadImage(img));
        }
    }

    loadImage(img) {
        const src = img.dataset.src;
        if (src) {
            img.src = src;
            img.classList.add('loaded');
            img.removeAttribute('data-src');
            
            // Add error handling
            img.onerror = () => {
                console.warn(`Failed to load image: ${src}`);
                img.classList.add('error');
            };
        }
    }

    setupResponsiveImages() {
        // Convert large images to use srcset for different screen sizes
        const images = document.querySelectorAll('img');
        
        images.forEach(img => {
            if (img.src && !img.srcset) {
                const src = img.src;
                
                // For very large images, suggest different sizes
                if (img.naturalWidth > 800 || src.includes('1360655.png')) {
                    this.addResponsiveSizes(img);
                }
            }
        });
    }

    addResponsiveSizes(img) {
        // Add loading="lazy" for native lazy loading support
        img.loading = 'lazy';
        
        // Add decoding="async" for better performance
        img.decoding = 'async';
        
        // Add optimization classes
        img.classList.add('img-optimized');
    }

    // Utility to compress images client-side (for user uploads)
    static async compressImage(file, maxWidth = 800, quality = 0.8) {
        return new Promise((resolve) => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();
            
            img.onload = () => {
                // Calculate new dimensions
                let { width, height } = img;
                
                if (width > maxWidth) {
                    height = (height * maxWidth) / width;
                    width = maxWidth;
                }
                
                canvas.width = width;
                canvas.height = height;
                
                // Draw and compress
                ctx.drawImage(img, 0, 0, width, height);
                
                canvas.toBlob(resolve, 'image/jpeg', quality);
            };
            
            img.src = URL.createObjectURL(file);
        });
    }

    // Convert images to WebP format if supported
    static supportsWebP() {
        const canvas = document.createElement('canvas');
        canvas.width = 1;
        canvas.height = 1;
        return canvas.toDataURL('image/webp').indexOf('data:image/webp') === 0;
    }

    // Preload critical images
    static preloadCriticalImages(urls) {
        urls.forEach(url => {
            const link = document.createElement('link');
            link.rel = 'preload';
            link.as = 'image';
            link.href = url;
            document.head.appendChild(link);
        });
    }
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new ImageOptimizer();
});

// Export for manual usage
window.ImageOptimizer = ImageOptimizer;