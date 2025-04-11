import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

                'resources/css/manage_event.css',
                'resources/css/admin.css',

                'resources/js/animated_bg.js',
                'resources/js/manage_event.js',
                'resources/js/view_calendar.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        port: 5173,
        host: '0.0.0.0',
        hmr: {
            host: 'localhost'
        },
    }
});
