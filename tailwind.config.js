import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
    
    // Performance optimizations
    corePlugins: {
        // Disable unused core plugins to reduce CSS size
        preflight: true,
    },
    
    // Safelist for dynamic classes
    safelist: [
        // Keep dynamic classes that might be added via JS
        'grid-cols-7',
        { pattern: /^flatpickr/ },
        { pattern: /^toastr/ },
        'lazy-img',
        'loaded',
        'img-optimized',
        'will-change-transform',
        'gpu-accelerated'
    ]
};
