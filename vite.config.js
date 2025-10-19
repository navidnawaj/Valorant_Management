import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        // Enable code splitting
        rollupOptions: {
            output: {
                manualChunks: {
                    // Split vendor libraries into separate chunks
                    'vendor': ['alpinejs', 'axios'],
                    'flatpickr': ['flatpickr']
                },
                // Optimize chunk naming for better caching
                chunkFileNames: 'assets/[name]-[hash].js',
                entryFileNames: 'assets/[name]-[hash].js',
                assetFileNames: 'assets/[name]-[hash].[ext]'
            }
        },
        // Optimize chunk size warnings
        chunkSizeWarningLimit: 1000,
        // Enable minification with optimized settings
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
                pure_funcs: ['console.log', 'console.info'],
                passes: 2
            },
            mangle: {
                safari10: true
            },
            format: {
                comments: false
            }
        },
        // Disable source maps for production
        sourcemap: false,
        // Optimize CSS
        cssCodeSplit: true,
        // Enable asset inlining for small files
        assetsInlineLimit: 4096,
        // Target modern browsers for smaller bundles
        target: ['es2020', 'chrome80', 'firefox78', 'safari14', 'edge88'],
        // Enable module preload polyfill
        modulePreload: {
            polyfill: true
        }
    },
    // Enable CSS preprocessing optimizations
    css: {
        devSourcemap: false,
        preprocessorOptions: {
            css: {
                charset: false
            }
        }
    },
    // Optimize dependencies
    optimizeDeps: {
        include: ['alpinejs', 'axios'],
        exclude: ['flatpickr'] // Lazy load this
    },
    // Enable experimental features for better performance
    experimental: {
        renderBuiltUrl(filename, { hostType }) {
            if (hostType === 'js') {
                return { js: `/${filename}` };
            } else {
                return { relative: true };
            }
        }
    },
    // Server configuration for development
    server: {
        hmr: {
            overlay: false // Disable error overlay for better performance
        }
    }
});
