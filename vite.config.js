import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        chunkSizeWarningLimit: 4000, // Suppress warning - Monaco is expected to be large
        rollupOptions: {
            output: {
                manualChunks: {
                    "monaco-editor": ["monaco-editor"],
                },
            },
        },
    },
});
