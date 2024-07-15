import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'public/admin-assests/plugins/dropzone/min/dropzone.min.js', 'admin-assests/plugins/dropzone/min/dropzone.min.css'],
            refresh: true,
        }),
    ],
});
