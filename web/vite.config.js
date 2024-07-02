import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import {fileURLToPath, URL} from "node:url";
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig(({mode}) => {
    return {
        plugins: [
            visualizer({
                open:false,
                filename: 'stats.html', // Имя файла с визуализацией
            }),
            laravel({
                input: [
                    "resources/js/Admin/admin.js",
                    "resources/scss/admin.scss",
                ],
                refresh: true,
                sourcemap: mode !== 'production',
            }),
            vue({
                template: {
                    version: 3,
                    transformAssetUrls: {
                        base: null,
                        includeAbsolute: false,
                    },
                },
            }),
        ],
        resolve: {
            alias: {
                "vue-i18n": "vue-i18n/dist/vue-i18n.cjs.js",
                "~": fileURLToPath(new URL("resources/js/Admin", import.meta.url)),
            }
        },
        build: {
            rollupOptions: {
                treeshake: true,
                external: "ziggy",
                output: {
                    chunkFileNames: 'js/[name].[hash].js',
                    entryFileNames: 'js/[name].[hash].js',
                    assetFileNames: ({ name }) => {
                        if (/\.css$/.test(name ?? '')) {
                            return 'css/[name].[hash][extname]';
                        }
                        return 'assets/[name].[hash][extname]';
                    },
                },
            },
            chunkSizeWarningLimit: 500,
            sourcemap: mode !== 'production',
        },
        ssr: {
            noExternal: [
                "@kyvg/vue3-notification",
                "laravel-vite-plugin",
                "@inertiajs/server",
                "jquery-nice-select",
                "vue-select",
                "vue-star-rating",
            ],
        },
    }
});
