import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tsconfigPaths from 'vite-tsconfig-paths'
import { config } from 'dotenv-safe'

config({
  allowEmptyValues: true,
  path: '../../.env',
  example: '../../.env.example',
})
// https://github.com/laravel/vite-plugin/pull/57
console.log(`The Vite server should not be accessed directly. Please visit ${process.env.APP_URL} instead.`)
export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/sass/app.scss',
        'resources/js/pages/main.ts',
        'resources/sass/pages/main.scss',
        'resources/js/pages/administration/city-form.ts',
        'resources/js/pages/administration/landmark-form.ts',
        'resources/js/pages/hotel/employee/main.ts',
        'resources/js/pages/hotel/main.ts',
        'resources/js/pages/hotel/edit.ts',
        'resources/js/pages/hotel/notes.ts',
        'resources/js/pages/hotel/show.ts',
        'resources/sass/pages/hotel/show.scss',
        'resources/js/pages/hotel/rooms.ts',
        'resources/sass/pages/hotel/rooms.scss',
        'resources/js/pages/hotel/room-form.ts',
        'resources/sass/pages/hotel/room-form.scss',
        'resources/js/pages/hotel/prices.ts',
        'resources/sass/pages/hotel/prices.scss',
        'resources/js/pages/administration/access-group-form.ts',
        'resources/sass/pages/administration/access-group-form.scss',
        'resources/js/pages/profile.ts',
        'resources/sass/pages/profile.scss',
        'resources/sass/pages/auth.scss',
        'resources/sass/pages/default/grid.scss',
        'resources/sass/pages/default/form.scss',
        'resources/js/pages/filemanager.ts',
        'resources/sass/pages/filemanager.scss',
      ],
      refresh: true,
      transformOnServe: (code, devServerUrl) =>
        // @ts-ignore
        code.replaceAll(`${devServerUrl}/@fs`, devServerUrl),
    }),
    tsconfigPaths(),
  ],
  resolve: {
    alias: {
      '~resources': 'resources',
    },
  },
})
