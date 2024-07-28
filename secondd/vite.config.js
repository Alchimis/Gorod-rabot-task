import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/search.css', 'resources/js/app.js', 'resources/js/load.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 3000,
        open: false,
	hmr: {
		 host: 'localhost'
	}
    },
});
