import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import vuetify from 'vite-plugin-vuetify'
import path from 'path'
import fs from 'fs';

const host = 'https://abz-test-assignment.vikinglingo.online/';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        vuetify({ autoImport: true })
    ],

    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            '@': path.resolve(__dirname, './resources/js'),
        },
    },

    build: {
        base: process.env.NODE_ENV === 'production' ? '/build/' : '/',
    },
    server: {
        host,
        hmr: {host},
        https: {
            kkey: fs.readFileSync('/etc/ssl/private/my-app.key'),
            cert: fs.readFileSync('/etc/ssl/certs/my-app.crt'),
        },
    },
})
