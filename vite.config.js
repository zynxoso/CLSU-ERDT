import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin-analytics.css',
                'resources/css/analytics.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
