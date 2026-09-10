import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { globSync } from 'node:fs';

/*
 * Every template under templates/<key>/ ships its own css/app.css and js/app.js.
 * All of them are built so the committed public/build works for whichever
 * template is active on the server (APP_TEMPLATE).
 */
const templateEntries = [
    ...globSync('templates/*/css/app.css'),
    ...globSync('templates/*/js/app.js'),
].sort();

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/admin.css', 'resources/js/admin.js', ...templateEntries],
            refresh: ['resources/views/**', 'templates/*/views/**', 'routes/**'],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
