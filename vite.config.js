import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";
import * as path from "path";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        host: true,
        port: 5174,
        strictPort: true,
        hmr: {
            host: 'localhost',
            clientPort: 5174,
            protocol: 'ws',
        },
        watch: {
            usePolling: true,
        }
    },
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "./resources/js"),
        },
    },
});
