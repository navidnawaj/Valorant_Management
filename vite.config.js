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
    modulePreload: {
        polyfill: false,
    },
    esbuild: {
        drop: ['console', 'debugger'],
    },
    build: {
        target: 'es2019',
        sourcemap: false,
        cssCodeSplit: true,
        assetsInlineLimit: 4096,
        rollupOptions: {
            output: {
                manualChunks: {
                    alpine: ['alpinejs'],
                },
            },
        },
    },
});
