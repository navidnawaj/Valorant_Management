export default {
    plugins: {
        tailwindcss: {},
        autoprefixer: {},
        // Add CSS optimization plugins for production
        ...(process.env.NODE_ENV === 'production' && {
            cssnano: {
                preset: ['default', {
                    discardComments: {
                        removeAll: true,
                    },
                    normalizeWhitespace: true,
                    reduceIdents: false, // Keep this false to avoid breaking CSS variables
                    zindex: false, // Keep this false to avoid z-index conflicts
                }]
            }
        })
    },
}