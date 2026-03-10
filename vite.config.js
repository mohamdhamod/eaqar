

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig(({ mode }) => {
    return {
        build: {
            chunkSizeWarningLimit: 2000,
            rollupOptions: {
                output: {
                    manualChunks: {
                        vendor: [
                            'jquery',
                            'bootstrap',
                            'datatables.net',
                            'datatables.net-bs5',
                            'datatables.net-buttons',
                            'datatables.net-buttons-bs5',
                            'jszip',
                            'pdfmake',
                        ],
                    },
                },
            },
        },
        plugins: [
            laravel({
                input: [
                    'resources/js/app.js',
                    'resources/js/properties.js',
                    'resources/css/app.css',
                ],
                refresh: true,
            }),
        ],
    };
});
